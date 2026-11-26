<?php
define('DOING_AJAX', true);

if (!isset($_REQUEST['action']))
  die('-1');

//relative to where your plugin is located
require_once('../wp-load.php');

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$action = esc_attr($_REQUEST['action']);

//A bit of security
$allowed_actions = array(
  'lpportadapdf_dorsal',
  'lpportadapdf_imprimir',
  'WPBC_AJX_CALENDAR_LOAD',
  'WPBC_AJX_BOOKING__CREATE',
  'et_fb_update_builder_assets'
);

//if(in_array($action, $allowed_actions)){
if (is_user_logged_in()) {
  do_action('wp_ajax_' . $action, $_REQUEST);
} else {
  do_action('wp_ajax_nopriv_' . $action, $_REQUEST);
}
//}
//else{
//   die('-1');
//}
?>