<?php

add_action('init', 'bf_register_custom_post_type');

function bf_register_custom_post_type()
{
    /* Añado las etiquetas que aparecerán en el escritorio de WordPress */
    $labels = array(
        'name' => _x('Candidatos', 'post type general name', 'text-domain'),
        'singular_name' => _x('Candidato', 'post type singular name', 'text-domain'),
        'menu_name' => _x('Candidatos', 'admin menu', 'text-domain'),
        'add_new' => _x('Añadir nuevo', 'Candidato', 'text-domain'),
        'add_new_item' => __('Añadir nuevo Candidato', 'text-domain'),
        'new_item' => __('Nuevo Candidato', 'text-domain'),
        'edit_item' => __('Editar Candidato', 'text-domain'),
        'view_item' => __('Ver Candidato', 'text-domain'),
        'all_items' => __('Candidatos', 'text-domain'),
        'search_items' => __('Buscar Candidato', 'text-domain'),
        'not_found' => __('No hay Candidatos.', 'text-domain'),
        'not_found_in_trash' => __('No hay Candidatos en la papelera.', 'text-domain')
    );

    /* Configuro el comportamiento y funcionalidades del nuevo custom post type */
    $args = array(
        'labels' => $labels,
        'description' => __('Descripción.', 'text-domain'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'candidato'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );

    register_post_type('candidato', $args);



    // $user = new WP_User(2);
    // $user->add_cap('manage_options');
}



// add_action('init', 'bf_register_custom_post_type_interes');
function bf_register_custom_post_type_interes()
{
    /* Añado las etiquetas que aparecerán en el escritorio de WordPress */
    $labels = array(
        'name' => _x('Candidatos Interesados', 'post type general name', 'text-domain'),
        'singular_name' => _x('Candidato Interesado', 'post type singular name', 'text-domain'),
        'menu_name' => _x('Candidatos Interesados', 'admin menu', 'text-domain'),
        'add_new' => _x('Añadir nuevo', 'Candidato Interesado', 'text-domain'),
        'add_new_item' => __('Añadir nuevo Candidato Interesado', 'text-domain'),
        'new_item' => __('Nuevo Candidato Interesado', 'text-domain'),
        'edit_item' => __('Editar Candidato Interesado', 'text-domain'),
        'view_item' => __('Ver Candidato Interesado', 'text-domain'),
        'all_items' => __('Candidatos Interesado', 'text-domain'),
        'search_items' => __('Buscar Candidato Interesado', 'text-domain'),
        'not_found' => __('No hay Candidatos.', 'text-domain'),
        'not_found_in_trash' => __('No hay Candidatos en la papelera.', 'text-domain')
    );

    /* Configuro el comportamiento y funcionalidades del nuevo custom post type */
    $args = array(
        'labels' => $labels,
        'description' => __('Descripción.', 'text-domain'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'candidato_interesado'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );

    register_post_type('candidatointeresado', $args);



    // $user = new WP_User(2);
    // $user->add_cap('manage_options');
}




function add_detalle_link($actions, $page_object)
{
    if (wp_get_current_user()->data->ID == '6303' || wp_get_current_user()->data->ID == '6304' || wp_get_current_user()->data->ID == '3838' || wp_get_current_user()->data->ID == '6305') {
        unset($actions["trash"]);
        unset($actions["edit"]);
        unset($actions["inline hide-if-no-js"]);
        unset($actions["view"]);
    }
    if ($page_object->post_type === 'candidatointeresado') {
        $actions['Ver detalle'] = '<a href="' . plugin_dir_url(__FILE__) . 'views/detalleCandidatoInteresado.php?candidatoid=' . $page_object->ID . '" target="_blank">Ver detalle</a>';
    } else {
        $actions['Ver detalle'] = '<a href="' . plugin_dir_url(__FILE__) . 'views/detalleCandidato.php?candidatoid=' . $page_object->ID . '" target="_blank">Ver detalle</a>';
    }
    return $actions;
}

add_filter('post_row_actions', 'add_detalle_link', 10, 2);
