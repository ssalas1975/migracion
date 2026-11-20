<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use YahnisElsts\AdminMenuEditor\Customizable\Builders\ElementBuilder;
use YahnisElsts\AdminMenuEditor\Customizable\Builders\ElementBuilderFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\AbstractSettingsDictionary;

class PlaceholderScreenshotTweak extends PlaceholderTweak {
	private string $relativeSrc;

	public function __construct(string $id, ?string $label = '', $additionalParams = []) {
		parent::__construct($id, $label);
		$this->relativeSrc = $additionalParams['relativeSrc'] ?? '';
	}

	public function createUiElement(
		ElementBuilderFactory      $b,
		AbstractSettingsDictionary $settings,
		                           $settingPrefix,
		array                      $extraElementParams = []
	): ?ElementBuilder {
		if ( empty($this->relativeSrc) ) {
			return $b->html('<p>No screenshot available.</p>');
		}

		return $b->html(sprintf(
			'<img src="%s" alt="Feature screenshot">',
			plugins_url($this->relativeSrc, AME_ROOT_DIR . '/menu-editor.php')
		));
	}
}