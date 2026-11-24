<?php
/**
 * Plugin Name: Candidatos Premios Arquia
 * Description: Gestión Candidatos Premios.
 * Version: 1.0
 */

define('GISTAU__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GISTAU__PLUGIN_URL', plugin_dir_url(__FILE__));

require_once(GISTAU__PLUGIN_DIR . 'admin/functions.php');
require_once(GISTAU__PLUGIN_DIR . 'admin/customCandidato.php');
require_once(GISTAU__PLUGIN_DIR . 'admin/menuCandidato.php');
