<?php
include_once(__DIR__ . '/../../../../../wp-load.php');

function create_gestioncss_shortcode($atts)
{
  // Attributes
  $atts = shortcode_atts(
    array(
      'nameofcss' => '',
    ),
    $atts,
    'gestion_css'
  );
  // Attributes in var
  $nameofcss = $atts['nameofcss'];

}
add_shortcode('gestion_css', 'create_gestioncss_shortcode');

// Create gestion css element for Visual Composer
add_action('vc_before_init', 'gestioncss_integrateWithVC');
function gestioncss_integrateWithVC()
{


  global $wpdb;
  $per_page = -1;
  $alldata = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name like '%recursocss_%'", ARRAY_A);
  $option_values = $wpdb->get_results("SELECT option_value FROM wp_options WHERE option_name like '%recursocss_%'", ARRAY_A);


  $data = ['Selecciona...'];
  $contador = 0;
  foreach ($option_values as $value) {
    if (is_serialized($value['option_value'])) {
      $retrieve = maybe_unserialize(maybe_unserialize($value['option_value']));
      $data[$retrieve['nombre']] = $retrieve['id'] . '@@' . $retrieve['nombre'] . '@@' . $alldata[$contador]["option_id"];
      // array_push($data, $retrieve['id'] . '@@' . $retrieve['nombre'] . '@@' . $alldata[$contador]["option_id"]);
    }

    $contador++;
  }


  vc_map(array(
    'name' => __('gestion css', 'textdomain'),
    'base' => 'gestion_css',
    'show_settings_on_create' => true,
    'category' => __('Content', 'textdomain'),
    'params' => array(
      array(
        'type' => 'dropdown',
        'holder' => 'div',
        'class' => '',
        'admin_label' => false,
        'heading' => __('', 'textdomain'),
        'param_name' => 'nameofcss',
        'value' => $data,
      ),
    )
  ));
}





add_action('save_post', 'my_extra_fields_save_on_update', 0);
function my_extra_fields_save_on_update($post_id)
{
  $postcontent = $_POST;
  $contenido = $postcontent["post_content"];
  $re = '/\[gestion_css nameofcss=\\\\"(.*?)\\\\"]/m';
  preg_match_all($re, $contenido, $matches, PREG_SET_ORDER, 0);

  if (empty($matches[0][1])) {
    delete_post_meta($post_id, 'css_opciones');
  } else {
    update_post_meta($post_id, 'css_opciones', $matches[0][1]);
  }
}