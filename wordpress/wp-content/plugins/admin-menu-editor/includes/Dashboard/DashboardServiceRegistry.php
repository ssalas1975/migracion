<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard;

class DashboardServiceRegistry {
	protected \WPMenuEditor $menuEditor;

	public function __construct(\WPMenuEditor $menuEditor) {
		$this->menuEditor = $menuEditor;
	}

	public function getMenuEditor(): \WPMenuEditor {
		return $this->menuEditor;
	}
}