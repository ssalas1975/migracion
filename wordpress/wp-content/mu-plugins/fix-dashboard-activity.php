<?php
/**
 * Plugin Name: Fix Dashboard Activity Widget
 * Description: Desactiva temporalmente el widget de actividad del dashboard que causa error SQL
 */

add_action('wp_dashboard_setup', function() {
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
}, 99);
