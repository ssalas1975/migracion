<?php

//Permisos de 'unfiltered_html' para el admin
add_filter('map_meta_cap', 'vocento_users_unfiltered', 1, 3);
function vocento_users_unfiltered($caps, $cap, $user_id)
{
  if ('unfiltered_html' === $cap && user_can($user_id, 'administrator'))
    $caps = array('unfiltered_html');
  return $caps;
}


//special rules
function voc_com_custom_rewrite_junta()
{
  add_rewrite_rule('accionistas-inversores/junta-accionistas/([0-9]+)(extra)?/?$', 'index.php?page_id=382', 'top');
  add_rewrite_rule('accionistas-inversores/junta-accionistas/?$', 'index.php?page_id=382', 'top');
  flush_rewrite_rules();
}
add_action('init', 'voc_com_custom_rewrite_junta', 1, 0);

function vccom_get_attachments_single($ID)
{
  $attachments = get_posts(array(
    'post_type' => 'attachment',
    'posts_per_page' => -1,
    'post_parent' => $ID,
    'exclude'     => get_post_thumbnail_id()
  ));

  if ($attachments) {
    return $attachments[0];
  }

  return "";
}


add_action('init', 'vocentocom_memoria_pt');
function vocentocom_memoria_pt()
{
  register_post_type(
    'memoria',
    array(
      'labels' => array(
        'name' => __('Memoria anual'),
        'singular_name' => __('Memoria anual')
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array('title', 'editor', 'thumbnail'),
      'taxonomies'  => array('category'),

    )
  );

  register_post_type(
    'localizacion',
    array(
      'labels' => array(
        'name' => __('Localizaciones'),
        'singular_name' => __('Localización')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'editor'),
      'taxonomies'  => array('category'),

    )
  );
}

/* permitir subir archivos con extensión svg*/
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes($existing_mimes = array())
{
  // Add the file extension to the array
  $existing_mimes['svg'] = 'image/svg+xml';
  $existing_mimes['svgz'] = 'image/svg+xml';
  $existing_mimes['zip'] = 'application/zip';
  return $existing_mimes;
}



if (is_plugin_active('wordpress-datepicker-master/metabox-datepicker.php')) {
  $metabox_datepicker = new metabox_datepicker();
}

function replace_content($content)
{
  $content = str_replace('https://ms-vocento.srv.vocento.in/vocentocom/', '/', $content);
  return $content;
}
add_filter('the_content', 'replace_content');


function edit_upload_types($existing_mimes = array())
{
  $existing_mimes['xhtml'] = 'xhtml';
  return $existing_mimes;
}
add_filter('upload_mimes', 'edit_upload_types');



// define( 'WPCF7_UPLOADS_TMP_DIR', '/your/file/path' );

define('WPCF7_UPLOADS_TMP_DIR', 'https://vocento.com/wp-content/uploads/wpcf7_uploads');
