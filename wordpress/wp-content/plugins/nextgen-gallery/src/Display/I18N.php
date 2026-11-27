<?php

namespace Imagely\NGG\Display;

use Imagely\NGG\DataMappers\DisplayedGallery as DisplayedGalleryMapper;
use Imagely\NGG\DataMappers\Gallery as GalleryMapper;
use Imagely\NGG\Util\Security;
use Imagely\NGG\Util\URL;

/**
 * Internationalization helper class.
 */
class I18N {

	/**
	 * Option holding the gallery-strings backfill cursor: the last gallery ID registered, or 'done'.
	 */
	const GALLERY_STRINGS_BACKFILL_OPTION = 'ngg_wpml_gallery_strings_backfill';

	/**
	 * Galleries registered per admin request by the backfill.
	 */
	const GALLERY_STRINGS_BACKFILL_BATCH = 100;

	/**
	 * Singleton instance.
	 *
	 * @var I18N|null
	 */
	protected static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return I18N
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new I18N();
		}
		return self::$instance;
	}

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks() {
		// Load translations early to avoid WordPress 6.7+ warnings about translations loading too early
		// This must happen before admin_menu where translation functions are used
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ], 10 );
		add_action( 'init', [ $this, 'register_translation_hooks' ], 2 );
	}

	/**
	 * Register translation hooks for WPML and other translation plugins.
	 */
	public function load_textdomain() {
		$dir = \str_replace(
			\wp_normalize_path( WP_PLUGIN_DIR ),
			'',
			\wp_normalize_path( NGG_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'I18N' )
		);

		// Load text domain.
		\load_plugin_textdomain( 'nggallery', false, $dir );
	}

	public function register_translation_hooks() {

		// Hooks to register image, gallery, and album name & description with WPML.
		\add_action( 'ngg_image_updated', [ $this, 'register_image_strings' ] );
		\add_action( 'ngg_album_updated', [ $this, 'register_album_strings' ] );
		\add_action( 'ngg_created_new_gallery', [ $this, 'register_gallery_strings' ] );
		\add_action( 'ngg_gallery_updated', [ $this, 'register_gallery_strings' ] );

		// Galleries renamed before ngg_gallery_updated existed, or saved while String Translation was inactive.
		\add_action( 'admin_init', [ $this, 'backfill_gallery_strings' ] );

		// do not let WPML translate posts we use as a document store.
		\add_filter( 'get_translatable_documents', [ $this, 'wpml_translatable_documents' ] );

		if ( \class_exists( 'SitePress' ) ) {
			// Copy AttachToPost entries when duplicating posts to another language.
			\add_action( 'icl_make_duplicate', [ $this, 'wpml_adjust_gallery_id' ], 10, 4 );
			\add_action( 'save_post', [ $this, 'wpml_set_gallery_language_on_save_post' ], 101, 1 );
		}

		// see function comments.
		\add_filter( 'ngg_displayed_gallery_cache_params', [ $this, 'set_qtranslate_cache_parameters' ] );
		\add_filter( 'ngg_displayed_gallery_cache_params', [ $this, 'set_wpml_cache_parameters' ] );
	}

	/**
	 * When QTranslate is active we must add its language & url-mode settings as display parameters
	 * so as to generate a unique cache for each language.
	 *
	 * @param array $arr Display parameters.
	 * @return array
	 */
	public function set_qtranslate_cache_parameters( $arr ) {
		if ( empty( $GLOBALS['q_config'] ) || ! \defined( 'QTRANS_INIT' ) ) {
			return $arr;
		}

		global $q_config;
		$arr['qtranslate_language'] = $q_config['language'];
		$arr['qtranslate_url_mode'] = $q_config['url_mode'];

		return $arr;
	}

	/**
	 * See notes on set_qtranslate_cache_paramters()
	 *
	 * @param array $arr Display parameters.
	 * @return array
	 */
	public function set_wpml_cache_parameters( $arr ) {
		if ( empty( $GLOBALS['sitepress'] ) || ! \defined( 'WPML_ST_VERSION' ) ) {
			return $arr;
		}

		global $sitepress;
		$settings             = $sitepress->get_settings();
		$arr['wpml_language'] = ICL_LANGUAGE_CODE;
		$arr['wpml_url_mode'] = $settings['language_negotiation_type'];

		return $arr;
	}

	/**
	 * Registers gallery strings with WPML
	 *
	 * The stored row is read directly rather than through GalleryMapper::find(): outside is_admin()
	 * (REST, Lightroom) find() runs set_defaults(), which returns the current language's translation,
	 * and registering that would make the translation the WPML original. The front end passes the
	 * stored title to I18N::translate(), so the stored value is the one WPML must hold.
	 *
	 * @param int|object $gallery Gallery object or ID.
	 */
	public function register_gallery_strings( $gallery ) {
		if ( ! \function_exists( 'icl_register_string' ) ) {
			return;
		}

		if ( \is_object( $gallery ) ) {
			$gallery = $gallery->{$gallery->id_field ?? 'gid'} ?? 0;
		}

		$gallery_id = (int) $gallery;
		if ( $gallery_id <= 0 ) {
			return;
		}

		global $wpdb;
		$table = GalleryMapper::get_instance()->get_table_name();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name from the mapper; read must bypass the translating mapper and its cache.
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT gid, title, galdesc FROM {$table} WHERE gid = %d", $gallery_id ) );

		if ( $row ) {
			$this->register_gallery_row_strings( $row );
		}
	}

	/**
	 * Registers the strings of every existing gallery with WPML once, a batch per admin request.
	 *
	 * Until #1243 a gallery's strings were registered only when it was created, so galleries renamed
	 * since then, and galleries saved while String Translation was inactive, hold a stale or missing
	 * WPML original and never show their translation. Re-registering an unchanged string is a no-op
	 * in WPML; a changed one is updated and its translations are flagged as needing an update.
	 *
	 * admin_init also fires for admin-ajax.php (heartbeat included) and admin-post.php before they
	 * authenticate, so the work is left to a gallery manager's ordinary admin page load.
	 */
	public function backfill_gallery_strings() {
		if ( ! \function_exists( 'icl_register_string' )
			|| \wp_doing_ajax()
			|| ! Security::is_allowed( 'nextgen_edit_gallery' ) ) {
			return;
		}

		$cursor = \get_option( self::GALLERY_STRINGS_BACKFILL_OPTION, 0 );
		if ( 'done' === $cursor ) {
			return;
		}

		global $wpdb;
		$table = GalleryMapper::get_instance()->get_table_name();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name from the mapper; raw stored values are required, see register_gallery_strings().
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT gid, title, galdesc FROM {$table} WHERE gid > %d ORDER BY gid ASC LIMIT %d", (int) $cursor, self::GALLERY_STRINGS_BACKFILL_BATCH ) );

		// wpdb::get_results() returns an empty array on a failed query too, which would read as the
		// last batch and end the backfill for good; leave the cursor for the next admin request.
		if ( '' !== $wpdb->last_error || ! \is_array( $rows ) ) {
			return;
		}

		foreach ( $rows as $row ) {
			$this->register_gallery_row_strings( $row );
		}

		$next = \count( $rows ) < self::GALLERY_STRINGS_BACKFILL_BATCH ? 'done' : (int) \end( $rows )->gid;
		\update_option( self::GALLERY_STRINGS_BACKFILL_OPTION, $next );
	}

	/**
	 * Registers a gallery row's title and description with WPML.
	 *
	 * @param object $row Row holding gid, title and galdesc as stored.
	 */
	protected function register_gallery_row_strings( $row ) {
		if ( ! empty( $row->title ) ) {
			\icl_register_string( 'plugin_ngg', 'gallery_' . $row->gid . '_name', $row->title, true );
		}
		if ( ! empty( $row->galdesc ) ) {
			\icl_register_string( 'plugin_ngg', 'gallery_' . $row->gid . '_description', $row->galdesc, true );
		}
	}

	/**
	 * Registers image strings with WPML
	 *
	 * @param object $image Image object.
	 */
	public function register_image_strings( $image ) {
		if ( \function_exists( 'icl_register_string' ) ) {
			if ( isset( $image->description ) && ! empty( $image->description ) ) {
				\icl_register_string( 'plugin_ngg', 'pic_' . $image->{$image->id_field} . '_description', $image->description, true );
			}
			if ( isset( $image->alttext ) && ! empty( $image->alttext ) ) {
				\icl_register_string( 'plugin_ngg', 'pic_' . $image->{$image->id_field} . '_alttext', $image->alttext, true );
			}
		}
	}

	/**
	 * Registers album strings with WPML
	 *
	 * @param object $album Album object.
	 */
	public function register_album_strings( $album ) {
		if ( \function_exists( 'icl_register_string' ) ) {
			if ( isset( $album->name ) && ! empty( $album->name ) ) {
				\icl_register_string( 'plugin_ngg', 'album_' . $album->{$album->id_field} . '_name', $album->name, true );
			}
			if ( isset( $album->albumdesc ) && ! empty( $album->albumdesc ) ) {
				\icl_register_string( 'plugin_ngg', 'album_' . $album->{$album->id_field} . '_description', $album->albumdesc, true );
			}
		}
	}

	/**
	 * NextGEN stores some data in custom posts that MUST NOT be automatically translated by WPML
	 *
	 * @param array $icl_post_types Array of post types that can be translated.
	 * @return array $icl_post_types without any NextGEN custom posts
	 */
	public function wpml_translatable_documents( $icl_post_types = [] ) {
		$nextgen_post_types = [
			'ngg_album',
			'ngg_gallery',
			'ngg_pictures',
			'display_type',
			'gal_display_source',
			'lightbox_library',
			'photocrati-comments',
		];
		foreach ( $icl_post_types as $ndx => $post_type ) {
			if ( \in_array( $post_type->name, $nextgen_post_types, true ) ) {
				unset( $icl_post_types[ $ndx ] );
			}
		}

		return $icl_post_types;
	}

	/**
	 * Adjust gallery ID for WPML when duplicating posts.
	 *
	 * @param int    $master_post_id The master post ID.
	 * @param string $lang The language code.
	 * @param array  $post_array The post data.
	 * @param int    $id The new post ID.
	 */
	public function wpml_adjust_gallery_id( $master_post_id, $lang, $post_array, $id ) {
		if ( ! isset( $post_array['post_type'] ) || 'displayed_gallery' === $post_array['post_type'] ) {
			return;
		}

		$re = '|preview/id--(\d+)|mi';
		if ( \preg_match_all( $re, $post_array['post_content'], $gallery_ids ) ) {
			foreach ( $gallery_ids[1] as $index => $gallery_id ) {
				$translated_gallery_id = \apply_filters( 'wpml_object_id', (int) $gallery_id, 'displayed_gallery', true, $lang );
			}

			$search[ $index ]           = 'preview/id--' . $gallery_id;
			$replace[ $index ]          = 'preview/id--' . $translated_gallery_id;
			$post_array['post_content'] = \str_replace( $search, $replace, $post_array['post_content'] );

			$to_save = [
				'ID'           => $id,
				'post_content' => $post_array['post_content'],
			];

			\wp_update_post( $to_save );
		}
	}

	/**
	 * Set gallery language on save post for WPML.
	 *
	 * @param int $post_id The post ID.
	 */
	public function wpml_set_gallery_language_on_save_post( $post_id ) {
		if ( \wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Nonce verification is not necessary: we are inspecting the URL and ending execution if a URL paramater matches.
		//
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['icl_ajx_action'] ) && 'make_duplicates' === sanitize_text_field( wp_unslash( $_POST['icl_ajx_action'] ) ) ) {
			return;
		}

		$post = \get_post( $post_id );

		if ( 'displayed_gallery' === $post->post_type ) {
			return;
		}

		if ( \preg_match_all( '#<img.*http(s)?://(.*)?' . NGG_ATTACH_TO_POST_SLUG . '(=|/)preview(/|&|&amp;)id(=|--)(\\d+).*?>#mi', $post->post_content, $matches, PREG_SET_ORDER ) ) {
			$mapper = DisplayedGalleryMapper::get_instance();
			foreach ( $matches as $match ) {
				// Find the displayed gallery.
				$displayed_gallery_id = $match[6];
				\add_filter( 'wpml_suppress_filters', '__return_true', 10, 1 );
				$displayed_gallery = $mapper->find( $displayed_gallery_id, true );
				\add_filter( 'wpml_suppress_filters', '__return_false', 11, 1 );
				if ( $displayed_gallery ) {
					$displayed_gallery_type = \apply_filters( 'wpml_element_type', 'displayed_gallery' );

					// set language of this gallery.
					$displayed_gallery_lang = \apply_filters( 'wpml_post_language_details', null, $displayed_gallery->ID );
					$post_language          = \apply_filters( 'wpml_post_language_details', null, $post_id );

					if ( ! $displayed_gallery_lang || $displayed_gallery_lang['language_code'] != $post_language['language_code'] ) {
						if ( $post_language ) {
							$args = [
								'element_id'    => $displayed_gallery->ID,
								'element_type'  => $displayed_gallery_type,
								'language_code' => $post_language['language_code'],
							];
							\do_action( 'wpml_set_element_language_details', $args );
						}
					}

					// duplicate gallery to other languages.
					$is_translated = \apply_filters( 'wpml_element_has_translations', '', $displayed_gallery->ID, $displayed_gallery_type );
					if ( ! $is_translated ) {
						\do_action( 'wpml_admin_make_post_duplicates', $displayed_gallery->ID );
					}
				}
			}
		}
	}

	/**
	 * REST namespace that serves the admin app.
	 *
	 * Every route registered under it, in both NextGEN Gallery and NextGEN Pro, is gated on a
	 * NextGEN capability. The public front-end routes Pro registers live under `nggpro/v1`.
	 *
	 * @var string
	 */
	const ADMIN_APP_REST_NAMESPACE = 'imagely/v1';

	/**
	 * Nesting depth of explicit entity-authoring scopes.
	 *
	 * @var int
	 */
	private static $authoring_depth = 0;

	/**
	 * Whether stored entity text should be swapped for its translation while an entity is loaded.
	 *
	 * Translating gallery, album and image text is a display concern. Code that reads the stored,
	 * default-language text in order to edit it and save it back must see the stored text, or the
	 * translation is written over the original on the way out (GH issue #1257).
	 *
	 * @return bool
	 */
	public static function should_translate_entity_text() {
		return ! \is_admin() && ! self::is_entity_authoring_request();
	}

	/**
	 * Whether the current request edits stored entity text rather than displaying it.
	 *
	 * `is_admin()`, tested by the caller, already covers the admin screens and admin-ajax. The
	 * three authoring surfaces it does not cover are the admin app (REST), the XML-RPC publishing
	 * API, and the Lightroom client, which announces itself with suspend_entity_translation()
	 * because it is dispatched over the same front-end AJAX entry point as display requests.
	 *
	 * @return bool
	 */
	public static function is_entity_authoring_request() {
		if ( self::$authoring_depth > 0 ) {
			return true;
		}

		if ( \defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
			return true;
		}

		return self::is_admin_app_request();
	}

	/**
	 * Declares that the code about to run loads entities in order to edit and save them.
	 *
	 * Reentrant: each call must be paired with resume_entity_translation().
	 */
	public static function suspend_entity_translation() {
		++self::$authoring_depth;
	}

	/**
	 * Ends one entity-authoring scope opened by suspend_entity_translation().
	 */
	public static function resume_entity_translation() {
		if ( self::$authoring_depth > 0 ) {
			--self::$authoring_depth;
		}
	}

	/**
	 * Whether the current request is served by one of the admin app's own REST routes.
	 *
	 * WordPress records the dispatched route in the `rest_route` query var for every REST request,
	 * pretty permalinks and plain `?rest_route=` alike, and does so before it defines REST_REQUEST.
	 * It matches routes case-insensitively and does not normalize the dispatched path, so the
	 * comparison lowercases it first -- the same hardening NextGEN Pro applies to this namespace in
	 * `src/Commerce/Manager.php`.
	 *
	 * @return bool
	 */
	public static function is_admin_app_request() {
		if ( ! isset( $GLOBALS['wp']->query_vars['rest_route'] ) ) {
			return false;
		}

		$route = $GLOBALS['wp']->query_vars['rest_route'];

		if ( ! \is_string( $route ) || '' === $route ) {
			return false;
		}

		$route = \strtolower( \ltrim( \trim( $route ), '/' ) );

		return 0 === \strpos( $route, self::ADMIN_APP_REST_NAMESPACE . '/' );
	}

	/**
	 * Translate a string using various translation plugins.
	 *
	 * @param string      $in The string to translate.
	 * @param string|null $name Optional name for WPML string.
	 * @return string The translated string.
	 */
	public static function translate( $in, $name = null ) {
		if ( \function_exists( 'langswitch_filter_langs_with_message' ) ) {
			$in = \langswitch_filter_langs_with_message( $in );
		}

		if ( \function_exists( 'polyglot_filter' ) ) {
			$in = \polyglot_filter( $in );
		}

		if ( \function_exists( 'qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage' ) ) {
			$in = \qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage( $in );
		}

		if ( \is_string( $name )
		&& ! empty( $name )
		&& \function_exists( 'icl_translate' )
		&& \apply_filters( 'wpml_current_language', null ) !== \apply_filters( 'wpml_default_language', null ) ) {
			$in = \icl_translate( 'plugin_ngg', $name, $in, true );
		}

		$in = \apply_filters( 'localization', $in );

		return $in;
	}

	/**
	 * Multibyte-safe pathinfo() implementation.
	 *
	 * @param string   $path The file path.
	 * @param int|null $options Optional pathinfo options.
	 * @return array|string Path information.
	 */
	public static function mb_pathinfo( $path, $options = null ) {
		$ret      = [
			'dirname'   => '',
			'basename'  => '',
			'extension' => '',
			'filename'  => '',
		];
		$pathinfo = [];
		if ( \preg_match( '%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $path, $pathinfo ) ) {
			if ( \array_key_exists( 1, $pathinfo ) ) {
				$ret['dirname'] = $pathinfo[1];
			}
			if ( \array_key_exists( 2, $pathinfo ) ) {
				$ret['basename'] = $pathinfo[2];
			}
			if ( \array_key_exists( 5, $pathinfo ) ) {
				$ret['extension'] = $pathinfo[5];
			}
			if ( \array_key_exists( 3, $pathinfo ) ) {
				$ret['filename'] = $pathinfo[3];
			}
		}
		switch ( $options ) {
			case PATHINFO_DIRNAME:
			case 'dirname':
				return $ret['dirname'];
			case PATHINFO_BASENAME:
			case 'basename':
				return $ret['basename'];
			case PATHINFO_EXTENSION:
			case 'extension':
				return $ret['extension'];
			case PATHINFO_FILENAME:
			case 'filename':
				return $ret['filename'];
			default:
				return $ret;
		}
	}

	/**
	 * Multibyte-safe basename() implementation.
	 *
	 * @param string $path The file path.
	 * @return string The basename.
	 */
	public static function mb_basename( $path ) {
		$separator = ' qq ';
		$path      = \preg_replace( '/[^ ]/u', $separator . '$0' . $separator, $path );
		$base      = \basename( $path );
		return \str_replace( $separator, '', $base );
	}

	/**
	 * Get allowed HTML tags for wp_kses.
	 *
	 * @return array Allowed HTML tags and attributes.
	 */
	public static function get_kses_allowed_html() {
		global $allowedtags;

		$our_keys = [
			'a'      => [
				'href'  => [],
				'class' => [],
				'title' => [],
			],
			'br'     => [],
			'em'     => [],
			'i'      => [],
			'strong' => [],
			'b'      => [],
			'u'      => [],
			'p'      => [ 'class' => [] ],
			'div'    => [
				'class' => [],
				'id'    => [],
			],
			'span'   => [
				'class' => [],
				'id'    => [],
			],
		];

		return \array_merge_recursive( $allowedtags, $our_keys );
	}

	/**
	 * Sanitize text for alt/title/desc field.
	 *
	 * @param string $content content.
	 * @return string Sanitized content.
	 */
	public static function ngg_sanitize_text_alt_title_desc( $content ) {
		// Step 1: Decode HTML entities to get rid of HTML entity encoding (for example, &lt; becomes <).
		$content = self::ngg_decode_html_entities( $content );

		// Strip backslashes that may have been added by wp_kses or other functions.
		$content = stripslashes( $content );
		$content = str_replace( '`', '', $content );

		// Step 2: Remove dangerous patterns. Each must match real HTML/JS constructs only,
		// never plain words containing them as a substring (e.g. "description", "medieval").
		// Remove <script> tags (paired) and any stray/unclosed <script> opener.
		$content = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $content );
		$content = preg_replace( '/<\/?script\b[^>]*>?/i', '', $content );

		// Remove inline event handlers like onclick, onload, etc.
		$content = preg_replace( '/\bon\w+\s*=\s*["\'].*?["\']/i', '', $content );

		// Remove "javascript:"/"vbscript:" URL protocols (scheme + colon).
		$content = preg_replace( '/\b(?:javascript|vbscript)\s*:/i', '', $content );

		// Remove eval() calls and window.location / document.cookie / document.write access.
		$content = preg_replace( '/\b(?:eval\s*\(|window\.location|document\.cookie|document\.write)/i', '', $content );

		// Strip remaining unwanted HTML tags and attributes (you can customize this further).
		$allowed_tags = self::get_kses_allowed_html();
		$content      = wp_kses( $content, $allowed_tags, array( 'http', 'https', 'mailto' ) );

		// wp_kses() normalizes a bare "&" to "&amp;" and a stray ">" to "&gt;", which the encode below
		// would encode a second time, so "A & B" was stored as "A &amp;amp; B" (#1180). Undo that in
		// text only; tags keep kses's form, which is the valid HTML their attributes need. The encode
		// below must keep double-encoding: an entity rebuilt after the decode above (the backtick
		// strip turns "&l`t;" into "&lt;") would otherwise come back as a live "<" when rendered.
		$parts = preg_split( '/(<[^>]*>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
		foreach ( $parts as $index => $part ) {
			// Odd indexes hold the captured tags.
			if ( 0 === $index % 2 ) {
				$parts[ $index ] = strtr(
					$part,
					[
						'&amp;' => '&',
						'&gt;'  => '>',
					]
				);
			}
		}
		$content = implode( '', $parts );

		// Clean up the content by re-encoding HTML entities, this ensures that no malicious code is stored in db.
		$content = htmlspecialchars( $content, ENT_QUOTES, 'UTF-8' );

		return $content;
	}

	/**
	 * Function to decode sanitized HTML content before render.
	 *
	 * @param  mixed $content The content to decode.
	 * @return string The decoded content.
	 */
	public static function ngg_decode_sanitized_html_content( $content ) {
		$content = self::ngg_sanitize_text_alt_title_desc( $content );
		$content = htmlspecialchars_decode( $content, ENT_QUOTES );
		return $content;
	}

	/**
	 * Function to return plain text for alt or title attributes.
	 *
	 * @param  mixed $text The text to process.
	 * @return string The plain text.
	 */
	public static function ngg_plain_text_alt_title_attributes( $text ) {
		$text = esc_attr( strip_tags( html_entity_decode( (string) $text, ENT_QUOTES, 'UTF-8' ) ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
		return $text;
	}

	/**
	 * Function to decode HTML entities recursively.
	 *
	 * @param  mixed $content The content to decode.
	 * @return string The decoded content.
	 */
	public static function ngg_decode_html_entities( $content ) {
		$previous_content = '';

		// Keep decoding until no changes occur (i.e., fully decoded).
		do {
			$previous_content = $content;
			$content          = html_entity_decode( (string) $content, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
		} while ( $previous_content !== $content );

		return $content;
	}
}
