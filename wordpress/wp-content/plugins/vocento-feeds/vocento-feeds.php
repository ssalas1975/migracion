<?php 
ob_start();

/*
Plugin Name: Vocento Feeds
Description: Generador de Feeds
Version:     1.2
Author:      Meytel
Author URI:  http://www.meytel.net
Text Domain: vocento-feeds
 */
 

 define( 'VCF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

//INCLUDES


include_once ('vocento-feeds/classes/vcf-root-object.php');
if(!class_exists('XMLSerializer'))
    require_once ('vocento-feeds/classes/XML/XMLSerializer.php');
include_once ('vocento-feeds/VocentoFeedsSettings.php');
include_once ('vocento-feeds/VocentoFeedsSettingsNetwork.php');
include_once ('vocento-feeds/VocentoFeedsEdit.php');
include_once ('vocento-feeds/VocentoFeedsEditNetwork.php');
include_once ('vocento-feeds/functions.php');

//ADMIN
if(is_admin()){

	

    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

	
    if(is_network_admin() && is_plugin_active_for_network('vocento-feeds/vocento-feeds.php')){
       
            switch($_GET["action"]){
                case "edit": 
					 if($_GET["page"] && $_GET["page"]=="network_vcf_settings"){
						$settings= new VCF_Edit_Settings();
					 }				
					break;
                default: $settings= new VCF_Network_Settings(); break;
            }
        
    }
    else{
		if(isset($_GET["action"])){
            switch($_GET["action"]){
                case "edit": 
				 if($_GET["page"] && $_GET["page"]=="vocentovcf_settings"){
					$settings= new VCF_Edit();
				 }
				 break;
                default: $settings= new VCF_Settings(); break;
            }
		}
		else{
			
			$settings= new VCF_Settings();
		}
        
    }

    //Check uploads folder
    if(!file_exists(WP_CONTENT_DIR .'/uploads/vocento-feeds/')){

        mkdir(WP_CONTENT_DIR .'/uploads/vocento-feeds/');

    }

    //Check uploads folder
    if(!file_exists(WP_CONTENT_DIR .'/uploads/vocento-feeds/')){

        mkdir(WP_CONTENT_DIR .'/uploads/vocento-feeds/');

    }

    add_action( 'wp_ajax_vcfpreview_xslt', 'vocentovcf_preview_xslt_callback'  );

    function vocentovcf_preview_xslt_callback() {
		if($_POST['action']=="vcfpreview_xslt"){
			if($_POST['xslt']){
                $xslt_text=str_replace('\"','"',$_POST['xslt']);
                $xslt_text=str_replace("\'","'",$xslt_text);
			    $proccessedXML=process_xsl_text(str_replace('\"','"',$xslt_text),get_sample_xml_raw());
				ob_clean();
				if($proccessedXML)
					echo $proccessedXML;
			}
			
			die();
		}		
    }


    function vcf_enqueue($hook) {
        
        wp_enqueue_script( 'ajax-script-ui',
                    plugins_url( '/assets/js/jquery-ui.min.js', __FILE__ ),
                    array('jquery')
        );
        
        wp_enqueue_script( 'datatables',
                    plugins_url( '/assets/js/jquery.dataTables.min.js', __FILE__ ),
                    array('jquery')
        );
        
        wp_enqueue_style( 'vocentotps_feeds_styles', plugins_url('/assets/css/style.css', __FILE__)  );

        wp_enqueue_style( 'vocentotps_datatable', plugins_url('/assets/css/jquery.dataTables.min.css', __FILE__)  );
        
    }

    
	
	add_action('admin_enqueue_scripts', 'vcf_enqueue');
}




ob_end_flush();
?>