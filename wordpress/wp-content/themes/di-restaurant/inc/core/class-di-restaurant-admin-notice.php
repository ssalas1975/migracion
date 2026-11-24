<?php
/**
 * Di Restaurant Admin Notice
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Di_Restaurant_Admin_Notice {

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'wp_ajax_di_restaurant_dismiss_notice', array( $this, 'dismiss_notice' ) );
		add_action( 'after_switch_theme', array( $this, 'reset_notice' ) );
	}

	public function reset_notice() {
		delete_metadata( 'user', 0, 'di_restaurant_pro_notice_dismissed', '', true );
	}

	public function admin_notice() {
		// Do not show if pro is active
		if ( function_exists( 'di_restaurant_pro' ) ) {
			return;
		}

		// Check if user has dismissed the notice
		$user_id = get_current_user_id();
		if ( get_user_meta( $user_id, 'di_restaurant_pro_notice_dismissed', true ) ) {
			return;
		}

		$nonce = wp_create_nonce( 'di_restaurant_dismiss_notice_nonce' );
		?>
		<div class="notice is-dismissible di-restaurant-pro-notice" style="border-left: 4px solid #135e96; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.05); margin-top: 20px; position: relative;">
			<div style="padding: 15px 30px 15px 15px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
				<div class="di-restaurant-notice-content" style="display: flex; align-items: center; gap: 20px;">
					<div class="notice-icon" style="color: #135e96; flex-shrink: 0;">
						<span class="dashicons dashicons-star-filled" style="font-size: 40px; width: 40px; height: 40px; line-height: 1;"></span>
					</div>
					<div>
						<h3 style="margin: 0 0 5px 0; color: #135e96; font-size: 16px; font-weight: 600;"><?php esc_html_e( 'Unlock Premium Features with Di Restaurant Pro!', 'di-restaurant' ); ?></h3>
						<p style="font-size: 14px; margin: 0; color: #3c434a;">
							<?php esc_html_e( 'Take your restaurant website to the next level. Customize footer credit link, advanced header & slider options, widget area creation, full color controls, premium support, and much more for just $14.', 'di-restaurant' ); ?>
						</p>
					</div>
				</div>
				<div class="di-restaurant-notice-action" style="white-space: nowrap;">
					<a href="https://dithemes.com/product/di-restaurant-pro-wordpress-theme/" target="_blank" class="button button-primary button-hero" style="background: #135e96; border-color: #135e96; text-shadow: none; box-shadow: none; font-weight: 600; padding: 6px 20px; height: auto; font-size: 15px; border-radius: 3px; display: inline-block; text-decoration: none;"><?php esc_html_e( 'Upgrade to Pro - $14', 'di-restaurant' ); ?></a>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(document).on('click', '.di-restaurant-pro-notice .notice-dismiss', function() {
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'di_restaurant_dismiss_notice',
							nonce: '<?php echo esc_js( $nonce ); ?>'
						}
					});
				});
			});
		</script>
		<?php
	}

	public function dismiss_notice() {
		check_ajax_referer( 'di_restaurant_dismiss_notice_nonce', 'nonce' );
		$user_id = get_current_user_id();
		add_user_meta( $user_id, 'di_restaurant_pro_notice_dismissed', 'true', true );
		wp_send_json_success();
	}
}

new Di_Restaurant_Admin_Notice();
