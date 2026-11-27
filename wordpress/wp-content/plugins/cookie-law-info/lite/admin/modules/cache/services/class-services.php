<?php
/**
 * Abstract class to handle all the modules on the plugin.
 *
 * @package CookieYes\Lite\Includes
 */

namespace CookieYes\Lite\Admin\Modules\Cache\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Module
 */
abstract class Services {

	/**
	 * Module constructor.
	 */
	public function __construct() {
		if ( $this->is_active() ) {
			$this->run();
		}
	}

	/**
	 * Load plugin hooks
	 *
	 * @return void
	 */
	public function load_hooks() {
		add_action( 'cky_after_update_banner', array( $this, 'clear_cache' ), 10, 1 );
		add_action( 'cky_after_update_cookie', array( $this, 'clear_cache' ) );
		add_action( 'cky_after_update_cookie_category', array( $this, 'clear_cache' ) );
		add_action( 'cky_after_connect', array( $this, 'clear_cache' ) );
		add_action( 'cky_after_update_settings', array( $this, 'clear_cache' ), 10, 1 );
		add_action( 'cky_after_activate', array( $this, 'clear_cache' ) );
		add_action( 'cky_clear_cache', array( $this, 'clear_cache' ) );
	}

	/**
	 * Path pattern for the plugin's front-end script files, for optimizers
	 * that match a script's URL or file path.
	 *
	 * The folder name must be the one in the script URL. It comes from
	 * plugin_basename(), which WordPress maps back to the plugins folder for a
	 * symlinked install; the folder of CLI_PLUGIN_BASEPATH would be the
	 * symlink's target instead.
	 *
	 * @return string
	 */
	public static function script_path_pattern() {
		return dirname( CLI_PLUGIN_BASENAME ) . '/lite/frontend/js/';
	}

	/**
	 * Check if the the cache service is installed/active;
	 *
	 * @return boolean
	 */
	abstract public function is_active();

	/**
	 * Initializes the module. Always executed even if the module is deactivated.
	 *
	 * Do not use __construct in subclasses, use init() instead
	 */
	abstract public function clear_cache();

	/**
	 * Initializes the module. Always executed even if the module is deactivated.
	 *
	 * Do not use __construct in subclasses, use init() instead
	 */
	abstract public function run();
}
