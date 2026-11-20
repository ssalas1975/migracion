<?php
/**
 * Plugin Name: Candidatos Premios ABC Top 100
 * Description: Gestión Candidatos Premios ABC Top 100.
 * Version: 1.0
 */

define('GISTAU__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GISTAU__PLUGIN_URL', plugin_dir_url(__FILE__));

require_once(GISTAU__PLUGIN_DIR . 'admin/functions.php');
require_once(GISTAU__PLUGIN_DIR . 'admin/customCandidato.php');
require_once(GISTAU__PLUGIN_DIR . 'admin/menuCandidato.php');
require_once(GISTAU__PLUGIN_DIR . 'admin/customPublicidad.php');
require_once(GISTAU__PLUGIN_DIR . 'admin/menuPublicidad.php');
require_once(GISTAU__PLUGIN_DIR . 'admin/vc_elements.php');
