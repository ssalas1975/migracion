<?php

function create_custommodhtml_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'rawhtmltype' => '',
		),
		$atts,
		'custom_mod_html'
	);
	// Attributes in var
	$mod_html = $atts['rawhtmltype'];
    global $post;

    $data = get_post_meta($post->ID, $mod_html, true);
    include($data);
    
}
add_shortcode( 'custom_mod_html', 'create_custommodhtml_shortcode' );




add_action('wp_ajax_saveHTMLModule', 'saveHTMLModule');
add_action('wp_ajax_nopriv_saveHTMLModule', 'saveHTMLModule');
function saveHTMLModule()
{
    $postID = intval($_POST['postid']);
    $content = $_POST['content'];
    $identificador = $_POST['identificador'];
    
    $content = str_replace( "\\\\\\", "\\", $content);
    $content = str_replace( '=\"', "=\"", $content);
    
    
    if ( ! is_dir( WP_CONTENT_DIR.'/uploads/htmlcache/pages/' ) ) {
        wp_mkdir_p( WP_CONTENT_DIR.'/uploads/htmlcache/pages/' );
    }
    $fileName = 'headercache_' . $identificador . '.html';
    $htmlFile = WP_CONTENT_DIR.'/uploads/htmlcache/pages/'.$fileName;
    file_put_contents($htmlFile, $content, FILE_USE_INCLUDE_PATH);

    update_post_meta($postID, $identificador, $htmlFile);

    $data = array(
        'identificador' => $identificador,
        'content' => $content
    );
    wp_send_json($data);
    die();
}


add_action('wp_ajax_getHTMLModule', 'getHTMLModule');
add_action('wp_ajax_nopriv_getHTMLModule', 'getHTMLModule');
function getHTMLModule()
{
    $postid = $_POST['postid'];
    $identificador = $_POST['identificador'];
    $url = get_post_meta($postid, "$identificador", true);
    $data = file_get_contents($url);
    wp_send_json($data);
    die();
}


vc_add_shortcode_param( 'rawhtmltype', 'raw_html_settings_field' );
function raw_html_settings_field( $settings, $value) {

    ?>
    <script>
    var urlParams = new URLSearchParams(window.location.search);
    var postId = urlParams.get("post");
    var identificador = "";
    if(!document.getElementById("rawhtmltype").value || document.getElementById("rawhtmltype").value == ""){
        identificador = '<?php echo uniqid(); ?>_'+postId;
        document.getElementById("rawhtmltype").value = identificador;
    }else{
        identificador = document.getElementById("rawhtmltype").value;
    }
    
    document.querySelector('#rawhtml').addEventListener("blur", function() {
        var content = document.getElementById("rawhtml").value;
        var dataUpdatePostImagen = {
            'action': 'saveHTMLModule',
            'postid': postId,
            'identificador': identificador,
            'content': content,
        };
        jQuery.post( '/wp-content/ajaxrewrite.php', dataUpdatePostImagen, function(response){
            console.log('RESPUESTA',response);
            document.getElementById("rawhtml").value = response.content;
        });
    });


    var dataGetContentHTML = {
            'action': 'getHTMLModule',
            'postid': postId,
            'identificador': document.getElementById("rawhtmltype").value,
        };
    jQuery.post( '/wp-content/ajaxrewrite.php', dataGetContentHTML, function(response){
        document.getElementById("rawhtml").value = response;
    });



    </script>
    <?php

    echo '
    <div class="wpb_element_label">HTML</div>
    <textarea id="rawhtml" name="rawhtml" rows="20" cols="100"></textarea>
    <br />
    </div>
    <span class="vc_description vc_clearfix"></span>
    ';


    return '
    <input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['param_name'] ) . ' ' .
    esc_attr( $settings['type'] ) . '_field inputcontenido" type="hidden" value="' . esc_attr( $value ) . '" />';


}


// Create Modulo HTML Personalizado element for Visual Composer
add_action( 'vc_before_init', 'custommodhtml_integrateWithVC' );
function custommodhtml_integrateWithVC() {
	vc_map( array(
		'name' => __( 'Modulo HTML Personalizado', 'textdomain' ),
		'base' => 'custom_mod_html',
		'show_settings_on_create' => true,
		'category' => __( 'Vocento', 'textdomain'),
		'params' => array(
            array(
                'type' => 'rawhtmltype',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                // 'heading' => __('Sinopsis', 'textdomain'),
                'param_name' => 'rawhtmltype',
            ),
		)
	) );
}