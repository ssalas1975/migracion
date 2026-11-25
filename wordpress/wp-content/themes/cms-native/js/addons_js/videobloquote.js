/**
 * Created by j.marin on 30/08/2016.
 */
jQuery(document).ready(function () {

    jQuery("span:contains('Save changes')").click(function () {
        var shortcode = jQuery('.shortcodevideo').val();
        //jQuery('.shortcodevideo').attr('value', shortcode);
        var videoimagenContent = jQuery('.videoimagen').html();
        var idvideo = jQuery('.idvideo').val();
	
        $codeBse64 = btoa(unescape(encodeURIComponent(videoimagenContent + '@' + shortcode + '@'+ idvideo)));
        jQuery('.inputcontenido').val($codeBse64);
	
    });
	
    jQuery('body').on('vcbc_videoselected',function(evt,video,medio,img,shortcode) {
        const initialcontent = tinymce.activeEditor.getContent();
        recogervalorvideo(video);
		recogervalor("[vcbc videoid='" + video + "' medio='" + medio + "']");
        var urlImg = img;
        var videoimg = "<img src='" + urlImg + "'>";
        videoimagen(videoimg);
        setTimeout(() => {
            tinymce.activeEditor.setContent(initialcontent);
        }, 200);
	});

});

var intervalVideoSecundario = setInterval(function () {


    var htmlBody = jQuery('body iframe').contents().find('body');

    if (htmlBody) {
        if (jQuery(".vcbc-video.selected", htmlBody).size() > 0) {
            jQuery(htmlBody).delegate('#vcbc-filter-select-submit', 'click', function (e) {

                var videoid = jQuery(".vcbc-video.selected", htmlBody).data("videoid");
                var medio = jQuery(".vcbc-video.selected", htmlBody).data("medio");
                //jQuery('.shortcodevideo').val("[vcbc videoid='" + videoid + "' medio='" + medio + "']");
                window.parent.recogervalor("[vcbc videoid='" + videoid + "' medio='" + medio + "']");
                var urlImg = jQuery(".vcbc-video.selected img", htmlBody).attr("src");
                var videoimg = "<img src='" + urlImg + "'>";
                window.parent.videoimagen(videoimg);
                window.parent.tb_remove();
                clearInterval(intervalVideoSecundario);


            });

            clearInterval(intervalVideoSecundario);
        }


    }

}, 100);

