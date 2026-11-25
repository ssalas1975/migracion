<?php



/******** H2 *******/

add_shortcode( 'vocento_h2', 'texto_h2_function' );
function texto_h2_function( $atts ) {
    extract( shortcode_atts( array(
        'textoh2'     => 'something',
        'el_class'      => ''
    ), $atts ) );
    //añadiendo metadatos para recogerlos en las noticias relacion
    if ( ! add_post_meta( get_the_ID() , 'titulo2', $textoh2, true ) ) { 
        update_post_meta( get_the_ID() , 'titulo2', $textoh2 );
    }
    $html = '
        <h2 class="'.$el_class.'">'.$textoh2.'</h2>
    ';

    return $html;
?>

   <?php
}
/******** H2 *******/

?>
