<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard\PageActions;

class SettingsUpdateAction implements HandlerInterface {
	const INPUT_FORMAT_FORM_FIELDS = 'form_fields';
	const INPUT_FORMAT_SETTINGS_JSON = 'json';
	const SETTINGS_FIELD_NAME = 'settings';
	const RESERVED_FIELDS = [
		'action',
		'_wpnonce',
		'_wp_http_referer',
	];

	protected SettingsUpdateConfig $config;

	public function __construct(SettingsUpdateConfig $config) {
		$this->config = $config;
	}

	public function handle(
		ActionRequest $request,
		string        $inputFormat = self::INPUT_FORMAT_SETTINGS_JSON
	): ResponseInterface {
		//Action name, nonce, and admin referer checks should have already been performed by this point.

		//Check permissions.
		if ( is_callable($this->config->permissionsCallback) ) {
			$hasPermission = call_user_func($this->config->permissionsCallback, $request);
			if ( !$hasPermission ) {
				return new ErrorResponse(new \WP_Error(
					'ame_permission_denied',
					'You do not have sufficient permissions to perform this action.'
				));
			} else if ( is_wp_error($hasPermission) ) {
				return new ErrorResponse($hasPermission);
			}
		}

		$inputFields = $request->getParsedBody();
		//Remove reserved fields from the input.
		foreach (self::RESERVED_FIELDS as $reservedField) {
			unset($inputFields[$reservedField]);
		}

		//Extract the payload based on the input format.
		if ( $inputFormat === self::INPUT_FORMAT_FORM_FIELDS ) {
			$payload = $inputFields;
		} else if ( $inputFormat === self::INPUT_FORMAT_SETTINGS_JSON ) {
			$payload = $this->extractSettingsJsonPayload($inputFields);
			if ( is_wp_error($payload) ) {
				return new ErrorResponse($payload);
			}
		} else {
			return new ErrorResponse(new \WP_Error(
				'invalid_input_format',
				'Invalid input format specified for the action handler.'
			));
		}

		//Validate the payload against the schema.
		if ( $this->config->payloadSchema ) {
			$parsed = $this->config->payloadSchema->parse($payload);
			if ( is_wp_error($parsed) ) {
				return new ErrorResponse($parsed);
			}
			$payload = $parsed;
		}

		//Call the handler.
		$result = call_user_func($this->config->handlerCallback, $payload, $request);

		//If necessary, wrap the result in a ResponseInterface implementation.
		if ( $result instanceof ResponseInterface ) {
			$response = $result;
		} else {
			if ( is_wp_error($result) ) {
				$response = new ErrorResponse($result);
			} else if ( empty($result) ) {
				$response = new ErrorResponse(new \WP_Error(
					'invalid_response',
					'The action handler returned an invalid response of type ' . gettype($result) . '.'
				));
			} else {
				$response = new UpdateSuccessResponse($result);
			}
		}

		//On success, redirect back to the same tab by default.
		if (
			($response instanceof UpdateSuccessResponse)
			&& (!$response->getSafeRedirectUrl()) //Check this is not already a redirect.
			&& $this->config->tabUrlGetter
		) {
			$tabUrl = call_user_func($this->config->tabUrlGetter, $request);
			if ( $tabUrl ) {
				$redirectParams = $this->prepareSuccessRedirectParams($request, $response);
				$response = $response->withSafeRedirect(add_query_arg($redirectParams, $tabUrl));
			}
		}

		return $response;
	}

	public static function builder(string $action, ?\ameModule $module = null): SettingsUpdateActionBuilder {
		$builder = (new SettingsUpdateActionBuilder())->action($action);
		if ( $module ) {
			$builder->tabUrlGetter(fn() => $module->getTabUrl());
		}
		return $builder;
	}

	/**
	 * @param array $inputFields
	 * @return array|\WP_Error
	 */
	protected function extractSettingsJsonPayload(array $inputFields) {
		if ( empty($inputFields['settings']) ) {
			return new \WP_Error(
				'ame_missing_settings_field',
				sprintf('The "%s" field is missing or empty.', self::SETTINGS_FIELD_NAME)
			);
		}

		if ( !is_string($inputFields[self::SETTINGS_FIELD_NAME]) ) {
			return new \WP_Error(
				'ame_invalid_settings_field',
				sprintf('The "%s" field must contain a JSON-encoded string.', self::SETTINGS_FIELD_NAME)
			);
		}

		$newSettings = json_decode($inputFields[self::SETTINGS_FIELD_NAME], true);
		if ( !is_array($newSettings) ) {
			return new \WP_Error(
				'ame_invalid_settings_data',
				sprintf('The "%s" field must contain a valid JSON object.', self::SETTINGS_FIELD_NAME)
			);
		}
		return $newSettings;
	}

	protected function prepareSuccessRedirectParams(ActionRequest $request, Response $response): array {
		$redirectParams = array_merge($request->getSelectedActorParams(), $this->config->successRedirectParams);


		$queryParams = $request->getQueryParams();
		$bodyParams = $request->getParsedBody();
		foreach ($this->config->passThroughParams as $paramName) {
			//Do not overwrite existing parameters.
			if ( array_key_exists($paramName, $redirectParams) ) {
				continue;
			}

			//Prefer POST parameters, then query parameters.
			//These could be the same if it's a GET request (but usually our forms use POST).
			if ( isset($bodyParams[$paramName]) ) {
				$redirectParams[$paramName] = $bodyParams[$paramName];
			} else if ( isset($queryParams[$paramName]) ) {
				$redirectParams[$paramName] = $queryParams[$paramName];
			}
		}

		if ( $response instanceof UpdateSuccessResponse ) {
			$redirectParams = array_merge($redirectParams, $response->getExtraRedirectParams());
		}

		return $redirectParams;
	}

	public function getActionName(): string {
		return $this->config->action;
	}

	/**
	 * Get the configuration data for the "ame-save-settings-form" KO component.
	 *
	 * See the SaveFormConfigFromServer interface in ko-extensions.ts.
	 *
	 * @return array
	 */
	public function getJsSaveFormConfig(): array {
		//This only works if we have the tab URL since we use it to generate the form action/submit URL.
		if ( empty($this->config->tabUrlGetter) ) {
			throw new \RuntimeException('Cannot get JS save form config: tabUrlGetter is not set.');
		}
		$tabUrl = call_user_func($this->config->tabUrlGetter);
		if ( !$tabUrl ) {
			throw new \RuntimeException('Cannot get JS save form config: tabUrlGetter returned an empty value.');
		}
		$submitUrl = add_query_arg('noheader', 1, $tabUrl);

		return [
			'action'      => $this->config->action,
			'actionNonce' => wp_create_nonce($this->config->action),
			'referer'     => remove_query_arg('_wp_http_referer'),
			'submitUrl'   => $submitUrl,
		];
	}
}