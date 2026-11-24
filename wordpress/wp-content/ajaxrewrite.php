<?php
// echo 'action '.$_POST['action'];
// echo 'portfolio_id '.$_POST['portfolio_id'];
// echo 'show_title '.$_POST['show_title'];

// echo ' peticioes tipo GET ';
// echo 'action GET ' . $_GET['action'];
// die('2222222');

// if (!isset( $_POST['action']) ||  !isset($_GET['action']))
//     die('-1');

//relative to where your plugin is located
require_once('../wp-load.php');

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$action = (isset($_POST['action'])) ? esc_attr($_POST['action']) : esc_attr($_GET['action']);


//A bit of security
$allowed_actions = array(
    'alm_query_posts',
    'alm_acf_query',
    'alm_nextpage_query',
    'alm_comments_query',
    'alm_users_query',
    'alm_query_previous_post',
    'alm_query_posts',
    'ut_get_portfolio_post_content',
    'excel_pull_ext',
    'dnd_codedropz_upload',
    'excel_pull_imgs'
);


if (in_array($action, $allowed_actions)) {
    if (is_user_logged_in()) {
        do_action('wp_ajax_' . $action);
    } else {
        do_action('wp_ajax_nopriv_' . $action);
    }
} else {
    die('-2');
}
