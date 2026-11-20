<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use YahnisElsts\AdminMenuEditor\Customizable\Builders\BaseElementBuilder;
use YahnisElsts\AdminMenuEditor\Customizable\Builders\ControlBuilder;
use YahnisElsts\AdminMenuEditor\Customizable\Controls\ControlFlow\WithBlock;
use YahnisElsts\AdminMenuEditor\Customizable\Controls\EventButton;
use YahnisElsts\AdminMenuEditor\Customizable\Controls\InterfaceStructure;
use YahnisElsts\AdminMenuEditor\Customizable\RevisedUiPack;
use YahnisElsts\AdminMenuEditor\Dashboard\DashboardServiceRegistry;
use YahnisElsts\AdminMenuEditor\Dashboard\InterfacePackage;
use YahnisElsts\AdminMenuEditor\Tweaks\Pro\PlaceholderGenerator;
use YahnisElsts\AdminMenuEditor\Tweaks\Pro\TweakManagerPro;
use YahnisElsts\AdminMenuEditor\WireDSL\Wire;

class TweaksUiPackage extends InterfacePackage {
	private ameTweakManager $tweakManager;

	public function __construct(DashboardServiceRegistry $serviceRegistry, ameTweakManager $module) {
		parent::__construct($serviceRegistry, $module);
		$this->tweakManager = $module;

		$this->addLocalStyle('tweaks.css');
		$this->setModuleTemplate('tweaks');
		$this->enableHeaderAndFooter();
		$this->addWrapClasses('ame-tab-list-bottom-margin-disabled');
	}

	public function enqueueScripts() {
		parent::enqueueScripts();

		$codeEditorSettings = null;
		if ( function_exists('wp_enqueue_code_editor') ) {
			$codeEditorSettings = wp_enqueue_code_editor(['type' => 'text/html']);
		}

		$uiPack = new RevisedUiPack($this->getInterfaceStructure());
		$editAdminCssUiPack = $this->getEditAdminCssUiPack();
		$uiPack->enqueueDependencies();
		$editAdminCssUiPack->enqueueDependencies();

		//Also serialize the setting used to store the user-defined tweak suffix.
		$settings = $this->tweakManager->loadSettings();
		$uiPack->addExtraSetting($settings->getSetting('lastUserTweakSuffix'));

		//For user-defined tweaks, we also need their "typeId" setting and other metadata.
		//These are not directly referenced in the UI structure.
		$userDefinedTweaks = $settings->get('userDefinedTweaks', []);
		foreach (array_keys($userDefinedTweaks) as $tweakId) {
			foreach (['typeId', 'isUserDefined', 'label'] as $field) {
				$uiPack->addExtraSetting($settings->getSetting('tweaks.' . $tweakId . '.' . $field));
			}
		}

		$saveForm = $this->tweakManager->getSettingsUpdateAction();

		$scriptData = [
			'uiPack'             => $uiPack->serializeForJs(),
			'editAdminCssUiPack' => $editAdminCssUiPack->serializeForJs(),

			'preferenceCookiePath'      => ADMIN_COOKIE_PATH,
			'defaultCodeEditorSettings' => $codeEditorSettings,

			'isProVersion'        => $this->isProVersion(),
			'lastUserTweakSuffix' => \ameUtils::get($settings, 'lastUserTweakSuffix', 0),
			'saveFormConfig'      => $saveForm->getJsSaveFormConfig(),
		];

		foreach ($this->tweakManager->getFeatures() as $featureManager) {
			$scriptData = $featureManager->filterScriptData($scriptData);
		}

		if ( $this->bundlesEnabled() ) {
			$managerScript = $this->webpackRegistry()->getWebpackEntryPoint('tweak-manager');
		} else {
			$managerScript = $this->localScript('tweak-manager.js')->setTypeToModule();
		}

		$baseDeps = $this->baseDependencies();
		$managerScript
			->addDependencies(
				'jquery',
				$baseDeps()->koPackage()->cookies()->qtip()
			)
			->setInFooter()
			->addJsVariable('wsTweakManagerData', $scriptData)
			->enqueue();
	}

	private function getEditAdminCssUiPack(): RevisedUiPack {
		$s = new ameAdminCssDialogSettings();
		$b = $s->elementBuilder();

		$structure = $b->structure(
			new WithBlock(
				$s->findSetting('tempAdminCssSnippet'),
				[
					$b->group(
						'',
						$b->textBox(Wire::bind('$item.label'))->inputClasses('large-text')->asGroup('Name'),
						$b->codeEditor(Wire::bind('$item.css'))->cssMode()->asGroup('CSS code')
					)->classes('ame-gcg-dialog-panel-style')->build(),
				]
			)
		);

		return new RevisedUiPack($structure->build());
	}

	public function getInterfaceStructure(): InterfaceStructure {
		$settings = $this->tweakManager->loadSettings();
		$b = $settings->elementBuilder();
		$collection = $this->tweakManager->getCollection();

		//Sections.
		$sections = $collection->getSections();
		//Sort sections by priority, then by label.
		uasort($sections, function (ameTweakSection $a, ameTweakSection $b) {
			$priorityA = $a->getPriority();
			$priorityB = $b->getPriority();
			if ( $priorityA !== $priorityB ) {
				return $priorityA <=> $priorityB;
			}
			return strnatcasecmp($a->getLabel(), $b->getLabel());
		});

		$sectionBuilders = [];
		foreach ($sections as $section) {
			$builder = $b->section($section->getLabel())
				->id('twm-section_' . $section->getId())
				->classes('ame-twm-section')
				->params(['childrenContainerClasses' => ['ame-check-or-radio-collection']]);

			$description = $section->getDescription();
			if ( !empty($description) ) {
				$builder->description($description);
			}

			if ( $section->isProPlaceholder() ) {
				$builder->classes('ame-twm-pro-placeholder');

				$message = $section->getProPlaceholderMessage();
				if ( empty($message) ) { //This should never happen because the getter should have a default message.
					$message = 'This is a Pro version feature.';
				}
				$builder->add(
					$b->html(sprintf(
						'<div class="ame-twm-placeholder-overlay"></div>
						 <div class="ame-twm-pro-banner">
							<div class="ame-twm-pro-message">%s</div>
							<a href="%s" target="_blank" class="button ame-twm-upgrade-button">Upgrade to Pro</a>
						</div>',
						esc_html($message),
						esc_attr('https://adminmenueditor.com/')
					))
				);
			}

			$sectionBuilders[$section->getId()] = $builder;
		}

		//Tweaks.
		$tweakControlsById = [];
		$tweakSectionsByTweakId = []; //For alias tooltips.

		foreach ($collection->getTweaks() as $tweak) {
			$plainTweakId = $tweak->getId();

			$tweakControl = $tweak->createUiElement($b, $settings, 'tweaks.' . $plainTweakId);
			if ( $tweakControl === null ) {
				//This tweak does not want a normal a UI element. The relevant feature should
				//set up its own UI for it.
				continue;
			}

			$tweakControlsById[$plainTweakId] = $tweakControl;

			if ( !empty($tweak->getParentId()) ) {
				$parentTweakId = $tweak->getParentId();
				if (
					isset($tweakControlsById[$parentTweakId])
					&& ($tweakControlsById[$parentTweakId] instanceof BaseElementBuilder)
				) {
					$parentControl = $tweakControlsById[$parentTweakId];
					$parentControl->add($tweakControl);
					continue;
				} else {
					//Parent not found; put it in a section instead (fallthrough).
				}
			}

			$plainSectionId = $tweak->getSectionId() ?? 'general';
			if ( !isset($sectionBuilders[$plainSectionId]) ) {
				continue;
			}

			$sectionBuilder = $sectionBuilders[$plainSectionId];
			$sectionBuilder->add($tweakControl);
			$tweakSectionsByTweakId[$plainTweakId] = $sectionBuilder;
		}

		//Aliases
		$aliasCounter = 0;
		foreach ($collection->getAliases() as $alias) {
			$targetTweakId = $alias->getTweakId();
			if ( !isset($tweakControlsById[$targetTweakId]) ) {
				continue;
			}

			//An alias is just another control that points to the same setting.
			$targetControl = $tweakControlsById[$targetTweakId];
			if ( !($targetControl instanceof ControlBuilder) ) {
				throw new \LogicException(sprintf(
					'Invalid alias: "%s" is not a ControlBuilder instance.',
					$targetTweakId
				));
			}

			$targetSettings = $targetControl->getSettings();
			$firstSetting = reset($targetSettings);
			if ( $firstSetting === false ) {
				throw new \LogicException(sprintf(
					'Invalid alias: target control for "%s" has no settings.',
					$targetTweakId
				));
			}

			$aliasCounter++;
			$aliasControl = $b->actorFeatureCheckbox($firstSetting)
				->label($alias->getLabel() ?? $targetTweakId)
				->id('alias-' . $targetTweakId . '-' . $aliasCounter);

			$tooltip = 'This is an alias for: "' . $targetControl->getParam('label', $targetTweakId) . '"';
			if ( isset($tweakSectionsByTweakId[$targetTweakId]) ) {
				$builder = $tweakSectionsByTweakId[$targetTweakId];
				$tooltip .= ' in the section "' . $builder->getTitle() . '".';
			}
			$aliasControl->tooltip($tooltip);

			if ( !empty($alias->getParentId()) ) {
				$parentTweakId = $alias->getParentId();
				if (
					isset($tweakControlsById[$parentTweakId])
					&& ($tweakControlsById[$parentTweakId] instanceof BaseElementBuilder)
				) {
					$aliasParentControl = $tweakControlsById[$parentTweakId];
					$aliasParentControl->add($aliasControl);
					continue;
				}
			}

			$plainSectionId = $alias->getSectionId() ?? 'general';
			if ( !isset($sectionBuilders[$plainSectionId]) ) {
				continue;
			}
			$sectionBuilder = $sectionBuilders[$plainSectionId];
			$sectionBuilder->add($aliasControl);
		}

		//"Add CSS snippet" button.
		if ( isset($sectionBuilders['admin-css']) ) {
			$adminCssSection = $sectionBuilders['admin-css'];
			$adminCssSection->add(
				new EventButton(
					[],
					[
						'label'     => 'Add CSS snippet',
						'eventName' => 'adminMenuEditor:addCssSnippet',
						'wrap'      => true,
						'id'        => 'twm-add-admin-css-snippet-button',
					]
				)
			);
		}

		//Let features extend or modify the UI.
		$features = $this->tweakManager->getFeatures();
		foreach ($features as $featureManager) {
			$sectionBuilders = $featureManager->extendUi($sectionBuilders, $b, $settings);
		}

		$structure = $b->structure();
		foreach ($sectionBuilders as $b) {
			$structure->add($b);
		}

		return $structure->build();
	}

	public function displayPage() {
		if (
			class_exists(PlaceholderGenerator::class)
			&& defined('WP_DEBUG') && constant('WP_DEBUG')
			&& defined('AME_TEST_MODE') && constant('AME_TEST_MODE')
			&& class_exists(TweakManagerPro::class, false)
			&& ($this->tweakManager instanceof TweakManagerPro)
		) {
			new PlaceholderGenerator($this->tweakManager);
		}

		parent::displayPage();
	}

	public function currentUserCanView(): bool {
		return $this->tweakManager->userCanAccessModule() && parent::currentUserCanView();
	}
}