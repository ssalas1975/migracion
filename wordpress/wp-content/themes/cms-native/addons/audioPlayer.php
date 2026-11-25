<?php
/******* AUDIO PLAYER *******/

vc_add_shortcode_param( 'selectaudio', 'select_audio_settings_field' );
function select_audio_settings_field( $settings, $value ) {

wp_enqueue_media();
?>
    <div>
		<label for="audio_url">Audio</label>
		<input type="text" name="audio_url" id="audio_url" class="regular-text">
		<!--input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Subir Audio"-->
		<br/><br/>
		<span class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw" name="upload-btn" id="upload-btn">Subir Audio</span>
		<br/><br/>
	</div>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            //console.log(uploaded_image);
            var audio_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#audio_url').val(audio_url);
			$('.mp3').val(audio_url+'@'+uploaded_image.toJSON().id);
        });
    });
	
	$('#audio_url').val($('.mp3').val().split("@",1));
	
});
</script>
	
	<?php
   return '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
             esc_attr( $settings['param_name'] ) . ' ' .
             esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';
}



add_shortcode( 'vocento_audio_player', 'audio_player_function' );
function audio_player_function( $atts ) {
    extract( shortcode_atts( array(
        'mp3'     => '',
        'image_url'     => '',
        'alignment'     => '',
        'el_class'  => '',
    ), $atts ) );

    
	$mp3 = explode("@",$mp3);
	$urlaudio = wp_get_attachment_url($mp3[1]);
    $imgBack = wp_get_attachment_url( $atts['image_url'] );

    return '<div class="'.$atts['alignment'].'">
        <audio id="track'.$mp3[1].'" src="'.$urlaudio.'">
            <source type="audio/mpeg" src="'.$urlaudio.'">
            <p>Your browser does not support the audio element</p>
        </audio>

            <span class="content_anim1 infographic-circle animated fadeIn active '.$atts['el_class'].'" id="spanmediaplayer">
                <img src="'.get_template_directory_uri()."/img/play.svg".'" class="play controls _play'.$mp3[1].'" data-idplay="'.$mp3[1].'"/>
                <img src="'.get_template_directory_uri()."/img/pause.svg".'" class="stop controls _stop'.$mp3[1].'" style="display: none" data-idstop="'.$mp3[1].'"/>
                <img class="discaudio" src="'.$imgBack.'">
                <svg id="svg" class="svg'.$mp3[1].'" viewBox="0 0 200 200" enable-background="new 0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                    <circle id="bar" class="bar'.$mp3[1].'" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                </svg>
            </span></div>';
}
/******* AUDIO PLAYER *******/
