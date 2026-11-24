<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class ActionRoute {
	protected string $action;
	protected HandlerInterface $handler;
	protected string $method = 'POST';

	public function __construct(string $action, HandlerInterface $handler, string $method = 'POST') {
		$this->action = $action;
		$this->handler = $handler;
		$this->method = strtoupper($method);
	}

	public function run(ActionRequest $request): ResponseInterface {
		if ( $request->getMethod() !== $this->method ) {
			return new ErrorResponse(new \WP_Error('invalid_method', 'Invalid request method.'));
		}
		check_admin_referer($this->action);

		return $this->handler->handle($request);
	}
}