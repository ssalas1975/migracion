<?php

namespace YahnisElsts\AdminMenuEditor\Tweaks\Core;

class TweakApplier {
	const STAGE_EARLY = 1;
	const STAGE_INIT = 2;
	const STAGE_SCREEN = 3;

	const STAGES_IN_ORDER = [
		self::STAGE_EARLY,
		self::STAGE_INIT,
		self::STAGE_SCREEN,
	];

	/**
	 * @var array<int, array<string, ameBaseTweak>>
	 */
	private array $pendingTweaks = [
		self::STAGE_EARLY  => [],
		self::STAGE_INIT   => [],
		self::STAGE_SCREEN => [],
	];

	private ameTweakManager $tweakManager;

	public function __construct(ameTweakManager $tweakManager) {
		$this->tweakManager = $tweakManager;
	}

	public function queue(ameBaseTweak $tweak) {
		if ( $tweak->hasScreenFilter() ) {
			$stage = self::STAGE_SCREEN;
			$this->ensureScreenHookAdded();
		} elseif ( $tweak->wantsToRunEarly() ) {
			$stage = self::STAGE_EARLY;
		} else {
			$stage = self::STAGE_INIT;
		}
		$this->pendingTweaks[$stage][$tweak->getId()] = $tweak;
	}

	public function processQueuedTweaks(int $currentStage) {
		$settings = $this->tweakManager->getEnabledTweakSettings();

		foreach (self::STAGES_IN_ORDER as $stage) {
			if ( $stage > $currentStage ) {
				break;
			}

			$tweaksForStage = $this->pendingTweaks[$stage] ?? [];
			foreach ($tweaksForStage as $tweakId => $tweak) {
				unset($this->pendingTweaks[$stage][$tweakId]);
				$this->processTweak($tweak, $settings);
			}
		}
	}

	private function processTweak(ameBaseTweak $tweak, $settings) {
		if ( empty($settings[$tweak->getId()]) ) {
			return; //This tweak is not enabled for the current user.
		}

		if ( !$tweak->isEnabledForCurrentScreen() ) {
			return;
		}

		$settingsForThisTweak = null;
		if ( $tweak->supportsUserInput() ) {
			$settingsForThisTweak = \ameUtils::get($settings, [$tweak->getId()], []);
		}
		$tweak->apply($settingsForThisTweak);
	}

	public function processTweaksImmediately(array $tweaks) {
		$settings = $this->tweakManager->getEnabledTweakSettings();
		foreach ($tweaks as $tweak) {
			$this->processTweak($tweak, $settings);
		}
	}

	private bool $screenHookAdded = false;

	private function ensureScreenHookAdded() {
		if ( $this->screenHookAdded ) {
			return;
		}
		add_action('current_screen', [$this, 'onCurrentScreen'], 10, 0);
		$this->screenHookAdded = true;
	}

	public function onCurrentScreen() {
		$this->processQueuedTweaks(self::STAGE_SCREEN);
	}
}