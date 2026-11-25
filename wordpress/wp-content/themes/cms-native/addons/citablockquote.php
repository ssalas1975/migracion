<?php
/******* CITA BLOCKQUOTE *******/

add_shortcode( 'vocento_blockquote', 'blockquote_function' );
function blockquote_function( $atts, $content ) {
    extract( shortcode_atts( array(
        'el_class'   => '',
        'blockquote' => '',
        'alignment'  => '',
    ), $atts ) );

    $html = '
        <blockquote class="'.$alignment.' '.$el_class.'">
		<p class="parrafocount">'.$content.'</p>
        <address>'.$blockquote.'</address>
		</blockquote>
    ';

    return $html;
}
/******* CITA BLOCKQUOTE *******/
