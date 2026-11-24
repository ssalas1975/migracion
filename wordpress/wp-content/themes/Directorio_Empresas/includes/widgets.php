<?php 

//Buscador
class ldk_widget_search extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_wdg_search', 
	__('LDK Buscador', 'ldk_wdg'), 
	array( 'description' => __( 'Buscador LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	 //Buscador
		get_template_part('partials/searcher');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
} 

//Tags
class ldk_widget_tags extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_wdg_tags', 
	__('LDK Tags', 'ldk_wdg'), 
	array( 'description' => __( 'Tags LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	 //Buscador
		get_template_part('partials/tags');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
} 

//Empresas
class ldk_widget_empresas extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_widget_empresas', 
	__('LDK Empresas', 'ldk_wdg'), 
	array( 'description' => __( 'Empresas LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	  //Empresas
    get_template_part('partials/sidebar','empresas');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
} 

//Vistos
class ldk_widget_views extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_widget_views', 
	__('LDK Vistos', 'ldk_wdg'), 
	array( 'description' => __( 'Vistos LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	  //vistos
    get_template_part('partials/most','visits');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
} 

//Vistos
class ldk_widget_newsletter extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_widget_newsletter', 
	__('LDK Newsletter', 'ldk_wdg'), 
	array( 'description' => __( 'Newsletter LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	  //vistos
    get_template_part('partials/newsletter');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
}

//Contacto
class ldk_widget_contacto extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_widget_contacto', 
	__('LDK Contacto', 'ldk_wdg'), 
	array( 'description' => __( 'Contacto LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	  //contacto
      get_template_part('partials/contacto','sidebar');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
}

//Social
class ldk_widget_social extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_widget_social', 
	__('LDK Social', 'ldk_wdg'), 
	array( 'description' => __( 'Social LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	 
    //social
    get_template_part('partials/social');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
}

//Buscador
class ldk_widget_categories extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	'ldk_widget_categories', 
	__('LDK Categorias', 'ldk_wdg'), 
	array( 'description' => __( 'Categorías LDK', 'ldk_wdg' ), ) 
	);
	}

	public function widget( $args, $instance ) {
	 //Buscador
		get_template_part('partials/category','menu');
	}

	public function form( $instance ) {}

	public function update( $new_instance, $old_instance ) {}
} 