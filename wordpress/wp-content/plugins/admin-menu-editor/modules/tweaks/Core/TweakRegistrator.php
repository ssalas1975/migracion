<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

class TweakRegistrator {
	private bool $earlyTweaksRegistered = false;

	private TweakCollection $collection;

	private ameTweakManager $tweakManager;
	/**
	 * @var callable
	 */
	private $getDefaultTweakFilesCallback;

	private array $defaultTweakFactories = [];
	/**
	 * @var array<string, callable(array): ?ameBaseTweak>
	 */
	private array $tweakBuilders;

	public function __construct(
		TweakCollection $collection,
		ameTweakManager $tweakManager,
		callable        $getDefaultTweakFilesCallback,
		array $tweakBuilders = []
	) {
		$this->collection = $collection;
		$this->tweakManager = $tweakManager;
		$this->getDefaultTweakFilesCallback = $getDefaultTweakFilesCallback;
		$this->tweakBuilders = $tweakBuilders;
	}

	public function registerEarlyTweaks() {
		if ( $this->earlyTweaksRegistered ) {
			return;
		}

		$this->collection->addSection('general', 'General');

		$tweakData = $this->loadDefaultTweakData();

		foreach (\ameUtils::get($tweakData, 'sections', []) as $id => $section) {
			$this->collection->addSectionInstance(ameTweakSection::fromDefinition($id, $section));
		}

		$defaultTweaks = \ameUtils::get($tweakData, 'tweaks', []);
		$this->addDefaultTweaks($this->filterTweaksToRegister($defaultTweaks));

		if ( !empty($tweakData['definitionFactories']) ) {
			$this->defaultTweakFactories = array_merge($this->defaultTweakFactories, $tweakData['definitionFactories']);
		}

		$this->earlyTweaksRegistered = true;
	}

	/**
	 * @param array<FeatureManager> $features
	 * @return void
	 */
	public function registerTweaks(array $features) {
		$this->registerEarlyTweaks();

		foreach ($this->defaultTweakFactories as $factoryCallback) {
			$tweakDefinitions = call_user_func($factoryCallback);
			$this->addDefaultTweaks($this->filterTweaksToRegister($tweakDefinitions));
		}

		$tweakFilter = $this->getTweakRegistrationFilter();

		foreach ($features as $featureManager) {
			$featureManager->registerTweaks($this->collection, $tweakFilter);
		}

		do_action('admin-menu-editor-register_tweaks', $this->collection, $tweakFilter);

		//Register user-defined tweaks.
		$settings = $this->tweakManager->loadSettings();
		$userDefinedTweakIds = \ameUtils::get($settings, 'userDefinedTweaks', []);
		if ( !empty($userDefinedTweakIds) ) {
			$tweakSettings = $settings['tweaks'] ?? [];
			foreach ($userDefinedTweakIds as $id => $unused) {
				if ( !isset($tweakSettings[$id]['typeId']) ) {
					continue;
				}
				$properties = $tweakSettings[$id];
				if ( isset($this->tweakBuilders[$properties['typeId']]) ) {
					$properties['id'] = $id;
					$tweak = call_user_func($this->tweakBuilders[$properties['typeId']], $properties);
					if ( $tweak ) {
						$this->collection->addTweak($tweak);
					}
				}
			}
		}
	}

	protected function loadDefaultTweakData(): array {
		//Register Pro feature placeholders for the free version, but only in the "Tweaks" tab.
		//They're just for display purposes, so there's no need to run them on other pages.
		//The variable is read in default-tweaks.php.
		$twmPlaceholdersEnabled = $this->isTweaksTab() && (
				!(apply_filters('admin_menu_editor_is_pro', false))
				|| (defined('AME_TEST_MODE') && constant('AME_TEST_MODE'))
			);

		$data = [
			'sections'            => [],
			'tweaks'              => [],
			'definitionFactories' => [],
		];
		$defaultTweakFiles = call_user_func($this->getDefaultTweakFilesCallback);
		foreach ($defaultTweakFiles as $file) {
			if ( file_exists($file) ) {
				$fileData = require($file);
				$data['sections'] = array_merge($data['sections'], \ameUtils::get($fileData, 'sections', []));
				$data['tweaks'] = array_merge($data['tweaks'], \ameUtils::get($fileData, 'tweaks', []));
				if ( !empty($fileData['definitionFactories']) ) {
					$data['definitionFactories'] = array_merge(
						$data['definitionFactories'],
						$fileData['definitionFactories']
					);
				}
			}
		}
		return $data;
	}

	private function addDefaultTweaks($defaultTweaks) {
		foreach ($defaultTweaks as $id => $properties) {
			if ( isset($properties['selector']) ) {
				$tweak = new ameHideSelectorTweak(
					$id,
					$properties['label'] ?? null,
					$properties['selector']
				);

				if ( isset($properties['screens']) ) {
					$tweak->setScreens($properties['screens']);
				}
			} else if ( isset($properties['className']) ) {
				if ( isset($properties['includeFile']) ) {
					require_once $properties['includeFile'];
				}

				$className = $properties['className'];
				$tweak = new $className(
					$id,
					$properties['label'] ?? null,
					$properties
				);
			} else if ( isset($properties['factory']) ) {
				if ( isset($properties['includeFile']) ) {
					require_once $properties['includeFile'];
				}
				$tweak = call_user_func($properties['factory'], $id, $properties);
			} else if ( isset($properties['jquery-js']) ) {
				$tweak = new ameJqueryTweak(
					$id,
					$properties['label'] ?? null,
					$properties['jquery-js']
				);
			} else if ( isset($properties['callback']) ) {
				$tweak = new ameDelegatedTweak(
					$id,
					$properties['label'] ?? null,
					$properties['callback']
				);
			} else if ( !empty($properties['isGroup']) ) {
				$tweak = new ameDelegatedTweak(
					$id,
					$properties['label'] ?? null,
					'__return_false'
				);
			} else {
				throw new \LogicException(esc_html('Unknown tweak type in default-tweaks.php for tweak "' . $id . '"'));
			}

			if ( isset($properties['parent']) ) {
				$tweak->setParentId($properties['parent']);
			}
			if ( isset($properties['section']) ) {
				$tweak->setSectionId($properties['section']);
			}
			if ( isset($properties['description']) ) {
				$tweak->setDescription($properties['description']);
			}

			if ( isset($properties['hideableLabel']) ) {
				$tweak->setHideableLabel($properties['hideableLabel']);
			}
			if ( isset($properties['hideableCategory']) ) {
				$tweak->setHideableCategoryId($properties['hideableCategory']);
			}

			$this->collection->addTweak($tweak);
		}
	}

	//region Filtering
	private function filterTweaksToRegister($tweaksById) {
		$tweakFilter = $this->getTweakRegistrationFilter();
		if ( $tweakFilter !== null ) {
			$tweaksById = array_intersect_key($tweaksById, $tweakFilter);
		}
		return $tweaksById;
	}

	private function getTweakRegistrationFilter(): ?array {
		//We may be able to improve performance by only registering tweaks that are enabled
		//for the current user. However, we still need to show all tweaks in the "Tweaks" tab.
		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		//-- This is not processing form data, it's just checking which page the user is on.
		$isEasyHidePage = is_admin() && isset($_GET['page'])
			&& ($_GET['page'] === 'ame-easy-hide');
		$isDevTab = is_admin() && isset($_GET['page']) && isset($_GET['sub_section'])
			&& ($_GET['page'] === 'menu_editor')
			&& ( //Internal prototyping tabs
				($_GET['sub_section'] === 'customizable-dev')
				|| ($_GET['sub_section'] === 'revised-ui-dev')
			);
		//phpcs:enable

		if ( $this->isTweaksTab() || $isEasyHidePage || $isDevTab ) {
			$tweakFilter = null;
		} else {
			$tweakFilter = $this->tweakManager->getEnabledTweakSettings();
		}
		return $tweakFilter;
	}

	private function isTweaksTab(): bool {
		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		return is_admin()
			&& isset($_GET['page'], $_GET['sub_section'])
			&& ($_GET['page'] === 'menu_editor')
			&& ($_GET['sub_section'] === $this->tweakManager->getTabSlug());
		//phpcs:enable
	}

	//endregion
}