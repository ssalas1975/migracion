<?php

/*
Plugin Name: Editor CSS CMS Native
Description: Editor CSS CMS Native
Version:     1.0
Author:      J.Godoy
Author URI:  
Text Domain: cmsnative-editor
*/

include_once ('functions.php');
include_once ('cmsnative-editor-page.php');


if(is_admin()){
	
	add_action('admin_enqueue_scripts', 'cmsnative_load_scripts');
	add_action( 'admin_menu', function(){
         cmsnative_compose_menu();
	}
);


function cmsnative_compose_menu(){	
    add_menu_page( "Editor CSS Native", "Editor CSS Native", 'manage_options', "cmsnative-editor", function(){
       $CMSNativeEditor=new CMSNativeEditor();
       $CMSNativeEditor->render_page();
   } );
}

}