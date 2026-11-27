<?php
/**
 * Class Bfcm_Banner file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Bfcm_Banner;

use CookieYes\Lite\Includes\Modules;
use CookieYes\Lite\Admin\Modules\Settings\Includes\Controller as SettingsController;
use CookieYes\Lite\Admin\Modules\Settings\Includes\Settings;
use CookieYes\Lite\Includes\Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Black Friday 2026 dashboard banner: REST status and dismissal (until the campaign ends).
 *
 * The campaign dates below (START_DATE, SHOWN_END_DATE, END_DATE) are the single source of the
 * offer's timing, for both banners, the Plans page prices and the codes the Plans page sends.
 * The banners count down to SHOWN_END_DATE (1 Dec); once it
 * passes the offer carries on, counting down to END_DATE (15 Dec), and everything stops on its own
 * at that second, with no plugin release.
 * Standalone sites get the new-user offer compiled in (NEW_USER_OFFER), with no backend call.
 * Connected sites ask the backend only whether the site has an offer and for which plans (website
 * details `bfcmOffer`); its dates are not used, and a cached answer never outlives END_DATE. Any
 * missing, malformed or unreachable answer means "not live" (fail closed).
 *
 * @class   Bfcm_Banner
 * @package CookieYes
 */
class Bfcm_Banner extends Modules {
	/*
	 * Black Friday 2026 campaign dates (CAP-336), in UTC. Named after the backend's settings
	 * (config cookieyes.bfcm_2026: start_date, end_date, shown_end_date). Fixed dates, never derived
	 * from the current year, so the banner can never reappear in a later year. Read them through
	 * campaign_start(), campaign_end() and advertised_end().
	 */

	/**
	 * Start of the campaign. Nothing promotional shows before it, whenever this plugin version ships.
	 *
	 * @var string ISO 8601, UTC.
	 */
	const START_DATE = '2026-11-12T00:00:00Z';

	/**
	 * End of the offer, the single source of the end: the banners and the Plans page discounts stop here.
	 *
	 * @var string ISO 8601, UTC.
	 */
	const END_DATE = '2026-12-15T23:59:59Z';

	/**
	 * The advertised deadline. The countdown runs to it first; the offer does not stop there but is
	 * extended to END_DATE, and the countdown then runs to that.
	 *
	 * @var string ISO 8601, UTC.
	 */
	const SHOWN_END_DATE = '2026-12-01T23:59:59Z';

	/**
	 * New-user (standalone site) offer: percent off per tier, monthly and yearly alike. Matches the
	 * WordPress new-user codes the Plans page sends (BFB20WP, BFP40WP, BFU50WP).
	 *
	 * @var array<string,int>
	 */
	const NEW_USER_OFFER = array(
		'basic'    => 20,
		'pro'      => 40,
		'ultimate' => 50,
	);

	/**
	 * Dismissal key. Carries the campaign year so a later campaign is not pre-dismissed.
	 *
	 * It must equal the last segment of the REST route: the generic
	 * `settings/notices/<notice>` POST route (Settings API) also answers this path and
	 * dismisses whatever key the URL names.
	 *
	 * @var string
	 */
	const NOTICE_KEY = 'bfcm_2026_new_user';

	/**
	 * Dismissal key of the existing-user (connected site) banner; same rules as NOTICE_KEY.
	 *
	 * @var string
	 */
	const EXISTING_USER_NOTICE_KEY = 'bfcm_2026_existing_user';

	/**
	 * Tiers the plugin can show an offer for.
	 *
	 * @var string[]
	 */
	const TIERS = array( 'basic', 'pro', 'ultimate' );

	/**
	 * How long a backend answer is kept (an offer never past its advertised end; "no offer" as long), and
	 * how long a failure is remembered so an unreachable backend does not hold every request for the timeout.
	 */
	const CACHE_TTL   = HOUR_IN_SECONDS;
	const FAILURE_TTL = 5 * MINUTE_IN_SECONDS;

	/**
	 * Backend request timeout, in seconds.
	 *
	 * @var int
	 */
	const HTTP_TIMEOUT = 8;

	/**
	 * Transient value remembered after a failed backend call.
	 *
	 * @var string
	 */
	const FAILED = 'failed';

	/**
	 * Transient value remembered when the backend answered and offers this site nothing.
	 *
	 * @var string
	 */
	const NO_OFFER = 'none';

	/**
	 * REST namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'cky/v1';

	/**
	 * Constructor — passes module ID to parent.
	 */
	public function __construct() {
		parent::__construct( 'bfcm_banner' );
	}

	/**
	 * Initialize the module: register REST routes.
	 *
	 * Priority 9: the settings API registers `/settings/notices/(?P<notice>…)` at 10, which matches these URLs too,
	 * and WordPress serves a request from the first matching route. Registering first makes POST reach
	 * dismiss_banner().
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ), 9 );
	}

	/**
	 * Register GET (status) and POST (dismiss) routes, one pair per banner:
	 * `/settings/notices/bfcm_2026_new_user` and `/settings/notices/bfcm_2026_existing_user`.
	 *
	 * @return void
	 */
	public function register_routes() {
		foreach ( array( self::NOTICE_KEY, self::EXISTING_USER_NOTICE_KEY ) as $key ) {
			register_rest_route(
				$this->namespace,
				'/settings/notices/' . $key,
				array(
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'get_banner_status' ),
						'permission_callback' => array( $this, 'permission_check' ),
					),
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'dismiss_banner' ),
						'permission_callback' => array( $this, 'permission_check' ),
					),
				)
			);
		}
	}

	/**
	 * Which banner a request is for: the last segment of its route.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return string One of NOTICE_KEY or EXISTING_USER_NOTICE_KEY.
	 */
	private function notice_key( $request ) {
		$key = basename( (string) $request->get_route() );
		return self::EXISTING_USER_NOTICE_KEY === $key ? self::EXISTING_USER_NOTICE_KEY : self::NOTICE_KEY;
	}

	/**
	 * A test date from wp-config.php, or null. Honoured only on a site whose environment type is not
	 * `production` (wp_get_environment_type()), so a live site always runs on the built-in dates.
	 * Accepts a Unix timestamp or an ISO 8601 date-time, e.g. '2026-11-23T11:05:00Z'.
	 *
	 * @param string $name Constant name.
	 * @return int|null
	 */
	private static function test_date( $name ) {
		if ( ! defined( $name ) || 'production' === wp_get_environment_type() ) {
			return null;
		}
		$value = constant( $name );
		if ( is_int( $value ) || ( is_string( $value ) && ctype_digit( $value ) ) ) {
			return (int) $value;
		}
		if ( is_string( $value ) && preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $value ) ) {
			$time = strtotime( $value );
			return false === $time ? null : $time;
		}
		return null;
	}

	/**
	 * Unix timestamp of one of the campaign date constants (START_DATE, END_DATE, SHOWN_END_DATE).
	 *
	 * @param string $date ISO 8601 date-time in UTC.
	 * @return int
	 */
	private static function timestamp( $date ) {
		return (int) strtotime( $date );
	}

	/**
	 * Start of the campaign: START_DATE.
	 *
	 * @return int Unix timestamp.
	 */
	public static function campaign_start() {
		return self::timestamp( self::START_DATE );
	}

	/**
	 * The advertised deadline: SHOWN_END_DATE, or `CKY_BFCM_TEST_ADVERTISED_END` on a non-production site.
	 *
	 * @return int Unix timestamp.
	 */
	public static function advertised_end() {
		$test = self::test_date( 'CKY_BFCM_TEST_ADVERTISED_END' );
		return null === $test ? self::timestamp( self::SHOWN_END_DATE ) : $test;
	}

	/**
	 * The end of the offer: END_DATE, or `CKY_BFCM_TEST_END` on a non-production site.
	 *
	 * @return int Unix timestamp.
	 */
	public static function campaign_end() {
		$test = self::test_date( 'CKY_BFCM_TEST_END' );
		return null === $test ? self::timestamp( self::END_DATE ) : $test;
	}

	/**
	 * Whether the campaign is running at the given time (between start and end).
	 *
	 * @param int $now Unix timestamp.
	 * @return bool
	 */
	public static function is_campaign_live( $now ) {
		return $now >= self::campaign_start() && $now <= self::campaign_end();
	}

	/**
	 * Transient key of a connected site's backend answer. It carries the website id, so switching the
	 * connected site (or disconnecting) never shows another site's offer, and the plan the plugin last
	 * read for it (Controller::get_app_info(), refreshed on admin loads), so an upgrade or downgrade asks
	 * the backend again instead of keeping offers for the old plan.
	 *
	 * @param int    $website_id Connected website id.
	 * @param string $plan_slug  Plan slug the plugin last read for the site, or '' when unknown.
	 * @return string
	 */
	public static function cache_key( $website_id, $plan_slug = '' ) {
		return 'cky_bfcm_site_' . md5( $website_id . '|' . $plan_slug );
	}

	/**
	 * Keep a backend answer: an offer never past the end it advertises (so the next request after that end
	 * asks the backend again), "no offer" for CACHE_TTL, a failure briefly.
	 *
	 * @param string            $cache_key Transient key.
	 * @param array|string|null $offer     Normalised offer, NO_OFFER, or null for a failure.
	 * @return void
	 */
	private function remember( $cache_key, $offer ) {
		if ( null === $offer ) {
			set_transient( $cache_key, self::FAILED, self::FAILURE_TTL );
			return;
		}
		if ( self::NO_OFFER === $offer ) {
			set_transient( $cache_key, self::NO_OFFER, self::CACHE_TTL );
			return;
		}
		set_transient( $cache_key, $offer, max( MINUTE_IN_SECONDS, min( self::CACHE_TTL, $offer['final_ends_at'] - time() ) ) );
	}

	/**
	 * Rank of a plan slug's tier (`pro-monthly` => pro => 2; free => 0), or null for any other plan
	 * (enterprise, custom, agency…), which never gets an upgrade offer.
	 *
	 * @param mixed $slug Plan slug.
	 * @return int|null
	 */
	private static function tier_rank( $slug ) {
		$tier = is_string( $slug ) ? strtok( strtolower( $slug ), '-' ) : '';
		if ( 'free' === $tier || 'plan_f' === $tier ) {
			return 0;
		}
		$rank = array_search( $tier, self::TIERS, true );
		return false === $rank ? null : $rank + 1;
	}

	/**
	 * Highest valid percent per tier, from the backend's per-plan map (`basic-monthly` => `percentOff`),
	 * keeping only tiers above the site's current (or trial) plan: an upgrade offer never discounts the
	 * same plan or a lower one.
	 *
	 * @param mixed $plans      Backend `plans` map: plan slug => array with `percentOff`.
	 * @param int   $above_rank Rank of the site's current plan (tier_rank()).
	 * @return array<string,int|float> Tier => percent.
	 */
	private static function clean_plans( $plans, $above_rank ) {
		$clean = array();
		if ( ! is_array( $plans ) ) {
			return $clean;
		}
		foreach ( $plans as $slug => $plan ) {
			$rank = self::tier_rank( $slug );
			$pct  = is_array( $plan ) && isset( $plan['percentOff'] ) ? $plan['percentOff'] : null;
			if ( null === $rank || $rank <= $above_rank || ! is_numeric( $pct ) || $pct <= 0 || $pct >= 100 ) {
				continue;
			}
			$pct  = 0 + $pct;
			$pct  = ( floor( $pct ) == $pct ) ? (int) $pct : (float) $pct; // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual
			$tier = self::TIERS[ $rank - 1 ];
			if ( ! isset( $clean[ $tier ] ) || $pct > $clean[ $tier ] ) {
				$clean[ $tier ] = $pct;
			}
		}
		return $clean;
	}

	/**
	 * Decode a JSON response body, or null unless the response is a 200.
	 *
	 * @param array|\WP_Error $response HTTP response.
	 * @return array|null
	 */
	private static function json_body( $response ) {
		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return null;
		}
		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		return is_array( $body ) ? $body : null;
	}

	/**
	 * Connected sites: the site's `bfcmOffer` from `GET api/v2/websites/{id}` (null = nothing offered), with
	 * the site's own token, limited to plans above the site's current plan (`websiteplan.slug`; a trial
	 * site's is its trial plan). `websites/{id}/bfcm-banner` is not asked: the backend refuses it for a
	 * connection token (403 auth.website_token_restricted).
	 *
	 * @param int $website_id Connected website id.
	 * @return array|string|null `ends_at`, `final_ends_at` (both Unix timestamps) and `plans` (tier => percent);
	 *                           NO_OFFER when the backend answered with nothing above the current plan (or a
	 *                           plan with no upgrade path); null when it could not be reached or read.
	 */
	private function fetch_site_offer( $website_id ) {
		$controller = SettingsController::get_instance();
		$previous   = $controller->get_timeout();
		$controller->set_timeout( self::HTTP_TIMEOUT );
		try {
			$details = self::json_body( $controller->get( 'websites/' . $website_id ) );
		} finally {
			$controller->set_timeout( $previous );
		}
		if ( null === $details ) {
			return null;
		}
		$offer   = isset( $details['bfcmOffer'] ) ? $details['bfcmOffer'] : null;
		$current = isset( $details['websiteplan']['slug'] ) ? self::tier_rank( $details['websiteplan']['slug'] ) : null;
		if ( ! is_array( $offer ) || null === $current ) {
			return self::NO_OFFER;
		}
		$plans = self::clean_plans( $offer['plans'] ?? null, $current );
		if ( array() === $plans ) {
			return self::NO_OFFER;
		}
		// The dates are the plugin's own (SHOWN_END_DATE, then END_DATE), not the backend's `endsAt`.
		return array(
			'ends_at'       => self::advertised_end(),
			'final_ends_at' => self::campaign_end(),
			'plans'         => $plans,
		);
	}

	/**
	 * The backend's offer for a banner, cached; null when there is none or it cannot be had (fail closed).
	 *
	 * @param string $key Notice key.
	 * @return array|null
	 */
	private function get_offer( $key ) {
		$settings  = new Settings();
		$connected = $settings->is_connected();
		if ( self::EXISTING_USER_NOTICE_KEY === $key ) {
			// Existing-user offer: connected sites only, for the connected (selected) site.
			if ( ! $connected || ! $settings->get_website_id() ) {
				return null;
			}
			$website_id = absint( $settings->get_website_id() );
		} else {
			// New-user offer: standalone sites only, compiled in (no backend call, nothing to cache).
			if ( $connected ) {
				return null;
			}
			return array(
				'ends_at'       => self::advertised_end(),
				'final_ends_at' => self::campaign_end(),
				'plans'         => self::NEW_USER_OFFER,
			);
		}

		$known     = get_transient( SettingsController::get_instance()->get_plan_cache_key() );
		$cache_key = self::cache_key( $website_id, is_array( $known ) && isset( $known['slug'] ) ? (string) $known['slug'] : '' );
		$cached    = get_transient( $cache_key );
		if ( is_array( $cached ) ) {
			return $cached;
		}
		if ( self::FAILED === $cached || self::NO_OFFER === $cached ) {
			return null;
		}
		$offer = $this->fetch_site_offer( $website_id );
		$this->remember( $cache_key, $offer );
		return is_array( $offer ) ? $offer : null;
	}

	/**
	 * What to show for a banner at a given time: the backend's offer, kept inside the compiled window.
	 *
	 * Response shape (REST GET, both routes):
	 * - `show`          bool   live and not dismissed (the banner).
	 * - `live`          bool   an offer is running now (the Plans page prices; independent of dismissal).
	 * - `ends_at`       string ISO 8601, the end to count down to first (advertised end), or null.
	 * - `final_ends_at` string ISO 8601, the end to count down to once `ends_at` has passed, or null.
	 * - `offer`         object tier slug => percent off (basic / pro / ultimate); empty when not live.
	 * No code string is ever returned.
	 *
	 * @param string $key Notice key.
	 * @param int    $now Unix timestamp.
	 * @return array
	 */
	public function resolve_offer( $key, $now ) {
		$none = array(
			'live'          => false,
			'ends_at'       => null,
			'final_ends_at' => null,
			'offer'         => array(),
		);
		if ( ! self::is_campaign_live( $now ) ) {
			return $none;
		}
		$offer = $this->get_offer( $key );
		if ( null === $offer ) {
			return $none;
		}
		// The dates are read now, not from a cached answer, so they always follow advertised_end() / campaign_end().
		$final = self::campaign_end();
		if ( $now > $final ) {
			return $none;
		}
		return array(
			'live'          => true,
			'ends_at'       => gmdate( 'c', min( self::advertised_end(), $final ) ),
			'final_ends_at' => gmdate( 'c', $final ),
			'offer'         => $offer['plans'],
		);
	}

	/**
	 * GET handler — campaign state for the dashboard banners and the Plans page.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_banner_status( $request ) {
		$key       = $this->notice_key( $request );
		$state     = $this->resolve_offer( $key, time() );
		$dismissed = Notice::get_instance()->is_dismissed( $key );
		return new \WP_REST_Response(
			array(
				'show'          => $state['live'] && ! $dismissed,
				'live'          => $state['live'],
				'ends_at'       => $state['ends_at'],
				'final_ends_at' => $state['final_ends_at'],
				'offer'         => $state['live'] ? $state['offer'] : new \stdClass(),
			),
			200
		);
	}

	/**
	 * POST handler — dismiss the banner for the rest of the campaign.
	 *
	 * The dismissal expires at campaign_end(), so the stored value (cky_admin_notices) is the end timestamp: it shows
	 * when the dismissal lapses, a past value brings the banner back for testing, and Notice removes the entry on its own
	 * once the campaign is over. At least a minute, as a 0 expiry would mean "forever".
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function dismiss_banner( $request ) {
		Notice::get_instance()->dismiss( $this->notice_key( $request ), max( MINUTE_IN_SECONDS, self::campaign_end() - time() ) );
		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	/**
	 * Permission check — only users who can manage options.
	 *
	 * @return bool
	 */
	public function permission_check() {
		return current_user_can( 'manage_options' );
	}
}
