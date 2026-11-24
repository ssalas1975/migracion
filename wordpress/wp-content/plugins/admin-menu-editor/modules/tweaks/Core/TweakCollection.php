<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

class TweakCollection implements TweakCollectionInterface {
	/**
	 * @var array<string, ameBaseTweak>
	 */
	private array $tweaks = [];
	/**
	 * @var array<string, ameTweakAlias>
	 */
	private array $aliases = [];
	/**
	 * @var array<string, ameTweakSection>
	 */
	private array $sections = [];
	private TweakApplier $applier;

	public function __construct(TweakApplier $applier) {
		$this->applier = $applier;
	}

	public function addTweak(ameBaseTweak $tweak, string $applicationMode = ameTweakManager::APPLY_TWEAK_AUTO) {
		$this->tweaks[$tweak->getId()] = $tweak;
		if ( $applicationMode === ameTweakManager::APPLY_TWEAK_AUTO ) {
			$this->applier->queue($tweak);
		}
	}

	public function addAlias(ameTweakAlias $alias) {
		$this->aliases[] = $alias;
	}

	public function addSection($id, $label, $priority = null): ameTweakSection {
		$section = new ameTweakSection($id, $label);
		if ( $priority !== null ) {
			$section->setPriority($priority);
		}
		$this->sections[$section->getId()] = $section;

		return $section;
	}

	public function addSectionInstance(ameTweakSection $section) {
		$this->sections[$section->getId()] = $section;
	}

	/**
	 * @return array<string, ameBaseTweak>
	 */
	public function getTweaks(): array {
		return $this->tweaks;
	}

	/**
	 * @return array<string, ameTweakAlias>
	 */
	public function getAliases(): array {
		return $this->aliases;
	}

	/**
	 * @return array<string, ameTweakSection>
	 */
	public function getSections(): array {
		return $this->sections;
	}

	public function getSection($id): ?ameTweakSection {
		return \ameUtils::get($this->sections, $id, null);
	}
}