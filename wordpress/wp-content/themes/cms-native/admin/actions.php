<?php

add_filter('the_content', 'add_cdn_images', 99);

function add_cdn_images($content)
{

    global $post;
    $siteLink = get_bloginfo('url');
    $content = do_shortcode($content);

    if ($post->ID == 1 || $post->ID == 910) {

        ob_start();
        echo $content;
        $content = ob_get_contents();
        ob_end_clean();

        $cdnlink = 'https://s1.ppllstatics.com/elcomercio/';
        $content = preg_replace_callback('/<img [^>]+/x', function ($matches) use ($siteLink, $cdnlink) {
            $href = $matches[0];
            if (preg_match("%src=\S\/contentfactory/post/%i", $href)) {
                $href = preg_replace("%src=\S\/contentfactory/post/%", 'src="' . $cdnlink . 'contentfactory/post/', $href);
            }
            return $href;
        }, $content);
    }
    remove_filter('the_content', 'wpautop');
    return $content;
}


class ChangeSiteContet
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'menuChangeSiteContent'));
    }


    public function menuChangeSiteContent()
    {
        add_menu_page(
            'cambio_ruta_site_content',
            'Cambio Ruta Content',
            'manage_options',
            'cambio_ruta_site_content',
            array($this, 'view_change_path_content'),
            'dashicons-admin-home',
            5
        );
        // remove_submenu_page('home_configuration', 'home_configuration');
    }

    public function view_change_path_content()
    {
        require_once('views/viewChangePathContent.php');
    }

}

if (is_admin()) {
    new ChangeSiteContet();
}
/**
 * Hook para convertir las imagenes subidas a formato WEBP
 */
add_filter('wp_handle_upload', 'wpturbo_handle_upload_convert_to_webp');
function wpturbo_handle_upload_convert_to_webp($upload)
{
    if ($upload['type'] == 'image/jpeg' || $upload['type'] == 'image/png') {
        $file_path = $upload['file'];
        // Check if ImageMagick or GD is available
        if (extension_loaded('imagick') || extension_loaded('gd')) {
            $image_editor = wp_get_image_editor($file_path);
            if (!is_wp_error($image_editor)) {
                $file_info = pathinfo($file_path);
                $dirname = $file_info['dirname'];
                $filename = $file_info['filename'];
                $filename = uniqid() . '.' . $filename;
                // Create a new file path for the WebP image
                $new_file_path = $dirname . '/' . $filename . '.webp';
                // Attempt to save the image in WebP format
                $saved_image = $image_editor->save($new_file_path, 'image/webp');
                if (!is_wp_error($saved_image) && file_exists($saved_image['path'])) {
                    // Success: replace the uploaded image with the WebP image
                    $upload['file'] = $saved_image['path'];
                    $upload['url'] = str_replace(basename($upload['url']), basename($saved_image['path']), $upload['url']);
                    $upload['type'] = 'image/webp';
                    // Optionally remove the original image
                    @unlink($file_path);
                }
            }
        }
    }
    return $upload;
}