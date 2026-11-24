<?php
	if(!function_exists('edgtf_quadric_layerslider_overrides')) {
		/**
		 * Disables Layer Slider auto update box
		 */
		function edgtf_quadric_layerslider_overrides() {
			$GLOBALS['lsAutoUpdateBox'] = false;
		}

		add_action('layerslider_ready', 'edgtf_quadric_layerslider_overrides');
	}
?>