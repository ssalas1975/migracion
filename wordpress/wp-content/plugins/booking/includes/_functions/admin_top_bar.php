<?php
/**
 * @version 1.0
 * @package Booking Calendar
 * @subpackage  Admin  Top  Bar
 * @category    Functions
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2024-09-03
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

// =====================================================================================================================
// ==  Admin  Top  Bar  ==
// =====================================================================================================================



/**
 * Resolve one canonical administration route for the WordPress admin bar.
 *
 * Canonical references centralize stable page identities without granting
 * access or registering controllers. Callers remain responsible for the same
 * role, edition, ownership, and mode gates that protect each menu item.
 *
 * @param string $canonical_page_id Canonical Booking Modes page identifier.
 * @param string $fallback_url      Established compatibility URL.
 *
 * @return string Canonical URL when registered, otherwise the fallback URL.
 */
function wpbc_admin_top_bar_get_canonical_route_url( $canonical_page_id, $fallback_url ) {
	$canonical_url = '';

	if ( function_exists( 'wpbc_booking_modes_get_canonical_page_url' ) ) {
		$canonical_url = wpbc_booking_modes_get_canonical_page_url( $canonical_page_id );
	}

	return is_string( $canonical_url ) && '' !== $canonical_url ? $canonical_url : $fallback_url;
}

/**
 * Resolve one canonical Bookings-area route for the WordPress admin bar.
 *
 * Booking Modes owns stable route identities. The explicit fallback preserves
 * compatibility when the canonical registry is unavailable in an older or
 * partially loaded edition, while still avoiding a request-dependent root URL.
 *
 * @param string $canonical_page_id Canonical Booking Modes page identifier.
 * @param string $fallback_tab      Established Bookings page tab identifier.
 *
 * @return string Absolute administration URL for the requested route.
 */
function wpbc_admin_top_bar_get_bookings_route_url( $canonical_page_id, $fallback_tab ) {
	return wpbc_admin_top_bar_get_canonical_route_url(
		$canonical_page_id,
		add_query_arg( 'tab', $fallback_tab, wpbc_get_bookings_url() )
	);
}

/**
 * Get the single effective Timeline item for the WordPress admin bar.
 *
 * The shared renderer policy accounts for the saved new/legacy-page choice,
 * feature availability, edition compatibility, and support overrides. Missing
 * Timeline policy code fails open to the released legacy route.
 *
 * @return array{canonical_page_id:string,tab:string,title:string,href:string,is_legacy:bool} Timeline menu item.
 */
function wpbc_admin_top_bar_get_timeline_menu_item() {
	$is_legacy = true;

	if ( function_exists( 'wpbc_timeline_should_show_legacy_admin_navigation' ) ) {
		$is_legacy = wpbc_timeline_should_show_legacy_admin_navigation();
	}

	$canonical_page_id = $is_legacy ? 'wpbc__vm_calendar' : 'wpbc__timeline';
	$timeline_tab       = $is_legacy ? 'vm_calendar' : 'timeline';

	return array(
		'canonical_page_id' => $canonical_page_id,
		'tab'               => $timeline_tab,
		'title'             => $is_legacy ? __( 'Timeline Legacy', 'booking' ) : __( 'Timeline New', 'booking' ),
		'href'              => wpbc_admin_top_bar_get_bookings_route_url( $canonical_page_id, $timeline_tab ),
		'is_legacy'         => $is_legacy,
	);
}

/**
 * Resolve the Bookings landing URL used by the admin-bar root and parent item.
 *
 * The saved default is read directly because the legacy page helper also reads
 * the current request. A toolbar rendered on another administration page must
 * not let that unrelated request override the configured landing
 * destination.
 *
 * @param array|null $timeline_menu_item Optional pre-resolved Timeline item.
 *
 * @return string Explicit Booking Listing or effective Timeline URL.
 */
function wpbc_admin_top_bar_get_bookings_landing_url( $timeline_menu_item = null ) {
	$saved_default_view = get_bk_option( 'booking_listing_default_view_mode' );

	if ( 'vm_calendar' === $saved_default_view ) {
		if ( ! is_array( $timeline_menu_item ) || empty( $timeline_menu_item['href'] ) ) {
			$timeline_menu_item = wpbc_admin_top_bar_get_timeline_menu_item();
		}

		return $timeline_menu_item['href'];
	}

	return wpbc_admin_top_bar_get_bookings_route_url( 'wpbc__vm_booking_listing', 'vm_booking_listing' );
}

/**
 * Determine whether one bundled mode page is visible in the selected mode.
 *
 * Booking Modes declarations remain presentation-only. This helper consumes
 * only an explicit `horizontal_menu.visible` decision and otherwise preserves
 * the established admin-bar default for extensions and compatibility builds.
 *
 * @param string $local_page_id     Booking Modes local page identifier.
 * @param bool   $default_visibility Visibility when no explicit decision exists.
 *
 * @return bool Effective mode-page visibility for this presentation surface.
 */
function wpbc_admin_top_bar_is_mode_page_visible( $local_page_id, $default_visibility = true ) {
	if ( ! function_exists( 'wpbc_booking_modes_v3_get_selected_definition' ) ) {
		return $default_visibility;
	}

	$mode_definition = wpbc_booking_modes_v3_get_selected_definition();
	if (
		! is_array( $mode_definition )
		|| empty( $mode_definition['pages'][ $local_page_id ] )
		|| ! is_array( $mode_definition['pages'][ $local_page_id ] )
		|| empty( $mode_definition['pages'][ $local_page_id ]['horizontal_menu'] )
		|| ! is_array( $mode_definition['pages'][ $local_page_id ]['horizontal_menu'] )
		|| ! array_key_exists( 'visible', $mode_definition['pages'][ $local_page_id ]['horizontal_menu'] )
	) {
		return $default_visibility;
	}

	return true === $mode_definition['pages'][ $local_page_id ]['horizontal_menu']['visible'];
}

/**
 * Get the mode-appropriate Add action shown in the WordPress admin bar.
 *
 * Appointment mode explicitly hides Add Booking and exposes Add Appointment.
 * Other bundled, custom, and compatibility modes retain the established Add
 * Booking action unless they provide the same explicit visibility contract.
 *
 * @return array{title:string,href:string} Add-action menu item.
 */
function wpbc_admin_top_bar_get_add_menu_item() {
	$uses_add_appointment = ! wpbc_admin_top_bar_is_mode_page_visible( 'add_booking', true )
		&& wpbc_admin_top_bar_is_mode_page_visible( 'add_appointment', false );

	if ( $uses_add_appointment ) {
		return array(
			'title' => __( 'Add Appointment', 'booking' ),
			'href'  => wpbc_admin_top_bar_get_canonical_route_url(
				'wpbc__add-appointment',
				add_query_arg( 'tab', 'add-appointment', wpbc_get_bookings_url() )
			),
		);
	}

	return array(
		'title' => __( 'Add booking', 'booking' ),
		'href'  => wpbc_admin_top_bar_get_canonical_route_url( 'wpbc__add-booking', wpbc_get_new_booking_url() ),
	);
}

/**
 * Resolve a Seasons link to the effective new or legacy administration page.
 *
 * The new Seasons catalog has its own route, while the released legacy tabs
 * live below Availability and Prices. Admin-bar entries must follow the same
 * effective renderer policy as native and Booking Calendar navigation so they
 * never target a legacy page that current navigation has hidden.
 *
 * @param string $legacy_canonical_page_id Canonical ID of the legacy Seasons tab.
 * @param string $legacy_url               Compatibility URL for that legacy tab.
 *
 * @return string Effective Seasons administration URL.
 */
function wpbc_admin_top_bar_get_seasons_url( $legacy_canonical_page_id, $legacy_url ) {
	$uses_new_pages = function_exists( 'wpbc_catalogs_should_use_new_renderer' )
		&& wpbc_catalogs_should_use_new_renderer();

	if ( $uses_new_pages ) {
		return wpbc_admin_top_bar_get_canonical_route_url(
			'wpbc-seasons__seasons',
			add_query_arg(
				array(
					'page' => 'wpbc-seasons',
					'tab'  => 'seasons',
				),
				admin_url( 'admin.php' )
			)
		);
	}

	return wpbc_admin_top_bar_get_canonical_route_url( $legacy_canonical_page_id, $legacy_url );
}

function wpbc_add__booking_menu__in__admin_top_bar(){

	global $wp_admin_bar;

	$current_user = wpbc_get_current_user();

	$curr_user_role = get_bk_option( 'booking_user_role_booking' );
	$level = 10;
	if ($curr_user_role == 'administrator')       $level = 10;
	else if ($curr_user_role == 'editor')         $level = 7;
	else if ($curr_user_role == 'author')         $level = 2;
	else if ($curr_user_role == 'contributor')    $level = 1;
	else if ($curr_user_role == 'subscriber')     $level = 0;

	$is_super_admin = apply_bk_filter('multiuser_is_user_can_be_here', false, 'only_super_admin');
	if (   ( ($current_user->user_level < $level) && (! $is_super_admin)  ) || !is_admin_bar_showing() ) return;


	$update_title = wpbc_get_svg_logo_for_html() . '&nbsp;' . 'Booking Calendar';

	$is_user_activated = apply_bk_filter( 'multiuser_is_current_user_active', true ); // FixIn: 6.0.1.17.
	$update_count      = wpbc_db_get_number_new_bookings();
	if ( ( $update_count > 0 ) && ( $is_user_activated ) ) {
		$update_title .= "&nbsp;<span class='booking-count bk-update-count' style='background: #f0f0f1;color: #2c3338;display: inline;padding: 2px 5px;font-weight: 600;border-radius: 10px;'>" . number_format_i18n( $update_count ) . '</span>';
	}

	$timeline_menu_item   = wpbc_admin_top_bar_get_timeline_menu_item();
	$add_menu_item        = wpbc_admin_top_bar_get_add_menu_item();
	$booking_listing_url  = wpbc_admin_top_bar_get_bookings_route_url( 'wpbc__vm_booking_listing', 'vm_booking_listing' );
	$bookings_landing_url = wpbc_admin_top_bar_get_bookings_landing_url( $timeline_menu_item );
	$block_dates_url      = wpbc_admin_top_bar_get_canonical_route_url(
		'wpbc-availability__availability',
		add_query_arg( 'tab', 'availability', wpbc_get_availability_url() )
	);
	$prices_url           = wpbc_admin_top_bar_get_canonical_route_url(
		'wpbc-prices__cost',
		add_query_arg( 'tab', 'cost', wpbc_get_price_url() )
	);
	$resources_url        = wpbc_admin_top_bar_get_canonical_route_url(
		'wpbc-resources__resources',
		add_query_arg( 'tab', 'resources', wpbc_get_resources_url() )
	);
	$settings_url         = wpbc_admin_top_bar_get_canonical_route_url(
		'wpbc-settings__general',
		add_query_arg( 'tab', 'general', wpbc_get_settings_url() )
	);

	// FixIn: 9.8.15.9.
	$wp_admin_bar->add_menu(
			array(
				'id' => 'wpbc_bar',
				'title' => $update_title ,
				'href' => $bookings_landing_url
				)
			);

	$wp_admin_bar->add_menu(
			array(
				'id' => 'wpbc_bar_bookings',
				'title' => __( 'Bookings', 'booking' ),
				'href' => $bookings_landing_url,
				'parent' => 'wpbc_bar',
			)
	);
		$wp_admin_bar->add_menu(
				array(
					'id' => 'wpbc_bar_booking_listing',
					'title' => __( 'Booking Listing', 'booking' ),
					'href' => $booking_listing_url,
					'parent' => 'wpbc_bar_bookings',
				)
		);
		$wp_admin_bar->add_menu(
				array(
					'id' => 'wpbc_bar_calendar_overview',
					'title' => $timeline_menu_item['title'],
					'href' => $timeline_menu_item['href'],
					'parent' => 'wpbc_bar_bookings',
				)
		);


	 $curr_user_role_settings = get_bk_option( 'booking_user_role_settings' );
	 $level = 10;
	 if ($curr_user_role_settings == 'administrator')       $level = 10;
	 else if ($curr_user_role_settings == 'editor')         $level = 7;
	 else if ($curr_user_role_settings == 'author')         $level = 2;
	 else if ($curr_user_role_settings == 'contributor')    $level = 1;
	 else if ($curr_user_role_settings == 'subscriber')     $level = 0;

	 if (   ( ($current_user->user_level < $level) && (! $is_super_admin)  ) || !is_admin_bar_showing() ) return;

	// Booking > Add booking page
	$wp_admin_bar->add_menu(
			array(
				'id' => 'wpbc_bar_new',
				'title' => $add_menu_item['title'],
				'href' => $add_menu_item['href'],
				'parent' => 'wpbc_bar',
			)
	);

	// Booking > Availability page
	$wp_admin_bar->add_menu(
			array(
				'id' => 'wpbc_bar_availability',
				'title' => __( 'Availability', 'booking' ),
				'href' => $block_dates_url,
				'parent' => 'wpbc_bar',
			)
	);
			$wp_admin_bar->add_menu(
				array(
					'id' => 'wpbc_bar_days_availability',
					'title' => __( 'Block Dates', 'booking' ),
					'href' => $block_dates_url,
					'parent' => 'wpbc_bar_availability'
				)
			);
		if ( wpbc_admin_top_bar_is_mode_page_visible( 'block_times', true ) ) {
			$wp_admin_bar->add_menu(
				array(
					'id'     => 'wpbc_bar_time_slots_availability',
					'title'  => __( 'Block Times', 'booking' ),
					'href'   => wpbc_admin_top_bar_get_canonical_route_url(
						'wpbc-availability__time_slots_availability',
						function_exists( 'wpbc_get_time_slots_availability_url' ) ? wpbc_get_time_slots_availability_url() : admin_url( 'admin.php?page=wpbc-availability&tab=time_slots_availability' )
					),
					'parent' => 'wpbc_bar_availability'
				)
			);
		}
			if ( wpbc_is_mu_user_can_be_here( 'only_super_admin' ) )
				$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_general_availability',
						'title' => __( 'Schedule & Rules', 'booking' ),
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-availability__general_availability',
							function_exists( 'wpbc_get_general_availability_url' ) ? wpbc_get_general_availability_url() : admin_url( 'admin.php?page=wpbc-availability&tab=general_availability' )
						),
						'parent' => 'wpbc_bar_availability'
					)
				);

			if ( class_exists( 'wpdev_bk_biz_m' ) ){
				$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_seasons_availability',
						'title' => __( 'Seasonal Availability', 'booking' ),
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-availability__season_availability',
							add_query_arg( 'tab', 'season_availability', wpbc_get_availability_url() )
						),
						'parent' => 'wpbc_bar_availability'
					)
				);

				$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_seasons_filters',
						'title' => __( 'Seasons', 'booking' ),
						'href' => wpbc_admin_top_bar_get_seasons_url(
							'wpbc-availability__filter',
							add_query_arg( 'tab', 'filter', wpbc_get_availability_url() )
						),
						'parent' => 'wpbc_bar_availability'
					)
				);
			}

	// Booking > Prices page
	if ( class_exists( 'wpdev_bk_biz_m' ) ){

		$wp_admin_bar->add_menu(
				array(
					'id' => 'wpbc_bar_prices',
					'title' => __( 'Prices', 'booking' ),
					'href' => $prices_url,
					'parent' => 'wpbc_bar',
				)
		);
			$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_daily_costs',
						'title' => __('Daily Costs','booking'),
						'href' => $prices_url,
						'parent' => 'wpbc_bar_prices',
					)
			);
			$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_cost_advanced',
						'title' => __('Form Options Costs','booking'),
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-prices__cost_advanced',
							add_query_arg( 'tab', 'cost_advanced', wpbc_get_price_url() )
						),
						'parent' => 'wpbc_bar_prices',
					)
			);
			if ( class_exists( 'wpdev_bk_biz_l' ) ){
				$wp_admin_bar->add_menu(
						array(
							'id' => 'wpbc_bar_coupons',
							'title' => __('Discount Coupons','booking'),
							'href' => wpbc_admin_top_bar_get_canonical_route_url(
								'wpbc-prices__coupons',
								add_query_arg( 'tab', 'coupons', wpbc_get_price_url() )
							),
							'parent' => 'wpbc_bar_prices',
						)
				);
			}
			$wp_admin_bar->add_menu(
				array(
					'id' => 'wpbc_bar_seasons_costs',
					'title' => __( 'Seasons', 'booking' ),
					'href' => wpbc_admin_top_bar_get_seasons_url(
						'wpbc-prices__filter',
						add_query_arg( 'tab', 'filter', wpbc_get_price_url() )
					),
					'parent' => 'wpbc_bar_prices'
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'id' => 'wpbc_bar_costs_payment_gateways',
					'title' => __( 'Payment Setup', 'booking' ),
					'href' => wpbc_admin_top_bar_get_canonical_route_url(
						'wpbc-settings__payment',
						add_query_arg( 'tab', 'payment', wpbc_get_settings_url() )
					),
					'parent' => 'wpbc_bar_prices'
				)
			);

	}

	//Booking > Resources page
	$wp_admin_bar->add_menu(
			array(
				'id' => 'wpbc_bar_resources',
				'title' => ( ( class_exists( 'wpdev_bk_personal' ) ) ? __( 'Resources', 'booking' ) : __( 'Resource', 'booking' ) ),
				'href' => $resources_url,
				'parent' => 'wpbc_bar',
			)
		);
			$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_resources_general',
						'title' => ( ( class_exists( 'wpdev_bk_personal' ) ) ? __( 'Resources', 'booking' ) : __( 'Resource', 'booking' ) ),
						'href' => $resources_url,
						'parent' => 'wpbc_bar_resources',
					)
				);

			if ( class_exists( 'wpdev_bk_biz_l' ) )
				$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_resources_searchable',
						'title' => __( 'Search Resources Setup', 'booking' ),//__( 'Searchable Resources', 'booking' ),//__( 'Search Availability', 'booking' ),
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-resources__searchable_resources',
							add_query_arg( 'tab', 'searchable_resources', wpbc_get_resources_url() )
						),
						'parent' => 'wpbc_bar_resources'
					)
				);

//					if ($is_super_admin)
//						if ( class_exists( 'wpdev_bk_biz_l' ) )
//							$wp_admin_bar->add_menu(
//								array(
//									'id' => 'wpbc_bar_resources_search',
//									'title' => __( 'Search Form / Results', 'booking' ),
//									'href' => $link_settings . '&tab=search',
//									'parent' => 'wpbc_bar_resources'
//								)
//							);


	//Booking > Settings General page

	$wp_admin_bar->add_menu(
			array(
				'id' => 'wpbc_bar_settings',
				'title' => __( 'Settings', 'booking' ),
				'href' => $settings_url,
				'parent' => 'wpbc_bar',
			)
	);

			if ($is_super_admin)
					$wp_admin_bar->add_menu(
						array(
							'id' => 'wpbc_bar_settings_general',
							'title' => __( 'General', 'booking' ),
							'href' => $settings_url,
							'parent' => 'wpbc_bar_settings'
						)
					);
			$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_settings_form',
						'title' => __( 'Booking Form', 'booking' ),
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-settings__builder_booking_form',
							add_query_arg( 'tab', 'builder_booking_form', wpbc_get_settings_url() )
						),
						'parent' => 'wpbc_bar_settings'
					)
			);
			$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_settings_email',
						'title' => __( 'Emails', 'booking' ),
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-settings__email',
							add_query_arg( 'tab', 'email', wpbc_get_settings_url() )
						),
						'parent' => 'wpbc_bar_settings'
					)
			);
			$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_settings_sync',
						'title' => __( 'Sync', 'booking' ),															//FixIn: 8.0
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-settings__sync',
							add_query_arg( 'tab', 'sync', wpbc_get_settings_url() )
						),
						'parent' => 'wpbc_bar_settings'
					)
			);
			if ( class_exists( 'wpdev_bk_biz_s' ) )
				$wp_admin_bar->add_menu(
					array(
						'id' => 'wpbc_bar_settings_payment',
						'title' => __( 'Payment Setup', 'booking' ),
						'href' => wpbc_admin_top_bar_get_canonical_route_url(
							'wpbc-settings__payment',
							add_query_arg( 'tab', 'payment', wpbc_get_settings_url() )
						),
						'parent' => 'wpbc_bar_settings'
					)
				);

			if ( ( class_exists( 'wpdev_bk_biz_l' ) ) && ( wpbc_is_mu_user_can_be_here( 'only_super_admin' ) ) )
					$wp_admin_bar->add_menu(
						array(
							'id' => 'wpbc_bar_settings_search',
							'title' => __( 'Search Form / Results', 'booking' ),
							'href' => wpbc_admin_top_bar_get_canonical_route_url(
								'wpbc-settings__search',
								add_query_arg( 'tab', 'search', wpbc_get_settings_url() )
							),
							'parent' => 'wpbc_bar_settings'
						)
					);
			if ( class_exists( 'wpdev_bk_multiuser' ) )
				if ($is_super_admin)
					$wp_admin_bar->add_menu(
						array(
							'id' => 'wpbc_bar_settings_users',
							'title' => __( 'Users', 'booking' ),
							'href' => wpbc_admin_top_bar_get_canonical_route_url(
								'wpbc-settings__users',
								add_query_arg( 'tab', 'users', wpbc_get_settings_url() )
							),
							'parent' => 'wpbc_bar_settings'
						)
					);
}
add_action( 'admin_bar_menu', 'wpbc_add__booking_menu__in__admin_top_bar', 70 );									    // Add   -  TOP Bar   - in admin menu
