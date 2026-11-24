<?php

if (!defined('WP_DEBUG')) {
    die('Direct access forbidden.');
}


add_action('wp_enqueue_scripts', 'exhibz_child_enqueue_styles', 99);
function exhibz_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_stylesheet_directory_uri() . '/style.css');
}


add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;

    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});




/**
 * cambiar datos de las etiquetas og de Yoast-SEO
 */
function change_canonical( $title, $presentation ) {
    $actual_link = "https://$_SERVER[HTTP_HOST]";
    // echo '<!-- 000000 '.$actual_link.' -->';
	// $title = str_replace('https://ms-mhsites.srv.vocento.in', $actual_link, $title);
	$title = $actual_link;
	return $title;
}

add_filter( 'wpseo_canonical', 'change_canonical', 10, 2 );

function change_opengraph_url( $title, $presentation ) {
	$actual_link = "https://$_SERVER[HTTP_HOST]";
    // echo '<!-- 1111111 '.$actual_link.' -->';
	// $title = str_replace('https://ms-mhsites.srv.vocento.in', $actual_link, $title);
	$title = $actual_link;
	return $title;
}

add_filter( 'wpseo_opengraph_url', 'change_opengraph_url', 10, 2 );


function change_image_urls_to_cdn( $data, $context ) {
    function replace_in_array($find, $replace, &$array) {
        array_walk_recursive($array, function(&$array) use($find, $replace) {
            $pos = strpos($array, $find);
            if ($pos !== false) {
                $array= str_replace($find, $replace, $array);
            }
        });
        return $array;
    }
    $actual_link = "$_SERVER[HTTP_HOST]";
	// $out = replace_in_array("ms-mhsites.srv.vocento.in", $actual_link, $data);
	$out = $actual_link;
    return $out;
}
add_filter( 'wpseo_schema_webpage', 'change_image_urls_to_cdn', 10, 2 );




// Register and enqueue all scripts in head
function custom_scripts() {

    wp_register_script( 'script_to_validate', '//static.vocstatic.com/voonto2/latest/voonto.js', false, false, false );
    wp_enqueue_script( 'script_to_validate' );

}

// Hook into the 'wp_enqueue_scripts' action
add_action( 'wp_enqueue_scripts', 'custom_scripts' );  

function add_data_attribute($tag, $handle) {
    if ( 'script_to_validate' !== $handle )
     return $tag;
 
    return str_replace( ' src', ' data-experiment="vocento/script" data-cmp="true" src', $tag );
 }
 add_filter('script_loader_tag', 'add_data_attribute', 10, 2);