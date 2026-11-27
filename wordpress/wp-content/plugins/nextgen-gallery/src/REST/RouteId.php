<?php
/**
 * Resolve the object id of a REST route from its URL path.
 *
 * @package Imagely\NGG\REST
 */

namespace Imagely\NGG\REST;

use WP_REST_Request;

/**
 * The id a route like /images/(?P<id>\d+) addresses, taken from the URL path only.
 *
 * WP_REST_Request::get_param() merges every parameter source and lets a JSON body, a form body
 * or the query string win over the URL path. A permission callback that authorizes the path id
 * while the handler acts on get_param( 'id' ) can therefore be pointed at another object with
 * ?id=<other>. Permission callbacks and handlers of id-bearing routes both read the id here, so
 * the object that is authorized is always the object that is acted on.
 */
class RouteId {

	/**
	 * The id captured from the URL path, or 0 when the route has none.
	 *
	 * @param WP_REST_Request $request The REST request.
	 * @param string          $param   Name of the URL parameter.
	 * @return int
	 */
	public static function from_url( WP_REST_Request $request, $param = 'id' ) {
		$url_params = $request->get_url_params();

		return isset( $url_params[ $param ] ) && is_scalar( $url_params[ $param ] ) ? absint( $url_params[ $param ] ) : 0;
	}

	/**
	 * Whether the route matched for this request captures the parameter in its URL path.
	 *
	 * @param WP_REST_Request $request The REST request.
	 * @param string          $param   Name of the URL parameter.
	 * @return bool
	 */
	public static function in_url( WP_REST_Request $request, $param = 'id' ) {
		return array_key_exists( $param, $request->get_url_params() );
	}

	/**
	 * Whether the query string or the body carries a value for the parameter that differs from
	 * the URL path. Such a request addresses two objects at once and is refused outright.
	 *
	 * @param WP_REST_Request $request The REST request.
	 * @param string          $param   Name of the URL parameter.
	 * @return bool
	 */
	public static function is_overridden( WP_REST_Request $request, $param = 'id' ) {
		$url_id  = self::from_url( $request, $param );
		$sources = [
			$request->get_query_params(),
			$request->get_body_params(),
			$request->get_json_params(),
		];

		foreach ( $sources as $params ) {
			if ( ! is_array( $params ) || ! array_key_exists( $param, $params ) ) {
				continue;
			}

			$value = $params[ $param ];
			if ( ! is_scalar( $value ) || ! is_numeric( $value ) || (int) $value !== $url_id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * The URL path id when the request addresses exactly one object, otherwise 0.
	 *
	 * For permission callbacks: 0 means "deny".
	 *
	 * @param WP_REST_Request $request The REST request.
	 * @param string          $param   Name of the URL parameter.
	 * @return int
	 */
	public static function authorized_target( WP_REST_Request $request, $param = 'id' ) {
		if ( self::is_overridden( $request, $param ) ) {
			return 0;
		}

		return self::from_url( $request, $param );
	}
}
