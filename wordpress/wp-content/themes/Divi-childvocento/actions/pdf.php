<?php

// Register Custom Post Type Pdf
function create_pdf_cpt()
{

	$labels = array(
		'name' => _x('Pdfs', 'Post Type General Name', 'textdomain'),
		'singular_name' => _x('Pdf', 'Post Type Singular Name', 'textdomain'),
		'menu_name' => _x('Pdfs', 'Admin Menu text', 'textdomain'),
		'name_admin_bar' => _x('Pdf', 'Add New on Toolbar', 'textdomain'),
		'archives' => __('Archivos Pdf', 'textdomain'),
		'attributes' => __('Atributos Pdf', 'textdomain'),
		'parent_item_colon' => __('Padres Pdf:', 'textdomain'),
		'all_items' => __('Todas Pdfs', 'textdomain'),
		'add_new_item' => __('Añadir nueva Pdf', 'textdomain'),
		'add_new' => __('Añadir nueva', 'textdomain'),
		'new_item' => __('Nueva Pdf', 'textdomain'),
		'edit_item' => __('Editar Pdf', 'textdomain'),
		'update_item' => __('Actualizar Pdf', 'textdomain'),
		'view_item' => __('Ver Pdf', 'textdomain'),
		'view_items' => __('Ver Pdfs', 'textdomain'),
		'search_items' => __('Buscar Pdf', 'textdomain'),
		'not_found' => __('No se encontraron Pdfs.', 'textdomain'),
		'not_found_in_trash' => __('Ningún Pdf encontrado en la papelera.', 'textdomain'),
		'featured_image' => __('Imagen destacada', 'textdomain'),
		'set_featured_image' => __('Establecer imagen destacada', 'textdomain'),
		'remove_featured_image' => __('Borrar imagen destacada', 'textdomain'),
		'use_featured_image' => __('Usar como imagen destacada', 'textdomain'),
		'insert_into_item' => __('Insertar en la Pdf', 'textdomain'),
		'uploaded_to_this_item' => __('Subido a esta Pdf', 'textdomain'),
		'items_list' => __('Lista de Pdfs', 'textdomain'),
		'items_list_navigation' => __('Navegación por el listado de Pdfs', 'textdomain'),
		'filter_items_list' => __('Lista de Pdfs filtradas', 'textdomain'),
	);
	$args = array(
		'label' => __('Pdf', 'textdomain'),
		'description' => __('', 'textdomain'),
		'labels' => $labels,
		'menu_icon' => 'dashicons-book',
		'supports' => array('title', 'editor'),
		'taxonomies' => array('dpf'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
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
	register_post_type('pdf', $args);

}
add_action('init', 'create_pdf_cpt', 0);




function my_pdf_future_post($new, $old, $post)
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if ('auto-draft' === $post->post_status)
		return;
	if ('draft' === $post->post_status)
		return;
	if ('future' === $post->post_status)
		return;
	if ('inherit' === $post->post_status)
		return;

	$carpeta_estatica = ABSPATH . 'wp-content/uploads/aviso-de-transparencia/' . $post->post_name;

	if ($new == 'publish') {
		if ($post->post_type == 'pdf') {
			if (!file_exists($carpeta_estatica)) {
				// wp_mkdir_p( get_home_path().'/'.$post->post_name );
				mkdir($carpeta_estatica, 0777, true);
				$re = '/"(.*?)"/m';
				preg_match_all($re, $post->post_content, $matches, PREG_SET_ORDER, 0);
				$contenido = '<?php echo \'<object data=' . $matches[0][0] . ' type="application/pdf" width="100%" height="1000"></object>\'; ?>';
				$myfile = fopen($carpeta_estatica . "/index.php", "w") or die("Unable to open file!");
				fwrite($myfile, $contenido);
				fclose($myfile);
			} else {
				$re = '/"(.*?)"/m';
				preg_match_all($re, $post->post_content, $matches, PREG_SET_ORDER, 0);
				$contenido = '<?php echo \'<object data=' . $matches[0][0] . ' type="application/pdf" width="100%" height="1000"></object>\'; ?>';
				$myfile = fopen($carpeta_estatica . "/index.php", "w") or die("Unable to open file!");
				fwrite($myfile, $contenido);
				fclose($myfile);
			}
		}
	}
}




add_action('transition_post_status', 'my_pdf_future_post', 10, 3);
add_action('publish_post', 'my_pdf_future_post', 10, 3);
add_action('draft_to_publish', 'my_pdf_future_post', 10, 3);
add_action('pending_to_publish', 'my_pdf_future_post', 10, 3);
add_action('publish_future_post', 'my_pdf_future_post', 10, 3);