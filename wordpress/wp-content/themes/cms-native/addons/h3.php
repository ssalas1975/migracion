<?php






/******* H3 *******/

add_shortcode( 'vocento_h3', 'texto_h3_function' );
function texto_h3_function( $atts ) {
    extract( shortcode_atts( array(
        'textoh3'     => 'something',
        'el_class'      => ''
    ), $atts ) );
    if ( ! add_post_meta( get_the_ID() , 'titulo3', $textoh3, true ) ) { 
        update_post_meta( get_the_ID() , 'titulo3', $textoh3 );
    }
    $html = '
        <h3 class="'.$el_class.'">'.$textoh3.'</h3>
    ';

    return $html;
}
/******* H3 *******/


?>
