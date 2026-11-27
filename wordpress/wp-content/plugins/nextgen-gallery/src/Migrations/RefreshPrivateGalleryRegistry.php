<?php
/**
 * Rebuilds the private gallery list on every upgrade.
 *
 * @package Imagely\NGG\Migrations
 */

namespace Imagely\NGG\Migrations;

use Imagely\NGG\PrivateGalleries\Registry;

/**
 * The list is rebuilt only when privacy changes. A rebuild that failed left it out of date until
 * the next change: on 4.6.0 every rebuild failed on databases whose postmeta collation differs from
 * the connection's (#1287), so galleries made private there were never listed and stayed public.
 * Rebuilding once per upgrade brings such a list up to date. Nothing changes when it already is.
 */
class RefreshPrivateGalleryRegistry {

	/**
	 * Rebuilds the list when the gallery table can be read for it.
	 *
	 * @return bool Whether a rebuild ran.
	 */
	public static function apply() {
		global $wpdb;

		// is_private is added to the gallery table lazily by the data mapper, so on a fresh
		// install it may not exist yet; reading it then is a database error, and with no column
		// no gallery is private. SHOW COLUMNS errors on a missing table, hence the check first.
		if ( true !== \Imagely\NGG\Util\Schema::table_exists( $wpdb->nggallery ) ) {
			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Schema probe, once per upgrade.
		$columns = $wpdb->get_col( 'SHOW COLUMNS FROM `' . \esc_sql( $wpdb->nggallery ) . '`' );

		if ( ! in_array( 'is_private', (array) $columns, true ) ) {
			return false;
		}

		Registry::rebuild();

		return true;
	}
}
