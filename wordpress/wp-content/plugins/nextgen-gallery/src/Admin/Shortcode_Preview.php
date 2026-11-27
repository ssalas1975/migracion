<?php
/**
 * NextGen Gallery Shortcode Preview functionality.
 *
 * @package Imagely\NGG\Admin
 * @since   1.0.0
 */

namespace Imagely\NGG\Admin;

use Imagely\NGG\DataMappers\Gallery as GalleryMapper;
use Imagely\NGG\REST\DataMappers\AddonsREST;

/**
 * Class Shortcode_Preview
 *
 * Handles the preview functionality for NextGen Gallery shortcodes.
 *
 * @package Imagely\NGG\Admin
 */
class Shortcode_Preview {
	/**
	 * Query args the preview endpoint reads itself, never passed to the shortcode as attributes.
	 *
	 * `_cache` is the token the admin app adds to every preview load so URL-keyed page caches and CDNs miss
	 * (GitHub #1164).
	 *
	 * @var string[]
	 */
	const RESERVED_QUERY_ARGS = [ 'ngg-preview', 'nonce_preview', '_cache' ];

	/**
	 * Register hooks for the shortcode preview functionality.
	 *
	 * @return void
	 */
	public static function hooks() {
		add_action( 'init', [ __CLASS__, 'register_preview_page' ] );
		add_action( 'template_redirect', [ __CLASS__, 'handle_preview_page' ] );
	}

	/**
	 * Register the preview page endpoint.
	 *
	 * @return void
	 */
	public static function register_preview_page() {
		add_rewrite_endpoint( 'ngg-preview', EP_ROOT );
	}

	/**
	 * Handle the preview page display and processing.
	 *
	 * @return void
	 */
	public static function handle_preview_page() {
		global $wp_query;

		if ( ! isset( $wp_query->query_vars['ngg-preview'] ) ) {
			return;
		}

		// Check if user is logged in and has required capability.
		// Allow users who can manage galleries or upload images to preview.
		if ( ! current_user_can( 'NextGEN Manage gallery' ) && ! current_user_can( 'NextGEN Upload images' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown -- NextGEN capabilities, registered in Imagely\NGG\Util\Installer::set_role_caps().
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'nggallery' ) );
		}

		// Verify nonce for security.
		if ( ! isset( $_GET['nonce_preview'] ) || ! wp_verify_nonce( sanitize_key( $_GET['nonce_preview'] ), 'ngg_preview_shortcode' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'nggallery' ) );
		}

		// The preview must always reflect the gallery as it is now (GitHub #1164).
		self::prevent_caching();

		// Check if gallery has an external source addon that is not enabled.
		$gallery_ids = isset( $_GET['gallery_ids'] ) ? sanitize_text_field( wp_unslash( $_GET['gallery_ids'] ) ) : '';
		if ( $gallery_ids ) {
			$gallery_mapper = GalleryMapper::get_instance();
			$gallery        = $gallery_mapper->find( intval( $gallery_ids ) );
			if ( $gallery && ! AddonsREST::can_render_gallery( $gallery ) ) {
				self::render_preview_page( '', null );
				exit;
			}
		}

		// Hide admin bar for this page.
		add_filter( 'show_admin_bar', '__return_false' );

		// Get shortcode parameters from URL and build the shortcode.
		$params = self::get_shortcode_params( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce verified above.

		// Build and process the shortcode.
		$shortcode = '[ngg ' . implode( ' ', $params ) . ']';
		$sorted    = $params;
		sort( $sorted );
		$sorted_shortcode = implode( '<br>', $sorted );

		// Managers preview their own private galleries here.
		\Imagely\NGG\PrivateGalleries\Visibility::$preview = true;
		$output = do_shortcode( $shortcode );

		// Store original GET parameters to append to URLs.
		$preview_params = [];
		foreach ( $_GET as $key => $value ) {
			$preview_params[ $key ] = sanitize_text_field( $value );
		}

		// Get the site URL for domain matching.
		$site_url = wp_parse_url( get_site_url(), PHP_URL_HOST );

		// Modify URLs in the output to include preview parameters, but skip URLs inside HTML attributes.
		$output = preg_replace_callback(
			'#\bhref=[\'"](https?://[^\'"\s<>]+)[\'"]\s*[^>]*>#i',
			function ( $matches ) use ( $preview_params, $site_url ) {
				$full_match = $matches[0];
				$url        = $matches[1];
				$url_parts  = wp_parse_url( $url );

				// Only process URLs from the same domain.
				if ( ! isset( $url_parts['host'] ) || $url_parts['host'] !== $site_url ) {
					return $full_match;
				}

				// Skip image URLs (common image extensions) to prevent corruption.
				if ( isset( $url_parts['path'] ) ) {
					$path_lower       = strtolower( $url_parts['path'] );
					$image_extensions = [ '.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.bmp', '.tiff' ];
					foreach ( $image_extensions as $ext ) {
						if ( substr( $path_lower, -strlen( $ext ) ) === $ext ) {
							return $full_match; // Don't modify image URLs
						}
					}
				}

				$query = [];
				if ( isset( $url_parts['query'] ) ) {
					parse_str( $url_parts['query'], $query );
				}

				$query              = array_merge( $query, $preview_params );
				$url_parts['query'] = http_build_query( $query );

				$new_url = self::build_url( $url_parts );

				// Replace the URL in the original match
				return str_replace( $matches[1], $new_url, $full_match );
			},
			$output
		);

		// Output the preview page with minimal theme interference.
		$debug_data = null;
		if ( isset( $_GET['debug'] ) || App::is_debug() ) {
			$debug_data = [
				'current_url'      => $_SERVER['REQUEST_URI'], //phpcs:ignore
				'raw_shortcode'    => $shortcode,
				'sorted_shortcode' => $sorted_shortcode,
			];
		}
		self::render_preview_page( $output, $debug_data );
		exit;
	}

	/**
	 * Keeps page caches and HTTP caches from storing the preview.
	 *
	 * The response is a per-user render of the gallery's current state, but its URL only changes with the display
	 * settings, so a cached copy would hide new images on every reload (GitHub #1164). DONOTCACHEPAGE is the flag
	 * page cache plugins check; the headers cover proxies and CDNs.
	 *
	 * DONOTCACHEOBJECT is deliberately not defined: object caches that honour it (W3 Total Cache) would then keep
	 * this request's transients in memory only, and the preview's lightbox reads its transient back in a later
	 * request.
	 *
	 * @return void
	 */
	public static function prevent_caching() {
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}

		if ( headers_sent() ) {
			return;
		}

		header_remove( 'Last-Modified' );
		foreach ( self::get_no_cache_headers() as $name => $value ) {
			if ( false !== $value ) {
				header( "{$name}: {$value}" );
			}
		}
	}

	/**
	 * Headers sent with the preview: WordPress' nocache headers, with a Cache-Control that no
	 * `nocache_headers` filter can weaken, since storing the preview anywhere brings the stale-gallery bug back.
	 *
	 * @return array<string, string|false> Header name => value; false means "do not send".
	 */
	public static function get_no_cache_headers() {
		$headers                  = wp_get_nocache_headers();
		$headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, max-age=0, private';

		return $headers;
	}

	/**
	 * Builds the [ngg] shortcode attributes from the preview's query args, skipping the args the endpoint reads
	 * itself (RESERVED_QUERY_ARGS).
	 *
	 * @param array $query The request's query args ($_GET).
	 * @return string[] Attributes in `key="value"` form, in query order.
	 */
	public static function get_shortcode_params( array $query ) {
		$params = [];

		foreach ( $query as $key => $value ) {
			if ( in_array( $key, self::RESERVED_QUERY_ARGS, true ) ) {
				continue;
			}
			$params[] = sanitize_text_field( $key ) . '="' . sanitize_text_field( $value ) . '"';
		}

		return $params;
	}

	/**
	 * Helper function to rebuild URL from parts
	 *
	 * @param array $parts Parts of URL from parse_url.
	 * @return string
	 */
	private static function build_url( $parts ) {
		$url = '';

		if ( isset( $parts['scheme'] ) ) {
			$url .= $parts['scheme'] . '://';
		}

		if ( isset( $parts['user'] ) ) {
			$url .= $parts['user'];
			if ( isset( $parts['pass'] ) ) {
				$url .= ':' . $parts['pass'];
			}
			$url .= '@';
		}

		if ( isset( $parts['host'] ) ) {
			$url .= $parts['host'];
		}

		if ( isset( $parts['port'] ) ) {
			$url .= ':' . $parts['port'];
		}

		if ( isset( $parts['path'] ) ) {
			$url .= $parts['path'];
		}

		if ( isset( $parts['query'] ) ) {
			$url .= '?' . $parts['query'];
		}

		if ( isset( $parts['fragment'] ) ) {
			$url .= '#' . $parts['fragment'];
		}

		return $url;
	}

	/**
	 * Render the preview page with the processed shortcode.
	 *
	 * @param string     $content     The processed shortcode content.
	 * @param array|null $debug_data The debug data array containing raw and sorted shortcode, or null if not debugging.
	 * @return void
	 */
	private static function render_preview_page( $content, $debug_data ) {
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title><?php esc_html_e( 'NextGen Gallery Preview', 'nggallery' ); ?></title>
			<?php wp_head(); ?>
			<style>
				body {
					background: #fff;
					margin: 0;
					padding: 20px;
					min-height: 90vh;
				}
				/*
				 * width: 100% keeps the container full width when a theme makes <body> a flex container
				 * (Salient and others). As a flex item with auto margins it would shrink to its content, and
				 * a Mosaic has no intrinsic width, so the gallery collapsed to 0px.
				 */
				.ngg-preview-container {
					width: 100%;
					max-width: 1200px;
					margin: 0 auto;
				}
				.ngg-preview-content {
					margin: 20px 0;
				}
				.ngg-preview-shortcode {
					margin-top: 40px;
					padding: 20px;
					background: #f5f5f5;
					border: 1px solid #ddd;
					border-radius: 4px;
					font-family: monospace;
					word-break: break-all;
				}
				.ngg-preview-shortcode-label {
					color: #666;
					font-size: 12px;
					margin-bottom: 10px;
				}
			</style>
		</head>
		<body class="ngg-preview">
			<div class="ngg-preview-container">
				<div class="ngg-preview-content">
				<?php echo $content; //phpcs:ignore ?>
				</div>
				<?php if ( $debug_data ) : ?>
				<div class="ngg-preview-shortcode">
					<div class="ngg-preview-shortcode-label"><?php esc_html_e( 'Rendered Shortcode:', 'nggallery' ); ?></div>
					<?php echo esc_html( $debug_data['raw_shortcode'] ); ?>
					<br>
					<br>
					<?php echo wp_kses( $debug_data['sorted_shortcode'], [ 'br' => [] ] ); // Fixed: was raw echo of user-derived shortcode params joined by <br>; wp_kses entity-encodes quotes/amps while preserving intended <br> separators. ?>
				</div>
				<?php endif; ?>
			</div>
			<?php wp_footer(); ?>
		</body>
		</html>
		<?php
	}
}
