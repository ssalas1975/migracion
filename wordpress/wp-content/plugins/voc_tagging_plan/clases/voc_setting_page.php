<?php

require plugin_dir_path( __DIR__ ) . 'includes/functions/voc_function_backend.php';

class voc_setting_page
{
    function __construct()
    {
        add_action( 'admin_menu', array( $this, 'voc_add_menu_voonto' ));
        register_activation_hook( __FILE__, array( $this, 'voc_install_init' ) );
        register_deactivation_hook( __FILE__, array( $this, 'voc_uninstall_options' ) );
    }

    function voc_add_menu_voonto()
    {
        add_menu_page( 'Adobe DTM', 'Adobe DTM', 'manage_options', 'adobe-settings', 'voc_adobe_settings', '', 7);

        add_submenu_page( 'adobe-settings', 'Marcado' , 'Marcado', 'manage_options', 'marcado_analitica' ,'marcado_analitica');

    }

    function voc_install_init()
    {

    }

    function voc_uninstall_options()
    {

    }


}

new voc_setting_page();