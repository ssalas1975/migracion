<?php
    /*
        Plugin Name: Exportar contenido ContentFacgtory
        Description: Generate xml to export content
        Version:     1.0
        Author:      Vocento
        Text Domain: ExportContentContentfactory
    */

    define('PATH', __DIR__);
    if ( ! defined( 'ABSPATH' ) ){
        exit;
    }
    include_once( PATH."/class/ExportacionContenido.php");
    include_once( PATH."/helpers/functions.php");

    ob_start();
    $pxw_test_db_version = '1.0';
    
    function pxw_test_menu() {
        add_menu_page(
            'exportar_contenido',
            'Exportar Contenido',
            'manage_options',
            'exportar_contenido',
            'view_exportar_contenido',
            'dashicons-admin-home',
            5
        );
        // add_submenu_page(
        //     'exportar_contenido',
        //     'Actualizar Datos Importados',
        //     'Actualizar Datos Importados',
        //     'manage_options',
        //     'update-old-data',
        //     'actualizar_datos'
        // );
        // add_submenu_page(
        //     'exportar_contenido',
        //     'Borrar Posts',
        //     'Borrar Posts',
        //     'manage_options',
        //     'delete-posts',
        //     'delete_post'
        // );
    }

    function view_exportar_contenido()
    {
        require_once('views/viewExportacionContenido.php');
    }

    function actualizar_datos()
    {
        require_once('views/actualizarImagenes.php');
    }

    function delete_post()
    {
        require_once('views/deltepost.php');
    }
    // Finally display the menu
    add_action('admin_menu', 'pxw_test_menu');
?>