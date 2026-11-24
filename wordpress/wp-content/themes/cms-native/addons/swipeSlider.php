<?php




/******* SWIPER SLIDER *******/
vc_add_shortcode_param( 'sliders', 'swiper_sliders_settings_field' );
function swiper_sliders_settings_field( $settings, $value) {
?>
  <script src="<?php echo get_template_directory_uri(); ?>/js/addons_js/swiper.js"></script>
<span class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw btn_add_slider">Añadir Slider</span>
<br />
<br />
<?php
    $valor =  base64_decode($value);
    $contenido = explode("@", $valor);
    wp_enqueue_script('jquery');
    wp_enqueue_media();
?>

<div class="list_sliders">
    <?php if(empty($valor) || '@' == $valor){ ?>
    <div class='content_general'>
        <div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column'>
            <div class='wpb_element_label'>Slider</div>
            <div class='edit_form_line'>
                <input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''>
                <div class='gallery_widget_attached_images'>
                    <ul class='gallery_widget_attached_images_list ui-sortable'></ul>
                </div>
                <div class='gallery_widget_site_images'></div>
                <a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Añadir slide'>Añadir Slide</a>
                <a class='vc_control column_delete vc_column-delete btn_deletecolumn' data-vc-control='delete' href='#' title='Delete this column' style='margin-top: 60px; margin-left: 15px;'><i class='vc_icon'></i></a>
                <span class='vc_description vc_clearfix'>Seleccionar una imagen de la biblioteca de medios.</span>
            </div>
        </div>
        <input name='title' class='wpb_vc_param_value wpb-textinput title textfield piedeimagen' type='text'>
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

add_shortcode( 'swiper_slider', 'swiper_function' );
function swiper_function( $atts ) {
    extract( shortcode_atts( array(
        'el_class'      => '',
        'sliders'  => '',
    ), $atts ) );

    //print_r($atts['fliping_text']);
    $valor =  base64_decode($atts['sliders']);
    $contenido = explode("@", $valor);

    $contenido = str_replace('&lt;','<',$contenido[1]);
    $contenido = str_replace('&gt;','>',$contenido);
?>
    <?php
    $str = '';
    eval("\$str = \"$contenido\";");

	return '<div class="content_anim2 foto swiper-container swiper-container-horizontal animated fadeIn <?php echo $el_class; ?>">
			<div class="swiper-wrapper" style="transition-duration: 0ms; transform: translate3d(-310.5px, 0px, 0px);">
			'.$str.'
			</div>
			<!-- Add Pagination -->
			<div class="swiper-button-next">avanti</div>
			<div class="swiper-button-prev">indietro</div>
		</div>';
     ?>

<?php

}
/******* SWIPER SLIDER *******/
