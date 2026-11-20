<?php
 if (jevelin_page_layout() == 'sidebar-right' || jevelin_page_layout() == 'sidebar-left') :
    $layout_sidebar = esc_attr(jevelin_page_layout());
 endif;
$class = '';
if (function_exists('fw_ext_page_builder_is_builder_post') && !fw_ext_page_builder_is_builder_post(get_queried_object_id())) {
    $class = ' page-default-content';
}

function hook_css()
{
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
            #heading-724795aea974582be083b6f12ec9f949 {margin: 0px 0px 15px 0px;}#heading-724795aea974582be083b6f12ec9f949 .sh-heading-content {font-weight: 700!important;color: #e2c900;}#heading-724795aea974582be083b6f12ec9f949 .sh-heading-content,#heading-724795aea974582be083b6f12ec9f949 .sh-heading-additional-text {}
            #divider-21007d757d9a460cd741f85ff224756f {margin: 25px 0px 25px 0px!important;}#divider-21007d757d9a460cd741f85ff224756f.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #d8d6ce;border-top-style: solid;}
            #empty-space-154b59fb5104f19e7acb2f401c93f8ff {height: 20px;}
            #button-a0e4a49cd2a8af9de60d98ff60b3c9b5 .sh-button {background-color: #e2c900;color: #ffffff;}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5 {margin: 0px 0px 25px 0px;}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5:not(.sh-button-style-2) .sh-button:hover {}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5.sh-button-style-2 .sh-button:after {}#button-a0e4a49cd2a8af9de60d98ff60b3c9b5.sh-button-style-2 .sh-button:hover {}
            #divider-aced65c20151be9648400223d7b26874 {margin: 25px 0px 25px 0px!important;}#divider-aced65c20151be9648400223d7b26874.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #d8d6ce;border-top-style: solid;}
            #heading-a9871d588a8ef29f3b30b78f07a90e2e {margin: 0px 0px 15px 0px;}#heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-content {font-weight: 700!important;color: #e2c900;}#heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-content,#heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-additional-text {}
            #divider-461a1c6f6bd592cdc88fa2b6b6f12251 {margin: 25px 0px 25px 0px!important;}#divider-461a1c6f6bd592cdc88fa2b6b6f12251.sh-divider-content-none .sh-divider-line {border-top-width: 1px;border-top-color: #d8d6ce;border-top-style: solid;}
            #empty-space-1faaa15572ffe2865ac8ccc3f2e4681b {height: 30px;}
            #heading-a7650996df19bf4a6a531ae1afe95e9f {margin: 0px 0px 15px 0px;}#heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-content {font-weight: 700!important;color: #e2c900;}#heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-content,#heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-additional-text {}
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

            .header-candidate {
    background-color: #01211B;
    color: white;
    text-align: center;
    padding: 20px;
    position: relative;
    background-image: url('/wp-content/uploads/sites/25/2024/10/imagenes-farmaceuticos-arquia.png');
    background-repeat: no-repeat;
    background-position: 95% center;
    background-size: 311px 340px;
    padding-top: 80px;
    padding-bottom: 80px;
}

.header-candidate h1 {
    font-size: clamp(40px, 4.1vw, 80px);
    color: #ffffff;
    opacity: 1;
    font-family: 'NeueHaasDisplayMedium';
}

.header-candidacy .logo-container {
    display: inline-block;
    position: relative;
    margin-top: 10px;
}

.header-candidate .logo {
    width: 378px;
    height: auto;
}

.category-section {
    background-color: white;
    text-align: center;
    padding: 80px 20px 100px 20px;
}

.category-section h2 {
    font-size: clamp(32px, 3vw, 50px);
    line-height: 60px;
    font-family: 'NeueHaasDisplayMedium';
    text-align: center;
    letter-spacing: 0px;
    color: #FBBA00;
    opacity: 1;
}


#wrapper > .sh-page-layout-default:has(.header-candidate) {
    padding: 0;
}

.detail-section {
    width: 100%;
    max-width: 1780px;
    margin: 0 auto;
}

#heading-724795aea974582be083b6f12ec9f949 .sh-heading-content {
    letter-spacing: 0px;
    color: #000000;
    opacity: 1;
    font-size: clamp(28px, 3vw, 50px);
    line-height: 60px;
    font-family: "NeueHaasDisplayMedium";
    padding-bottom: 70px;
}
.sh-output-html > p {
    font-size: clamp(20px, 2vw, 30px);
    line-height: 40px;
    font-family: 'NeueHaasDisplayLight';
}
.sh-output-html > p > strong {
    font-size: clamp(20px, 2vw, 30px);
    line-height: 40px;
    font-family: 'NeueHaasDisplayMedium';
}

@media (max-width: 1200px) {
    .header-candidate .logo {
        transform: scale(0.8);
    }
    .detail-section{
        padding-left: 35px;
        padding-right: 35px;
    }
    .category-section {
    padding: 80px 20px 50px 20px;
    }

    #heading-724795aea974582be083b6f12ec9f949 .sh-heading-content {
    font-family: "NeueHaasDisplayMedium";
    padding-bottom: 35px;
    }
}
        </style>
    <?php
}
add_action('wp_head', 'hook_css');


get_header();
$id = $_GET['id'];
$candidato = get_post_meta($id, 'candidato_metadata', true);
$enlaceInterno = get_site_url().'/'.get_option('slugDetallCandidato').'?id='.$id;


$type = $candidato['type'];
$nombrefarmacia = $candidato['nombrefarmacia'];
$apellidofarmacia = $candidato['apellidos'];
$numerocolegiado = $candidato['numerocolegiado'];
$numerofarmacia = $candidato['numerofarmacia'];
$direccion = $candidato['direccion'];
$codigopostal = $candidato['codigopostal'];
$provincia = $candidato['provincia'][0];
$poblacion = $candidato['poblacion'];
$telefono = $candidato['telefono'];
$email = $candidato['email'];
$razonprincipal = $candidato['razonprincipal'];
$justificacion = $candidato['justificacion'];
$persona_nombre = $candidato['persona_nombre'];
$persona_apellidos = $candidato['persona_apellidos'];
$persona_dni = $candidato['persona_dni'];
$direccionpersonal = $candidato['direccionpersonal'];
$persona_farmacia = $candidato['persona_farmacia'];
$persona_telefono = $candidato['persona_telefono'];
$persona_email = $candidato['persona_email'];
$upload_file_cat1 = $candidato['upload_file_cat1'];
$cif = $candidato['persona_cif'];
$persona_relacion = $candidato['persona_relacion'];
$work_url = $candidato['work-url'];

?>
<div id="content" class="page-content <?php if (isset($layout_sidebar) && $layout_sidebar) : ?>content-with-<?php echo esc_attr($layout_sidebar);
endif; ?><?php echo esc_attr($class); ?>">
    <div class="fw-row">
    <div class="header-candidate">
                <h1>DATOS DE LA CANDIDATURA</h1>
                <div class="logo-container">
                    <img src="/wp-content/uploads/sites/25/2024/10/logo-arquia-premiosfarmaceuticos.svg" alt="Premios Farmacéuticos Contigo Arquia" class="logo">
                </div>
            </div>

            <div class="category-section">
                <h2>DETALLE DE LA CANDIDATURA</h2>
            </div>

            <div class="detail-section">
        <div class="sh-heading" id="heading-724795aea974582be083b6f12ec9f949">
            <h2 class="sh-heading-content size-default text-left 44444">DATOS DE LA CANDIDATURA</h2>
        </div>

        <div class="sh-output-html">
            <p>Categoría: <strong><?php echo $type; ?></strong></p>
            <p>Nombre Candidato: <strong><?php echo $nombrefarmacia; ?></strong></p>
            <p>Apellidos Candidato: <strong><?php echo $apellidofarmacia; ?></strong></p>
            <p>Número Colegiado: <strong><?php echo $numerocolegiado; ?></strong></p>
            <p>Número Farmacia: <strong><?php echo $numerofarmacia; ?></strong></p>
            <p>Dirección Farmacia: <strong><?php echo $direccion; ?></strong></p>
            <p>Código Postal: <strong><?php echo $codigopostal; ?></strong></p>
            <p>Provincia: <strong><?php echo $provincia; ?></strong></p>
            <p>Población: <strong><?php echo $poblacion; ?></strong></p>
            <p>Teléfono: <strong><?php echo $telefono; ?></strong></p>
            <p>email: <strong><?php echo $email; ?></strong></p>
            <p>Nombre Presentador: <strong><?php echo $persona_nombre; ?></strong></p>
            <p>Apellidos Presentador: <strong><?php echo $persona_apellidos; ?></strong></p>
            <p>DNI Presentador: <strong><?php echo $persona_dni; ?></strong></p>
            <p>Nombre de la entidad que presenta la candidatura: <strong><?php echo $persona_farmacia; ?></strong></p>
            <p>Teléfono: <strong><?php echo $persona_telefono; ?></strong></p>
            <p>email: <strong><?php echo $persona_email; ?></strong></p>
            <p>cif: <strong><?php echo $cif; ?></strong></p>

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
            <p>Relación con la farmacia: <strong><?php echo $persona_relacion; ?></strong></p>
            <p>Razón por la que la candidatura debe ganar : <strong><?php echo ($razonprincipal) ? $razonprincipal : $justificacion; ?></strong></p>

        </div>
        </div>

    </div>
</div>
	<?php if (isset($layout_sidebar) && $layout_sidebar) : ?>
		<div id="sidebar" class="<?php echo esc_attr($layout_sidebar); ?>">
			<?php get_sidebar(); ?>
		</div>
	<?php endif; ?>

<?php get_footer(); ?>