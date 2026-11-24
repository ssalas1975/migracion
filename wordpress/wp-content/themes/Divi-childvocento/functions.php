<?php

require_once __DIR__ . '/actions/pdf.php';


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
  $existing_mimes['ttf'] = 'application/x-font-ttf';
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



// define the wpcf7_before_send_mail callback
function action_wpcf7_before_send_mail($cfdata, &$abort, $submission)
{

  if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
      $formdata = $submission->get_posted_data();
    }
  } elseif (isset($cfdata->posted_data)) {
    $formdata = $cfdata->posted_data;
  }

  $uploaded_files = $submission->uploaded_files();
  $re = '/\.(.*)?/m';


  if (!empty($uploaded_files)) {
    preg_match_all($re, $uploaded_files["file-163"][0], $matches, PREG_SET_ORDER, 0);
    $filenameDNIUNO = $formdata["file-163"] . $matches[0][0];
    $ficherosubida = wp_upload_bits($filenameDNIUNO, null, file_get_contents($uploaded_files["file-163"][0]));
    $candidatoMeta['fichero'] = $ficherosubida['url']; //url fichero subida
  }

  $mailContent = '<p>' . $formdata['your-message'] . '</p>' . "\n" . "Enlace del documento adjunto: <a href='https://www.vocento.com" . $ficherosubida['url'] . "'>PDF</a>";

  $mail = $cfdata->prop('mail');

  $mail["body"] = $mail["body"] . $mailContent;
  $cfdata->set_properties(array('mail' => $mail));

  return $cfdata;
};

// add the action
add_action('wpcf7_before_send_mail', 'action_wpcf7_before_send_mail', 15, 3);
