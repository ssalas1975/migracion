<?php

echo '<h1>Actualizar datos importados</h1>';

if(isset($_POST['submit'])){
    $i = 0;
    $check = true;
    global $wpdb;

    function actualizarThumbnail($check){
        global $wpdb;
        $sql3 = "SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_thumbnail_id'";
        $postsThumbnail = $wpdb->get_results( $sql3, ARRAY_A);
        foreach($postsThumbnail as $thumbainID){
            if($thumbainID['meta_value'] !== ""){
                $ch = curl_init();
                $url = '';
                if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
                    $url = 'https://pre-www.xlsemanal.com/?idfoto=';
                }elseif ($_ENV["ENVIRON"] == "PRO") {
                    $url = 'https://www.xlsemanal.com/?idfoto=';
                }
                curl_setopt($ch, CURLOPT_URL, $url.$thumbainID['meta_value']); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                curl_setopt($ch, CURLOPT_HEADER, 0); 
                $data = curl_exec($ch); 
                curl_close($ch); 
                $data = str_replace("/3/", "/5/", $data); 
                $idThumbnail = attachment_url_to_postid('https://ms-rc.srv.vocento.in/xlsemanal/'.$data);
                var_dump($idThumbnail);
                update_post_meta($thumbainID['post_id'], '_thumbnail_id', $idThumbnail);
                update_post_meta($thumbainID['post_id'],'oldcontent',2);
            }
        }
        if(!$check){
            echo '<h2>Actualización realizada con éxito.</h2>';
        }
    }


    function actualizarTitle($check){
        global $wpdb;
        $sql2 = "SELECT ID, post_title FROM ".$wpdb->prefix."posts WHERE id IN (SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'oldcontent' AND meta_value = '1')";
        $postsTitle = $wpdb->get_results( $sql2, ARRAY_A);
        foreach($postsTitle as $postTitle){
            update_post_meta($postTitle['ID'],'titular',$postTitle['post_title']);
            // update_post_meta($postTitle['ID'],'oldcontent',2);
        }
        actualizarThumbnail($check);
    }


    while ($check) {
        $ch = curl_init();
        // $img = get_post_meta($idPost, 'imagen_'.$i.'_foto', true);
        // var_dump($img);

        $sql = "SELECT post_id, meta_value FROM ".$wpdb->prefix."postmeta where meta_key = 'imagen_".$i."_foto' AND meta_key = 'oldcontent' AND meta_value = '1'";
        $posts = $wpdb->get_results( $sql, ARRAY_A); 

        // var_dump($posts);
        // echo 'https://pre-www.xlsemanal.com/?idfoto='.$posts[0]["meta_value"];
        // var_dump($posts[0]['post_title']);
        $url = '';
        if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
            $url = 'https://pre-www.xlsemanal.com/?idfoto=';
        }elseif ($_ENV["ENVIRON"] == "PRO") {
            $url = 'https://www.xlsemanal.com/?idfoto=';
        }

        curl_setopt($ch, CURLOPT_URL, $url.$posts[0]["meta_value"]); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $data = curl_exec($ch); 
        curl_close($ch); 
        $data = str_replace("/3/", "/5/", $data); 

        update_post_meta($posts[0]['post_id'],'urlimagengaleria_'.$i, $data);

        if(count($posts) == 0){
            $check = false;
            actualizarTitle($check);
        }
        // if($i == 10){
        //     $check = false;
        //     echo '<h2>Actualización de las urls realizada con éxito</h2>';
        // }
        $i++;
    }

    // update_post_meta($postTitle['ID'],'oldcontent',2);

}
    
?>

<form method="post" action="#">
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Actualizar rutas"></p>
</form>
  