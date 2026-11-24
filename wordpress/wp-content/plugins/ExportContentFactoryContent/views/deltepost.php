<?php

if(isset($_POST['submit'])){
    $query_args = array(
        'post_type'  => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'   => 'oldcontent',
                'value' => '1',
            ),
        )
    );
    $query = new WP_Query( $query_args );

    if ( $query->posts ) {
        foreach ( $query->posts as $post ) {
            // var_dump($post->ID);
            wp_delete_post( $post->ID, true );
        }
    }
}

?>

<form method="post" action="#">
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Borrar"></p>
</form>