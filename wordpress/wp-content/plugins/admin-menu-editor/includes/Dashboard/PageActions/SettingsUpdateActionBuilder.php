<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class SettingsUpdateActionBuilder {
	protected SettingsUpdateConfig $config;

	public function __construct() {
		$this->config = new SettingsUpdateConfig();
	}

	public function action(string $action): self {
		$this->config->action = $action;
		return $this;
	}

	public function passThroughParams(array $params): self {
		$this->config->passThroughParams = $params;
		return $this;
	}

	public function successRedirectParams(array $params): self {
		$this->config->successRedirectParams = $params;
		return $this;
	}

	public function tabUrlGetter(callable $getter): self {
		$this->config->tabUrlGetter = $getter;
		return $this;
	}

	public function handler(callable $callback): self {
		$this->config->handlerCallback = $callback;
		return $this;
	}

	public function permissionsCallback(callable $callback): self {
		$this->config->permissionsCallback = $callback;
		return $this;
	}

	public function build(): SettingsUpdateAction {
		if ( empty($this->config->action) ) {
			throw new \LogicException('Action name must be set before building the action.');
		}
		if ( !is_callable($this->config->handlerCallback) ) {
			throw new \LogicException('Handler callback must be set before building the action.');
		}

		return new SettingsUpdateAction($this->config);
	}
}