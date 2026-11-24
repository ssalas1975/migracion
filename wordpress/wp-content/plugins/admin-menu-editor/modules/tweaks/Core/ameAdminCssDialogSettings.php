<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use YahnisElsts\AdminMenuEditor\Customizable\Schemas\SchemaFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\AbstractSettingsDictionary;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\LazyArrayStorage;

class ameAdminCssDialogSettings extends AbstractSettingsDictionary {
	public function __construct() {
		parent::__construct(new LazyArrayStorage());
	}

	protected function createDefaults(): array {
		return [];
	}

	protected function createSettings(): array {
		$f = $this->settingFactory();
		$s = new SchemaFactory();
		return $f->buildSettings([
			'tempAdminCssSnippet' => $s->struct(array_merge(
				ameTweakManager::createBaseTweakSettingsFields($s),
				ameAdminCssTweak::createSettingsSchemaFields($s)
			)),
		]);
	}
}