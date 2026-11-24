<?php
/**
 * Plugin Name: Candidatos Premios ABC Salud
 * Description: Gestión Candidatos Premios.
 * Version: 1.0
 */

define('ABCSALUD__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ABCSALUD__PLUGIN_URL', plugin_dir_url(__FILE__));

require_once(ABCSALUD__PLUGIN_DIR . 'admin/functions.php');
require_once(ABCSALUD__PLUGIN_DIR . 'admin/customCandidato.php');
require_once(ABCSALUD__PLUGIN_DIR . 'admin/menuCandidato.php');
