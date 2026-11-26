<?php


if (!is_admin()) {
    echo "<!-- si que entra -->";
    add_filter('no-admin-ajax/keyword', 'my_custom_no_admin_ajax_url');
    function my_custom_no_admin_ajax_url($ajax_url)
    {
        return "wp-content/ajaxrewrite.php";
    }
}

define( 'CHILD_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

function theme_enqueue_styles()
{

    $parent_style = 'parent-style';

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/rtl.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/css/editor-style.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/css/meta-box-styles.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/css/theme-customizer-controls-styles.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/epanel/css/panel.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/epanel/google-fonts/et_google_fonts.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/epanel/shortcodes/css/shortcodes.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/epanel/shortcodes/css/shortcodes_responsive.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/advanced_options.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/jquery-ui-1.10.4.custom.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/library_menu.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/library_pages.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/magnific_popup.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/preview.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/roles_style.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/style.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/includes/builder/styles/widgets.css');
    wp_enqueue_script('isInViewport', get_bloginfo('stylesheet_directory') . '/js/isInViewport.js', array('jquery'), CHILD_THEME_VERSION);

    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style)
    );
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

//add_action('init', 'html_page_permalink', -1);
//function html_page_permalink() {
// global $wp_rewrite;
//  if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
//      $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
//   }
//}


//function estilos_lorca() {
//	wp_enqueue_style( 'estiloincendio', get_stylesheet_directory_uri().'/estilo.css', false );
//}
//add_action( 'wp_enqueue_scripts', 'estilos_incendio' );

//function scripts_lorca() {
//
//}
//
//add_action('wp_enqueue_scripts', 'scripts_incendio');

function comscore_analytics_head()
{
    ?>



    <?php

}
add_action('wp_enqueue_scripts', 'comscore_analytics_head');

function comscore_footer()
{
    ?>




    <?php
}
add_action('wp_footer', 'comscore_footer');

function creditos_footer()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("#footer-info").text('');
            jQuery("<p>© LA VERDAD MULTIMEDIA, S.A.</p>").insertAfter("#footer-info");
            //     jQuery('.header-content').delay(2000).fadeIn(5200);
            //     jQuery('.entry-content video').removeAttr('loop');
            //     jQuery('.logo_container a').attr('href', 'http://www.laverdad.es');
        });
    </script>
    <script>
        jQuery(document).ready(function ($) {
            "use strict";
            $(window).scroll(function () {
                $('video').each(function () {
                    if ($(this).is(":in-viewport()")) {
                        $(this)[0].play();
                    } else {
                        $(this)[0].pause();
                    }
                });
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'creditos_footer');
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
