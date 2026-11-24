<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class ActionRequest {
	protected string $method;
	protected string $action;
	protected array $queryParams;
	protected array $parsedBody;

	protected ?string $selectedActorId = null;
	protected ?string $selectedActorFieldName = null;

	public function __construct(
		string  $method,
		string  $action,
		array   $queryParams = [],
		array   $parsedBody = [],
		?string $selectedActorId = null,
		?string $selectedActorFieldName = null
	) {
		$this->method = $method;
		$this->action = $action;
		$this->queryParams = $queryParams;
		$this->parsedBody = $parsedBody;
		$this->selectedActorId = $selectedActorId;
		$this->selectedActorFieldName = $selectedActorFieldName;
	}

	public function getMethod(): string {
		return $this->method;
	}

	public function getAction(): string {
		return $this->action;
	}

	public function getQueryParams(): array {
		return $this->queryParams;
	}

	public function getParsedBody(): array {
		return $this->parsedBody;
	}

	/**
	 * Get an associative array with the selected actor field name as the key
	 * and the selected actor ID as the value.
	 *
	 * Example: ['selectedActor' => 'role:editor']
	 *
	 * @return array<string,string>
	 */
	public function getSelectedActorParams(): array {
		if ( $this->selectedActorFieldName === null ) {
			return [];
		}
		return [$this->selectedActorFieldName => $this->selectedActorId];
	}
}