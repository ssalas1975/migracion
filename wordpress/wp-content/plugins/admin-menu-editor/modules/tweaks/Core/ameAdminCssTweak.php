<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use YahnisElsts\AdminMenuEditor\Customizable\Builders\ElementBuilder;
use YahnisElsts\AdminMenuEditor\Customizable\Builders\ElementBuilderFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Schemas\SchemaFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\AbstractSettingsDictionary;
use YahnisElsts\AdminMenuEditor\WireDSL\Wire;

class ameAdminCssTweak extends ameDelegatedTweak {
	public function getSettingsSchemaFields(SchemaFactory $s): array {
		return array_merge(
			parent::getSettingsSchemaFields($s),
			self::createSettingsSchemaFields($s)
		);
	}

	public static function createSettingsSchemaFields(SchemaFactory $s): array {
		return [
			'label' => $s->string()->min(1)->defaultValue(''),
			'css'   => $s->string(),
		];
	}

	public function createUiElement(
		ElementBuilderFactory      $b,
		AbstractSettingsDictionary $settings,
		                           $settingPrefix,
		array                      $extraElementParams = []
	): ?ElementBuilder {
		$extraElementParams['objectId'] = $this->getId();
		//Only user-created tweaks can be edited/deleted, and their IDs always start with 'utw-'.
		$isUserTweak = $this->isUserTweak();
		if ( $isUserTweak ) {
			$extraElementParams['hasTweakActions'] = true;
		}

		$defaultControl = parent::createUiElement($b, $settings, $settingPrefix, $extraElementParams);

		$defaultControl->add(
			$b->codeEditor($settingPrefix . '.css')->cssMode()
		);

		if ( $isUserTweak ) {
			$defaultControl->slot(
				'actions',
				$b->eventButton()
					->eventName('adminMenuEditor:editObject')
					->eventData(Wire::resolutionOf($settings->findSetting($settingPrefix)))
					->iconClass('dashicons dashicons-edit')
					->kind('link'),
				$b->eventButton()
					->eventName('adminMenuEditor:deleteObject')
					->eventData($this->getId())
					->iconClass('dashicons dashicons-trash')
					->classes('ame-delete-action-button')
					->kind('link')
			);
		}

		return $defaultControl;
	}

	protected function getUiElementLabel(AbstractSettingsDictionary $settings, string $settingPrefix) {
		if ( $this->isUserTweak() ) {
			return Wire::bind($settings->findSetting($settingPrefix . '.label'));
		} else {
			return parent::getUiElementLabel($settings, $settingPrefix);
		}
	}

	protected function isUserTweak(): bool {
		return strpos($this->getId(), 'utw-') === 0;
	}

	public function hasAnySettings() {
		//This is necessary for the extra settings to be passed to the apply() method.
		return true;
	}
}