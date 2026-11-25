<?php
include_once(__DIR__ . '/../../../../../wp-load.php');

if (!class_exists('WP_List_Table')) {
  include(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}




class GestionClasesCss extends WP_List_Table
{
  function __construct()
  {
    global $status, $page;

    parent::__construct(array(
      'singular' => 'css',
      'plural' => 'css',
      'ajax' => false
    ));
  }

  protected function get_views()
  {
    $status_links = array(
      "all" => __("<a href='#'>All</a>", 'my-plugin-slug'),
      "published" => __("<a href='#'>Published</a>", 'my-plugin-slug'),
      "trashed" => __("<a href='#'>Trashed</a>", 'my-plugin-slug')
    );
    return $status_links;
  }


  function column_default($item, $column_name)
  {
    switch ($column_name) {
      case 'booktitle':
      case 'author':
      case 'isbn':
        return $item[$column_name];
      default:
        return print_r($item, true); // Mostramos todo el arreglo para resolver problemas
    }
  }

  function get_columns()
  {
    $columns = array(
      'booktitle' => 'Nombre',
      'author' => 'URL',
      'isbn' => 'ID'
    );

    return $columns;
  }


  public function get_sortable_columns()
  {
    $sortable_columns = array(
      'ID' => array('ID', false),
      'Title' => array('Title', false)
    );
    return $sortable_columns;
  }
  public function get_bulk_actions()
  {
    $actions = array(
      'delete' => 'Delete',
      'email' => 'Email'
    );
    return $actions;
  }


  /**
   * Summary of generateMeta
   * @param mixed $name
   * @param mixed $recursoID
   * @return void
   */
  static function generateMeta($name, $recursoID, $nombreclase)
  {
    $optionName = 'recursocss_' . $recursoID;
    $data = array('nombre' => $nombreclase, 'id' => $recursoID);
    update_option($optionName, maybe_serialize($data));
  }

  /**
   * Summary of getAllOptions
   * @return void
   */
  function prepare_items()
  {
    global $wpdb;
    $per_page = 10;

    $alldata = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name like '%recursocss_%'", ARRAY_A);
    $option_values = $wpdb->get_results("SELECT option_value FROM wp_options WHERE option_name like '%recursocss_%'", ARRAY_A);
    $postsCount = $wpdb->get_results("select count(*) FROM wp_options WHERE option_name like '%recursocss_%'", ARRAY_A);
    $total_items = intval($postsCount[0]["count(*)"]);



    $data = array();
    $contador = 0;
    foreach ($option_values as $value) {
      if (is_serialized($value['option_value'])) {
        $retrieve = maybe_unserialize(maybe_unserialize($value['option_value']));

        $dato = array(
          'booktitle' => $retrieve['nombre'],
          'author' => get_permalink($retrieve['id']),
          'isbn' => $retrieve['id'],
          'optionid' => $alldata[$contador]["option_id"]
        );
        array_push($data, $dato);

      }

      $contador++;
    }


    $this->set_pagination_args(array(
      'total_items' => $total_items,                  //WE have to calculate the total number of items
      'per_page' => $per_page,                     //WE have to determine how many items to show on a page
      'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
    ));


    $columns = $this->get_columns();
    $hidden = array();
    $sortable = array();
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
  }

  function column_booktitle($item)
  {
    $actions = array(
      // 'edit' => sprintf('<a href="post.php?post=%s&action=edit">Editar</a>', $item['isbn']),
      'delete' => sprintf('<a href="' . get_template_directory_uri() . '/gestioncss/gestioncss/actions.php?attachment_id=%s&action=deleteatachement&optionid=%s">Eliminar</a>', $item['isbn'], $item['booktitle']),
    );

    return sprintf('%1$s %2$s', $item['booktitle'], $this->row_actions($actions));
  }


  public function check_post_exists_on_media_upload($file)
  {
    // 1. Obtener el nombre del archivo sin la extensión
    $filename = $file["fileToUpload"]["name"];

    // 2. Convertir el nombre del archivo en un "slug" (formato post_name)
    $post_name = sanitize_title($filename);

    // 3. Comprobar si existe un post/página con ese nombre (post_name)
    global $wpdb;
    $exists = $wpdb->get_var($wpdb->prepare(
      "SELECT ID FROM wp_posts WHERE post_name = %s AND post_status != 'trash'",
      $post_name
    ));

    // 4. Si existe, detener la subida y lanzar un error
    if ($exists) {
      // $file['error'] = 'Error: Ya existe un post/página con el nombre: ' . $post_name;
      wp_delete_post(intval($exists));
    }

    return $file;
  }


}





function css_add_menu_items()
{
  add_menu_page(
    'gestion_css_files',
    'Archivos CSS',
    'manage_options',
    'gestion_css_files',
    'o_render_form_css_uploads',
    'dashicons-admin-home',
    5
  );

}
add_action('admin_menu', 'css_add_menu_items');





function o_render_form_css_uploads()
{
  $gestionclasescss = new GestionClasesCss();
  // $title = __("Post Sin Canonical","my_plugin_slug");
  ?>
  <div class="wrap">
    <h1>Listado archivos CSS</h1>
    <form method="post" enctype="multipart/form-data">
      <input type="text" placeholder="Nombre de la clase" name="nombreclase">
      <input type="file" name="fileToUpload">
      <input type="submit" name="upload" value="Upload">
    </form>
    <?php
    if (@$_POST['upload']) {
      $_FILES = $gestionclasescss->check_post_exists_on_media_upload($_FILES);

      $file_name = $_FILES['fileToUpload']['name'];
      $file_temp = $_FILES['fileToUpload']['tmp_name'];
      $nombreclase = $_POST['nombreclase'];


      $upload_dir = wp_upload_dir();
      $image_data = file_get_contents($file_temp);
      $filename = basename($file_name);
      $filetype = wp_check_filetype($file_name);


      if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
      } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
      }

      file_put_contents($file, $image_data);
      $wp_filetype = wp_check_filetype($filename, null);
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
      );

      $attach_id = wp_insert_attachment($attachment, $file);
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      $attach_data = wp_generate_attachment_metadata($attach_id, $file);
      wp_update_attachment_metadata($attach_id, $attach_data);

      $gestionclasescss::generateMeta($file_name, $attach_id, $nombreclase);

    }

    $gestionclasescss->prepare_items();
    $gestionclasescss->display();

    ?>

  </div>

  <?php

}