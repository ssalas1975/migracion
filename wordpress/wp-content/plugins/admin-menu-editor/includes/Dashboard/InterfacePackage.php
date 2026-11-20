<?php

namespace YahnisElsts\AdminMenuEditor\Dashboard;

use YahnisElsts\AdminMenuEditor\Utils\LazyValue;
use YahnisElsts\AdminMenuEditor\WebpackRegistry\WebpackAssetRegistry;
use YahnisElsts\WpDependencyWrapper\v1\ScriptDependency;

class InterfacePackage {
	protected DashboardServiceRegistry $serviceRegistry;
	protected ?\ameModule $module = null;

	protected array $localStyles = [];

	public function __construct(DashboardServiceRegistry $serviceRegistry, ?\ameModule $module = null) {
		$this->serviceRegistry = $serviceRegistry;
		$this->module = $module;
	}

	public function enqueueScripts() {
		//Nothing here yet. Subclasses can override this to enqueue scripts.
	}

	public function enqueueStyles() {
		if ( !empty($this->localStyles) ) {
			if ( $this->module === null ) {
				throw new \RuntimeException('Cannot enqueue local styles without a module.');
			}
			foreach ($this->localStyles as $handle => $relativePath) {
				list($styleUrl, $version) = $this->module->findModuleBrowserDependency($relativePath);
				wp_enqueue_style($handle, $styleUrl, [], $version);
			}
		}
	}

	//region Rendering

	public function displayContent() {
		//This permissions check is redundant in most cases, but the method can technically be called directly.
		if ( !$this->currentUserCanView() ) {
			echo 'Error: You do not have permission to view this content.';
			return;
		}

		if ( $this->moduleTemplateName && $this->module ) {
			$templatePath = $this->module->getModuleDir() . '/' . $this->moduleTemplateName . '-template.php';
			if ( file_exists($templatePath) ) {
				$moduleTabUrl = $this->module->getTabUrl();

				$templateVariables = $this->templateVariables !== null ? $this->templateVariables->get() : null;
				if ( !empty($templateVariables) ) {
					extract($templateVariables, EXTR_SKIP);
				}
				include $templatePath;
			} else {
				echo '<p>Template not found: ' . esc_html($this->moduleTemplateName) . '</p>';
			}
		} else {
			echo '<p>No template specified for this interface package.</p>';
		}
	}

	public function displayPage() {
		if ( !$this->currentUserCanView() ) {
			echo 'Error: You do not have permission to view this page.';
			return;
		}

		$menuEditor = $this->serviceRegistry->getMenuEditor();
		if ( $this->wantsTabHeader() ) {
			$menuEditor->display_settings_page_header($this->getWrapClasses());
		}

		$this->displayContent();

		if ( $this->wantsTabFooter() ) {
			$menuEditor->display_settings_page_footer();
		}
	}

	//endregion

	//region Utilities & Services

	protected function isProVersion(): bool {
		$menuEditor = $this->serviceRegistry->getMenuEditor();
		return $menuEditor->is_pro_version();
	}

	protected function bundlesEnabled(): bool {
		return defined('WS_AME_USE_BUNDLES') && WS_AME_USE_BUNDLES;
	}

	protected function webpackRegistry(): ?WebpackAssetRegistry {
		$menuEditor = $this->serviceRegistry->getMenuEditor();
		return $menuEditor->get_webpack_registry();
	}

	protected function baseDependencies(): \ameBaseScriptDependencies {
		$menuEditor = $this->serviceRegistry->getMenuEditor();
		return $menuEditor->get_base_dependencies();
	}

	//endregion

	//region Configuration helpers

	/**
	 * Adds a local CSS file to be enqueued when this interface package is used.
	 *
	 * @param string $relativePath Relative path to the CSS file within the module.
	 * @param string|null $handle Optional handle for the style.
	 * @return $this
	 */
	public function addLocalStyle(string $relativePath, ?string $handle = null): self {
		if ( $handle === null ) {
			$handle = 'als-' . basename($relativePath, '.css');
		}
		$this->localStyles[$handle] = $relativePath;
		return $this;
	}

	public function localScript(string $relativePath, ?string $handle = null): ScriptDependency {
		if ( $this->module === null ) {
			throw new \RuntimeException('Cannot create local script dependency without a module.');
		}
		$moduleDir = $this->module->getModuleDir();

		$relativePath = ltrim($relativePath, '/');
		$fullPath = $moduleDir . '/' . $relativePath;

		return ScriptDependency::create(
			plugins_url($relativePath, $moduleDir . '/dummy.php'),
			$handle,
			$fullPath
		);
	}

	protected ?string $moduleTemplateName = null;
	protected ?LazyValue $templateVariables = null;

	/**
	 * @param string $templateName
	 * @param callable|array|null $templateVariables
	 * @return $this
	 */
	public function setModuleTemplate(string $templateName, $templateVariables = null): self {
		if ( $this->module === null ) {
			throw new \RuntimeException('Cannot set module template without a module.');
		}
		$this->moduleTemplateName = $templateName;

		if ( is_callable($templateVariables) ) {
			$this->templateVariables = new LazyValue($templateVariables);
		} else {
			$this->templateVariables = new LazyValue(function () use ($templateVariables) {
				return is_array($templateVariables) ? $templateVariables : [];
			});
		}
		return $this;
	}

	protected bool $tabHeaderEnabled = false;
	protected bool $tabFooterEnabled = false;

	public function enableHeaderAndFooter(): self {
		$this->tabHeaderEnabled = true;
		$this->tabFooterEnabled = true;
		return $this;
	}

	public function wantsTabHeader(): bool {
		return $this->tabHeaderEnabled;
	}

	public function wantsTabFooter(): bool {
		return $this->tabFooterEnabled;
	}

	protected array $wrapClasses = [];

	/**
	 * @param string ...$classes
	 * @return $this
	 */
	public function addWrapClasses(string ...$classes): self {
		$this->wrapClasses = array_merge($this->wrapClasses, $classes);
		return $this;
	}

	public function getWrapClasses(): array {
		return $this->wrapClasses;
	}
	//endregion

	//region Permissions

	public function currentUserCanView(): bool {
		return $this->serviceRegistry->getMenuEditor()->current_user_can_edit_menu();
	}

	//endregion
}