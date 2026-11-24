<?php

if (!defined('WP_DEBUG')) {
    die('Direct access forbidden.');
}


add_action('wp_enqueue_scripts', 'exhibz_child_enqueue_styles', 99);
function exhibz_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_stylesheet_directory_uri() . '/style.css');

    wp_enqueue_style(
        'exhibz-child-fonts',
        get_stylesheet_directory_uri() . '/css/fonts.css',
        array(),
        null
    );
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
function change_canonical($title, $presentation)
{
    $actual_link = "https://$_SERVER[HTTP_HOST]";
    // echo '<!-- 000000 '.$actual_link.' -->';
    // $title = str_replace('https://ms-mhsites.srv.vocento.in', $actual_link, $title);
    $title = $actual_link;
    return $title;
}

add_filter('wpseo_canonical', 'change_canonical', 10, 2);

function change_opengraph_url($title, $presentation)
{
    $actual_link = "https://$_SERVER[HTTP_HOST]";
    // echo '<!-- 1111111 '.$actual_link.' -->';
    // $title = str_replace('https://ms-mhsites.srv.vocento.in', $actual_link, $title);
    $title = $actual_link;
    return $title;
}

add_filter('wpseo_opengraph_url', 'change_opengraph_url', 10, 2);


function change_image_urls_to_cdn($data, $context)
{
    function replace_in_array($find, $replace, &$array)
    {
        array_walk_recursive($array, function (&$array) use ($find, $replace) {
            $pos = strpos($array, $find);
            if ($pos !== false) {
                $array = str_replace($find, $replace, $array);
            }
        });
        return $array;
    }
    $actual_link = "$_SERVER[HTTP_HOST]";
    // $out = replace_in_array("ms-mhsites.srv.vocento.in", $actual_link, $data);
    $out = $actual_link;
    return $out;
}
add_filter('wpseo_schema_webpage', 'change_image_urls_to_cdn', 10, 2);


add_action('wp_head', 'hook_javascript_voont_preload', 100);

function hook_javascript_voont_preload()
{
    ?>
    <link rel="preload" as="script" href="//static.vocstatic.com/voonto2/latest/voonto.js" />
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
            n.queue = []; t = b.createElement(e); t.async = !0;
            t.src = v; s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '284335272448646');
        fbq('track', 'PageView');
    </script>
    <?php
}

add_action('wp_footer', 'hook_javascript_voonto', 100);

function hook_javascript_voonto()
{

    $output = '<script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-contentpass="b9152283"></script>';
    echo $output;
}



function exhibz_preloader_function_2()
{
    $output = '<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=284335272448646&ev=PageView&noscript=1"/></noscript>';
    echo $output;
}

add_action('wp_body_open', 'exhibz_preloader_function_2');




add_filter('the_content', 'filter_the_content_in_the_main_loop', 1);
function filter_the_content_in_the_main_loop($content)
{

    if (in_the_loop() && is_main_query()) {

        $re = '/<iframe(.*?)<\/iframe>/msx';
        $content = do_shortcode($content);
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        foreach ($matches as $match) {
            $content = str_replace($match[0], '<script type="cookiewall/html" class="my-class">' . $match[0] . '</script>', $content);
        }

        // $re = '/<script(.*)src="(.*?).twitter(.*?)><\/script>/msx';
        // preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        // foreach ($matches as $match) {
        //     $content = str_replace($match[3], $match[3] . ' type="cookiewall/script"', $content);
        // }

        return $content;
    }

    return $content;
}