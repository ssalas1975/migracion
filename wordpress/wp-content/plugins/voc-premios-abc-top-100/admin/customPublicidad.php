<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

add_action('init', 'voc_register_publicidad_post_type');

function voc_register_publicidad_post_type()
{
    $labels = array(
        'name' => _x('Anuncios Top 100', 'post type general name', 'text-domain'),
        'singular_name' => _x('Anuncio', 'post type singular name', 'text-domain'),
        'menu_name' => _x('Anuncios Top 100', 'admin menu', 'text-domain'),
        'add_new' => _x('Añadir nuevo', 'Anuncio', 'text-domain'),
        'add_new_item' => __('Añadir nuevo Anuncio', 'text-domain'),
        'new_item' => __('Nuevo Anuncio', 'text-domain'),
        'edit_item' => __('Editar Anuncio', 'text-domain'),
        'view_item' => __('Ver Anuncio', 'text-domain'),
        'all_items' => __('Todos los Anuncios', 'text-domain'),
        'search_items' => __('Buscar Anuncios', 'text-domain'),
        'not_found' => __('No hay Anuncios.', 'text-domain'),
        'not_found_in_trash' => __('No hay Anuncios en la papelera.', 'text-domain')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => 'publicidad-top100',
        'query_var' => true,
        'rewrite' => array('slug' => 'anuncio_top100'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'revisions')
    );

    register_post_type('voc_anuncio', $args);
}

/**
 * Habilitar WPBakery (Visual Composer) para el nuevo post type
 */
add_action('vc_before_init', 'voc_enable_vc_for_publicidad');
function voc_enable_vc_for_publicidad()
{
    if (function_exists('vc_set_default_editor_post_types')) {
        $list = array('page', 'post', 'voc_anuncio');
        vc_set_default_editor_post_types($list);
    }
}
