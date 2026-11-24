<?php
/**
 * Deactivation feedback: reason collection + payload to ReDi API.
 * Install identity lives in ReDiInstall (functions/install.php).
 *
 * Flow: popup (templates/popup.php) posts the form to the WordPress deactivate URL,
 * WP fires our deactivation hook, ReDiDeactivationFeedback::handle() builds the payload
 * and sends it (non-blocking) to the ReDi API.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('REDI_API_FEEDBACK')) {
    define('REDI_API_FEEDBACK', 'Feedback.svc/');
}

if (!class_exists('ReDiDeactivationFeedback')) {
    class ReDiDeactivationFeedback
    {
        const MAX_TEXT = 2000;

        /**
         * Reason code => label.
         */
        public static function reasons()
        {
            return array(
                'not_working'     => __('The plugin does not work on my site', 'redi-restaurant-reservation'),
                'cant_configure'  => __('I could not set it up the way I need', 'redi-restaurant-reservation'),
                'missing_feature' => __('A feature I need is missing', 'redi-restaurant-reservation'),
                'too_expensive'   => __('It is too expensive / I do not want to pay', 'redi-restaurant-reservation'),
                'found_better'    => __('I found a better plugin or service', 'redi-restaurant-reservation'),
                'design_outdated' => __('The design looks outdated', 'redi-restaurant-reservation'),
                'temporary'       => __("It's a temporary deactivation", 'redi-restaurant-reservation'),
                'closed_business' => __('The restaurant closed / I no longer need online booking', 'redi-restaurant-reservation'),
                'other'           => __('Other', 'redi-restaurant-reservation'),
            );
        }

        /** Reasons where we offer to contact the user. */
        public static function contact_reasons()
        {
            return array('not_working', 'cant_configure', 'missing_feature', 'too_expensive', 'other');
        }

        public static function competitors()
        {
            return array(
                'Five Star Restaurant Reservations', 'GloriaFood', 'WPCafe', 'VikRestaurants',
                'Quick Restaurant Reservations', 'Alex Reservations', 'Guestplan',
                'OpenTable', 'TheFork', 'Resy / SevenRooms', 'Google Reserve',
            );
        }

        public static function setup_steps()
        {
            return array(
                'account'       => __('Creating the account / plugin key', 'redi-restaurant-reservation'),
                'hours'         => __('Opening hours and time slots', 'redi-restaurant-reservation'),
                'capacity'      => __('Capacity, seats or tables', 'redi-restaurant-reservation'),
                'emails'        => __('Email notifications', 'redi-restaurant-reservation'),
                'form_design'   => __('Booking form design or language', 'redi-restaurant-reservation'),
                'page'          => __('Adding the booking form to a page', 'redi-restaurant-reservation'),
                'other'         => __('Something else', 'redi-restaurant-reservation'),
            );
        }

        public static function features()
        {
            return array(
                'payments'      => __('Online payments / deposits', 'redi-restaurant-reservation'),
                'sms'           => __('SMS reminders', 'redi-restaurant-reservation'),
                'floor_plan'    => __('Floor plan / table map', 'redi-restaurant-reservation'),
                'google'        => __('Bookings from Google (Reserve with Google)', 'redi-restaurant-reservation'),
                'multi_location'=> __('Several restaurants / locations', 'redi-restaurant-reservation'),
                'integration'   => __('Integration with POS or another system', 'redi-restaurant-reservation'),
                'waitlist'      => __('Waitlist', 'redi-restaurant-reservation'),
                'other'         => __('Something else', 'redi-restaurant-reservation'),
            );
        }

        private static function text($post, $field)
        {
            $key = 'redi_' . $field;
            if (!isset($post[$key]) || is_array($post[$key])) {
                return '';
            }
            $value = trim(sanitize_textarea_field(wp_unslash($post[$key])));
            return substr($value, 0, self::MAX_TEXT);
        }

        private static function iso($timestamp)
        {
            return $timestamp ? gmdate('c', (int)$timestamp) : null;
        }

        /**
         * Called from the plugin deactivation hook with $_POST.
         */
        public static function handle($post, $api_key, $plugin_version)
        {
            if (!is_array($post) || empty($post['redi_reason']) || !current_user_can('activate_plugins')) {
                return;
            }

            $reasons = self::reasons();
            $code = sanitize_key(wp_unslash($post['redi_reason']));
            if (!isset($reasons[$code])) {
                return;
            }

            $details = array();
            foreach (array('competitor', 'competitor_other', 'setup_step', 'feature', 'price', 'comment') as $field) {
                $value = self::text($post, $field);
                if ($value !== '') {
                    $details[$field] = $value;
                }
            }

            $contact_requested = !empty($post['redi_contact']) && in_array($code, self::contact_reasons(), true);
            $contact_email = $contact_requested && isset($post['redi_contact_email'])
                ? sanitize_email(wp_unslash($post['redi_contact_email'])) : '';
            if ($contact_email === '') {
                $contact_requested = false;
            }

            $install = ReDiInstall::ensure();
            $activated_at = !empty($install['activated_at']) ? (int)$install['activated_at'] : null;

            $payload = array(
                'event'                 => 'plugin_deactivated',
                'install_id'            => $install['id'],
                'api_key'               => !empty($api_key) ? (string)$api_key : null,
                'reason'                => $code,
                'reason_label'          => $reasons[$code],
                'details'               => (object)$details,
                'contact_requested'     => $contact_requested,
                'contact_email'         => $contact_requested ? $contact_email : null,
                'activated_at'          => self::iso($activated_at),
                'first_activated_at'    => self::iso(isset($install['first_activated_at']) ? $install['first_activated_at'] : null),
                'days_since_activation' => $activated_at ? (int)floor((time() - $activated_at) / DAY_IN_SECONDS) : null,
                'plugin_version'        => (string)$plugin_version,
                'locale'                => get_locale(),
                'sent_at'               => gmdate('c'),
                'technical'             => !empty($post['redi_include_tech']) ? self::technical() : null,
            );

            self::send_to_api($payload);
        }

        private static function technical()
        {
            $plugins = array();
            foreach ((array)get_option('active_plugins', array()) as $plugin_file) {
                $slug = dirname($plugin_file);
                $plugins[] = ($slug === '.' || $slug === '') ? basename($plugin_file, '.php') : $slug;
            }
            $theme = wp_get_theme();

            return array(
                'site_url'       => home_url(),
                'wp_version'     => get_bloginfo('version'),
                'php_version'    => PHP_VERSION,
                'multisite'      => is_multisite(),
                'theme'          => $theme ? trim($theme->get('Name') . ' ' . $theme->get('Version')) : null,
                'active_plugins' => array_values(array_unique($plugins)),
            );
        }

        private static function send_to_api($payload)
        {
            wp_remote_post(REDI_RESTAURANT_API . REDI_API_FEEDBACK . 'plugin/deactivation', array(
                'headers'  => array('Content-Type' => 'application/json; charset=utf-8'),
                'body'     => wp_json_encode($payload),
                'timeout'  => 5,
                'blocking' => false,
            ));
        }
    }
}
