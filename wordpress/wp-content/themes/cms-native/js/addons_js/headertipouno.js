/**
 * Created by s.salas on 10/08/2016.
 */
jQuery(document).ready(function () {
    jQuery(document).on('change', '.idsimagenes', function () {
        if (jQuery(this).val() != null || jQuery(this) != "") {
            jQuery('.shortcodevideo').val("");
        }
    });


    jQuery('body').on('vcbc_videoselected', function (evt, video, medio, img, shortcode) {
        const initialcontent = tinymce.activeEditor.getContent();
        recogervalorvideo(video);
        recogervalor(shortcode);
        var urlImg = img;
        var videoimg = "<img src='" + urlImg + "'>";
        videoimagen(videoimg);
        jQuery('.imgVideo').val(videoimg);
        setTimeout(() => {
            tinymce.activeEditor.setContent(initialcontent);
        }, 200);
    });



    jQuery("span:contains('Save changes')").click(function () {

        var partner_content = jQuery('.partner_content').val();
        //var brandend_content = jQuery('.brandend_content').val();
        var imgVideo = jQuery('.imgVideo').val();
        var shortcode = jQuery('.shortcodevideo').val();
        var idplayer = jQuery('.idplayer').val();

        var i = 0, strLength = shortcode.length;

        for (i; i < strLength; i++) {

            shortcode = shortcode.replace("\"", "'");

        }

        shortcode = shortcode.replace("]", " idplayer='"+idplayer+"']");

        var idimagen = jQuery('.idsimagenes').val();
        var cabecera_content = jQuery('.cabecera_content').val();

        var clase = "";
        if(jQuery(".amarillo").is(':checked')) {
            clase = "p-content";
            var colorCintillo = "";
        }
        if(jQuery(".gris").is(':checked')) {
            clase = "p-content-gris";
            var colorCintillo = "";

        }
        var autoplay = ""
        var autoplayClass = "";
        if(jQuery(".autoplay_yes").is(':checked')) {
            autoplay = "yes";
            autoplayClass = "autoplay-yes";
        }
        if(jQuery(".autoplay_no").is(':checked')) {
            autoplay = "no";
            autoplayClass = "autoplay-no";
        }
        if( !jQuery(".amarillo").is(':checked') && !jQuery('.gris').is(':checked'))
        {
            clase = "p-content";
        }
        var logo_partner = jQuery('.logo_partner').val();
        var link_logo = jQuery('.link_logo').val();
        var texto_logo = jQuery('.texto_logo').val();
        var html = "";


        /*if (brandend_content!="") {
             var html = "<div class='fullimg principal nuevaclase'><div class='tituloemergente'><div class='grid grid-pad'><a class='open-btn close-nav quees' href='#'><span class='" + clase + "'>" + partner_content + "</span><span>" + brandend_content + "</span></a><h1><strong>" + cabecera_content + "</strong><p>#texto_content#</p></h1></div></div>";
         } else {}*/
        if (!shortcode) {
            html = "<div class='fullimg principal nuevaclase'><div class='tituloemergente'><div class='grid grid-pad'><a class='open-btn close-nav quees' href='#'><span class='" + clase + "'>" + partner_content + "</span></a><h1><strong>" + cabecera_content + "</strong><p>#texto_content#</p></h1></div></div>";
        } else {
            html = "<div class='fullvideo principal nuevaclase'><div class='grid grid-pad'><a class='open-btn close-nav quees' href='#'><span class='" + clase + "'>" + partner_content + "</span></a><h1><strong>" + cabecera_content + "</strong><p>#texto_content#</p></h1></div>";

        }

        if( idimagen )
        {
            html += "<div class='bloque'><small class='logocliente '><p>" + texto_logo + "</p><a href='" + link_logo + "' target='_blank' rel='nofollow'><img src=\".wp_get_attachment_url(" + logo_partner + ").\"></a></small></div><figure >\".wp_get_attachment_image(" + idimagen + ",large).\"</figure>";
        }/*else{
            html += "<figure >\".do_shortcode(\" "+shortcode+" \").\"</figure>";
        }*/
        if (imgVideo) {
            html += "<figure class='videoPrincipal' style='display:none;'>" + imgVideo + "</figure>";
        }
        html += "</div>";
        


        jQuery('.partner_content').attr('value', partner_content);
        //jQuery('.brandend_content').attr('value', brandend_content);

        jQuery('.shortcodevideo').attr('value', shortcode);
        jQuery('.idsimagenes').attr('value', idimagen);
        jQuery('.cabecera_content').attr('value', cabecera_content);
        jQuery('.link_logo').attr('value', link_logo);
        jQuery('.logo_partner').attr('value', logo_partner);
        jQuery('.texto_logo').attr('value', texto_logo);
        jQuery('.idplayer').attr('value', idplayer);


        var contentaddon = jQuery('.contentheader').html();
        var idvideo = jQuery('.idvideo').val();
        //console.log(contentaddon);
        $codeBse64 = btoa(unescape(encodeURIComponent(contentaddon + '@' + html + '@' + idvideo + '@' + shortcode + '@' + autoplay)));

        jQuery('.inputcontenido').val($codeBse64);

        //console.log(html);
    });
});
