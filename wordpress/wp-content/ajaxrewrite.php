<?php

if (!isset($_POST['action'])) {
    die('-1');
}

//relative to where your plugin is located
require_once('../wp-load.php');

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$action = esc_attr($_POST['action']);


$allowed_actions = array('nf_ajax_submit', 'nf_ajax_resume', 'nf_ajax_get_new_nonce','post_bloque_tag_select_portada', 'getPostForSelectorPortada','saveIdImagenHeader','art-pag-destacados-adicionales', 'validate_input', 'saveSliderPost', 'getSliderPost2025');

if (in_array($action, $allowed_actions)) {
    if (is_user_logged_in()) {
        check_action($action);
    } else {
        check_action($action);
    }
} else {
    die('-1');
}

function check_action($action)
{
    switch ($action) {
        case 'nf_ajax_submit':
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
        case 'post_bloque_tag_select_portada':
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
        case 'getPostForSelectorPortada':
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
        case 'saveIdImagenHeader':
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
        case 'art-pag-destacados-adicionales':
            pag_destacados_adicionales();
            break;
        case 'validate_input';
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
        case 'hash_password':
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
         case 'saveSliderPost':
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
        case 'getSliderPost2025':
            if (is_user_logged_in()) {
                do_action('wp_ajax_'.$action, $_POST);
            } else {
                do_action('wp_ajax_nopriv_'.$action, $_POST);
            }
            break;
    }
}
