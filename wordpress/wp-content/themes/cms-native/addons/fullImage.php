<?php


/******* FULL IMAGE *******/
vc_add_shortcode_param( 'fullimage', 'full_image_settings_field' );
function full_image_settings_field( $settings, $value) {
?>
    <script src="/wp-content/themes/cms-native/js/addons_js/fullimage.js"></script>
<?php
    $valor =  base64_decode($value);
    $contenido = explode("@", $valor);
    wp_enqueue_script('jquery');
    wp_enqueue_media();
?>
<div class="fullimagen">
    <?php if(empty($valor) || '@' == $valor){ ?>
    <div class='content_general'>
        <div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column' style="margin-bottom: 20px;">
            <div class='wpb_element_label'>Imagen</div>
            <div class='edit_form_line'>
                <input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''>
                <div class='gallery_widget_attached_images'>
                    <ul class='gallery_widget_attached_images_list ui-sortable'></ul>
                </div>
                <div class='gallery_widget_site_images'></div>
                <a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Añadir imagen'>Añadir Imagen</a>
                <span class='vc_description vc_clearfix'>Seleccionar una imagen de la biblioteca de medios.</span>
            </div>
        </div>
        <input name='title' class='wpb_vc_param_value wpb-textinput title textfield titulo' type='text'>
        <span class='vc_description vc_clearfix'>Título.</span><br />
        <textarea name='title' class='wpb_vc_param_value wpb-textinput title textfield textogeneral'></textarea>
        <span class='vc_description vc_clearfix'>Texto.</span><br />
    </div>
    <?php }else{ echo $contenido[0]; } ?>
</div>
<?php
    return '
    <input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['param_name'] ) . ' ' .
    esc_attr( $settings['type'] ) . '_field inputcontenido" type="hidden" value="' . esc_attr( $value ) . '" />';
}


add_shortcode( 'full_image', 'fullimagen_function' );
function fullimagen_function( $atts ) {
    extract( shortcode_atts( array(
        'animacionway'      => '',
        'el_class'      => '',
        'fullimage'  => '',
    ), $atts ) );
    $animacion =  base64_decode($atts['fullimage']);

    
    //comprobando si se ha marcado la animación y añadiendola si es así
    if($atts['animacionway']=="true"){
        wp_enqueue_script( 'wayFullImg', '/wp-content/themes/cms-native/js/addons_js/waypoints/fullimageWaypoints.js');
    }

    //print_r($atts['fliping_text']);
    $valor =  base64_decode($atts['fullimage']);
    $contenido = explode("@", $valor);

    $contenido = str_replace('&lt;','<',$contenido[1]);
    $contenido = str_replace('&gt;','>',$contenido);
    $contenido = str_replace('personalclass',$el_class,$contenido);

    $str = '';
    eval("\$str = \"$contenido\";");
    return $html.$str;

}
/******* FULL IMAGE *******/


