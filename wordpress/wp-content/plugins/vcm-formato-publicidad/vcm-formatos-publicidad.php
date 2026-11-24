<?php
/**
* Plugin Name: Vocento Formato Publicidad
* Description: Formatos Digitales.
* Version: 0.1
*/

	

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
 
	
		require_once dirname( __FILE__ ) . '/includes/VocentoFormatoPublicidad.php';
		$my_shortcode_formatos_publicdad = new VocentoFormatoPublicidad();
	


/**
*
* Create POST TYPE vcm-formatos
*
*/
add_action( 'init', 'create_postype_vcm_formatos' );
function create_postype_vcm_formatos() {
	
	$labels = array(
		'name'                => _x( 'vcm-formatos', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'vcm-formatos', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'vcm-formatos', 'text_domain' ),
		'name_admin_bar'      => __( 'vcm-formatos', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'vcm-formatos', 'text_domain' ),
		'add_new_item'        => __( 'Añadir nuevo', 'text_domain' ),
		'add_new'             => __( 'Añadir nuevo', 'text_domain' ),
		'new_item'            => __( 'Nuevo', 'text_domain' ),
		'edit_item'           => __( 'Editar', 'text_domain' ),
		'update_item'         => __( 'Actualizar', 'text_domain' ),
		'view_item'           => __( 'Ver', 'text_domain' ),
		'search_items'        => __( 'Buscar', 'text_domain' ),
		'not_found'           => __( 'No encontrado', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);

	$args = array(
		'labels' 				=> $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', ),
		'hierarchical' 			=> false,
		'public' 				=> true,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'menu_position' 		=> 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_nav_menus'     => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'query_var' => true,
		'rewrite' => false,
	  ); 	

	//register what we just set up above
	register_post_type('vcm-formatos', $args);    
	
}


/**
*
* Registrar custom fields para cm-formatos
*
*/
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_formatos-publicidad',
		'title' => 'Formatos publicidad',
		'fields' => array (
			array (
				'key' => 'field_57f21cf32e017',
				'label' => 'Tamaño:',
				'name' => 'tamaño',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Tamaño: 980x250',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_57f21d6baa9e5',
				'label' => 'Peso máx:',
				'name' => 'peso_max',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Peso máx: 50 Kb',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_57f21d8aaa9e6',
				'label' => 'Formato:',
				'name' => 'formato',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Formato: gif, jpg, html5',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_57f21db0aa9e7',
				'label' => 'Tecnología:',
				'name' => 'tecnologia',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Tecnología: html,redirect',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'vcm-formatos',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 1,
	));
}


