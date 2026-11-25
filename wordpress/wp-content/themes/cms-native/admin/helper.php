<?php

// class CacheExternalFile
// {
//     static private $timeToCache;

//     public function __construct($timeToCache = 900)
//     {
//         CacheExternalFile::$timeToCache = $timeToCache;
//     }

//     static public function loadHTML($url)
//     {
//         $fileName = CacheExternalFile::nameCacheFile($url);
//         if (file_exists(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName) && time() - CacheExternalFile::$timeToCache < filemtime(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName)) {
//             $html = file_get_contents(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName);
//         } else {
//             $html = null;
//         }

//         return $html;
//     }

//     static public function nameCacheFile($url)
//     {
//         $break = Explode('/', $url);

//         $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
//         $replacements = array('_', '_', "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_");

//         $fileName = str_replace($entities, $replacements, urlencode(implode("", $break)));
//         // $fileName = str_replace('.', '_', $fileName);
//         // $fileName = str_replace('-', '_', $fileName);

//         return $fileName;
//     }

//     static public function deletecache()
//     {
//         $dir = WP_CONTENT_DIR . '/uploads/headercache/';

//         $cdir = scandir($dir);
//         foreach ($cdir as $key => $value) {
//             if (!in_array($value, array(".", "..", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12",))) {
//                 unlink($dir . $value);
//             }
//         }
//     }

//     static public function getHtmlLayoutURL($url, $fileName)
//     {
//         $cabecera = "";
//         if (CacheExternalFile::loadHTML($fileName)) {
//             $cabecera = CacheExternalFile::loadHTML($fileName);
//         } else {
//             try {
//                 $cabecera = file_get_contents($url);
//                 $fichero = WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName;

//                 if (!is_dir(WP_CONTENT_DIR . '/uploads/headercache/')) {
//                     wp_mkdir_p(WP_CONTENT_DIR . '/uploads/headercache/');
//                 }

//                 file_put_contents($fichero, $cabecera, FILE_USE_INCLUDE_PATH);
//             } catch (\Exception $e) {
//                 // var_dump($e->getRequest());
//             }
//         }
//         return $cabecera;
//     }
// }

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


/**
 * Nuevo campo activación cabeceras
 */
add_action('admin_init', 'my_general_section');
function my_general_section() {
    add_settings_section(
        'my_settings_section_header', // Section ID
        'Activación Header', // Section Title
        'my_section_options_callback_api_header', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );

    add_settings_field( // Option 1
        'apiheaderdate', // Option ID
        'Fecha:', // Label
        'my_api_header_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section_header', // Name of our section
        array( // The $args
            'apiheaderdate' // Should match Option ID
        )
    );


    register_setting('general','apiheaderdate', 'esc_attr');
}

function my_section_options_callback_api_header() { // Section Callback
    // echo '<p>A little message on editing info</p>';
}

function my_api_header_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    // echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
    echo '<input type="date" id="start" name="'. $args[0] .'" value="' . $option . '" />';
}




/**
 * Generar preview públcia
 */

function generar_token_borrador($post_id) {
    // Evitar que se ejecute en auto-salvados o revisiones
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    // Solo si el post está en borrador
    if (get_post_status($post_id) === 'draft') {
        $token = get_post_meta($post_id, '_mi_token_secreto', true);
        if (empty($token)) {
            // Generar un token aleatorio seguro de 32 caracteres
            $token = wp_generate_password(32, false);
            update_post_meta($post_id, '_mi_token_secreto', $token);
        }
    }
}
add_action('save_post', 'generar_token_borrador');


function mostrar_previsualizacion_publica_segura() {
    // Verificamos si están presentes los parámetros en la URL
    if (isset($_GET['preview_post_id']) && isset($_GET['preview_token'])) {
        $post_id = intval($_GET['preview_post_id']);
        $token_enviado = sanitize_text_field($_GET['preview_token']);

        // Obtener el token válido guardado para este post
        $token_valido = get_post_meta($post_id, '_mi_token_secreto', true);

        // Validar que el token exista y coincida de forma segura (contra ataques de tiempo)
        if (!empty($token_valido) && hash_equals($token_valido, $token_enviado)) {
            $post = get_post($post_id);

            $apiheaderdate = get_option('apiheaderdate');
            var_dump($apiheaderdate);
            $date1 = new DateTime($apiheaderdate);
            $date2 = new DateTime(get_the_time('Y-m-d', $post_id));

            if ($date1 < $date2) {
            	$url_medio2 = $_SERVER['HTTP_HOST'];

            	$re2 = '/(.+?)\./s';
            	preg_match($re2, $url_medio2, $matches, PREG_OFFSET_CAPTURE, 0);
            	$tld = str_replace($matches[0][0], "", $_SERVER['HTTP_HOST']);
            	$id_krux = '';
            	$medios = array('eleconomista.es', 'eslang.es', 'finanzas.com', 'lavozdegalicia.es', 'menorca.info', 'mujerhoy.com', 'periodicodeibiza.es', 'ultimahora.es', 'welife.es', 'womennow.es', 'xlsemanal.com', 'relevo.com', 'sevilla.abc.es', 'turium.es');


            	$pos = strpos($url_medio2, 'abc.es');

            	if (in_array($tld, $medios)) {
            	  echo 'entro en lo bueno';
            		get_template_part('partials/single', '');
            	} else {
            		if ($post_id == 638) {
            			get_template_part('partials/single', '2026');
            		} else {
            			get_template_part('partials/single', '2024');
            		}
            	}
            } else {
              get_template_part('partials/single', '');
            }
        } else {
            wp_die('El enlace de previsualización no es válido o ha expirado.', 'Acceso Denegado', array('response' => 403));
        }
    }
}
add_action('template_redirect', 'mostrar_previsualizacion_publica_segura');

/**
 * 1. Registrar la Meta Box en la pantalla de edición
 */
function registrar_caja_previsualizacion_publica() {
    add_meta_box(
        'meta_box_previsualizacion_publica',     // ID único de la caja
        'Enlace de Previsualización Pública',    // Título visible de la caja
        'renderizar_caja_previsualizacion',      // Función que pinta el contenido
        'post',                                  // Tipo de contenido ('post', 'page', o custom post type)
        'side',                                  // Posición ('side' para la barra lateral, 'normal' para abajo)
        'high'                                   // Prioridad de visualización
    );
}
add_action('add_meta_boxes', 'registrar_caja_previsualizacion_publica');

/**
 * 2. Renderizar el contenido HTML dentro de la Meta Box
 */
function renderizar_caja_previsualizacion($post) {
    // Comprobar si el post está en borrador
    $status = get_post_status($post->ID);

    if ($status !== 'draft' && $status !== 'pending') {
        echo '<p style="color: #666; font-style: italic; margin: 0;">Esta previsualización pública solo está disponible para posts en estado de borrador o pendiente.</p>';
        return;
    }

    // Obtener el token secreto generado previamente
    $token = get_post_meta($post->ID, '_mi_token_secreto', true);

    if (empty($token)) {
        echo '<p style="margin: 0;">Guarda los cambios como borrador para generar el enlace de previsualización pública.</p>';
        return;
    }

    // Construir la URL completa de previsualización
    $preview_url = home_url('/?preview_post_id=' . $post->ID . '&preview_token=' . $token);

    ?>
    <div class="inside" style="padding: 0; margin-top: 5px;">
        <p style="margin: 0 0 8px 0; font-size: 12px; color: #50575e;">
            Comparte este enlace con personas que no tengan cuenta en la web:
        </p>
        <div style="display: flex; gap: 5px; align-items: center;">
            <input type="text" id="input-enlace-prev" readonly="readonly" value="<?php echo esc_url($preview_url); ?>" style="width: 100%; background: #f0f0f1; padding: 6px; font-size: 11px; border: 1px solid #8c8f94; border-radius: 4px;" onclick="this.select();" />
            <button type="button" class="button button-small" onclick="copiarEnlacePrev(this)" style="white-space: nowrap;">Copiar</button>
        </div>
        <p style="margin: 8px 0 0 0; font-size: 11px; color: #72777c; font-style: italic;">
            Haz clic en el campo para seleccionarlo automáticamente.
        </p>
    </div>

    <script>
    function copiarEnlacePrev(button) {
        var copyText = document.getElementById("input-enlace-prev");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // Para móviles
        document.execCommand("copy");

        var originalText = button.innerText;
        button.innerText = "¡Copiado!";
        setTimeout(function() {
            button.innerText = originalText;
        }, 2000);
    }
    </script>
    <?php
}
/**
 * Fin Generar preview públcia
 */
