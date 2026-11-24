<?php

/*
Plugin Name: Listado Editores
Description: Genera listado editores por blog.
Version: 1.0.0
License: GPLv2 or later
Text Domain: litadoeditores
*/

add_action('network_admin_menu', 'mipage');

function mipage() {
    add_menu_page( 'listado editores', 'listado editores', 'manage_options', 'listado_editores', 'mipagina' );
}

function mipagina() {
    global $wp_version;
    if ( version_compare( $wp_version, '4.6', '>=' ) ) {
        $sites = get_sites();
        listadoSitesUsers($sites);
    }else{
        $sites = wp_get_sites();
        listadoSitesUsers($sites);
    }

}

function listadoSitesUsers($sites) {
    echo '<h1>'.$sites[0]->domain.'</h1>';
    $total = 0;
    foreach ($sites as $site) {
        switch_to_blog( intval($site->blog_id) );
        $args = array(
            'blog_id' => $site->blog_id,
            'role'    => 'editor'
        );
        $users = get_users($args);
        foreach ($users as $user) {
            $total ++;
        }
        
        restore_current_blog();
    }
    echo 'Total: '.$total;
}