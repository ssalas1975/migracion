/**
 * Created by s.salas on 08/08/2016.
 */
jQuery(document).ready(function(){
    jQuery('.btn_add_li').click(function(){
        jQuery('.list_vertical').append('<div class="content_general" style="margin-top:20px;"><div class="vc_col-xs-12 wpb_el_type_attach_images vc_wrapper-param-type-attach_images vc_shortcode-param vc_column" data-vc-ui-element="panel-shortcode-param" data-vc-shortcode-param-name="images" data-param_type="attach_images" data-param_settings="{"type":"attach_images","heading":"Images","param_name":"images","value":"","description":"Select images from media library.","dependency":{"element":"source","value":"media_library"},"vc_single_param_edit_holder_class":["vc_col-xs-12","wpb_el_type_attach_images","vc_wrapper-param-type-attach_images","vc_shortcode-param","vc_column"]}"><input name="textonegrita" class="wpb_vc_param_value wpb-textinput title textfield textonegrita" type="text"><span class="vc_description vc_clearfix">Encabezado en negrita.</span><br/><textarea class="wpb_vc_param_value wpb-textinput title textfield textogeneral" rows="4" cols="50"></textarea><span class="vc_description vc_clearfix">Texto.</span><br/><div class="wpb_element_label">Imágenes</div><div class="edit_form_line"><input type="hidden" class="wpb_vc_param_value gallery_widget_attached_images_ids images attach_images ids_images" name="images" value=""><div class="gallery_widget_attached_images"><ul class="gallery_widget_attached_images_list ui-sortable"></ul></div><div class="gallery_widget_site_images"></div><a class="gallery_widget_add_images" href="#" title="Add images">Add images</a></div></div><span class="vc_general vc_ui-button vc_ui-button-default vc_ui-button-shape-rounded vc_ui-button-fw btn_remove" style="margin-top:20px;">Borrar Elemento de la Lista</span></div>');
    });

    jQuery('input.wpb_vc_param_value.wpb-textinput.offset.textfield', jQuery(this)).attr('maxlength', '2');
    jQuery('input.wpb_vc_param_value.wpb-textinput.offset.textfield', jQuery(this)).attr('width', '10');


    var lista = "";
    var contentaddon = "";
    var titulo = "";

    jQuery("span:contains('Save changes')").click(function(){
        titulo = jQuery('.titulolista').val();
        lista = "<li><h2>"+titulo+"</h2></li>";
        //var contador = 1;
        jQuery('.content_general').each(function(){

            var textonegrita = jQuery('.textonegrita',jQuery(this)).val();
            var textogeneral = jQuery('.textogeneral', jQuery(this)).val();
            var claseExtra = jQuery('.claseExtra', jQuery(this)).val();
            var animacionWay = jQuery('.animacionway', jQuery(this)).val();

            lista += "<li " + claseExtra + "><p><strong>&nbsp;" + textonegrita + "&nbsp;</strong>" + textogeneral + "</p>";

            var idsimagenes = jQuery('.ids_images', jQuery(this)).val();
            var ids = idsimagenes.split(",");
            var imagenes = "";
            jQuery.each(ids, function(i, val){
                imagenes += "<img class='content_anim1 col-1-1' src=\".wp_get_attachment_url("+val+").\">";
            });

            lista += imagenes+"</li>";

            contentaddon = "";
            jQuery('.titulolista').attr('value',titulo);
            jQuery('.textonegrita',jQuery(this)).attr('value',textonegrita);
            jQuery('.textogeneral', jQuery(this)).text(textogeneral);
            jQuery('.claseExtra', jQuery(this)).text(claseExtra);
            jQuery('.animacionway', jQuery(this)).text(animacionway);

            contentaddon += jQuery('.list_vertical').html();

            //contador ++;

        });


        $codeBse64 = btoa(unescape( encodeURIComponent( contentaddon+'@'+lista)));
        jQuery('.inputcontenido').val($codeBse64);

    });

    jQuery(document).on('click','.btn_remove',function(){

        jQuery(this).parent().remove();

        titulo = jQuery('.titulolista').val();
        lista = "<li><h2>"+titulo+"</h2></li>";
        //var contador = 1;
        jQuery('.content_general').each(function(){

            var textonegrita = jQuery('.textonegrita',jQuery(this)).val();
            var textogeneral = jQuery('.textogeneral', jQuery(this)).val();
            var claseExtra = jQuery('.claseExtra', jQuery(this)).val();

            lista += "<li " + claseExtra + "><p><strong>&nbsp;" + textonegrita + "&nbsp;</strong>" + textogeneral + "</p>";

            var idsimagenes = jQuery('.ids_images', jQuery(this)).val();
            var ids = idsimagenes.split(",");
            var imagenes = "";
            jQuery.each(ids, function(i, val){
                imagenes += "<img class='content_anim1 col-1-1' src=\".wp_get_attachment_url("+val+").\">";
            });

            lista += imagenes+"</li>";

            contentaddon = "";
            jQuery('.titulolista').attr('value',titulo);
            jQuery('.textonegrita',jQuery(this)).attr('value',textonegrita);
            jQuery('.textogeneral', jQuery(this)).text(textogeneral);
            jQuery('.animacionway', jQuery(this)).attr('value', animacionway);
            jQuery('.claseExtra', jQuery(this)).text(claseExtra);
            contentaddon += jQuery('.list_vertical').html();

            //contador ++;

        });

        //console.log(contentaddon);

        $codeBse64 = btoa(unescape( encodeURIComponent( contentaddon+'@'+lista)));
        jQuery('.inputcontenido').val($codeBse64);

    });



});
