<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use YahnisElsts\AdminMenuEditor\Dashboard\AdminPageTab;
use YahnisElsts\AdminMenuEditor\Dashboard\DashboardServiceRegistry;
use YahnisElsts\AdminMenuEditor\Dashboard\InterfacePackage;

class TweaksTab extends AdminPageTab {
	private ameTweakManager $module;

	public function __construct(\WPMenuEditor $menuEditor, ameTweakManager $module) {
		parent::__construct('tweaks', 'Tweaks', $menuEditor);
		$this->module = $module;
	}

	protected function chooseInterfacePackage(array $queryParams): InterfacePackage {
		$serviceRegistry = new DashboardServiceRegistry($this->menuEditor);
		return new TweaksUiPackage($serviceRegistry, $this->module);
	}

	public function getInterfacePackage(): InterfacePackage {
		return $this->getActivePackage();
	}
}