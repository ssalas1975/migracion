<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

interface HandlerInterface {
	public function handle(ActionRequest $request): ResponseInterface;
}