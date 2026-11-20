<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use amePersistentModule;
use ameUtils;
use YahnisElsts\AdminMenuEditor\Customizable\Controls\InterfaceStructure;
use YahnisElsts\AdminMenuEditor\Customizable\Schemas\SchemaFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\ModuleSettings;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\Slot;
use YahnisElsts\AdminMenuEditor\Dashboard\PageActions\ResponseInterface;
use YahnisElsts\AdminMenuEditor\Dashboard\PageActions\SettingsUpdateAction;
use YahnisElsts\AdminMenuEditor\Dashboard\PageActions\UpdateSuccessResponse;
use YahnisElsts\AdminMenuEditor\Options\Option;
use YahnisElsts\AdminMenuEditor\Utils\LazyValue;
use function YahnisElsts\AdminMenuEditor\Collections\w;

class ameTweakManager extends amePersistentModule {
	const APPLY_TWEAK_AUTO = 'auto';
	const APPLY_TWEAK_MANUALLY = 'manual';

	const BASIC_TWEAK_PROPERTIES = ['id' => true, 'enabledForActor' => true];

	const FEATURE_DATA_KEY = 'featureData';

	const SETTINGS_FORM_ACTION = 'ame-save-tweak-settings';

	protected $optionName = 'ws_ame_tweak_settings';

	protected TweakRegistrator $registrator;
	protected TweakCollection $collection;
	protected TweakApplier $applier;

	private ameAdminCssTweakManager $adminCssManager;

	/**
	 * @var null|array
	 */
	private ?array $cachedEnabledTweakSettings = null;

	/**
	 * @var callable[]
	 */
	private array $tweakBuilders = [];

	/**
	 * @var FeatureManager[]
	 */
	private array $features = [];
	/**
	 * @var FeatureManager[]
	 */
	private array $featuresWithStorage = [];

	private bool $earlyInitDone = false;

	/**
	 * @var LazyValue<SettingsUpdateAction>
	 */
	private LazyValue $settingsUpdateAction;

	public function __construct($menuEditor) {
		$this->settingsWrapperEnabled = true;
		parent::__construct($menuEditor, dirname(__DIR__));

		$this->initFeatures();
		$this->initProFeatures();

		$this->adminCssManager = new ameAdminCssTweakManager();
		$this->tweakBuilders['admin-css'] = [$this->adminCssManager, 'createTweak'];

		$this->applier = new TweakApplier($this);
		$this->collection = new TweakCollection($this->applier);
		$this->registrator = new TweakRegistrator($this->collection, $this, [
			$this,
			'getDefaultTweakFiles',
		], $this->tweakBuilders);

		//Some tweaks may need to run early, before the "init" hook. For example, the tweak that disables
		//admin menu customizations affects virtual caps, and some plugins trigger cap checks very early.
		//The earliest we can process tweaks is on "set_current_user" because we need to know the current
		//user to figure out which tweaks to apply.
		if ( did_action('set_current_user') ) {
			$this->earlyInit();
		} else {
			add_action('set_current_user', [$this, 'earlyInit'], 1);
		}

		add_action('init', [$this, 'onInit'], PHP_INT_MAX - 1000);

		$this->settingsUpdateAction = $this->pushSettingsUpdater(fn() => $this->createSettingsUpdater());
	}

	public function earlyInit() {
		//"set_current_user" can potentially be triggered multiple times, so we need to make sure
		//we only run this once.
		if ( $this->earlyInitDone ) {
			return;
		}

		$userAvailable = function_exists('is_user_logged_in') && is_user_logged_in();
		if ( !$userAvailable ) {
			return;
		}

		$this->registrator->registerEarlyTweaks();
		$this->applier->processQueuedTweaks(TweakApplier::STAGE_EARLY);
	}

	public function getDefaultTweakFiles(): array {
		//Extension point for subclasses to add more default tweaks.
		return [__DIR__ . '/../default-tweaks.php'];
	}

	public function getTabSlug(): ?string {
		$tabs = $this->getTabs();
		foreach ($tabs as $tab) {
			return $tab->getSlug();
		}
		return null;
	}

	public function onInit() {
		$this->registrator->registerTweaks($this->features);
		$this->applier->processQueuedTweaks(
			did_action('current_screen') ? TweakApplier::STAGE_SCREEN : TweakApplier::STAGE_INIT
		);
	}

	/**
	 * Get settings associated with tweaks that are enabled for the current user.
	 */
	public function getEnabledTweakSettings(): ?array {
		if ( $this->cachedEnabledTweakSettings !== null ) {
			return $this->cachedEnabledTweakSettings;
		}

		$results = [];

		$currentUser = wp_get_current_user();
		if ( $currentUser ) {
			$roles = $this->menuEditor->get_user_roles($currentUser);
			$isSuperAdmin = is_multisite() && is_super_admin($currentUser->ID);

			//Load standard tweak settings.
			$standardSettings = ameUtils::get($this->loadSettings(), 'tweaks');
			if ( !is_array($standardSettings) ) {
				$standardSettings = [];
			}
			$settingCollections = [$standardSettings];

			//Load tweak settings from features that have their own storage.
			foreach ($this->featuresWithStorage as $featureManager) {
				if ( $featureManager->needsStorage() ) {
					$settingCollections[] = $featureManager->getTweakSettings();
				}
			}

			//Keep only the tweaks that are enabled for the current user.
			foreach ($settingCollections as $settings) {
				foreach ($settings as $id => $tweakSettings) {
					$enabledForActor = ameUtils::get($tweakSettings, 'enabledForActor', []);
					if ( !$this->appliesToUser($enabledForActor, $currentUser, $roles, $isSuperAdmin) ) {
						continue;
					}

					$results[$id] = $tweakSettings;
				}
			}
		}

		$this->cachedEnabledTweakSettings = $results;
		return $results;
	}

	/**
	 * @param array $enabledForActor
	 * @param \WP_User $user
	 * @param array $roles
	 * @param bool $isSuperAdmin
	 * @return bool
	 */
	private function appliesToUser(
		array    $enabledForActor,
		\WP_User $user,
		array    $roles,
		bool     $isSuperAdmin = false
	): bool {
		//User-specific settings have priority over everything else.
		$userActor = 'user:' . $user->user_login;
		if ( isset($enabledForActor[$userActor]) ) {
			return $enabledForActor[$userActor];
		}

		//The "Super Admin" flag has priority over regular roles.
		if ( $isSuperAdmin && isset($enabledForActor['special:super_admin']) ) {
			return $enabledForActor['special:super_admin'];
		}

		//If it's enabled for any role, it's enabled for the user.
		foreach ($roles as $role) {
			if ( !empty($enabledForActor['role:' . $role]) ) {
				return true;
			}
		}

		//By default, all tweaks are disabled.
		return false;
	}

	public function getCollection(): TweakCollection {
		return $this->collection;
	}

	protected function createSettingsUpdater(): SettingsUpdateAction {
		return SettingsUpdateAction::builder(self::SETTINGS_FORM_ACTION, $this)
			->handler([$this, 'handleSettingsUpdate'])
			->permissionsCallback([$this, 'userCanAccessModule'])
			->build();
	}

	public function handleSettingsUpdate(array $payload): ResponseInterface {
		$submittedSettings = $payload;

		$submittedTweaks = w($submittedSettings['tweaks'])
			//To save space, filter out tweaks that are not enabled for anyone and have no other settings.
			//Most tweaks only have an "enabledForActor" property (previously also "id").
			->filterValues(function ($settings) {
				$additionalProperties = array_diff_key($settings, $this::BASIC_TWEAK_PROPERTIES);
				return !empty($settings['enabledForActor']) || !empty($additionalProperties);
			})
			//User-defined tweaks must have a type.
			->filterValues(function ($settings) {
				return empty($settings['isUserDefined']) || !empty($settings['typeId']);
			});

		//TODO: Give other components an opportunity to validate and sanitize tweak settings. E.g. a filter.
		//Sanitize CSS with FILTER_SANITIZE_FULL_SPECIAL_CHARS if unfiltered_html is not enabled. Always strip </style>.

		//Build a lookup array of user-defined tweaks so that we can register them later
		//without iterating through the entire list.
		$userDefinedTweakIds = $submittedTweaks
			->filterValues(fn($props) => !empty($props['isUserDefined']))
			->mapValues('__return_true');

		//We use an incrementing suffix to ensure each user-defined tweak gets a unique ID.
		$lastUserTweakSuffix = ameUtils::get($this->loadSettings(), 'lastUserTweakSuffix', 0);
		$newSuffix = ameUtils::get($submittedSettings, 'lastUserTweakSuffix', 0);
		if ( is_scalar($newSuffix) && is_numeric($newSuffix) ) {
			$newSuffix = max(intval($newSuffix), 0);
			if ( $newSuffix < 10000000 ) {
				$lastUserTweakSuffix = $newSuffix;
			}
		}

		//Validate feature data.
		$submittedFeatureData = ameUtils::get($submittedSettings, self::FEATURE_DATA_KEY, []);
		$newFeatureData = $this->settings[self::FEATURE_DATA_KEY] ?? [];
		$featureDataSetting = $this->loadSettings()->getSetting(self::FEATURE_DATA_KEY);
		if ( $featureDataSetting ) {
			$sanitizedValue = $featureDataSetting->validate(new \WP_Error(), $submittedFeatureData);
			if ( is_wp_error($sanitizedValue) ) {
				wp_die(
					esc_html('Error validating feature data: ' . implode(', ', $sanitizedValue->get_error_messages()))
				);
			} elseif ( is_array($newFeatureData) ) {
				$newFeatureData = $sanitizedValue;
			}
		}

		$this->settings['tweaks'] = $submittedTweaks->toArray();
		$this->settings['userDefinedTweaks'] = $userDefinedTweakIds->toArray();
		$this->settings[self::FEATURE_DATA_KEY] = $newFeatureData;
		$this->settings['lastUserTweakSuffix'] = $lastUserTweakSuffix;
		$this->saveSettings();

		return new UpdateSuccessResponse();
	}

	public function getSettingsUpdateAction(): SettingsUpdateAction {
		return $this->settingsUpdateAction->get();
	}

	public function userCanAccessModule(): bool {
		return (
			current_user_can('edit_theme_options')

			//The following check is currently redundant because AME core already checks this when
			//loading any settings page tab. It's only included here in case some future code calls
			//this method from outside the context of a settings page.
			&& $this->menuEditor->current_user_can_edit_menu()
		);
	}

	public function setTweakSettings($tweakSettings) {
		$this->loadSettings();
		$this->settings['tweaks'] = $tweakSettings;
		$this->saveSettings();
	}

	public function createSettingInstances(ModuleSettings $settings): array {
		$f = $settings->settingFactory();
		$s = new SchemaFactory();
		$parentSettings = parent::createSettingInstances($settings);

		$basePerTweakSettingsFields = self::createBaseTweakSettingsFields($s);

		$perTweakSettingsSchemas = [];
		foreach ($this->collection->getTweaks() as $tweak) {
			$perTweakSettingsSchemas[$tweak->getId()] = $s->struct(array_merge(
				$basePerTweakSettingsFields,
				$tweak->getSettingsSchemaFields($s)
			));
		}

		$featureDataFields = [];
		foreach ($this->features as $feature) {
			if ( $feature->needsStorage() ) {
				$featureSchema = $feature->getStorageSchema($s);
				if ( $featureSchema ) {
					$featureDataFields[$feature->getManagerId()] = $featureSchema;
				}
			}
		}

		return array_merge($parentSettings, $f->buildSettings([
			'tweaks'               => $s->struct($perTweakSettingsSchemas),
			'userDefinedTweaks'    => $s->record(
				$s->string()->min(1),
				$s->boolean()
			),
			self::FEATURE_DATA_KEY => $s->struct($featureDataFields),
			'lastUserTweakSuffix'  => $s->number()->min(0)->defaultValue(0),
			'configFormatVersion'  => $s->number()->min(1)->defaultValue(1),
		]));
	}

	public static function createBaseTweakSettingsFields(SchemaFactory $s): array {
		return [
			'enabledForActor' => $s->actorFeatureMap()->settingParams(['deleteWhenBlank' => true]),
			'isUserDefined'   => $s->boolean()->defaultValue(null)->settingParams(['deleteWhenBlank' => true]),
			'typeId'          => $s->string()->defaultValue(null)->settingParams(['deleteWhenBlank' => true]),
		];
	}

	public function getInterfaceStructure(): InterfaceStructure {
		$packageOption = $this->getFirstTab()->flatMap(function ($tab) {
			if ( !($tab instanceof TweaksTab) ) {
				return Option::none();
			}

			$package = $tab->getInterfacePackage();
			if ( $package instanceof TweaksUiPackage ) {
				return Option::some($package->getInterfaceStructure());
			}
			return Option::none();
		});

		if ( $packageOption->isDefined() ) {
			return $packageOption->get();
		}
		throw new \RuntimeException('Could not get interface structure from TweaksTab.');
	}

	protected function initProFeatures() {
		//This will be overridden in the Pro version to initialize Pro tweak managers.
	}

	protected function initFeatures() {

	}

	protected function addFeatures(array $features) {
		foreach ($features as $feature) {
			$this->features[$feature->getManagerId()] = $feature;
		}

		foreach ($this->features as $featureManager) {
			if ( $featureManager->needsStorage() ) {
				$this->featuresWithStorage[$featureManager->getManagerId()] = $featureManager;
			}
		}
	}

	/**
	 * @return FeatureManager[]
	 */
	public function getFeatures(): array {
		return $this->features;
	}

	/**
	 * @var array<string, Slot>
	 */
	private array $featureDataSlots = [];

	protected function getFeatureDataFor(FeatureManager $featureManager): ?Slot {
		if ( !$featureManager->needsStorage() ) {
			return null;
		}

		$managerId = $featureManager->getManagerId();
		if ( !isset($this->featureDataSlots[$managerId]) ) {
			$settings = $this->loadSettings();
			$store = $settings->getStore();
			$this->featureDataSlots[$managerId] = $store->buildSlot(
				$this->getFeatureSettingPath($featureManager)
			);
		}
		return $this->featureDataSlots[$managerId];
	}

	protected function getFeatureSettingPath(FeatureManager $featureManager): array {
		return [self::FEATURE_DATA_KEY, $featureManager->getManagerId()];
	}

	protected function getFeatureSettingPrefix(FeatureManager $featureManager): string {
		return implode('.', $this->getFeatureSettingPath($featureManager));
	}

	protected function createTabs(): array {
		return [new TweaksTab($this->menuEditor, $this)];
	}
}