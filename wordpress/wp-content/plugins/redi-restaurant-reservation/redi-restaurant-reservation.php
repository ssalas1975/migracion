<?php
/*
    Plugin Name: ReDi Restaurant Reservation
    Plugin URI: https://landing.reservationdiary.eu
    Description: Get your restaurant business booming with the <strong>ReDi Restaurant Reservation plugin</strong>! Our plugin simplifies the reservation process and allows your clients to easily book online. With <strong>instant reservation confirmation</strong> and customizable settings, managing reservations has never been easier. Say goodbye to the hassle of manually confirming reservations and hello to the convenience of automatic confirmation. Try it out today and see the difference it can make in just a few clicks!
    Version: 26.1.0
    Author: Reservation Diary
    Author URI: https://landing.reservationdiary.eu
    License: GPLv3 & Proprietary License
    Text Domain: redi-restaurant-reservation
    Domain Path: /languages
 */
if (!defined('REDI_RESTAURANT_PLUGIN_URL')) {
    define('REDI_RESTAURANT_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('REDI_RESTAURANT_TEMPLATE')) {
    define('REDI_RESTAURANT_TEMPLATE', plugin_dir_path(__FILE__) . 'templates' . DIRECTORY_SEPARATOR);
}

if (!defined('REDI_PLUGIN_NAME')) {
    define( 'REDI_PLUGIN_NAME', plugin_basename( __FILE__ ) );
}

global $wp_filesystem;

require_once('redi.php');
require_once('redi-restaurant-reservation-db.php');
require_once('redi-restaurant-reservation-date-format.php');
require_once('functions/email.php');
require_once('functions/time.php');
require_once('functions/notice.php');
require_once('functions/feedback.php');
require_once('redi-restaurant-reservation-api.php');
require_once( ABSPATH . 'wp-admin/includes/file.php' );

if (!class_exists('ReDiRestaurantReservation')) {
    if (!class_exists('Report')) {
        class Report
        {
            const Full = 'Full';
            const None = 'None';
            const Single = 'Single';
        }
    }
    if (!class_exists('ReDiSendEmailFromOptions')) {
        class ReDiSendEmailFromOptions
        {
            const ReDi = 'ReDi';
            const CustomSMTP = 'CustomSMTP';
            const WordPress = 'WordPress';
            const Disabled = 'Disabled';
        }
    }

    if (!class_exists('ReDiTheme'))
    {
        class ReDiTheme
        {
            const Default_v1 = 'Default_v1';
            const Design25_v2 = 'Design25_v2';
            const DesignR25_v3 = 'DesignR25_v3';
        }
    }

    if (!class_exists('EmailContentType')) {
        class EmailContentType
        {
            const Canceled = 'Canceled';
            const Confirmed = 'Confirmed';
        }
    }
    if (!class_exists('AlternativeTime')) {
        class AlternativeTime
        {
            const AlternativeTimeBlocks = 1;
            const AlternativeTimeByDay = 3;
        }
    }
    if (!class_exists('CustomFieldsSaveTo')) {
        class CustomFieldsSaveTo
        {
            const WPOptions = 'options';
            const API = 'api';
        }
    }

    class ReDiRestaurantReservation
    {
        use ReDiAPIHelperMethods;

        private $redi_notice;
        public $version = '26.1.0';
        /**
         * @var string The options string name for this plugin
         */
        private $optionsName = 'wp_redi_restaurant_options';
        private $apiKeyOptionName = 'wp_redi_restaurant_options_ApiKey';
        private static $name = 'REDI_RESTAURANT';
        private $options = [];
        public $ApiKey;
        private $redi;
        private $emailContent;
        private $weekday = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        private $page_title;
        private $content;
        private $page_name;
        private $page_id;
        private $redi_lang_path;

        public $table_name = 'redi_restaurant_reservation_v6';
        public $_name;

        function filter_timeout_time()
        {
            return 60; //new number of seconds default 5
        }

        public function __construct()
        {
            $this->_name = self::$name;
            
            //Initialize the options
            $this->get_options();

            $this->ApiKey = isset($this->options['ID']) ? $this->options['ID'] : null;

            $this->redi = new Redi($this->ApiKey);
            //Actions
            add_action('init', array($this, 'init_sessions'));

            $this->redi_notice = new RediNotice();
            $this->redi_notice->show_notices($this);

            add_action('admin_menu', array($this, 'redi_restaurant_admin_menu_link_new'));
            add_action('admin_menu', array($this, 'remove_admin_submenu_items'));

            $this->page_title = 'Reservation';
            $this->content = '[redirestaurant]';
            $this->page_name = $this->_name;
            $this->page_id = '0';

            register_activation_hook(__FILE__, array($this, 'activate'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate'));

            register_uninstall_hook(__FILE__, array('ReDiRestaurantReservation', 'uninstall')); // static

            add_action('admin_enqueue_scripts', array($this, 'admin_setting_design'));

            add_action('wp_ajax_nopriv_redi_restaurant-submit', array($this, 'redi_restaurant_ajax'));
            add_action('wp_ajax_redi_restaurant-submit', array($this, 'redi_restaurant_ajax'));            

            // Page Creation Ajax
            add_action('wp_ajax_nopriv_redi_restaurant-page_create', array($this, 'redi_restaurant_ajax'));
            add_action('wp_ajax_redi_restaurant-page_create', array($this, 'redi_restaurant_ajax'));

            add_action('wp_ajax_nopriv_redi_waitlist-submit', array($this, 'redi_restaurant_ajax'));
            add_action('wp_ajax_redi_waitlist-submit', array($this, 'redi_restaurant_ajax'));

            add_action('wp_ajax_nopriv_redi_userfeedback_submit', array($this, 'redi_userfeedback_submit'));
            add_action('wp_ajax_redi_userfeedback_submit', array($this, 'redi_userfeedback_submit'));

            add_filter('http_request_timeout', array($this, 'filter_timeout_time'));
            add_filter('http_request_args', array($this, 'my_http_request_args'), 100, 1);
            add_shortcode('redirestaurant', array($this, 'shortcode'));

            add_action('redi-reservation-send-confirmation-email', array($this, 'send_confirmation_email'));
            add_action('redi-reservation-email-content', array($this, 'redi_reservation_email_content'));
            add_action('redi-reservation-send-confirmation-email-other', array($this, 'send_confirmation_email'));
            do_action('redi-reservation-after-init');

            add_action('rest_api_init', array($this, 'register_redi_api'));

      		// Add links to plugin listing
    		add_filter('plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2);

            // Disable automatic update
            add_filter('pre_set_site_transient_update_plugin', array( $this, 'filter_plugin_updates'), 10, 2);

            ReDiRestaurantReservationDb::CreateCustomDatabase($this->table_name);

            // Add script enqueue action
            add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        }

        /**
         * Enqueue frontend scripts and styles
         */
        public function enqueue_frontend_scripts() {
            wp_enqueue_script('redi-custom-fields', 
                REDI_RESTAURANT_PLUGIN_URL . 'js/redi-custom-fields.js',
                array('jquery'),
                $this->version
            );

            if ($this->GetOption('EnableAttributionTracking', 0)) {
                wp_enqueue_script(
                    'redi-attribution',
                    REDI_RESTAURANT_PLUGIN_URL . 'js/redi-attribution.js',
                    array(),
                    $this->version,
                    true
                );
            }
        }

        function filter_plugin_updates( $value ) {
            unset( $value->response['redi-restaurant-reservation/redi-restaurant-reservation.php'] );
            return $value;
        }

        public function register_redi_api() {
            // Existing places endpoint
            register_rest_route('redi-restaurant-api/v1', '/places', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_all_places' ),
                'permission_callback' => '__return_true'
            ));

            // New custom fields endpoint with nested resource pattern
            register_rest_route('redi-restaurant-api/v1', '/places/(?P<place_id>\d+)/custom-fields', array(
                'methods' => 'GET',
                'callback' => function($request) {
                    $place_id = $request['place_id'];
                    return $this->get_custom_fields($place_id, self::lang());
                },
                'permission_callback' => '__return_true',
                'args' => array(
                    'place_id' => array(
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    )
                )
            ));

            register_rest_route('redi-restaurant-api/v1', '/places/(?P<place_id>\d+)/menu', array(
                'methods' => 'GET',
                'callback' => function($request) {
                    $place_id = $request['place_id'];
                    $time = $request['time'];
                    $shiftName = $request['shiftName'];
                    return $this->get_menu($place_id, $time, $shiftName, self::lang());
                },
                'permission_callback' => '__return_true',
                'args' => array(
                    'place_id' => array(
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    )
                )
            ));

            register_rest_route('redi-restaurant-api/v1', '/places/(?P<place_id>\d+)/day-availability', array(
                'methods' => 'GET',
                'callback' => function($request) {
                    $place_id = $request['place_id'];
                    $start_time = $request['from'];
                    $end_time = $request['to'];
                    $guests = $request['guests'];       
                    return $this->get_days_availability($place_id, $start_time, $end_time, $guests);
                },
                'permission_callback' => '__return_true',
                'args' => array(
                    'place_id' => array(
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    )
                )
            ));

            register_rest_route('redi-restaurant-api/v1', '/places/(?P<place_id>\d+)/day/(?P<day>\d{4}-\d{2}-\d{2})/time-availability', array(
                'methods' => 'GET',
                'callback' => function($request) {
                    $place_id = $request['place_id'];
                    $day = $request['day'];
                    $duration = $request['duration'];
                    $guests = $request['guests'];
            
                    return $this->get_time_availability($place_id, $day, $duration, $guests, ReDiTime::getAlternativeTimeStep($this->options, $guests));
                },
                'permission_callback' => '__return_true',
                'args' => array(
                    'place_id' => array(
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    ),
                    'day' => array(
                        'validate_callback' => function($param) {
                            // Handle null values to prevent PHP 8.1+ deprecation warnings
                            return $param !== null && preg_match('/^\d{4}-\d{2}-\d{2}$/', $param);
                        }
                    )
                )
            ));

            register_rest_route('redi-restaurant-api/v1', '/places/(?P<place_id>\d+)/reservations', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    
                    $place_id = $request['place_id'];
                    $reservation = json_decode($request->get_body(), true);

                    return $this->create_reservation($place_id, $reservation);
                },
                'args' => array(
                    'place_id' => array(
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    ),
                ),
                'permission_callback' => '__return_true',
            ));

            // Search reservations endpoint
            register_rest_route('redi-restaurant-api/v1', '/reservations/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => function($request) {
                    $reservation_id = $request['id'];
                    $personal_info = $request->get_param('personalInformation');
                    $lang = $request->get_param('lang');

                    if (empty($personal_info)) {
                        return new WP_Error(
                            'redi_missing_personal_info',
                            __('Personal information (email, phone, or name) is required to search for a reservation.', 'redi-restaurant-reservation'),
                            array('status' => 400)
                        );
                    }
                    
                    return $this->find_reservation($reservation_id, $personal_info, $lang);
                },
                'args' => array(
                    'id' => array(
                        'required' => true,
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    ),
                    'personalInformation' => array(
                        'required' => true,
                        'validate_callback' => function($param) {
                            return !empty($param);
                        }
                    ),
                    'lang' => array(
                        'required' => false,
                        'default' => ''
                    )
                ),'permission_callback' => '__return_true',
            ));

            register_rest_route('redi-restaurant-api/v1', '/reservations/(?P<id>\d+)', array(
                'methods' => 'DELETE',
                'callback' => function($request) {
                    $reservation_id = $request['id'];
                    $body = json_decode($request->get_body(), true);

                    $status = isset($body['status']) ? $body['status'] : '';
                    $reason = isset($body['cancellation_reason']) ? $body['cancellation_reason'] : '';
                    $personal_info = isset($body['personal_information']) ? $body['personal_information'] : '';
                    $lang = isset($body['lang']) ? $body['lang'] : '';

                    // Validate required fields from body
                    if (strtolower($status) !== 'cancelled' || empty($personal_info)) {
                        return new WP_Error(
                            'redi_missing_params',
                            __('Status must be "cancelled", and personal_information and cancellation_reason are required.', 'redi-restaurant-reservation'),
                            array('status' => 400)
                        );
                    }

                    return $this->cancel_reservation($reservation_id, $personal_info, $reason, $lang);
                },
                'args' => array(
                    'id' => array(
                        'required' => true,
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    // Validation for these fields is handled in the callback since data may come in the request body.
                    )
                ),
                'permission_callback' => '__return_true',
            ));

            // Update reservation endpoint (PATCH)
            register_rest_route('redi-restaurant-api/v1', '/reservations/(?P<id>\d+)', array(
                'methods' => 'PATCH',
                'callback' => function($request) {
                    $reservation_id = $request['id'];
                    $body = $request->get_json_params();
                    if (empty($body)) {
                        $body = json_decode($request->get_body(), true);
                    }
                    if (!is_array($body)) {
                        $body = array();
                    }
                    return $this->update_reservation($reservation_id, $body);
                },
                'args' => array(
                    'id' => array(
                        'required' => true,
                        'validate_callback' => function($param) {
                            return !empty($param) && is_numeric($param);
                        }
                    ),
                ),
                'permission_callback' => '__return_true',
            ));
        }


        public function plugin_action_links( $links, $plugin ) {
        
            if ( $plugin == REDI_PLUGIN_NAME )
            {    
                array_unshift( $links, '<a class="redi-plugin-page-upgrade-link" href="' . $this->redi->getBasicPackageSettingsUrl(self::lang()) . '" title="' . __( 'Try Premium', 'redi-restaurant-reservation' ) . '" target="_blank">' . __( 'Try Premium', 'redi-restaurant-reservation' ) . '</a>' );
                
                $wizard_completed = get_option($this->_name . '_page_skip') == true || !empty(get_option($this->_name . '_page_title'));
                
                if ($wizard_completed)
                {
                    $settings_page = 'redi-restaurant-reservation-settings';
                }
                else{
                    $settings_page = 'redi-restaurant-reservation-reservations';
                }

                $settings_url = get_admin_url( null, 'admin.php?page=' . $settings_page);
                array_unshift( $links, '<a href="' . $settings_url . '" title="' . __( 'Settings', 'redi-restaurant-reservation' ) . '">' . __( 'Settings', 'redi-restaurant-reservation' ) . '</a>' );
                $links['help'] = '<a target ="_blank" href="https://redi.atlassian.net/wiki/spaces/REDIPLUGINDOCS/overview" title="' . __( 'View the help documentation for ReDi Restaurant Reservations plugin', 'redi-restaurant-reservation' ) . '">' . __( 'Help', 'redi-restaurant-reservation' ) . '</a>';
	    	}
    
            return $links;
        }
    

        function admin_setting_design(){
            wp_enqueue_style('redi-reservation-setting-css',REDI_RESTAURANT_PLUGIN_URL . 'css/redi-reservation-settings.css');
            wp_enqueue_style('redi-reservation-popup',REDI_RESTAURANT_PLUGIN_URL . 'css/popup.css');
            wp_enqueue_script('redi-reservation-admin', REDI_RESTAURANT_PLUGIN_URL . '/js/redi-admin.js', array(), '1.0.0', true);
            wp_enqueue_style('redi-restaurant-admin', REDI_RESTAURANT_PLUGIN_URL . '/css/redi-admin.css');
        }

        function my_http_request_args($r)
        {
            $r['timeout'] = 60; # new timeout
            return $r;
        }

        function redi_reservation_email_content($args)
        {
            $this->emailContent = $this->redi->getEmailContent(
                $args['id'],
                EmailContentType::Confirmed,
                array(
                    'Lang' => $args['lang']
                )
            );
        }

        function send_confirmation_email()
        {
            Email::send_email($this->options, $this->emailContent, ReDiSendEmailFromOptions::WordPress);
        }


        function ReDiRestaurantReservation()
        {
            $this->__construct();
        }

        function plugin_get_version()
        {
            $plugin_data = get_plugin_data(__FILE__);
            $plugin_version = $plugin_data['Version'];

            return $plugin_version . '-' . $this->GetOption('Theme', ReDiTheme::Default_v1);
        }

        /**
         * Retrieves the plugin options from the database.
         * @return array
         */
        function get_options()
        {
            // Disable texturization filter before loading options to preserve exact quotes
            remove_filter('option_' . $this->optionsName, 'wptexturize');
            if (!$options = get_option($this->optionsName)) {
                $options = []; // Default values
                update_option($this->optionsName, $options);
            }
            $this->options = $options;
        }
        
        private function register($email,$consentContact)
        {
            $new_account = array(); 
            $new_account = $this->redi->createUser(array('Email' => $email, 'Source' => 'WordPress', 'ConsentToContact' => $consentContact));

            $name = get_bloginfo('name');//get from site name;

            if (empty($name)) {
                $name = "Restaurant name";
            }

            if (isset($new_account['ID']) && !empty($new_account['ID'])) {
                
                if (!isset($this->options) || empty($this->options)) {
                    $this->options = array(); 
                }

                $this->ApiKey = $this->options['ID'] = $new_account['ID'];
                $this->redi->setApiKey($this->options['ID']);
                $place = $this->redi->createPlace(array(
                    'place' => array(
                        'Name' => $name,
                        'City' => 'city',
                        'Country' => self::get_country_by_ip(), 
                        'Address' => 'Address line 1',
                        'Email' => $email,
                        'EmailCC' => '',
                        'Phone' => '[areacode] [number]',
                        'WebAddress' => get_option('siteurl'),
                        'Lang' => self::lang(),
                        'ReservationDuration' => 30, // min
                        'MinTimeBeforeReservation' => 24 // hours
                    )
                ));


                if (isset($place['Error'])) {
                    return $place;
                }
                

                $placeID = (int)$place['ID'];

                $category = $this->redi->createCategory($placeID,
                    array('category' => array('Name' => 'Restaurant')));

                if (isset($category['Error'])) {
                    return $category;
                }

                $categoryID = (int)$category['ID'];
                $service = $this->redi->createService($categoryID,
                    array('service' => array('Name' => 'Person', 'Quantity' => 10)));

                if (isset($service['Error'])) {
                    return $service;
                }

                foreach ($this->weekday as $value) {
                    $times[$value] = array('OpenTime' => '12:00', 'CloseTime' => '00:00');
                }
                $this->redi->setServiceTime($categoryID, $times);

                $this->options['newInstallation'] = 'yes';
                $this->options['Theme'] = ReDiTheme::DesignR25_v3;

                $this->saveAdminOptions();
            }

            return $new_account;
        }

        /**
         * Saves the admin options to the database.
         *
         * @return bool True if the option was successfully updated, false otherwise.
         */
        function saveAdminOptions()
        {
            // Temporarily disable texturization to preserve exact quote characters
            remove_filter('option_' . $this->optionsName, 'wptexturize');
            $updated = update_option($this->optionsName, $this->options);
            // Always flush API transients so saves refresh cached availability/places even when option blob is unchanged.
            $this->delete_all_redi_restaurant_cache();

            return $updated;
        }

        /**
         * Deletes all plugin API transients (options row + external object cache when applicable).
         */
        public function delete_all_redi_restaurant_cache() {
            global $wpdb;

            $prefix = $wpdb->esc_like( '_transient_redi_restaurant_' );
            $option_names = $wpdb->get_col(
                $wpdb->prepare(
                    "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
                    $prefix . '%'
                )
            );

            foreach ( (array) $option_names as $option_name ) {
                if ( ! is_string( $option_name ) || $option_name === '' ) {
                    continue;
                }
                $transient = str_replace( '_transient_', '', $option_name );
                if ( strpos( $transient, 'redi_restaurant_' ) === 0 ) {
                    delete_transient( $transient );
                }
            }

            // Orphan timeouts (e.g. incomplete writes) — safe cleanup
            $timeout_like = $wpdb->esc_like( '_transient_timeout_redi_restaurant_' );
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                    $timeout_like . '%'
                )
            );
        }

        function display_errors($errors, $admin = false, $action = '')
        {
            if (isset($errors['Error']) && is_array($errors)) {
                foreach ((array)$errors['Error'] as $error) {
                    echo '<div class="error redi-reservation-alert-error redi-reservation-alert"><p>' . $error . '</p></div>';
                }
            }
            //WP-errors
            if (isset($errors['Wp-Error'])) {

                foreach ((array)$errors['Wp-Error'] as $error_key => $error) {
                    foreach ((array)$error as $err) {
                        if ($admin) {
                            echo '<div class="error"><p>' . $error_key . ' : ' . $err . '</p></div>';
                        }
                    }
                }
            }
            if (isset($errors['updated'])){
                foreach ((array)$errors['updated'] as $error) {
                    echo ' <div class="updated notice"><p>' . $error . '</p></div>';
                }
            }
        }

        function redi_restaurant_admin_upcoming()
        {
            ?><script>window.location.assign("<?php echo $this->redi->getWaiterDashboardUrl(self::lang()) ?>");</script><?php
        }

        function redi_restaurant_basic_package_settings()
        {
            ?><script>window.location.assign("<?php echo $this->redi->getBasicPackageSettingsUrl(self::lang()) ?>");</script><?php
        }

        function redi_restaurant_admin_test()
        {
            global $wpdb;
            $red_results = $wpdb->get_results("SELECT ID, post_title, guid FROM ".$wpdb->posts." WHERE post_content LIKE '%[redirestaurant]%' AND post_status = 'publish'");

            if(empty($red_results)){
                require_once(REDI_RESTAURANT_TEMPLATE . 'admin_welcome_no_page.php');
            }else{
                ?><script>window.location.assign("<?php echo esc_url(get_the_permalink( $red_results[0]->ID )); ?>");</script><?php
            }
        }

        function redi_restaurant_admin_reservations()
        {
            ?><script>window.location.assign("<?php echo $this->redi->getReservationUrl(self::lang())?>");</script><?php
        }

        function redi_restaurant_admin_welcome()
        {   
            require_once(REDI_RESTAURANT_TEMPLATE . 'admin_welcome.php');
        }
        
        function admin_welcome_no_key()
        {   
            require_once(REDI_RESTAURANT_TEMPLATE . 'admin_welcome_no_key.php');
        }        
        function admin_welcome_no_page()
        {   
            require_once(REDI_RESTAURANT_TEMPLATE . 'admin_welcome_no_page.php');
        }

        private function shouldSendEmail()
        {
            return isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress;
        }

        public function processConfirmation($reservation, $placeID)
        {
            if ($this->shouldSendEmail()) 
            {
                //call api for content

                $place = $this->redi->getPlace($placeID);
                do_action('redi-reservation-email-content', array(
                    'id' => (int)$reservation['ID'],
                    'lang' => str_replace('_', '-', self::GetPost('lang'))
                ));

                // Send email
                Email::send_email($this->options, $this->emailContent , ReDiSendEmailFromOptions::WordPress);
                //send
            }
        }

        public function processCancellation($cancel, &$errors, &$cancel_success) {
            if ($this->shouldSendEmail() &&
                !isset($cancel['Error'])
            ) {
                // Call API for content
                $emailContent = $this->redi->getEmailContent(
                    (int)$cancel['ID'],
                    EmailContentType::Canceled,
                    array(
                        'Lang' => str_replace('_', '-', self::GetPost('lang'))
                    )
                );
    
                // Send email
                Email::send_email($this->options, $emailContent, ReDiSendEmailFromOptions::WordPress);
            }
    
            if (isset($cancel['Error'])) {
                $errors["Error"] = $cancel['Error'];
            } else {
                $cancel_success = __('Reservation has been successfully canceled.', 'redi-restaurant-reservation');
            }
        }

        /**
         * Adds settings/options page
         */
        function redi_restaurant_admin_options_page()
        {
            if (isset($_POST['action']) && (!isset($_POST['redi_plugin_nonce']) || !wp_verify_nonce($_POST['redi_plugin_nonce'], 'redi_restaurant_ajax')))
            {
                $errors[] = __('Form security check failed with error: Nonce check failed.', 'redi-restaurant-reservation');

                require_once(REDI_RESTAURANT_TEMPLATE . 'admin.php');
                require_once(REDI_RESTAURANT_TEMPLATE . 'basicpackage.php');

                return;
            }
            
            if (isset($_POST['action']) && $_POST['action'] == 'cancel') {
                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $params = array(
                        'ID' => urlencode(self::GetPost('id')),
                        'Lang' => self::lang(),
                        'Reason' => urlencode(mb_substr(self::GetPost('Reason', ''), 0, 250)),
                        'CurrentTime' => urlencode(gmdate('Y-m-d H:i', current_time('timestamp'))),
                        'Version' => urlencode(self::plugin_get_version())
                    );

                    if (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::Disabled ||
                        isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress
                    ) {
                        $params['DontNotifyClient'] = 'true';
                    }
                    
                    $cancel = $this->redi->cancelReservation($params);
                    
                    $errors = array();
                    $cancel_success = "";
                    
                    $this->processCancellation($cancel, $errors, $cancel_success);

                } else {
                    $errors['Error'] = __('Reservation number is required', 'redi-restaurant-reservation');
                }

                require_once(REDI_RESTAURANT_TEMPLATE . 'admin.php');
                require_once(REDI_RESTAURANT_TEMPLATE . 'basicpackage.php');

                return;
            }

            if (isset($_POST['action']) && isset($_POST['new_key']))
            {
                $newKey = sanitize_text_field(wp_unslash($_POST['new_key']));
                $errors = array();
                
                if (!empty($newKey)) 
                {
                    if (preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i", $newKey) == 1) {
                        if ($this->ApiKey != $newKey) {
                            $this->redi->setApiKey($newKey);
                            $this->ApiKey = $this->options['ID'] = $newKey;
                            $this->saveAdminOptions();
                            $error['updated'] = __('API key is successfully changed.', 'redi-restaurant-reservation');
                            $this->display_errors($error, true, 'ApiKey update');
                        }
                    } else {

                        $error['Error'] = __('Not a valid API key provided.', 'redi-restaurant-reservation');
                        $this->display_errors($error, true, 'No ApiKey');
                    }
                }
            }

            if ($this->ApiKey == null) {

                $errors['Error'] = array(
                    __('ReDi Restaurant Reservation plugin could not get an API key from the reservationdiary.eu server when it activated.
                        <br/> You can try to fix this by going to the ReDi Restaurant Reservation "options" page.
                        <br/>This will cause ReDi Restaurant Reservation plugin to retry fetching an API key for you.
                        <br/>If you keep seeing this error it usually means that server where you host your web site can\'t connect to our reservationdiary.eu server.
                        <br/>You can try asking your WordPress host to allow your WordPress server to connect to api.reservationdiary.eu
                        <br/>In case you can not solve this problem yourself, please contact us directly by <a href="mailto:info@reservationdiary.eu">info@reservationdiary.eu</a>',
                        'redi-restaurant-reservation')
                );
                $this->display_errors($errors, true, 'Failed to register');
                die;
            }
            $places = $this->redi->getPlaces();

            if (isset($places['Error'])) {
                $this->display_errors($places, true, 'getPlaces');
                die;
            }
            $placeID = $places[0]->ID;

            $categories = $this->redi->getPlaceCategories($placeID);

            $categoryID = $categories[0]->ID;


            $settings_saved = false;
            
            if (isset($_POST['action']) && isset($_POST['submit'])) {
                $form_valid = true;
                $services = (int)self::GetPost('services');
                $MaxGuestsPerMonth = (int)self::GetPost('MaxGuestsPerMonth');
                $minPersons = (int)self::GetPost('MinPersons');
                $maxPersons = (int)self::GetPost('MaxPersons');
                $ShowComment = self::GetPost('ShowComment');
                $MaxChild = self::GetPost('MaxChild');
                $largeGroupsMessage = self::GetPostTextarea('LargeGroupsMessage');
                $emailFrom = self::GetPost('EmailFrom');
                $theme = self::GetPost('RediTheme');
                $email = self::GetPost('Email');
                $restro_name = self::GetPost('Name');
                $report = self::GetPost('Report', Report::Full);
                $thanks = self::GetPost('Thanks', 0);
                $timepicker = self::GetPost('TimePicker');
                $alternativeTimeStep = self::GetPost('AlternativeTimeStep', 30);
                $MinTimeBeforeReservation = self::GetPost('MinTimeBeforeReservation');
                $MinTimeBeforeReservationType = self::GetPost('MinTimeBeforeReservationType');
                $waitlist = self::GetPost('WaitList');
                $confirmationPage = self::GetPost('ConfirmationPage');
                $dateFormat = self::GetPost('DateFormat');
                $calendar = self::GetPost('Calendar');
                $hidesteps = self::GetPost('Hidesteps');
                $enablefirstlastname = self::GetPost('EnableFirstLastName');
                $endreservationtime = self::GetPost('EndReservationTime');
                $countrycode = self::GetPost('CountryCode', false);
                $timeshiftmode = self::GetPost('TimeShiftMode');
                $manualReservation = self::GetPost('ManualReservation', 0);             
                $displayLeftSeats = self::GetPost('DisplayLeftSeats', 0);
                $EnableCancelForm = self::GetPost('EnableCancelForm', 0);
                $EnableModifyReservations = self::GetPost('EnableModifyReservations', 0);
                $userfeedback = self::GetPost('userfeedback', false);
                $EnableSocialLogin = self::GetPost('EnableSocialLogin', 0);
                $fullyBookedMessage = self::GetPostTextarea('FullyBookedMessage');
                $captcha = self::GetPost('Captcha', 0);
                $childrenSelection = self::GetPost('ChildrenSelection', 0);
                $totalSelection = self::GetPost('TotalSelection', 0);

                $childrenDescription = self::GetPostTextarea('ChildrenDescription');
                $captchaKey = self::GetPost('CaptchaKey');
                $mandatoryCancellationReason = self::GetPost('MandatoryCancellationReason', 0);
                $lang_path = self::GetPost('lang_path');

                $sgtmEndpoint = self::GetPost('SgtmEndpoint', '');
                $ga4MeasurementId = self::GetPost('GA4MeasurementId', '');
                $ga4ApiSecret = self::GetPost('GA4ApiSecret', '');
                $enableAttributionTracking = self::GetPost('EnableAttributionTracking', 0);

                $SubscriptionName = self::GetPost('SubscriptionName');
                $SubscriptionEmail = self::GetPost('SubscriptionEmail');
                $IsSubscription = self::GetPost('IsSubscription');

                $country = self::GetPost('Country');
                if ($country == '' || empty($country)) {
                    $country = self::get_country_by_ip();
                }

                // Validation
                if ($minPersons > $maxPersons) {
                    $errors[] = '<a href="#MinPersons">' . __(' "Min Persons" should be lower than Max Persons', 'redi-restaurant-reservation') . '</a>';
                    $form_valid = false;
                }

                $reservationTime = (int) self::GetPost('ReservationTime');
                if ($reservationTime <= 0) {
                    $errors[] = '<a href="#ReservationTime">' . __('"Reservation time" should be greater than 0', 'redi-restaurant-reservation') . '</a>';
                    $form_valid = false;
                }

                if ($MaxGuestsPerMonth == '0' || empty($MaxGuestsPerMonth)) {
                    $errors[] = '<a href="#MaxGuestsPerMonth">' . __('Please select value from "Monthly guest reservation limit"', 'redi-restaurant-reservation') . '</a>';
                    $form_valid = false;
                }

                if ($childrenSelection == '1' && ($MaxChild == '0' || empty($MaxChild))) {
                    $errors[] = '<a href="#MaxChild">' . __('Please select value from "Max children per reservation"', 'redi-restaurant-reservation') . '</a>';
                    $form_valid = false;
                }

                if ($IsSubscription != false) {
                    if (empty($SubscriptionEmail)) {
                        $errors[] = '<a href="#SubscriptionEmail">' . __('"Subscription Email" Field should be filled.', 'redi-restaurant-reservation') . '</a>';
                        $form_valid = false;
                    }

                    if (empty($SubscriptionName)) {
                        $errors[] = '<a href="#SubscriptionName">' . __('"Subscription Name" Field should be filled.', 'redi-restaurant-reservation') . '</a>';
                        $form_valid = false;
                    }
                }

                if (empty(self::GetPost('Name'))) {
                    $errors[] = '<a href="#Name">' . __('"Restaurant Name" is required', 'redi-restaurant-reservation') . '</a>';
                    $form_valid = false;
                }
                
                if (empty($country)) {
                    $errors[] = '<a href="#Country">' . __('"Country" is required', 'redi-restaurant-reservation') . '</a>';
                    $form_valid = false;
                }
                
                $serviceTimes = self::GetServiceTimes();
                
                if (is_array($serviceTimes) && count($serviceTimes) == 0) {
                    $errors[] = '<a href="#service_time_table">' . __('At least one opening time should be provided', 'redi-restaurant-reservation') . '</a>';
                    $form_valid = false;
                }

                $place = array(
                    'place' => array(
                        'Name' => self::GetPost('Name'),
                        'City' => self::GetPost('City'),
                        'Country' => $country,
                        'Address' => self::GetPost('Address'),
                        'Email' => self::GetPost('Email'),
                        'EmailCC' => self::GetPost('EmailCC'),
                        'Phone' => self::GetPost('Phone'),
                        'WebAddress' => self::GetPost('WebAddress'),
                        'Lang' => self::GetPost('Lang'),
                        'DescriptionShort' => self::GetPost('DescriptionShort'),
                        'DescriptionFull' => self::GetPost('DescriptionFull'),
                        'MaxGuestsPerMonth' => (int)self::GetPost('MaxGuestsPerMonth'), 
                        'MinTimeBeforeReservation' => self::GetPost('MinTimeBeforeReservation'),
                        'MinTimeBeforeReservationType' => self::GetPost('MinTimeBeforeReservationType'),
                        'Catalog' => (int)self::GetPost('Catalog'),
                        'DateFormat' => self::GetPost('DateFormat'),
                        'MaxTimeBeforeReservation' => self::GetPost('MaxTime'),
                        'MaxTimeBeforeReservationType' => self::GetPost('MaxTimeBeforeReservationType'),
                        'ReservationDuration' => $reservationTime,
                        'Version' => self::plugin_get_version(),
                        'ContactPersonName' => $SubscriptionName,
                        'ContactPersonEmail' => $SubscriptionEmail,
                        'ContactConsent' => $IsSubscription
                    )
                );

                $placeID = self::GetPost('Place');
                
                for ($i = 0; $i != REDI_MAX_CUSTOM_FIELDS; $i++) {
                    $field_id = 'field_' . $i . '_id';
                    $field_name = 'field_' . $i . '_name';
                    $field_text = 'field_' . $i . '_text';
                    $field_values = 'field_' . $i . '_values';
                    $field_type = 'field_' . $i . '_type';
                    $field_required = 'field_' . $i . '_required';
                    $field_print = 'field_' . $i . '_print';
                    $field_message = 'field_' . $i . '_message';

                    $$field_id = self::GetPost($field_id);

                    $$field_name = self::GetPost($field_name);
                    
                    // Get field type first to determine how to handle the text field
                    $$field_type = self::GetPost($field_type);
                    
                    // For GDPR fields, allow safe HTML (links, formatting) but prevent scripts
                    if ($$field_type === 'gdpr') {
                        $raw_text = wp_unslash($_POST[$field_text] ?? '');
                        // Allow safe HTML tags: a, strong, em, b, i, u, p, br, span
                        // But prevent scripts, iframes, and other dangerous tags
                        $allowed_html = array(
                            'a' => array(
                                'href' => array(),
                                'title' => array(),
                                'target' => array(),
                            ),
                            'strong' => array(),
                            'em' => array(),
                            'b' => array(),
                            'i' => array(),
                            'u' => array(),
                            'p' => array(),
                            'br' => array(),
                            'span' => array(),
                        );
                        $$field_text = wp_kses($raw_text, $allowed_html);
                    } else {
                        // For other field types, use existing behavior (strip HTML)
                        $$field_text = htmlentities(self::GetPost($field_text), ENT_QUOTES);
                    }

                    $$field_print = (self::GetPost($field_print) === 'on');
                    $$field_required = (self::GetPost($field_required) === 'on');
                    $$field_values = self::GetPost($field_values);

                    $$field_message = self::GetPost($field_message);

                    if (empty($$field_name) && isset($$field_id) && $$field_id > 0) { //name is empty so delete this field
                        $this->redi->deleteCustomField(self::lang(), $placeID, $$field_id);
                    } else {
                        //new or update
                        if (isset($$field_id) && $$field_id > 0) {
                            $this->redi->updateCustomField(self::lang(), $placeID, $$field_id, array(
                                'customfield' => array(
                                    'Name' => $$field_name,
                                    'Text' => $$field_text,
                                    'Values' => $$field_values,
                                    'Message' => $$field_message,
                                    'Required' => $$field_required ? 'true' : 'false',
                                    'Print' => $$field_print ? 'true' : 'false',
                                    'Type' => $$field_type
                                )
                            ));
                        } else {
                            $this->redi->saveCustomField(self::lang(), $placeID, array(
                                'customfield' => array(
                                    'Name' => $$field_name,
                                    'Text' => $$field_text,
                                    'Values' => $$field_values,
                                    'Message' => $$field_message,
                                    'Required' => $$field_required ? 'true' : 'false',
                                    'Print' => $$field_print ? 'true' : 'false',
                                    'Type' => $$field_type
                                )
                            ));
                        }
                    }
                }

                if ($form_valid) {
                    $settings_saved = true;

                    add_option($this->_name . '_settings_saved', true);

                    $this->options['WaitList'] = $waitlist;
                    $this->options['Thanks'] = $thanks;
                    $this->options['TimePicker'] = $timepicker;
                    $this->options['AlternativeTimeStep'] = $alternativeTimeStep;
                    $this->options['ConfirmationPage'] = $confirmationPage;
                    $this->options['services'] = $services;
                    $this->options['MaxGuestsPerMonth'] = $MaxGuestsPerMonth;
                    $this->options['MinTimeBeforeReservation'] = $MinTimeBeforeReservation;
                    $this->options['MinTimeBeforeReservationType'] = $MinTimeBeforeReservationType;
                    $this->options['DateFormat'] = $dateFormat;
                    $this->options['Hidesteps'] = $hidesteps;
                    $this->options['EnableFirstLastName'] = $enablefirstlastname;
                    $this->options['EndReservationTime'] = $endreservationtime;
                    $this->options['CountryCode'] = $countrycode;
                    $this->options['MinPersons'] = $minPersons;
                    $this->options['MaxPersons'] = $maxPersons;
                    if(isset($ShowComment) && $ShowComment != ""){
                        $this->options['ShowComment'] = $ShowComment;
                    } else{
                        $this->options['ShowComment'] = 0;
                    }
                    $this->options['MaxChild'] = $MaxChild;
                    $this->options['LargeGroupsMessage'] = $largeGroupsMessage;
                    $this->options['EmailFrom'] = $emailFrom;
                    $this->options['Email'] = $email;
                    $this->options['Name'] = $restro_name;
                    $this->options['Report'] = $report;
                    $this->options['Calendar'] = $calendar;
                    $this->options['TimeShiftMode'] = $timeshiftmode;
                    $this->options['ManualReservation'] = $manualReservation;
                    $this->options['DisplayLeftSeats'] = $displayLeftSeats;
                    $this->options['EnableCancelForm'] = $EnableCancelForm;
                    $this->options['EnableModifyReservations'] = $EnableModifyReservations;
                    $this->options['userfeedback'] = $userfeedback;
                    $this->options['EnableSocialLogin'] = $EnableSocialLogin;                   
                    $this->options['FullyBookedMessage'] = $fullyBookedMessage;
                    $this->options['Captcha'] = $captcha;
                    $this->options['ChildrenSelection'] = $childrenSelection;
                    $this->options['TotalSelection'] = $totalSelection; 
                    $this->options['ChildrenDescription'] = $childrenDescription;
                    $this->options['CaptchaKey'] = $captchaKey;
                    $this->options['MandatoryCancellationReason'] = $mandatoryCancellationReason;
                    $this->options['Theme'] = $theme;

                    $this->options['SgtmEndpoint'] = sanitize_url($sgtmEndpoint);
                    $this->options['GA4MeasurementId'] = sanitize_text_field($ga4MeasurementId);
                    $this->options['GA4ApiSecret'] = sanitize_text_field($ga4ApiSecret);
                    $this->options['EnableAttributionTracking'] = !empty($enableAttributionTracking) ? 1 : 0;

                    $placeID = self::GetPost('Place');
                    $categories = $this->redi->getPlaceCategories($placeID);
                    if (isset($categories['Error'])) {
                        $errors[] = $categories['Error'];
                        $settings_saved = false;
                    }
                    $categoryID = $categories[0]->ID;
                    $this->options['OpenTime'] = self::GetPost('OpenTime');
                    $this->options['CloseTime'] = self::GetPost('CloseTime');

                    $getServices = $this->redi->getServices($categoryID);
                    if (isset($getServices['Error'])) {
                        $errors[] = $getServices['Error'];
                        $settings_saved = false;
                    }
                    if (count($getServices) != $services) {
                        if (count($getServices) > $services) {
                            //delete
                            $diff = count($getServices) - $services;

                            $cancel = $this->redi->deleteServices($categoryID, $diff);
                            if (isset($cancel['Error'])) {
                                $errors[] = $cancel['Error'];
                                $settings_saved = false;
                            }
                            $cancel = array();
                        } else {
                            //add
                            $diff = $services - count($getServices);

                            $cancel = $this->redi->createService($categoryID,
                                array(
                                    'service' => array(
                                        'Name' => 'Person',
                                        'Quantity' => $diff
                                    )
                                ));
                            if (isset($cancel['Error'])) {
                                $errors[] = $cancel['Error'];
                                $settings_saved = false;
                            }
                            $cancel = array();
                        }
                    }

                    $this->saveAdminOptions();

                    if (is_array($serviceTimes) && count($serviceTimes)) {
                        $cancel = $this->redi->setServiceTime($categoryID, $serviceTimes);
                        if (isset($cancel['Error'])) {
                            $errors[] = $cancel['Error'];
                            $settings_saved = false;
                        }
                        $cancel = array();
                    }
                    $cancel = $this->redi->setPlace($placeID, $place);
                    if (isset($cancel['Error'])) {
                        $errors[] = $cancel['Error'];
                        $settings_saved = false;
                    }
                    $cancel = array();
                }

                $places = $this->redi->getPlaces();
                if (isset($places['Error'])) {
                    $errors[] = $places['Error'];
                    $settings_saved = false;
                }
            }

            // Disable texturization filter before loading options to preserve exact quotes
            remove_filter('option_' . $this->optionsName, 'wptexturize');
            $this->options = get_option($this->optionsName);

            if ($settings_saved || !isset($_POST['submit'])) {
                $thanks = $this->GetOption('Thanks', 0);
                $calendar = $this->GetOption('Calendar', 'show');
                $hidesteps = $this->GetOption('Hidesteps', 0);
                $enablefirstlastname = $this->GetOption('EnableFirstLastName', 'false');
                $endreservationtime = $this->GetOption('EndReservationTime', 'false');
                $countrycode = $this->GetOption('CountryCode', 'true');
                $userfeedback = $this->GetOption('userfeedback', 'true');
                $timeshiftmode = $this->GetOption('TimeShiftMode', 'byshifts');
                $timepicker = $this->GetOption('TimePicker');
                $confirmationPage = $this->GetOption('ConfirmationPage');
                $manualReservation = $this->GetOption('ManualReservation', 0);
                $displayLeftSeats = $this->GetOption( 'DisplayLeftSeats', 0);
                $EnableCancelForm = $this->GetOption('EnableCancelForm', 0);
                $EnableModifyReservations = $this->GetOption('EnableModifyReservations', 0);
                $EnableSocialLogin = $this->GetOption('EnableSocialLogin', 0);
                $fullyBookedMessage = $this->GetOption('FullyBookedMessage', '');
                $captcha = $this->GetOption('Captcha', 0);
                $childrenSelection = $this->GetOption('ChildrenSelection', 0);
                $totalSelection = $this->GetOption('TotalSelection', 0);
                $childrenDescription = $this->GetOption('ChildrenDescription', '');
                $captchaKey = $this->GetOption('CaptchaKey', '');
                $mandatoryCancellationReason = $this->GetOption('MandatoryCancellationReason', 1);
                $lang_path = $this->GetOption('lang_path', '');
                $MaxGuestsPerMonth = $this->GetOption('MaxGuestsPerMonth');
                $waitlist = $this->GetOption('WaitList', 0);

                $minPersons = $this->GetOption('MinPersons', 1);
                $maxPersons = $this->GetOption('MaxPersons', 10);
                $ShowComment = $this->GetOption('ShowComment');
                $MaxChild = $this->GetOption('MaxChild');
                
                $alternativeTimeStep = $this->GetOption('AlternativeTimeStep', 30);
                $largeGroupsMessage = $this->GetOption('LargeGroupsMessage', '');
                $emailFrom = $this->GetOption('EmailFrom', ReDiSendEmailFromOptions::ReDi);
                $theme = $this->GetOption('Theme', ReDiTheme::Default_v1);
                $email = $this->GetOption('Email','');
                $restro_name = $this->GetOption('Name','');
                $report = $this->GetOption('Report', Report::Full);

                $sgtmEndpoint = $this->GetOption('SgtmEndpoint', '');
                $ga4MeasurementId = $this->GetOption('GA4MeasurementId', '');
                $ga4ApiSecret = $this->GetOption('GA4ApiSecret', '');
                $enableAttributionTracking = $this->GetOption('EnableAttributionTracking', 0);

                $getServices = $this->redi->getServices($categoryID);
                if (isset($getServices['Error'])) {
                    $errors[] = $getServices['Error'];
                }

                $custom_fields = $this->redi->getCustomField(self::lang(), $placeID);
                add_option($this->_name . '_settings_saved', true);
            }

            if (!$settings_saved && isset($_POST['submit'])) {
                $timepicker = self::GetPost('TimePicker');
                $alternativeTimeStep = self::GetPost('AlternativeTimeStep');
            }

            $place = $places[0];

            // If installtaion is new then hide Timeshift & Timepicker settings
            $newInstallation =  $this->GetOption('newInstallation', 'no');

            require_once(REDI_RESTAURANT_TEMPLATE . 'admin.php');
            require_once(REDI_RESTAURANT_TEMPLATE . 'basicpackage.php');
        }

        private function GetOption($name, $default = null)
        {
            // when shorcode has attribute, and it's passed to shortcode function, it chanmges it's case to lowercase
            // but in database settings are in CamelCase
            // First take lowercase version of the options, then try to get original name, and finally return default value
            $value = isset($this->options[strtolower($name)]) ? $this->options[strtolower($name)]
                : (isset($this->options[$name]) ? $this->options[$name]
                : $default);
            
            return $value;
        }

        private static function GetPost($name, $default = null)
        {
            if (!isset($_POST[$name])) {
                return $default;
            }
            $value = wp_unslash($_POST[$name]);
            // Strip HTML tags but preserve exact text including quotes
            // Don't use sanitize_text_field as it may transform quotes
            return wp_strip_all_tags($value);
        }

        private static function GetPostTextarea($name, $default = null)
        {
            if (!isset($_POST[$name])) {
                return $default;
            }
            $value = wp_unslash($_POST[$name]);
            // For textarea, preserve exact text content including quotes
            // Strip only script/style tags and their content for security, but preserve quotes
            $value = preg_replace('/<(script|style)[^>]*>.*?<\/\1>/is', '', $value);
            $value = strip_tags($value);
            return trim($value);
        }

        function GetServiceTimes()
        {
            $serviceTimes = array();
            foreach ($_POST['OpenTime'] as $key => $value) {
                if (self::set_and_not_empty($value)) {
                    $serviceTimes[$key]['OpenTime'] = wp_strip_all_tags(wp_unslash($value));
                }
            }
            foreach ($_POST['CloseTime'] as $key => $value) {
                if (self::set_and_not_empty($value)) {
                    $serviceTimes[$key]['CloseTime'] = wp_strip_all_tags(wp_unslash($value));
                }
            }

            return $serviceTimes;
        }

        private function get_country_by_ip() {
            $ip = $_SERVER['REMOTE_ADDR'];
            if ($ip) {

                $details = json_decode(wp_remote_retrieve_body(wp_remote_get("http://ipinfo.io/{$ip}/json")));

                if ( $details ) {
                    $countries = json_decode(wp_remote_retrieve_body(wp_remote_get("http://country.io/names.json")));
                    if ( $countries ) {
                        if (isset($details->country)) {
                            $countryAbbr = $details->country;
                            if (isset($countries->$countryAbbr)) {
                                $countryName = $countries->$countryAbbr;
                                return $countryName;
                            } else {
                                return 'United States';
                            }
                        } else {
                            return 'United States';
                        }
                    } else {
                        return 'United States';
                    }
                } else {
                    return 'United States';
                }
            } else {
                return 'United States';
            }
        }

        function ajaxed_admin_page($placeID, $categoryID, $settings_saved = false)
        {
            require_once(plugin_dir_path(__FILE__) . 'languages.php');
            $places = $this->redi->getPlaces();
            $getServices = $this->redi->getServices($categoryID);
            $apiKey = isset($this->options['ID']) ? $this->options['ID'] : null;

            if (!isset($_POST['submit']) || $settings_saved) {

                $serviceTimes = $this->redi->getServiceTime($categoryID); //goes to template 'admin'
                $serviceTimes = json_decode(wp_json_encode($serviceTimes), true);
                $place = $this->redi->getPlace($placeID); //goes to template 'admin'

            } else {
                $country = self::GetPost('Country');
                if ($country == '' || empty($country)) {
                    $country = self::get_country_by_ip();
                }
                $place = array(
                    'Name' => self::GetPost('Name'),
                    'City' => self::GetPost('City'),
                    'Country' => $country,
                    'Address' => self::GetPost('Address'),
                    'Email' => self::GetPost('Email'),
                    'EmailCC' => self::GetPost('EmailCC'),
                    'Phone' => self::GetPost('Phone'),
                    'WebAddress' => self::GetPost('WebAddress'),
                    'Lang' => self::GetPost('Lang'),
                    'DescriptionShort' => self::GetPost('DescriptionShort'),
                    'DescriptionFull' => self::GetPost('DescriptionFull'),
                    'MaxGuestsPerMonth' => (int)self::GetPost('MaxGuestsPerMonth'),
                    'MinTimeBeforeReservation' => self::GetPost('MinTimeBeforeReservation'), 
                    'MinTimeBeforeReservationType' => self::GetPost('MinTimeBeforeReservationType'),
                    'MaxTimeBeforeReservation' => self::GetPost('MaxTime'),
                    'MaxTimeBeforeReservationType' => self::GetPost('MaxTimeBeforeReservationType'),
                    'ReservationDuration' => 30,
                    'Catalog' => (int)self::GetPost('Catalog'),
                    'DateFormat' => self::GetPost('DateFormat'),
                    'ContactPersonName' => self::GetPost('SubscriptionName'),
                    'ContactPersonEmail' => self::GetPost('SubscriptionEmail'),
                    'ContactConsent' => self::GetPost('IsSubscription')
                );
                $serviceTimes = self::GetServiceTimes();
            }

            // If installtaion is new then hide Timeshift & Timepicker settings
            $newInstallation =  $this->GetOption('newInstallation', 'no');
            $sgtmEndpoint = $this->GetOption('SgtmEndpoint', '');
            $ga4MeasurementId = $this->GetOption('GA4MeasurementId', '');
            $ga4ApiSecret = $this->GetOption('GA4ApiSecret', '');
            $enableAttributionTracking = $this->GetOption('EnableAttributionTracking', 0);
            require_once('countrylist.php');
            require_once(REDI_RESTAURANT_TEMPLATE . 'admin_ajaxed.php');
        }

        function init_sessions()
        {
            self::load_plugin_translations();
        }

        function load_plugin_translations() {
            // Define the text domain and the path to the plugin's languages directory
            $textdomain = 'redi-restaurant-reservation';
            $plugin_path = dirname(plugin_basename(__FILE__)) . '/languages/';
        
            // Get the current locale (e.g., fr_CA, de_AT, etc.)
            $locale = get_locale();
        
            // Check if the locale contains a region (e.g., fr_CA, de_AT)
            if (strpos($locale, '_') !== false) {
            // Split the locale into language and region parts
            list($language, $region) = explode('_', $locale);
        
            // Check if there's a specific translation for the full locale (e.g., fr_CA, de_AT)
            $locale_file = $plugin_path . 'redi-restaurant-reservation-' . $locale . '.mo';
        
            // If the specific locale file doesn't exist, fallback to the base language (e.g., fr_FR, de_DE)
            if (!file_exists(WP_PLUGIN_DIR . '/' . $locale_file)) {
                // Fallback to the base language locale (e.g., fr_CA -> fr_FR, de_AT -> de_DE)
                $locale_file = $plugin_path . 'redi-restaurant-reservation-' . $language . '_' . strtoupper($language) . '.mo';
            }
            } else {
            // If no region is specified, just use the locale as it is
                $locale_file = $plugin_path . 'redi-restaurant-reservation-' . $locale . '.mo';
            }
        
            // Load the plugin translations from the plugin's languages directory
            load_plugin_textdomain($textdomain, false, $plugin_path);

            // Load the translation file if it exists
            if (file_exists(WP_PLUGIN_DIR . '/' . $locale_file)) {
                load_textdomain($textdomain, WP_PLUGIN_DIR . '/' . $locale_file);
            }
        }

        function redi_restaurant_admin_menu_link_new()
        {
            $icon = 'dashicons-groups';
            $exclamation = ' <span class="redi-menu-warning">!</span>';

            if (!current_user_can('manage_options'))
            {
                return;
            }

            if ($this->ApiKey && ( get_option($this->_name . '_page_skip') == true || !empty(get_option($this->_name . '_page_title')) )) {
                global $wpdb;
                
                $query = "SELECT * FROM {$wpdb->prefix}{$this->table_name} LIMIT 1";
                $reservation_results = $wpdb->get_results($query);

                add_menu_page(
                    __('ReDi Reservations', 'redi-restaurant-reservation'),
                    __( 'ReDi Reservations', 'redi-restaurant-reservation') . (get_option($this->_name . '_settings_saved') == false || empty($reservation_results) ? $exclamation : ''),
                    'edit_posts',
                    'redi-restaurant-reservation-reservations',
                    array(&$this, 'redi_restaurant_admin_welcome'),
                    $icon,
                    20);
                    
                add_submenu_page(
                    'redi-restaurant-reservation-reservations',
                    __('Welcome', 'redi-restaurant-reservation'),
                    __('Welcome', 'redi-restaurant-reservation'),
                    'edit_posts',
                    'redi_restaurant_welcome_reservations',
                    array(&$this, 'redi_restaurant_admin_welcome'));    
                        
                add_submenu_page(
                    'redi-restaurant-reservation-reservations',
                    __('Settings', 'redi-restaurant-reservation'),
                    __('Settings', 'redi-restaurant-reservation') . (get_option($this->_name . '_settings_saved') == false ? $exclamation : ''),
                    'edit_posts',
                    'redi-restaurant-reservation-settings',
                    array(&$this, 'redi_restaurant_admin_options_page'));

                add_submenu_page(
                    'redi-restaurant-reservation-reservations',
                    __('Settings $', 'redi-restaurant-reservation'),
                    __('Settings $', 'redi-restaurant-reservation'),
                    'edit_posts',
                    'redi_restaurant_basic_package_settings',
                    array(&$this, 'redi_restaurant_basic_package_settings'));
                add_submenu_page(
                    'redi-restaurant-reservation-reservations',
                    __('Test reservation', 'redi-restaurant-reservation'),
                    __('Test reservation', 'redi-restaurant-reservation') . (get_option($this->_name . '_settings_saved') == true && empty($reservation_results) ? $exclamation : ''),
                    'edit_posts',
                    'redi-restaurant-reservation-test',
                    array(&$this, 'redi_restaurant_admin_test'));
                add_submenu_page(
                    'redi-restaurant-reservation-reservations',
                    __('Reservations', 'redi-restaurant-reservation'),
                    __('Reservations', 'redi-restaurant-reservation'),
                    'edit_posts',
                    'redi_restaurant_admin_reservations',
                    array(&$this, 'redi_restaurant_admin_reservations'));
    
                add_submenu_page(
                    'redi-restaurant-reservation-reservations',
                    __('Waiter Dashboard', 'redi-restaurant-reservation'),
                    __('Waiter Dashboard', 'redi-restaurant-reservation'),
                    'edit_posts',
                    'redi_restaurant_admin_upcoming',
                    array(&$this, 'redi_restaurant_admin_upcoming'));
            }
            elseif( !empty( $this->ApiKey ) && empty(get_option($this->_name . '_page_title')) && ( get_option($this->_name . '_page_skip') != true ) && ( !isset($_GET['api_key']) && !isset($_GET['email_skip'])  )){
                add_menu_page(
                    __('ReDi Reservations', 'redi-restaurant-reservation'),
                    __( 'ReDi Reservations', 'redi-restaurant-reservation') . $exclamation,
                    'edit_posts',
                    'redi-restaurant-reservation-reservations',
                    array(&$this, 'admin_welcome_no_page'),
                    $icon,
                    20);
            } else {
                add_menu_page(
                    __('ReDi Reservations', 'redi-restaurant-reservation'),
                    __( 'ReDi Reservations', 'redi-restaurant-reservation') . $exclamation,
                    'edit_posts',
                    'redi-restaurant-reservation-reservations',
                    array(&$this, 'admin_welcome_no_key'),
                    $icon,
                    20);
            }
        }

        function remove_admin_submenu_items() {
            remove_submenu_page('redi-restaurant-reservation-reservations', 'redi-restaurant-reservation-reservations');
        }

        static function install()
        {
        }

        public function activate()
        {
			if ($this->ApiKey == null)
			{
                $admin_email = get_option('admin_email');
                self::register($admin_email,true);
            }
        }

        private static function set_and_not_empty($value)
        {
            return (isset($value) && !empty($value));
        }

        public function deactivate()
        {
           
            if( isset($_POST) && !empty($_POST['selected-reason'])){
                $this->redi_popup($_POST);
            }
        }
        /*
        * Plugin deactivate user feedback review backend
        */
        public function redi_popup($data){
            ReDiFeedback::feedback_on_deactivate($data);
        }

        public static function uninstall()
        {
            self::deletePage();
            self::deleteOptions();
        }

        public static function deletePage($hard = false)
        {
            $id = get_option(self::$name . '_page_id');
            
            if ($id) 
            {
                wp_delete_post($id, $hard);
            }
        }

        public static function deleteOptions()
        {
            delete_option(self::$name . '_page_title');
            delete_option(self::$name . '_page_name');
            delete_option(self::$name . '_page_id');
        }

        public function shortcode($attributes)
        {
            if (is_array($attributes) && is_array($this->options)) {
                $this->options = array_merge($this->options, $attributes);
            }

            ob_start();
            wp_enqueue_script('jquery');
            wp_enqueue_style('jquery_ui');
            wp_enqueue_script('moment');

            // Get query parameter language and set locale
            if (isset($_GET['lang']) && !empty($_GET['lang'])) {
                $locale = sanitize_text_field($_GET['lang']);
                switch_to_locale($locale);
            }

            $apiKeyId = (int)$this->GetOption('apikeyid');

            if ($apiKeyId) {
                $this->ApiKey = $this->GetOption('apikey' . $apiKeyId, $this->ApiKey);

                $check = get_option($this->apiKeyOptionName . $apiKeyId);
                if ($check != $this->ApiKey) { // update only if changed
                    //Save Key if newed
                    update_option($this->apiKeyOptionName . $apiKeyId, $this->ApiKey);
                }
                $this->redi->setApiKey($this->ApiKey);
            }
            if ($this->ApiKey == null) {
                $this->display_errors(array(
                    'Error' => __('Online reservation service is not available at this time. Try again later or', 'redi-restaurant-reservation') . ' ' .
                    '<a href="mailto:info@reservationdiary.eu;' . get_bloginfo('admin_email') . '?subject=Reservation form is not working&body=' . get_bloginfo().'">' .
                    __('contact us directly', 'redi-restaurant-reservation').'</a>',
                ), false, 'Frontend No ApiKey');

                return;
            }


            if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::Default_v1) 
            {
                    wp_register_style('jquery-ui-custom-style',
                    REDI_RESTAURANT_PLUGIN_URL . 'css/custom-theme/jquery-ui-1.8.18.custom.css');
                wp_enqueue_style('jquery-ui-custom-style');
                wp_enqueue_script('jquery-ui-datepicker');
                wp_register_script('datetimepicker',
                    REDI_RESTAURANT_PLUGIN_URL . 'lib/datetimepicker/js/jquery-ui-timepicker-addon.js',
                    array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker'));
                wp_enqueue_script('datetimepicker');

                wp_register_script('datetimepicker-lang',
                    REDI_RESTAURANT_PLUGIN_URL . 'lib/datetimepicker/js/jquery.ui.i18n.all.min.js');
                wp_enqueue_script('datetimepicker-lang');

                wp_register_script('timepicker-lang',
                    REDI_RESTAURANT_PLUGIN_URL . 'lib/timepicker/i18n/jquery-ui-timepicker.all.lang.js');
                wp_enqueue_script('timepicker-lang');

                wp_register_script('reginaltimepicker-lang',
                    REDI_RESTAURANT_PLUGIN_URL . 'lib/timepicker/i18n/jquery-ui-i18n.min.js');
                wp_enqueue_script('reginaltimepicker-lang');

                if ($this->GetOption('CountryCode', true) && null === $this->GetOption("skin"))
                {
                    wp_register_style('intl-tel-custom-style',
                        REDI_RESTAURANT_PLUGIN_URL . 'lib/intl-tel-input-16.0.0/build/css/intlTelInput.min.css');
                    wp_enqueue_style('intl-tel-custom-style');  
                    wp_register_script('intl-tel-input',
                        REDI_RESTAURANT_PLUGIN_URL . 'lib/intl-tel-input-16.0.0/build/js/utils.js');
                    wp_enqueue_script('intl-tel-input');
                    wp_register_script('intl-tel',
                        REDI_RESTAURANT_PLUGIN_URL . 'lib/intl-tel-input-16.0.0/build/js/intlTelInput.min.js');
                    wp_enqueue_script('intl-tel');
                }
            }
            // v2
            else if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::Design25_v2) {
                wp_register_style(
                    'intl-tel-input-v23-css',

                    REDI_RESTAURANT_PLUGIN_URL . 'lib/v2/intl-tel-input-23.0.0/css/intlTelInput.css'
                );

                wp_enqueue_style('intl-tel-input-v23-css');

                wp_register_script(
                    'intl-tel-input-v23',

                    REDI_RESTAURANT_PLUGIN_URL . 'lib/v2/intl-tel-input-23.0.0/js/intlTelInput.min.js'
                );

                wp_enqueue_script('intl-tel-input-v23');

                wp_register_style(
                    'nice-select2-css',

                    REDI_RESTAURANT_PLUGIN_URL . 'lib/v2/nice-select2/nice-select2.min.css'
                );

                wp_enqueue_style('nice-select2-css');

                wp_register_script(
                    'nice-select2',

                    REDI_RESTAURANT_PLUGIN_URL . 'lib/v2/nice-select2/nice-select2.js'
                );

                wp_enqueue_script('nice-select2');

                wp_register_style(
                    'vanilla-calendar-css',

                    REDI_RESTAURANT_PLUGIN_URL . 'lib/v2/vanilla-calendar/vanilla-calendar.layout.min.css'
                );

                wp_enqueue_style('vanilla-calendar-css');

                wp_register_script(
                    'vanilla-calendar',

                    REDI_RESTAURANT_PLUGIN_URL . 'lib/v2/vanilla-calendar/vanilla-calendar.min.js'
                );

                wp_enqueue_script('vanilla-calendar');
            }


            $stylefile = 'restaurant.css';
            
            if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::Design25_v2)
            {
                $stylefile = 'redi-style_v2.css';
            }

            if (file_exists(get_stylesheet_directory() . '/redi-restaurant-reservation/' . $stylefile))
            {
                wp_register_style('redi-restaurant', get_stylesheet_directory_uri() . '/redi-restaurant-reservation/' . $stylefile);
            }
            else
            {
                wp_register_style('redi-restaurant', REDI_RESTAURANT_PLUGIN_URL . 'css/' . $stylefile);
            }

            if ($this->GetOption("Captcha"))
            {
                wp_enqueue_script(
                    'google-recaptcha',
                    'https://www.google.com/recaptcha/api.js',
                    array(), // Dependencies
                    null, // Version number
                    true // Load in footer
                );
            }
            
            wp_enqueue_style('redi-restaurant');

            if (isset($_GET['jquery_fail']) && $_GET['jquery_fail'] === 'true') {
                $this->display_errors(array(
                    'Error' => __('Plugin failed to properly load javascript file, please check that jQuery is loaded and no javascript errors present.',
                        'redi-restaurant-reservation')
                ), false, 'Frontend No ApiKey');
            }

            if (isset($_GET['confirm']) && isset($_GET['id']) && isset($_GET['phone'])){

                $id = str_replace(' ', '', $_GET['id']);

                $reservation = $this->redi->findReservation( str_replace( '_', '-', self::lang()), $id);

                $reservationPhone = isset($reservation['Phone']) ? str_replace([' ', '+'], '', $reservation['Phone']) : '';
                $getParameterPhone = str_replace([' ', '+'], '', $_GET['phone']);

                if (isset($reservation['Error']) || $reservationPhone !== $getParameterPhone) {
                    $this->display_errors(array(
                        'Error' => __('Reservation is not found.',
                            'redi-restaurant-reservation')
                    ), false, 'Reservation not found.');
                    return;
                }

                wp_register_script('confirm-visit', REDI_RESTAURANT_PLUGIN_URL . 'js/redi-confirm-visit.js', array(
                    'jquery'),self::plugin_get_version(), true);
                wp_localize_script('confirm-visit',
                    'redi_restaurant_reservation',
                    array( 
                        'locale' => get_locale(),
                        'apikeyid' => $apiKeyId,
                        'ajaxurl' => admin_url('admin-ajax.php'),
                        'rest_url' => rest_url('redi-restaurant-api/v1/')
                    ));

                wp_enqueue_script('confirm-visit');
    
                require_once(REDI_RESTAURANT_TEMPLATE . 'confirm.php');
                return;
            }

            wp_register_script('restaurant', REDI_RESTAURANT_PLUGIN_URL . 'js/restaurant.js', array(
                'jquery',
                'jquery-ui-tooltip',
                'datetimepicker'
            ),self::plugin_get_version(), true);

            wp_register_script('restaurant_v2', REDI_RESTAURANT_PLUGIN_URL . 'js/restaurant_v2.js', array(
                'jquery'
            ),self::plugin_get_version(), true);



            //places
            $places = $this->redi->getPlaces();
            if (isset($places['Error'])) {
                $this->display_errors($places, false, 'getPlaces');
                return;
            }

            if (isset($this->options['placeid'])) {

                $ind = array_search($this->options['placeid'], array_column($places, 'ID'));
                $places = array($places[$ind]);
            }
            $placeID = $places[0]->ID;

            $categories = $this->redi->getPlaceCategories($placeID);
            if (isset($categories['Error'])) {
                $this->display_errors($categories, false, 'getPlaceCategories');
                return;
            }

            $categoryID = $categories[0]->ID;
            $time_format = get_option('time_format');

            $date_format_setting = $this->GetOption('DateFormat');
            $date_format = RediDateFormats::getPHPDateFormat($date_format_setting);
            $calendar_date_format = RediDateFormats::getCalendarDateFormat($date_format_setting);
            
            // TODO: Get time before reservation from place selected in shortcode in case of multiplace mode
            $MinTimeBeforeReservation = $places[0]->MinTimeBeforeReservation;

            $reservationStartTime = strtotime('+' . $MinTimeBeforeReservation . ' ' . $this->getPeriodFromType($places[0]->MinTimeBeforeReservationType),
                current_time('timestamp'));

            $MaxTimeBeforeReservation = $places[0]->MaxTimeBeforeReservation;

            $reservationMaxTime = strtotime('+' . $MaxTimeBeforeReservation . ' ' . $this->getPeriodFromTypeForMaxTime($places[0]->MaxTimeBeforeReservationType),
                current_time('timestamp'));
            
            $startDate = gmdate($date_format, $reservationStartTime);
            $startDateISO = gmdate('Y-m-d', $reservationStartTime);
            $startTime = mktime(gmdate('G', $reservationStartTime), 0, 0, 0, 0, 0);
            
            $minPersons = $this->GetOption('MinPersons', 1);
            $maxPersons = $this->GetOption('MaxPersons', 10);
            $largeGroupsMessage = $this->extractTranslatedContent($this->GetOption('LargeGroupsMessage', ''), self::lang());
            $emailFrom = $this->GetOption('EmailFrom', ReDiSendEmailFromOptions::ReDi);
            $theme = $this->GetOption('Theme', ReDiTheme::Default_v1);
            $report = $this->GetOption('Report', Report::Full);
            $thanks = $this->GetOption('Thanks');
            $manualReservation = $this->GetOption('ManualReservation');
            $displayLeftSeats = $this->GetOption( 'DisplayLeftSeats');
            $EnableCancelForm = $this->GetOption('EnableCancelForm', 0);
            $EnableModifyReservations = $this->GetOption('EnableModifyReservations', 0);
            $userfeedback = $this->GetOption('userfeedback', 'true');
            $ShowComment = $this->GetOption('ShowComment');
            $MaxChild = $this->GetOption('MaxChild');
            $EnableSocialLogin = $this->GetOption('EnableSocialLogin');
            $fullyBookedMessage = $this->extractTranslatedContent($this->GetOption('FullyBookedMessage', ''), self::lang());
            $captcha = $this->GetOption('Captcha');
            $childrenSelection = $this->GetOption('ChildrenSelection');
            $totalSelection =  $this->GetOption('TotalSelection'); 
            $childrenDescription = $this->extractTranslatedContent($this->GetOption('ChildrenDescription'), self::lang());
            $captchaKey = $this->GetOption('CaptchaKey', '');
            $mandatoryCancellationReason = $this->GetOption('MandatoryCancellationReason', 1);

            $waitlist = $this->GetOption('WaitList', 0);

            $timepicker = $this->GetOption('timepicker', $this->GetOption('TimePicker'));
            $time_format_hours = ReDiTime::dropdown_time_format();
            $calendar = $this->GetOption('calendar',
                $this->GetOption('Calendar', 'show')); // first admin settings then shortcode 

            $custom_fields = $this->redi->getCustomField(self::lang(), $placeID);

            $custom_duration = ReDiTime::loadCustomDurations();

            // todo: Reservation time
            if (!isset($custom_duration)) {
                $default_reservation_duration = $places[0]->ReservationDuration;
            } else {
                $default_reservation_duration = $custom_duration["durations"][0]["duration"];
            }

            $hide_clock = false;
            $persons = 0;
            $all_busy = false;
            $hidesteps = false; // this settings only for 'byshifts' mode
            $timeshiftmode = $this->GetOption('timeshiftmode', $this->GetOption('TimeShiftMode', 'byshifts'));
            if ($timeshiftmode === 'byshifts') {
                $hidesteps = $this->GetOption('hidesteps',
                $this->GetOption('Hidesteps')) == '1'; // first admin settings then shortcode

                $hide_clock = true;
            }


            if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::Default_v1) {
                wp_localize_script('restaurant',
                'redi_restaurant_reservation',
                array( // URL to wp-admin/admin-ajax.php to process the request
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'id_missing' => __('Reservation number can\'t be empty', 'redi-restaurant-reservation'),
                    'name_missing' => __('Name can\'t be empty', 'redi-restaurant-reservation'),
                    'fname_missing' => __('First Name can\'t be empty', 'redi-restaurant-reservation'),
                    'lname_missing' => __('Last Name can\'t be empty', 'redi-restaurant-reservation'),
                    'personalInf' => __('Name or Phone or Email can\'t be empty', 'redi-restaurant-reservation'),
                    'email_missing' => __('Email can\'t be empty', 'redi-restaurant-reservation'),
                    'phone_missing' => __('Phone can\'t be empty', 'redi-restaurant-reservation'),
                    'phone_not_valid' => __('Phone number is not valid', 'redi-restaurant-reservation'),
                    'reason_missing' => __('Reason can\'t be empty', 'redi-restaurant-reservation'),
                    'next' => __('Next', 'redi-restaurant-reservation'),
                    'tooltip' => __('This time is fully booked', 'redi-restaurant-reservation'),
                    'error_fully_booked' => __('There are no more reservations can be made for selected day.', 'redi-restaurant-reservation'),
                    'time_not_valid' => __('Provided time is not in valid format.', 'redi-restaurant-reservation'),
                    'captcha_not_valid' => __('Captcha is not valid.', 'redi-restaurant-reservation'),
                    'available_seats' => __('Available seats', 'redi-restaurant-reservation'),
                    'unexpected_error' => __('Apologies for the inconvenience, but we encountered an issue while trying to make your reservation. It seems there was a technical glitch on our end. Our team is actively working to resolve this issue. Please try again in a few minutes, and if the problem persists, feel free to reach out to our customer support for assistance. Additionally, if you could please send a screenshot of the error message to {emailLink}, it would greatly help us in diagnosing and resolving the issue more efficiently. We appreciate your patience and cooperation. Response:', 'redi-restaurant-reservation'),
                    // data
                    'enablefirstlastname' => $this->GetOption('EnableFirstLastName'),
                    'endreservationtime' => $this->GetOption('EndReservationTime'),
                    'countrycode' => $this->GetOption('CountryCode', true),
                    'cancel_reason_mandatory' => $this->GetOption('MandatoryCancellationReason', true),
                    'display_time_shift' => $this->GetOption('timeshift'),
                    'locale' => get_locale(),
                    'apikeyid' => $apiKeyId,
                    'rest_url' => rest_url('redi-restaurant-api/v1/'),
                    'min_time_before_reservation' => gmdate('Y-m-d', $reservationStartTime),
                    'max_time_before_reservation' => gmdate('Y-m-d', $reservationMaxTime),
                    'errortotal' => __('Total guests must be greater than the number of children.', 'redi-restaurant-reservation'), 
                ));

                wp_enqueue_script('restaurant');            

            }
            else if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::Design25_v2) {

                    wp_localize_script(
                        'restaurant_v2',

                        'redi_restaurant_reservation',

                        array( // URL to wp-admin/admin-ajax.php to process the request

                            'ajaxurl' => admin_url('admin-ajax.php'),

                            'loading_dates' => __('Loading available dates', 'redi-restaurant-reservation'),

                            'loading_times' => __('Loading available time slots', 'redi-restaurant-reservation'),

                            'creating_reservation' => __('Creating reservation', 'redi-restaurant-reservation'),

                            'plugin_url' => REDI_RESTAURANT_PLUGIN_URL,

                            'id_missing' => __('Reservation number can\'t be empty', 'redi-restaurant-reservation'),

                            'name_missing' => __('Name can\'t be empty', 'redi-restaurant-reservation'),

                            'fname_missing' => __('First Name can\'t be empty', 'redi-restaurant-reservation'),

                            'lname_missing' => __('Last Name can\'t be empty', 'redi-restaurant-reservation'),

                            'personalInf' => __('Name or Phone or Email can\'t be empty', 'redi-restaurant-reservation'),

                            'email_missing' => __('Email can\'t be empty', 'redi-restaurant-reservation'),

                            'phone_missing' => __('Phone can\'t be empty', 'redi-restaurant-reservation'),

                            'phone_not_valid' => __('Phone number is not valid', 'redi-restaurant-reservation'),

                            'reason_missing' => __('Reason can\'t be empty', 'redi-restaurant-reservation'),

                            'next' => __('Next', 'redi-restaurant-reservation'),

                            'tooltip' => __('This time is fully booked', 'redi-restaurant-reservation'),

                            'error_fully_booked' => __('There are no more reservations can be made for selected day.', 'redi-restaurant-reservation'),

                            'time_not_valid' => __('Provided time is not in valid format.', 'redi-restaurant-reservation'),

                            'captcha_not_valid' => __('Captcha is not valid.', 'redi-restaurant-reservation'),

                            'available_seats' => __('Available seats', 'redi-restaurant-reservation'),

                            'unexpected_error' => __('Apologies for the inconvenience, but we encountered an issue while trying to make your reservation. It seems there was a technical glitch on our end. Our team is actively working to resolve this issue. Please try again in a few minutes, and if the problem persists, feel free to reach out to our customer support for assistance. Additionally, if you could please send a screenshot of the error message to {emailLink}, it would greatly help us in diagnosing and resolving the issue more efficiently. We appreciate your patience and cooperation. Response:', 'redi-restaurant-reservation'),

                            // data

                            'enablefirstlastname' => $this->GetOption('EnableFirstLastName'),

                            'endreservationtime' => $this->GetOption('EndReservationTime'),

                            'countrycode' => $this->GetOption('CountryCode', true),

                            'cancel_reason_mandatory' => $this->GetOption('MandatoryCancellationReason', true),

                            'locale' => get_locale(),

                            'apikeyid' => $apiKeyId

                        )
                    );
            
                    wp_enqueue_script('restaurant_v2');            
                }
            
            

            if (file_exists(plugin_dir_path(__FILE__) . 'js/maxpersonsoverride.js')) {
                wp_register_script('maxpersonsoverride', REDI_RESTAURANT_PLUGIN_URL . 'js/maxpersonsoverride.js', array(
                    'restaurant'
                ));
                wp_enqueue_script('maxpersonsoverride');
            }
            else if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::DesignR25_v3) {
                // Load React build files from the plugin folder
                wp_register_script(
                    'redi-react-app',
                    REDI_RESTAURANT_PLUGIN_URL . 'react-form/index.js',
                    array(), // No dependencies
                    '1.0',
                    true // Load in footer
                );

                wp_enqueue_style(
                    'redi-react-app-theme',
                    REDI_RESTAURANT_PLUGIN_URL . 'react-form/theme-variables.css',
                    array(),
                    '1.0'
                );

                wp_enqueue_style(
                    'redi-react-app-css',
                    REDI_RESTAURANT_PLUGIN_URL . 'react-form/index.css',
                    array(),
                    '1.0'
                );

        
                $translations = array(
                    // header
                    'powered_by' => __('Powered by', 'redi-restaurant-reservation'),
                    
                    // Navigation and Steps
                    'location' => __('Location', 'redi-restaurant-reservation'),
                    'adults' => __('Adults', 'redi-restaurant-reservation'),
                    'guests' => __('Guests', 'redi-restaurant-reservation'),
                    'children' => __('Children', 'redi-restaurant-reservation'),
                    'contact_info' => __('Contact', 'redi-restaurant-reservation'),
                    'confirmation' => __('Confirmation', 'redi-restaurant-reservation'),
                    'select_date' => __('Date', 'redi-restaurant-reservation'),
                    'select_time' => __('Time', 'redi-restaurant-reservation'),
                    'show_all_steps' => __('Show all steps', 'redi-restaurant-reservation'),
                    'hide_all_steps' => __('Hide all steps', 'redi-restaurant-reservation'),

                    // Contact Form Fields
                    'first_name' => __('First Name', 'redi-restaurant-reservation'),
                    'last_name' => __('Last Name', 'redi-restaurant-reservation'),
                    'full_name' => __('Full Name', 'redi-restaurant-reservation'),
                    'email' => __('Email', 'redi-restaurant-reservation'),
                    'phone' => __('Phone', 'redi-restaurant-reservation'),
                    'notes' => __('Special requests (optional)', 'redi-restaurant-reservation'),
                    'notes_placeholder' => __('Any special requests or notes', 'redi-restaurant-reservation'),
 
                    // Loading States
                    'creating_reservation' => __('Creating reservation', 'redi-restaurant-reservation'),
                    'loading_custom_fields' => __('Loading custom fields', 'redi-restaurant-reservation'),
                    'loading_dates' => __('Loading available dates', 'redi-restaurant-reservation'),
                    'loading_restaurants' => __('Loading restaurants', 'redi-restaurant-reservation'),
                    'loading_time_slots' => __('Loading available time slots', 'redi-restaurant-reservation'),
                    'loading_reservation_form' => __('Loading reservation form', 'redi-restaurant-reservation'),

                    // Calendar Interface
                    'day_sun' => __('Sun', 'redi-restaurant-reservation'),
                    'day_mon' => __('Mon', 'redi-restaurant-reservation'),
                    'day_tue' => __('Tue', 'redi-restaurant-reservation'),
                    'day_wed' => __('Wed', 'redi-restaurant-reservation'),
                    'day_thu' => __('Thu', 'redi-restaurant-reservation'),
                    'day_fri' => __('Fri', 'redi-restaurant-reservation'),
                    'day_sat' => __('Sat', 'redi-restaurant-reservation'),
                    'reservation_date' => __('Selected date', 'redi-restaurant-reservation'),
                    'next_month' => __('Next month', 'redi-restaurant-reservation'),
                    'previous_month' => __('Previous month', 'redi-restaurant-reservation'),
                    // Month Names (Date Formatting)
                    'month_january' => __('January', 'redi-restaurant-reservation'),
                    'month_february' => __('February', 'redi-restaurant-reservation'),
                    'month_march' => __('March', 'redi-restaurant-reservation'),
                    'month_april' => __('April', 'redi-restaurant-reservation'),
                    'month_may' => __('May', 'redi-restaurant-reservation'),
                    'month_june' => __('June', 'redi-restaurant-reservation'),
                    'month_july' => __('July', 'redi-restaurant-reservation'),
                    'month_august' => __('August', 'redi-restaurant-reservation'),
                    'month_september' => __('September', 'redi-restaurant-reservation'),
                    'month_october' => __('October', 'redi-restaurant-reservation'),
                    'month_november' => __('November', 'redi-restaurant-reservation'),
                    'month_december' => __('December', 'redi-restaurant-reservation'),

                    // Legend
                    'legend' => __('Legend', 'redi-restaurant-reservation'),
                    'outside_range' => __('Out of range', 'redi-restaurant-reservation'),
                    'today' => __('Today', 'redi-restaurant-reservation'),
                    'closed_day' => __('Closed', 'redi-restaurant-reservation'),
                    'fully_booked' => __('Fully booked', 'redi-restaurant-reservation'),

                    // tooltips
                    'before_min_time' => __('Reservations must be made at least ${timeText} in advance', 'redi-restaurant-reservation'),
                    'after_max_time' => __('Reservations can only be made up to ${timeText} in advance', 'redi-restaurant-reservation'),
                    'days' => __('days', 'redi-restaurant-reservation'),
                    'hours' => __('hours', 'redi-restaurant-reservation'),
                    'minutes' => __('minutes', 'redi-restaurant-reservation'),
                    'past_date_tooltip' => __('This date is in the past and cannot be selected', 'redi-restaurant-reservation'),
                    
                    // Custom Fields and Form Validation
                    'failed_to_load_custom_fields' => __('Failed to load custom fields. Please try again later.', 'redi-restaurant-reservation'),
                    'custom_fields_error' => __('Error loading custom fields', 'redi-restaurant-reservation'),
                    'select_option' => __('Select', 'redi-restaurant-reservation'),
                    'yes' => __('Yes', 'redi-restaurant-reservation'),
                    'no' => __('No', 'redi-restaurant-reservation'),
                    'field_required' => __('is required', 'redi-restaurant-reservation'),
                    'please_select_option' => __('Please select an option for', 'redi-restaurant-reservation'),
                    'additional_information' => __('Additional Information', 'redi-restaurant-reservation'),
                    'select_month' => __('Select month', 'redi-restaurant-reservation'),
                    'select_day' => __('Select day', 'redi-restaurant-reservation'),
                    'select_year' => __('Select year', 'redi-restaurant-reservation'),
                    'please_select_valid_date' => __('Please select a valid date for', 'redi-restaurant-reservation'),

                    // Confirmation
                    'look_forward_message' => __('We look forward to serving you!', 'redi-restaurant-reservation'),
                    'confirmation_email_note' => __('A confirmation email has been sent to you. If you don\'t receive it, please rest assured that your booking has been confirmed. You may contact the restaurant directly if you need to make any changes to your reservation.', 'redi-restaurant-reservation'),
                    'manual_confirmation_email_note' => __('A confirmation email will be sent to you once your reservation request has been approved. You may contact the restaurant directly if you need to make any changes to your reservation request.', 'redi-restaurant-reservation'),
                    'manual_reservation_notice' => __('Your reservation request has been received and will be reviewed by the restaurant staff. Please note that the reservation is not guaranteed until you receive confirmation.', 'redi-restaurant-reservation'),
                    'update_confirmation_email_note' => __('A confirmation email has been sent to you with your updated reservation details. You may contact the restaurant directly if you need further changes.', 'redi-restaurant-reservation'),
                    'request_id' => __('REQUEST ID', 'redi-restaurant-reservation'),
                    'reservation_id' => __('RESERVATION ID', 'redi-restaurant-reservation'),
                    'child_singular' => __('child', 'redi-restaurant-reservation'),
                    'child_plural' => __('children', 'redi-restaurant-reservation'),
                    'adult_singular' => __('adult', 'redi-restaurant-reservation'),
                    'adult_plural' => __('adults', 'redi-restaurant-reservation'),
                    'children_label' => __('Children', 'redi-restaurant-reservation'),
                    'adults_label' => __('Adults', 'redi-restaurant-reservation'),
                    'time_label' => __('Time', 'redi-restaurant-reservation'),
                    'date_label' => __('Date', 'redi-restaurant-reservation'),
                    'reservation_request_details' => __('Reservation Request Details', 'redi-restaurant-reservation'),
                    'reservation_details' => __('Reservation Details', 'redi-restaurant-reservation'),
                    'thank_you_request_message' => __('Thank you for your reservation request!', 'redi-restaurant-reservation'),
                    'thank_you_message' => __('Thank you for your reservation!', 'redi-restaurant-reservation'),
                    'loading_confirmation_details' => __('Loading confirmation details', 'redi-restaurant-reservation'),

                    // Manage reservations
                    'manage_reservation' => __('Manage Existing Reservation', 'redi-restaurant-reservation'),
                    'cancel_reservation' => __('Cancel Reservation', 'redi-restaurant-reservation'),
                    'modify_reservation' => __('Modify Reservation', 'redi-restaurant-reservation'),
                    'lookup_reservation' => __('Find Reservation', 'redi-restaurant-reservation'),
                    'searching_reservation' => __('Searching for reservation', 'redi-restaurant-reservation'),
                    'existing_reservation_id' => __('Reservation ID', 'redi-restaurant-reservation'),
                    'personal_information' => __('Personal information', 'redi-restaurant-reservation'),
                    'personal_info_placeholder' => __('Enter any personal information from reservation like name, email or phone number', 'redi-restaurant-reservation'),

                    // Form Validation - New
                    'reservation_id_required' => __('Reservation ID is required', 'redi-restaurant-reservation'),
                    'reservation_id_invalid' => __('Reservation ID must be a number', 'redi-restaurant-reservation'),
                    'personal_info_required' => __('Personal information is required', 'redi-restaurant-reservation'),
                    
                    // Cancellation Process - New
                    'cancel_confirmation' => __('Are you sure you want to cancel this reservation?', 'redi-restaurant-reservation'),
                    'cancellation_reason' => __('Cancellation reason', 'redi-restaurant-reservation'),
                    'cancellation_reason_placeholder' => __('Please provide a reason for cancellation', 'redi-restaurant-reservation'),
                    'cancellation_reason_required' => __('Cancellation reason is required', 'redi-restaurant-reservation'),
                    'cancelling_reservation' => __('Cancelling reservation', 'redi-restaurant-reservation'),
                    
                    // Success/Status Messages - New
                    'reservation_canceled_success' => __('Your reservation has been successfully canceled', 'redi-restaurant-reservation'),
                    
                    // UI Elements - New
                    'yes' => __('Yes', 'redi-restaurant-reservation'),
                    'no' => __('No', 'redi-restaurant-reservation'),
                    'confirmation' => __('Confirmation', 'redi-restaurant-reservation'),
                    
                    // Display Improvements - New  
                    'special_requests' => __('Special requests', 'redi-restaurant-reservation'),                    
                    
                    // Error Handling
                    'unknown_error' => __('An unknown error occurred', 'redi-restaurant-reservation'),
                    'online_reservation_unavailable' => __('Online reservation service is not available at this time. Try again later or contact us directly.', 'redi-restaurant-reservation'),
                    'email_reservation_request' => __('Send Reservation Request by Email', 'redi-restaurant-reservation'),

                    // Email Template Labels (for support contact emails)
                    'email_mobile' => __('Mobile', 'redi-restaurant-reservation'),
                    'email_comments' => __('Comments', 'redi-restaurant-reservation'),

                    // Submit
                    'book_now' => __('Book Now', 'redi-restaurant-reservation'),

                    // Validation Messages
                    'phone_required' => __('Phone number is required', 'redi-restaurant-reservation'),
                    'email_invalid' => __('Please enter a valid email address', 'redi-restaurant-reservation'),
                    'first_name_required' => __('First name is required', 'redi-restaurant-reservation'),
                    'last_name_required' => __('Last name is required', 'redi-restaurant-reservation'),
                    'full_name_required' => __('Full name is required', 'redi-restaurant-reservation'),

                    // email template
                    'email_greeting'   => __('Hi, could you please make a reservation for me with following details', 'redi-restaurant-reservation'),
                    'email_restaurant' => __('Restaurant', 'redi-restaurant-reservation'),
                    'email_date'       => __('Date', 'redi-restaurant-reservation'),
                    'email_time'       => __('Time', 'redi-restaurant-reservation'),
                    'email_adults'     => __('Adults', 'redi-restaurant-reservation'),
                    'email_children'   => __('Children', 'redi-restaurant-reservation'),
                    'email_name'       => __('Name', 'redi-restaurant-reservation'),
                    'email_mobile'     => __('Mobile', 'redi-restaurant-reservation'),
                    'email_comments'   => __('Comments', 'redi-restaurant-reservation'),
                );
                
                $confirmationPage = $this->GetOption('ConfirmationPage');
                $redirect_to_confirmation_page = "";

                if (isset($confirmationPage) && strlen($confirmationPage) != 0) 
                {
                    $redirect_to_confirmation_page = get_permalink($confirmationPage);
                }

                // Create the complete data object to pass to JavaScript
                $redi_app_data = array(
                    // translations
                    'translations' => $translations,
                    // settings
                    'locale' => get_locale(),
                    'max_children' => $this->GetOption('MaxChild'),
                    'min_persons' => $this->GetOption('MinPersons', 1),
                    'max_persons' => $this->GetOption('MaxPersons', 10),
                    'children_step' => $this->GetOption('ChildrenSelection'),
                    'rest_url' => rest_url('redi-restaurant-api/v1/'),
                    'duration' => $default_reservation_duration,
                    'default_contact_email' => get_bloginfo('admin_email'),                    
                    'first_day' => strtolower(substr(get_locale(), 0, 2)) === 'en' && strtolower(get_locale()) !== 'en_gb' ? '0' : '1',
                    'large_group_message' => $this->extractTranslatedContent($this->GetOption('LargeGroupsMessage', ''), self::lang()),
                    'enable_first_last_name' => $this->GetOption('EnableFirstLastName'),
                    'manual_reservation' => $this->GetOption('ManualReservation'),
                    'place_id' => $this->GetOption('placeid'),
                    'enable_country_code' => $this->GetOption('CountryCode', true),
                    'powered_by' => $this->GetOption('Thanks', 0),
                    'enable_cancel_reservation' => $this->GetOption('EnableCancelForm', 0),
                    'enable_modify_reservation' => $this->GetOption('EnableModifyReservations', 0),
                    'mandatory_cancellation_reason' => $this->GetOption('MandatoryCancellationReason', 1),
                    'enable_menu' => (class_exists('Redi_Restaurant_Reservation_Menu_Plugin') && is_plugin_active('redi-restaurant-reservation-menu/redi-restaurant-reservation-menu.php')) ? 1 : 0,
                    'confirmation_page' => $redirect_to_confirmation_page);

                // Localize the script with your data
                wp_localize_script(
                    'redi-react-app', // The handle of your registered script
                    'rediSettings',         // The JavaScript object name that will contain your data
                    $redi_app_data          // The data to pass
                );

                wp_enqueue_script('redi-react-app');
            }


            $js_locale = get_locale();
            $datepicker_locale = substr($js_locale, 0, 2);
            
            $confirmationPage = $this->GetOption('ConfirmationPage');
            $redirect_to_confirmation_page = "";

            if (isset($confirmationPage) && strlen($confirmationPage) != 0) 
            {
                $redirect_to_confirmation_page = get_permalink($confirmationPage);
            }

            $time_format_s = explode(':', $time_format);

            $timepicker_time_format = (isset($time_format_s[0]) && in_array($time_format_s[0],
                    array('g', 'h'))) ? 'h:mm tt' : 'HH:mm';
            $buttons_time_format = (isset($time_format_s[0]) && in_array($time_format_s[0],
                    array('g', 'h'))) ? 'h:MM TT' : 'HH:MM';
            if (function_exists('qtrans_convertTimeFormat') || function_exists('ppqtrans_convertTimeFormat')) {// time format from qTranslate and qTranslate Plus
                global $q_config;
                $format = $q_config['time_format'][$q_config['language']];
                $buttons_time_format = ReDiTime::convert_to_js_format($format);
            }
            if (isset($this->options['ManualReservation']) && $this->options['ManualReservation'] == 1) {
                $manual = true;
            }

            $username = '';
            $lname = '';
            $email = '';
            $phone = '';
            $user_id = get_current_user_id();
            $returned_user = FALSE;
            $enablefirstlastname = $this->GetOption('EnableFirstLastName');
            
            if ($user_id)
            {
                $user_data = get_userdata($user_id);
                $username = get_user_meta( $user_id, 'first_name', true ); 
                $lname = get_user_meta( $user_id, 'last_name', true );
                
                if ($enablefirstlastname == 'false'){
                    $username = $username . ' ' . $lname;
                }
                $email = $user_data->user_email;
                $phone = get_user_meta($user_id, 'phone', true );
                $userimg = get_avatar($user_id);
                $returned_user = !empty($username) && !empty($email) && !empty($phone);
                
                $this->add_reminder_to_make_a_reservation($placeID, self::lang(), $username, $email);
            }

            $template = 'frontend.php';

            if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::Design25_v2) {
                $template = 'frontend_v2.php';
            }
            else if ($this->GetOption("Theme", ReDiTheme::Default_v1) == ReDiTheme::DesignR25_v3) {
                $template = 'frontend_v3.php';
            }

            if (file_exists(get_stylesheet_directory() . '/redi-restaurant-reservation/' . $template))
            {
                require_once(get_stylesheet_directory() . '/redi-restaurant-reservation/' . $template);
            }
            else
            {
                require_once(REDI_RESTAURANT_TEMPLATE . $template);
            }

            $out = ob_get_contents();

            ob_end_clean();

            return $out;
        }

        function extractTranslatedContent($string, $tag) {
            // Handle null values to prevent PHP 8.1+ deprecation warnings
            if ($string === null) {
                return '';
            }
            $tag = str_replace('-', '_', $tag);
            $pattern = '/\[' . preg_quote($tag, '/') . '\](.*?)\[\/' . preg_quote($tag, '/') . '\]/';
            preg_match($pattern, $string, $matches);
            if(isset($matches[1])) {
                return $matches[1];
            }
            return $string; // Return same string if pattern is not found
        }

        function getPeriodFromTypeForMaxTime($type)
        {
            switch ($type) {
                case 'M':
                    return 'months';
                case 'D':
                    return 'days';
            }

            return 'hour';
        }
        
        function getPeriodFromType($type)
        {
            switch ($type) {
                case 'M':
                    return 'minutes';
                case 'D':
                    return 'days';
            }

            return 'hour';
        }

        function add_reminder_to_make_a_reservation($placeID, $lang, $username, $email)
        {
            if (empty($email) || empty($username))
            {
                // don't create if no information provided
                return;
            }

            // check that reminder is not created for this user
            $key = 'redi-reminder-for-' . $email;
            $val = get_transient($key);

            if ($val == null)
            {
                $ret = $this->redi->addReminder(
                    $placeID,
                    $lang,
                    array(
                        'Name'  => $username,
                        'Email'  => $email));

                if ( !isset( $ret['Error'] ) ) 
                {
                    set_transient($key, true, 60 * 60 * 24);
                }
            }
        }        

        private function step1($categoryID, $post, $placeID = null)
        {
            global $q_config;
            $loc = get_locale();
            if (isset($post['lang'])) {
                $loc = $post['lang'];
            }
            $time_lang = null;
            $time_format = get_option('time_format');
            if (isset($q_config['language'])) { //if q_translate
                $time_lang = $q_config['language'];
                foreach ($q_config['locale'] as $key => $val) {
                    if ($loc == $val) {
                        $time_lang = $key;
                    }
                }
            } else { // load time format from file
                $time_format = ReDiTime::load_time_format($loc, $time_format);
            }

            $timeshiftmode = self::GetPost('timeshiftmode',
                $this->GetOption('timeshiftmode', $this->GetOption('TimeShiftMode', 'byshifts')));
            // convert date to array
            $date = date_parse(self::GetPost('startDateISO') . ' ' . self::GetPost('startTime',
                    gmdate('H:i', current_time('timestamp'))));

            if ($date['error_count'] > 0) {
                echo wp_json_encode(
                    array_merge($date['errors'],
                        array('Error' => __('Selected date or time is not valid.', 'redi-restaurant-reservation'))
                    ));
                die;
            }

            $startTimeStr = $date['year'] . '-' . $date['month'] . '-' . $date['day'] . ' ' . $date['hour'] . ':' . $date['minute'];

            $adults = (int)$post['adults'];
            $children = (int)$post['children'];
            // convert to int
            $startTimeInt = strtotime($startTimeStr, 0);

            // calculate end time and take custom duration if set
            $endTimeInt = strtotime('+' . ReDiTime::getReservationTime($adults + ($children ?? 0), (int)$post['duration']) . 'minutes', $startTimeInt);

            // format to ISO
            $startTimeISO = gmdate('Y-m-d H:i', $startTimeInt);
            $endTimeISO = gmdate('Y-m-d H:i', $endTimeInt);
            $currentTimeISO = current_datetime()->format('Y-m-d H:i');

            if ($timeshiftmode === 'byshifts') {
                $StartTime = gmdate('Y-m-d 00:00', strtotime($post['startDateISO'])); //CalendarDate + 00:00
                $EndTime = gmdate('Y-m-d 00:00',
                    strtotime('+1 day', strtotime($post['startDateISO']))); //CalendarDate + 1day + 00:00
                $params = array(
                    'StartTime' => urlencode($StartTime),
                    'EndTime' => urlencode($EndTime),
                    'Quantity' => $adults + ($children ?? 0),
                    'ChildrenQuantity' => $children,
                    'Lang' => str_replace('_', '-', $post['lang']),
                    'CurrentTime' => urlencode($currentTimeISO),
                    'AlternativeTimeStep' => ReDiTime::getAlternativeTimeStep($this->options, $persons)
                );
                if (isset($post['alternatives'])) {
                    $params['Alternatives'] = (int)$post['alternatives'];
                }
                $params = apply_filters('redi-reservation-pre-query', $params);
                $alternativeTime = AlternativeTime::AlternativeTimeByDay;

                $custom_duration = ReDiTime::loadCustomDurations();

                if (isset($custom_duration)) {
                    // Check availability for custom duration
                    $custom_duration_availability = $this->redi->getCustomDurationAvailability($categoryID, array(
                        'date' => $post['startDateISO']));

                    // if for selected duration no more reservation is allowed return all booked flag
                    if (!$this->isReservationAvailableForSelectedDuration(
                        $persons, $custom_duration_availability, $custom_duration, (int)$post['duration'])) {
                        $query = array("all_booked_for_this_duration" => true);
                        return $query;
                    }
                }

                switch ($alternativeTime) {
                    case AlternativeTime::AlternativeTimeBlocks:
                        $query = $this->redi->query($categoryID, $params);
                        break;

                    case AlternativeTime::AlternativeTimeByDay:
                        $params['ReservationDuration'] = ReDiTime::getReservationTime($persons, (int)$post['duration']);
                        $query = $this->redi->availabilityByDay($categoryID, $params);
                        break;
                }
            } else {
                $categories = $this->redi->getPlaceCategories($placeID);
                if (isset($categories['Error'])) {
                    $categories['Error'];
                    echo wp_json_encode($categories);
                    die;
                }

                $params = array(
                    'StartTime' => urlencode($startTimeISO),
                    'EndTime' => urlencode($endTimeISO),
                    'Quantity' => $persons,
                    'Alternatives' => 2,
                    'Lang' => str_replace('_', '-', $post['lang']),
                    'CurrentTime' => urlencode($currentTimeISO),
                    'AlternativeTimeStep' => ReDiTime::getAlternativeTimeStep($this->options, $persons)
                );
                $category = $categories[0];

                $query = $this->redi->query($category->ID, $params);
            }

            if (isset($query['Error'])) {
                return $query;
            }

            if ($timeshiftmode === 'byshifts') {

                if (has_filter('redi-reservation-discount'))
                {
                    $discounts = apply_filters('redi-reservation-discount', $startTimeInt, $placeID);
                }

                $query['alternativeTime'] = $alternativeTime;
                switch ($alternativeTime) {
                    case AlternativeTime::AlternativeTimeBlocks: // pass thought
                        foreach ($query as $q) {
                            $q->Select = ($startTimeISO == $q->StartTime && $q->Available);
                            $q->StartTimeISO = $q->StartTime;
                            $q->EndTimeISO = $q->EndTime;
                            $q->StartTime = ReDiTime::format_time($q->StartTime, $time_lang, $time_format);
                            $q->EndTime = gmdate($time_format, strtotime($q->EndTime));

                            $duration = date_diff(date_create($q->StartTimeISO), date_create($q->EndTimeISO));
                            $q->Duration = $duration->h * 60 + $duration->i;
                        }
                        break;
                    case AlternativeTime::AlternativeTimeByDay:
                        foreach ($query as $q2) {
                            if (isset($q2->Availability)) {
                                foreach ($q2->Availability as $q) {
                                    
                                    if (isset($discounts))
                                    {
                                        $discountElement = apply_filters('redi-reservation-max-discount', $discounts, $q->StartTime);

                                        if (isset($discountElement))
                                        {
                                            $q->Discount = $discountElement->discountVisual;
                                            $q->DiscountClass = $discountElement->discountClass;
                                        }
                                    }
                                    
                                    $q->Select = ($startTimeISO == $q->StartTime && $q->Available);
                                    $q->StartTimeISO = $q->StartTime;
                                    $q->EndTimeISO = $q->EndTime;
                                    $q->StartTime = ReDiTime::format_time($q->StartTime, $time_lang, $time_format);
                                    $q->EndTime = gmdate($time_format, strtotime($q->EndTime));
                                    
                                    $duration = date_diff(date_create($q->StartTimeISO), date_create($q->EndTimeISO));
                                    $q->Duration = $duration->h * 60 + $duration->i;
                                }
                            }
                        }
                        break;
                }
            } else {
                foreach ($query as $q) {
                    $q->Select = ($startTimeISO == $q->StartTime && $q->Available);
                    $q->StartTimeISO = $q->StartTime;
                    $q->StartTime = ReDiTime::format_time($q->StartTime, $time_lang, $time_format);
                    $q->EndTimeISO = $q->EndTime;
                    $q->EndTime = gmdate($time_format, strtotime($q->EndTime));

                    $duration = date_diff(date_create($q->StartTimeISO), date_create($q->EndTimeISO));
                    $q->Duration = $duration->h * 60 + $duration->i;
                }
            }

            return $query;
        }

        private function isReservationAvailableForSelectedDuration($persons, $custom_duration_availability, $custom_duration, $selected_duration)
        {
            // Find from custom duration limits
            foreach ($custom_duration["durations"] as $d) {
                if ($d["duration"] == $selected_duration) {
                    if (!isset($d["limit"])) {
                        return true;
                    }

                    $limit = $d["limit"];

                    if ($limit == null) {
                        return true;
                    }

                    foreach ((array)$custom_duration_availability as $a) {
                        if ($a->Duration == $selected_duration) {
                            return $a->Guests + $persons < $limit;
                        }
                    }
                }
            }

            return true;
        }


        function redi_restaurant_ajax()
        {
            if ($_POST['get'] == 'createPageForReDiReservation' &&                
                (!isset($_POST['redi_plugin_nonce']) || !wp_verify_nonce($_POST['redi_plugin_nonce'], 'redi_restaurant_ajax')))
            {
                $data = [
                    'Error' => __('Form security check failed with error: Nonce check failed. Reservation form is not working today, please contact us directly.', 'redi-restaurant-reservation'),
                    'NonceValue' => $_POST['redi_plugin_nonce'],
                    'NonceCheckResult' => wp_verify_nonce($_POST['redi_plugin_nonce'], 'redi_restaurant_ajax')
                ];
                echo wp_json_encode( $data );
                die;
            }

            $apiKeyId = $this->GetPost('apikeyid');
            if ($apiKeyId) {
                $this->ApiKey = get_option($this->apiKeyOptionName . $apiKeyId);
                $this->redi->setApiKey($this->ApiKey);
            }

            if (isset($_POST['placeID'])) {
                $placeID = (int)self::GetPost('placeID');
                $categories = $this->redi->getPlaceCategories($placeID);
                if (isset($categories['Error'])) {
                    echo wp_json_encode($categories);
                    die;
                }
                $categoryID = $categories[0]->ID;

            }

            $date_format_setting = $this->GetOption('DateFormat');
            $date_format = RediDateFormats::getPHPDateFormat($date_format_setting);

            switch ($_POST['get']) {
                case 'step1':
                    echo wp_json_encode($this->step1($categoryID, 
                    array(
                        'startDateISO' => self::GetPost('startDateISO'),
                        'adults' => self::GetPost('adults'),
                        'children' => self::GetPost('children'),
                        'lang' => self::GetPost('lang'),
                        'duration' => self::GetPost('duration'),
                        'alternatives' => self::GetPost('alternatives')
                    ), $placeID));
                    break;

                case 'step3':
                    try 
                    {
                        $persons = (int)self::GetPost('persons');
                        $startTimeStr = self::GetPost('startTime');

                        // convert to int
                        $startTimeInt = strtotime($startTimeStr, 0);

                        // calculate end time
                        $endTimeInt = strtotime('+' . ReDiTime::getReservationTime($persons, (int)self::GetPost('duration')) . 'minutes', $startTimeInt);

                        // format to ISO
                        $startTimeISO = gmdate('Y-m-d H:i', $startTimeInt);
                        $endTimeISO = gmdate('Y-m-d H:i', $endTimeInt);
                        $currentTimeISO = current_datetime()->format('Y-m-d H:i');
                        $comment = '';
                        $parameters = array();
                        $custom_fields = array();
                        $custom_fields = $this->redi->getCustomField(self::lang(), $placeID);

                        foreach ($custom_fields as $custom_field) {
                            if (isset($_POST['field_' . $custom_field->Id])) {
                                $parameters[] = array(
                                    'Name' => $custom_field->Name,
                                    'Text' => $custom_field->Text,
                                    'Type' => $custom_field->Type,
                                    'Print' => $custom_field->Print ? 'true' : 'false',
                                    'Value' => sanitize_text_field(
                                        $custom_field->Type === 'text' ||  $custom_field->Type === 'dropdown' || $custom_field->Type === 'options' || $custom_field->Type === 'birthday' ?
                                            self::GetPost('field_' . $custom_field->Id) : (self::GetPost('field_' . $custom_field->Id) === 'on' ? 'true' : 'false')));
                            }
                        }

                        if (has_filter('redi-reservation-discount'))
                        {
                            $discounts = apply_filters('redi-reservation-discount', $startTimeInt, $placeID);
                        
                            if (isset($discounts))
                            {
                                $discount = apply_filters('redi-reservation-max-discount', $discounts, $startTimeISO);

                                if ($discount && !empty($discount->discountText)) {
                                    $comment .= __('Discount', 'redi-restaurant-reservation') . ': ' . $discount->discountText . '<br/>';
                                }                        
                            }
                        }
   
                        $children = (int)self::GetPost('children');

                        if ($children > 0)
                        {
                            $comment .= __('Children', 'redi-restaurant-reservation') . ': ' . $children . '<br/>';
                        }

                        if (!empty($comment)) {
                            $comment .= '<br/>';
                        }

                        $comment .= mb_substr(sanitize_text_field(self::GetPost('UserComments', '')), 0, 250);

                        $user_id = get_current_user_id();
                        $user_profile_image = "";

                        if ($user_id)
                        {
                            $user_profile_image = get_avatar_url($user_id);
                        }                    

                        $params = array(
                            'reservation' => array(
                            'StartTime' => $startTimeISO,
                            'EndTime' => $endTimeISO,
                            'Quantity' => $persons,
                            'ChildrenQuantity' => $children,
                            'UserName' => sanitize_text_field(self::GetPost('UserName')),
                            'FirstName' => sanitize_text_field(self::GetPost('UserName')),
                            'LastName' => sanitize_text_field(self::GetPost('UserLastName')),
                            'UserEmail' => sanitize_email(self::GetPost('UserEmail')),
                            'UserComments' => $comment,
                            'UserPhone' => sanitize_text_field(self::GetPost('UserPhone')),
                            'UserProfileUrl' => $user_profile_image,
                            'Name' => 'Person',
                            'Lang' => str_replace('_', '-', self::GetPost('lang')),
                            'CurrentTime' => $currentTimeISO,
                            'Version' => self::plugin_get_version(),
                            'PrePayment' => 'false',
                            'Source' => 'HOMEPAGE',
                            'Admin' => is_user_logged_in() && current_user_can('manage_options')
                            )
                        );
                        if (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::Disabled ||
                            isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress
                        ) {
                            $params['reservation']['DontNotifyClient'] = 'true';
                        }

                        if (isset($this->options['ManualReservation']) && $this->options['ManualReservation'] == 1) {
                            $params['reservation']['ManualConfirmationLevel'] = 100;
                        }
                        if (!empty($parameters)) {
                            $params['reservation']['Parameters'] = $parameters;
                        }

                        $attribution = $this->redi_parse_attribution_cookie();
                        if (is_array($attribution)) {
                            $trackingCode = http_build_query(array_filter($attribution));
                            if (!empty($trackingCode)) {
                                if (!isset($params['reservation']['Parameters'])) {
                                    $params['reservation']['Parameters'] = [];
                                }
                                $params['reservation']['Parameters'][] = [
                                    'Name'  => 'TrackingCode',
                                    'Type'  => 'Hidden',
                                    'Value' => sanitize_text_field($trackingCode)
                                ];
                            }
                        }

                        if (isset($this->options['EnableFirstLastName']))
                        {
                            $reservation = $this->redi->createReservation_v1($categoryID, $params);
                        }
                        else
                        {
                            $reservation = $this->redi->createReservation($categoryID, $params);
                        }

                        //insert parameters into user database wp_redi_restaurant_reservation
                        if( !isset( $reservation['Error'] ) ) {
                            $this->fire_sgtm_event($reservation, $params);
                            ReDiRestaurantReservationDb::save_reservation($params, $reservation, $this->table_name);

                            $reservation_id = isset($reservation['ID']) ? $reservation['ID'] : null;
                            $user_name = sanitize_text_field(self::GetPost('UserName'));
                            $first_name = sanitize_text_field(self::GetPost('UserName'));
                            $last_name = sanitize_text_field(self::GetPost('UserLastName'));
                            $email = sanitize_email(self::GetPost('UserEmail'));
                            $phone = sanitize_text_field(self::GetPost('UserPhone'));

                            $payload = [
                                'reservationId' => $reservation_id,
                                'placeId'       => $placeID,
                                'startsAt'      => $startTimeISO,
                                'endsAt'        => $endTimeISO,
                                'partySize'     => $persons,
                                'parameters'    => $parameters,
                                'comments'      => $comment,
                                'customer'      => [
                                    'name'  => $name,
                                    'firstName' => $first_name,
                                    'lastName' => $last_name,
                                    'phone' => $phone,
                                    'email' => $email,
                                ],
                            ];

                            if (($p = $this->redi_prepare_payload('ReservationCreated', $payload)) !== false) {
                                $this->redi_emit_event('ReservationCreated', $p);
                            }

                            $this->processConfirmation($reservation, $placeID);
                        }

                        echo wp_json_encode($reservation);
                    }
                    catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    break;

                case 'get_place':
                    self::ajaxed_admin_page($placeID, $categoryID, true);
                    break;

                case 'date_information':
                    $place = $this->redi->getPlace($placeID);

                    $dates = $this->redi->getDateInformation(str_replace('_', '-', get_locale()), $categoryID, array(
                        'StartTime' => self::GetPost('from'),
                        'EndTime' => self::GetPost('to'),
                        'Guests' => self::GetPost('adults'),
                        'Children' => self::GetPost('children'),
                    ));

                    echo wp_json_encode( $dates );

                    break;

case 'cancel':
                    $this->cancelReservationWithReason(mb_substr(sanitize_text_field(self::GetPost('Reason', '')), 0, 250));
                    break;

                case 'cancel-visit':
                    $this->cancelReservationWithReason(__( 'Visit not confimed by the guest.', 'redi-restaurant-reservation' ));
                    break;

                case 'modify':
                    $reservation = $this->redi->findReservation( str_replace( '_', '-', self::GetPost( 'lang' ) ), preg_replace( '/[^0-9]/', '', self::GetPost( 'ID' )));

                    if( isset( $reservation['Error'] ) 
                        || ( strtolower(sanitize_email(self::GetPost('Email'))) != strtolower( $reservation['Email'] )
                            && str_replace([' '], '', sanitize_text_field(self::GetPost('Phone'))) != $reservation['Phone'] 
                            && strtolower(sanitize_text_field(self::GetPost('Name'))) != strtolower($reservation['Name'])) 
                    ) 
                    {
                        $data = [
                            'reservation' => [
                                'Error' => __( 'Unable to find reservation with provided information. Please verify that provided reservation number and phone or name or email is correct.', 'redi-restaurant-reservation' )
                            ]
                        ];
                    } 
                    else if ($reservation['Status'] == 'CANCELED')
                    {
                        $data = [
                            'reservation' => [
                                'Error' => __( 'Reservation that is canceled can not be modified.', 'redi-restaurant-reservation' )
                            ]
                        ];
                    }
                    else 
                    {
                        $startDate = gmdate(get_option('date_format'), strtotime( $reservation['From'] ) );
                        $startTime = gmdate(get_option('time_format'), strtotime( $reservation['From'] ) );
                        
                        $data = [
                            'startDate' => $startDate, 
                            'startTime' => $startTime,
                            'reservation' => $reservation
                        ];
                    }

                    echo wp_json_encode( $data );
                    break;

                case 'update':
                    $reservationID = self::GetPost('ID');
                    $lang = self::GetPost('lang');
                    $lang = str_replace('_', '-', $lang);
        
                    $reservation = $this->redi->findReservation($lang, $reservationID);

                    $params = [
                        'PlaceReferenceId' => self::GetPost('PlaceReferenceId'),
                        'UserName' => sanitize_text_field(self::GetPost('UserName')),
                        'UserEmail' => sanitize_email(self::GetPost('UserEmail')),
                        'UserComments' => sanitize_text_field(self::GetPost('UserComments')),
                        'StartTime' => self::GetPost('StartTime'),
                        'EndTime' => self::GetPost('EndTime'),
                        'UserPhone' => sanitize_text_field(self::GetPost('UserPhone')),
                        'Quantity' => self::GetPost('Quantity'),
                        'ChildrenQuantity' => self::GetPost('ChildrenQuantity'),
                        'Name' => 'Person',
                        'Version' => self::plugin_get_version(),
                        'Source' => 'HOMEPAGE',
                        'Parameters' => $this->objectToArray($reservation["Parameters"])
                    ];

                    $DontNotifyClient = 'false';

                    if (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::Disabled ||
                        isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress
                    ) 
                    {
                        $DontNotifyClient = 'true';
                    }

                    $currentTimeISO = current_datetime()->format('Y-m-d H:i');

                    $reservation = $this->redi->updateReservation(preg_replace( '/[^0-9]/', '', self::GetPost( 'ID' )), str_replace( '_', '-', self::GetPost( 'lang' ) ), $currentTimeISO, $DontNotifyClient, $params);

                    //update parameters into user database wp_redi_restaurant_reservation
                    if( !isset( $reservation['Error'] ) ) {
                        global $wpdb;
                        $wpdb->update( $wpdb->prefix . $this->table_name, [ 
                            'name'               => $params['UserName'],
                            'phone'              => $params['UserPhone'],
                            'email'              => $params['UserEmail'],
                            'date_from'          => $params['StartTime'],
                            'date_to'            => $params['EndTime'],
                            'guests'             => $params['Quantity'],
                            'comments'           => $params['UserComments'],                
                        ], ['reservation_number' => self::GetPost('ID')] );
                        
                        //mail
                        if (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress ) {
                            
                            //call api for content
                            do_action('redi-reservation-email-content', array(
                                'id' => (int)self::GetPost('ID'),
                                'lang' => str_replace('_', '-', self::GetPost('lang'))
                            ));

                            do_action('redi-reservation-send-confirmation-email', $this->emailContent);
                            //send
                        }
                        
                    }
                    
                    echo wp_json_encode($reservation);
                    break;

                case 'formatDate':

                    $startDate = gmdate($date_format, self::GetPost('startDate'));

                    echo $startDate;
                    break; 

                case 'waitlist':
                    $params = array(
                        'Name' => self::GetPost('Name'),
                        'Guests' => (int)self::GetPost('Guests'),
                        'Date' => self::GetPost('Date'),
                        'Email' => self::GetPost('Email'),
                        'Phone' => self::GetPost('Phone'),
                        'Time' => self::GetPost('Time')
                    );

                    $lang = self::lang();
                    
                    $CurrentTime = urlencode(gmdate('Y-m-d H:i'));
                    
                    $waitlist = $this->redi->addWaitList($placeID, $params, $CurrentTime, $lang);
                    echo wp_json_encode($waitlist);
                    break;

                case 'confirm-visit':
                    $this->confirmReservationVisit();
                    break;

                case 'activationCheck':
                    
                    if (!current_user_can('administrator')) {
                        wp_die(__('You do not have permission to access this call.', 'redi-restaurant-reservation'));
                    }
                    
                    $responce = [];
                    $type = self::GetPost('type');
                    $data = self::GetPost('data');
                    $consent = self::GetPost('consentContact');

                    if($type == 'email') {
                        // Handle null values to prevent PHP 8.1+ deprecation warnings
                        if(empty($data)) {
                            $error['Error'] = __('Email is required.', 'redi-restaurant-reservation');
                            echo $error['Error'];
                        } elseif(preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i", $data ?? '') == 1) {
                            $this->register($data,$consent);
                            $responce['success'] = 'success';
                            $responce['type'] = $type;
                            echo wp_json_encode($responce);
                        } else {
                            $error['Error'] = __('Email is not valid.', 'redi-restaurant-reservation');
                            echo $error['Error'];
                        }
                    } else {

                        if ($data && preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i", $data) == 1) {

                            if (!isset($this->options) || empty($this->options)) {
                                $this->options = array(); 
                            }

                            $this->redi->setApiKey($data);
                            $this->ApiKey = $this->options['ID'] = $data;
                            $this->saveAdminOptions();
                            $responce['success'] = 'success';
                            $responce['type'] = $type;
                            echo wp_json_encode($responce);
                        } else {
                            $error['Error'] = __('Api key should be a GUID number.', 'redi-restaurant-reservation');
                            echo $error['Error'];
                        }

                    }    
                                  
                    break;

                // Page Creating case
                case 'createPageForReDiReservation':

                    if (!current_user_can('administrator')) {
                        wp_die(__('You do not have permission to access this call.', 'redi-restaurant-reservation' ));
                    }

                    $type = self::GetPost('type');
                    $data = self::GetPost('pagedata');
                    if($type != 'page_skip'){
                        if( !empty($data) ){
                            delete_option($this->_name . '_page_title');
                            add_option($this->_name . '_page_title', $data, '', 'yes');

                            $the_page = get_page_by_title($data);

                            if (!$the_page) {
                                // Create post object
                                $_p = array();
                                $_p['post_title'] = $data;
                                $_p['post_content'] = $this->content;
                                $_p['post_status'] = 'publish';
                                $_p['post_type'] = 'page';
                                $_p['comment_status'] = 'closed';
                                $_p['ping_status'] = 'closed';
                                $_p['post_category'] = array(1); // the default 'Uncategorized'
                                // Insert the post into the database
                                $created_page_id = wp_insert_post($_p);
                                if(!is_wp_error($created_page_id)){
                                    $this->page_id = $created_page_id;
                                    echo 'success';
                                }else{
                                    //there was an error in the post insertion, 
                                    $error['Error'] = $created_page_id->get_error_message();
                                }
                            } else {
                                // the plugin may have been previously active and the page may just be trashed...
                                $this->page_id = $the_page->ID;

                                //make sure the page is not trashed...
                                $the_page->post_status = 'publish';
                                $updated_page_id = wp_update_post($the_page);
                                if(!is_wp_error($updated_page_id)){
                                    $this->page_id = $updated_page_id;
                                    echo 'success';
                                }else{
                                    //there was an error in the post insertion, 
                                    $error['Error'] = $updated_page_id->get_error_message();
                                }
                            }

                            delete_option($this->_name . '_page_id');
                            add_option($this->_name . '_page_id', $this->page_id);
                            $page_obj = get_post($this->page_id); 
                            $page_slug = $page_obj->post_name;
                            add_option($this->_name . '_page_name', $page_slug, '', 'yes');
                        }else{
                            $error['Error'] = __('Page name is required', 'redi-restaurant-reservation');
                            echo $error['Error'];
                        }
                    }else{
                        delete_option($this->_name . '_page_skip');
                        add_option($this->_name . '_page_skip', true);
                        echo 'page_skiped';
                    }
                    break;

            } //Switch case End

            die;
        }

        /*
        * Frontend side user review feedback
        */
        function redi_userfeedback_submit(){
            ReDiFeedback::feedback_on_experience();
        }

        // Emit a sync action: consumers run immediately in the same request.
        function redi_emit_event(string $type, array $payload): void {
            /**
             * Action: redi/event
             *
             * @param string $type    e.g. ReservationCreated | ReservationUpdated | ReservationCancelled
             * @param array  $payload Your event data (see examples below)
             */
            do_action('redi/event', $type, $payload);
        }        

        // Optional filter to let other plugins modify/block the payload *before* sending webhooks, etc.
        function redi_prepare_payload($type, $payload) {
            /**
             * Filter: redi/event/payload
             * Return false to cancel emitting, or return (possibly modified) payload array.
             */
            $filtered = apply_filters('redi/event/payload', $payload, $type);
            return $filtered;
        }        

        public function confirmReservationVisit()
        {
            $reservationID = self::GetPost('ID');
            $lang = self::GetPost('lang');
            $lang = str_replace('_', '-', $lang);
            $currentTimeISO = current_datetime()->format('Y-m-d H:i');

            $reservation = $this->redi->findReservation($lang, $reservationID);

            $params = [
            'UserComments' =>  $reservation["Comments"],
            'UserEmail' =>  $reservation["Email"],
            'UserName' =>  $reservation["Name"],
            'UserPhone' =>  $reservation["Phone"],
            'StartTime' =>  $reservation["From"],
            'EndTime' =>  $reservation["To"],
            'Quantity' =>  $reservation["Persons"],
            'Verified' =>  'true',
            'PlaceReferenceId' => $reservation["PlaceReferenceId"],
            'Name' => 'Person',
            'DontNotifyClient' => 'true',
            'Version' => '26', // TODO: support string version in API self::plugin_get_version(),
            'Source' => 'HOMEPAGE',
            'Parameters' => $this->objectToArray($reservation["Parameters"])
            ];

            $update = $this->redi->updateReservation($reservationID, $lang, $currentTimeISO, 'true', $params);
            
            echo wp_json_encode($update);
        }

        // Function to recursively convert objects or class instances to arrays
        public function objectToArray($object) {
            if (is_object($object) || is_array($object)) {
                $array = [];
                foreach ($object as $key => $value) {
                    $array[$key] = $this->objectToArray($value);
                }
                return $array;
            }
            return $object;
        }

        public function cancelReservationWithReason($reason) {
            $personalInformation = '';
            
            if (self::GetPost('Email')) {
                $personalInformation = urlencode(sanitize_email(self::GetPost('Email')));
            } elseif (self::GetPost('Phone')) {
                $personalInformation = urlencode(str_replace([' '], '', sanitize_text_field(self::GetPost('Phone'))));
            } elseif (self::GetPost('Name')) {
                $personalInformation = urlencode(sanitize_text_field(self::GetPost('Name')));
            }
        
            $params = array(
                'ID' => urlencode(self::GetPost('ID')),
                'personalInformation' => $personalInformation,
                'Reason' => urlencode(mb_substr(sanitize_text_field($reason), 0, 250)),
                'Lang' => str_replace('_', '-', self::GetPost('lang')),
                'CurrentTime' => urlencode(gmdate('Y-m-d H:i', current_time('timestamp'))),
                'Version' => urlencode(self::plugin_get_version())
            );
        
            if (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::Disabled ||
                isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress
            ) {
                $params['DontNotifyClient'] = 'true';
            }
        
            $cancel = $this->redi->cancelReservationByClient($params);

            $errors = array();
            $success = "";
            
            $this->processCancellation($cancel, $errors, $success);

            echo wp_json_encode($cancel);
        }


        private function fire_sgtm_event($reservation, $params) {
            $sgtm_url = $this->GetOption('SgtmEndpoint', '');
            $measurement_id = $this->GetOption('GA4MeasurementId', '');
            $api_secret = $this->GetOption('GA4ApiSecret', '');

            if (empty($sgtm_url) || empty($measurement_id) || empty($api_secret)) {
                return;
            }

            $attribution = $this->redi_parse_attribution_cookie();
            if (!is_array($attribution)) {
                $attribution = [];
            }

            $client_id = wp_generate_uuid4();
            if (isset($_COOKIE['_ga'])) {
                $parts = explode('.', $_COOKIE['_ga']);
                if (count($parts) >= 4) {
                    $client_id = $parts[2] . '.' . $parts[3];
                }
            }

            $reservation_id = isset($reservation['ID']) ? $reservation['ID'] : '';
            $party_size = isset($params['reservation']['Quantity']) ? $params['reservation']['Quantity'] : '';
            $start_time = isset($params['reservation']['StartTime']) ? $params['reservation']['StartTime'] : '';

            $endpoint = rtrim($sgtm_url, '/') . '/mp/collect?measurement_id=' . urlencode($measurement_id) . '&api_secret=' . urlencode($api_secret);

            $body = [
                'client_id' => $client_id,
                'events' => [[
                    'name' => 'reservation_complete',
                    'params' => array_filter([
                        'reservation_id' => (string) $reservation_id,
                        'party_size'     => (string) $party_size,
                        'start_time'     => $start_time,
                        'source'         => 'HOMEPAGE',
                        'referrer'       => isset($attribution['referrer']) ? $attribution['referrer'] : '',
                        'utm_source'     => isset($attribution['utm_source']) ? $attribution['utm_source'] : '',
                        'utm_medium'     => isset($attribution['utm_medium']) ? $attribution['utm_medium'] : '',
                        'utm_campaign'   => isset($attribution['utm_campaign']) ? $attribution['utm_campaign'] : '',
                        'utm_content'    => isset($attribution['utm_content']) ? $attribution['utm_content'] : '',
                        'utm_term'       => isset($attribution['utm_term']) ? $attribution['utm_term'] : '',
                        'gclid'          => isset($attribution['gclid']) ? $attribution['gclid'] : '',
                        'gbraid'         => isset($attribution['gbraid']) ? $attribution['gbraid'] : '',
                        'wbraid'         => isset($attribution['wbraid']) ? $attribution['wbraid'] : '',
                        'fbclid'         => isset($attribution['fbclid']) ? $attribution['fbclid'] : '',
                        'landing_page'   => isset($attribution['landing_page']) ? $attribution['landing_page'] : '',
                    ])
                ]]
            ];

            wp_remote_post($endpoint, [
                'body'     => wp_json_encode($body),
                'headers'  => ['Content-Type' => 'application/json'],
                'timeout'  => 5,
                'blocking' => false,
            ]);
        }

        private function object_to_array($object)
        {
            return json_decode(wp_json_encode($object), true);
        }

        private static function lang()
        {

            $l = get_locale();

            if ($l == "") {
                $l = "en-US";
            }

            return str_replace('_', '-', $l);
        }
    }
} // End of class exists check

// Initialize plugin
new ReDiRestaurantReservation();

// Register activation hook
register_activation_hook(__FILE__, array('ReDiRestaurantReservation', 'install'));