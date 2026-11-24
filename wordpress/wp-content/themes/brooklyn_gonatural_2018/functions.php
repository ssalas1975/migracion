<?php if (!defined('ABSPATH')) {
    exit; // exit if accessed directly
}

/**
 * Default Theme Constants
 *
 * @since 1.0
 */

define('UT_THEME_NAME', 'Brooklyn');
define('UT_THEME_VERSION', '4.2.5');

/* Unite Framework 
   if you are looking for scripts enqueue or other stuff which is usually 
   located inside this file please have a look into unite-custom folder 
*/
require('unite/unite-init.php');


/* old admin - will be removed in a future update  */
if (is_admin()) {

    /* option tree loader */
    include(THEME_DOCUMENT_ROOT . '/admin/ot-loader.php');

    include(THEME_DOCUMENT_ROOT . '/admin/ut-admin-loader.php');

    /* layout loader for demo importer */
    include(get_template_directory() . '/admin/ut-layout-loader.php');

    /* theme demo importer */
    include(THEME_DOCUMENT_ROOT . '/admin/ut-demo-importer.php');
}


/**
 * Visual Composer Config
 *
 * @since     4.0
 */

include(THEME_DOCUMENT_ROOT . '/vc/vc-config.php');


/**
 * Woocommerce
 */

if (ut_is_plugin_active('woocommerce/woocommerce.php')) {

    /* remove default woocommerce content wrapper */
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
}


/**
 * Default Content Width
 */

if (!isset($content_width)) {
    $content_width = 1170; /* pixels */
}


/**
 * Load required files
 */
include(THEME_DOCUMENT_ROOT . '/inc/sidebars/index.php');   /* deprecated */

include(THEME_DOCUMENT_ROOT . '/inc/ut-image-resize.php');  /* deprecated - will be moved to new core plugin in a future update */
include(THEME_DOCUMENT_ROOT . '/inc/ut-theme-postformat-tools.php');

include(THEME_DOCUMENT_ROOT . '/inc/ut-prepare-header.php');
include(THEME_DOCUMENT_ROOT . '/inc/ut-prepare-front-page.php');
include(THEME_DOCUMENT_ROOT . '/inc/ut-prepare-csection.php');
include(THEME_DOCUMENT_ROOT . '/inc/ut-section-player.php');

/**
 * Load required files depending on site type
 */
if (ot_get_option('ut_site_layout', 'onepage') == 'onepage') {

    include(THEME_DOCUMENT_ROOT . '/inc/ut-prepare-hero-onepage.php');
    include(THEME_DOCUMENT_ROOT . '/inc/ut-menu-walker-onepage.php');
} else {

    include(THEME_DOCUMENT_ROOT . '/inc/ut-prepare-hero-multisite.php');
    include(THEME_DOCUMENT_ROOT . '/inc/ut-menu-walker.php');
}

/**
 * deprecated
 */
include_once(THEME_DOCUMENT_ROOT . '/inc/ut-custom-css.php');

/*
 * Cutom JavaScript
 * 
 * can be placed within the child theme 
 */
if (file_exists(STYLE_DOCUMENT_ROOT . '/inc/ut-custom-js.php')) {

    /* file inside child theme */
    include_once(STYLE_DOCUMENT_ROOT . '/inc/ut-custom-js.php');
} else {

    /* file inside main theme */
    include_once(THEME_DOCUMENT_ROOT . '/inc/ut-custom-js.php');
}


/*
|--------------------------------------------------------------------------
| WordPress Customizer
|--------------------------------------------------------------------------
*/
require get_template_directory() . '/inc/ut-customizer.php';


/*
|--------------------------------------------------------------------------
| Recognized Font Families
|--------------------------------------------------------------------------
*/

if (!function_exists('ut_recognized_families')) {

    function ut_recognized_families($basefonts)
    {

        $newfonts = array(
            "ralewayextralight"     => "Raleway Extralight",
            "ralewaylight"          => "Raleway Light",
            "ralewayregular"        => "Raleway Regular",
            "ralewaymedium"         => "Raleway Medium",
            "ralewaysemibold"       => "Raleway Semibold",
            "ralewaybold"           => "Raleway Bold"
        );

        $basefonts = array_merge($basefonts, $newfonts);
        return $basefonts;
    }
}

/*
|--------------------------------------------------------------------------
| Custom Search Form 
| due to WP echo on get_search_form Bug we need to make use a filter
|--------------------------------------------------------------------------
*/
if (!function_exists('ut_searchform_filter')) {

    function ut_searchform_filter($form)
    {

        $searchform = '<form role="search" method="get" class="search-form" id="searchform" action="' . esc_url(home_url('/')) . '">
        <label>
            <span>' . __('Search for:', 'unitedthemes') . '</span>
            <input type="search" class="search-field" placeholder="' . esc_attr__('To search type and hit enter', 'unitedthemes') . '" value="' . esc_attr(get_search_query()) . '" name="s" title="' . __('Search for:', 'unitedthemes') . '">
        </label>
        <input type="submit" class="search-submit" value="' . esc_attr__('Search', 'unitedthemes') . '">
        </form>';

        return $searchform;
    }

    add_filter('get_search_form', 'ut_searchform_filter');
}


/*
|--------------------------------------------------------------------------
| helper function : return image ID by URL
|--------------------------------------------------------------------------
*/
if (!function_exists('ut_get_image_id')) {

    function ut_get_image_id($image_url)
    {

        global $wpdb;

        if (empty($image_url)) {
            return;
        }

        $prefix = $wpdb->prefix;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $image_url));

        return isset($attachment[0]) ? $attachment[0] : '';
    }
}

/*
|--------------------------------------------------------------------------
| helper function : returns needed meta data of the current page
|--------------------------------------------------------------------------
*/

if (!function_exists('ut_return_meta')) {

    function ut_return_meta($key = '')
    {

        if (empty($key)) {
            return;
        }

        global $post, $wp_query;

        // woo commerce shop ID
        if (function_exists('is_shop')) {

            if (is_shop()) {
                $pageID = get_option('woocommerce_shop_page_id');
            }
        }

        // blog page ID
        if (is_home() || ut_is_blog_related()) {

            $pageID = get_option('page_for_posts');

            // all other pages
        } else {

            $pageID = (isset($wp_query->post)) ? $wp_query->post->ID : NULL;
        }

        if (!empty($key)) {

            return get_post_meta($pageID, $key, true);
        } else {

            return get_post_meta($pageID);
        }
    }
}




/*
|--------------------------------------------------------------------------
| helper function : default menu output
|--------------------------------------------------------------------------
*/
if (!function_exists('ut_default_menu')) {
    function ut_default_menu()
    {
        require_once(THEME_DOCUMENT_ROOT . '/inc/ut-default-menu.php');
    }
}


/*
|--------------------------------------------------------------------------
| helper function : QTranslate Quicktags Support for Meta and Theme Options
|--------------------------------------------------------------------------
*/
if (!function_exists('ut_translate_meta')) :

    function ut_translate_meta($content)
    {

        if (function_exists('qtrans_useCurrentLanguageIfNotFoundShowAvailable')) {
            return qtrans_useCurrentLanguageIfNotFoundShowAvailable($content);
        }

        if (function_exists('ppqtrans_useCurrentLanguageIfNotFoundShowAvailable')) {
            return ppqtrans_useCurrentLanguageIfNotFoundShowAvailable($content);
        }

        if (function_exists('qtranxf_useCurrentLanguageIfNotFoundShowAvailable')) {
            return qtranxf_useCurrentLanguageIfNotFoundShowAvailable($content);
        }

        return $content;
    }

endif;


/*
|--------------------------------------------------------------------------
| Helper Function: create background video player
|--------------------------------------------------------------------------
*/

if (!function_exists('ut_dynamic_conditional')) :

    function ut_dynamic_conditional($option = '')
    {

        if (empty($option)) {
            return false;
        }

        $dynamic_for = ot_get_option($option);
        $dynamic_match = false;

        if (!empty($dynamic_for) && is_array($dynamic_for)) {

            foreach ($dynamic_for as $key => $conditional) {

                if ($conditional() && $conditional != 'is_singular') {

                    $dynamic_match = true;

                    /* front page gets handeled as a page too */
                    if ($conditional == 'is_page' && is_front_page()) {

                        $dynamic_match = false;
                    } elseif ($conditional == 'is_single' && is_singular('portfolio')) {

                        $dynamic_match = false;
                    } else {

                        /* we have a match , so we can stop the loop */
                        break;
                    }
                }

                if ($conditional('portfolio') && $conditional == 'is_singular') {

                    $dynamic_match = true;
                    break;
                }
            }
        }

        return $dynamic_match;
    }

endif;





if (!function_exists('ut_installation_note')) :

    function ut_installation_note()
    { ?>

        <div class="grid-container section-content">

            <div class="grid-100 mobile-grid-100 tablet-grid-100">

                <div class="section-content">
                    <div class="ut-install-note">

                        <h2><?php _e('Setup Information', 'unitedthemes'); ?></h2>

                        <p>
                            <?php _e('Thank you for purchasing our theme. We hope you enjoy our product! If you have any questions that are beyond the scope of the help file, please feel free to contact us on our Support Forum at.', 'unitedthemes'); ?>
                            <a href="http://support.unitedthemes.com/">http://support.unitedthemes.com</a>
                        </p>

                        <p>
                            <?php _e('Information: There are no Pages are assigned to the menu yet or the assigned pages are not set to menutype "Section"! Please read the delivered documentation carefully. We recommend to start with the "Start from Scratch Setup" documentation part.', 'unitedthemes'); ?> <br />
                        </p>

                        <p><strong><?php _e('Useful links to start with:', 'unitedthemes'); ?></strong></p>

                        <ul>
                            <li><a href="<?php echo home_url(); ?>/wp-admin/themes.php?page=install-required-plugins"><?php _e('Install required plugins', 'unitedthemes'); ?></a></li>
                            <li><a href="<?php echo home_url(); ?>/wp-admin/customize.php"><?php _e('Customize Theme', 'unitedthemes'); ?></a></li>
                            <li><a href="<?php echo home_url(); ?>/wp-admin/themes.php?page=ot-theme-options"><?php _e('Theme Options', 'unitedthemes'); ?></a></li>
                            <li><a href="<?php echo home_url(); ?>/wp-admin/nav-menus.php"><?php _e('Set Up Your Menu', 'unitedthemes'); ?></a></li>
                        </ul>
                    </div>

                </div>

            </div>

        <?php }

endif;


/*
|--------------------------------------------------------------------------
| Helper Function: returns sidebar id for current page
|--------------------------------------------------------------------------
*/
if (!function_exists('ut_get_sidebar_settings')) {

    function ut_get_sidebar_settings()
    {

        /* create sidebar settings array */
        $sidebar_settings = array();

        /* assign primary sidebar */
        $sidebar_settings['primary_sidebar'] = ut_return_meta('ut_select_sidebar');

        /* return sidebar config */
        return $sidebar_settings;
    }
}


/*
|--------------------------------------------------------------------------
| Helper Function: hatom microformat data
|--------------------------------------------------------------------------
*/
if (!function_exists('add_brooklyn_hatom_data')) {

    function add_brooklyn_hatom_data($content)
    {

        if (is_admin()) {
            return;
        }

        if (is_home() || is_singular() || is_archive()) {

            $content .= '<div class="brookly-hatom-data" style="display:none;visibility:hidden;">';
            $content .= '<span class="entry-title">' . get_the_title() . '</span>';
            $content .= '<span class="updated"> ' . get_the_modified_time('F jS, Y') . '</span>';
            $content .= '<span class="author vcard"><span class="fn">' . get_the_author() . '</span></span>';
            $content .= '</div>';
        }

        return $content;
    }

    add_filter('the_content', 'add_brooklyn_hatom_data');
}

/*
|--------------------------------------------------------------------------
| Helper Function: removes numerics from section ID's and replaces them 
| to avoid CSS styling issues
|--------------------------------------------------------------------------
*/
if (!function_exists('ut_clean_section_id')) {

    function ut_clean_section_id($slug)
    {

        /* check if slug contains any numbers */
        if (!preg_match('/[0-9]/', $slug)) {
            return $slug;
        }

        $slug    = str_split($slug);
        $newslug = '';

        $dictionary  = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine'
        );

        foreach ($slug as $char) {

            if (ctype_digit($char)) {

                $newslug .= $dictionary[$char];
            } else {

                $newslug .= $char;
            }
        }

        return $newslug;
    }
}



/******************** GONATURAL *********************************************/
switch_to_blog(get_current_blog_id());

add_action('wp_enqueue_scripts', 'custom_add_shortcode_on_enqueue', 20);
function custom_add_shortcode_on_enqueue() {
    if (function_exists('wpcf7_add_shortcode')) {
        wpcf7_add_shortcode('event', 'custom_shortcode_handler');
    }
}
function custom_shortcode_handler($atts)
{
    $idpost = $atts[values][0];
    $evento = get_post_meta($idpost, 'data_event');
    $users = get_users(array('meta_key' => 'idevento'));
    $count = 0;
    foreach ($users as $user) {
        $data = get_user_meta($user->ID, 'idevento');
        //print_r($data);
        if (in_array($idpost, $data[0])) {
            $count++;
        }
    }
    $totalpax = $evento[0]['asistentes'];
    if ($totalpax - $count > 0) {
        return '<p class="checkBox clases">
            <font class="verde">' . $evento[0]["hora"] . 'H</font> - ' . $evento[0]['nombre'] . ' ' . $evento[0]["docente"] . '
            <span class="wpcf7-form-control-wrap checkbox-455"><span class="wpcf7-form-control wpcf7-checkbox">
            <span class="wpcf7-list-item first last"><label><span class="wpcf7-list-item-label"></span>&nbsp;
            <input type="checkbox" name="idevento[]"  value="' . $atts[values][0] . '"></label></span></span></span></p>';
    } else {
        return '<p class="checkBox clases">
<font style="color: #a1a1a1; display: block; width: 75%">' . $evento[0]["hora"] . 'H - ' . $evento[0]['nombre'] . ' ' . $evento[0]["docente"] . '</font>
<aviso style="background: #e9e9e9; padding: 5px; float: right; margin-top: -20px">AFORO COMPLETO</aviso>
</p> ';
    }
}


switch_to_blog(get_current_blog_id());

//  add_action("wpcf7_before_send_mail", "wpcf7_do_something_else");  
//  function wpcf7_do_something_else( $cf7 ) {
//       $submission = WPCF7_Submission::get_instance();

//     if ( $submission ) {
//        $posted_data = $submission->get_posted_data();
//       var_dump( $posted_data );
//     } 
//  }


function create_user_from_registration($cfdata)
{

    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        $formdata = $cfdata->posted_data;
    }
    $password = wp_generate_password(12, false);
    $email = $formdata["your-email"];
    $name = $formdata["your-name"];
    $valordato = $formdata["idevento"];
    // Construct a username from the user's name
    $username = strtolower(str_replace(' ', '', $name));
    $name_parts = explode(' ', $name);
    $last_name = $formdata["your-surname"];
    $comonosconocio = $formdata['checkbox-454'];
    if (!email_exists($email)) {
        // Find an unused username
        $username_tocheck = $username;
        $i = 1;
        while (username_exists($username_tocheck)) {
            $username_tocheck = $username . $i++;
        }
        $username = $username_tocheck;
        // Create the user
        $userdata = array(
            'user_login' => $username,
            'user_pass' => $password,
            'user_email' => $email,
            'nickname' => reset($name_parts),
            'display_name' => $name,
            'first_name' => reset($name_parts),
            'last_name' => $last_name,
            'role' => $cf7frr
        );
        $user_id = wp_insert_user($userdata);
        if ($valordato != null) {
            add_user_meta($user_id, 'idevento', $valordato);
        }

        update_user_meta($user_id, 'comonosconocio', $comonosconocio);
    } else {
        $user_id = get_user_by('email', $email);
        if ($valordato != null) {
            $data = get_user_meta($user_id->ID, 'idevento', true);
            if (empty($data)) {
                add_user_meta($user_id->ID, 'idevento', $valordato);
            } else {

                foreach ($valordato as $valor) {
                    if (in_array($valor, $data)) {
                        //update_user_meta( $user_id->ID, 'idevento', $data );
                    } else {
                        array_push($data, $valor);
                    }
                }

                update_user_meta($user_id->ID, 'idevento', $data);
                update_user_meta($user_id->ID, 'comonosconocio', $comonosconocio);
            }
        }
    }

    // if ( get_current_blog_id() == 11 ){

    //     $mailContent = '
    //     <html>
    //         <head>
    //         <meta charset="UTF-8">
    //         <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    //         <style type="text/css">
    //         body,td,th {
    //             font-family: Poppins, sans-serif;
    //             font-size: 16px;
    //             text-align: center;
    //             color: #888888;
    //             font-weight: normal;
    //         }
    //         a:link {
    //             text-decoration: none;
    //         }
    //         a:visited {
    //             text-decoration: none;
    //         }
    //         a:hover {
    //             text-decoration: none;
    //         }
    //         a:active {
    //             text-decoration: none;
    //         }
    //         </style>
    //         </head>
    //         <body>
    //         <p align="center"><img src="https://gonatural.mujerhoy.com/wp-content/uploads/sites/11/2019/05/logo.png" width="400"></p>
    //         <p align="center">¡Hola!</p>
    //         <p align="center"> Te damos la bienvenida a <strong style="color: #6FB140">#mujerhoyGONatural</strong>!<br>
    //         Ven y disfruta de un día lleno de actividades saludables e inspiradoras ¡para todas!
    //         </p>

    //         <p align="center" style="margin-top: 50px; margin-bottom: 50px"><a href="https://gonatural.mujerhoy.com/wp-content/uploads/sites/11/2018/05/Invitacion.pdf" style="padding: 10px; background-color: #6FB140; color: white; ">DESCARGA AQUÍ TU INVITACIÓN</a></p>
    //         ';

    //     if( $valordato != null || $valordato != "" || isset($formdata["idevento"]) ){    
    //        $mailContent .= ' 
    //         <p align="center">Te confirmamos tu plaza en:</p>
    //         <div align="center" style="color: #6FB140">
    //        ';

    //        foreach( $valordato as $eventos){
    //             $evento = get_post_meta( $eventos, 'data_event', true );
    //             //$evento['docente']
    //             //$evento['hora']
    //             $mailContent .= cleanString($evento['hora']).'   '.cleanString(strtoupper($evento['nombre'])).'<br>';
    //         }

    //         $mailContent .= '
    //         </div>
    //         <p align="center">¡Recuerda!</p>
    //             <p align="center" style="font-size: 10px; line-height: 12px; margin-bottom: 40px">*Ven con ropa deportiva o cómoda<br>*Contarás con el material deportivo necesario para la realización de las clases<br>*Si te surge algo y no puedes venir ¡avísanos! Otra persona podrá ocupar tu plaza. Estamos en: eventosmujerhoy@vocento.com</p>
    //         ';
    //     }
    //         $mailContent .= '
    //         <p align="center">¡Te esperamos!<br><strong style="color: #000">Equipo mujerhoy</strong></p>

    //         <p align="center"><strong style="color: #6FB140"><a href="http://gonatural.mujerhoy.com/" style="color: #6FB140">gonatural.mujerhoy.com<br>#mujerhoyGONatural<br>#tesientabien</a></strong></p>
    //         </body>
    //     </html>
    //     ';

    //     $mail = $cfdata->prop( 'mail' );
    //     $mail_2 = $cfdata->prop( 'mail_2' );
    //     $mail['body'] = $mailContent;
    //     $mail_2['body'] = $mailContent;
    //     $mail["sender"] = "GO NATURAL <noresponder@gonatural.mujerhoy.com>";
    //     $mail_2["sender"] = "GO NATURAL <noresponder@gonatural.mujerhoy.com>";
    //     if( $valordato != null || $valordato != "" || isset($formdata["idevento"]) ){  
    //         $mail_2['subject'] = 'INVITACIÓN/CONFIRMACIÓN CLASE mujerhoy GO NATURAL - 29 junio 2019';
    //     }else{
    //         $mail_2['subject'] = 'INVITACIÓN mujerhoy GO NATURAL - 29 junio 2019';
    //     }
    //     // set mail property with changed value(s)
    //     $cfdata->set_properties( array( 'mail' => $mail ) );
    //     $cfdata->set_properties( array( 'mail_2' => $mail_2 ) );
    // }


    if (get_current_blog_id() == 11) {

        $mailContent = '
        <html>
            <head>
            <meta charset="UTF-8">
            <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
            <style type="text/css">
            body,td,th {
                font-family: Poppins, sans-serif;
                font-size: 16px;
                text-align: center;
                color: #888888;
                font-weight: normal;
            }
            a:link {
                text-decoration: none;
            }
            a:visited {
                text-decoration: none;
            }
            a:hover {
                text-decoration: none;
            }
            a:active {
                text-decoration: none;
            }
            </style>
            </head>
            <body>
            <p align="center"><img src="https://gonatural.mujerhoy.com/wp-content/uploads/sites/11/2019/05/logo.png" width="400"></p>
            <p align="center">¡Hola!</p>
            <p align="center"> Te damos la bienvenida a <strong style="color: #6FB140">#mujerhoyGONatural</strong>!<br>
            Ven y disfruta de un día lleno de actividades saludables e inspiradoras ¡para todas!
            </p>

            <p align="center" style="margin-top: 50px; margin-bottom: 50px"><a href="https://gonatural.mujerhoy.com/wp-content/uploads/sites/11/2018/05/Invitacion.pdf" style="padding: 10px; background-color: #6FB140; color: white; ">DESCARGA AQUÍ TU INVITACIÓN</a></p>
            ';

        if ($valordato != null || $valordato != "" || isset($formdata["idevento"])) {
            $mailContent .= ' 
            <p align="center">En breve recibirás un email confirmando la disponibilidad de plaza </p>
            <div align="center" style="color: #6FB140">
           ';

            foreach ($valordato as $eventos) {
                $evento = get_post_meta($eventos, 'data_event', true);
                //$evento['docente']
                //$evento['hora']
                $mailContent .= cleanString($evento['hora']) . '   ' . cleanString(strtoupper($evento['nombre'])) . '<br>';
            }

            $mailContent .= '
            </div>
            <p align="center">¡Recuerda!</p>
                <p align="center" style="font-size: 10px; line-height: 12px; margin-bottom: 40px">*Ven con ropa deportiva o cómoda<br>*Contarás con el material deportivo necesario para la realización de las clases<br>*Si te surge algo y no puedes venir ¡avísanos! Otra persona podrá ocupar tu plaza. Estamos en: eventosmujerhoy@vocento.com</p>
            ';
        }
        $mailContent .= '
            <p align="center">¡Te esperamos!<br><strong style="color: #000">Equipo mujerhoy</strong></p>

            <p align="center"><strong style="color: #6FB140"><a href="http://gonatural.mujerhoy.com/" style="color: #6FB140">gonatural.mujerhoy.com<br>#mujerhoyGONatural<br>#tesientabien</a></strong></p>
            </body>
        </html>
        ';

        $mail = $cfdata->prop('mail');
        $mail_2 = $cfdata->prop('mail_2');
        $mail['body'] = $mailContent;
        $mail_2['body'] = $mailContent;
        $mail["sender"] = "GO NATURAL <noresponder@gonatural.mujerhoy.com>";
        $mail_2["sender"] = "GO NATURAL <noresponder@gonatural.mujerhoy.com>";
        if ($valordato != null || $valordato != "" || isset($formdata["idevento"])) {
            $mail_2['subject'] = 'INVITACIÓN mujerhoy GO NATURAL - 29 junio 2019';
        } else {
            $mail_2['subject'] = 'INVITACIÓN mujerhoy GO NATURAL - 29 junio 2019';
        }
        // set mail property with changed value(s)
        $cfdata->set_properties(array('mail' => $mail));
        $cfdata->set_properties(array('mail_2' => $mail_2));
    }


    return $cfdata;
}

add_action('wpcf7_before_send_mail', 'create_user_from_registration', 1, 2);

function cleanString($text)
{
    $utf8 = array(
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚``´´]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}


function wp_delete_post_link($post)
{
    $link = wp_nonce_url(get_bloginfo('url') . "/wp-admin/post.php?action=delete&amp;post=" . $post, 'delete-post_' . $post);
    return $link;
}



add_action('init', 'init_register_post_type_evento');

function init_register_post_type_evento()
{

    register_post_type('evento', array(
        'plural_name' => 'Eventos',
        'singular_name' => 'Evento',
        'post_type_name' => 'evento',
        'description' => 'Evnetos',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'rewrite' =>
        array(
            'slug' => 'evento',
            'with_front' => false,
            'feeds' => true,
            'pages' => true,
        ),
        'show_in_rest' => false,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'can_export' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => true,
        'show_under_a_parent' => '',
        'show_under_parent' => '',
        'menu_position' => 5,
        'menu_position_custom' => '',
        'menu_icon' => 'dashicons-admin-post',
        'show_in_admin_bar' => true,
        'auto_generate_labels' => '1',
        'label_add_new' => 'Add New',
        'label_add_new_item' => 'Add New Evento',
        'label_edit_item' => 'Edit Evento',
        'label_new_item' => 'New Evento',
        'label_view_item' => 'View Evento',
        'label_search_items' => 'Search Eventos',
        'label_not_found' => 'No eventos found',
        'label_not_found_in_trash' => 'No eventos found in Trash',
        'label_parent_item_colon' => 'Parent Evento',
        'label_all_items' => 'All Eventos',
        'label_archives' => 'Eventos Archives',
        'label_insert_into_item' => 'Insert into evento',
        'label_uploaded_to_this_item' => 'Uploaded to this evento',
        'label_featured_image' => 'Featured Image',
        'label_set_featured_image' => 'Set featured image',
        'label_remove_featured_image' => 'Remove featured image',
        'label_use_featured_image' => 'Use as featured image',
        'label_menu_name' => 'Eventos',
        'label_filter_items_list' => 'Eventos',
        'label_items_list_navigation' => 'Eventos',
        'label_items_list' => 'Eventos',
        'label_name_admin_bar' => 'Evento',
        'rewrite_with_front' => false,
        'rewrite_slug' => 'evento',
        'rewrite_feeds' => true,
        'rewrite_pages' => true,
        'rest_base' => NULL,
        'rest_controller_class' => NULL,
        'supports' =>
        array(
            0 => 'title',
        ),
        'taxonomies' =>
        array(
            0 => '',
        ),
        'label' => 'Eventos',
        'title_placeholder' => 'Enter evento name',
        'labels' =>
        array(
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Evento',
            'edit_item' => 'Edit Evento',
            'new_item' => 'New Evento',
            'view_item' => 'View Evento',
            'search_items' => 'Search Eventos',
            'not_found' => 'No eventos found',
            'not_found_in_trash' => 'No eventos found in Trash',
            'parent_item_colon' => 'Parent Evento',
            'all_items' => 'All Eventos',
            'archives' => 'Eventos Archives',
            'insert_into_item' => 'Insert into evento',
            'uploaded_to_this_item' => 'Uploaded to this evento',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'menu_name' => 'Eventos',
            'filter_items_list' => 'Eventos',
            'items_list_navigation' => 'Eventos',
            'items_list' => 'Eventos',
            'name_admin_bar' => 'Evento',
        ),
        'dashicon_unicode_number' => 109,
    ));
}

add_action('init', 'gonatural_register_meta_fields');

function gonatural_register_meta_fields()
{
    register_meta('evento', 'data_event', [
        'description' => _x('Nombre del evento', 'meta description', 'cyb-textdomain'),
        'single' => true
    ]);
}

add_action('add_meta_boxes', 'gn_meta_boxes');

function gn_meta_boxes()
{
    add_meta_box('gonatural-meta-box', 'Datos del Evento', 'gn_nombre_callback', 'evento');
}

function gn_nombre_callback($post)
{
    $current_value = get_post_meta($post->ID, 'data_event', true);
    wp_nonce_field('gn_meta_box', 'gn_meta_box_noncename');
        ?>
        <p>
            <label class="label" for="gn_name"><?php _e('Nombre del Evento', 'gn_textdomain'); ?></label>
            <input name="gn_name" id="gn_name" type="text" value="<?php echo $current_value['nombre']; ?>">
        </p>

        <p>
            <label class="label" for="gn_docente"><?php _e('Docente', 'gn_textdomain'); ?></label>
            <input name="gn_docente" id="gn_docente" type="text" value="<?php echo $current_value["docente"]; ?>">
        </p>

        <p>
            <label class="label" for="gn_hora"><?php _e('Introduce tu nombre', 'gn_textdomain'); ?></label>
            <input name="gn_hora" id="gn_hora" type="text" value="<?php echo $current_value["hora"]; ?>">
        </p>

        <p>
            <label class="label" for="gn_asistentes"><?php _e('Introduce tu nombre', 'gn_textdomain'); ?></label>
            <input name="gn_asistentes" id="gn_asistentes" type="text" value="<?php echo $current_value["asistentes"]; ?>">
        </p>
        <?php
    }

    add_action('save_post', 'gn_save_custom_fields', 10, 2);
    function gn_save_custom_fields($post_id, $post)
    {
        if (!isset($_POST['gn_meta_box_noncename']) || !wp_verify_nonce($_POST['gn_meta_box_noncename'], 'gn_meta_box')) {
            return;
        }
        if (isset($_POST['gn_name']) && $_POST['gn_name'] != "") {
            $temp = array();
            $temp['docente'] = $_POST['gn_docente'];
            $temp['nombre'] = $_POST['gn_name'];
            $temp['hora'] = $_POST['gn_hora'];
            $temp['asistentes'] = $_POST['gn_asistentes'];
            update_post_meta($post_id, 'data_event', $temp);
        }
    }

    add_filter('wpcf7_validate_configuration', '__return_false');

    /*
* Añade boton a administración de usuarios justo debajo del menú superior
*/
    add_action('admin_footer', 'add_club_filter_to_user_listing');
    function add_club_filter_to_user_listing()
    {

        global $pagenow;

        if ($pagenow == "users.php") {

        ?>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery(".tablenav.top").append("<div class='alignleft actions' style='margin: 1%;'><a href='<?php echo plugin_dir_url(); ?>gonatural-events/exportusersuscriber.php' class='exportar'><input type='button' name='exportUsers' id='exportUsers' class='button' value='Listado Asistentes'></a></div><br class='clear'><div class='alignleft actions' style='margin: 1%;'><a href='<?php echo plugin_dir_url(); ?>gonatural-events/register-2-excel.php' class='exportar'><input type='button' name='exportUsers' id='exportUsers' class='button' value='Listado Usuarios Clases'></a></div><br class='clear'>");

                    /*jQuery("#exportUsers").live("click", function(){
								   alert("Exportando usuarios...");
								   //si quieres redirigir..
								   //window.location = "/wp-admin/users.php?club="+club_id_value;
				   });*/
                });
            </script>
    <?php
        }
    }

    /*
* Permite a los usuarios administradores editar los usuarios del site
*/
    function mc_admin_users_caps($caps, $cap, $user_id, $args)
    {

        foreach ($caps as $key => $capability) {

            if ($capability != 'do_not_allow')
                continue;

            switch ($cap) {
                case 'edit_user':
                case 'edit_users':
                    $caps[$key] = 'edit_users';
                    break;
                case 'delete_user':
                case 'delete_users':
                    $caps[$key] = 'delete_users';
                    break;
                case 'create_users':
                    $caps[$key] = $cap;
                    break;
            }
        }
        return $caps;
    }
    //indicar el ID del blog para permitar la edicion de usuarios a administradores
    if (get_current_blog_id() == 11) {

        add_filter('map_meta_cap', 'mc_admin_users_caps', 10, 4);

        remove_all_filters('enable_edit_any_user_configuration');
        add_filter('enable_edit_any_user_configuration', '__return_true');

        /**
         * Checks that both the editing user and the user being edited are
         * members of the blog and prevents the super admin being edited.
         */
        function mc_edit_permission_check()
        {
            global $current_user, $profileuser;

            $screen = get_current_screen();

            get_currentuserinfo();

            if (!is_super_admin($current_user->ID) && in_array($screen->base, array('user-edit', 'user-edit-network'))) { // editing a user profile
                if (is_super_admin($profileuser->ID)) { // trying to edit a superadmin while less than a superadmin
                    wp_die(__('You do not have permission to edit this user.'));
                } elseif (!(is_user_member_of_blog($profileuser->ID, get_current_blog_id()) && is_user_member_of_blog($current_user->ID, get_current_blog_id()))) { // editing user and edited user aren't members of the same blog
                    wp_die(__('You do not have permission to edit this user.'));
                }
            }
        }
        add_filter('admin_head', 'mc_edit_permission_check', 1, 4);
    }

    add_action('admin_init', function () {
        // Redirect any user trying to access comments page
        global $pagenow;

        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }

        // Remove comments metabox from dashboard
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

        // Disable support for comments and trackbacks in post types
        foreach (get_post_types() as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    });

    // Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);

    // Remove comments page in menu
    add_action('admin_menu', function () {
        remove_menu_page('edit-comments.php');
    });

    // Remove comments links from admin bar
    add_action('init', function () {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    });
