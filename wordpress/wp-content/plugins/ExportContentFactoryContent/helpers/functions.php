<?php

function parsers_olg_img_gallery($request){
    if (isset($_GET['idfoto'])) {
        $idPhoto = $_GET['idfoto'];
        $src = wp_get_attachment_image_src($idPhoto, 'full')[0];
        echo $src;
        exit();
    }
}

add_action('parse_request', 'parsers_olg_img_gallery', 1, 1);