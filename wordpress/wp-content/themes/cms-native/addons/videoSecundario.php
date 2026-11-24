<?php

/******* VIDEO SECUNDARIO *******/

vc_add_shortcode_param( 'videosecundario', 'video_secundariol_settings_field' );
function video_secundariol_settings_field( $settings, $value) {
?>
    <script src="<?php echo get_template_directory_uri(); ?>/js/addons_js/videosecundario.js"></script>
<script>
    function recogervalor(data){
        jQuery('.shortcodevideo').val(data);
    }
    function recogervalorvideo(idvideo) {
        jQuery('.idvideo').val(idvideo);
    }
    function videoimagen(data){
        jQuery('.videoimagen').html(data);
    }
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
    <?php }else{ echo $contenido[0]; } ?>
</div>
<span class='vc_description vc_clearfix'></span>

<div class="wpb_element_label">AutoPlay:</div>
        <?php
            if($contenido[3] == "yes"){
                echo '<label class="vc_checkbox-label">
                <input class="autoplay_yes" type="radio" name="autoplay" style="width: 0px !important;" checked="checked">
                Si</label>';
            }else{
                echo '<label class="vc_checkbox-label">
                <input class="autoplay_yes" type="radio" name="autoplay" style="width: 0px !important;">
                Si</label>';
            }
        ?>

        <?php
            if($contenido[3] == "no"){
                echo '<label class="vc_checkbox-label">
                <input class="autoplay_no" type="radio" name="autoplay" style="width: 0px !important;" checked="checked">
            No</label>';
            }else{
                echo '<label class="vc_checkbox-label">
                <input class="autoplay_no" type="radio" name="autoplay" style="width: 0px !important;">
            No</label>';
            }
        ?>
    <br /><br />

<?php
    return '
    <input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['param_name'] ) . ' ' .
    esc_attr( $settings['type'] ) . '_field inputcontenido" type="hidden" value="' . esc_attr( $value ) . '" />';

}

add_shortcode( 'video_secundario', 'videosecundario_function' );
function videosecundario_function( $atts ) {
    extract( shortcode_atts( array(
        'videosecundario'  => '',
		'stillurlvs' => '',
    ), $atts ) );

    //print_r($atts['fliping_text']);
    $valor =  base64_decode($atts['videosecundario']);
    $contenido = explode("@", $valor);
    $id_video=$contenido[2];
    $autoplay=$contenido[3];

    $contenido = str_replace('&lt;','<',$contenido[1]);
    $contenido = str_replace('&gt;','>',$contenido);
	
    if(isset($atts['stillurlvs'])){
			$urlAttach =  wp_get_attachment_image_src(intval($atts['stillurlvs']), 'full');
			$url_medio = preg_replace("/content-factory/", 'www', $_SERVER['HTTP_HOST']);
			$urlStill = "http://".$url_medio.$urlAttach[0];
			$contenido = str_replace("]", " stillurl='".$urlStill."']", $contenido);
    }
?>
<?php
 //echo $contenido;
    //exit();
    return '<span class="autoplay videoCount" data-autoplay="'.$autoplay.'">'.do_shortcode( $contenido ).'</span>';

}

/******* VIDEO SECUNDARIO *******/


