<?php

class ProcesarPostContent
{
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'menuGestionLoMasVisto' ));
    }


    public function menuGestionLoMasVisto()
    {
        add_menu_page(
            'procesar_content',
            'Procesar Content Post',
            'manage_options',
            'procesar_content',
            array( $this, 'view_procesar_content' ),
            'dashicons-admin-home',
            5
        );

    }

    public function view_procesar_content()
    {
        require_once('views/viewProcesarContent.php');
    }

}


if (is_admin()) {
    $procesarcontent = new ProcesarPostContent();
}
