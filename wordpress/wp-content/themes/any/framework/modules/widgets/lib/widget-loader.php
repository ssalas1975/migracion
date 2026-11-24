<?php

if (!function_exists('any_edge_register_widgets')) {

	function any_edge_register_widgets() {

		$widgets = array(
			'AnyEdgeFullScreenMenuOpener',
			'AnyEdgeLatestPosts',
			'AnyEdgeSearchOpener',
			'AnyEdgeSideAreaOpener',
			'AnyEdgeStickySidebar',
			'AnyEdgeSocialIconWidget',
			'AnyEdgeSeparatorWidget'
		);

		foreach ($widgets as $widget) {
			register_widget($widget);
		}
	}
}

add_action('widgets_init', 'any_edge_register_widgets');