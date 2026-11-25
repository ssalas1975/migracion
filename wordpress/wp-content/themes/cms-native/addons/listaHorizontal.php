<?php


/******* LISTA HORIZONTAL *******/
vc_add_shortcode_param( 'listahorizontal', 'lista_horizontal_settings_field' );
function lista_horizontal_settings_field( $settings, $value) {
?>

    <script src="/wp-content/themes/cms-native/js/addons_js/listahorizontal.js"></script>
<?php
    $valor =  base64_decode($value);
    $contenido = explode("@", $valor);
    wp_enqueue_script('jquery');
    wp_enqueue_media();
?>

<div class='list_horizontal'>
    <?php if(empty($contenido[0])){ ?>
    <input name='title' class='wpb_vc_param_value wpb-textinput title textfield titulolista' type='text'>
    <span class='vc_description vc_clearfix'>Encabezado lista Horizontal.</span><hr />
    <div class="content_general" style='margin-top: 20px;'>
        <input name='title' class='wpb_vc_param_value wpb-textinput title textfield texto_lista' type='text'>
        <span class='vc_description vc_clearfix'>Texto para el elemento de lista.</span><hr />
        <input name='title' class='wpb_vc_param_value wpb-textinput title textfield classList' type='text'>
        <span class='vc_description vc_clearfix'>Clase personalizada para elemento de la lista</span><hr />
        <div class="edit_form_line">
            <label class="vc_checkbox-label">
                <input class="wpb_vc_param_value autoplay checkbox check_icon" type="checkbox" name="autoplay">
                Usar icono</label>
            <span class="vc_description vc_clearfix">Activar icono.</span>
        </div>
        <div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column btn_add_icono' style='display: none'>
            <div class='edit_form_line'>
                <input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''>
                <div class='gallery_widget_attached_images'>
                    <ul class='gallery_widget_attached_images_list ui-sortable'></ul>
                </div>
                <div class='gallery_widget_site_images'></div>
                <a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Añadir icono'>Añadir Icono</a>
                <span class='vc_description vc_clearfix'>Seleccionar una imagen para usar como icono.</span>
            </div>
        </div>
        <span class='vc_general vc_ui-button vc_ui-button-default vc_ui-button-shape-rounded vc_ui-button-fw btn_remove' style='margin-top: 20px;'>Borrar Elemento de la Lista</span><br />
    </div>
    <?php }else{ echo $contenido[0]; } ?>
</div>
<span class='vc_description vc_clearfix'></span>
<hr>
<br />
<span class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw btn_add_li">Añadir Elemento a la Lista</span><br />
<br />


<?php
    return '
    <input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['param_name'] ) . ' ' .
    esc_attr( $settings['type'] ) . '_field inputcontenido" type="hidden" value="' . esc_attr( $value ) . '" />';

}

add_shortcode( 'lista_horizontal', 'listahorizontal_function' );
function listahorizontal_function( $atts ) {
    extract( shortcode_atts( array(
        'el_class'      => '',
        'listahorizontal'  => '',
    ), $atts ) );

    //print_r($atts['fliping_text']);
    $valor =  base64_decode($atts['listahorizontal']);
    $contenido = explode("@", $valor);

    $contenido = str_replace('&lt;','<',$contenido[1]);
    $contenido = str_replace('&gt;','>',$contenido);
    $contenido = str_replace('nuevaclase',$el_class,$contenido);

    $str = '';
    eval("\$str = \"$contenido\";");
    return $str;
}

/******* LISTA HORIZONTAL *******/
