/**
 * Created by s.salas on 09/08/2016.
 */
jQuery(document).ready(function(){

    jQuery("span:contains('Save changes')").click(function(){

        var idsimagenes = jQuery('.idsimagenes').val();

        var titulo = jQuery('.titulo').val();
        jQuery('.titulo').attr('value', titulo);


        var textogeneral = jQuery('.textogeneral').val();
        jQuery('.textogeneral').text(textogeneral);

        var contentaddon = "";
        contentaddon += jQuery('.fullimagen').html();

        if (jQuery('.animacionway').attr('checked')) {
            var fullimage = "<article class='encabecadofullimg noticia'><div id='due' class='content_anim3 fullimg'><div class='tituloemergente'><div class='grid grid-pad'><h2><strong class='col-10-12 tablet-col-1-1'>" + titulo + "</strong><p class='col-8-12 tablet-col-1-1'>" + textogeneral + "</p></h2></div></div><figure><img class='imagenrelacionada' src=\".wp_get_attachment_url(" + idsimagenes + ").\"></figure></div></article>";
        } else {
            var fullimage = "<article class='encabecadofullimg noticia'><div id='due' class='content_anim3 fullimg animated fadeIn active'><div class='tituloemergente'><div class='grid grid-pad'><h2><strong class='col-10-12 tablet-col-1-1'>" + titulo + "</strong><p class='col-8-12 tablet-col-1-1'>" + textogeneral + "</p></h2></div></div><figure><img class='imagenrelacionada' src=\".wp_get_attachment_url(" + idsimagenes + ").\"></figure></div></article>";
        }

        var fullimage2 = '';

        $codeBse64 = btoa(unescape( encodeURIComponent( contentaddon+'@'+fullimage )));
        jQuery('.inputcontenido').val($codeBse64);

    });

});
