<?php

echo '<h1>Cambio Path Media Content</h1>';

if(isset($_POST['submit'])){
    $post_offset = $_POST['desde'];

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'offset'         => $post_offset,
    );
    $postslist = get_posts( $args );
    
    foreach($postslist as $post){
        var_dump($post->ID);
        $content = $post->post_content;

        $re = '/header_uno headertipouno="(.*?)"]/sm';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $valor =  base64_decode($matches[0][1]);
        $valor = str_replace('sites/4/', '', $valor);
        var_dump($valor);
        $valor = base64_encode( $valor );
        $content = str_replace($matches[0][1], $valor, $content);

        $re = '/full_image fullimage="(.*?)"]/sm';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $valor =  base64_decode($matches[0][1]);
        $valor = str_replace('sites/4/', '', $valor);
        $valor = base64_encode( $valor );
        $content = str_replace($matches[0][1], $valor, $content);

        $re = '/header_dos headertipodos="(.*?)"]/sm';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $valor =  base64_decode($matches[0][1]);
        $valor = str_replace('sites/4/', '', $valor);
        $valor = base64_encode( $valor );
        $content = str_replace($matches[0][1], $valor, $content);

        $re = '/lista_horizontal listahorizontal="(.*?)"]/sm';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $valor =  base64_decode($matches[0][1]);
        $valor = str_replace('sites/4/', '', $valor);
        $valor = base64_encode( $valor );
        $content = str_replace($matches[0][1], $valor, $content);

        $re = '/swiper_slider sliders="(.*?)"]/sm';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $valor =  base64_decode($matches[0][1]);
        $valor = str_replace('sites/4/', '', $valor);
        $valor = base64_encode( $valor );
        $content = str_replace($matches[0][1], $valor, $content);

        $re = '/video_bloquote videobloquote="(.*?)"]/sm';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $valor =  base64_decode($matches[0][1]);
        $valor = str_replace('sites/4/', '', $valor);
        $valor = base64_encode( $valor );
        $content = str_replace($matches[0][1], $valor, $content);

        $re = '/video_secundariovideosecundario="(.*?)"]/sm';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $valor =  base64_decode($matches[0][1]);
        $valor = str_replace('sites/4/', '', $valor);
        $valor = base64_encode( $valor );
        $content = str_replace($matches[0][1], $valor, $content);


        // $content = str_replace('sites/4/', '', $content);

        // wp_update_post( wp_slash( array(
        //     'ID' => $my_postid,
        //     'post_content' => $content,
        // ) ) );

        // update_post_meta($post->ID, 'oldcontent', 2);
        
    }

    echo 'Se terminó el proceso de parseo 2222';

}

?>

<form method="post" action="#">
<p><input type="text" name="desde" class="button button-primary" value=""></p>
<p><input type="text" name="hasta" class="button button-primary" value=""></p>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Cambiar"></p>
</form>