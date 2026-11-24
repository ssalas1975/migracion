<?php


function cptui_register_my_cpts() {

    /**
     * Post Type: Noticias.
     */

    $labels = [
        "name" => __( "Noticias", "won2020" ),
        "singular_name" => __( "Noticia", "won2020" ),
    ];

    $args = [
        "label" => __( "Noticias", "won2020" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => [ "slug" => "noticia", "with_front" => true ],
        "query_var" => true,
        "supports" => [ "title", "editor", "thumbnail", "revisions" ],
        "taxonomies" => [ "category" ],
        "show_in_graphql" => false,
    ];

    register_post_type( "noticia", $args );

    /**
     * Post Type: Podcasts.
     */

    $labels = [
        "name" => __( "Podcasts", "won2020" ),
        "singular_name" => __( "Podcast", "won2020" ),
    ];

    $args = [
        "label" => __( "Podcasts", "won2020" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => [ "slug" => "podcast", "with_front" => true ],
        "query_var" => true,
        "supports" => [ "title", "editor", "thumbnail", "revisions" ],
        "taxonomies" => [ "category" ],
        "show_in_graphql" => false,
    ];

    register_post_type( "audio", $args );

    /**
     * Post Type: Videos.
     */

    $labels = [
        "name" => __( "Videos", "won2020" ),
        "singular_name" => __( "Video", "won2020" ),
    ];

    $args = [
        "label" => __( "Videos", "won2020" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => [ "slug" => "video", "with_front" => true ],
        "query_var" => true,
        "supports" => [ "title", "editor", "thumbnail", "revisions" ],
        "taxonomies" => [ "category" ],
        "show_in_graphql" => false,
    ];

    register_post_type( "videocpt", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );




if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_5e186fffc0276',
    'title' => 'Contenidos Extra',
    'fields' => array(
        array(
            'key' => 'field_5e1870088addc',
            'label' => 'Video',
            'name' => 'video',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'videocpt',
            ),
            'taxonomy' => '',
            'allow_null' => 1,
            'multiple' => 0,
            'return_format' => 'object',
            'ui' => 1,
        ),
        array(
            'key' => 'field_5e1870148addd',
            'label' => 'Audio',
            'name' => 'audio',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => '',
            'taxonomy' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'object',
            'ui' => 1,
        ),
        array(
            'key' => 'field_5ed409c320fcb',
            'label' => 'Galería',
            'name' => 'galeria_extra',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => '',
            'taxonomy' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'object',
            'ui' => 1,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5e15f3ffb1ae9',
    'title' => 'Datos Foto',
    'fields' => array(
        array(
            'key' => 'field_5e15f41be33f1',
            'label' => 'Pie de foto',
            'name' => 'pie_foto',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5ea9313560ebb',
            'label' => '',
            'name' => 'foto-interior',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => 'No mostrar foto en interior',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed40ba337c4d',
    'title' => 'Datos video',
    'fields' => array(
        array(
            'key' => 'field_5ed40ba34aaa1',
            'label' => 'Pretítulo',
            'name' => 'pretitulo',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5ed40bbb74ec0',
            'label' => 'Id video',
            'name' => 'idvideo',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'videocpt',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed0fc4f10f13',
    'title' => 'Escuchalo en ....',
    'fields' => array(
        array(
            'key' => 'field_5ed0fc571ca02',
            'label' => '',
            'name' => 'escuchalo',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_5ed0fc771ca03',
            'min' => 0,
            'max' => 0,
            'layout' => 'block',
            'button_label' => 'Añadir plataforma',
            'sub_fields' => array(
                array(
                    'key' => 'field_5ed0fc771ca03',
                    'label' => 'Plataforma',
                    'name' => 'plataforma',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '30',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5ed0fc881ca04',
                    'label' => 'Enlace',
                    'name' => 'enlace',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '70',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'audio',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5e15e8a0e15d2',
    'title' => 'Galeria',
    'fields' => array(
        array(
            'key' => 'field_5e15e8b4cb438',
            'label' => '',
            'name' => 'galeria',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'Añadir Imagen',
            'sub_fields' => array(
                array(
                    'key' => 'field_5ed408b44d3c0',
                    'label' => '',
                    'name' => 'grupo_imagen',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5ed408cb4d3c1',
                            'label' => '',
                            'name' => 'imagen',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                        array(
                            'key' => 'field_5ed408e04d3c2',
                            'label' => '',
                            'name' => 'pie',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_5ed409094d3c3',
                    'label' => '',
                    'name' => 'texto_imagen',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '75',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'basic',
                    'media_upload' => 1,
                    'delay' => 0,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed0fbd332f9a',
    'title' => 'Iframe podcast',
    'fields' => array(
        array(
            'key' => 'field_5ed0fbe905f40',
            'label' => 'Audio',
            'name' => 'audio',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 4,
            'new_lines' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'audio',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5db8351af4210',
    'title' => 'Intro página',
    'fields' => array(
        array(
            'key' => 'field_5db8352973798',
            'label' => 'Intro',
            'name' => 'intro_pagina',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '85',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '71',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '77',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '53',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '73',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_61dd88b0bf7b1',
    'title' => 'listado Podcasts',
    'fields' => array(
        array(
            'key' => 'field_61dd89ca65d80',
            'label' => 'Listado Podcasts',
            'name' => 'listado_podcasts',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => '',
            'sub_fields' => array(
                array(
                    'key' => 'field_61dd89da65d81',
                    'label' => 'Imagen del podcast',
                    'name' => 'imagen_podcasts',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_61dd89ff65d82',
                    'label' => 'Título del podcast',
                    'name' => 'titulo_podcast',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_61dd8a1865d83',
                    'label' => 'Descripción del podcast',
                    'name' => 'descripcion_podcast',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_61dd8a2865d84',
                    'label' => 'URL del podcast',
                    'name' => 'url_podcast',
                    'type' => 'page_link',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => '',
                    'taxonomy' => '',
                    'allow_null' => 0,
                    'allow_archives' => 1,
                    'multiple' => 0,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '668',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed0f69d0d03a',
    'title' => 'Módulos',
    'fields' => array(
        array(
            'key' => 'field_5ed0f6fe44706',
            'label' => '',
            'name' => 'modulos',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_5ed0f71d44707',
            'min' => 0,
            'max' => 0,
            'layout' => 'block',
            'button_label' => 'Añadir modulo',
            'sub_fields' => array(
                array(
                    'key' => 'field_5ed0f71d44707',
                    'label' => 'Título',
                    'name' => 'titulo',
                    'type' => 'text',
                    'instructions' => 'Si tiene varios títulos, separarlos por comas.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5ed0f76144708',
                    'label' => '¿Páginas donde se muestra?',
                    'name' => 'mostrar',
                    'type' => 'post_object',
                    'instructions' => 'Elegir páginas donde se muestra',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'page',
                    ),
                    'taxonomy' => '',
                    'allow_null' => 1,
                    'multiple' => 1,
                    'return_format' => 'id',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_5ed0f79a44709',
                    'label' => '¿Interiores donde se muestra?',
                    'name' => 'mostrar_int',
                    'type' => 'checkbox',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'noticia' => 'Noticia',
                        'videocpt' => 'Video',
                        'audio' => 'Podcast',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(
                    ),
                    'layout' => 'horizontal',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'save_custom' => 0,
                ),
                array(
                    'key' => 'field_5ed0f7bc4470a',
                    'label' => 'Contenido',
                    'name' => 'contenido',
                    'type' => 'relationship',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => '',
                    'taxonomy' => '',
                    'filters' => array(
                        0 => 'search',
                        1 => 'post_type',
                    ),
                    'elements' => '',
                    'min' => '',
                    'max' => '',
                    'return_format' => 'object',
                ),
                array(
                    'key' => 'field_5ed0f7e34470b',
                    'label' => 'Publicidad',
                    'name' => 'publicidad',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Añadir ID de la publicidad',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5ed0f7fd4470c',
                    'label' => 'Carrusel',
                    'name' => 'carrusel',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => 'No mostrar como carrusel',
                    'default_value' => 0,
                    'ui' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
            ),
        ),
        array(
            'key' => 'field_619f5f6b55f71',
            'label' => 'Enlaces de Interés',
            'name' => 'enlaces_interes',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'category',
            'field_type' => 'checkbox',
            'add_term' => 1,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'id',
            'multiple' => 0,
            'allow_null' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '211',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '3068',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '3078',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '3127',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed0fb6cbb74d',
    'title' => 'Podcast relacionados',
    'fields' => array(
        array(
            'key' => 'field_5ed0fb8e4846e',
            'label' => '',
            'name' => 'podcast_relacionados',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'audio',
            ),
            'taxonomy' => '',
            'filters' => '',
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'object',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'audio',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5e32f61abed57',
    'title' => 'Pretítulo',
    'fields' => array(
        array(
            'key' => 'field_5e32f63bab6f6',
            'label' => 'Pretítulo',
            'name' => 'pretitulo',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed4f3742315d',
    'title' => 'Pretitulo y Subtitulo Podcast',
    'fields' => array(
        array(
            'key' => 'field_5ed4f3b0f9e23',
            'label' => 'Pretítulo',
            'name' => 'pretitulo',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5ed4f37438735',
            'label' => 'Subtítulo',
            'name' => 'subtitulo',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'audio',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5dc2d2fe4d552',
    'title' => 'Resumen',
    'fields' => array(
        array(
            'key' => 'field_5dc2d30970888',
            'label' => 'Resumen',
            'name' => 'resumen',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 2,
            'new_lines' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'proyecto',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'red',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'observatorio',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'barometro',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'paper',
            ),
            array(
                'param' => 'post_taxonomy',
                'operator' => '==',
                'value' => 'tipo_paper:comentarios',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'paper',
            ),
            array(
                'param' => 'post_taxonomy',
                'operator' => '==',
                'value' => 'tipo_paper:informes',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'prensa',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'the_content',
    ),
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5db2e06b83e18',
    'title' => 'Slide',
    'fields' => array(
        array(
            'key' => 'field_5db2e08aa5f8b',
            'label' => '',
            'name' => 'slide',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'Añadir Imagen',
            'sub_fields' => array(
                array(
                    'key' => 'field_5db2e09ba5f8c',
                    'label' => '',
                    'name' => 'imagen',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '211',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '85',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '73',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'featured_image',
    ),
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5dd40318df5df',
    'title' => 'Slide Home',
    'fields' => array(
        array(
            'key' => 'field_5dd403190c617',
            'label' => '',
            'name' => 'slide',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'block',
            'button_label' => 'Añadir Slide',
            'sub_fields' => array(
                array(
                    'key' => 'field_5e17181768a2b',
                    'label' => '',
                    'name' => 'grupo',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5e17186e53563',
                            'label' => 'Titulo',
                            'name' => 'titulo',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5e17188b53564',
                            'label' => 'Texto',
                            'name' => 'texto',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'maxlength' => '',
                            'rows' => 7,
                            'new_lines' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_5e17182868a2c',
                    'label' => '',
                    'name' => 'grupo2',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5e1718be53565',
                            'label' => 'Imagen',
                            'name' => 'imagen',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '50',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                        array(
                            'key' => 'field_5e33e8d1d934e',
                            'label' => 'Imagen Movil',
                            'name' => 'imagen_movil',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '50',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                        array(
                            'key' => 'field_5e1718cf53566',
                            'label' => 'Enlace',
                            'name' => 'enlace',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '211',
            ),
        ),
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '3127',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'featured_image',
    ),
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed0f93075b20',
    'title' => 'Te puede interesar',
    'fields' => array(
        array(
            'key' => 'field_5ed0f94788729',
            'label' => '',
            'name' => 'interes',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'noticia',
            ),
            'taxonomy' => '',
            'filters' => array(
                0 => 'search',
            ),
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'object',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'videocpt',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'audio',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_60bf2ace30937',
    'title' => 'Video Congreso',
    'fields' => array(
        array(
            'key' => 'field_60bf2c8be6bbb',
            'label' => 'Video Congreso',
            'name' => 'video_congreso',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '3127',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5e0f0cc0f3cfb',
    'title' => 'Video destacado',
    'fields' => array(
        array(
            'key' => 'field_5e0f0cdc1931b',
            'label' => '',
            'name' => 'video_destacado',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'videocpt',
            ),
            'taxonomy' => '',
            'filters' => '',
            'elements' => '',
            'min' => '',
            'max' => 1,
            'return_format' => 'object',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page',
                'operator' => '==',
                'value' => '667',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5ed0f9eed5288',
    'title' => 'Vídeos relacionados',
    'fields' => array(
        array(
            'key' => 'field_5ed0f9f944eaa',
            'label' => '',
            'name' => 'videos_relacionados',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'videocpt',
            ),
            'taxonomy' => '',
            'filters' => '',
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'object',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'videocpt',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_61dd383141ceb',
    'title' => 'Autor',
    'fields' => array(
        array(
            'key' => 'field_61dd389c72dfb',
            'label' => 'Autor',
            'name' => 'autor',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
    ),
    'menu_order' => 1,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5daf11de4e777',
    'title' => 'Contenido',
    'fields' => array(
        array(
            'key' => 'field_5daf12002a226',
            'label' => 'Contenido sin YOAST',
            'name' => 'contenido',
            'type' => 'wysiwyg',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'noticia',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
    ),
    'menu_order' => 1,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'the_content',
    ),
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5db83b606fde4',
    'title' => 'Otros contenidos',
    'fields' => array(
        array(
            'key' => 'field_5db83b68e220c',
            'label' => 'Link',
            'name' => 'link',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5db83b89e220d',
            'label' => 'Pdf',
            'name' => 'pdf',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'observatorio',
            ),
        ),
    ),
    'menu_order' => 1,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'the_content',
    ),
    'active' => 1,
    'description' => '',
));

endif;



if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5ed0fc4f10f13',
        'title' => 'Escuchalo en ....',
        'fields' => array(
            array(
                'key' => 'field_5ed0fc571ca02',
                'label' => '',
                'name' => 'escuchalo',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => 'field_5ed0fc771ca03',
                'min' => 0,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Añadir plataforma',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5ed0fc771ca03',
                        'label' => 'Plataforma',
                        'name' => 'plataforma',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '30',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5ed0fc881ca04',
                        'label' => 'Enlace',
                        'name' => 'enlace',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '70',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'audio',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
    
    endif;