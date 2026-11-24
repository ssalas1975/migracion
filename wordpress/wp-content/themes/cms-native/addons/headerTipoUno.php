<?php

/******* TIPOS UNO HEADER *******/
vc_add_shortcode_param( 'headertipouno', 'header_uno_settings_field' );
function header_uno_settings_field( $settings, $value) {
?>

    <script src="<?php echo get_template_directory_uri(); ?>/js/addons_js/headertipouno.js"></script>
<script>

    function recogervalor(data) {
        jQuery('.shortcodevideo').val(data);
        jQuery('.idsimagenes').val("");
    }
    function recogervalorvideo(idvideo) {
        jQuery('.idvideo').val(idvideo);
    }
    function videoimagen(data) {
        jQuery('.listaimagenes').html('<li class="added"><div class="inner" style="width: 80px; height: 80px; overflow: hidden;text-align: center;">' + data + '</div><a href="#" class="vc_icon-remove"></a></li>');
    }
    jQuery('.add_media').hide();
</script>

<?php
    $valor =  base64_decode($value);
    $contenido = explode("@", $valor);
    wp_enqueue_script('jquery');
    wp_enqueue_media();
    //marcando input type radio de color segun el contenido guardado
    //quitamos el checked enviado si existe,, para signarlo y que no se repita
    $resulNoChecked = str_replace('checked="checked"','',$contenido[0]);
    $contenido[0] = $resulNoChecked;

    $colorGris = strpos($contenido[1], 'p-content-gris');
    //Añadiendo campos checked a los input....
    if($colorGris){
        $resultadoGris = str_replace('class="gris"','class="gris" checked="checked"',$contenido[0]);
        $contenido[0] = $resultadoGris;
    }else{
        $resultadoAmarillo = str_replace('class="amarillo"','class="amarillo" checked="checked"',$contenido[0]);
        $contenido[0] = $resultadoAmarillo;
    }

    if($contenido[4] == "yes"){
        $res = str_replace('class="autoplay_yes"','class="autoplay_yes" checked="checked"',$contenido[0]);
        $contenido[0] = $res;
    }elseif($contenido[4] == "no"){
        $res = str_replace('class="autoplay_no"','class="autoplay_no" checked="checked"',$contenido[0]);
        $contenido[0] = $res;
    }
    
?>

<div class="contentheader">
    <?php if(empty($contenido[0])){ ?>
    <div class="wpb_element_label">Nombre de cliente</div>
    <input name='title' class='wpb_vc_param_value wpb-textinput title textfield partner_content' type='text'><br />
    <br />
            <div class="wpb_element_label">Color caja Cliente</div>

    <label class="vc_checkbox-label">
        <input class="amarillo" type="radio" name="colorcintillo" style="width: 0px !important;">
        Amarillo</label>
    <label class="vc_checkbox-label">
        <input class="gris" type="radio" name="colorcintillo" style="width: 0px !important;">
        Gris</label>
                <br />
            <br />

    <div class="wpb_element_label">Añadir Video/Imagen Principal</div>
    
    <div class="content_general" style='margin-top: 20px;'>
        <div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column btn_add_icono'>
            <div class='edit_form_line'>
                <input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''>
                <input type="hidden" class="shortcodevideo" value="" />
                <input type="hidden" class="imgVideo" value="" />
                <input type="hidden" class="idvideo" Value="" />
                <span class="videoimagen"></span>
                <div class='gallery_widget_attached_images'>
                    <ul class='gallery_widget_attached_images_list ui-sortable listaimagenes'></ul>
                </div>
                <div class='gallery_widget_site_images'></div>
                <a class="gallery_widget_add_images" href="#" title="Añadir Video/Imagen" title='Añadir icono' style="margin-bottom: 20px;">Añadir Video/Imagen</a>
            </div>
        </div>
    </div>
    <br />
    <div class="wpb_element_label">IdPlayer:</div>
    <input name='idplayer' class='wpb_vc_param_value wpb-textinput title textfield idplayer' type='text'>
    <br /><br />
    <div class="wpb_element_label">AutoPlay:</div>
    <label class="vc_checkbox-label">
        <input class="autoplay_yes" type="radio" name="autoplay" style="width: 0px !important;">
        Si</label>
    <label class="vc_checkbox-label">
        <input class="autoplay_no" type="radio" name="autoplay" style="width: 0px !important;">
    No</label>
    <br /><br />
    <div class="wpb_element_label">Titulo</div>
    <input name='title' class='wpb_vc_param_value wpb-textinput title textfield cabecera_content' type='text'>
    <br />    <br />

                <div class="wpb_element_label">Añadir Logo Cliente</div>

     <div class="content_general" style='margin-top: 20px;'>
        <div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column btn_add_icono'>
            <div class='edit_form_line'>
                <input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image logo_partner' name='image' value=''>
                <input type="hidden" class="shortcodevideo" value="" />
                <span class="videoimagen"></span>
                <div class='gallery_widget_attached_images'>
                    <ul class='gallery_widget_attached_images_list ui-sortable'></ul>
                </div>
                <div class='gallery_widget_site_images'></div>
                <a class="gallery_widget_add_images" href="#" title="Añadir Video/Imagen" title='Añadir icono' style="margin-bottom: 20px;">Añadir Logo Partner</a>
            </div>
        </div>
    </div>
        <div class="wpb_element_label">Link Logo Cliente</div>
   
    <input name='title' class='wpb_vc_param_value wpb-textinput title textfield link_logo' type='text'>
    <br />
     <div class="wpb_element_label">Párrafo Logo Cliente</div>
   <br />
    <input name='title' class='wpb_vc_param_value wpb-textinput title textfield texto_logo' value="Contenido desarrollado por" type='text'>
    <br />
    <?php }else{ echo $contenido[0]; } ?>
</div>
<span class='vc_description vc_clearfix'></span>

<?php
    return '
    <input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['param_name'] ) . ' ' .
    esc_attr( $settings['type'] ) . '_field inputcontenido" type="hidden" value="' . esc_attr( $value ) . '" />';
}


add_shortcode( 'header_uno', 'headeruno_function' );
function headeruno_function( $atts, $content ) {
    extract( shortcode_atts( array(
        'el_class'      => '',
        'headertipouno'  => '',
    ), $atts ) );
    $valor =  base64_decode($atts['headertipouno']);
    $contenido = explode("@", $valor);
	$id_video=$contenido[2];
    $shortcodeVideo=$contenido[3];
    if($contenido[4] == null || $contenido[4] == "")
    {
        $autoplay='';
    }else{
        $autoplay=$contenido[4];
    }
    
    $contenido = str_replace('&lt;','<',$contenido[1]);
    $contenido = str_replace('&gt;','>',$contenido);
	
    $contenido = str_replace('#texto_content#',$content,$contenido);
    $contenido = str_replace('nuevaclase',$el_class,$contenido);

    extraeDatosHeader($contenido);

?>

<?php

    
    $str = '';
    eval("\$str = \"$contenido\";");
    if($shortcodeVideo){
        if(isset($atts['stillurlh1'])){
			$urlAttach =  wp_get_attachment_image_src(intval($atts['stillurlh1']), 'full');
			$url_medio = preg_replace("/content-factory/", 'www', $_SERVER['HTTP_HOST']);
			$urlStill = "http://".$url_medio.$urlAttach[0];
            $shortcodeVideo = str_replace("]", " stillurl='".$urlStill."']", $shortcodeVideo);
        }
        return '<!--p>'.$shortcodeVideo.'</p--><div class="videoC autoplay videoCount" data-autoplay="'.$autoplay.'"><figure>'.do_shortcode($shortcodeVideo).'
                    </figure>'.$str. '
           </div>';
    }else{
        return '<!--p>'.$shortcodeVideo.'</p-->'.$str;
    }
}

/******* TIPOS UNO HEADER *******/


