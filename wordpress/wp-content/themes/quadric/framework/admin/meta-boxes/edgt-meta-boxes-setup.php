<?php

add_action('after_setup_theme', 'edgtf_quadric_meta_boxes_map_init', 1);
function edgtf_quadric_meta_boxes_map_init() {
    /**
    * Loades all meta-boxes by going through all folders that are placed directly in meta-boxes folder
    * and loads map.php file in each.
    *
    * @see http://php.net/manual/en/function.glob.php
    */

    do_action('edgtf_quadric_before_meta_boxes_map');

	global $edgtf_quadric_options;
	global $edgtQuadricFramework;
	global $edgtf_quadric_options_fontstyle;
	global $edgtf_quadric_options_fontweight;
	global $edgtf_quadric_options_texttransform;
	global $edgtf_quadric_options_fontdecoration;
	global $edgtf_quadric_options_arrows_type;

    foreach(glob(EDGE_FRAMEWORK_ROOT_DIR.'/admin/meta-boxes/*/map.php') as $meta_box_load) {
        include_once $meta_box_load;
    }

	do_action('edgtf_quadric_meta_boxes_map');

	do_action('edgtf_quadric_after_meta_boxes_map');
}