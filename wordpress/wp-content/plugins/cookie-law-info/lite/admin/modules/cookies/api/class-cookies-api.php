<?php
/**
 * Class Cookies_API file.
 *
 * @package Cookies
 */

namespace CookieYes\Lite\Admin\Modules\Cookies\Api;

use WP_REST_Server;
use CookieYes\Lite\Admin\Modules\Cookies\Api\API_Controller;
use CookieYes\Lite\Admin\Modules\Cookies\Includes\Cookie;
use CookieYes\Lite\Admin\Modules\Cookies\Includes\Cookie_Controller;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cookies API
 *
 * @class       Cookies_API
 * @version     3.0.0
 * @package     CookieYes
 * @extends     API_Controller
 */
class Cookies_API extends API_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'cky/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'cookies';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ), 10 );
	}
	/**
	 * Register the routes for cookies.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'cookie-law-info' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

	}

	/**
	 * Return the cookies for the list endpoint: one category's when `category`
	 * is given, otherwise all of them.
	 *
	 * @param array $args Request arguments.
	 * @return array
	 */
	public function get_item_objects( $args ) {
		$controller = Cookie_Controller::get_instance();
		if ( isset( $args['category'] ) && '' !== $args['category'] ) {
			return $controller->get_items_by_category( absint( $args['category'] ) );
		}
		return $controller->get_item_from_db();
	}

	/**
	 * Format a cookie for the response. With `lang`, the translated fields
	 * (description, duration) hold only that language; a language the site
	 * has not selected falls back to the default language.
	 *
	 * @param Cookie          $object  Cookie instance.
	 * @param WP_REST_Request $request Request params.
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $object, $request ) {
		$response = parent::prepare_item_for_response( $object, $request );
		$lang     = isset( $request['lang'] ) ? sanitize_text_field( $request['lang'] ) : '';
		if ( '' === $lang ) {
			return $response;
		}
		if ( ! in_array( $lang, cky_selected_languages(), true ) ) {
			$lang = cky_default_language();
		}
		$data = $response->get_data();
		foreach ( array( 'description', 'duration' ) as $field ) {
			if ( isset( $data[ $field ] ) && is_array( $data[ $field ] ) ) {
				$data[ $field ] = array_intersect_key( $data[ $field ], array( $lang => true ) );
			}
		}
		$response->set_data( $data );
		return $response;
	}

	/**
	 * Return item object
	 *
	 * @param object $item Cookie id.
	 * @return Cookie
	 */
	public function get_item_object( $item = false ) {
		return new Cookie( $item );
	}
	/**
	 * Get formatted item data.
	 *
	 * @since  3.0.0
	 * @param  Cookie $object Cookie instance.
	 * @return array
	 */
	protected function get_formatted_item_data( $object ) {
		return $object->get_prepared_data();
	}
	/**
	 * Get the Cookies's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'cookie',
			'type'       => 'object',
			'properties' => array(
				'id'            => array(
					'description' => __( 'Unique identifier for the resource.', 'cookie-law-info' ),
					'type'        => 'integer',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'date_created'  => array(
					'description' => __( 'The date the cookie was created, as GMT.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified' => array(
					'description' => __( 'The date the cookie was last modified, as GMT.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'name'          => array(
					'description' => __( 'Cookie name.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'category'      => array(
					'description' => __( 'Cookie category name.', 'cookie-law-info' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'          => array(
					'description' => __( 'Cookie unique name', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'description'   => array(
					'description' => __( 'Cookie description.', 'cookie-law-info' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
				),
				'duration'      => array(
					'description' => __( 'Cookie duration', 'cookie-law-info' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
				),
				'language'      => array(
					'description' => __( 'Cookie language.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'type'          => array(
					'description' => __( 'Cookie type.', 'cookie-law-info' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'domain'        => array(
					'description' => __( 'Cookie domain.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'discovered'    => array(
					'description' => __( 'If cookies added from the scanner or not.', 'cookie-law-info' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'url_pattern'   => array(
					'description' => __( 'URL patterns for blocking purposes', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
} // End the class.
