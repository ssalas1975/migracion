<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class UpdateSuccessResponse extends Response {
	/**
	 * @var array<string,mixed>
	 */
	protected $extraRedirectParams = [];

	public function __construct($extraRedirectParams = []) {
		$this->extraRedirectParams = $extraRedirectParams;
	}

	public function getExtraRedirectParams() {
		return $this->extraRedirectParams;
	}
}