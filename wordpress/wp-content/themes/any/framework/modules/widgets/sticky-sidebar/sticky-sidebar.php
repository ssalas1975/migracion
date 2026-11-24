<?php
class AnyEdgeStickySidebar extends AnyEdgeWidget {
	protected $params;
	public function __construct() {
		parent::__construct(
			'edgtf_sticky_sidebar', // Base ID
			esc_html__('Edge Sticky Sidebar', 'any'), // Name
			array( 'description' => esc_html__( 'Use this widget to make the sidebar sticky. Drag it into the sidebar above the widget which you want to be the first element in the sticky sidebar.', 'any' ), ) // Args
		);
		$this->setParams();
	}
	protected function setParams() {
		
	}
	public function widget( $args, $instance ) {
		echo '<div class="widget widget_sticky-sidebar"></div>';
	}

}

add_action('widgets_init', function () {
    register_widget( "AnyEdgeStickySidebar" );
});

