<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Tag generator helper scripts
 */
function cf7msm_admin_enqueue_scripts( $hook_suffix ) {
    $notice_big_cookie = cf7msm_maybe_display_notice_big_cookie();
    $notice_num = cf7msm_maybe_display_notice();
    if ( $notice_big_cookie || !empty( $notice_num ) ) {

        wp_enqueue_script( 'cf7msm-admin-notice',
            cf7msm_url( 'resources/cf7msm-notice.min.js' ),
            array( 'jquery' ),
            CF7MSM_VERSION, true );
        wp_enqueue_style( 'cf7msm-admin-notice',
            cf7msm_url( 'resources/cf7msm-notice.css' ),
            array(), CF7MSM_VERSION );

        wp_localize_script( 'cf7msm-admin-notice', 'cf7msm_admin', array(
            'nonce'                  => wp_create_nonce( 'cf7msm-admin-nonce' ),
            'confirm_cookie_warning' => __( 'Dismiss this warning? It will return if another submission exceeds the cookie size limit.', 'contact-form-7-multi-step-module' ),
        ));
    }

    if ( false === strpos( $hook_suffix, 'wpcf7' ) ) {
        return;
    }
    wp_enqueue_script( 'cf7msm-admin-taggenerator',
        cf7msm_url( 'form-tags/js/tag-generator.js' ),
        array( 'jquery' ), CF7MSM_VERSION, true );

    wp_enqueue_style( 'cf7msm-admin',
        cf7msm_url( 'form-tags/css/styles.css' ),
        array( 'contact-form-7-admin' ), CF7MSM_VERSION );

    // The free build's cookie usage UI also appears on sites with a paid license.
    if ( ( cf7msm_cookie_usage_ui_enabled() && !cf7msm_fs()->is__premium_only() ) || cf7msm_fs()->is_not_paying() ) {
        wp_enqueue_style( 'cf7msm-admin-panel',
            cf7msm_url( 'resources/cf7msm-admin.css' ),
            array( 'contact-form-7-admin' ), CF7MSM_VERSION );
        wp_enqueue_script( 'cf7msm-admin-panel',
            cf7msm_url( 'resources/cf7msm-admin.min.js' ),
            array( 'jquery' ),
            CF7MSM_VERSION, true );
    }

    $wpcf7 = WPCF7_ContactForm::get_current();

    if ( $wpcf7 ) {
        if ( cf7msm_fs()->is_not_paying() ) {
            $tags = $wpcf7->scan_form_tags( array( 
                'type' => array( 'multistep' ) 
            ) );

            if ( !empty( $tags ) ) {
                add_action( 'wpcf7_admin_footer', 'cf7msm_upgrade_panel' );   
            }
        }
        // Without an available dashboard widget, use the flow's last form or
        // a step whose unfinished flow neared the limit. A new, unsaved form
        // has no id, which would list every form's log.
        if ( cf7msm_cookie_usage_ui_enabled() && !cf7msm_fs()->is__premium_only() && !cf7msm_has_cookie_dashboard_widget()
            && ( cf7msm_form_is_last_step( $wpcf7 )
                || ( $wpcf7->id() && cf7msm_cookie_usage_log( $wpcf7->id() ) ) ) ) {
            add_action( 'wpcf7_admin_footer', 'cf7msm_cookie_usage_panel' );
        }
    }

}
add_action( 'admin_enqueue_scripts', 'cf7msm_admin_enqueue_scripts' );

/**
 * Show upgrade information
 */
function cf7msm_upgrade_panel() {
?>
    <div id="upgradediv" class="postbox hide">
        <h3><?php echo esc_html( __( 'Webheadcoder Multi-Step Forms for Contact Form 7', 'contact-form-7-multi-step-module' ) ); ?></h3>
        <div class="inside">
            <?php echo wp_kses_post( __( '<p>Not getting all information from your Multi-Step forms? </p><p>Consider upgrading to allow for longer multi-step forms.</p>', 'contact-form-7-multi-step-module' ) ); ?>
            <br>
            <div class="aligncenter">
                <a href="<?php echo esc_url( cf7msm_fs()->get_upgrade_url() ); ?>" class="cf7msm-freemius-purchase"><?php echo esc_html( __( 'Upgrade Now', 'contact-form-7-multi-step-module' ) ); ?></a><br>
                <a href="<?php echo esc_url( CF7MSM_LEARN_MORE_URL ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( __( 'Learn more', 'contact-form-7-multi-step-module' ) ); ?><span class="external dashicons dashicons-external" aria-hidden="true"></span></a>
            </div>
        </div>
    </div><!-- #upgradediv -->
<?php
}


/**
 * Keep the unreleased monitor UI opt-in; cookie warnings remain active.
 */
function cf7msm_cookie_usage_ui_enabled() {
    return (bool) apply_filters( 'cf7msm_cookie_usage_ui_enabled', false );
}

/**
 * Whether this Contact Form 7 version has its own dashboard screen (6.2+).
 */
function cf7msm_has_cf7_dashboard() {
    return defined( 'WPCF7_PLUGIN_DIR' ) && file_exists( WPCF7_PLUGIN_DIR . '/admin/includes/dashboard.php' );
}

/**
 * Prefer the dashboard unless its registration was disabled or the current
 * user's last dashboard visit showed that our widget did not render.
 */
function cf7msm_has_cookie_dashboard_widget() {
    return cf7msm_cookie_usage_ui_enabled() && cf7msm_has_cf7_dashboard()
        && false !== has_action( 'wpcf7_dashboard_setup', 'cf7msm_dashboard_setup' )
        && 'no' !== get_user_option( 'cf7msm_dashboard_widget_available' );
}

/**
 * Whether a form is the last step of a multi-step flow.
 */
function cf7msm_form_is_last_step( $wpcf7 ) {
    // Legacy final steps may omit the next-page URL: [multistep "2-2"].
    if ( preg_match( '/\[multistep\s+"(\d+)-(\d+)(?:-[^"]*)?"\]/', $wpcf7->prop( 'form' ), $steps ) ) {
        return intval( $steps[1] ) > 0 && $steps[1] === $steps[2];
    }
    $tags = $wpcf7->scan_form_tags( array(
        'type' => array( 'multistep' )
    ) );
    foreach ( $tags as $tag ) {
        $options = cf7msm_get_multistep_tag_options( $tag );
        if ( !empty( $options['last_step'] ) ) {
            return true;
        }
    }
    return false;
}

/**
 * Logged cookie sizes, newest flow first, keyed by form id. A completed flow
 * is listed under its last-step form. A flow that neared the limit is listed
 * as soon as it does, under the step form that reached its largest size, and
 * moves to its last-step form when completed.
 */
function cf7msm_cookie_usage_log( $form_id = 0 ) {
    $log = get_option( '_cf7msm_cookie_log', array() );
    if ( !is_array( $log ) ) {
        return array();
    }
    $forms = array();
    foreach ( array_reverse( $log, true ) as $id => $entries ) {
        if ( !is_array( $entries ) || ( $form_id && intval( $id ) !== intval( $form_id ) ) ) {
            continue;
        }
        $entries = array_filter( $entries, function( $entry ) {
            return is_array( $entry ) && isset( $entry['time'], $entry['size'] );
        } );
        if ( !empty( $entries ) ) {
            $forms[intval( $id )] = array_values( $entries );
        }
    }
    return $forms;
}

/**
 * Where the recent submissions are shown: an available dashboard widget, or
 * the form whose flow most recently neared the limit.
 */
function cf7msm_cookie_usage_url() {
    if ( !cf7msm_cookie_usage_ui_enabled() || !function_exists( 'wpcf7_contact_form' ) || !current_user_can( 'wpcf7_read_contact_forms' ) ) {
        return '';
    }
    if ( cf7msm_has_cookie_dashboard_widget() ) {
        return admin_url( 'admin.php?page=wpcf7-dashboard#cf7msm_cookie_usage' );
    }
    $form_id = 0;
    $latest = 0;
    foreach ( cf7msm_cookie_usage_log() as $id => $entries ) {
        foreach ( $entries as $entry ) {
            if ( $entry['size'] >= CF7MSM_COOKIE_SIZE_THRESHOLD && $entry['time'] > $latest ) {
                $latest = $entry['time'];
                $form_id = $id;
            }
        }
    }
    // Loading a CF7 instance changes its current form, which can make the
    // Contact Forms list render an editor after this admin notice runs.
    if ( empty( $form_id ) || 'wpcf7_contact_form' !== get_post_type( $form_id ) ) {
        return '';
    }
    return admin_url( 'admin.php?page=wpcf7&post=' . $form_id . '&action=edit#cf7msm-cookie-usage-div' );
}

/**
 * Print the last 3 submissions of each flow with their largest cookie size.
 *
 * @param int $form_id Limit to the flows logged under this form (completed on
 *                     it, or not yet completed and largest on it); 0 for all.
 */
function cf7msm_cookie_usage_render( $form_id = 0 ) {
    $limit = CF7MSM_COOKIE_SIZE_LIMIT;
    $max_size = 0;
    $forms = array();
    foreach ( cf7msm_cookie_usage_log( $form_id ) as $id => $entries ) {
        // Read display data without changing CF7's current form. Skip flows
        // whose form has been deleted or is no longer a contact form.
        $contact_form = get_post( $id );
        if ( $contact_form && 'wpcf7_contact_form' === $contact_form->post_type ) {
            $forms[$id] = array( $contact_form, $entries );
            foreach ( $entries as $entry ) {
                $max_size = max( $max_size, intval( $entry['size'] ) );
            }
        }
    }
    // All rows share one scale, with room beyond the limit for oversized cookies.
    $scale = max( $max_size, $limit * 1.25 );
    $limit_pos = round( $limit / $scale * 100, 1 );
    /* translators: %s: cookie size limit in kilobytes. */
    $limit_text = sprintf( __( '%s KB limit', 'contact-form-7-multi-step-module' ), number_format_i18n( $limit / 1024, 0 ) );
?>
    <div class="cf7msm-cookie-usage">
        <p class="cf7msm-cookie-description">
            <?php echo esc_html( __( 'Form submissions are currently limited to 4 KB due to browser limits.', 'contact-form-7-multi-step-module' ) ); ?>
            <a href="<?php echo esc_url( 'https://webheadcoder.com/too-many-cookies/' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( __( 'Learn more', 'contact-form-7-multi-step-module' ) ); ?></a>
        </p>
        <?php if ( empty( $forms ) ) : ?>
        <p class="cf7msm-cookie-empty"><?php echo esc_html( __( 'No multi-step submissions have been recorded yet.', 'contact-form-7-multi-step-module' ) ); ?></p>
        <?php endif; ?>
        <?php foreach ( $forms as $id => $form ) : ?>
        <div class="cf7msm-cookie-form">
        <div class="cf7msm-cookie-head">
            <?php if ( empty( $form_id ) ) : ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpcf7&post=' . $id . '&action=edit' ) ); ?>"><?php echo esc_html( $form[0]->post_title ); ?></a>
            <?php endif; ?>
            <span class="cf7msm-cookie-meta"><?php
                $entry_count = count( $form[1] );
                /* translators: %d: number of recent submissions. */
                echo esc_html( sprintf( _n( 'Last %d submission', 'Last %d submissions', $entry_count, 'contact-form-7-multi-step-module' ), $entry_count ) );
            ?></span>
        </div>
        <ul>
            <?php foreach ( $form[1] as $index => $entry ) :
                $size = intval( $entry['size'] );
                $status = 'ok';
                $status_text = __( 'OK', 'contact-form-7-multi-step-module' );
                if ( $size > $limit ) {
                    $status = 'over';
                    $status_text = __( 'Over limit', 'contact-form-7-multi-step-module' );
                }
                else if ( $size >= CF7MSM_COOKIE_SIZE_THRESHOLD ) {
                    $status = 'near';
                    $status_text = __( 'Near limit', 'contact-form-7-multi-step-module' );
                }
                $percent = min( 100, max( 2, round( $size / $scale * 100, 1 ) ) );
            ?>
            <li class="cf7msm-cookie-row cf7msm-cookie-<?php echo esc_attr( $status ); ?>">
                <div class="cf7msm-cookie-line">
                    <span class="cf7msm-cookie-time"><?php echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), intval( $entry['time'] ) ) ); ?></span>
                    <span class="cf7msm-cookie-size"><?php
                        /* translators: %s: cookie size in kilobytes. */
                        echo esc_html( sprintf( __( '%s KB', 'contact-form-7-multi-step-module' ), number_format_i18n( $size / 1024, 1 ) ) );
                    ?> <span class="screen-reader-text"><?php echo esc_html( $limit_text ); ?></span></span>
                </div>
                <?php if ( 0 === $index ) : ?>
                <div class="cf7msm-cookie-limit-label" aria-hidden="true">
                    <span style="left: <?php echo esc_attr( $limit_pos ); ?>%;"><?php echo esc_html( $limit_text ); ?></span>
                </div>
                <?php endif; ?>
                <div class="cf7msm-cookie-bar" aria-hidden="true">
                    <div class="cf7msm-cookie-track"><div class="cf7msm-cookie-fill" style="width: <?php echo esc_attr( $percent ); ?>%;"></div></div>
                    <div class="cf7msm-cookie-limit" style="left: <?php echo esc_attr( $limit_pos ); ?>%;"></div>
                </div>
                <div class="cf7msm-cookie-status">
                    <span class="cf7msm-cookie-pill"><?php echo esc_html( $status_text ); ?></span>
                    <?php if ( 'over' === $status ) : ?>
                    <span class="cf7msm-cookie-note"><?php echo esc_html( __( 'Form data was likely lost.', 'contact-form-7-multi-step-module' ) ); ?></span>
                    <?php endif; ?>
                    <?php if ( !empty( $entry['incomplete'] ) ) : ?>
                    <span class="cf7msm-cookie-note cf7msm-cookie-incomplete"><?php echo esc_html( __( 'Last step not submitted yet.', 'contact-form-7-multi-step-module' ) ); ?></span>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        </div>
        <?php endforeach; ?>
    </div>
<?php
}

/**
 * Add the cookie usage widget to the Contact Form 7 dashboard.
 */
function cf7msm_dashboard_setup() {
    // Premium builds use sessionStorage instead of cookies for saved fields.
    if ( !cf7msm_cookie_usage_ui_enabled() || cf7msm_fs()->is__premium_only() ) {
        return;
    }
    wp_add_dashboard_widget(
        'cf7msm_cookie_usage',
        __( 'Multi-Step Form Cookie Size Monitor', 'contact-form-7-multi-step-module' ),
        'cf7msm_dashboard_cookie_usage_render',
        null,
        null,
        'side'
    );
}
add_action( 'wpcf7_dashboard_setup', 'cf7msm_dashboard_setup' );

/**
 * Mark actual dashboard rendering, so removing a registered meta box is also
 * detected. Merely visiting a form editor must not change dashboard status.
 */
function cf7msm_dashboard_cookie_usage_render() {
    $GLOBALS['cf7msm_dashboard_widget_rendered'] = true;
    cf7msm_cookie_usage_render();
}

/**
 * Dashboard setup and rendering are complete by admin_footer. Keep this
 * per user and site; a later successful dashboard visit restores the widget
 * destination automatically. Screen Options hiding is a user preference,
 * not a registration failure (WordPress still renders hidden meta boxes).
 */
function cf7msm_record_dashboard_widget_status() {
    $screen = get_current_screen();
    if ( !cf7msm_cookie_usage_ui_enabled() || !$screen || 'toplevel_page_wpcf7-dashboard' !== $screen->id
        || cf7msm_fs()->is__premium_only() ) {
        return;
    }
    $available = !empty( $GLOBALS['cf7msm_dashboard_widget_rendered'] ) ? 'yes' : 'no';
    if ( get_user_option( 'cf7msm_dashboard_widget_available' ) !== $available ) {
        update_user_option( get_current_user_id(), 'cf7msm_dashboard_widget_available', $available );
    }
}
add_action( 'admin_footer', 'cf7msm_record_dashboard_widget_status' );

/**
 * Show cookie usage beside the editor of a flow's last form, or of a step
 * form with logged entries.
 */
function cf7msm_cookie_usage_panel( $post ) {
?>
    <div id="cf7msm-cookie-usage-div" class="postbox hide">
        <h3><?php echo esc_html( __( 'Multi-Step Form Cookie Size Monitor', 'contact-form-7-multi-step-module' ) ); ?></h3>
        <div class="inside">
            <?php cf7msm_cookie_usage_render( $post->id() ); ?>
        </div>
    </div><!-- #cf7msm-cookie-usage-div -->
<?php
}


/**
 * Display review notice
 */
function cf7msm_review_notice() { 
    $notice_big_cookie = cf7msm_maybe_display_notice_big_cookie();
    if ( $notice_big_cookie ) {
        // big cookie notice is more important.  only display one at a time.
        return;
    }
    $notice_num = cf7msm_maybe_display_notice();
    if ( empty( $notice_num ) ) {
        return;
    }

    // don't show review if they need the pro version
    $stats = get_option( '_cf7msm_stats', array() );
    if ( !empty( $stats['big_cookies'] ) && cf7msm_fs()->is_free_plan() ) {
        return;
    }
?>
    <div class="notice notice-info cf7msm-notice cf7msm-notice-review">
        <div class="cf7msm-notice-inner">
            <div class="cf7msm-notice-icon">
                <img src="<?php echo esc_url( cf7msm_url( '/resources/plugin-icon.png' ) ); ?>" width="64">
            </div>
            <div class="cf7msm-notice-content">
                <?php if ( $notice_num == 1 ) : ?>
                <h3><?php echo esc_html( __( 'Your Multi-Step Forms are doing great!', 'contact-form-7-multi-step-module' ) ); ?></h3>
                <p><?php echo wp_kses_post( __( 'When you get a chance, could you help me out by leaving a 5-star review for <strong>Webheadcoder Multi-Step Forms for Contact Form 7</strong> on WordPress? It makes me feel awesome to know the plugin is helping others.', 'contact-form-7-multi-step-module' ) ); ?></p>
                <?php else : ?>
                <h3><?php echo esc_html( __( 'Wow!  Your Multi-Step Forms are taking off!', 'contact-form-7-multi-step-module' ) ); ?></h3>
                <p><?php echo wp_kses_post( __( 'I would really appreciate it if you could leave a 5-star review for <strong>Webheadcoder Multi-Step Forms for Contact Form 7</strong> on WordPress. It helps to hear that people are benefiting from the plugin.', 'contact-form-7-multi-step-module' ) ); ?></p>
                <?php endif; ?>
            </div>
            <div class="cf7msm-notice-actions">
                <a href="https://wordpress.org/support/view/plugin-reviews/contact-form-7-multi-step-module#new-post" class="button button-primary cf7msm-review-button" target="_blank"><?php echo esc_html( __( 'Leave a Review', 'contact-form-7-multi-step-module' ) ); ?></a>
                <div class="other-buttons">
                    <a href="#" class="cf7msm-did"><?php echo esc_html( __( 'I already did', 'contact-form-7-multi-step-module' ) ); ?></a>
                    <span class="spacer">|</span>
                    <a href="#" class="cf7msm-later"><?php echo esc_html( __( 'Maybe later', 'contact-form-7-multi-step-module' ) ); ?></a>

                </div>
            </div>
        </div>
    </div>
<?php }
add_action( 'admin_notices', 'cf7msm_review_notice' );

/**
 * Display review notice
 */
function cf7msm_big_cookie_notice() { 
    $notice_big_cookie = cf7msm_maybe_display_notice_big_cookie();
    if ( !$notice_big_cookie ) {
        return;
    }
    $stats = get_option( '_cf7msm_stats', array() );
    if ( empty( $stats['big_cookies'] ) ) {
        return false;
    }
    $num_cookies_str = '' . $stats['big_cookies'];
    if ( $stats['big_cookies'] < 10 ) {
        $num_array = array( 
            '', 
            __( 'one', 'contact-form-7-multi-step-module' ), 
            __( 'two', 'contact-form-7-multi-step-module' ), 
            __( 'three', 'contact-form-7-multi-step-module' ), 
            __( 'four', 'contact-form-7-multi-step-module' ), 
            __( 'five', 'contact-form-7-multi-step-module' ), 
            __( 'six', 'contact-form-7-multi-step-module' ), 
            __( 'seven', 'contact-form-7-multi-step-module' ), 
            __( 'eight', 'contact-form-7-multi-step-module' ), 
            __( 'nine', 'contact-form-7-multi-step-module' ), 
            __( 'ten', 'contact-form-7-multi-step-module' )
        );
        $num_cookies_str = ucfirst( $num_array[$stats['big_cookies']] );
    }
    $usage_url = cf7msm_cookie_usage_url();
?>
    <div class="notice notice-error cf7msm-notice cf7msm-notice-cookie">
        <div class="cf7msm-notice-inner">
            <div class="cf7msm-notice-icon">
                <img src="<?php echo esc_url( cf7msm_url( '/resources/plugin-icon.png' ) ); ?>" width="64" alt="">
            </div>
            <div class="cf7msm-notice-content">
                <h3><?php echo esc_html( __( 'Potential data loss in Multi-Step Forms detected', 'contact-form-7-multi-step-module' ) ); ?></h3>
                <p><?php
                    /* translators: %1$s: number of oversized form submissions. */
                    echo wp_kses_post( sprintf( __( '<strong>%1$s</strong> multi-step form submissions have exceeded 90%% of the standard browser\'s cookie size.  You may not be getting everything your users submit from your multi-step forms!', 'contact-form-7-multi-step-module' ), esc_html( $num_cookies_str ) ) );
                ?></p>
                <p><?php echo wp_kses_post( __( 'Make sure you never lose a submission by upgrading to <strong>Webheadcoder Multi-Step Forms for Contact Form 7 Pro</strong>.', 'contact-form-7-multi-step-module' ) ); ?></p>
            </div>
            <div class="cf7msm-notice-actions">
                <a href="<?php echo esc_url( cf7msm_fs()->get_upgrade_url() ); ?>" class="button button-primary cf7msm-review-button"><?php echo esc_html( __( 'Upgrade to Pro', 'contact-form-7-multi-step-module' ) ); ?></a>
            </div>
        </div>
        <div class="cf7msm-notice-footer">
            <div class="cf7msm-notice-links">
                <?php if ( $usage_url ) : ?>
                <a href="<?php echo esc_url( $usage_url ); ?>"><?php echo esc_html( __( 'View recent submissions', 'contact-form-7-multi-step-module' ) ); ?></a>
                <span class="spacer" aria-hidden="true">|</span>
                <?php endif; ?>
                <a href="<?php echo esc_url( 'https://webheadcoder.com/contact-form-7-multi-step-forms/#pro' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( __( 'Learn More', 'contact-form-7-multi-step-module' ) ); ?></a>
            </div>
            <div class="cf7msm-notice-dismiss">
                <a href="#" class="cf7msm-later"><?php echo esc_html( __( 'Remind me later', 'contact-form-7-multi-step-module' ) ); ?></a>
                <span class="spacer" aria-hidden="true">|</span>
                <a href="#" class="trash cf7msm-did"><?php echo esc_html( __( 'Dismiss forever', 'contact-form-7-multi-step-module' ) ); ?></a>
            </div>
        </div>
    </div>
<?php }
add_action( 'admin_notices', 'cf7msm_big_cookie_notice' );

/**
 * Return a number for which notice to display depending on the timing and usage.
 */
function cf7msm_maybe_display_notice() {
    $stats = get_option( '_cf7msm_stats', array() );
    $notice = !empty( $stats['notice'] ) ? $stats['notice'] : '';
    if ( $notice == 'no' || $notice == 'yes' ) {
        return 0;
    }
    $install_date = !empty( $stats['install_date'] ) ? $stats['install_date'] : 0;
    $one_week_ago = ( time() - 604800 );
    if ( $install_date > $one_week_ago ) {
        return;
    }
    if ( !empty( $notice ) && $notice > $one_week_ago ) {
        return;
    }

    $count = !empty( $stats['count'] ) ? $stats['count'] : 0;

    if ( $count > 5 && !empty( $notice ) ) {
        return 2;
    }
    if ( $count > 3 ) {
        return 1;
    }

    
    return 0;
}

/**
 * Return a true if big cookie notice should be displayed
 */
function cf7msm_maybe_display_notice_big_cookie() {
    $stats = get_option( '_cf7msm_stats', array() );
    $notice = !empty( $stats['notice-big-cookie'] ) ? $stats['notice-big-cookie'] : '';
    if ( $notice == 'no' ) {
        return false;
    }
    if ( empty( $stats['big_cookies'] ) ) {
        return false;
    }
    // Stay hidden for a day after a dismissal, even if an over-limit save re-armed it.
    if ( !empty( $stats['notice-big-cookie-time'] ) && time() - intval( $stats['notice-big-cookie-time'] ) < DAY_IN_SECONDS ) {
        return false;
    }

    $display = false;

    if ( empty( $notice ) ) {
        if ( $stats['big_cookies'] >= 1 ) {
            $display = true;
        }
    }
    else if ( $stats['big_cookies'] >= $notice + 1 ) {
        $display = true;
    }
    
    return $display;
}

/**
 * note at top of form tags
 */
function cf7msm_form_tag_header_text( $description ) {
    $desc_link = wpcf7_link( 
        'https://wordpress.org/plugins/contact-form-7-multi-step-module/', 
        esc_html( __( 'the plugin page on WordPress.org', 'contact-form-7-multi-step-module' ) ), 
        array( 'target' => '_blank' )
    );
    echo wp_kses_post( sprintf( $description, $desc_link ) );
}

/**
 * Links to help the plugin.
 */
function cf7msm_form_tag_footer_text() {
    $url_review = 'https://wordpress.org/support/view/plugin-reviews/contact-form-7-multi-step-module#postform';
?>
    <p class="description" style="font-size:12px;margin-top:0;padding-top:0;font-style:normal;">
        <?php 
        /* translators: %1$s: URL of the plugin review page on WordPress.org. */
        $review_prompt = __( 'Let me know how the Multi-Step forms are working for you <a href="%1$s" target="_blank">here</a>.', 'contact-form-7-multi-step-module' );
        echo wp_kses_post( sprintf(
            $review_prompt,
            esc_url( $url_review )
        ) );
         ?>
    </p>
    <div style="position:absolute; right:25px; bottom:5px;">
        <a href="https://webheadcoder.com" target="_blank"><img src="<?php echo esc_url( cf7msm_url( '/resources/logo.png' ) ); ?>" width="40"></a>
    </div>
<?php
}



/**
 * Log what the user said to the review notice.
 */
function cf7msm_notice_response() {
    if ( !check_ajax_referer('cf7msm-admin-nonce', 'nonce', false) ){
        wp_send_json( 0 );
    }
    if ( !current_user_can( 'manage_options' ) ) {
        wp_send_json( 0 );
    }
    if ( !isset( $_POST['request_type'] ) ) {
        wp_send_json( 0 );
    }
    $data = !empty( $_POST['request_type'] );
    $stats = get_option( '_cf7msm_stats', array() );
    if ( !$data ) {    
        $notice = !empty( $stats['notice'] ) ? $stats['notice'] : '';
        if ( empty( $notice ) ) {
            $stats['notice'] = time();
        }
        else {
            $stats['notice'] = 'no';
        }
    }
    elseif ( $data ) {
        $stats['notice'] = 'yes';
    }
    update_option( '_cf7msm_stats', $stats );
    wp_send_json( 1 );
}
add_action('wp_ajax_cf7msm-notice-response', 'cf7msm_notice_response');


/**
 * Log what the user said to the big cookie notice.
 */
function cf7msm_notice_response_big_cookie() {
    if ( !check_ajax_referer('cf7msm-admin-nonce', 'nonce', false) ){
        wp_send_json( 0 );
    }
    if ( !current_user_can( 'manage_options' ) ) {
        wp_send_json( 0 );
    }
    if ( !isset( $_POST['request_type'] ) ) {
        wp_send_json( 0 );
    }
    $data = !empty( $_POST['request_type'] );
    $stats = get_option( '_cf7msm_stats', array() );
    if ( empty( $stats['big_cookies'] ) ) {
        // shouldn't get here
        wp_send_json( 1 );
    }
    if ( !$data ) {    
        // set the notice marker to the last seen number of big cookies.
        $stats['notice-big-cookie'] = $stats['big_cookies'];
    }
    elseif ( $data ) {
        $stats['notice-big-cookie'] = 'no';
    }
    $stats['notice-big-cookie-time'] = time();
    update_option( '_cf7msm_stats', $stats );
    wp_send_json( 1 );
}
add_action('wp_ajax_cf7msm-notice-response-big-cookie', 'cf7msm_notice_response_big_cookie');


/**
 * Return true if we can use version 2 in CF7 6.0+
 */
function cf7msm_is_tg_v2() {
    return ( version_compare( WPCF7_VERSION, '6.0' ) >= 0 );
}
