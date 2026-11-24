/**
 * Created by j.marin on 29/08/2016.
 */
jQuery(document).ready(function () {

    jQuery("span:contains('Save changes')").click(function () {
        var shortcode = jQuery('.shortcodevideo').val();
        //jQuery('.shortcodevideo').attr('value', shortcode);
        var contentaddon = jQuery('.videosecundario').html();
        var idvideo = jQuery('.idvideo').val();

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


        $codeBse64 = btoa(unescape(encodeURIComponent(contentaddon + '@' + shortcode +'@'+ idvideo + '@' + autoplay)));
        jQuery('.inputcontenido').val($codeBse64);

    });
	
	jQuery('body').on('vcbc_videoselected',function(evt,video,medio,img,shortcode){
        const initialcontent = tinymce.activeEditor.getContent();
	    recogervalorvideo(video);
		recogervalor("[vcbc videoid='" + video + "' medio='" + medio + "']");
        var urlImg = img;
        var videoimg = "<img src='" + urlImg + "'>";
        videoimagen(videoimg);
        tinymce.activeEditor.setContent("");
        setTimeout(() => {
            tinymce.activeEditor.setContent(initialcontent);
        }, 200);
	});

});

/*
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

}, 100);*/

