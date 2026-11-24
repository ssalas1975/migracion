<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use YahnisElsts\AdminMenuEditor\Customizable\Builders\BaseElementBuilder;
use YahnisElsts\AdminMenuEditor\Customizable\Builders\ElementBuilder;
use YahnisElsts\AdminMenuEditor\Customizable\Builders\ElementBuilderFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Schemas\SchemaFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\AbstractSettingsDictionary;
use YahnisElsts\AdminMenuEditor\WireDSL\Expression;

abstract class ameBaseTweak {
	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var string
	 */
	protected string $label = '';

	protected $parentId;
	protected $sectionId;

	/**
	 * @var string[]|null List of admin screen IDs that the tweak applies to.
	 */
	protected $screens = null;

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var string|null
	 */
	protected $hideableLabel = null;

	/**
	 * @var string|null
	 */
	protected $hideableCategory = null;

	/**
	 * NamedNode constructor.
	 *
	 * @param string $id
	 * @param string|null $label
	 */
	public function __construct(string $id, ?string $label = '') {
		$this->id = $id;
		$this->label = ($label !== null) ? $label : $id;
	}

	/**
	 * @param array|null $settings User settings for this tweak.
	 * @return mixed
	 */
	abstract public function apply($settings = null);

	public function getId() {
		return $this->id;
	}

	public function getLabel() {
		return $this->label;
	}

	public function getParentId() {
		return $this->parentId;
	}

	public function setParentId($id) {
		$this->parentId = $id;
		return $this;
	}

	public function setSectionId($id) {
		$this->sectionId = $id;
		return $this;
	}

	public function getSectionId() {
		return $this->sectionId;
	}

	public function hasScreenFilter() {
		return ($this->screens !== null);
	}

	public function isEnabledForCurrentScreen() {
		if ( !$this->hasScreenFilter() ) {
			return true;
		}
		if ( !function_exists('get_current_screen') ) {
			return false;
		}
		$screen = get_current_screen();
		if ( isset($screen, $screen->id) ) {
			return $this->isEnabledForScreen($screen->id);
		}
		return false;
	}

	public function isEnabledForScreen($screenId) {
		if ( $this->screens === null ) {
			return true;
		}
		return in_array($screenId, $this->screens);
	}

	public function setScreens($screens) {
		$this->screens = $screens;
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return bool
	 */
	public function wantsToRunEarly(): bool {
		return false;
	}

	public function isIndependentlyHideable(): bool {
		return ($this->hideableCategory !== null);
	}

	public function getHideableCategoryId() {
		return $this->hideableCategory;
	}

	public function setHideableCategoryId($categoryId) {
		$this->hideableCategory = $categoryId;
	}

	public function getHideableLabel() {
		if ( $this->hideableLabel !== null ) {
			return $this->hideableLabel;
		}
		return $this->getLabel();
	}

	public function setHideableLabel($text) {
		$this->hideableLabel = $text;
	}

	public function getSettingsSchemaFields(SchemaFactory $s): array {
		return [];
	}

	/**
	 * @param ElementBuilderFactory $b
	 * @param AbstractSettingsDictionary $settings
	 * @param $settingPrefix
	 * @param array $extraElementParams
	 * @return ElementBuilder|null
	 */
	public function createUiElement(
		ElementBuilderFactory      $b,
		AbstractSettingsDictionary $settings,
		                           $settingPrefix,
		array                      $extraElementParams = []
	): ?ElementBuilder {
		$setting = $settings->findSetting($settingPrefix . '.enabledForActor');

		$tweakControl = $b->actorFeatureCheckbox($setting)
			->id($this->generateUiElementHtmlId())
			->label($this->getUiElementLabel($settings, $settingPrefix))
			->params($extraElementParams);
		if ( $this->getDescription() ) {
			$tweakControl->description($this->getDescription());
		}

		return $tweakControl;
	}

	/**
	 * Get the label to use for the tweak's UI element.
	 *
	 * This is a string by default, but subclasses may choose to return an Expression that
	 * evaluates to a string instead.
	 *
	 * @return string|Expression
	 */
	protected function getUiElementLabel(AbstractSettingsDictionary $settings, string $settingPrefix) {
		return $this->getLabel() ?? $this->getId();
	}

	protected function generateUiElementHtmlId(): string {
		$slugifiedId = str_replace('_', '-', preg_replace('/[^a-zA-Z0-9_-]/', '', $this->getId()));
		return 'ame-tweak-' . $slugifiedId;
	}

	public function supportsUserInput() {
		return $this->hasAnySettings();
	}

	public function hasAnySettings() {
		return false;
	}

	//todo: getEditableProperties(). Or maybe we don't need it at all? Just merge the settings.
}