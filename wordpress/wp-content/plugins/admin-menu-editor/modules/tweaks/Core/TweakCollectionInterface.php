<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

interface TweakCollectionInterface {
	public function addSection($id, $label, $priority = null): ameTweakSection;
	public function addSectionInstance(ameTweakSection $section);

	/**
	 * @return array<string, ameBaseTweak>
	 */
	public function getTweaks(): array;

	/**
	 * @return array<string, ameTweakAlias>
	 */
	public function getAliases(): array;

	/**
	 * @return array<string, ameTweakSection>
	 */
	public function getSections(): array;

	public function getSection($id): ?ameTweakSection;
}