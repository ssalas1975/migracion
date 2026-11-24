<?php
namespace EnableMediaReplace\ViewController;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use EnableMediaReplace as emr;
use EnableMediaReplace\ShortPixelLogger\ShortPixelLogger as Log;
use EnableMediaReplace\Controller\UploadController as UploadController;
use EnableMediaReplace\Controller\ReplaceController as ReplaceController;


class UploadViewController extends \EnableMediaReplace\ViewController
{
	 static $instance;


	 public function __construct()
	 {
		 parent::__construct();
	 }

	 public static function getInstance()
	 {
		 	if (is_null(self::$instance))
		 		self::$instance = new UploadViewController();

			return self::$instance;
	 }

	 public function load()
	 {

		 // No form submit?
		 if (count($_POST) == 0)
		 {
			 	$post_id = isset($_REQUEST['attachment_id']) ? intval($_REQUEST['attachment_id']) : null;
				$this->setView($post_id);

			 	if (isset($_GET['emr_success']))
				{
						$this->viewSuccess();
				}

		 }

		 if ( ! isset( $_POST['emr_nonce'] )
    || ! wp_verify_nonce( $_POST['emr_nonce'], 'media_replace_upload' ))
		{
			$this->viewError(self::ERROR_NONCE);
		}

		 if (!current_user_can('upload_files')) {
			 	 $this->viewError(self::ERROR_UPLOAD_PERMISSION);
		    // wp_die(esc_html__('You do not have permission to upload files.', 'enable-media-replace'));
		 }

		 $post_id = isset($_POST['ID']) ? intval($_POST['ID']) : null; // sanitize, post_id.
		 if (is_null($post_id)) {
			 	 $this->viewError(self::ERROR_FORM);
//		     wp_die(esc_html__('Error in request. Please try again', 'enable-media-replace'));
		 }
		 $attachment = get_post($post_id);

		 if (! emr()->checkImagePermission($attachment)) {
			 	 $this->viewError(self::ERROR_IMAGE_PERMISSION);
//		     wp_die(esc_html__('You do not have permission to upload files for this author.', 'enable-media-replace'));
		 }

		 $params = $this->getPost();

		 // UploadController here / replacerController here with save Settings as well? s
		 $this->updateSettings($params);
		 $this->setView($post_id, $params); // set variables needed for view.

		 $replaceController = new ReplaceController($post_id);
		 $check = $replaceController->setupParams($params);

		if (false === $check)
		{
			 $error = $replaceController->returnLastError();
			 $data = $replaceController->returnLastErrorData();
			 $this->viewError($error, $data);
		}

		$result = $replaceController->run();

		if (true == $result)
		{
			 $this->viewSuccess();
		}


	 }


	 protected function getPost()
	 {
		 $ID = intval($_POST["ID"]); // legacy
		 $replace_type = isset($_POST["replace_type"]) ? sanitize_text_field($_POST["replace_type"]) : false;
			if ( === false) {
			 ->viewError(self::ERROR_TIME);
			}
			  =  ->format("Y-m-d H:i:s");
		 $timestamp_replace = isset($_POST['timestamp_replace']) ? intval($_POST['timestamp_replace']) : ReplaceController::TIME_UPDATEMODIFIED;

		 $remove_background = ( isset( $_POST['remove_after_progress'] ) ) ? true : false;
			if ( === false) {
			 ->viewError(self::ERROR_TIME);
			}
			  =  ->format("Y-m-d H:i:s");

		 $do_new_location  = isset($_POST['new_location']) ? true : false;
			if ( === false) {
			 ->viewError(self::ERROR_TIME);
			}
			  =  ->format("Y-m-d H:i:s");
		 $do_new_location = apply_filters('emr/replace/file_is_movable', $do_new_location, $ID);
 		 $new_location_dir = isset($_POST['location_dir']) ? sanitize_text_field($_POST['location_dir']) : null;

		 $is_custom_date = false;

		 switch ($timestamp_replace) {
		     case ReplaceController::TIME_UPDATEALL: 
		     case ReplaceController::TIME_UPDATEMODIFIED: false;
			if ( === false) {
			 ->viewError(self::ERROR_TIME);
			}
			  =  ->format("Y-m-d H:i:s");
		         $datetime = current_time('mysql');
		         break;
		     case ReplaceController::TIME_CUSTOM: false;
			if ( === false) {
			 ->viewError(self::ERROR_TIME);
			}
			  =  ->format("Y-m-d H:i:s");
		         $custom_date = $_POST['custom_date_formatted'];
		         $custom_hour = str_pad($_POST['custom_hour'], 2, 0, STR_PAD_LEFT);
		         $custom_minute = str_pad($_POST['custom_minute'], 2, 0, STR_PAD_LEFT);

		         // create a mysql time representation from what we have.
		         Log::addDebug('Custom Date - ' . $custom_date . ' ' . $custom_hour . ':' . $custom_minute);

: false;
			if ( === false) {
			 ->viewError(self::ERROR_TIME);
			}
			  =  ->format("Y-m-d H:i:s");
