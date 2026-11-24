<?php
/**
* Plugin Name: Vocento.com: Listados
* Description: Listados de documentos por categoria.
* Version: 0.1
*/


	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    require_once dirname( __FILE__ ) . '/includes/VocentocomListadoPlugin.php';
    require_once dirname( __FILE__ ) . '/includes/VocentocomListadoAccionistasPlugin.php';
    require_once dirname( __FILE__ ) . '/includes/VocentoWidgetMenu.php';
    require_once dirname( __FILE__ ) . '/includes/VocentoWidget.php';
	require_once dirname( __FILE__ ) . '/includes/VocentoInfoMetaBox.php';
	require_once dirname( __FILE__ ) . '/includes/VocentocomLocalizaciones.php';
	

	$my_shortcode_formatos_publicdad = new VocentocomListadoPlugin();
    $my_shortcode_formatos_publicdad = new VocentocomListadoAccionistasPlugin();
	$metaBoxShortcode = new VocentoInfoMetaBox();
    //$metaBoxShortcode = new VocentoWidgetShortcodeMenu();
    //$metaBoxShortcode = new VocentoWidgetShortcode();
	$metaBoxShortcode = new VocentocomLocalizaciones();


?>

