<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

use YahnisElsts\AdminMenuEditor\Customizable\Schemas;

class SettingsUpdateConfig {
	/**
	 * @var string The action name that this config is for.
	 */
	public string $action = '';

	/**
	 * @var callable
	 */
	public $handlerCallback = '__return_null';

	/**
	 * @var null|callable
	 */
	public $tabUrlGetter = null;

	/**
	 * @var array<string,mixed> Query parameters to add to the redirect URL on success.
	 */
	public array $successRedirectParams = ['updated' => 1];

	/**
	 * @var array Optional query parameters to pass through when redirecting.
	 */
	public array $passThroughParams = [];

	/**
	 * @var null|callable
	 */
	public $permissionsCallback = null;

	public Schemas\Schema $payloadSchema;

	public function __construct() {
		$this->payloadSchema = new Schemas\Anything();
	}
}