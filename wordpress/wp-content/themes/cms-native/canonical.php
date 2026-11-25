<?php

/**
 * Canonical URL for Medios.
 *
 *
 * @version 1.0
 * @author j.marin
 */
//a�adiendo metabox para canonical

add_action( 'add_meta_boxes', 'meta_box_canonical' );
function meta_box_canonical()
{
    add_meta_box( 'meta-box-canonical', 'Canonical URL Medios', 'print_meta_box_canonical', 'post', 'side', 'low' );
}

//pintando metabox canonical
function print_meta_box_canonical()
{
    $customList = print_canoncial_callback();
    echo $customList;
}

//guarda los metadatos del canonical
function canonical_save_meta_box( $post_id) {
    if(isset($_POST['canonicalURL'])){
        $urlCanonical = $_POST['canonicalURL'];
        update_post_meta($post_id, "canonical-url", $urlCanonical);
    }
}
add_action( 'save_post', 'canonical_save_meta_box' );

//elimina los metadatos del canonical
function canonical_delete_meta_box( $post_id) {
        delete_post_meta($post_id, "canonical-url");
}
add_action( 'delete_post', 'canonical_save_meta_box' );

//devuelve la lista con los medios y sus canonicals
function print_canoncial_callback(){
    $html="";
    global $pagenow;
    if ( 'post.php' == $pagenow ) {
        //obteniendo datos
        $taxName = "Medios";
        // $terms =  get_terms($taxName);
        $terms = get_terms(
            array(
                'taxonomy' => 'Medios',
                'update_term_meta_cache' => false,
            )
        );
        $urlPost = get_permalink();
        $urlPost = wp_make_link_relative( $urlPost );
		$urlPost = preg_replace("/\/contentfactory/", '', $urlPost);
        $html .= '<ul id="MediosCanonical" class="categorychecklist form-no-clear">
                ';
        //pintando enlaces para cada medio
        foreach ( $terms as $term ) {
            $key_1_value = get_post_meta( get_the_ID(), 'canonical-url', true );
            // Check if the custom field has a value.
            $url_medio = get_field('url_medio', $term);
            $url_medio = preg_replace("/content-factory/", 'www', $url_medio);
            $canonicalURL = $url_medio."/contentfactory/post".$urlPost;
            if ( $key_1_value == $canonicalURL ) {
                $html.= '<li>
                         <label class="selectit">
                             <input value="'.$canonicalURL.'" type="radio" name="canonicalURL" checked>
                             '.$term->name.'</label>
                         <a target="blank" href="'.$canonicalURL.'">Ver URL </a></li>
                ';
            }else{
                $html.= '<li>
                        <label class="selectit">
                            <input value="'.$canonicalURL.'" type="radio" name="canonicalURL">
                            '.$term->name.'</label>
                        <a target="blank" href="'.$canonicalURL.'">Ver URL </a></li>
                ';
            }
        }
        $html .= '</ul>';
    }
    return $html;
}
