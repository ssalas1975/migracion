<?php

if (!function_exists('edgtf_quadric_register_widgets')) {

	function edgtf_quadric_register_widgets() {

		$widgets = array(
			'EdgeQuadricLatestPosts',
			'EdgeQuadricSearchOpener',
			'EdgeQuadricSideAreaOpener',
			'EdgeQuadricStickySidebar',
		);

		foreach ($widgets as $widget) {
			register_widget($widget);
		}

	}

}

add_action('widgets_init', 'edgtf_quadric_register_widgets');