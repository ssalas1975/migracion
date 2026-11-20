<?php
/*
Plugin Name: Rewrite Ajax Request Url
Plugin URI: http://www.lasprovincias.es
Description: Sobrescribe la ubicación donde se reciben las peticiones ajax de WP. De esta forma en producción se puede utilizar AJAX con cualquier plugin
Version: 3.0
Author: Raúl Gómez
Author URI: http://www.lasprovincias.es
License: GPL2
*/

/*
 * Mejoras: pagina configuracion de plugin:
 *  - En la activación guardarse la versión de WP, para chequear si hay que sobrescribir el admin-ajax
 *
 * */

define("AJAXREWRITE","ajaxrewrite.php");
register_activation_hook(__FILE__,"fnActivado");

function fnActivado(){

    $admin_path = dirname(__FILE__)."/".AJAXREWRITE;
    $content_path= ABSPATH."wp-content/".AJAXREWRITE;

    copy($admin_path,$content_path);
}


add_action("wp","lprewriteajax_init");

function lprewriteajax_init(){
    wp_register_script("lprewriteajax",plugins_url('/lprewriteajax.js',__FILE__),array('jquery'));
    wp_enqueue_script('jquery');
    wp_enqueue_script('lprewriteajax');
}


?>