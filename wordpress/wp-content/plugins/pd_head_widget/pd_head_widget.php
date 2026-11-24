<?php
/*
Plugin Name: Pidecita widget head
Plugin URI: #
Description: Código html para el head
Author: Vocento
Version: 1.0
*/

add_action( 'widgets_init', function(){
	register_widget( 'pc_head_widget' );
});

class pc_head_widget extends WP_Widget {
	// class constructor
	public function __construct() {
        $widget_ops = array( 
            'classname' => 'pc_head_widget',
            'description' => 'A plugin for Kinsta blog readers',
        );
        parent::__construct( 'pc_head_widget', 'My Widget', $widget_ops );
    }
	
	// output the widget content on the front-end
	public function widget( $args, $instance ) {
        if ( ! empty( $instance['content'] ) ) {
            echo $instance['content'] ;
        }
    }

	// output the option form field in admin Widgets screen
	public function form( $instance ) {
        $contenido = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( 'Contenido', 'text_domain' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>">
        <?php esc_attr_e( 'Contenido:', 'text_domain' ); ?>
        </label> 
        <textarea rows="4" cols="50" class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>" >
            <?php echo esc_attr( $contenido ); ?>
        </textarea>
        </p>
        <?php
    }

	// save options
	public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['content'] = ( ! empty( $new_instance['content'] ) ) ? strip_tags( $new_instance['content'] ) : '';
        return $instance;
    }
}

add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    $args = array(
        'name'          => sprintf(__('pidecita head'), $i ),
        'id'            => 'pd_head',
        'description'   => '',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '' 
    );
    register_sidebar( $args );
}