<?php

/******* IMAGE-VIDEO BLOCKQUOTE *******/
vc_add_shortcode_param( 'videobloquote', 'video_bloquote_settings_field' );
function video_bloquote_settings_field( $settings, $value) {
?>
<script src="<?php echo get_template_directory_uri(); ?>/js/addons_js/videobloquote.js"></script>
<script>
    function recogervalor(data) {
        jQuery('.shortcodevideo').val(data);
    }
    function recogervalorvideo(idvideo) {
        jQuery('.idvideo').val(idvideo);
    }
	
    function videoimagen(data){
        jQuery('.videoimagen').html(data);
    }
	
    jQuery('.add_media').hide();
	
</script>

<?php
    $valor =  base64_decode($value);
    $contenido = explode("@", $valor);
    wp_enqueue_script('jquery');
    wp_enqueue_media();
?>

<a class="gallery_widget_add_images" href="#" title="Add movie">
    <span class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw btn_add_li" style="margin-bottom: 20px;">Añadir Video</span>
</a>

<div class="videosecundario">
    <?php if(empty($contenido[0])){ ?>
		<input type="hidden" class="shortcodevideo" value="" />
    <input type="hidden" class="idvideo" Value="" />
		<span class="videoimagen"></span>
    <?php }else{ ?>
		<input type="hidden" class="shortcodevideo" value="<?php echo $contenido[1]; ?>" />
		<span class="videoimagen"><?php echo $contenido[0]; ?></span>
	<?php } ?>
</div>
<span class='vc_description vc_clearfix'></span>

<?php
    return '
    <input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['type'] ) . '_field inputcontenido" type="hidden" value="' . esc_attr( $value ) . '" />';

}

add_shortcode( 'video_bloquote', 'videobloquote_function' );
function videobloquote_function( $atts, $content ) {
    extract( shortcode_atts( array(
        'videobloquote'  => '',
        'el_class'       => '',
    ), $atts ) );

    //print_r($atts['fliping_text']);
    $valor =  base64_decode($atts['videobloquote']);
    $contenido = explode("@", $valor);
    $id_video=$contenido[2];
    
    $contenido = str_replace('&lt;','<',$contenido[1]);
    $contenido = str_replace('&gt;','>',$contenido);
    if(isset($atts['stillurlvb'])){
			$urlAttach =  wp_get_attachment_image_src(intval($atts['stillurlvb']), 'full');
			$url_medio = preg_replace("/content-factory/", 'www', $_SERVER['HTTP_HOST']);
			$urlStill = "http://".$url_medio.$urlAttach[0];
			$contenido = str_replace("]", " stillurl='".$urlStill."']", $contenido);
    }

?>

<?php
    return '
     
        <figure id="cuatro" class="imgarticle '.$el_class.'">
        <div class="">
        '.do_shortcode( $contenido ).'
        </div>
      
        <figcaption class="">
        <p>'.$content.'</p>
        </figcaption>
        </figure>
    ';

}

/******* IMAGE-VIDEO BLOCKQUOTE *******/
