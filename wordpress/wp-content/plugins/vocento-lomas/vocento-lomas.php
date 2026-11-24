<?php 
ob_start();

/*
Plugin Name: Vocento Lo más
Description: Generador de Lo más
Version:     1.2
Author:      Meytel
Author URI:  http://www.meytel.net
Text Domain: vocento-lomas
 */


define( 'VCLM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

//INCLUDES
include_once ('vocento-lomas/VocentoLoMasDesign.php');
include_once ('vocento-lomas/VocentoLoMasStats.php');
include_once ('vocento-lomas/VocentoLoMasGigya.php');
include_once ('vocento-lomas/VocentoLoMasAddthis.php');
include_once ('widgets/VocentoLoMasWidget.php');
include_once ('vocento-lomas/functions.php');
include_once ('services/VCLM_Stats_srv.php');
include_once ('services/VCLM_WP_srv.php');
include_once ('services/VCLM_Gigya_srv.php');
include_once ('services/VCLM_Addthis_srv.php');
//ADMIN
if(is_admin()){

	

    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

	
    


    //Añadimos menus
    add_action( 'network_admin_menu', function(){
           vclm_compose_admin_menu();
     });

    add_action( 'admin_menu', function(){
         vclm_compose_admin_menu();
   });

    //enqueue
    add_action('admin_enqueue_scripts', 'vclm_enqueue');





}

//Widget
add_action( 'widgets_init', array ( 'VCLM_Widget', 'register' ) );
add_action('admin_enqueue_scripts', array( 'VCLM_Widget', 'vocentovclm_widget_enqueue' ));
add_action('admin_enqueue_scripts',function(){
     wp_enqueue_style( 'vocentovclm_styles_widgets', plugins_url('/assets/css/style-front.css', __FILE__)  );
    wp_enqueue_style( 'vocentovclm_styles_widgets-ui', plugins_url('/assets/css/jquery-ui.css', __FILE__)  );
});

add_action('wp_enqueue_scripts', function(){

      wp_enqueue_style( 'vocentovclm_styles_widgets', plugins_url('/assets/css/style-front.css', __FILE__)  );
       wp_enqueue_style( 'vocentovclm_styles_widgets-ui', plugins_url('/assets/css/jquery-ui.css', __FILE__)  );
         wp_enqueue_script( 'ajax-script-ui-front-vclm',
				plugins_url( '/widgets/assets/js/jquery-ui.min.js', __FILE__ ),
				array('jquery')

		);
});


ob_end_flush();
?>