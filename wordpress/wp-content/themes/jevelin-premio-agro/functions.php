<?php if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

/**
 * Load framework
 */
require_once(trailingslashit(get_template_directory()) . '/inc/init.php');


include_once('helpers/forms.php');
require_once(__DIR__ . '/metabox.php');

function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

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

add_filter('wpcf7_autop_or_not', '__return_false');


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'abc-custom-fonts',
        get_stylesheet_directory_uri() . '/css/fonts.css',
        [],
        '1.0'
    );
}, 20);



add_filter('wpseo_canonical', 'custom_canonical_url');
function custom_canonical_url($canonical)
{
    $canonical = str_replace("ms-mhsites.srv.vocento.in/abc-top-100", "top.abc.es", $canonical);
    return $canonical;
}

add_filter('wpseo_opengraph_url', 'change_url');
function change_url($url)
{
    $url = str_replace("ms-mhsites.srv.vocento.in/abc-top-100", "top.abc.es", $url);
    return $url;
}





function bybe_remove_yoast_json($data)
{

    $oldUrl = "https://ms-mhsites.srv.vocento.in/abc-top-100/";
    $newUrl = "https://top.abc.es/";

    array_walk_recursive($data, function (&$item) use ($oldUrl, $newUrl) {
        if (is_string($item)) {
            $item = str_replace($oldUrl, $newUrl, $item);
        }
    });



    return $data;
}
add_filter('wpseo_schema_webpage', 'bybe_remove_yoast_json', 10, 1);


add_filter('wpseo_schema_website', 'example_change_website');
function example_change_website($data)
{
    $oldUrl = "https://ms-mhsites.srv.vocento.in/abc-top-100/";
    $newUrl = "https://top.abc.es/";

    array_walk_recursive($data, function (&$item) use ($oldUrl, $newUrl) {
        if (is_string($item)) {
            $item = str_replace($oldUrl, $newUrl, $item);
        }
    });


    return $data;
}

add_filter('wpseo_json_ld_search_url', 'schema_change_search_url');

function schema_change_search_url()
{
    return 'https://top.abc.es/100directivospymes/?q={search_term_string}';
}


add_filter('wpseo_schema_breadcrumb', 'replace_domain_name_to_breadcrumb_schema', 11, 2);
function replace_domain_name_to_breadcrumb_schema($piece)
{
    $oldUrl = "https://ms-mhsites.srv.vocento.in/abc-top-100/";
    $newUrl = "https://top.abc.es/";
    array_walk_recursive($piece, function (&$item) use ($oldUrl, $newUrl) {
        if (is_string($item)) {
            $item = str_replace($oldUrl, $newUrl, $item);
        }
    });
    return $piece;
}


add_filter('wpseo_schema_graph', 'change_image_urls_to_cdn', 10, 2);
function change_image_urls_to_cdn($data, $context)
{
    $oldUrl = "https://ms-mhsites.srv.vocento.in/abc-top-100/";
    $newUrl = "https://top.abc.es/";
    foreach ($data as $key => $value) {
        if ($value['@type'] === 'ImageObject') {
            $data[$key]['contentUrl'] = str_replace($oldUrl, $newUrl, $value['contentUrl']);
        }

        if (isset($value['@id'])) {
            $data[$key]['@id'] = str_replace($oldUrl, $newUrl, $value['@id']);
        }
    }
    return $data;
}


add_filter('wpseo_schema_imageobject', 'replace_domain_name_to_imageobject_schema', 11, 2);
function replace_domain_name_to_imageobject_schema($piece)
{
    $oldUrl = "https://ms-mhsites.srv.vocento.in/abc-top-100/";
    $newUrl = "https://top.abc.es/";
    array_walk_recursive($piece, function (&$item) use ($oldUrl, $newUrl) {
        if (is_string($item)) {
            $item = str_replace($oldUrl, $newUrl, $item);
        }
    });
    return $piece;
}