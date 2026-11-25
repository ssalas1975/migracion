<?php

add_action('init', 'bf_register_custom_post_type_html');

function bf_register_custom_post_type_html()
{
    /* Añado las etiquetas que aparecerán en el escritorio de WordPress */
    $labels = array(
        'name'               => _x('Módulo HTML', 'post type general name', 'text-domain'),
        'singular_name'      => _x('Módulo HTML', 'post type singular name', 'text-domain'),
        'menu_name'          => _x('Módulo HMTL', 'admin menu', 'text-domain'),
        'add_new'            => _x('Añadir nuevo', 'Módulo HMTL', 'text-domain'),
        'add_new_item'       => __('Añadir nuevo Módulo HMTL', 'text-domain'),
        'new_item'           => __('Nuevo Módulo HMTL', 'text-domain'),
        'edit_item'          => __('Editar Módulo HMTL', 'text-domain'),
        'view_item'          => __('Ver Módulo HMTL', 'text-domain'),
        'all_items'          => __('Módulo HMTL', 'text-domain'),
        'search_items'       => __('Buscar Módulo HMTL', 'text-domain'),
        'not_found'          => __('No hay Módulo HMTL.', 'text-domain'),
        'not_found_in_trash' => __('No hay Módulo HMTL en la papelera.', 'text-domain')
    );

    /* Configuro el comportamiento y funcionalidades del nuevo custom post type */
    $args = array(
        'labels'             => $labels,
        'description'        => __('Descripción.', 'text-domain'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'modulo_HMTL' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor' )
    );

    register_post_type('modulo_HMTL', $args);

}




// add_action("admin_init", "admin_init2222");

function admin_init2222(){
  add_meta_box("html_body_content", "Preview Post Sin Indexación", "html_body_content", "modulo_hmtl", "normal", "low");
}

function year_completed(){
  global $post;
  $custom = get_post_custom($post->ID);
  $year_completed = $custom["year_completed"][0];
  ?>
  <label>Year:</label>
  <input name="year_completed" value="<?php echo $year_completed; ?>" />
  <?php
}

function html_body_content() {
  global $post;
  $custom = get_post_custom($post->ID);

    if ( ! is_dir( WP_CONTENT_DIR.'/uploads/previewpost/post/' ) ) {
        wp_mkdir_p( WP_CONTENT_DIR.'/uploads/previewpost/post/' );
    }
    $fileName = 'headercache_' . $post->ID . '.html';
    $htmlFile = WP_CONTENT_DIR.'/uploads/previewpost/post/'.$fileName;
    
    // file_put_contents($htmlFile, $content, FILE_USE_INCLUDE_PATH);

  ?>
    <p><label>Previsualización Post</label><br />
        <input type="text" name="htmlblock" style="width: 100%;" value="<?php echo $post->ID; ?>"/>
    </p>
  <?php
}

// add_action('save_post', 'save_details');
function save_details(){
    global $post;
    // var_dump($_POST["htmlblock"]);
    var_dump($post->post_content);
    die('7777');
    // update_post_meta($post->ID, "htmlblock", $_POST["htmlblock"]);
}