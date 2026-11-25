/**
 * Created by s.salas on 04/08/2016.
 */
jQuery(document).ready(function(){

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const post = urlParams.get('post')
    console.log('POST ID', post);

    var dataUpdatePostImagen = {
        'action': 'getSliderPost2025',
        'postid': post,
    };
    jQuery.post( '/wp-content/ajaxrewrite.php', dataUpdatePostImagen, function(response){
        console.log('RESPUESTA GET POST',response);
        if (response === undefined || response.length == 0 || response == 0) {
            jQuery('.list_sliders').append("<div class='content_general'><div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column' ><div class='wpb_element_label'>Slider</div><div class='edit_form_line'><input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''><div class='gallery_widget_attached_images'><ul class='gallery_widget_attached_images_list ui-sortable'></ul></div><div class='gallery_widget_site_images'></div><a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Add image'>Añadir Imagen</a><a class='vc_control column_delete vc_column-delete btn_deletecolumn' data-vc-control='delete' href='#' title='Delete this column' style='margin-top: 60px; margin-left: 15px;'><i class='vc_icon'></i></a><span class='vc_description vc_clearfix'>Seleccionar una imagen de la biblioteca de medios.</span></div></div><input name='link' class='wpb_vc_param_value wpb-textinput title textfield enlace' type='text' /><span class='vc_description vc_clearfix'>Enlace.</span><br /><input name='title' class='wpb_vc_param_value wpb-textinput title textfield titulo' type='text' /><span class='vc_description vc_clearfix'>Título.</span><br /><input name='subtitle' class='wpb_vc_param_value wpb-textinput title textfield subtitulo' type='text' /><span class='vc_description vc_clearfix'>Subtítulo.</span><br /></div>");
        }else{
            console.log('kkkkkkkkkk', response);
            response.forEach(myFunction);
            function myFunction(item) {
            jQuery('.list_sliders').append(`<div class='content_general'><div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column' ><div class='wpb_element_label'>Slider</div><div class='edit_form_line'><input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value='${item[0]}'><div class='gallery_widget_attached_images'><ul class='gallery_widget_attached_images_list ui-sortable'><li className='added' /><div class='inner' style=\" width: 80, height: 80, overflow: 'hidden', textAlign: 'center' \"><img rel='${item[0]}' src='${item[4]}' /></div><a href='#' className='vc_icon-remove' /></li></ul></div><div class='gallery_widget_site_images'></div><a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Add image'>Añadir Imagen</a><a class='vc_control column_delete vc_column-delete btn_deletecolumn' data-vc-control='delete' href='#' title='Delete this column' style='margin-top: 60px; margin-left: 15px;'><i class='vc_icon'></i></a><span class='vc_description vc_clearfix'>Seleccionar una imagen de la biblioteca de medios.</span></div></div><input name='link' class='wpb_vc_param_value wpb-textinput title textfield enlace' type='text' value='${item[1]}'/><span class='vc_description vc_clearfix'>Enlace.</span><br /><input name='title' value='${item[2]}' class='wpb_vc_param_value wpb-textinput title textfield titulo' type='text' /><span class='vc_description vc_clearfix'>Título.</span><br /><input name='subtitle' value='${item[3]}' class='wpb_vc_param_value wpb-textinput title textfield subtitulo' type='text' /><span class='vc_description vc_clearfix'>Subtítulo.</span><br /></div>`);
            }



            
        }
        // document.getElementById("rawhtml").value = response.content;
    });

    
    jQuery('.my_param ').hide();

   jQuery('.btn_add_slider').click( function(){
       jQuery('.list_sliders').append("<div class='content_general'><div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column' ><div class='wpb_element_label'>Slider</div><div class='edit_form_line'><input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''><div class='gallery_widget_attached_images'><ul class='gallery_widget_attached_images_list ui-sortable'></ul></div><div class='gallery_widget_site_images'></div><a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Add image'>Añadir Imagen</a><a class='vc_control column_delete vc_column-delete btn_deletecolumn' data-vc-control='delete' href='#' title='Delete this column' style='margin-top: 60px; margin-left: 15px;'><i class='vc_icon'></i></a><span class='vc_description vc_clearfix'>Seleccionar una imagen de la biblioteca de medios.</span></div></div><input name='link' class='wpb_vc_param_value wpb-textinput title textfield enlace' type='text' /><span class='vc_description vc_clearfix'>Enlace.</span><br /><input name='title' class='wpb_vc_param_value wpb-textinput title textfield titulo' type='text' /><span class='vc_description vc_clearfix'>Título.</span><br /><input name='subtitle' class='wpb_vc_param_value wpb-textinput title textfield subtitulo' type='text' /><span class='vc_description vc_clearfix'>Subtítulo.</span><br /></div>");
   }) ;


    jQuery(document).on('click','.btn_addimage', function(){
       jQuery(this).hide();
    });


    var sliders = [];
    var contentaddon = "";

    jQuery("span:contains('Save changes')").click(function(){

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const post = urlParams.get('post');

        var sliders = [];

        jQuery('.content_general').each(function(){
            slider = [];
            let idimagen = jQuery('.idsimagenes',jQuery(this)).val();
            console.log('IDS', idimagen);
            let enlace = jQuery('.enlace',jQuery(this)).val();
            let titulo = jQuery('.titulo',jQuery(this)).val();
            let subtitulo = jQuery('.subtitulo',jQuery(this)).val();
            slider = [idimagen, enlace, titulo, subtitulo];
            sliders.push(slider);

        });

        jQuery('.inputcontenido').val(sliders);

        var dataUpdatePostImagen = {
            'action': 'saveSliderPost',
            'postid': post,
            'content': sliders,
        };
        jQuery.post( '/wp-content/ajaxrewrite.php', dataUpdatePostImagen, function(response){
            console.log('RESPUESTA',response);
            // document.getElementById("rawhtml").value = response.content;
        });

        // $codeBse64 = btoa(unescape( encodeURIComponent( contentaddon+'@'+slider)));
        // jQuery('.inputcontenido').val($codeBse64);

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
