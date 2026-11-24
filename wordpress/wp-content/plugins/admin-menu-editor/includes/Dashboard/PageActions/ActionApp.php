<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class ActionApp {
	/**
	 * @var array<string,ActionRoute>
	 */
	protected array $routes = [];

	public function run(string $action, \WPMenuEditor $menuEditor): bool {
		if ( !isset($_SERVER['REQUEST_METHOD']) ) {
			return false;
		}
		$method = strtoupper(sanitize_key($_SERVER['REQUEST_METHOD']));
		$parsedBody = $method === 'POST' ? $menuEditor->get_post_params() : [];
		$queryParams = $menuEditor->get_query_params();

		return $this->dispatch($method, $action, $queryParams, $parsedBody);
	}

	protected function dispatch(string $method, string $action, array $queryParams = [], array $parsedBody = []): bool {
		if ( !isset($this->routes[$action]) ) {
			return false;
		}

		list($selectedActorId, $selectedActorFieldName) = $this->extractSelectedActorDetails($parsedBody);

		$request = new ActionRequest(
			$method,
			$action,
			$queryParams,
			$parsedBody,
			$selectedActorId,
			$selectedActorFieldName
		);

		$route = $this->routes[$action];
		$response = $route->run($request);

		if ( $response instanceof ErrorResponse ) {
			wp_die(wsAmeEscapeWpError($response->getError()));
		}

		$redirectUrl = $response->getSafeRedirectUrl();
		if ( $redirectUrl !== null ) {
			wp_safe_redirect($redirectUrl);
			exit;
		}

		//Unsupported response type.
		wp_die('Invalid response from action handler.');
	}

	/**
	 * Extract the selected actor details from the request parameters.
	 *
	 * @param array $params Request parameters (e.g. $_POST or $_GET).
	 * @return array{0: string|null, 1: string|null} Tuple of selected actor ID and parameter name.
	 *  Both can be NULL if no valid actor ID was found.
	 */
	public function extractSelectedActorDetails(array $params): array {
		$selectedActorFields = ['selectedActor', 'selected_actor'];

		$foundSelectedActorId = null;
		$foundSelectedActorParam = null;
		foreach ($selectedActorFields as $paramName) {
			if ( isset($params[$paramName]) && $this->couldBeValidActorId($params[$paramName]) ) {
				$foundSelectedActorId = strval($params[$paramName]);
				$foundSelectedActorParam = $paramName;
				break;
			}
		}
		return [$foundSelectedActorId, $foundSelectedActorParam];
	}

	protected function couldBeValidActorId($actorId): bool {
		return (
			is_string($actorId)
			&& (strlen($actorId) <= 200)
			&& (
				($actorId === 'special:super_admin')
				|| preg_match('/^(role|user):[a-z0-9_]+$/i', $actorId)
			)
		);
	}

	public function add(string $action, HandlerInterface $handler, string $method = 'POST'): ActionRoute {
		if ( isset($this->routes[$action]) ) {
			throw new \InvalidArgumentException("Action '$action' is already registered.");
		}
		$route = new ActionRoute($action, $handler, $method);
		$this->routes[$action] = $route;
		return $route;
	}
}