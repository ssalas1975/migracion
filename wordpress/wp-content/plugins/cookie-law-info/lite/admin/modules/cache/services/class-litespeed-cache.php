<?php
/**
 * LiteSpeed Cache integration.
 *
 * @package CookieYes\Lite\Includes
 */

namespace CookieYes\Lite\Admin\Modules\Cache\Services;

use CookieYes\Lite\Admin\Modules\Cache\Services\Services;
use CookieYes\Lite\Integrations\Wp_Google_Maps\Wp_Google_Maps;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Purges LiteSpeed's page cache when CookieYes settings change, and keeps the
 * banner out of LiteSpeed's CSS and JavaScript optimizations.
 */
class Litespeed_Cache extends Services {

	/**
	 * Whether the page cache was already purged in this request. One save
	 * fires several cky_after_* actions.
	 *
	 * @var boolean
	 */
	private static $purged = false;

	/**
	 * Load hooks of each plugin.
	 *
	 * @return void
	 */
	public function run() {
		$this->load_hooks();
		add_filter( 'litespeed_optimize_js_excludes', array( $this, 'exclude_scripts' ) );
		add_filter( 'litespeed_optm_js_defer_exc', array( $this, 'exclude_scripts' ) );
		add_filter( 'litespeed_optm_gm_js_exc', array( $this, 'exclude_scripts' ) );
		add_filter( 'litespeed_optimize_css_excludes', array( $this, 'exclude_styles' ) );
		add_filter( 'litespeed_iframe_lazyload_exc', array( $this, 'exclude_from_lazyload' ), 10, 2 );
	}

	/**
	 * Keep the banner scripts out of LiteSpeed's JavaScript optimizations, so
	 * the banner, Google Consent Mode and the blocking start on page load:
	 * - JS Excludes (minify/combine): otherwise the connected-mode banner is
	 *   copied into LiteSpeed's combined file instead of loading from the
	 *   CookieYes CDN, and the banner can appear late. An excluded script is
	 *   still deferred or delayed, hence the next two lists;
	 * - JS Deferred/Delayed Excludes: otherwise Delay JS waits for the first
	 *   interaction, so ad and tracking scripts LiteSpeed never delays (Google
	 *   tags, AdSense) run before the blocking starts;
	 * - Guest Mode JS Excludes: in Guest Mode this list replaces the previous
	 *   one, and is empty by default, so Google Consent Mode would report no
	 *   consent for public visitors.
	 *
	 * LiteSpeed matches each pattern as a plain substring of a script's src, or
	 * of an inline script's code (never its attributes, so ids do not work):
	 * - client_data/: the connected-mode banner on the CookieYes CDN;
	 * - <plugin folder>/lite/frontend/js/: the plugin's script files;
	 * - _ckyConfig, _ckyGcm, _ckyGsk: the inline banner config, Google Consent
	 *   Mode settings and WP Consent API flag, by content.
	 * A bare "cookie-law-info" is not used: it would also pull third-party
	 * inline scripts that mention #cookie-law-info-bar out of the delay, and
	 * they would then run before the delayed jQuery and fail.
	 *
	 * @param array $exclusions Patterns LiteSpeed excludes.
	 * @return array
	 */
	public function exclude_scripts( $exclusions ) {
		return self::add_patterns( $exclusions, self::script_patterns() );
	}

	/**
	 * Keep the banner's pre-render hide rule (<style id="cky-style-inline">) out
	 * of LiteSpeed's CSS combine. With "CSS Combine External and Inline" (on by
	 * default, and forced by Guest Optimization) LiteSpeed moves the rule into
	 * its combined stylesheet, where the banner script can no longer remove it,
	 * and the banner stays hidden. LiteSpeed matches CSS patterns against the
	 * whole tag.
	 *
	 * @param array $exclusions Patterns LiteSpeed excludes.
	 * @return array
	 */
	public function exclude_styles( $exclusions ) {
		return self::add_patterns( $exclusions, array( 'cky-style-inline' ) );
	}

	/**
	 * Keep Google Maps embeds out of Lazy Load Iframes. Lazy load replaces the
	 * iframe src with about:blank, which hides it from the banner's blocking,
	 * so the map would load without consent.
	 *
	 * @param boolean $excluded Whether the iframe is already excluded.
	 * @param string  $src      The iframe src.
	 * @return boolean
	 */
	public function exclude_from_lazyload( $excluded, $src = '' ) {
		if ( $excluded ) {
			return $excluded;
		}
		return is_string( $src ) && false !== strpos( $src, Wp_Google_Maps::COOKIE_URL_PATTERN );
	}

	/**
	 * Patterns that match the plugin's script files and inline scripts.
	 *
	 * @return array
	 */
	public static function script_patterns() {
		return array( 'client_data/', self::script_path_pattern(), '_ckyConfig', '_ckyGcm', '_ckyGsk' );
	}

	/**
	 * Append patterns to a LiteSpeed exclude list, keeping the site owner's
	 * entries and skipping patterns already in it.
	 *
	 * @param array $exclusions Patterns LiteSpeed excludes.
	 * @param array $patterns   Patterns to add.
	 * @return array
	 */
	private static function add_patterns( $exclusions, $patterns ) {
		$exclusions = is_array( $exclusions ) ? $exclusions : array();
		foreach ( $patterns as $pattern ) {
			if ( ! in_array( $pattern, $exclusions, true ) ) {
				$exclusions[] = $pattern;
			}
		}
		return $exclusions;
	}

	/**
	 * Check if the the cache service is installed/active;
	 *
	 * @return boolean
	 */
	public function is_active() {
		return class_exists( \LiteSpeed\Purge::class );
	}

	/**
	 * Purge LiteSpeed's page cache, at most once per request. The banner config
	 * is in the page HTML, so the page cache is all that goes stale. A full
	 * purge would also delete LiteSpeed's optimized CSS and JS files, which
	 * breaks the styling of any copy of a page cached elsewhere (a CDN, the
	 * browser) until that copy expires. LiteSpeed before 7.7 has no page-cache
	 * only purge, so there it purges everything, as before.
	 *
	 * @param boolean|array $clear False to skip the purge.
	 * @return void
	 */
	public function clear_cache( $clear = true ) {
		if ( false === $clear || self::$purged ) {
			return;
		}
		self::$purged = true;
		if ( method_exists( \LiteSpeed\Purge::class, 'purge_all_lscache' ) ) {
			\LiteSpeed\Purge::purge_all_lscache( 'CookieYes settings updated' );
			return;
		}
		\LiteSpeed\Purge::purge_all( 'CookieYes settings updated' );
	}

}
