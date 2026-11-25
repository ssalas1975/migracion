

<form method="post" action="#">
<p class="submit"><input type="submit" name="submit2" id="submit" class="button button-primary" value="Procesar"></p>
</form>


<?php

if(isset($_POST['submit'])){
    $args = array(
        'meta_key' => 'oldcontent',
        'meta_value' => 1,
        'post_status' => 'any',
        'posts_per_page' => -1
    );
    $posts = get_posts($args);
    
    foreach($posts as $post){
        $content = $post->post_content;

        // var_dump($content);

        $re = '/\[header_uno(.*?)="(.*?)"\]/msx';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $decodeData = base64_decode($matches[0][2]);
        $decodeData = str_replace('/sites/4/', '/', $decodeData);
        $encodeData = base64_encode($decodeData);
        $content = str_replace($matches[0][2], $encodeData, $content);


        $re = '/\[header_dos(.*?)="(.*?)"\]/msx';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $decodeData = base64_decode($matches[0][2]);
        $decodeData = str_replace('/sites/4/', '/', $decodeData);
        $encodeData = base64_encode($decodeData);
        $content = str_replace($matches[0][2], $encodeData, $content);


        $re = '/\[header_dos(.*?)="(.*?)"\]/msx';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $decodeData = base64_decode($matches[0][2]);
        $decodeData = str_replace('/sites/4/', '/', $decodeData);
        $encodeData = base64_encode($decodeData);
        $content = str_replace($matches[0][2], $encodeData, $content);

        
        $re = '/\[lista_horizontal(.*?)="(.*?)"\]/msx';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $decodeData = base64_decode($matches[0][2]);
        $decodeData = str_replace('/sites/4/', '/', $decodeData);
        $encodeData = base64_encode($decodeData);
        $content = str_replace($matches[0][2], $encodeData, $content);


        $re = '/\[swiper_slider(.*?)="(.*?)"\]/msx';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $decodeData = base64_decode($matches[0][2]);
        $decodeData = str_replace('/sites/4/', '/', $decodeData);
        $encodeData = base64_encode($decodeData);
        $content = str_replace($matches[0][2], $encodeData, $content);

        
        $re = '/\[video_bloquote(.*?)="(.*?)"\]/msx';
        preg_match_all($re, $contentt, $matches, PREG_SET_ORDER, 0);
        $decodeData = base64_decode($matches[0][2]);
        $decodeData = str_replace('/sites/4/', '/', $decodeData);
        $encodeData = base64_encode($decodeData);
        $content = str_replace($matches[0][2], $encodeData, $content);

        
        $re = '/\[video_secundario(.*?)="(.*?)"\]/msx';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $decodeData = base64_decode($matches[0][2]);
        $decodeData = str_replace('/sites/4/', '/', $decodeData);
        $encodeData = base64_encode($decodeData);
        $content = str_replace($matches[0][2], $encodeData, $content);


        $my_post = array(
            'ID'           => $post->ID,
            'post_content' => $content,
        );
        wp_update_post( $my_post );
        update_post_meta($post->ID, 'oldcontent', 2);

    }
    echo '<h1>Proceso completado</h1>';
}
