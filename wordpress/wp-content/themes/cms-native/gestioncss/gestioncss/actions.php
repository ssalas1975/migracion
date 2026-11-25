<?php

// require(__DIR__ . '/../../../../../wp-load.php');

// $action = $_GET['action'];
// $attachment_id = $_GET['attachment_id'];
// $optionid = $_GET['optionid'];


// switch ($action) {
//   case 'editattachment':
//     var_dump($attachment_id);
//     // wp_redirect(admin_url('admin.php?page=gestion_css_files'));
//     exit;
//     break;
//   case 'deleteatachement':
//     wp_delete_post(intval($attachment_id));
//     delete_option('recursocss_' . $attachment_id);
//     wp_redirect(admin_url('admin.php?page=gestion_css_files'));
//     break;
// }

// die();




add_filter('vc_load_default_templates', 'my_custom_template_modify_array'); // Hook in
function my_custom_template_modify_array($data)
{
  var_dump($data);
  die('hhhghfghfgh');
  // return array(); // This will remove all default templates. Basically you should use native PHP functions to modify existing array and then return it.
}