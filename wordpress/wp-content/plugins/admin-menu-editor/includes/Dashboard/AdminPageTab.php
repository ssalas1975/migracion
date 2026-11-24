<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard;

class AdminPageTab {
	protected string $slug;
	protected string $title;
	protected int $order;
	protected \WPMenuEditor $menuEditor;

	protected ?InterfacePackage $activePackage = null;

	public function __construct(string $slug, string $title, \WPMenuEditor $menuEditor, int $order = 10) {
		$this->slug = $slug;
		$this->title = $title;
		$this->order = $order;
		$this->menuEditor = $menuEditor;
	}

	public function getSlug(): string {
		return $this->slug;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getOrder(): int {
		return $this->order;
	}

	public function display() {
		$this->getActivePackage()->displayPage();
	}

	public function enqueueScripts() {
		$this->getActivePackage()->enqueueScripts();
	}

	public function enqueueStyles() {
		$this->getActivePackage()->enqueueStyles();
	}

	protected function getActivePackage(): InterfacePackage {
		if ( $this->activePackage === null ) {
			$this->activePackage = $this->chooseInterfacePackage($this->menuEditor->get_query_params());
		}
		return $this->activePackage;
	}

	protected function chooseInterfacePackage(array $queryParams): InterfacePackage {
		//This should be overridden in subclasses to return the appropriate interface package.
		return new InterfacePackage($this->menuEditor->get_dashboard_service_registry());
	}
}