<?php 
ob_start();

/*
Plugin Name: Vocento Analytics
Description: Generador de códigos Analytics de varias redes
Version:     1.3
Author:      Javier Godoy | H/T David Navia
Author URI:  http://www.meytel.net
Text Domain: vocento-analytics
 */


define( 'vcanly_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

//INCLUDES

//Classes
include_once ('vocento-analytics/classes/VocentoAnalyticsBase.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsComScore.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsChartBeat.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsAMP.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsGA.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsGTM.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsCrazyEgg.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsSoftClick.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsInspectlet.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsKrux.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsDTM.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsHOType.php');
include_once ('vocento-analytics/classes/VocentoAnalyticsContainer.php');

//Admin pages
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminBase.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminComScore.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminGA.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminGTM.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminKrux.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminAMP.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminChartBeat.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminCrazyEgg.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminWordpress.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminOptions.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminOverview.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminSoftClick.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminInspectlet.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminHelp.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminDTM.php');
include_once ('vocento-analytics/admin/VocentoAnalyticsAdminImportExport.php');

//Metabox
include_once ('vocento-analytics/metaboxes/VocentoAnalyticsMB.php');

//Front
include_once ('vocento-analytics/front/VocentoAnalyticsFrontManager.php');
include_once ('vocento-analytics/front/VocentoAnalyticsPageViewCandidate.php');

//Functions
include_once ('vocento-analytics/functions.php');

//ADMIN
if(is_admin()){

    if (! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    //Añadimos menus
    add_action( 'network_admin_menu', function(){
           vcanly_compose_admin_menu();
     });

    add_action( 'admin_menu', function(){
         vcanly_compose_admin_menu();
   });

    //enqueue
    add_action('admin_enqueue_scripts', 'vcanly_enqueue');

    //Metaboxes
    new VocentoAnalyticsMB();

}
else{

    new VocentoAnalyticsFrontManager();
    new VocentoAnalyticsPageViewCandidate();
}




ob_end_flush();
?>