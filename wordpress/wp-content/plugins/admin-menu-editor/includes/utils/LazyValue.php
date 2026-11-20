<?php

namespace YahnisElsts\AdminMenuEditor\Utils;
/**
 * @template T
 */
class LazyValue {
	/**
	 * @var callable():T
	 */
	private $callback;
	/**
	 * @var T
	 * @noinspection PhpMissingFieldTypeInspection
	 */
	private $value;
	private bool $isValueReady = false;

	public function __construct(callable $callback) {
		$this->callback = $callback;
	}

	/**
	 * @return T
	 */
	public function get() {
		if ( !$this->isValueReady ) {
			$this->value = call_user_func($this->callback);
			$this->isValueReady = true;
		}
		return $this->value;
	}

	/**
	 * @return T
	 */
	public function __invoke() {
		return $this->get();
	}
}