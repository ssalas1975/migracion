/**
 * Created by s.salas on 08/08/2016.
 */
jQuery(document).ready(function () {

    jQuery('.btn_add_li').click(function () {
        jQuery('.list_horizontal').append("<div class='content_general' style='margin-top: 20px;'><input name='title' class='wpb_vc_param_value wpb-textinput title textfield texto_lista' type='text'><span class='vc_description vc_clearfix'>Texto para el elemento de lista.</span><hr /><input name='title' class='wpb_vc_param_value wpb-textinput title textfield classList' type='text'> <span class='vc_description vc_clearfix'>Clase personalizada para elemento de la lista</span><hr /><div class='edit_form_line'><label class='vc_checkbox-label'><input class='wpb_vc_param_value autoplay checkbox check_icon' type='checkbox' name='autoplay'>Usar icono</label><span class='vc_description vc_clearfix'>Activar icono.</span></div><div class='vc_col-xs-12 wpb_el_type_attach_image vc_wrapper-param-type-attach_image vc_shortcode-param vc_column btn_add_icono' style='display: none'><div class='edit_form_line'> <input type='hidden' class='wpb_vc_param_value gallery_widget_attached_images_ids image attach_image idsimagenes' name='image' value=''><div class='gallery_widget_attached_images'><ul class='gallery_widget_attached_images_list ui-sortable'></ul></div><div class='gallery_widget_site_images'></div><a class='gallery_widget_add_images btn_addimage' href='#' use-single='true' title='Añadir icono'>Añadir Icono</a><span class='vc_description vc_clearfix'>Seleccionar una imagen para usar como icono.</span></div></div><span class='vc_general vc_ui-button vc_ui-button-default vc_ui-button-shape-rounded vc_ui-button-fw btn_remove' style='margin-top: 20px;'>Borrar Elemento de la Lista</span><br /></div>");
    });


    jQuery(document).on('change', '.check_icon', function () {
        if (jQuery(this).is(':checked')) {
            jQuery(this).attr('checked', 'checked');
            jQuery(this).parent().parent().parent().find('.btn_add_icono').show(200);
        } else {
            jQuery(this).removeAttr('checked');
            jQuery(this).parent().parent().parent().find('.btn_add_icono').hide(200);
        }
    });

    var lista = "";
    var contentaddon = "";
    var titulo = "";



    jQuery("span:contains('Save changes')").click(function () {
        titulo = jQuery('.titulolista').val();
        lista = "<h2>" + titulo + "</h2><ul class='listahorizontal numerada nuevaclase'>";
        var contador = 1;
        jQuery('.content_general').each(function () {
            var res = "";
            var textolista = jQuery('.texto_lista', jQuery(this)).val();
            var clasePersonalizada = jQuery('.classList', jQuery(this)).val();
            var idsimagenes = ""

            if (jQuery('.check_icon').is(':checked')) {
                idsimagenes = jQuery('.idsimagenes', jQuery(this)).val();
            } else {
                idsimagenes = "";
            }


            if (idsimagenes == null || idsimagenes == "") {
                res = "0" + contador;
            } else {
                res = "<img src=\".wp_get_attachment_url(" + idsimagenes + ").\">";
            }

            lista += "<li class='"+clasePersonalizada+"'><span>" + res + "</span><p>" + textolista + "</p></li>";

            jQuery('.titulolista').attr('value', titulo);
            jQuery('.texto_lista', jQuery(this)).attr('value', textolista);
            jQuery('.classList', jQuery(this)).attr('value', clasePersonalizada);

            lista += contador;
            contador++;
        });

        contentaddon = "";
        contentaddon += jQuery('.list_horizontal').html();

        $codeBse64 = btoa(unescape(encodeURIComponent(contentaddon + '@' + lista)));
        jQuery('.inputcontenido').val($codeBse64);

    });

    jQuery(document).on('click', '.btn_remove', function () {

        jQuery(this).parent().remove();
        titulo = jQuery('.titulolista').val();
        lista = "<h2>" + titulo + "</h2><ul class='listahorizontal numerada nuevaclase'>";
        var contador = 1;
        jQuery('.content_general').each(function () {
            var res = "";
            var textolista = jQuery('.texto_lista', jQuery(this)).val();
            var idsimagenes = jQuery('.idsimagenes', jQuery(this)).val();

            if (idsimagenes == null || idsimagenes == "") {
                res = "0" + contador;
            } else {
                res = "<img src=\".wp_get_attachment_url(" + idsimagenes + ").\">";
            }

            lista += "<li><span>" + res + "</span><p>" + textolista + "</p></li>";

            contentaddon = "";
            jQuery('.titulolista').attr('value', titulo);
            jQuery('.texto_lista', jQuery(this)).attr('value', textolista);
            contentaddon += jQuery('.list_horizontal').html();

            contador++;
        });

        $codeBse64 = btoa(unescape(encodeURIComponent(contentaddon + '@' + lista)));
        jQuery('.inputcontenido').val($codeBse64);

    });



});
