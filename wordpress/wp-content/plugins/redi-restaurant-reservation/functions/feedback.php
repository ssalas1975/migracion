<?php 
    class ReDiFeedback{

        // Deactivation feedback moved to functions/deactivation-feedback.php (ReDiDeactivationFeedback).

        static function feedback_on_experience()
        {
            $responce = [];
            $reservationid = ( !empty( $_POST['reservationid'] ) ) ? $_POST['reservationid'] :  '';
            $review = ( !empty( $_POST['review'] ) ) ? $_POST['review'] :  '';
            $message = ( !empty( $_POST['message'] ) ) ? $_POST['message'] :  '';
            if( !empty($reservationid)){
                $googleFormsURL = 'https://docs.google.com/forms/d/13-R-aF5hsesvxNMeGoNKKKHyhbXag2Qv3GJ2ok9QPDs/formResponse';
                $data_array = array(
                    'entry.58078908' => $reservationid,
                    'entry.119436979' => $review,
                    'entry.842379435' => $message
                );
                $response = wp_remote_post($googleFormsURL, array(
                    'body' => $data_array,
                    'timeout' => 45,
                    'sslverify' => true
                ));
                if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 ) {
                    $error_message = is_wp_error($response) ? $response->get_error_message() : 'Request failed.';
                    $responce['message'] = $error_message;
                    $responce['type'] = "Error";
                    
                }else{
                    $responce['message'] = __("Thank you for your feedback.", 'redi-restaurant-reservation');
                    $responce['type'] = "success";
                }
            }else{
                $responce['message'] = __("Reservation id is empty.", 'redi-restaurant-reservation');
                $responce['type'] = "Error";
            }
            echo wp_json_encode($responce); 
            die;            
        }
    }
?>