<?php

class CacheExternalFile
{
    static private $timeToCache;

    public function __construct($timeToCache = 900)
    {
        CacheExternalFile::$timeToCache = $timeToCache;
    }

    static public function loadHTML($url)
    {
        $fileName = CacheExternalFile::nameCacheFile($url);
        if (file_exists(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName) && time() - CacheExternalFile::$timeToCache < filemtime(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName)) {
            $html = file_get_contents(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName);
        } else {
            $html = null;
        }

        return $html;
    }

    static public function nameCacheFile($url)
    {
        $break = Explode('/', $url);

        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('_', '_', "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_");

        $fileName = str_replace($entities, $replacements, urlencode(implode("", $break)));
        // $fileName = str_replace('.', '_', $fileName);
        // $fileName = str_replace('-', '_', $fileName);

        return $fileName;
    }

    static public function deletecache()
    {
        $dir = WP_CONTENT_DIR . '/uploads/headercache/';

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", "..", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12",))) {
                unlink($dir . $value);
            }
        }
    }

    static public function getHtmlLayoutURL($url, $fileName)
    {
        $cabecera = "";
        if (CacheExternalFile::loadHTML($fileName)) {
            $cabecera = CacheExternalFile::loadHTML($fileName);
        } else {
            try {
                $cabecera = file_get_contents($url);
                $fichero = WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName;

                if (!is_dir(WP_CONTENT_DIR . '/uploads/headercache/')) {
                    wp_mkdir_p(WP_CONTENT_DIR . '/uploads/headercache/');
                }

                file_put_contents($fichero, $cabecera, FILE_USE_INCLUDE_PATH);
            } catch (\Exception $e) {
                // var_dump($e->getRequest());
            }
        }
        return $cabecera;
    }
}

add_filter('the_content', 'addLazyLoadToImage', 10);

function addLazyLoadToImage($content)
{
    global $post;

    if ($post->ID == 1 || $post->ID == 910) {
        $content = do_shortcode($content);


        ob_start();
        echo $content; 
        $content = ob_get_contents();
        ob_end_clean();
        
        
        // $re = '/<img(.*?)>/msx';
        $re = '/<img(.*?)src="(.*?)">/msx';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

        $contador = 0;
        foreach ($matches as $match) {

            $html = $match[0];
            if ($contador > 2) {
                $html = str_replace('<img', '<img loading="lazy"', $html);
                $content = str_replace($match[0], $html, $content);
            }
            $contador++;
        }

        $hostUrl = "https://www.elcorreo.com";


        $re = '/<img(.*?)src=(.*?)"(.*?)"/ms';

        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $match[3] = str_replace('"', '', $match[3]);
            $match[3] = str_replace("'", '', $match[3]);

            $imgurl = $hostUrl . $match[3];
            $re = '/(.*)\//m';
            $subst = "";
            $result = preg_replace($re, $subst, $match[3]);
            $result = str_replace(['jpg', 'png', 'jpeg'], '', $result);
            $result = preg_replace('/[^A-Za-z0-9\-.]/', '', $result);
            $posSVG = strpos($result, '.svg');
            if ($posSVG === false) {
                $result = str_replace('.', '', $result);
                $attachment_id = rudr_upload_file_by_url($imgurl, $result . '.webp');
                $custom_image_webp =  wp_get_attachment_url($attachment_id);
                if($custom_image_webp){
                    var_dump("si que subo la imagen ".$custom_image_webp);
                    $content = str_replace($match[3], $custom_image_webp, $content);
                }
            }

            // $attachment_id = rudr_upload_file_by_url($imgurl, $result . '.webp');
            // $custom_image_webp =  wp_get_attachment_url($attachment_id);
            // $content = str_replace($match[3], $custom_image_webp, $content);
        }
        remove_filter('the_content','wpautop');
        return $content;
    } else {
        return $content;
    }
}


function rudr_upload_file_by_url($image_url, $name)
{

    // $image_url = "https://www.elcorreo.com/contentfactory/post/wp-content/uploads/sites/4/2024/04/españa-xiaomi.png";
    // $name = "espana-xiaomi.webp";
    
    // require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // $temp_file = download_url('https://www.elcorreo.com/contentfactory/post/wp-content/themes/cms-native/admin/imagewebp.php?url=' . $image_url);

    $temp_file = download_url($image_url);
    $file = create_webp($temp_file);
    var_dump($file);


    if (is_wp_error($temp_file)) {
        return false;
    }
    global $wpdb;
    $name = sanitize_file_name( $name );
    $uploads = wp_upload_dir();
    $filepath = $uploads["basedir"] . $uploads["subdir"] . '/' . $name;
    $image_src = wp_upload_dir()['baseurl'] . '/' . _wp_relative_upload_path($filepath);
    $query = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE guid='$image_src'";
    $count = intval($wpdb->get_var($query));
    $size = getimagesize($image_url);

    $allowed_mimes = array(
        'jpg|jpeg|jpe'	=> 'image/jpeg',
        'gif'			=> 'image/gif',
        'png'			=> 'image/png',
        'webp'          => 'image/webp'
    );

    if ($size) {
        if ($count > 0) {
            $attachment_id = get_attachment_id_by_slug($name);
            return $attachment_id;
        } else {
            $file = array(
                'name'     => basename($name),
                'type'     => mime_content_type($temp_file),
                'tmp_name' => $temp_file,
                'size'     => filesize($temp_file),
            );
            $sideload = wp_handle_sideload(
                $file,
                array(
                    'test_form'   => false,
                    'mimes'		=> $allowed_mimes,
                )
            );
            if (!empty($sideload['error'])) {
                var_dump($sideload['error']);
                return false;
            }

            $attachment_id = wp_insert_attachment(
                array(
                    'guid'           => $sideload['url'],
                    'post_mime_type' => $sideload['type'],
                    'post_title'     => basename($sideload['file']),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                ),
                $sideload['file']
            );

            if (is_wp_error($attachment_id) || !$attachment_id) {
                return false;
            }

            require_once(ABSPATH . 'wp-admin/includes/image.php');

            wp_update_attachment_metadata(
                $attachment_id,
                wp_generate_attachment_metadata($attachment_id, $sideload['file'])
            );

            return $attachment_id;
        }
    }
}


function get_attachment_url_by_slug($slug)
{
    $args = array(
        'post_type' => 'attachment',
        'name' => sanitize_title($slug),
        'posts_per_page' => 1,
        'post_status' => 'inherit',
    );
    $_header = get_posts($args);
    $header = $_header ? array_pop($_header) : null;
    return $header ? wp_get_attachment_url($header->ID) : '';
}

function get_attachment_id_by_slug($slug)
{
    $args = array(
        'post_type' => 'attachment',
        'name' => sanitize_title($slug),
        'posts_per_page' => 1,
        'post_status' => 'inherit',
    );
    $_header = get_posts($args);
    $header = $_header ? array_pop($_header) : null;
    return $header->ID;
}


function create_webp($file) {

    if ($file['type'] === "image/png") {
    // Create and save
    $img = imagecreatefrompng($file['file']);
    imagepalettetotruecolor($img);  
    imagealphablending($img, true);
    imagesavealpha($img, true);
    imagewebp($img, str_replace(".png" ,".webp", $file['file']), 100);
    imagedestroy($img);

}
elseif($file['type'] === "image/jpg" || $file['type'] === "image/jpeg"){
    $img = imagecreatefromjpeg($file['file']); 
    imagepalettetotruecolor($img);  
    imagealphablending($img, true);
    imagesavealpha($img, true);
    if($file['type'] === "image/jpg"){
        imagewebp($img, str_replace(".jpg" ,".webp", $file['file']), 100);
    }
    else{
        imagewebp($img, str_replace(".jpeg" ,".webp", $file['file']), 100);
    }
    imagedestroy($img);
  
}

return $file;
}
