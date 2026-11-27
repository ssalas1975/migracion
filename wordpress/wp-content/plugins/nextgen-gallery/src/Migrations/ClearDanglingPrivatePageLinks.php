<?php
/**
 * Clears page links that point nowhere on galleries flagged private before client pages existed.
 *
 * @package Imagely\NGG\Migrations
 */

namespace Imagely\NGG\Migrations;

use Imagely\NGG\PrivateGalleries\Registry;
use Imagely\NGG\Settings\ManagerBase;

/**
 * A gallery whose page is gone counts as a private gallery with a missing client page. Before
 * client pages existed such a link meant nothing, so it is cleared once and the gallery stays visible.
 */
class ClearDanglingPrivatePageLinks {

	/**
	 * Setting that records this has already run.
	 *
	 * @var string
	 */
	const SETTING = 'private_gallery_dangling_links_cleared';

	/**
	 * Clears the links. Mutates the settings marker without saving; the caller saves.
	 *
	 * @param ManagerBase $settings Settings to update.
	 * @return bool Whether this ran.
	 */
	public static function apply( ManagerBase $settings ) {
		if ( $settings->get( self::SETTING ) ) {
			return false;
		}

		global $wpdb;

		// is_private is not in the gallery table's CREATE TABLE; the data mapper adds it lazily on
		// first construction, which has not happened yet at this point in the install. Reading the
		// column before then raises a database error, so resolve it from the live schema instead.
		// The existence check runs first because SHOW COLUMNS errors on a missing table.
		if ( true !== \Imagely\NGG\Util\Schema::table_exists( $wpdb->nggallery ) ) {
			$settings->set( self::SETTING, true );

			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Schema probe, one-time upgrade step.
		$columns = $wpdb->get_col( 'SHOW COLUMNS FROM `' . \esc_sql( $wpdb->nggallery ) . '`' );

		// No column means no gallery can carry the private flag, so there is no dangling link to
		// clear. Recorded as done either way, for the reason given at the marker below.
		if ( ! in_array( 'is_private', (array) $columns, true ) ) {
			$settings->set( self::SETTING, true );

			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time upgrade step.
		$gallery_ids = $wpdb->get_col(
			"SELECT g.gid FROM {$wpdb->nggallery} g LEFT JOIN {$wpdb->posts} p ON p.ID = g.pageid
			WHERE g.is_private = 1 AND g.pageid > 0 AND p.ID IS NULL"
		);

		// Runs once, on the upgrade that adds client pages, so no link here is a real client page yet.
		$gallery_ids = array_map( 'intval', (array) $gallery_ids );

		if ( $gallery_ids ) {
			$placeholders = implode( ',', array_fill( 0, count( $gallery_ids ), '%d' ) );

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- $placeholders is a list of %d.
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->nggallery} SET pageid = 0 WHERE gid IN ( {$placeholders} )", array_values( $gallery_ids ) ) );
		}

		// Recorded as done even if a query above failed, on purpose: this clears links only while
		// none of them can be a real client page yet, and the marker is the only thing holding it
		// to that one upgrade. A later run would zero the pageid of a private gallery whose page
		// row is gone, dropping it out of Registry::rebuild()'s `pageid > 0` and unhiding it.
		$settings->set( self::SETTING, true );

		Registry::rebuild();

		return true;
	}
}
