/**
 * Created by s.salas on 04/08/2016.
 */
jQuery(document).ready(function(){

    jQuery('.my_param ').hide();

   jQuery('.btn_add_slider').click( function(){
       jQuery('.list_sliders').append("<div class='content_general'><div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column' ><div class='wpb_element_label'>Slider</div><div class='edit_form_line'><input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''><div class='gallery_widget_attached_images'><ul class='gallery_widget_attached_images_list ui-sortable'></ul></div><div class='gallery_widget_site_images'></div><a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Add image'>Añadir Imagen</a><a class='vc_control column_delete vc_column-delete btn_deletecolumn' data-vc-control='delete' href='#' title='Delete this column' style='margin-top: 60px; margin-left: 15px;'><i class='vc_icon'></i></a><span class='vc_description vc_clearfix'>Seleccionar una imagen de la biblioteca de medios.</span></div></div><input name='title' class='wpb_vc_param_value wpb-textinput title textfield piedeimagen' type='text' value=''><span class='vc_description vc_clearfix'>Texto pié imágen.</span></div>");
   }) ;


    jQuery(document).on('click','.btn_addimage', function(){
       jQuery(this).hide();
    });


    var slider = "";
    var contentaddon = "";

    jQuery("span:contains('Save changes')").click(function(){
    //jQuery(document).on('change','.piedeimagen', function(){
        slider = "";
        jQuery('.content_general').each(function(){
            contentaddon = "";
            /*console.log(jQuery('.idsimagenes',jQuery(this)).val());*/
             //console.log(jQuery('.piedeimagen',jQuery(this)).val());
            slider += "<div class='swiper-slide'>\".wp_get_attachment_image("+jQuery('.idsimagenes',jQuery(this)).val()+",full).\"<p><span>"+jQuery('.piedeimagen',jQuery(this)).val()+"</span></p></div>";
            jQuery('.piedeimagen',jQuery(this)).attr('value',jQuery('.piedeimagen',jQuery(this)).val());
            contentaddon += jQuery('.list_sliders').html();
        });
        $codeBse64 = btoa(unescape( encodeURIComponent( contentaddon+'@'+slider)));
        jQuery('.inputcontenido').val($codeBse64);

    });

    // Eliminar slider
    jQuery(document).on('click','.btn_deletecolumn', function(){
        jQuery(this).parent().parent().parent().remove();
        slider = "";
        jQuery('.content_general').each(function(){
            contentaddon = "";
            /*console.log(jQuery('.idsimagenes',jQuery(this)).val());
            console.log(jQuery('.piedeimagen',jQuery(this)).val());*/
            slider += "<div class='swiper-slide'>\".wp_get_attachment_image(" + jQuery('.idsimagenes', jQuery(this)).val() +",full).\"<p><span>"+jQuery('.piedeimagen',jQuery(this)).val()+"</span></p></div>";
            jQuery('.piedeimagen',jQuery(this)).attr('value',jQuery('.piedeimagen',jQuery(this)).val());
            contentaddon += jQuery('.list_sliders').html();
        });
        $codeBse64 = btoa(unescape( encodeURIComponent( contentaddon+'@'+slider)));
        jQuery('.inputcontenido').val($codeBse64);
    });



});
