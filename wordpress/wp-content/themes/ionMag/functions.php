<?php
/*
    Load the speed booster framework + theme specific files
*/

// load the deploy mode
require_once('td_deploy_mode.php');
require_once('ImportMagentoData.php');

// load the config
require_once('includes/td_config.php');
add_action('td_global_after', array('td_config', 'on_td_global_after_config'), 9); //we run on 9 priority to allow plugins to updage_key our apis while using the default priority of 10



// check and load the wp_booster framework
//if (!file_exists('includes/wp_booster/td_wp_booster_functions.php')) {
//    echo ':( wp_booster Framework not found! The framework should be in ' . TD_THEME_NAME . '/includes/wp_booster';
//    die;
//}
require_once('includes/wp_booster/td_wp_booster_functions.php');

require_once('includes/td_css_generator.php');
require_once('includes/widgets/td_page_builder_widgets.php'); // widgets

//td_demo_state::update_state("premium_magazine", 'full');

/* ----------------------------------------------------------------------------
 * Woo Commerce
 */

// breadcrumb
add_filter('woocommerce_breadcrumb_defaults', 'td_woocommerce_breadcrumbs');
function td_woocommerce_breadcrumbs()
{
    return array(
        'delimiter' => ' <i class="td-icon-right td-bread-sep"></i> ',
        'wrap_before' => '<div class="entry-crumbs" itemprop="breadcrumb">',
        'wrap_after' => '</div>',
        'before' => '',
        'after' => '',
        'home' => _x('Home', 'breadcrumb', 'woocommerce'),
    );
}

// use own pagination
if (!function_exists('woocommerce_pagination')) {
    // pagination
    function woocommerce_pagination()
    {
        echo td_page_generator::get_pagination();
    }
}

// Override theme default specification for product 3 per row
add_filter('loop_shop_columns', 'td_wc_loop_shop_columns', 1, 10);
function td_wc_loop_shop_columns($number_columns)
{
    return 4;
}

// Number of product per page 8
add_filter('loop_shop_per_page', function($cols) {
    return 8;
});

if (!function_exists('woocommerce_output_related_products')) {
    // Number of related products
    function woocommerce_output_related_products()
    {
        woocommerce_related_products(array(
            'posts_per_page' => 4,
            'columns' => 4,
            'orderby' => 'rand',
        )); // Display 4 products in rows of 1
    }
}

/**
 * tdStyleCustomizer.js is required
 */
if (TD_DEBUG_LIVE_THEME_STYLE) {
    add_action('wp_footer', 'td_theme_style_footer');
    function td_theme_style_footer()
    {
?>
        <div id="td-theme-settings" class="td-live-theme-demos td-theme-settings-small">
            <div class="td-skin-body">
                <div class="td-skin-wrap">
                    <div class="td-skin-container td-skin-buy"><a target="_blank" href="https://www.wpion.com/free-news-theme/">FREE DOWNLOAD NOW!</a></div>
                    <div class="td-skin-container td-skin-header">GET AN AWESOME START!</div>
                    <div class="td-skin-container td-skin-desc">The theme comes with the following demos. You can start your site using one of them or make your own design.</div>
                    <div class="td-skin-container td-skin-content">
                        <div class="td-demos-list">
                            <?php
                            $td_demo_names = array();

                            foreach (td_global::$demo_list as $demo_id => $stack_params) {
                                $td_demo_names[$stack_params['text']] = $demo_id; ?>
                                <div class="td-set-theme-style"><a href="<?php echo td_global::$demo_list[$demo_id]['demo_url'] ?>" class="td-set-theme-style-link td-popup td-popup-<?php echo $td_demo_names[$stack_params['text']] ?>" data-img-url="<?php echo td_global::$get_template_directory_uri ?>/demos_popup/large/<?php echo $demo_id; ?>.jpg"><span></span></a></div>
                            <?php
                            } ?>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty1"></a></div>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty5"></a></div>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty2"></a></div>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty6"></a></div>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty3"></a></div>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty7"></a></div>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty4"></a></div>
                            <div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty8"></a></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="td-skin-scroll"><i class="td-icon-read-down"></i></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="td-set-hide-show"><a href="#" id="td-theme-set-hide"></a></div>
            <div class="td-screen-demo" data-width-preview="380"></div>
            <div class="td-screen-demo-extend"></div>
        </div>
    <?php
    }
}

//td_demo_state::update_state("blog_baby", 'full');

/* permitir subir archivos con extensión svg*/
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes($existing_mimes = array())
{
    // Add the file extension to the array
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['svgz'] = 'image/svg+xml';
    return $existing_mimes;
}

function custom_excerpt_length($length)
{
    return 18;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

add_action('add_meta_boxes', 'stick_metabox');
function stick_metabox()
{
    add_meta_box('stick-post', 'Post Destacado', 'stick_post_metabox', 'post', 'side', 'high');
}

function stick_post_metabox($post)
{
    $values = get_post_custom($post->ID);
    $check = isset($values['my_sticky_box_check']) ? esc_attr($values['my_sticky_box_check'][0]) : '';
    wp_nonce_field('my_sticky_box_nonce', 'sticky_box_nonce'); ?>
    <p>
        <input type="checkbox" id="my_sticky_box_check" name="my_sticky_box_check" <?php checked($check, 'on'); ?> />
        <label for="my_sticky_box_check">Marcar este post como destacado</label>
    </p>
<?php
}

add_action('save_post', 'sticky_meta_box_save');
function sticky_meta_box_save($post_id)
{
    // Bail if we're doing an auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // if our nonce isn't there, or we can't verify it, bail
    if (!isset($_POST['sticky_box_nonce']) || !wp_verify_nonce($_POST['sticky_box_nonce'], 'my_sticky_box_nonce')) {
        return;
    }

    // if our current user can't edit this post, bail
    if (!current_user_can('edit_post')) {
        return;
    }

    $check = isset($_POST['my_sticky_box_check']) && $_POST['my_sticky_box_check'] ? 'on' : 'off';


    /*var_dump($post_id);
    die('0000');*/

    if ($check == 'on') {
        $second_loop = get_posts(array(
            'meta_key'   => 'my_sticky_box_check',
            'meta_value' => 'on',
        ));

        if (empty($second_loop)) {
            update_post_meta($post_id, 'my_sticky_box_check', $check);
        } else {
            if ($post_id != $second_loop[0]->ID) {
                update_post_meta($post_id, 'my_sticky_box_check', $check);
                update_post_meta($second_loop[0]->ID, 'my_sticky_box_check', 'off');
            } else {
                update_post_meta($post_id, 'my_sticky_box_check', $check);
            }
        }
    } else {
        update_post_meta($post_id, 'my_sticky_box_check', $check);
    }
}

function pidecita_replace_domain($content)
{
    if (!is_admin()) {
        $urlpars = parse_url($content);

        if (is_admin()) {
            $content = str_replace('/.', '', $content);
            return $content;
        }

        if (strpos($content, 'blog.pidecita.com') > 0) {
            $content = str_replace('/.', '', $urlpars["path"]);
            if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
                return "https://pre.pidecita.com" . $content;
            } else {
                return "https://www.pidecita.com" . $content;
            }
        } else {
            return $content;
        }
    } else {
        $content = str_replace('/.', '', $content);
        return $content;
    }
}

function pidecita_replace_domain2($content)
{
    $urlpars = parse_url($content);
    if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        return "https://pre.pidecita.com" . $urlpars["path"];
    } else {
        return "https://www.pidecita.com" . $urlpars["path"];
    }
}

add_filter('nav_menu_link_attributes', 'filter_function_name', 10, 3);

function filter_function_name($atts, $item, $args)
{
    $slug = $atts['href'];
    if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        $atts['href'] = str_replace("blog.pidecita.com", "pre.pidecita.com", $slug);
    } else {
        $atts['href'] = str_replace("blog.pidecita.com", "www.pidecita.com", $slug);
    }

    return $atts;
}

// add_filter('script_uri', 'pidecita_replace_domain');
// add_filter('stylesheet_uri', 'pidecita_replace_domain');
// add_filter('bloginfo_url', 'pidecita_replace_domain');
// add_filter('bloginfo', 'pidecita_replace_domain');
// add_filter('post_link', 'pidecita_replace_domain');
// add_filter('category_link', 'pidecita_replace_domain');
// add_filter('category_feed_link', 'pidecita_replace_domain');
// add_filter('wp_get_attachment_url', 'pidecita_replace_domain2');
// add_filter('wp_get_attachment_thumb_url', 'pidecita_replace_domain2');
// add_filter('post_comments_feed_link', 'pidecita_replace_domain');
// add_filter('author_link', 'pidecita_replace_domain');
// add_filter('author_feed_link', 'pidecita_replace_domain');
// add_filter('plugins_url', 'pidecita_replace_domain');
// add_filter('bloginfo_rss', 'pidecita_replace_domain');
// add_filter('home_url', 'pidecita_replace_domain');
// add_filter('script_loader_src', 'pidecita_replace_domain');
// add_filter('style_loader_src', 'pidecita_replace_domain');
// add_filter('stylesheet_directory_uri', 'pidecita_replace_domain');
// add_filter('site_url', 'pidecita_replace_domain');
// add_filter('template_directory_uri', 'pidecita_replace_domain');
// add_filter('esc_url', 'pidecita_replace_domain');


function my_the_content_filter($content)
{
    $content = do_shortcode($content);
    $content = str_replace('blog.pidecita.com', 'www.pidecita.com', $content);
    $content = str_replace("blog.pidecita.com", "www.pidecita.com", $content);
    $content = str_replace("/.", "", $content);
    return $content;
}
add_filter('the_content', 'my_the_content_filter');

add_filter('script_loader_tag', 'add_id_to_script', 10, 3);
function add_id_to_script($tag, $handle, $src)
{
    // The handles of the enqueued scripts we want to defer
    $defer_scripts = array(
        'ajax-load-more'
    );

    /*
    $src = str_replace( $_SERVER[HTTP_HOST], "//www.abc.es/familia/supersanos/concurso/", $src );
        return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";*/

    if (in_array($handle, $defer_scripts)) {
        $src = str_replace($_SERVER[HTTP_HOST], "//www.pidecita.com/", $src);
        return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
    }

    return $tag;
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
