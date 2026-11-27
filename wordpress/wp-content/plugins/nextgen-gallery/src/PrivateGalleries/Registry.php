<?php
/**
 * Cached list of the private galleries that have a client page.
 *
 * @package Imagely\NGG\PrivateGalleries
 */

namespace Imagely\NGG\PrivateGalleries;

use Imagely\NGG\Util\Transient;

/**
 * One autoloaded option listing every set up private gallery: its page carries the client mark,
 * or the page is gone (still hidden, shown as "Page missing").
 */
class Registry {

	/**
	 * Option holding the map.
	 *
	 * @var string
	 */
	const OPTION = 'ngg_private_galleries';

	/**
	 * Post meta key marking a page as a client page. One row per gallery id.
	 *
	 * @var string
	 */
	const PAGE_MARK = '_ngg_client_gallery';

	/**
	 * Option holding the last failed rebuild: [ 'error' => database error, 'time' => timestamp ].
	 * Present only while the stored map may be out of date.
	 *
	 * @var string
	 */
	const ERROR_OPTION = 'ngg_private_galleries_error';

	/**
	 * Seconds between automatic retries of a failed rebuild from wp-admin.
	 *
	 * @var int
	 */
	const RETRY_INTERVAL = 300;

	/**
	 * Request cache of the option.
	 *
	 * @var array<int,array{p:int,a:int,x:array}>|null
	 */
	private static $map = null;

	/**
	 * Every set up private gallery.
	 *
	 * @return array<int,array{p:int,a:int}> Gallery id => [ 'p' => page id, 'a' => author id ].
	 */
	public static function get_all() {
		if ( null === self::$map ) {
			$stored    = get_option( self::OPTION, [] );
			self::$map = [];

			foreach ( is_array( $stored ) ? $stored : [] as $gid => $row ) {
				if ( is_array( $row ) && isset( $row['p'] ) ) {
					self::$map[ (int) $gid ] = [
						'p' => (int) $row['p'],
						'a' => isset( $row['a'] ) ? (int) $row['a'] : 0,
						'x' => isset( $row['x'] ) && is_array( $row['x'] ) ? $row['x'] : [],
					];
				}
			}
		}

		return self::$map;
	}

	/**
	 * Whether a gallery is a set up private gallery.
	 *
	 * @param int|string $gallery_id Gallery id.
	 * @return bool
	 */
	public static function is_private( $gallery_id ) {
		$map = self::get_all();
		return isset( $map[ (int) $gallery_id ] );
	}

	/**
	 * The client page of a set up private gallery.
	 *
	 * @param int|string $gallery_id Gallery id.
	 * @return int 0 when the gallery is not a set up private gallery.
	 */
	public static function page_for( $gallery_id ) {
		$map = self::get_all();
		return isset( $map[ (int) $gallery_id ] ) ? $map[ (int) $gallery_id ]['p'] : 0;
	}

	/**
	 * The author of a set up private gallery.
	 *
	 * @param int|string $gallery_id Gallery id.
	 * @return int
	 */
	public static function author_of( $gallery_id ) {
		$map = self::get_all();
		return isset( $map[ (int) $gallery_id ] ) ? $map[ (int) $gallery_id ]['a'] : 0;
	}

	/**
	 * Extra data an add-on stored for a private gallery (see ngg_private_galleries_map).
	 *
	 * @param int|string $gallery_id Gallery id.
	 * @return array
	 */
	public static function extra( $gallery_id ) {
		$map = self::get_all();
		return isset( $map[ (int) $gallery_id ] ) ? $map[ (int) $gallery_id ]['x'] : [];
	}

	/**
	 * The private galleries whose client page is the given page.
	 *
	 * @param int $page_id Page id.
	 * @return int[]
	 */
	public static function galleries_for_page( $page_id ) {
		$page_id = (int) $page_id;
		$retval  = [];

		if ( $page_id <= 0 ) {
			return $retval;
		}

		foreach ( self::get_all() as $gid => $row ) {
			if ( $row['p'] === $page_id ) {
				$retval[] = (int) $gid;
			}
		}

		return $retval;
	}

	/**
	 * Every client page id.
	 *
	 * @return int[]
	 */
	public static function page_ids() {
		$ids = [];

		foreach ( self::get_all() as $row ) {
			$ids[ $row['p'] ] = $row['p'];
		}

		return array_values( $ids );
	}

	/**
	 * Whether a page is the client page of at least one private gallery.
	 *
	 * @param int $page_id Page id.
	 * @return bool
	 */
	public static function is_client_page( $page_id ) {
		return in_array( (int) $page_id, self::page_ids(), true );
	}

	/**
	 * Rebuilds the option from the gallery table and the page marks.
	 *
	 * @return array<int,array{p:int,a:int}> The new map.
	 */
	public static function rebuild() {
		global $wpdb;

		// The page marks are read on their own and matched in PHP. Comparing meta_value with
		// anything built from the gallery id in SQL compares two strings of different collations
		// whenever the postmeta table and the connection disagree (a table left on
		// utf8mb4_unicode_ci under a utf8mb4_unicode_520_ci connection), and the server refuses
		// it with "Illegal mix of collations" (#1287). These two reads compare no strings across
		// columns, so no collation is involved.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- This query is what fills the cache; it has no input.
		$rows = $wpdb->get_results(
			"SELECT g.gid, g.pageid, g.author, p.ID AS page_id FROM {$wpdb->nggallery} g
			LEFT JOIN {$wpdb->posts} p ON p.ID = g.pageid
			WHERE g.is_private = 1 AND g.pageid > 0"
		);

		// A failed read is not "no private galleries": keep the stored list instead of unhiding them all.
		if ( self::read_failed( $rows ) ) {
			return self::get_all();
		}

		$has_live_page = false;

		foreach ( $rows as $row ) {
			if ( null !== $row->page_id ) {
				$has_live_page = true;
				break;
			}
		}

		$marks = [];

		// Only needed when a gallery's page still exists; a gallery whose page is gone stays listed.
		if ( $has_live_page ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- This query is what fills the cache.
			$mark_rows = $wpdb->get_results(
				$wpdb->prepare( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s", self::PAGE_MARK )
			);

			if ( self::read_failed( $mark_rows ) ) {
				return self::get_all();
			}

			foreach ( $mark_rows as $mark ) {
				$marks[ (int) $mark->post_id ][ (string) $mark->meta_value ] = true;
			}
		}

		$map = [];

		foreach ( $rows as $row ) {
			// The same rule as ClientPage::page_has_mark(): the mark holds the gallery id as written.
			if ( null !== $row->page_id && ! isset( $marks[ (int) $row->pageid ][ (string) (int) $row->gid ] ) ) {
				continue;
			}

			$map[ (int) $row->gid ] = [
				'p' => (int) $row->pageid,
				'a' => (int) $row->author,
				'x' => [],
			];
		}

		/**
		 * Filters the private gallery list before it is cached. Add-ons store their per-gallery
		 * data under 'x' so reading it costs no query.
		 *
		 * @param array $map Gallery id => [ 'p' => page id, 'a' => author id, 'x' => extra ].
		 */
		$map = (array) apply_filters( 'ngg_private_galleries_map', $map );

		ksort( $map );

		// Compared with the stored list, not this request's copy: after a save that failed, the copy
		// is the unsaved list, and a later rebuild in the same request has to try the save again.
		self::$map = null;
		$previous  = self::get_all();
		self::$map = $map;

		if ( $previous !== $map || false === get_option( self::OPTION, false ) ) {
			// update_option() is also false for a value it did not need to write, so a failed save
			// is one that left a different value stored. Until it is saved, other requests still
			// read the old list: record it, and leave the change hooks to the retry that saves it.
			$saved = update_option( self::OPTION, $map, true );
			$error = (string) $wpdb->last_error;

			if ( ! $saved && maybe_serialize( get_option( self::OPTION ) ) !== maybe_serialize( $map ) ) {
				self::record_failure( $error );

				return $map;
			}

			// Rendered output and the galleries table both depend on who is private.
			Transient::flush( 'displayed_gallery_rendering' );
			Transient::flush( 'rest_galleries' );

			/**
			 * Fires after the set of private galleries changed.
			 *
			 * @param array $map      Gallery id => [ 'p' => page id, 'a' => author id ].
			 * @param array $previous The previous map.
			 */
			do_action( 'ngg_private_galleries_changed', $map, $previous );
		}

		// The stored list is up to date.
		self::clear_failure();

		return $map;
	}

	/**
	 * Retries a failed rebuild, at most once per RETRY_INTERVAL, so a list left out of date by a
	 * database error catches up once the database answers again, without waiting for the next
	 * privacy change. Hooked to admin_init.
	 *
	 * @return void
	 */
	public static function retry_failed_rebuild() {
		if ( wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$failure = get_option( self::ERROR_OPTION, false );

		if ( ! is_array( $failure ) ) {
			return;
		}

		if ( isset( $failure['time'] ) && time() - (int) $failure['time'] < self::RETRY_INTERVAL ) {
			return;
		}

		self::rebuild();
	}

	/**
	 * Tells administrators that the private gallery list could not be updated. Until it is,
	 * a gallery made private may still be shown to everyone, which is not something to fail at
	 * silently. Hooked to admin_notices.
	 *
	 * Capability-gated like the plugin's other database error notices: the message carries the
	 * database's own error, which is for whoever can act on it.
	 *
	 * @return void
	 */
	public static function render_failure_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$failure = get_option( self::ERROR_OPTION, false );

		if ( ! is_array( $failure ) ) {
			return;
		}

		$error  = isset( $failure['error'] ) ? (string) $failure['error'] : '';
		$detail = __( 'Galleries made private may still be visible to everyone until it can. It is retried automatically.', 'nggallery' );

		if ( '' !== $error ) {
			/* translators: %s: database error message */
			$detail = sprintf( __( 'Galleries made private may still be visible to everyone until it can. It is retried automatically. Database error: %s', 'nggallery' ), $error );
		}

		printf(
			'<div class="notice notice-error"><p><strong>%1$s</strong> %2$s</p></div>',
			esc_html__( 'NextGEN Gallery could not update its list of private galleries.', 'nggallery' ),
			esc_html( $detail )
		);
	}

	/**
	 * Whether a read for rebuild() failed. A failure is recorded for render_failure_notice().
	 *
	 * @param mixed $result What $wpdb->get_results() returned.
	 * @return bool
	 */
	private static function read_failed( $result ) {
		global $wpdb;

		if ( is_array( $result ) && $wpdb->ready && empty( $wpdb->last_error ) ) {
			return false;
		}

		// Without a connection wpdb returns before it clears last_error, so it is not this read's.
		self::record_failure( $wpdb->ready ? (string) $wpdb->last_error : '' );

		return true;
	}

	/**
	 * Records a failed rebuild.
	 *
	 * @param string $error Database error.
	 * @return void
	 */
	private static function record_failure( $error ) {
		update_option(
			self::ERROR_OPTION,
			[
				'error' => substr( $error, 0, 1000 ),
				'time'  => time(),
			],
			false
		);
	}

	/**
	 * Forgets a recorded failure after a rebuild that worked.
	 *
	 * @return void
	 */
	private static function clear_failure() {
		if ( false !== get_option( self::ERROR_OPTION, false ) ) {
			delete_option( self::ERROR_OPTION );
		}
	}

	/**
	 * Forgets the request cache (tests, and after another process changed the option).
	 *
	 * @return void
	 */
	public static function reset() {
		self::$map = null;
	}
}
