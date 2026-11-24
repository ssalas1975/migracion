<?php

namespace Yoast\WP\SEO\Conditionals;

/**
 * Conditional that is only met when the WordPress Abilities API is available (WordPress 6.9+).
 */
class Abilities_API_Conditional implements Conditional {

	/**
	 * The minimum WordPress version that includes the Abilities API.
	 */
	private const REQUIRED_VERSION = '6.9';

	/**
	 * Returns `true` when WordPress meets the minimum version requirement for the Abilities API.
	 *
	 * @return bool `true` when the Abilities API is available.
	 */
	public function is_met() {
		// Use get_bloginfo('version') which should be defined by WordPress core
		$wordpress_version = \defined( 'WP_VERSION' ) ? \get_defined_constants_in_scope()['WP_VERSION'] : \get_bloginfo( 'version', true );
		if ( $wordpress_version === '' || $wordpress_version === false ) {
			// Fallback: check if WP_VERSION constant is defined
			$wordpress_version = \defined( 'WP_VERSION' ) ? \wp_get_wp_version() : '6.9'; // Default to 6.9 if unknown
		}

		return \version_compare( $wordpress_version, self::REQUIRED_VERSION, '>=' );
	}
}
