<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard;

class LegacyTab extends AdminPageTab {
	protected $displayCallback;
	protected $scriptsCallback;
	protected $stylesCallback;

	public function __construct(
		string $slug,
		string $title,
		\WPMenuEditor $menuEditor,
		int $order = 10,
		$displayCallback = '__return_null',
		$scriptsCallback = null,
		$stylesCallback = null
	) {
		parent::__construct($slug, $title, $menuEditor, $order);
		$this->displayCallback = $displayCallback;
		$this->scriptsCallback = $scriptsCallback;
		$this->stylesCallback = $stylesCallback;
	}

	public function display() {
		if ( is_callable($this->displayCallback) ) {
			call_user_func($this->displayCallback, $this->menuEditor);
		} else {
			parent::display();
		}
	}

	public function enqueueScripts() {
		if ( is_callable($this->scriptsCallback) ) {
			call_user_func($this->scriptsCallback, $this->menuEditor);
		} else {
			parent::enqueueScripts();
		}
	}

	public function enqueueStyles() {
		if ( is_callable($this->stylesCallback) ) {
			call_user_func($this->stylesCallback, $this->menuEditor);
		} else {
			parent::enqueueStyles();
		}
	}
}