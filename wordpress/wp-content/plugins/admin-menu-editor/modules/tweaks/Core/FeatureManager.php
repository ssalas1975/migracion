<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

use YahnisElsts\AdminMenuEditor\Customizable\Builders\ElementBuilderFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Builders\SectionBuilder;
use YahnisElsts\AdminMenuEditor\Customizable\Schemas\Schema;
use YahnisElsts\AdminMenuEditor\Customizable\Schemas\SchemaFactory;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\AbstractSettingsDictionary;
use YahnisElsts\AdminMenuEditor\Customizable\Storage\Slot;

abstract class FeatureManager {
	/**
	 * @var null|callable(FeatureManager): ?Slot
	 */
	private $slotGetter;

	/**
	 * @var null|callable(FeatureManager): string
	 */
	private $settingPrefixGetter;

	/**
	 * @param callable|null $slotGetter A callable that returns the storage slot for this feature.
	 * @param callable|null $settingPrefixGetter A callable that returns the setting prefix for this feature.
	 */
	public function __construct(?callable $slotGetter = null, ?callable $settingPrefixGetter = null) {
		$this->slotGetter = $slotGetter;
		$this->settingPrefixGetter = $settingPrefixGetter;
	}

	abstract public function getManagerId(): string;

	public function registerTweaks(TweakCollectionInterface $tweakManager, ?array $tweakFilter) {

	}

	/**
	 * @param array<SectionBuilder> $sectionBuilders
	 * @param ElementBuilderFactory $b
	 * @param AbstractSettingsDictionary $settings
	 * @return array
	 */
	public function extendUi(
		array                      $sectionBuilders,
		ElementBuilderFactory      $b,
		AbstractSettingsDictionary $settings
	): array {
		return $sectionBuilders;
	}

	public function filterScriptData(array $scriptData): array {
		return $scriptData;
	}

	//region Storage (optional)
	public function needsStorage(): bool {
		return false;
	}

	public function getStorageSchema(SchemaFactory $s): ?Schema {
		return null;
	}

	/**
	 * Get custom tweak settings for this feature.
	 *
	 * It's expected that the feature will pull the settings from the storage slot, but it's
	 * up to the feature to decide how, specifically, it stores the settings there. This method
	 * will only be called if needsStorage() returns true.
	 *
	 * @return array<string, array> [$tweakId => $tweakSettings], and the settings must include
	 *                              at least the 'enabledForActor' field (which is an array).
	 */
	public function getTweakSettings(): array {
		return [];
	}

	protected function getFeatureData(): ?Slot {
		if ( $this->slotGetter === null ) {
			return null;
		}
		return call_user_func($this->slotGetter, $this);
	}

	protected function getSettingPrefix(): ?string {
		if ( $this->settingPrefixGetter === null ) {
			return null;
		}
		return call_user_func($this->settingPrefixGetter, $this);
	}
	//endregion
}