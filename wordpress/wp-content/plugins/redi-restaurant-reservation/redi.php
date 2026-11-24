<?php

if ( ! defined( 'REDI_API_USER' ) ) {
	define( 'REDI_API_USER', 'User.svc/' );
}
if ( ! defined( 'REDI_API_USERGET' ) ) {
	define( 'REDI_API_USERGET', 'User.svc/get' );
}
if ( ! defined( 'REDI_API_PLACE' ) ) { 
	define( 'REDI_API_PLACE', 'Place.svc/' );
}
if ( ! defined( 'REDI_API_SERVICE' ) ) {
	define( 'REDI_API_SERVICE', 'Service.svc/' );
}
if ( ! defined( 'REDI_API_CATEGORY' ) ) {
	define( 'REDI_API_CATEGORY', 'Category.svc/' );
}
if ( ! defined( 'REDI_API_RESERVATION' ) ) {
	define ( 'REDI_API_RESERVATION', 'Reservation.svc/' );
}
if ( ! defined( 'REDI_API_EMAILCONTENT' ) ) {
	define( 'REDI_API_EMAILCONTENT', 'emailcontent.svc/' );
}
if ( ! defined( 'REDI_API_DATES' ) ) {
	define( 'REDI_API_DATES', 'Date.svc/' );
}
if ( ! defined('REDI_API_CUSTOMFIELDS')){
    define( 'REDI_API_CUSTOMFIELDS', 'CustomFields.svc/' );
}
if ( ! defined( 'REDI_API_REMINDER' ) ) {
	define ( 'REDI_API_REMINDER', 'Reminder.svc/' );
}
if ( ! defined( 'REDI_RESTAURANT_API' ) ) {
	define( 'REDI_RESTAURANT_API', 'https://api.reservationdiary.eu/service/' );
}
if ( ! defined( 'REDI_API_REQUEST_TIMEOUT' ) ) {
	define( 'REDI_API_REQUEST_TIMEOUT', 60 );
}
if ( ! defined( 'REDI_MAX_CUSTOM_FIELDS' ) ) {
	define( 'REDI_MAX_CUSTOM_FIELDS', 11 );
}
if ( ! defined( 'REDI_API_WAITLIST' ) ) {
    define( 'REDI_API_WAITLIST', 'WaitList.svc/' );
}
if ( ! defined( 'REDI_METHOD_POST' ) ) {
	define( 'REDI_METHOD_POST', 'POST' );
}
if ( ! defined( 'REDI_METHOD_GET' ) ) {
	define( 'REDI_METHOD_GET', 'GET' );
}
if ( ! defined( 'REDI_METHOD_PUT' ) ) {
	define( 'REDI_METHOD_PUT', 'PUT' );
}
if ( ! defined( 'REDI_METHOD_DELETE' ) ) {
	define( 'REDI_METHOD_DELETE', 'DELETE' );
}

if ( ! class_exists( 'ReDi' ) ) {
	class Redi {
		private $ApiKey;

        public function __construct( $ApiKey ) {
            $this->ApiKey = $ApiKey;
            add_action( 'admin_footer', array( $this, '_add_deactivation_feedback_popup' ) );
            self::register_http_timeout_filters();
        }

		/**
		 * Register HTTP timeout filters once; scoped to Reservation Diary API only.
		 */
		private static function register_http_timeout_filters() {
			if ( has_filter( 'http_request_timeout', array( __CLASS__, 'filter_http_request_timeout' ) ) ) {
				return;
			}
			add_filter( 'http_request_timeout', array( __CLASS__, 'filter_http_request_timeout' ), 10, 2 );
			add_filter( 'http_request_args', array( __CLASS__, 'filter_http_request_args' ), 100, 2 );
		}

		public static function filter_http_request_timeout( $timeout, $url = '' ) {
			if ( self::is_reservation_diary_api_url( $url ) ) {
				return REDI_API_REQUEST_TIMEOUT;
			}
			return $timeout;
		}

		public static function filter_http_request_args( $args, $url = '' ) {
			if ( self::is_reservation_diary_api_url( $url ) ) {
				$args['timeout'] = REDI_API_REQUEST_TIMEOUT;
			}
			return $args;
		}

		private static function is_reservation_diary_api_url( $url ) {
			if ( ! is_string( $url ) || $url === '' ) {
				return false;
			}
			static $api_host = null;
			if ( $api_host === null ) {
				$api_host = wp_parse_url( REDI_RESTAURANT_API, PHP_URL_HOST );
			}
			return $api_host && strpos( $url, $api_host ) !== false;
		}
		public function Redi( $ApiKey ) {
			$this->ApiKey = $ApiKey;
		}

		public function deleteCustomField( $lang, $placeID, $customFieldID ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CUSTOMFIELDS . $lang . '/' . $this->ApiKey . '/place/' . $placeID . '/customfield/' . $customFieldID, REDI_METHOD_DELETE );
		}

		public function updateCustomField( $lang, $placeID, $customFieldID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CUSTOMFIELDS .'/v2/'. $lang . '/' . $this->ApiKey . '/place/' . $placeID . '/customfield/' . $customFieldID, REDI_METHOD_PUT,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function saveCustomField( $lang, $placeID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CUSTOMFIELDS .'/v2/'. $lang . '/' . $this->ApiKey . '/place/' . $placeID, REDI_METHOD_POST,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function getCustomField( $lang, $placeID ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CUSTOMFIELDS . $lang . '/' . $this->ApiKey . '/place/' . $placeID, REDI_METHOD_GET );
		}

		public function getDateInformation( $lang, $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_DATES  . $lang . '/'.$this->ApiKey.'/'. $categoryID, REDI_METHOD_GET,  self::strParams( $params ) );
		}

		public function getReservationUrl($lang) {
			return 'https://wp.reservationdiary.eu/' . $lang . '/' . $this->ApiKey . '/Reservation/Index?ReturnUrl=' . get_admin_url();
		}

		public function getWaiterDashboardUrl($lang) {
			return 'https://upcoming.reservationdiary.eu/Entry/' . $this->ApiKey . '?ReturnUrl=' . get_admin_url();
		}

		/**
		 * Paid settings (WPProvider) entry URL.
		 *
		 * @param string      $lang   Language code.
		 * @param string      $entry  Where the click came from: menu|teaser|banner|bottom|notice|plugins_list|host_dashboard.
		 * @param string|null $teaser Optional teaser slug (e.g. reservations, billing).
		 * @param string|null $start  Optional action, e.g. demo. Sanitized, max 40 chars.
		 * @return string
		 */
		public function getBasicPackageSettingsUrl( $lang, $entry = 'menu', $teaser = null, $start = null ) {
			$url = 'https://wp.reservationdiary.eu/' . $lang . '/' . $this->ApiKey;
			$args = array(
				'ReturnUrl' => get_admin_url(),
				'entry'     => self::sanitize_tracking_param( $entry, 'menu' ),
			);
			$teaser = self::sanitize_tracking_param( $teaser );
			if ( $teaser !== '' ) {
				$args['teaser'] = $teaser;
			}
			$start = self::sanitize_tracking_param( $start );
			if ( $start !== '' ) {
				$args['start'] = $start;
			}
			return add_query_arg( $args, $url );
		}

		/**
		 * Sanitize short tracking keys (no PII): [a-z0-9-], max 40 chars.
		 *
		 * @param string|null $value   Raw value.
		 * @param string      $default Fallback when empty/invalid.
		 * @return string
		 */
		public static function sanitize_tracking_param( $value, $default = '' ) {
			if ( $value === null || $value === '' ) {
				return $default;
			}
			$value = sanitize_key( $value );
			$value = preg_replace( '/[^a-z0-9\-]/', '', $value );
			if ( strlen( $value ) > 40 ) {
				$value = substr( $value, 0, 40 );
			}
			return $value === '' ? $default : $value;
		}

		public function getEmailContent( $reservationID, $type, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_EMAILCONTENT . $this->ApiKey . '/' . $reservationID . '/ClientReservation' . $type,
			REDI_METHOD_GET, self::strParams( $params ) );
		}

		public function cancelReservationByClient( $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION .'/v1/'. $this->ApiKey . '/cancelByClient', REDI_METHOD_DELETE,
				self::strParams( $params ) );
		}

		public function cancelReservation( $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . $this->ApiKey . '/cancelByProvider', REDI_METHOD_DELETE,
				self::strParams( $params ) );
		}

		public function findReservation($lang, $reservationID) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . $lang . '/' . $this->ApiKey . '/getReservation/' . $reservationID, REDI_METHOD_GET);
	    }

		public function createReservation( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . $this->ApiKey . '/' . $categoryID, REDI_METHOD_POST,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function createReservation_v1( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . 'v1/' . $this->ApiKey . '/' . $categoryID, REDI_METHOD_POST,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function updateReservation($reservationID, $lang, $currentTime, $dontNotifyClient, $params)
		{
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . $this->ApiKey . '/update/' . $reservationID . 
				'?Lang=' . $lang . '&CurrentTime=' . $currentTime . '&DontNotifyClient=' . $dontNotifyClient, REDI_METHOD_PUT,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function addWaitList($placeID, $params, $currentTime, $lang){
            return $this->request( REDI_RESTAURANT_API . REDI_API_WAITLIST . $lang .'/v1/' . $this->ApiKey . '/place/' . $placeID . '?currentTime='. $currentTime, REDI_METHOD_POST, wp_json_encode( self::unescape_array( $params ) ) );
		}
		
		public function addReminder( $placeID, $lang, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_REMINDER . $lang . '/' . $this->ApiKey . '/place/' . $placeID, REDI_METHOD_POST,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		/**
		 * @param $categoryID
		 * @param $params array
		 * <pre>
		 * StartTime -
		 * EndTime
		 * Quantity
		 * Alternatives
		 * </pre>
		 *
		 * @return array
		 */
		public function query( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . $this->ApiKey . '/' . $categoryID . '/Person',
			REDI_METHOD_GET, self::strParams( $params ) );
		}

		public function createCategory( $placeID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CATEGORY . $this->ApiKey . '/' . $placeID, REDI_METHOD_POST,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function getServices( $categoryID ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_SERVICE . $this->ApiKey . '/' . $categoryID . '/Person', REDI_METHOD_GET );
		}

		public function deleteServices( $categoryID, $quantity ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_SERVICE . $this->ApiKey . '/' . $categoryID . '/person/delete?quantity=' . $quantity,
			REDI_METHOD_DELETE );
		}

		public function setServiceTime( $categoryID, $timeSet ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CATEGORY . $this->ApiKey . '/' . $categoryID . '/time',
			REDI_METHOD_PUT,
				wp_json_encode( self::unescape_array( array( 'timeSet' => $timeSet ) ) ) );
		}

		public function getServiceTime( $categoryID ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CATEGORY . $this->ApiKey . '/' . $categoryID . '/time', REDI_METHOD_GET );
		}

		public function createService( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_SERVICE . $this->ApiKey . '/' . $categoryID, REDI_METHOD_POST,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function createUser( $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_USER, REDI_METHOD_POST, wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function setPlace( $placeID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_PLACE . $this->ApiKey . '/' . $placeID, REDI_METHOD_PUT,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function createPlace( $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_PLACE . $this->ApiKey, REDI_METHOD_POST,
				wp_json_encode( self::unescape_array( $params ) ) );
		}

		public function shiftsStartTime( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_CATEGORY . $this->ApiKey . '/' . $categoryID . '/shiftsStartTime',
			REDI_METHOD_GET, self::strParams( $params ) );
		}

		public function availabilityByDay( $categoryID, $params ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . $this->ApiKey . '/' . $categoryID . '/availabilityByDay/Person',
			REDI_METHOD_GET, self::strParams( $params ) );
		}

		public function getCustomDurationAvailability( $categoryID, $params) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_RESERVATION . $this->ApiKey . '/' . $categoryID . '/guestsByStayDuration',
			REDI_METHOD_GET, self::strParams( $params ) );
		}

		public function getPlace( $placeID ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_PLACE . $this->ApiKey . '/' . $placeID, REDI_METHOD_GET );
		}

		public function getPlaceCategories( $placeID ) {
			return $this->request( REDI_RESTAURANT_API . REDI_API_PLACE . $this->ApiKey . '/' . $placeID . '/categories', REDI_METHOD_GET );
		}

		public function getPlaces() {
			return $this->request( REDI_RESTAURANT_API . REDI_API_PLACE . $this->ApiKey, REDI_METHOD_GET );
		}

		public function setApiKey( $ApiKey ) {
			$this->ApiKey = $ApiKey;
		}

		public static function strParams( $params ) {
			$url_param = '';
			$first     = 0;

			if ( is_array( $params ) ) {
				foreach ( $params as $param_name => $param_value ) {
					$url_param .= ( ( $first ++ == 0 ) ? '?' : '&' ) . $param_name . '=' . $param_value;
				}
			}

			return $url_param;
		}

		private static function unescape_array( $array ) {
			$unescaped_array = array();
			foreach ( $array as $key => $val ) {
				if ( is_array( $val ) ) {
					$unescaped_array[ $key ] = self::unescape_array( $val );
				} else {
						if (!empty($val))
						{
							$unescaped_array[ $key ] = stripslashes( $val );
						}
				}
			}

			return $unescaped_array;
		}

		private function request( $url, $method = REDI_METHOD_GET, $params_string = null ) {
			$request = new WP_Http;
			$curTime = microtime( true );

			$plugin_version = defined( 'REDI_PLUGIN_VERSION' ) ? REDI_PLUGIN_VERSION : 'unknown';
			if ( defined( 'REDI_PLUGIN_REVISION' ) && REDI_PLUGIN_REVISION !== '' ) {
				$plugin_version .= '+' . REDI_PLUGIN_REVISION;
			}

			$req     = array(
				'method'  => $method,
				'body'    => (($method === REDI_METHOD_GET || $method === REDI_METHOD_DELETE ) ? null : $params_string),
				'headers' => array(
					'Content-Type'            => 'application/json; charset=utf-8',
					'Content-Length'          => ($method === REDI_METHOD_GET || $method === REDI_METHOD_DELETE ) ? 0 : strlen( $params_string ),
					'X-ReDi-Plugin-Version'   => $plugin_version,
					'X-ReDi-Install-Id'       => class_exists( 'ReDiInstall' ) ? ReDiInstall::id() : '',
				)
			);
			$output  = $request->request(
				$url . ( ( $method === REDI_METHOD_GET || $method === REDI_METHOD_DELETE ) ? $params_string : '' ), $req );

			$link = '<a href="mailto:info@reservationdiary.eu;'.get_bloginfo('admin_email').'?subject=' . __("Reservation form is not working", 'redi-restaurant-reservation') . '&body='.get_bloginfo().'">' . __("contact us directly", 'redi-restaurant-reservation') .'</a>';
			
			// Translators: %s is a placeholder for a dynamic link.
			$message = sprintf(__( 'Online reservation service is not available at this time. Try again later or %s', 'redi-restaurant-reservation' ), $link);

			if ( is_wp_error( $output ) ) {
				return array(
					'request_time' => round( microtime( true ) - $curTime, 3 ) * 1000,
					'Error'        => $message,
					'Wp-Error'     => $output->errors
				);
			}

			if ( $output['response']['code'] != 200 && $output['response']['code'] != 400 ) {
				return array(
					'response_code' => $output['response']['code'],
					'Error'         => $message
				);
			}
			$output = $output['body'];

			// convert response
			$output = (array) json_decode( $output );

			return $output;
		}

		public function _add_deactivation_feedback_popup(){
			$screen = get_current_screen();
			if ($screen->base === 'plugins') {
				if ( is_file( plugin_dir_path( __FILE__ ) . 'templates/popup.php' ) ) {
					include_once plugin_dir_path( __FILE__ ) . 'templates/popup.php';
	            }
	        }
		}
	}
}
