<?php 
ob_start();

/*
Plugin Name: Vocento Brightcove
Description: Generador de Brightcove Vocento
Version:     1.0
Author:      Meytel
Author URI:  http://www.meytel.net
Text Domain: vocento-brightcove
 */
 

 define( 'VCF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

//INCLUDES


include_once ('vocento-brightcove/services/brightcove/cms-api.php');
include_once ('vocento-brightcove/admin/media/vocento-media-bc-upload.php');
include_once ('vocento-brightcove/admin/media/vocento-media-bc-select.php');
include_once ('vocento-brightcove/admin/media/vocento-media-bc-edit.php');
include_once ('vocento-brightcove/admin/media/vocento-media-bc-upload-page.php');
include_once ('vocento-brightcove/admin/network/vocento-network-bc-add-medio.php');
include_once ('vocento-brightcove/admin/network/vocento-network-bc-list-medio.php');
include_once ('vocento-brightcove/admin/network/vocento-network-bc-settings.php');
include_once ('vocento-brightcove/admin/site/vocento-site-bc-add-medio.php');
include_once ('vocento-brightcove/admin/site/vocento-site-bc-list-medio.php');
include_once ('vocento-brightcove/admin/site/vocento-site-bc-settings.php');
include_once ('vocento-brightcove/shortcodes/vcbc-shortcode.php');
include_once ('vocento-brightcove/functions.php');


//ADMIN
if(is_admin()){

   
    //Añadimos menu
    add_action( 'network_admin_menu', function(){
           vcbc_compose_admin_menu();
     });

    add_action( 'admin_menu', function(){
         vcbc_compose_admin_menu();
   });


    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }


    add_action('admin_enqueue_scripts', 'vcbc_enqueue');


    add_filter( 'media_upload_tabs', 'vcbc_add_video_tab' );

  
    add_action( 'media_upload_vcbc_media', 'vcbc_add_video_tab_content' );


    add_action( 'wp_ajax_vcbc_methode_sections', 'vcbc_ajax_methode_sections' );

    add_action( 'wp_ajax_vcbc_edit_video', 'vcbc_ajax_vcbc_edit_video' );

    add_action( 'wp_ajax_vcbc_edit_video_post', 'vcbc_ajax_vcbc_edit_video_post' );

}

//hook de cabecera para ver si tenemos que añadir o no los ficheros de js de vocento
add_action('wp_head','vcbc_header_handler');








ob_end_flush();
?>