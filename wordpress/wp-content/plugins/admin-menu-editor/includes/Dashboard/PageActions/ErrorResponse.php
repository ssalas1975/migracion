<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class ErrorResponse extends Response {
	protected \WP_Error $error;

	public function __construct(\WP_Error $error) {
		$this->error = $error;
	}

	public function getError(): \WP_Error {
		return $this->error;
	}
}