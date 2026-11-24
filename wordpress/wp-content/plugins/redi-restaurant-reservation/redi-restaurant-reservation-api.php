<?php
trait ReDiAPIHelperMethods
{
    /**
     * Site visitors use transients for API responses; admins bypass so changes are visible immediately.
     */
    protected function redi_use_api_transient_cache() {
        return !( is_user_logged_in() && current_user_can( 'manage_options' ) );
    }

    public function handle_api_response_error($response) {
        if (isset($response['Error'])) {
            return new WP_Error(
                'redi_api_error',
                $response['Error'],
                array('status' => 200)
            );
        }
        return false;
    }

    /**
     * Decode the redi_attribution cookie into an associative array.
     * Do not pass the raw cookie through sanitize_text_field(): WordPress strips percent-encoded
     * byte sequences (%7B, %22, …), which destroys URL-encoded JSON before json_decode.
     */
    protected function redi_parse_attribution_cookie() {
        if ( ! isset( $_COOKIE['redi_attribution'] ) || $_COOKIE['redi_attribution'] === '' ) {
            return null;
        }
        $raw = wp_unslash( (string) $_COOKIE['redi_attribution'] );
        if ( $raw === '' ) {
            return null;
        }
        $data = json_decode( $raw, true );
        if ( ! is_array( $data ) && strpos( $raw, '%' ) !== false ) {
            $data = json_decode( rawurldecode( $raw ), true );
        }
        return is_array( $data ) ? $data : null;
    }

    
    public function get_all_places()
    {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        if ( $this->redi_use_api_transient_cache() ) {
            $cached_places = get_transient( 'redi_restaurant_places' );
            if ( $cached_places !== false ) {
                return $cached_places;
            }
        }

        $places = $this->redi->getPlaces();

        $error_response = $this->handle_api_response_error($places);
        
        if (is_wp_error($error_response)) {
            return $error_response;
        }

        // Extracting only id, name, and address fields from each place
        $filteredPlaces = array();
        
        foreach ($places as $place) {
            $filteredPlaces[] = array(
                'id' => $place->ID,
                'name' => $place->Name,
                'address' => $place->Address,
                'email' => $place->Email,
                'phone' => $place->Phone,
                'min_time_before_reservation' => $place->MinTimeBeforeReservation, 
                'min_time_before_reservation_type' => $place->MinTimeBeforeReservationType,
                'max_time_before_reservation' => $place->MaxTimeBeforeReservation,
                'max_time_before_reservation_type' => $place->MaxTimeBeforeReservationType,
                'duration' => $place->ReservationDuration
            );
        }

        if ( $this->redi_use_api_transient_cache() ) {
            set_transient( 'redi_restaurant_places', $filteredPlaces, HOUR_IN_SECONDS );
        }

        return $filteredPlaces;
    }

    /**
     * Get custom fields for a specific place
     * 
     * @return WP_REST_Response|WP_Error Response object or WP_Error
     */
    public function get_custom_fields($place_id, $lang) {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        $cache_key = 'redi_restaurant_custom_fields_' . $place_id . '_' . $lang;

        if ( $this->redi_use_api_transient_cache() ) {
            $cached_fields = get_transient( $cache_key );
            if ( $cached_fields !== false ) {
                return new WP_REST_Response( $cached_fields, 200 );
            }
        }
        
        $custom_fields = $this->redi->getCustomField($lang, $place_id);

        $error_response = $this->handle_api_response_error($custom_fields);
    
        if (is_wp_error($error_response)) {
            return $error_response;
        } 

        $custom_fields_array = json_decode(json_encode($custom_fields), true);

        foreach ($custom_fields_array as &$field) {
            if (isset($field['Text'])) {
            $field['Text'] = $this->extractTranslatedContent($field['Text'], $lang);
            }
        }

        $custom_fields = $custom_fields_array;

        if ( $this->redi_use_api_transient_cache() ) {
            set_transient( $cache_key, $custom_fields, HOUR_IN_SECONDS );
        }

        return new WP_REST_Response($custom_fields, 200);
    }

    /**
     * Handle API key error
     * 
     * @return WP_Error Error response indicating the API key is missing or invalid
     */
    public function handle_api_key_error() {
        return new WP_Error(
            'redi_api_error',
            __('ReDi Restaurant Reservation plugin not registered', 'redi-restaurant-reservation'),
            array('status' => 401)
        );
    }

    /**
     * Get date information for a specific place
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error Response object or WP_Error
     */
    public function get_days_availability($place_id, $start_time, $end_time, $guests){
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        $cache_key = 'redi_restaurant_days_availability_' . $place_id . '_' . $start_time . '_' . $end_time . '_' . $guests;

        if ( $this->redi_use_api_transient_cache() ) {
            $cached_dates = get_transient( $cache_key );
            if ( $cached_dates !== false ) {
                return new WP_REST_Response( $cached_dates, 200 );
            }
        }

        $categories = $this->get_place_categories($place_id);

        $error_response = $this->handle_api_response_error($categories);

        if (is_wp_error($error_response)) {
            return $error_response;
        } 
        
        $category_id = $categories[0]->ID;
        $dates = $this->redi->getDateInformation(
            str_replace('_', '-', get_locale()), 
            $category_id, 
            array(
                'StartTime' => $start_time,
                'EndTime' => $end_time,
                'Guests' => $guests,
            )
        );

        $error_response = $this->handle_api_response_error($dates);

        if (is_wp_error($error_response)) {
            return $error_response;
        }         

        if ( $this->redi_use_api_transient_cache() ) {
            set_transient( $cache_key, $dates, HOUR_IN_SECONDS );
        }

        return new WP_REST_Response($dates, 200);
    }

    public function get_menu($place_id, $time, $shiftName, $lang) {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        $cache_key = 'redi_restaurant_menu_' . $place_id . '_' . $time . '_' . $shiftName . '_' . $lang;

        if ( $this->redi_use_api_transient_cache() ) {
            $cached_menu = get_transient( $cache_key );
            if ( $cached_menu !== false ) {
                return new WP_REST_Response( $cached_menu, 200 );
            }
        }

        $menu = apply_filters('redi-reservation-menu', $time, $shiftName);

        $error_response = $this->handle_api_response_error($menu);

        if (is_wp_error($error_response)) {
            return $error_response;
        }

        if ( $this->redi_use_api_transient_cache() ) {
            set_transient( $cache_key, $menu, HOUR_IN_SECONDS );
        }

        return new WP_REST_Response($menu, 200);
    }

    public function get_time_availability($place_id, $day, $duration, $guests, $step) {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        $cache_key = 'redi_restaurant_time_availability_' . $place_id . '_' . $day . '_' . $duration . '_' . $guests;

        if ( $this->redi_use_api_transient_cache() ) {
            $cached_availability = get_transient( $cache_key );
            if ( $cached_availability !== false ) {
                return new WP_REST_Response( $cached_availability, 200 );
            }
        }

        $categories = $this->get_place_categories($place_id);

        $error_response = $this->handle_api_response_error($categories);
    
        if (is_wp_error($error_response)) {
            return $error_response;
        } 

        $StartTime = gmdate('Y-m-d 00:00', strtotime($day)); //CalendarDate + 00:00
        $EndTime = gmdate('Y-m-d 00:00',
            strtotime('+1 day', strtotime($day))); //CalendarDate + 1day + 00:00
        $currentTimeISO = current_datetime()->format('Y-m-d H:i');

        $params = array(
            'StartTime' => urlencode($StartTime),
            'EndTime' => urlencode($EndTime),
            'Quantity' => $guests,
            'ReservationDuration' => $duration,
            'AlternativeTimeStep' => $step,
            'Lang' => str_replace('_', '-', get_locale()),
            'CurrentTime' => urlencode($currentTimeISO)
        );

        $time_availability = $this->redi->availabilityByDay($categories[0]->ID, $params);
        
        $error_response = $this->handle_api_response_error($time_availability);
    
        if (is_wp_error($error_response)) {
            return $error_response;
        }

        if (has_filter('redi-reservation-discount'))
        {
            $startTimeInt = strtotime($day, 0);
            $discounts = apply_filters('redi-reservation-discount', $startTimeInt, $place_id);
        }

        if (isset($discounts))
        {
            foreach ($time_availability as $q2) {
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
                    }
                }
            }
        }

        if ( $this->redi_use_api_transient_cache() ) {
            set_transient( $cache_key, $time_availability, HOUR_IN_SECONDS );
        }

        return new WP_REST_Response($time_availability, 200);
    }

    /**
     * Get place categories with caching
     * 
     * @param int $place_id Place ID
     * @return array|WP_Error Categories array or WP_Error
     */
    public function get_place_categories($place_id) {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        $cache_key = 'redi_restaurant_place_categories_' . $place_id;

        if ( $this->redi_use_api_transient_cache() ) {
            $cached_categories = get_transient( $cache_key );
            if ( $cached_categories !== false ) {
                return $cached_categories;
            }
        }

        $categories = $this->redi->getPlaceCategories($place_id);

        $error_response = $this->handle_api_response_error($categories);

        if (is_wp_error($error_response)) {
            return $error_response;
        }

        if ( $this->redi_use_api_transient_cache() ) {
            set_transient( $cache_key, $categories, DAY_IN_SECONDS );
        }

        return $categories;
    }

    /**
     * Find a reservation by ID and personal information
     * 
     * @param string $reservation_id Reservation ID
     * @param string $personal_info Email, phone, or name
     * @param string $lang Language code
     * @return WP_REST_Response|WP_Error Response object or WP_Error
     */
    public function find_reservation($reservation_id, $personal_info = '', $lang = '') {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        // Use provided language or default to current locale
        $language = !empty($lang) ? str_replace('_', '-', $lang) : str_replace('_', '-', get_locale());
    
        $reservation = $this->redi->findReservation($language, $reservation_id);

        $error_response = $this->handle_api_response_error($reservation);
        
        if (is_wp_error($error_response)) {
            return $error_response;
        }

        // Verify reservation ownership using provided personal information
        $personal_info = trim($personal_info);
        $email_match = !empty($personal_info) && strtolower(sanitize_email($personal_info)) == strtolower($reservation['Email']);
        $phone_match = !empty($personal_info) && str_replace([' '], '', sanitize_text_field($personal_info)) == $reservation['Phone'];
        $name_match = !empty($personal_info) && strtolower(sanitize_text_field($personal_info)) == strtolower($reservation['Name']);

        // Check if any verification method matches
        if (!$email_match && !$phone_match && !$name_match) {
            return new WP_Error(
                'redi_reservation_not_found',
                __('Unable to find reservation with provided information. Please verify that provided reservation number and personal information (email, phone, or name) is correct.', 'redi-restaurant-reservation'),
                array('status' => 404)
            );
        }

        // Check if reservation is canceled
        if (isset($reservation['Status']) && $reservation['Status'] == 'CANCELED') {
            return new WP_Error(
                'redi_reservation_canceled',
                __('Reservation that is canceled can not be modified.', 'redi-restaurant-reservation'),
                array('status' => 400)
            );
        }

        if (isset($reservation['To'])) {
            $reservation_end_time = strtotime($reservation['To']);
            if ($reservation_end_time < current_time('timestamp')) {
                return new WP_Error(
                    'redi_reservation_in_past',
                    __('Reservation is in the past and cannot be modified.', 'redi-restaurant-reservation'),
                    array('status' => 400)
                );
            }
        }

        return new WP_REST_Response($reservation, 200);
    }

    /**
     * Create a reservation
     * 
     * @param int $place_id Place ID
     * @param array $reservation Reservation data
     * @return WP_REST_Response|WP_Error Response object or WP_Error
     */
    public function create_reservation($place_id, $reservation) {
        // 1. API key check
        if ($this->ApiKey === null) {
            return $this->handle_api_key_error();
        }

        // simulate response for testing
        // Uncomment the following lines to simulate a reservation response for testing purposes
        /*return new WP_REST_Response(array(
            'ID' => '123456789',
            'Message' => __('This is a simulated reservation.', 'redi-restaurant-reservation')
        ), 200);*/

        // Map ReservationObject fields from $reservation array
        $date = isset($reservation['date']) ? $reservation['date'] : '';
        $time = isset($reservation['time']) ? $reservation['time'] : '';
        $startTime = trim($date . ' ' . $time);
        $duration = isset($reservation['duration']) ? $reservation['duration'] : 30; // fallback to 30 min if not provided
        // Extract customer info from nested 'customer' array if present
        $customer = isset($reservation['customer']) ? $reservation['customer'] : array();

        // If fullName is provided, use it as UserName and leave FirstName/LastName empty
        if (!empty($customer['fullName'])) {
            $fullName = !empty($customer['fullName']) ? $customer['fullName'] : $reservation['fullName'];
            $firstName = '';
            $lastName = '';
            $userName = $fullName;
        } else {
            $firstName = isset($customer['firstName']) ? $customer['firstName'] : (isset($reservation['firstName']) ? $reservation['firstName'] : '');
            $lastName = isset($customer['lastName']) ? $customer['lastName'] : (isset($reservation['lastName']) ? $reservation['lastName'] : '');
            $userName = $firstName;
        }

        $email = isset($customer['email']) ? $customer['email'] : (isset($reservation['email']) ? $reservation['email'] : '');
        $phone = isset($customer['phone']) ? $customer['phone'] : (isset($reservation['phone']) ? $reservation['phone'] : '');

        $parameters = array();
        $custom_fields = array();
        $custom_fields = $this->redi->getCustomField(self::lang(), $place_id);

        foreach ($custom_fields as $custom_field) {
            if (isset($reservation['field_' . $custom_field->Id])) {
            $parameters[] = array(
                'Name' => $custom_field->Name,
                'Text' => $custom_field->Text,
                'Type' => $custom_field->Type,
                'Print' => $custom_field->Print ? 'true' : 'false',
                'Value' => sanitize_text_field(
                $custom_field->Type === 'text' ||  
                $custom_field->Type === 'dropdown' || 
                $custom_field->Type === 'options' || 
                $custom_field->Type === 'birthday'
                    ? $reservation['field_' . $custom_field->Id]
                    : ($reservation['field_' . $custom_field->Id] === true ? 'true' : 'false'))
                );
            }
        }
        
        $comment = '';

        if (has_filter('redi-reservation-discount'))
        {
            $startTimeInt = strtotime($startTime, 0);
            $discounts = apply_filters('redi-reservation-discount', $startTimeInt, $place_id);
        
            if (isset($discounts) && is_array($discounts) && count($discounts) > 0)
            {
                $startTimeISO = gmdate('Y-m-d H:i', $startTimeInt);
                $discount = apply_filters('redi-reservation-max-discount', $discounts, $startTimeISO);

                if ($discount && !empty($discount->discountText)) {
                    $comment .= __('Discount', 'redi-restaurant-reservation') . ': ' . $discount->discountText . '<br/>';
                }                        
            }

            if (!empty($comment)) {
                $comment .= '<br/>';
            }            
        }

        if (isset($reservation['menuItems']) && is_array($reservation['menuItems']) && has_filter('redi-reservation-menu-items')) {
            $menu_ids = array();
            foreach ($reservation['menuItems'] as $item) {
                if (isset($item['id'])) {
                    $menu_ids[] = $item['id'];
                }
            }
            
            if (!empty($menu_ids)) {
                $menu_items_info = apply_filters('redi-reservation-menu-items', $menu_ids);
                if (!empty($menu_items_info) && is_array($menu_items_info)) {
                    $comment .= __('Menu items', 'redi-restaurant-reservation') . ':<br/>';
                    foreach ($reservation['menuItems'] as $item) {
                        $item_name = '';
                        
                        if (isset($item['id'])) {
                            foreach ($menu_items_info as $menu_item) {
                                if (isset($menu_item['id']) && $menu_item['id'] == $item['id']) {
                                    $item_name = $menu_item['name'];
                                    break;
                                }
                            }
                        }
                        
                        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                        $comment .= esc_html($item_name) . ' x ' . $quantity . '<br/>';
                    }
                }
            }

            if (!empty($comment)) {
                    $comment .= '<br/>';
            }
        }
        
        $comment .= mb_substr(sanitize_text_field(isset($reservation['comments']) ? $reservation['comments'] : ''), 0, 250);

        $params = array(
            'reservation' => array(
            'StartTime'        => date('Y-m-d H:i', strtotime($startTime)),
            'EndTime'          => date('Y-m-d H:i', strtotime($startTime . ' +' . intval($duration) . ' minutes')),
            'ChildrenQuantity' => isset($reservation['children']) ? intval($reservation['children']) : 0,
            'Quantity'         => (isset($reservation['adults']) ? intval($reservation['adults']) : 1) + (isset($reservation['children']) ? intval($reservation['children']) : 0),
            'UserName'         => $userName,
            'FirstName'        => $firstName,
            'LastName'         => $lastName,
            'UserEmail'        => $email,
            'UserComments'     => $comment,
            'UserPhone'        => $phone,
            'Lang'             => get_locale(),
            'CurrentTime'      => current_datetime()->format('Y-m-d H:i'),
            'Version'          => defined('REDI_PLUGIN_VERSION') ? REDI_PLUGIN_VERSION : 'unknown',
            'PrePayment'       => 'false',
            'Source'           => 'HOMEPAGE',
            'Name'              => 'Person', // Default name for the reservation
            )
        );

        $categories = $this->get_place_categories($place_id);

        $error_response = $this->handle_api_response_error($categories);

        if (is_wp_error($error_response)) {
            return $error_response;
        }

        $categoryID = $categories[0]->ID;

        if (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::Disabled ||
            isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress
        ) {
            $params['reservation']['DontNotifyClient'] = 'true';
        }

        if (isset($this->options['ManualReservation']) && $this->options['ManualReservation'] == 1) {
            $params['reservation']['ManualConfirmationLevel'] = 100;
        }

        $reservation_parameters = array();
        if (!empty($parameters)) {
            $reservation_parameters = $parameters;
        }

        $explicit_tracking = isset($reservation['trackingCode']) && is_scalar($reservation['trackingCode'])
            && trim((string) $reservation['trackingCode']) !== '';

        if ($explicit_tracking) {
            $reservation_parameters[] = array(
                'Name'  => 'TrackingCode',
                'Type'  => 'Hidden',
                'Value' => sanitize_text_field((string) $reservation['trackingCode'])
            );
        }

        if (!$explicit_tracking) {
            $attribution = $this->redi_parse_attribution_cookie();
            if ($attribution === null && isset($reservation['attribution']) && is_array($reservation['attribution'])) {
                $attribution = $reservation['attribution'];
            }
            if (is_array($attribution)) {
                foreach (array_filter($attribution) as $attr_key => $attr_value) {
                    if (!is_scalar($attr_value)) {
                        continue;
                    }
                    $clean_key = sanitize_key((string) $attr_key);
                    if ($clean_key === '') {
                        continue;
                    }
                    $reservation_parameters[] = array(
                        'Name'  => $clean_key,
                        'Type'  => 'Hidden',
                        'Value' => sanitize_text_field((string) $attr_value)
                    );
                }
            }
        }

        if (!empty($reservation_parameters)) {
            $params['reservation']['Parameters'] = $reservation_parameters;
        }

        if (isset($this->options['EnableFirstLastName']))
        {
            $reservation = $this->redi->createReservation_v1($categoryID, $params);
        }
        else
        {
            $reservation = $this->redi->createReservation($categoryID, $params);
        }        


        $error_response = $this->handle_api_response_error($reservation);

        if (is_wp_error($error_response)) {
            return $error_response;
        }

        ReDiRestaurantReservationDb::save_reservation($params, $reservation, $this->table_name);
        $this->fire_sgtm_event($reservation, $params);
        $this->processConfirmation($reservation, $place_id);        

        return new WP_REST_Response($reservation, 200);
    }

    /**
     * Cancel a reservation by ID, reason, and personal information
     *
     * @param string $reservation_id Reservation ID
     * @param string $personalInformation Email, phone, or name for verification
     * @param string $reason Reason for cancellation
     * @param string $lang Language code
     * @return WP_REST_Response|WP_Error
     */
    public function cancel_reservation($reservation_id, $personalInformation, $reason, $lang = '') {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        $language = !empty($lang) ? str_replace('_', '-', $lang) : str_replace('_', '-', get_locale());

        $params = array(
            'ID' => urlencode($reservation_id),
            'personalInformation' => $personalInformation,
            'Reason' => urlencode(mb_substr(sanitize_text_field($reason), 0, 250)),
            'Lang' => $language,
            'CurrentTime' => urlencode(gmdate('Y-m-d H:i', current_time('timestamp'))),
            'Version' => urlencode(method_exists($this, 'plugin_get_version') ? $this->plugin_get_version() : (defined('REDI_PLUGIN_VERSION') ? REDI_PLUGIN_VERSION : 'unknown'))
        );

        if (
            (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::Disabled) ||
            (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress)
        ) {
            $params['DontNotifyClient'] = 'true';
        }

        $cancel = $this->redi->cancelReservationByClient($params);

        $error_response = $this->handle_api_response_error($cancel);

        if (is_wp_error($error_response)) {
            return $error_response;
        }

        $errors = array();
        $success = "";

        if (method_exists($this, 'processCancellation')) {
            $this->processCancellation($cancel, $errors, $success);
        }

        return new WP_REST_Response($cancel, 200);
    }

    /**
     * Update a reservation by ID
     *
     * @param string $reservation_id Reservation ID
     * @param array $body Request body: personal_information, lang, place_reference_id, start_time, end_time, quantity, children_quantity, user_name, user_email, user_phone, user_comments, etc.
     * @return WP_REST_Response|WP_Error
     */
    public function update_reservation($reservation_id, $body) {
        if ($this->ApiKey == null) {
            return $this->handle_api_key_error();
        }

        $personal_info = isset($body['personal_information']) ? trim($body['personal_information']) : '';
        if (empty($personal_info)) {
            $customer = isset($body['customer']) && is_array($body['customer']) ? $body['customer'] : array();
            if (!empty($customer['email'])) {
                $personal_info = trim($customer['email']);
            } elseif (!empty($customer['phone'])) {
                $personal_info = trim($customer['phone']);
            } elseif (!empty($customer['fullName'])) {
                $personal_info = trim($customer['fullName']);
            } elseif (!empty($body['email'])) {
                $personal_info = trim($body['email']);
            } elseif (!empty($body['phone'])) {
                $personal_info = trim($body['phone']);
            } elseif (!empty($body['fullName'])) {
                $personal_info = trim($body['fullName']);
            }
        }
        $lang = isset($body['lang']) ? str_replace('_', '-', $body['lang']) : str_replace('_', '-', get_locale());

        if (empty($personal_info)) {
            return new WP_Error(
                'redi_missing_personal_info',
                __('Personal information (email, phone, or name) is required to update a reservation.', 'redi-restaurant-reservation'),
                array('status' => 400)
            );
        }

        $reservation = $this->redi->findReservation($lang, $reservation_id);
        $error_response = $this->handle_api_response_error($reservation);
        if (is_wp_error($error_response)) {
            return $error_response;
        }

        $email_match = strtolower(sanitize_email($personal_info)) === strtolower($reservation['Email']);
        $phone_match = str_replace([' '], '', sanitize_text_field($personal_info)) === $reservation['Phone'];
        $name_match = strtolower(sanitize_text_field($personal_info)) === strtolower($reservation['Name']);
        if (!$email_match && !$phone_match && !$name_match) {
            return new WP_Error(
                'redi_reservation_not_found',
                __('Unable to find reservation with provided information.', 'redi-restaurant-reservation'),
                array('status' => 404)
            );
        }

        if (isset($reservation['Status']) && $reservation['Status'] === 'CANCELED') {
            return new WP_Error(
                'redi_reservation_canceled',
                __('Reservation that is canceled can not be modified.', 'redi-restaurant-reservation'),
                array('status' => 400)
            );
        }

        $parameters = isset($reservation['Parameters']) && (is_array($reservation['Parameters']) || is_object($reservation['Parameters']))
            ? (method_exists($this, 'objectToArray') ? $this->objectToArray($reservation['Parameters']) : (array) $reservation['Parameters'])
            : array();

        if (isset($body['start_time'])) {
            $start_time = $body['start_time'];
            $end_time = isset($body['end_time']) ? $body['end_time'] : $reservation['To'];
        } elseif (isset($body['date']) && isset($body['time'])) {
            $start_time = date('Y-m-d H:i', strtotime(trim($body['date'] . ' ' . $body['time'])));
            $duration = isset($body['duration']) ? intval($body['duration']) : 30;
            $end_time = date('Y-m-d H:i', strtotime($start_time . ' +' . $duration . ' minutes'));
        } else {
            $start_time = $reservation['From'];
            $end_time = $reservation['To'];
        }
        $children = isset($body['children_quantity']) ? intval($body['children_quantity']) : (isset($body['children']) ? intval($body['children']) : (isset($reservation['ChildrenQuantity']) ? (int) $reservation['ChildrenQuantity'] : 0));
        $quantity = isset($body['quantity']) ? intval($body['quantity']) : (isset($body['adults']) ? (intval($body['adults']) + $children) : (int) $reservation['Persons']);

        $params = array(
            'PlaceReferenceId' => isset($body['place_reference_id']) ? $body['place_reference_id'] : $reservation['PlaceReferenceId'],
            'UserName' => isset($body['user_name']) ? sanitize_text_field($body['user_name']) : (isset($body['fullName']) ? sanitize_text_field($body['fullName']) : $reservation['Name']),
            'UserEmail' => isset($body['user_email']) ? sanitize_email($body['user_email']) : (isset($body['email']) ? sanitize_email($body['email']) : $reservation['Email']),
            'UserComments' => isset($body['user_comments']) ? sanitize_text_field($body['user_comments']) : (isset($body['comments']) ? sanitize_text_field($body['comments']) : (isset($reservation['UserComments']) ? $reservation['UserComments'] : '')),
            'StartTime' => $start_time,
            'EndTime' => $end_time,
            'UserPhone' => isset($body['user_phone']) ? sanitize_text_field($body['user_phone']) : (isset($body['phone']) ? sanitize_text_field($body['phone']) : $reservation['Phone']),
            'Quantity' => $quantity,
            'ChildrenQuantity' => $children,
            'Name' => 'Person',
            'Version' => defined('REDI_PLUGIN_VERSION') ? REDI_PLUGIN_VERSION : 'unknown',
            'Source' => 'HOMEPAGE',
            'Parameters' => $parameters,
        );

        $dont_notify = 'false';
        if (
            (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::Disabled) ||
            (isset($this->options['EmailFrom']) && $this->options['EmailFrom'] == ReDiSendEmailFromOptions::WordPress)
        ) {
            $dont_notify = 'true';
        }

        $current_time = current_datetime()->format('Y-m-d H:i');
        $update = $this->redi->updateReservation($reservation_id, $lang, $current_time, $dont_notify, $params);

        $error_response = $this->handle_api_response_error($update);
        if (is_wp_error($error_response)) {
            return $error_response;
        }

        return new WP_REST_Response($update, 200);
    }
}