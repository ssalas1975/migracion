<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class Response implements ResponseInterface {
	protected ?string $safeRedirectUrl = null;

	public function withSafeRedirect(string $url): self {
		$response = clone $this;
		$response->safeRedirectUrl = $url;
		return $response;
	}

	public function getSafeRedirectUrl(): ?string {
		return $this->safeRedirectUrl;
	}
}