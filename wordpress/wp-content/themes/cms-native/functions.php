<?php
require_once('custom-categories.php');
require_once('canonical.php');
require_once('custom-addons.php');
require_once('keywords.php');
include_once('admin/desakamai.php');
include_once('admin/helper.php');
include_once('admin/actions.php');
include_once('admin/sitemap.php');

include_once("post_without_canonical.php");

// $newperformance = $_GET['newperformance'];
// if ($newperformance == 'validatenewperformance') {
// }


add_filter('wp_robots', function ($robots) {
    unset($robots['max-image-preview']);
    return $robots;
});

add_action('wp_head', 'insert_in_head', 1);
function insert_in_head()
{
    echo '<meta name="robots" content="index,follow,noarchive,max-snippet:-1,max-image-preview:large,max-video-preview:-1">';
}

show_admin_bar(false);

//getcontenido html
function getContentH($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $inc_obj = curl_exec($ch);

    curl_close($ch);
    return $inc_obj;
}

//getcontenido nav mobile
function getContentNavMobile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $inc_obj = curl_exec($ch);

    curl_close($ch);
    return $inc_obj;
}


/* permitir subir archivos con extensión svg*/
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes($existing_mimes = array())
{
    // Add the file extension to the array
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['svgz'] = 'image/svg+xml';
    return $existing_mimes;
}
//permite visualizar los post más vistos y el numero de vistas de cada uno


/* Contador de visitas para cada post*/

function getPostViews($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return 0;
    }
    return $count;
}

function setPostViews($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


//funcion que extrae el valor de un campo determiando de la  taxonomia "Medios"
function valueByUrlMedio($post_id, $url_medio, $campoRequerido)
{
    $taxName = "Medios";
    if ($post_id == null || $post_id == "") {
        // $terms =  get_terms($taxName);
        $terms = get_terms(
            array(
                'taxonomy' => 'Medios',
                'update_term_meta_cache' => false,
            )
        );
    } else {
        $terms =  wp_get_post_terms($post_id, $taxName);
    }
    foreach ($terms as $term) {
        $url_medioCheck = get_field('url_medio', $term);
        if ($url_medioCheck == $url_medio) {
            $custom_value = get_field($campoRequerido, $term);
        }
    }
    if (isset($custom_value)) {
        //devolviendo valores obtenidos
        return $custom_value;
    } else {
        return false;
    }
}


function checkdomain($post_id, $url_medio)
{
    $taxName = "Medios";
    if ($post_id == null || $post_id == "") {
        // $terms =  get_terms($taxName);
        $terms = get_terms(
            array(
                'taxonomy' => 'Medios',
                'update_term_meta_cache' => false,
            )
        );
    } else {
        $terms =  wp_get_post_terms($post_id, $taxName);
    }
    foreach ($terms as $term) {
        $url_medioCheck = get_field('url_medio', $term);
        if ($url_medioCheck == $url_medio) {
            return true;
        }
    }

    return false;
}
//hook into the init action and call create_topics_nonhierarchical_taxonomy when it fires

add_action('init', 'create_Medios', 0);

function create_Medios()
{
    //quitando editor de Medios si no es usuario admin
    $user = wp_get_current_user();
    if ($user->roles[0] != 'administrator') {
        add_action('admin_menu', 'adjust_the_wp_menu', 999);
        function adjust_the_wp_menu()
        {
            remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=Medios');
        }
    }
    // Labels part for the GUI

    $labels = array(
        'name' => _x('Medios', 'taxonomy general name'),
        'singular_name' => _x('Medio', 'taxonomy singular name'),
        'search_items' =>  __('Search Medios'),
        'popular_items' => __('Popular Medios'),
        'all_items' => __('All Medios'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Medio'),
        'update_item' => __('Update Medio'),
        'add_new_item' => __('Add New Medio'),
        'new_item_name' => __('New Medio Name'),
        'separate_items_with_commas' => __('Separate medios with commas'),
        'add_or_remove_items' => __('Add or remove Medios'),
        'choose_from_most_used' => __('Choose from the most used Medios'),
        'menu_name' => __('Medios'),
    );

    // Now register the non-hierarchical taxonomy like tag

    register_taxonomy('Medios', 'post', array(
        'hierarchical' => true,
        'labels' => $labels,

    ));
}



// A callback function to add a custom field to our "presenters" taxonomy
function presenters_taxonomy_custom_fields($tag)
{
    // Check for existing taxonomy meta for the term you're editing
    $t_id = $tag->term_id; // Get the ID of the term you're editing
    $term_meta = get_option("taxonomy_term_" . $t_id); // Do the check

    if (is_plugin_active('vocento-analytics/vocento-analytics.php')) {
?>

        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="dtm_data_property_id">DATA PROPERTY ID</label>
            </th>
            <td>
                <input type="text" name="term_meta[dtm_data_property_id]" id="term_meta[dtm_data_property_id]" size="25" style="width:60%;" value="<?php echo $term_meta['dtm_data_property_id'] ? $term_meta['dtm_data_property_id'] : ''; ?>"><br />
                <span class="description">DATA PROPERTY ID para el medio</span>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="dtm_reportsuit">REPORT SUITE</label>
            </th>
            <td>
                <input type="text" name="term_meta[dtm_reportsuit]" id="term_meta[dtm_reportsuit]" size="25" style="width:60%;" value="<?php echo $term_meta['dtm_reportsuit'] ? $term_meta['dtm_reportsuit'] : ''; ?>"><br />
                <span class="description">REPORT SUITE para el medio</span>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="v55v56">NOSCRIPT v55 - v56</label>
            </th>
            <td>
                <input type="text" name="term_meta[v55v56]" id="term_meta[v55v56]" size="25" style="width:60%;" value="<?php echo $term_meta['v55v56'] ? $term_meta['v55v56'] : ''; ?>"><br />
                <span class="description">Valor noscript v55 - v56</span>
            </td>
        </tr>

    <?php
    }
}


// A callback function to save our extra taxonomy field(s)
function save_taxonomy_custom_fields($term_id)
{
    if (isset($_POST['term_meta'])) {
        $t_id = $term_id;
        $term_meta = get_option("taxonomy_term_" . $t_id);
        $cat_keys = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        //save the option array
        update_option("taxonomy_term_" . $t_id, $term_meta);
    }
}

// Add the fields to the "presenters" taxonomy, using our callback function
add_action('Medios_edit_form_fields', 'presenters_taxonomy_custom_fields', 10, 2);

// Save the changes made on the "presenters" taxonomy, using our callback function
add_action('edited_Medios', 'save_taxonomy_custom_fields', 10, 2);

function generate_header_tagmanager($dataTrack)
{
    $url_medio = 'http://' . $_SERVER['HTTP_HOST'];
    $re = '/\.(.+?)\./s';
    preg_match($re, $url_medio, $matches, PREG_OFFSET_CAPTURE, 0);
    $term = get_term_by('slug', $matches[1][0], 'Medios');
    $taxvalue = get_option('taxonomy_term_' . $term->term_id);

    $url_medio2 = $_SERVER['HTTP_HOST'];
    $re2 = '/(.+?)\./s';
    preg_match($re2, $url_medio2, $matches, PREG_OFFSET_CAPTURE, 0);
    $tld = str_replace($matches[0][0], "", $_SERVER['HTTP_HOST']);

    $id_krux = '';
    echo '<!-- PPPPPPPPPPPP ' . $tld . ' -->';
    switch ($tld) {
        case "abc.es":
            $id_krux = "qsba75l5v";
            break;
        case "burgosconecta.es":
            $id_krux = "sc1zfkkix";
            break;
        case "codigounico.com":
            $id_krux = "sxj5u97gw";
            break;
        case "diariosur.es":
            $id_krux = "qxobqleza";
            break;
        case "diariovasco.com":
            $id_krux = "qxobpcvnb";
            break;
        case "elcomercio.es":
            $id_krux = "qwote8h50";
            break;
        case "elcorreo.com":
            $id_krux = "qxobju7rp";
            break;
        case "eldiariomontanes.es":
            $id_krux = "qxobspj7r";
            break;
        case "elnortedecastilla.es":
            $id_krux = "qxoborp8n";
            break;
        case "hoy.es":
            $id_krux = "qxobgd9vm";
            break;
        case "ideal.es":
            $id_krux = "qxobpzqdj";
            break;
        case "larioja.com":
            $id_krux = "qxobk17rj";
            break;
        case "lasprovincias.es":
            $id_krux = "qxobrr4p3";
            break;
        case "laverdad.es":
            $id_krux = "qxobtir2y";
            break;
        case "lavozdigital.es":
            $id_krux = "qwotcj0qp";
            break;
        case "leonoticias.com":
            $id_krux = "qxobub19i";
            break;
        case "mujerhoy.com":
            $id_krux = "q8cvcf01d";
            break;
        case "sevilla.abc.es":
            $id_krux = "qwotbe87w";
            break;
        case "xlsemanal.com":
            $id_krux = "rnw2v0hze";
            break;
        case "welife.es":
            $id_krux = "";
            break;
        case "womennow.es":
            $id_krux = "ttistq20z";
            break;
    }

    global $post;
    $canonicalURL = str_replace("http://", "https://", get_post_meta($post->ID, 'canonical-url', true));
    $posLaliga = strpos($canonicalURL, 'contentfactory/post/eslaliga/');


    if ($tld == 'abc.es' || $tld == 'lavozdigital.es' || $tld == 'sevilla.abc.es') {


        if ($posLaliga === false) {
            echo '
                <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-kangaroo="false"></script>
                <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-analytics-id="ppnn_' . strtolower($_ENV["ENVIRON"]) . '" data-legacy-autotracking="false"></script>
            ';
            if ($dataTrack) {
                echo '<script>TagManager.ready.then(function() {TagManager.set({ tipologiaLogin: Voonto.getInstance().getUserType() }).track();});</script>';
            }
            echo '<!-- FIN ADOBE ANALYTICS -->';
        } else {
            echo '
                <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-kangaroo="false"></script>
                <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-analytics-id="ppnn_' . strtolower($_ENV["ENVIRON"]) . '" data-legacy-autotracking="false"></script>
            ';
            if ($dataTrack) {
                echo '<script>TagManager.ready.then(function() {TagManager.set({ tipologiaLogin: Voonto.getInstance().getUserType() }).track();});</script>';
            }
            echo '<!-- FIN ADOBE ANALYTICS -->';
        }
    } elseif ($tld == 'codigounico.com' || $tld == 'mujerhoy.com' || $tld == 'xlsemanal.com' || $tld == 'welife.es' || $tld == 'womennow.es') {

        if ($posLaliga === false) {
            echo '
           <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-kangaroo="false"></script>
           <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-analytics-id="ppvv_' . strtolower($_ENV["ENVIRON"]) . '" data-legacy-autotracking="false"></script>
           ';
            if ($dataTrack) {
                echo '<script>TagManager.ready.then(function() {TagManager.set({ tipologiaLogin: Voonto.getInstance().getUserType() }).track();});</script>';
            }
            echo '<!-- FIN ADOBE ANALYTICS -->';
        } else {
            echo '
           <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-kangaroo="false"></script>
           <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-analytics-id="ppvv_' . strtolower($_ENV["ENVIRON"]) . '" data-legacy-autotracking="false"></script>
           ';
            if ($dataTrack) {
                echo '<script>TagManager.ready.then(function() {TagManager.set({ tipologiaLogin: Voonto.getInstance().getUserType() }).track();});</script>';
            }
            echo '<!-- FIN ADOBE ANALYTICS -->';
        }
    } elseif ($tld == 'relevo.com') {
        echo '
        <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-components="data-voc-component" data-cmp="true" data-kangaroo="false"></script>
        <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-analytics-id="' . $taxvalue["dtm_data_property_id"] . '"></script>
        ';
        if ($dataTrack) {
            echo '<script>TagManager.ready.then(function() {TagManager.set({ tipologiaLogin: Voonto.getInstance().getUserType() }).track();});</script>';
        }
        echo '<!-- FIN ADOBE ANALYTICS -->';
    } else {
        if ($posLaliga === false) {
            echo '
                <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-kangaroo="false"></script>
                <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-analytics-id="ppll_' . strtolower($_ENV["ENVIRON"]) . '" data-legacy-chartbeat="' . $tld . '" data-legacy-autotracking="false"></script>
                ';
            if ($dataTrack) {
                echo '<script>TagManager.ready.then(function() {TagManager.set({ tipologiaLogin: Voonto.getInstance().getUserType() }).track();});</script>';
            }
            echo '<!-- FIN ADOBE ANALYTICS -->';
        } else {
            echo '
                <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-kangaroo="false"></script>
                <script src="//static.vocstatic.com/tag-manager/latest/vtm.js"  data-analytics-id="ppll_' . strtolower($_ENV["ENVIRON"]) . '" data-legacy-autotracking="false"></script>
                ';
        }
    }
}

function generate_body_tagmanager($canonicalURL)
{
    $url_medio = 'http://' . $_SERVER['HTTP_HOST'];
    $re = '/\.(.+?)\./s';
    preg_match($re, $url_medio, $matches, PREG_OFFSET_CAPTURE, 0);
    if ($matches[1][0] == 'sevilla') {
        $term = get_term_by('slug', 'abc-sevilla', 'Medios');
    } else {
        $term = get_term_by('slug', $matches[1][0], 'Medios');
    }
    $taxvalue = get_option('taxonomy_term_' . $term->term_id);
    $url_medioCheck = get_field('url_medio', $term);
    $url = str_replace("http://content-factory.", "", $url_medioCheck);

    echo '
     <!-- Start vocento DTM B -->
     <noscript><img src="//sadbmetrics.' . $url . '/b/ss/' . $taxvalue["dtm_reportsuit"] . '/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=/js-noactive&v55=' . $taxvalue["v55v56"] . '&c56=' . $taxvalue["v55v56"] . '" width="1" height="1" border="0" /></noscript>
     <!-- End vocento DTM B -->
     ';
}



function register_theme_css($wp_customize)
{
    $wp_customize->add_section('css_defecto', array(
        'title'       => __('Css por defecto', 'themeslug'),
        'priority'    => 30,
        'description' => 'El css aparecere en los medios que no tengan seleccionado un fichero',
    ));
    $wp_customize->add_setting('file-upload');

    $wp_customize->add_control(
        new WP_Customize_Upload_Control(
            $wp_customize,
            'file-upload',
            array(
                'label' => 'Subir css',
                'section' => 'css_defecto',
                'settings' => 'file-upload'
            )
        )
    );
}
add_action('customize_register', 'register_theme_css');
function enlacesVistasMedios()
{
    global $pagenow;
    if ('post.php' == $pagenow) {

        //registrando script para actualizar la preview
        wp_enqueue_script('updatePreview', get_template_directory_uri() . '/js/addons_js/updatePreview.js');
        //obteniendo datos
        $taxName = "Medios";
        // $terms =  get_terms($taxName);
        $terms = get_terms(
            array(
                'taxonomy' => 'Medios',
                'update_term_meta_cache' => false,
            )
        );
        $urlPost = get_permalink();
        $urlPost = wp_make_link_relative($urlPost);
        $urlPost = preg_replace("/\/contentfactory/", '', $urlPost);
        $post_id = get_the_ID();
        //pintando enlaces para cada medio
        foreach ($terms as $term) {
            $url_medio = get_field('url_medio', $term);
            $url_medio = preg_replace("/content-factory/", 'www', $url_medio);
            $nonce = wp_create_nonce('post_preview_' . $post_id);
            //$customURLPreview ='?preview_id='.$term->term_id.'&preview_nonce='.$customNonce.'&preview=true';
            $customURLPreview = '?preview=true&preview_id=' . $post_id . '&preview_nonce=' . $nonce . '&medio=' . $url_medio;
            //$url_medio = preg_replace('/\/contentfactory/', '', $url_medio);

            echo  "<script>
                        (function($) {
                        $(document).ready(function(){
                        idMedio = jQuery('li#Medios-" . $term->term_id . "').val();
                            oldhtml = $( jQuery('li#Medios-" . $term->term_id . "')).html();
                            $( jQuery('li#Medios-" . $term->term_id . "')).html(oldhtml+'   <a target=\"blank\"> Permalink </a>&nbsp;&nbsp;<a target=\"blank\" class=\"previewcontent\">PreView</a>');
                            $('li#Medios-" . $term->term_id . " a:nth-child(2)').attr('href', '" . $url_medio . "/contentfactory/post" . $urlPost . "');
                            $('li#Medios-" . $term->term_id . " a:nth-child(3)').attr('href', 'https://" . $_SERVER['HTTP_HOST'] . $urlPost . $customURLPreview . "');
                            });
                        }(jQuery));
                    </script>";
        }
    }
}
add_action('admin_head', 'enlacesVistasMedios');

function editaCSS()
{
    if (isset($_GET["tag_ID"])) {
        $tagID = $_GET["tag_ID"];

        $ppruebaterm = get_term($tagID, "Medios");


        $_medioCheck = get_field('css', $ppruebaterm);
        $urlmedio = get_field('url_medio', $ppruebaterm);

        //si hay contenido pintamos el editor de CSS
        if ($_medioCheck['url'] != false) {
            $checkBase = str_replace("wp-admin", "", getcwd());
            $checkFile = $checkBase . $_medioCheck['url'];
            $contenido = file_get_contents($checkFile);
            echo "<div id='editorCSS' style='display:block;width: 85%;float: right; margin-top: -5%; margin-bottom:5%;'><h3>Edita CSS</h3> <textarea id='default' style='width:100%;height:350px;'>";
            echo $contenido;
            echo "</textarea></div><br><br>";


            wp_enqueue_script('my_custom_script', get_template_directory_uri() . '/js/addons_js/editaCSS.js');
        }
    }
}
add_action('admin_footer', 'editaCSS');


function editaCSSJS()
{
    if ($_SERVER['PHP_SELF'] == "/wp-admin/edit-tags.php") {
        wp_enqueue_script('script_custom_generalCSS', get_template_directory_uri() . '/js/addons_js/editaCSSGeneral.js');
    }
}
add_action('admin_head', 'editaCSSJS');

function eliminaEdit()
{
    if ($_SERVER['PHP_SELF'] == "/wp-admin/theme-editor.php") {
        wp_enqueue_script('script_custom_eliminaEdit', get_template_directory_uri() . '/js/addons_js/eliminaEdit.js');
    }
}
add_action('admin_head', 'eliminaEdit');

function my_acf_save_post($post_id)
{
    $url_actual = "http://" . $_SERVER["SERVER_NAME"];
    $urlRelativa = str_replace($url_actual, "", $_POST['urlCSS']);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $urlRelativa, stripslashes($_POST['textoCss']));
}



// run after ACF saves the $_POST['acf'] data
add_action('acf/save_post', 'my_acf_save_post', 20);

//añadiendo permisos para ver previews
add_rewrite_endpoint('post-preview', EP_ROOT);

//filtro para datos GTM
add_filter('vcanly_gtm_body_start', function () {
    //obteniendo datos
    $url_medio = 'http://' . $_SERVER['HTTP_HOST'];


    $pos = strpos($url_medio, 'relevo');
    // $posLaliga = strpos($url_medio, 'eslaliga');
    if ($pos === false) {

        if ($_SERVER['HTTPS'] === '1'  || $_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === true) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $canonicalURL = str_replace("http://", $protocol, get_post_meta(get_the_ID(), 'canonical-url', true));

        $posLaliga = strpos($canonicalURL, 'contentfactory/post/eslaliga/');


        $GTM_ID = valueByUrlMedio(get_the_ID(), $url_medio, 'id_ptm');
        if ($posLaliga === false) {
            //GTM Analytics
            $html = file_get_contents(get_template_directory() . '/analytics/GTM/body_start.html');
        } else {
            $html = file_get_contents(get_template_directory() . '/analytics/GTM/body_start_laliga.html');
        }

        $html = str_replace("##gtmid##", $GTM_ID, $html);
        //quitando BOM
        $bom = pack('H*', 'EFBBBF');
        $html = preg_replace("/^$bom/", '', $html);
        //devolviendo resultado
        return $html;
    }
});

add_filter('vcanly_vocentoWP_Analytics', function () {
    //obteniendo datos
    $url_medio = 'http://' . $_SERVER['HTTP_HOST'];

    echo '<!-- urlmdeio ' . $url_medio . ' -->';
    $pos = strpos($url_medio, 'relevo');
    if ($pos === false) {
        $GTM_ID = valueByUrlMedio(get_the_ID(), $url_medio, 'id_ptm');
        if ($_SERVER['HTTPS'] === '1'  || $_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === true) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $canonicalURL = str_replace("http://", $protocol, get_post_meta(get_the_ID(), 'canonical-url', true));

        $posLaliga = strpos($canonicalURL, 'contentfactory/post/eslaliga/');

        if ($posLaliga === false) {
            //GTM Analytics
            $html = file_get_contents(get_template_directory() . '/analytics/GTM/vocentoWP_Analytics.html');
        } else {
            //GTM Analytics Laliga
            $html = file_get_contents(get_template_directory() . '/analytics/GTM/vocentoWP_Analytics_laliga.html');
        }

        $html = str_replace("##gtmid##", $GTM_ID, $html);
        //quitando BOM
        $bom = pack('H*', 'EFBBBF');
        $html = preg_replace("/^$bom/", '', $html);
        //devolviendo resultado
        return $html;
    } else {
        $GTM_ID = valueByUrlMedio(get_the_ID(), $url_medio, 'id_ptm');
        //GTM Analytics
        // $html = file_get_contents(get_template_directory(). '/analytics/GTM/vocentoWP_Analytics_Relevo.html');
        $html = file_get_contents(get_template_directory() . '/analytics/GTM/vocentoWP_Analytics.html');

        $html = str_replace("##gtmid##", $GTM_ID, $html);
        //quitando BOM
        $bom = pack('H*', 'EFBBBF');
        $html = preg_replace("/^$bom/", '', $html);
        //devolviendo resultado
        return $html;
    }
});

function limpiaURL($url)
{
    $posWPContent = strpos($url, "/wp-content");
    if ($posWPContent != false) {
        $posWPContent;
        $rest = substr($url, $posWPContent);
        return $rest;
    } else {
        return $url;
    }
}

//añade al path el dominio para la redirección
function CF_replace_domain($content)
{
    if (is_single()) {
        $urlpars = parse_url($content);

        if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
            if (strpos($content, '/contentfactory/post') == false) {
                if ($urlpars["path"]) {
                    return $urlpars["path"];
                } else {
                    return "/contentfactory/post";
                }
            } else {
                return $content;
            }
        } else {
            if (strpos($content, 'contentfactory/post') == false) {
                if ($urlpars["path"]) {
                    return "/contentfactory/post" . $urlpars["path"];
                } else {
                    return "/contentfactory/post";
                }
            } else {
                return $content;
            }
        }
    } else {
        return $content;
    }
}
add_filter('script_uri', 'CF_replace_domain');
add_filter('stylesheet_uri', 'CF_replace_domain');
add_filter('bloginfo_url', 'CF_replace_domain');
add_filter('bloginfo', 'CF_replace_domain');
add_filter('post_link', 'CF_replace_domain');
add_filter('category_link', 'CF_replace_domain');
add_filter('category_feed_link', 'CF_replace_domain');
add_filter('wp_get_attachment_url', 'CF_replace_domain');
add_filter('wp_get_attachment_thumb_url', 'CF_replace_domain');
add_filter('post_comments_feed_link', 'CF_replace_domain');
add_filter('author_link', 'CF_replace_domain');
add_filter('author_feed_link', 'CF_replace_domain');
//add_filter('plugins_url', 'CF_replace_domain');
add_filter('bloginfo_rss', 'CF_replace_domain');
add_filter('home_url', 'CF_replace_domain');
add_filter('script_loader_src', 'CF_replace_domain');
add_filter('style_loader_src', 'CF_replace_domain');
add_filter('stylesheet_directory_uri', 'CF_replace_domain');
add_filter('site_url', 'CF_replace_domain');
add_filter('template_directory_uri', 'CF_replace_domain');
add_filter('esc_url', 'CF_replace_domain');
add_filter('vcbc_video_host', 'cambiaURLBrightcove');

/* Reemplaza la URL de las imagenes para los metas de las RRSS*/
function CF_replace_domainRRSS($content)
{
    if (is_single() && $content != null) {
        $urlpars = parse_url($content);

        if (strpos($content, 'contentfactory/post') == false) {
            if ($urlpars["path"]) {
                return "http://" . $_SERVER['HTTP_HOST'] . $urlpars["path"];
            } else {
                return "http://" . $_SERVER['HTTP_HOST'];
            }
        } else {
            return $content;
        }
    } else {
        return $content;
    }
}
add_filter('fb_og_image', 'CF_replace_domainRRSS');
add_filter('fb_og_image_additional', 'CF_replace_domainRRSS');


/*Añadiendo parametros a row para centrar contenido*/
$attributes = array(
    'type' => 'checkbox',
    'heading' => "¿Centrar contenido?",
    'param_name' => 'centrar',
    'value' => 'false',
    'description' => __("Añade la clase  'grid grid-pad'", "discprofile")
);

if (function_exists('vc_add_param')) {
    vc_add_param('vc_row', $attributes);
}


/*Filtro que devuelve URL publica a brightcove para subir videos*/
function cambiaURLBrightcove($url)
{
    return 'http://content-factory.abc.es';
}
add_filter('vcbc_video_host', 'cambiaURLBrightcove');

/*incluyendo titulo y texto de metadescripcion en los ogs de RRSS*/

function fb_opengraph_title()
{
    global $post;

    if (is_single()) {
        $titulo1 = get_post_meta(get_the_ID(), 'titulo1', true);
        if (isset($titulo1)) {
            return $titulo1;
        }
    }
}
add_action('fb_og_title', 'fb_opengraph_title', 5);

function fb_opengraph_description()
{
    global $post;

    if (is_single()) {
        $texto = get_post_meta(get_the_ID(), 'texto', true);
        if (isset($texto)) {
            return $texto;
        }
    }
}
add_action('fb_og_desc', 'fb_opengraph_description', 5);
// add_action('wp_head', 'fb_opengraph', 5);

function fb_opengraph() {
    // Function not implemented - commented out
}

function alter_fields_wpse_10888($fields)
{
    return 'ID,post_author,post_date,post_date_gmt,post_title,post_excerpt,post_status,comment_status,ping_status,post_password,post_name,to_ping,pinged,post_modified,post_modified_gmt,post_content_filtered,post_parent,guid,menu_order,post_type,post_mime_type,comment_count';
}
// add_filter('posts_fields', 'alter_fields_wpse_10888');

/*generate sitemap*/
add_action('action_vcf_posts', function (&$posts) {
    $host = str_replace("content-factory.", "", $_SERVER['HTTP_HOST']);
    $reHost = '/^[a-zA-Z0-9-_]+(\.){0}/';

    preg_match_all($reHost, $host, $machesHost);

    add_filter('posts_fields', 'alter_fields_wpse_10888');
    $args = array(
        'post_type' => 'post',
        'post_status ' => 'publish',
        'posts_per_page' => -1,
        'update_post_meta_cache' => false,
        'cache_results'  => false,
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'numberposts' => -1,
        'suppress_filters' => false,
        'tax_query' => array(
            array(
                'taxonomy' => 'Medios',
                'field'    => 'slug',
                'terms'    => $machesHost[0][0]
            ),
        ),
        'meta_query' => array(
            array(
                'key' => 'canonical-url',
                'value' => $machesHost[0][0],
                'compare' => 'LIKE'
            )
        )
    );

    $query = get_posts($args);
    remove_filter('posts_fields', 'alter_fields_wpse_10888');

    $url_medio = 'https://www.' . $host . '/contentfactory/post/site_map.xml';

    $temp = array();

    /*$post["canonical"]=$host.'--'.$machesHost[0][0].'--'.$url_medio.'--'.count($query);
        array_push($temp, $post);*/


    foreach ($query as $res) {
        $canonicalURL = get_post_meta($res->ID, 'canonical-url', true);

        if (isset($canonicalURL)) {
            $re = '/[w]{3}\.[a-zA-z]*\.[a-z]{2,3}\//';
            preg_match_all($re, $canonicalURL, $matches);
            preg_match_all($re, $url_medio, $matches2);

            if ($matches[0][0] === $matches2[0][0]) {
                $canonicalURL = str_replace('http:', 'https:', $canonicalURL);
                $res->canonical = $canonicalURL;
                array_push($temp, get_object_vars($res));
            }
        }
    }

    $posts = $temp;
}, 99);

add_filter('vcf_time_to_cache', function ($cache_time) {
    return 1;
});



function admin_canonical__error()
{
    $class = 'notice notice-error';
    $canonicalURL = get_post_meta(get_the_ID(), 'canonical-url', true);
    if (!isset($canonicalURL) || $canonicalURL == "") {
        echo $canonicalURL;
        $message = __('Alerta! Este post no tiene asignada una url para el canonical', 'sample-text-domain');

        printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
    }
}
add_action('edit_form_after_title', 'admin_canonical__error');



/*function autoplayVideos(){
    wp_enqueue_script('customautoplay', get_template_directory_uri() .'/js/jquery.appear.js' );
}
add_action( 'get_footer', 'autoplayVideos' );*/


function url_facebook()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    $urlPost = preg_replace("/\/contentfactory/", '', $_SERVER['REQUEST_URI']);
    $url_medio = preg_replace("/content-factory/", 'www', $_SERVER['HTTP_HOST']);

    return $protocol . $url_medio . '/contentfactory/post' . $urlPost;
}




function valueTermIdByUrlMedio($post_id, $url_medio)
{
    $taxName = "Medios";

    if ($post_id == null || $post_id == "") {
        // $terms =  get_terms($taxName);
        $terms = get_terms(
            array(
                'taxonomy' => 'Medios',
                'update_term_meta_cache' => false,
            )
        );
    } else {
        $terms =  wp_get_post_terms($post_id, $taxName);
    }


    $urlPost = get_permalink();
    $urlPost = wp_make_link_relative($urlPost);
    $urlPost = preg_replace("/\/contentfactory/", '', $urlPost);

    foreach ($terms as $term) {
        $url_medioCheck = get_field('url_medio', $term);
        if ($url_medioCheck == $url_medio) {
            $url_medio = get_field('url_medio', $term);
            $url_medio = preg_replace("/content-factory/", 'www', $url_medio);
            $urlmedio = $url_medio . "/contentfactory/post" . $urlPost;

            if ($term->slug == "abc") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocabces,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "diariosur") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/voceldiariosur,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "diariovasco") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/voceldiariovasco,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "elcomercio") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocelcomercio,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "elcorreo") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocelcorreo,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "eldiariomontanes") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/voceldiariomontanes,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "elnortedecastilla") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocelnortedecastilla,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "hoy") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocelhoy,vocesdev/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "ideal") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocelideal,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "larioja") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/voclarioja,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "laverdad") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/voclaverdad,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "lasprovincias") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/voclasprovincias,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "leonoticias") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocleonnoticias,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "mujerhoy") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocmujerhoy, voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
            if ($term->slug == "xlsemanal") {
                $custom_value = '
                    <!-- Start vocento DTM B -->
                    <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocxlsemanal,voces/1/G.4--S/12009522' . $term->term_id . '?pageName=&g=' . $urlmedio . '" width="1" height="1" border="0" /></noscript>
                    <!-- End vocento DTM B -->
                    ';
            }
        }
    }

    if (isset($custom_value)) {
        //devolviendo valores obtenidos
        return $custom_value;
    } else {
        return false;
    }
}


function valueSlugByUrlMedio($post_id, $url_medio)
{
    $taxName = "Medios";

    if ($post_id == null || $post_id == "") {
        // $terms =  get_terms($taxName);
        $terms = get_terms(
            array(
                'taxonomy' => 'Medios',
                'update_term_meta_cache' => false,
            )
        );
    } else {
        $terms =  wp_get_post_terms($post_id, $taxName);
    }
    foreach ($terms as $term) {
        $url_medioCheck = get_field('url_medio', $term);
        if ($url_medioCheck == $url_medio) {
            $custom_value = $term->slug;
        }
    }

    if (isset($custom_value)) {
        //devolviendo valores obtenidos
        return $custom_value;
    } else {
        return false;
    }
}





/**
 * Add metabox para determinar si un post se debe publicar en los intantarticles de facebook
 */

add_action('add_meta_boxes', 'instant_articles');
function instant_articles()
{
    add_meta_box('instant_articles', 'Instant Articles', 'instant_articles_metabox', 'post', 'side', 'high');
}

function instant_articles_metabox($post)
{
    $values = get_post_custom($post->ID);
    $check = isset($values['instant_articles_check']) ? esc_attr($values['instant_articles_check'][0]) : '';
    wp_nonce_field('my_intant_article_box_nonce', 'instant_box_nonce'); ?>
    <p>
        <input type="checkbox" id="instant_articles_check" name="instant_articles_check" <?php checked($check, 'on'); ?> />
        <label for="instant_articles_check">Marcar este post para publicarlo en Intant Article</label>
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
    if (!isset($_POST['instant_box_nonce']) || !wp_verify_nonce($_POST['instant_box_nonce'], 'my_intant_article_box_nonce')) {
        return;
    }

    // if our current user can't edit this post, bail
    if (!current_user_can('edit_post')) {
        return;
    }

    $check = isset($_POST['instant_articles_check']) && $_POST['instant_articles_check'] ? 'on' : 'off';

    update_post_meta($post_id, 'instant_articles_check', $check);
}

//FBIA

add_filter('fbia_the_content', 'cms_regex_content', 10, 1);
function cms_regex_content($the_content)
{
    $the_content = apply_filters('the_content', $the_content);
    $the_content = do_shortcode($the_content);
    $re = '/(<(?!\/?figure)[^>]+>\s*)<img(.[^\>]+)?>/sx';
    preg_match_all($re, $the_content, $matches, PREG_SET_ORDER, 0);

    $temp = array();
    foreach ($matches as $match) {
        if (in_array($match[2], $temp)) {
            continue;
        } else {
            array_push($temp, $match[2]);
        }
    }
    foreach ($temp as $res) {
        $the_content = str_replace('<img' . $res . '>', '<figure><img' . $res . '></figure>', $the_content);
    }

    $the_content = preg_replace('/(class|id)=(\"|\')(.[^^(\"|\')]+)(\"|\')/s', '', $the_content);
    $the_content = preg_replace("/<script(.[^<]*)?>((.(?!script))*)?<\/script>/s", '', $the_content);
    $the_content = str_replace("<figure><figure>", "<figure>", $the_content);
    $the_content = str_replace("<br />", "", $the_content);
    $the_content = preg_replace("/<img src=\'https:\/\/comeresaprensa(.[^\']+)\'>/s", '', $the_content);
    $the_content = str_replace("</figure></figure>", "</figure>", $the_content);
    $the_content = preg_replace("/<div class=\'compartir\'(?:(?R)|(?:(?!<\/?div).))*<\/div>/s", '', $the_content);
    $the_content = preg_replace("/<p><\/p>/s", '', $the_content);
    $the_content = preg_replace("/<span><\/span>/s", '', $the_content);
    $the_content = preg_replace("/<img src=\"\">/s", '', $the_content);
    $the_content = preg_replace("/<img src=\"\">/s", '', $the_content);
    $the_content = preg_replace("/class=\"([^\"]*)\"/s", '', $the_content);
    $the_content = preg_replace("/<figure(.[^>]*)>/s", '', $the_content);
    $the_content = preg_replace("/<\/figure>/s", '', $the_content);
    $the_content = preg_replace("/<small(.[^>]*)>/s", '', $the_content);
    $the_content = preg_replace("/<\/small>/s", '', $the_content);
    $the_content = preg_replace("/<div(.[^>]*)>/s", '', $the_content);
    $the_content = preg_replace("/<\/div>/s", '', $the_content);
    $the_content = preg_replace("/<article(.[^>]*)>/s", '', $the_content);
    $the_content = preg_replace("/<\/article>/s", '', $the_content);
    $the_content = preg_replace("/<i(.[^<]*)> *?<\/i>/x", '', $the_content);
    $the_content = preg_replace("/<b(.[^<]*)> *?<\/b>/x", '', $the_content);
    $the_content = preg_replace("/<a(.[^<]*)> *?<\/a>/x", '', $the_content);
    $the_content = preg_replace("/<li(.[^<])*> *?<\/li>/x", '', $the_content);
    $the_content = preg_replace("/<ul(.[^<])*> *?<\/ul>/x", '', $the_content);
    $the_content = preg_replace("/<img(.[^>]*)>/s", '<figure><img $1 /></figure>', $the_content);
    $the_content = preg_replace("/src=\"\/contentfactory\//s", 'src="http://www.abc.es/contentfactory/', $the_content);
    $the_content = preg_replace("/<a  *href=(\"|\')#(\"|\')>((.(?!\/a))+)<\/a>/s", '$3', $the_content);
    $the_content = preg_replace("/<p>(C|c)ontenido desarrollado (por|para) ?<\/p>/s", '', $the_content);

    $the_content = preg_replace("/<p>Compartir<\/p>/s", '', $the_content);

    return $the_content;
}
//apply_filters( 'instant_articles_authors', $authors, $this->_post->ID );
add_filter('instant_articles_authors', function ($authors, $postID) {
    $re = '/<div class=\'firmaRRSS ?\'>(.+)<div class=\'info\'><address>(.[^<]+)<\/address>/si';
    $post = get_post($postID);
    $the_content = $post->post_content;
    $the_content = apply_filters('the_content', $the_content);
    $the_content = do_shortcode($the_content);
    preg_match_all($re, $the_content, $matches);
    $name = "Content Factory";

    if ($matches && count($matches)) {
        $m = $matches[count($matches) - 1];
        $name = $m[0];
        $name = strip_tags($name);
    }


    $author = new stdClass;
    $author->ID            = 0;
    $author->display_name  = $name;
    $author->first_name    = $name;
    $author->last_name     = "";
    $author->user_login    = "";
    $author->user_nicename = $name;
    $author->user_email    = "";
    $author->user_url      = "";
    $author->bio           = "";

    $authors[0] = $author;

    return $authors;
}, 10, 2);

add_filter('instant_articles_cover_kicker', function ($kicker, $postID) {
    $re = '/<span class=(\"|\')p-content(\"|\')>(.[^<]+)<\/span>/s';
    $post = get_post($postID);
    $the_content = $post->post_content;
    $the_content = apply_filters('the_content', $the_content);
    $the_content = do_shortcode($the_content);

    preg_match_all($re, $the_content, $matches);

    if (count($matches) && count($matches[count($matches) - 1][0])) {
        $m = $matches[count($matches) - 1];
        return $m[0];
    }


    return "";
}, 10, 2);


/**
 * remove redirect
 */
remove_filter('template_redirect', 'redirect_canonical');
add_filter('redirect_canonical', 'content_redirect_canonical', 10, 2);
function content_redirect_canonical($redirect_url, $requested_url)
{
    return $requested_url;
}


/** Yoast SEO plugin doesn't handle opengraph image tags properly
 * if they're hosted on a secure server. Luckily they provide lots
 * of useful filters to adjust this value. This function checks to
 * see if the image is on a secure link and if so, adds secure_url
 * onto the og tag so it's properly formatted.
 * @param  string $image URL for the opengraph image
 * @return none
 */
function check_ssl_facebook_opengraph_image($image)
{
    $og = "og:image";
    if (preg_match('/^https/', $image)) {
        echo '<meta property="' . $og .             ':secure_url" content="' . $image . '" />' . "\n";
        $image = preg_replace("/^https/", "http", $image);
    }
    // Chances are the image is also available via http, so let's include
    // to keep the Facebook Linter happy
    echo '<meta property="' . $og .         '" content="' . $image . '" />' . "\n";
}
// Hook into the Yoast plugin's hooks for handling the OG image
add_action('wpseo_opengraph_image', 'check_ssl_facebook_opengraph_image');



/**
 * Función para sustituir http:// por //
 */
function filter_handler($data, $postarr)
{
    // do something with the post data
    $data['post_content'] = str_replace('src="http:', 'src="//', $data['post_content']);
    //$data['post_content'] = str_replace('href="//tratamientofibrilacionauricular.com/','href="http://tratamientofibrilacionauricular.com/',$data['post_content']);
    return $data;
}

add_filter('wp_insert_post_data', 'filter_handler', '99', 2);


/**
 * Función para sustituir http:// por // antes de imprimir el contenido en pantalla
 */
function my_the_content_filter($content)
{
    $content = do_shortcode($content);
    $content = str_replace('src="http:', 'src="//', $content);
    //$content = str_replace('href="//tratamientofibrilacionauricular.com/','href="http://tratamientofibrilacionauricular.com/', $content);
    return $content;
}

add_filter('the_content', 'my_the_content_filter');

// disable srcset on frontend
add_filter('max_srcset_image_width', function () {
    return 1;
});

/**
 * Se comenta el inicio de la funcionalidad a la que se llamaba para crear la analítica de la liga
 */
// add_action( 'wp_ajax_head_javascript', 'head_javascript' );
// add_action( 'wp_ajax_nopriv_head_javascript', 'head_javascript' );
// function head_javascript() {
// 	$idpost = $_POST['idpost'];
// 	$textjavascript = $_POST['textojs'];
// 	update_post_meta($idpost,'head_field',$textjavascript);
// 	exit( json_encode($textjavascript) );
// }

// add_action( 'wp_ajax_body_javascript', 'body_javascript' );
// add_action( 'wp_ajax_nopriv_body_javascript', 'body_javascript' );
// function body_javascript() {
// 	$idpost = $_POST['idpost'];
// 	$textjavascript = $_POST['textojs'];
// 	update_post_meta($idpost,'body_field',$textjavascript);
// 	exit( json_encode($textjavascript) );
// }


// function hook_javascript() {
//     global $post;
// 	$head_script = get_post_meta($post->ID, 'head_field', true);
// 	if( $head_script )
// 	{
// 		echo $head_script;
// 	}
// }
// add_action('wp_head', 'hook_javascript');


// function hook_js_after_body()
// {
//     global $post;
// 	$postid = url_to_postid( $url );
// 	$head_script = get_post_meta($post->ID, 'body_field', true);
// 	if( $head_script )
// 	{
// 		echo $head_script;
// 	}
// }
// add_action('js_after_body', 'hook_js_after_body');

// add_action( 'wp_ajax_loadHeadScript', 'loadHeadScript' );
// add_action( 'wp_ajax_nopriv_loadHeadScript', 'loadHeadScript' );
// function loadHeadScript() {
// 	$idpost = $_POST['idpost'];
// 	$content = get_post_meta($idpost,'head_field',true);
// 	exit( json_encode($content) );
// }

// add_action( 'wp_ajax_loadBodyScript', 'loadBodyScript' );
// add_action( 'wp_ajax_nopriv_loadBodyScript', 'loadBodyScript' );
// function loadBodyScript() {
// 	$idpost = $_POST['idpost'];
// 	$content = get_post_meta($idpost,'body_field',true);
// 	exit( json_encode($content) );
// }
/**
 * Fin funcionalidad analítica de la liga
 */

add_action('wp_ajax_borrarregistrosjavascript', 'borrarregistrosjavascript');
add_action('wp_ajax_nopriv_borrarregistrosjavascript', 'borrarregistrosjavascript');
function borrarregistrosjavascript()
{
    $idpost = $_POST['idpost'];
    delete_post_meta($idpost, 'head_field');
    $res = delete_post_meta($idpost, 'body_field');

    exit(json_encode($res));
}


add_action('admin_menu', 'remove_admin_menu_links');
function remove_admin_menu_links()
{
    $user = wp_get_current_user();
    if ($user && isset($user->user_email) && 'bperezdevargas@vocento.com' == $user->user_email) {
        remove_menu_page('tools.php');
        remove_menu_page('themes.php');
        remove_menu_page('options-general.php');
        remove_menu_page('plugins.php');
        remove_menu_page('users.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('page.php');
        remove_menu_page('upload.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('edit.php?post_type=videos');
        remove_menu_page('edit.php?post_type=acf');
        remove_menu_page('edit.php');
        remove_menu_page('vc-general');
        remove_menu_page('acf');
        remove_menu_page('cmsnative-editor');
        remove_menu_page('instant-articles-wizard');
        remove_menu_page('site_vcanly_overview');
        remove_menu_page('site_vcbc_list');

        remove_submenu_page('ninja-forms', 'ninja-forms');
        remove_submenu_page('ninja-forms', 'ninja-forms#new-form');
        remove_submenu_page('ninja-forms', 'nf-import-export');
        remove_submenu_page('ninja-forms', 'nf-settings');
        remove_submenu_page('ninja-forms', 'nf-system-status');
        remove_submenu_page('ninja-forms', 'ninja-forms#apps');
    }
}


add_action('wp_ajax_post_bloque_tag_select_portada', 'post_bloque_tag_select_portada');
add_action('wp_ajax_nopriv_post_bloque_tag_select_portada', 'post_bloque_tag_select_portada');

function post_bloque_tag_select_portada()
{
    $tag = $_POST['tag'];

    $postSelectedByCategoryName = get_posts(array(
        'post_type'         => 'post',
        'post_status ' => 'publish',
        'posts_per_page' => -1,
        'update_post_meta_cache' => false,
        'cache_results'  => false,
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'post_tag',
                'field' => 'slug',
                'terms' => $tag
            )
        )
    ));
    wp_send_json($postSelectedByCategoryName);
}


add_action('wp_ajax_getPostForSelectorPortada', 'getPostForSelectorPortada');
add_action('wp_ajax_nopriv_getPostForSelectorPortada', 'getPostForSelectorPortada');

function getPostForSelectorPortada()
{
    $postID = intval($_POST['id']);
    wp_send_json(get_post($postID));
}

add_action('wp_ajax_saveIdImagenHeader', 'saveIdImagenHeader');
add_action('wp_ajax_nopriv_saveIdImagenHeader', 'saveIdImagenHeader');

function saveIdImagenHeader()
{
    $postID = intval($_POST['postid']);
    $idimagen = intval($_POST['idimagen']);
    $postValue = update_post_meta($postID, 'imagencabecera', $idimagen);
    wp_send_json(get_post($postValue));
}


// Reduce or prevent calling of update_meta_cachewp_query
add_filter('posts_results', 'cache_meta_data', 9999, 2);
function cache_meta_data($posts, $object)
{
    $posts_to_cache = array();
    // this usually makes only sense when we have a bunch of posts
    if (empty($posts) || is_wp_error($posts) || is_single() || is_page() || count($posts) < 3)
        return $posts;

    foreach ($posts as $post) {
        if (isset($post->ID) && isset($post->post_type)) {
            $posts_to_cache[$post->ID] = 1;
        }
    }

    if (empty($posts_to_cache))
        return $posts;

    update_meta_cache('post', array_keys($posts_to_cache));
    unset($posts_to_cache);

    return $posts;
}


// ACF Display Custom Fields
add_filter('acf/settings/remove_wp_meta_box', '__return_false');