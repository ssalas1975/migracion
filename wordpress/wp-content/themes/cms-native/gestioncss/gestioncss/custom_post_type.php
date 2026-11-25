<?php
// Register Custom Post Type Edit css
function create_editcss_cpt()
{

  $labels = array(
    'name' => _x('Edit css', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Edit css', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Edit css', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Edit css', 'Add New on Toolbar', 'textdomain'),
    'archives' => __('Edit css Archives', 'textdomain'),
    'attributes' => __('Edit css Attributes', 'textdomain'),
    'parent_item_colon' => __('Parent Edit css:', 'textdomain'),
    'all_items' => __('All Edit css', 'textdomain'),
    'add_new_item' => __('Add New Edit css', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Edit css', 'textdomain'),
    'edit_item' => __('Edit Edit css', 'textdomain'),
    'update_item' => __('Update Edit css', 'textdomain'),
    'view_item' => __('View Edit css', 'textdomain'),
    'view_items' => __('View Edit css', 'textdomain'),
    'search_items' => __('Search Edit css', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
    'featured_image' => __('Featured Image', 'textdomain'),
    'set_featured_image' => __('Set featured image', 'textdomain'),
    'remove_featured_image' => __('Remove featured image', 'textdomain'),
    'use_featured_image' => __('Use as featured image', 'textdomain'),
    'insert_into_item' => __('Insert into Edit css', 'textdomain'),
    'uploaded_to_this_item' => __('Uploaded to this Edit css', 'textdomain'),
    'items_list' => __('Edit css list', 'textdomain'),
    'items_list_navigation' => __('Edit css list navigation', 'textdomain'),
    'filter_items_list' => __('Filter Edit css list', 'textdomain'),
  );
  $args = array(
    'label' => __('Edit css', 'textdomain'),
    'description' => __('Crear contenedor css', 'textdomain'),
    'labels' => $labels,
    'menu_icon' => 'dashicons-admin-links',
    'supports' => array('title'),
    'taxonomies' => array(),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'exclude_from_search' => false,
    'show_in_rest' => true,
    'publicly_queryable' => true,
    'capability_type' => 'post',
  );
  register_post_type('editcss', $args);

}
add_action('init', 'create_editcss_cpt', 0);