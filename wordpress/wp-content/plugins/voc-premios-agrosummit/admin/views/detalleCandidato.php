<?php

if (!defined(ABSPATH)) {
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/', '', $pagePath[0] . '/wp-load.php'));
} else {
    exit();
}

$idCandidato = $_GET['candidatoid'];
$candidato = get_post_meta($idCandidato, 'candidato_metadata', true);

$type = $candidato['type'];
$categoria_propuesta = $candidato["categoria_propuesta"];
$tipo_solicitud = $candidato["tipo_solicitud"];
$tipo_concurso = $candidato["tipo_concurso"];
$nombre_comercial_empresa = $candidato["nombre_comercial_empresa"];
$cif = $candidato["cif"];
$email_candidato = $candidato["email_candidato"];
$telefono_candidato = $candidato["telefono_candidato"];
$sector_actividad = $candidato["sector_actividad"];
$provincia_principal = $candidato["provincia_principal"];
$generacion_familiar_actual = $candidato["generacion_familiar_actual"];
$linkedin_empresa = $candidato["linkedin_empresa"];
$web_empresa = $candidato["web_empresa"];
$nombre_candidato = $candidato["nombre_candidato"];
$apellidos_candidato = $candidato["apellidos_candidato"];
$cargo_candidato = $candidato["cargo_candidato"];
$apellidos_candidato = $candidato["apellidos_candidato"];
$anio_fundacion = $candidato["anio_fundacion"];
$linkedin_candidato = $candidato["linkedin_candidato"];
$nombre_comercial_empresa = $candidato["nombre_comercial_empresa"];
$sector_actividad_nominador = $candidato["sector_actividad_nominador"];
$provincia_principal_nominador = $candidato["provincia_principal_nominador"];
$motivo_nominacion = $candidato["motivo_nominacion"];
$nombre_persona_contacto = $candidato["nombre_persona_contacto"];
$cargo_persona_contacto = $candidato["cargo_persona_contacto"];
$email_empresa_nominada = $candidato["email_empresa_nominada"];
$telefono_empresa_nominada = $candidato["telefono_empresa_nominada"];
$meritos_liderazgo = $candidato["meritos_liderazgo"];
$meritos_vision = $candidato["meritos_vision"];
$meritos_impacto = $candidato["meritos_impacto"];
$porcentaje_ventas = $candidato["porcentaje_ventas"];
$dato_cerrado_ventas_2025 = $candidato["dato_cerrado_ventas_2025"];
$porcentaje_ebita = $candidato["porcentaje_ebita"];
$dato_cerrado_ebita_2025 = $candidato["dato_cerrado_ebita_2025"];
$tendencia_evolucion_ebita = $candidato["tendencia_evolucion_ebita"];
$porcentaje_empleo_neto = $candidato["porcentaje_empleo_neto"];
$dato_cerrado_empleo_neto_2025 = $candidato["dato_cerrado_empleo_neto_2025"];
$nombre_nominador = $candidato["nombre_nominador"];
$apellidos_nominador = $candidato["apellidos_nominador"];
$email_nominador = $candidato["email_nominador"];
$telefono_nominador = $candidato["telefono_nominador"];
$relacion_con_nominado = $candidato["relacion_con_nominado"];
$politica_privacidad_001_nominado = $candidato["politica_privacidad_001_nominado"];
$politica_privacidad_002_nominado = $candidato["politica_privacidad_002_nominado"];
$politica_privacidad_003_nominado = $candidato["politica_privacidad_003_nominado"];
$politica_privacidad_004_nominado = $candidato["politica_privacidad_004_nominado"];
$politica_privacidad_005_nominado = $candidato["politica_privacidad_005_nominado"];
$politica_privacidad_001_nominador = $candidato["politica_privacidad_001_nominador"];
$politica_privacidad_002_nominador = $candidato["politica_privacidad_002_nominador"];
$politica_privacidad_003_nominador = $candidato["politica_privacidad_003_nominador"];
$nombre_nominador = $candidato["nombre_nominador"];
$nombre_apellidos_empresario = $candidato["nombre_apellidos_empresario"];
$dni_nie_empresario = $candidato["dni_nie_empresario"];
$provincia_ccaa_empresario = $candidato["provincia_ccaa_empresario"];
$cargo_empresario = $candidato["cargo_empresario"];
$empresa_empresario = $candidato["empresa_empresario"];
$meritos_liderazgo_empresario = $candidato["meritos_liderazgo_empresario"];
$meritos_vision_empresario = $candidato["meritos_vision_empresario"];
$meritos_impacto_empresario = $candidato["meritos_impacto_empresario"];
$porcentaje_ventas_empresario = $candidato["porcentaje_ventas_empresario"];
$dato_cerrado_ventas_2025_empresario = $candidato["dato_cerrado_ventas_2025_empresario"];
$porcentaje_ebita_empresario = $candidato["porcentaje_ebita_empresario"];
$dato_cerrado_ebita_2025_empresario = $candidato["dato_cerrado_ebita_2025_empresario"];
$porcentaje_empleo_neto_empresario = $candidato["porcentaje_empleo_neto_empresario"];
$dato_cerrado_empleo_neto_2025_empresario = $candidato["dato_cerrado_empleo_neto_2025_empresario"];
$foto_familia = $candidato['foto_familia'];
$logo_empresa = $candidato['logo_empresa'];
$foto_empresario = $candidato['foto_empresario'];

if(isset($_POST['subirficheros'])){

    $newsUrls = [];

    if (!empty($_FILES['upload_attachment']['name'][0])) {

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );


        $files = $_FILES['upload_attachment'];
        $count = 0;
        $galleryImages = array();


        foreach ($files['name'] as $count => $value) {

            if ($files['name'][$count]) {

                $file = array(
                    'name'     => $files['name'][$count],
                    'type'     => $files['type'][$count],
                    'tmp_name' => $files['tmp_name'][$count],
                    'error'    => $files['error'][$count],
                    'size'     => $files['size'][$count]
                );

                $upload_overrides = array( 'test_form' => false );
                $upload = wp_handle_upload($file, $upload_overrides);


                // $filename should be the path to a file in the upload directory.
                $filename = $upload['file'];

                // The ID of the post this attachment is for.
                $parent_post_id = $post_id;

                // Check the type of tile. We'll use this as the 'post_mime_type'.
                $filetype = wp_check_filetype( basename( $filename ), null );

                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                array_push($newsUrls, $attachment['guid']);

                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );


            }

            $count++;

        }


        $upload_file_cat1 = array_merge($upload_file_cat1, $newsUrls);
        $candidato['upload_file_cat1'] = $upload_file_cat1;
        update_post_meta($idCandidato, 'candidato_metadata', $candidato);

    }


}

?>
<style>
    img.sh-standard-logo{max-width: 300px;}
    .sh-section-14e6bc0776f1c94bef259dfed42dec4d {padding: 50px 0px 50px 0px;}
    .sh-section-overlay-14e6bc0776f1c94bef259dfed42dec4d{z-index: -1000; background-position: absolute;top: 0; bottom: 0; left: 0; right: 0;}
    .sh-section-overlay-front {z-index: 0;}
    .sh-column-9dae4ec81245eb9e327ba31ecc60f696 {padding: 0px 30px 0px 15px;background-position: center center;}
    @media (max-width: 800px) {
        .sh-column-9dae4ec81245eb9e327ba31ecc60f696 .sh-column-wrapper {margin-left: auto;margin-right: auto;}
    }
    #text-block-752304fecac30efb79218b85205ca41d {margin: 0px 0px 15px 0px;}
    #text-block-752304fecac30efb79218b85205ca41d .drop-cap {font-weight: bold;font-size: 50px;display: block;float: left;margin: 8px 10px 0 0;}
    .sh-column-60871d11e9728c8259314d72a2264041 {padding: 30px 30px 30px 30px;margin: 0px px 0px px;background-color: #f7f7f7;border-left: 1px solid #d8d6ce;border-right: 1px solid #d8d6ce;border-top: 1px solid #d8d6ce;border-bottom: 1px solid #d8d6ce;background-position: center center;}@media (max-width: 800px) {.sh-column-60871d11e9728c8259314d72a2264041 {padding: 20px 20px 20px 20px;}}@media (max-width: 800px) {.sh-column-60871d11e9728c8259314d72a2264041 .sh-column-wrapper {margin-left: auto;margin-right: auto;}}@media (max-width: 800px) {.sh-column-60871d11e9728c8259314d72a2264041 {}}
    #heading-724795aea974582be083b6f12ec9f949 {margin: 0px 0px 15px 0px;}#heading-724795aea974582be083b6f12ec9f949 .sh-heading-content {font-weight: 700!important;color: #bf1717;}#heading-724795aea974582be083b6f12ec9f949 .sh-heading-content,#heading-724795aea974582be083b6f12ec9f949 .sh-heading-additional-text {}
    #divider-21007d757d9a460cd741f85ff224756f {margin: 25px 0px 25px 0px!important;}#divider-21007d757d9a460cd741f85ff224756f.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #d8d6ce;border-top-style: solid;}
    #empty-space-154b59fb5104f19e7acb2f401c93f8ff {height: 20px;}
    #button-a0e4a49cd2a8af9de60d98ff60b3c9b5 .sh-button {background-color: #bf1717;color: #ffffff;}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5 {margin: 0px 0px 0px 0px;}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5:not(.sh-button-style-2) .sh-button:hover {}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5.sh-button-style-2 .sh-button:after {}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5.sh-button-style-2 .sh-button:hover {}
    #divider-aced65c20151be9648400223d7b26874 {margin: 25px 0px 25px 0px!important;}#divider-aced65c20151be9648400223d7b26874.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #d8d6ce;border-top-style: solid;}
    #heading-a9871d588a8ef29f3b30b78f07a90e2e {margin: 0px 0px 15px 0px;}#heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-content {font-weight: 700!important;color: #bf1717;}#heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-content,#heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-additional-text {}
    #divider-461a1c6f6bd592cdc88fa2b6b6f12251 {margin: 25px 0px 25px 0px!important;}#divider-461a1c6f6bd592cdc88fa2b6b6f12251.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #d8d6ce;border-top-style: solid;}
    #empty-space-1faaa15572ffe2865ac8ccc3f2e4681b {height: 30px;}
    #heading-a7650996df19bf4a6a531ae1afe95e9f {margin: 0px 0px 15px 0px;}#heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-content {font-weight: 700!important;color: #bf1717;}#heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-content,#heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-additional-text {}
    #divider-76767fe041a00c23970936184986d47f {margin: 25px 0px 25px 0px!important;}#divider-76767fe041a00c23970936184986d47f.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #d8d6ce;border-top-style: solid;}
    .sh-section-49239f9fc68e5937068fb0bc0847eec5 {padding: 0px 0px 0px 0px;}.sh-section-overlay-49239f9fc68e5937068fb0bc0847eec5{z-index: -1000;background: #ffffff;position: absolute;top: 0; bottom: 0; left: 0; right: 0;}.sh-section-overlay-front {z-index: 0;}
    .sh-column-68912b23ce7b437eb73344b462c5cab8 {padding: 0px 0px 0px 0px;background-position: center center;}@media (max-width: 800px) {.sh-column-68912b23ce7b437eb73344b462c5cab8 .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #divider-a51fc6bcf1be62fef802a0f6cff67e79 {margin: 0px 0px 0px 0px!important;}#divider-a51fc6bcf1be62fef802a0f6cff67e79.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #888269;border-top-style: solid;}
    .sh-section-4efd962705615b8b1c3e1a2d59af1439 {padding: 40px 0px 20px 0px;}.sh-section-overlay-4efd962705615b8b1c3e1a2d59af1439{z-index: -1000;background: #ffffff;position: absolute;top: 0; bottom: 0; left: 0; right: 0;}.sh-section-overlay-front {z-index: 0;}
    .sh-column-3a63afc1d9865b6d638e02696529eaac {padding: 0px 15px 15px 15px;background-position: center center;}@media (max-width: 800px) {.sh-column-3a63afc1d9865b6d638e02696529eaac .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 {margin: 0px 0px 15px 0px;}#heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 .sh-heading-content {font-weight: 400!important;color: #888269;}#heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 .sh-heading-content,#heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 .sh-heading-additional-text {}
    .sh-column-1b2fb072a23d8bf6406f4b020f282ce0 {}@media (max-width: 800px) {.sh-column-1b2fb072a23d8bf6406f4b020f282ce0 .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    .sh-column-99eeb9ebbba5da4a5a75a9c9eb4a3daf {padding: 0px 50px 0px 50px;background-position: center center;}@media (max-width: 800px) {.sh-column-99eeb9ebbba5da4a5a75a9c9eb4a3daf .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #single-image-7697333153aa5bfe5b4941c72b78ea82 {text-align: center;}
    .sh-column-f9359beae0c3daa0f61acbdd3c23fbaf {padding: 0px 50px 0px 50px;background-position: center center;}@media (max-width: 800px) {.sh-column-f9359beae0c3daa0f61acbdd3c23fbaf .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #single-image-38e949acc16fed552fe68f3feac131f5 {text-align: center;}
    .sh-column-07ffafb379b494afcad446e0c8ad05ec {}@media (max-width: 800px) {.sh-column-07ffafb379b494afcad446e0c8ad05ec .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    .sh-column-cb6eb4f8d91b006323bb7ad27c4295fe {}@media (max-width: 800px) {.sh-column-cb6eb4f8d91b006323bb7ad27c4295fe .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #empty-space-db88dfbd592c0dc4aa537dda486e3d9d {height: 30px;}
    #heading-04700e8e7ef2e565be04c050da4afcc9 {margin: 0px 0px 15px 0px;}#heading-04700e8e7ef2e565be04c050da4afcc9 .sh-heading-content {font-weight: 400!important;color: #888269;}#heading-04700e8e7ef2e565be04c050da4afcc9 .sh-heading-content,#heading-04700e8e7ef2e565be04c050da4afcc9 .sh-heading-additional-text {}
    .sh-column-3bfd853b1de6b8a362ffffa19814ca03 {padding: 0px 35px 0px 35px;background-position: center center;}@media (max-width: 800px) {.sh-column-3bfd853b1de6b8a362ffffa19814ca03 .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #single-image-7d66c912e7a5eabdefa66dc2ba25c711 {text-align: center;}
    .sh-column-19ac2c60fda39cbdb2c96888afe7ae2f {padding: 0px 35px 0px 35px;background-position: center center;}@media (max-width: 800px) {.sh-column-19ac2c60fda39cbdb2c96888afe7ae2f .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #single-image-92d4f683075b45878568c69f4a900eb5 {text-align: center;}
    .sh-column-d71ca7af0525b399af1584dd8119d7a2 {padding: 0px 35px 0px 35px;background-position: center center;}@media (max-width: 800px) {.sh-column-d71ca7af0525b399af1584dd8119d7a2 .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #single-image-d0940ac49cacbb9ac6046704175e1b53 {text-align: center;}
    .sh-column-1da24ad46dae09bb0ac1f86e5f10a664 {padding: 0px 35px 0px 35px;background-position: center center;}@media (max-width: 800px) {.sh-column-1da24ad46dae09bb0ac1f86e5f10a664 .sh-column-wrapper {margin-left: auto;margin-right: auto;}}
    #single-image-997b7417f9ac8f9a63bf035087b1fb19 {text-align: center;}
</style>
<div id="content" class="page-content <?php if (isset($layout_sidebar) && $layout_sidebar) : ?>content-with-<?php echo esc_attr($layout_sidebar);
endif; ?><?php echo esc_attr($class); ?>">
    <div class="fw-row">
    <div class="sh-heading" id="heading-724795aea974582be083b6f12ec9f949">
            <h2 class="sh-heading-content size-default text-left 22222">DATOS DEL CANDIDATO | INSCRIPCIÓN</h2>
        </div>

        <div class="sh-heading" id="heading-724795aea974582be083b6f12ec9f949">
            <h2 class="sh-heading-content size-default text-left">Subir Ficheros</h2>
            <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="upload_attachment[]" class="files" size="50" multiple="multiple" />
            <?php wp_nonce_field( 'upload_attachment', 'my_image_upload_nonce' ); ?>
                <input type="submit" name="subirficheros" value="Subir" />
            </form>
        </div>

        <div id="divider-21007d757d9a460cd741f85ff224756f" class="sh-divider sh-divider-center sh-divider-content-none">
                <div class="sh-divider-line"></div>
        </div>

        <div class="sh-output-html">
            <p><strong>categoria_propuesta: </strong><?php echo $categoria_propuesta;  ?></p>
            <p><strong>tipo_solicitud: </strong><?php echo $tipo_solicitud;  ?></p>
            <p><strong>tipo_concurso: </strong><?php echo $tipo_concurso;  ?></p>
            <p><strong>nombre_comercial_empresa: </strong><?php echo $nombre_comercial_empresa;  ?></p>
            <p><strong>cif: </strong><?php echo $cif;  ?></p>
            <p><strong>email_candidato: </strong><?php echo $email_candidato;  ?></p>
            <p><strong>telefono_candidato: </strong><?php echo $telefono_candidato;  ?></p>
            <p><strong>sector_actividad: </strong><?php echo $sector_actividad;  ?></p>
            <p><strong>provincia_principal: </strong><?php echo $provincia_principal;  ?></p>
            <p><strong>generacion_familiar_actual: </strong><?php echo $generacion_familiar_actual; ?></p>
            <p><strong>linkedin_empresa: </strong><?php echo $linkedin_empresa;  ?></p>
            <p><strong>web_empresa: </strong><?php echo $web_empresa;  ?></p>
            <p><strong>nombre_candidato: </strong><?php echo $nombre_candidato;  ?></p>
            <p><strong>apellidos_candidato: </strong><?php echo $apellidos_candidato;  ?></p>
            <p><strong>cargo_candidato: </strong><?php echo $cargo_candidato;  ?></p>
            <p><strong>apellidos_candidato: </strong><?php echo $apellidos_candidato;  ?></p>
            <p><strong>anio_fundacion: </strong><?php echo $anio_fundacion;  ?></p>
            <p><strong>linkedin_candidato: </strong><?php echo $linkedin_candidato;  ?></p>
            <p><strong>nombre_comercial_empresa: </strong><?php echo $nombre_comercial_empresa;  ?></p>
            <p><strong>sector_actividad_nominador: </strong><?php echo $sector_actividad_nominador;  ?></p>
            <p><strong>provincia_principal_nominador: </strong><?php echo $provincia_principal_nominador;  ?></p>
            <p><strong>motivo_nominacion: </strong><?php echo $motivo_nominacion;  ?></p>
            <p><strong>nombre_persona_contacto: </strong><?php echo $nombre_persona_contacto;  ?></p>
            <p><strong>cargo_persona_contacto: </strong><?php echo $cargo_persona_contacto;  ?></p>
            <p><strong>email_empresa_nominada: </strong><?php echo $email_empresa_nominada;  ?></p>
            <p><strong>telefono_empresa_nominada: </strong><?php echo $telefono_empresa_nominada;  ?></p>
            <p><strong>meritos_liderazgo: </strong><?php echo $meritos_liderazgo;  ?></p>
            <p><strong>meritos_vision: </strong><?php echo $meritos_vision;  ?></p>
            <p><strong>meritos_impacto: </strong><?php echo $meritos_impacto; ?></p>
            <p><strong>porcentaje_ventas: </strong><?php echo $porcentaje_ventas;  ?></p>
            <p><strong>dato_cerrado_ventas_2025: </strong><?php echo $dato_cerrado_ventas_2025;  ?></p>
            <p><strong>porcentaje_ebita: </strong><?php echo $porcentaje_ebita;  ?></p>
            <p><strong>dato_cerrado_ebita_2025: </strong><?php echo $dato_cerrado_ebita_2025;  ?></p>
            <p><strong>tendencia_evolucion_ebita: </strong><?php echo $tendencia_evolucion_ebita;  ?></p>
            <p><strong>porcentaje_empleo_neto: </strong><?php echo $porcentaje_empleo_neto;  ?></p>
            <p><strong>dato_cerrado_empleo_neto_2025: </strong><?php echo $dato_cerrado_empleo_neto_2025;  ?></p>
            <p><strong>nombre_nominador: </strong><?php echo $nombre_nominador;  ?></p>
            <p><strong>apellidos_nominador: </strong><?php echo $apellidos_nominador;  ?></p>
            <p><strong>email_nominador: </strong><?php echo $email_nominador;  ?></p>
            <p><strong>telefono_nominador: </strong><?php echo $telefono_nominador;  ?></p>
            <p><strong>relacion_con_nominado: </strong><?php echo $relacion_con_nominado;  ?></p>
            <p><strong>nombre_nominador: </strong><?php echo $nombre_nominador;  ?></p>
            <p><strong>nombre_apellidos_empresario: </strong><?php echo $nombre_apellidos_empresario;  ?></p>
            <p><strong>dni_nie_empresario: </strong><?php echo $dni_nie_empresario;  ?></p>
            <p><strong>provincia_ccaa_empresario: </strong><?php echo $provincia_ccaa_empresario;  ?></p>
            <p><strong>cargo_empresario: </strong><?php echo $cargo_empresario;  ?></p>
            <p><strong>empresa_empresario: </strong><?php echo $empresa_empresario;  ?></p>
            <p><strong>meritos_liderazgo_empresario: </strong><?php echo $meritos_liderazgo_empresario;  ?></p>
            <p><strong>meritos_vision_empresario: </strong><?php echo $meritos_vision_empresario;  ?></p>
            <p><strong>meritos_impacto_empresario: </strong><?php echo $meritos_impacto_empresario;  ?></p>
            <p><strong>porcentaje_ventas_empresario: </strong><?php echo $porcentaje_ventas_empresario;  ?></p>
            <p><strong>dato_cerrado_ventas_2025_empresario: </strong><?php echo $dato_cerrado_ventas_2025_empresario;  ?></p>
            <p><strong>porcentaje_ebita_empresario: </strong><?php echo $porcentaje_ebita_empresario;  ?></p>
            <p><strong>dato_cerrado_ebita_2025_empresario: </strong><?php echo $dato_cerrado_ebita_2025_empresario;  ?></p>
            <p><strong>porcentaje_empleo_neto_empresario: </strong><?php echo $porcentaje_empleo_neto_empresario;  ?></p>
            <p><strong>dato_cerrado_empleo_neto_2025_empresario: </strong><?php echo $dato_cerrado_empleo_neto_2025_empresario;  ?></p>
            <p><strong>foto_familia: </strong><?php echo $foto_familia;  ?></p>
            <p><strong>logo_empresa: </strong><?php echo $logo_empresa;  ?></p>
            <p><strong>foto_empresario: </strong><?php echo $foto_empresario;  ?></p>

            <?php
                foreach($upload_file_cat1 as $file){
                    echo '
                    <div id="button-a0e4a49cd2a8af9de60d98ff60b3c9b5" class="sh-button-container sh-button-style-1">
                    <a href="https://www.premiosfarmaceuticoscontigoarquia.com'.$file.'" target="_blank" class="sh-button sh-button-medium ">
                    <span class="sh-button-text">VER TRABAJO</span>
                    </a>
                    </div>
                    ';
                }
                ?>

            <p>Url trabajos: <strong><?php echo $work_url; ?></strong></p>


        </div>
    </div>
</div>
<?php
