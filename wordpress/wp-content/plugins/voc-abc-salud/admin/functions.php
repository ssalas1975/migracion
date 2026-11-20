<?php

if ($_GET['action'] == 'wp_ajax_nopriv_excel_pull_ext') {
    ns_contact_form_excel_pull_ext();
}

/**
 * Declaramos el slug por defecto para la página de candidatos
 */
(get_option('slugCandidatePage') == "" || get_option('slugCandidatePage') == null) ? update_option('slugCandidatePage', 'defaultPageCandidate') : "";


// define the wpcf7_before_send_mail callback
function action_wpcf7_before_send_mail($cfdata)
{
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        $formdata = $cfdata->posted_data;
    }

    $candidatoMeta = [];
    $uploaded_files = $submission->uploaded_files();
    $re = '/\.(.*)?/m';

    if (!empty($uploaded_files)) {
        preg_match_all($re, $uploaded_files["dniuno"][0], $matches, PREG_SET_ORDER, 0);
        $filenameDNIUNO = $formdata["dniuno"] . $matches[0][0];
        $dniuno = wp_upload_bits($filenameDNIUNO, null, file_get_contents($uploaded_files["dniuno"][0]));

        preg_match_all($re, $uploaded_files["dnidos"][0], $matches, PREG_SET_ORDER, 0);
        $filenameDNIDOS = $formdata["dnidos"] . $matches[0][0];
        $dnidos = wp_upload_bits($filenameDNIDOS, null, file_get_contents($uploaded_files["dnidos"][0]));

        preg_match_all($re, $uploaded_files["cif"][0], $matches, PREG_SET_ORDER, 0);
        $filenameCIF = $formdata["cif"] . $matches[0][0];
        $cif = wp_upload_bits($filenameCIF, null, file_get_contents($uploaded_files["cif"][0]));

        preg_match_all($re, $uploaded_files["candidatura"][0], $matches, PREG_SET_ORDER, 0);
        $filenameCANDIDATURA = $formdata["candidatura"] . $matches[0][0];
        $candidatura = wp_upload_bits($filenameCANDIDATURA, null, file_get_contents($uploaded_files["candidatura"][0]));

        $candidatoMeta['urldniuno'] = $dniuno['url']; //url fichero subida
        $candidatoMeta['urldnidos'] = $dnidos['url']; //url fichero subida
        $candidatoMeta['urlcif'] = $cif['url']; //url fichero subida
        $candidatoMeta['urlcandidatura'] = $candidatura['url']; //url fichero subida

    }

    $candidatoMeta["Categoria"] = $formdata["Categoria"];

    $candidatoMeta["nombredelacandidatura"] = $formdata["nombredelacandidatura"];

    $candidatoMeta["descripcioncandidatura"] = $formdata["descripcioncandidatura"];

    $candidatoMeta["nombre"] = $formdata["nombre"];

    $candidatoMeta["apellidos"] = $formdata["apellidos"];

    $candidatoMeta["empresaentidad"] = $formdata["empresaentidad"];

    $candidatoMeta["telefono"] = $formdata["telefono"];

    $candidatoMeta["email"] = $formdata["email"];

    // $candidatoMeta["NombrePersonaEntidad"]=$formdata["NombrePersonaEntidad"];

    // $candidatoMeta["ApellidosPersonaEntidad"]=$formdata["ApellidosPersonaEntidad"];

    // $candidatoMeta["EmpresaColectividad"]=$formdata["EmpresaColectividad"];

    // $candidatoMeta["TelefonoPersonaEntidad"]=$formdata["TelefonoPersonaEntidad"];

    // $candidatoMeta["EmailPersonaEntidad"]=$formdata["EmailPersonaEntidad"];

    // $candidatoMeta["url"]=$formdata["url"];


    $new_post = array(
        'post_title'    => $formdata["nombredelacandidatura"],
        'post_status'   => 'publish',
        'post_type'     => 'candidato'
    );
    $pid = wp_insert_post($new_post);
    update_post_meta($pid, 'candidato_metadata', $candidatoMeta);
    update_post_meta($pid, 'ronda', 0);

    $senderMails = "";
    $mailContent = '
    <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta name="from" content="WOMEN NOW &lt;noreply@womennow.es&gt;" />
            <meta name="subject" content="WOMEN NOW" />
            <meta charSet="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <style>

            </style>
        </head>

        <body style="background-color: #fff;margin:0px">
            <!--[if (gte mso 9)|(IE)]><table border="0"  cellpadding="0" cellspacing="0" width="600" align="center"><tr><td><![endif]-->
            

            <table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #00A9AB;"
                align="center" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td><img width="600" style="max-width:600px; width:100%;display:block"
                                src="https://www.premiosabcsalud.com/wp-content/uploads/sites/19/2024/04/cab-mail.jpg" />
                        </td>
                    </tr>
                </tbody>
            </table>

            <table width="100%" style="max-width: 600px;background-color: #00A9AB; padding-top:20px;padding-bottom:20px;"
                align="center" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td align="center"
                            style="font-size: 20px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#fff">
                            INSCRIPCIÓN realizada con ÉXITO
                        </td>
                    </tr>

                </tbody>
            </table>



            <table width="100%" style="max-width: 600px;background-color: #fff; "
                align="center" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td align="center" style="padding-right: 3%;padding-left: 3%;">

                            <table width="100%"	style="max-width: 530px;background-color: #fff; padding-top:25px;padding-bottom:20px;"
                                align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;">
                                            Hola <strong >' . $formdata["nombre"] . '</strong>,
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-top:15px">
                                        ¡Gracias por presentar su candidatura a los   <strong style="color:#00A9AB">Premios ABC SALUD!</strong>
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-top:15px">
                                            Los datos de tu candidatura son los siguientes:
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td align="center" style="padding-right: 3%;padding-left: 3%;">

                            <table width="100%"	style="max-width: 530px;background-color: #00A9AB; padding-top:10px;padding-bottom:10px;"
                                align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 14px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#fff;padding-left:3%;padding-right: 3%;">
                                            DATOS DE LA CANDIDATURA
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td align="center" style="padding-right: 3%;padding-left: 3%;">

                            <table width="100%"	style="max-width: 530px;background-color: #fff; padding-top:25px;padding-bottom:20px;"
                                align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-left: 3%;">
                                            Categoría:  <strong>' . $formdata["Categoria"][0] . '   </strong>
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-left: 3%;">
                                            Nombre de la candidatura: <strong>' . $formdata["nombredelacandidatura"] . '</strong>
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-left: 3%;">
                                            Breve descripción de la candidatura:  <strong>' . $formdata["descripcioncandidatura"] . '</strong>
                                        </td>
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td align="center" style="padding-right: 3%;padding-left: 3%;">

                            <table width="100%"	style="max-width: 530px;background-color: #00A9AB; padding-top:10px;padding-bottom:10px;"
                                align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 14px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#fff;padding-left:3%;padding-right: 3%;">
                                            DATOS DE LA PERSONA O ENTIDAD QUE PRESENTA LA CANDIDATURA
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td align="center" style="padding-right: 3%;padding-left: 3%;">

                            <table width="100%"	style="max-width: 530px;background-color: #fff;padding-bottom:20px;"
                                align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-top:25px;padding-left: 3%;">
                                            Nombre: <strong>' . $formdata["nombre"] . '</strong>
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-left: 3%;">
                                            Apellidos: <strong>' . $formdata["apellidos"] . '</strong> 
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-left: 3%;">
                                            Empresa o entidad: <strong>' . $formdata["empresaentidad"] . '</strong>
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-left: 3%;">
                                            Teléfono: <strong>' . $formdata["telefono"] . '</strong>
                                        </td>
                                    </tr>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-left: 3%;">
                                            Email: <strong>' . $formdata["email"] . '</strong>
                                        </td>
                                    </tr>
                                
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr border="0" cellpadding="0" cellspacing="0">
                        <td align="center" style="padding-right: 3%;padding-left: 3%;">

                            <table width="100%"	style="max-width: 530px;background-color: #fff; padding-top:15px;padding-bottom:40px;"
                                align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr border="0" cellpadding="0" cellspacing="0">
                                        <td align="left" style="font-size: 15px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#000000;">
                                            El Fallo del Jurado y la comunicación de los proyectos ganadores serán publicadas y comunicadas en el Diario ABC, abc.es y en la web oficial de los Premios - <a style="color:#00A9AB;font-weight: bold;" href="http://www.premioabcsalud.com/">premiosabcsalud.com</a>
                                        </td>
                                    </tr>
                                

                                </tbody>
                            </table>
                        </td>
                    </tr>
                
                
                
                </tbody>
            </table>


            <table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color:#000 ;"
                align="center" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr align="center" border="0" cellpadding="0" cellspacing="0">
                        <td align="center"><img width="100%" style="max-width:150px; width:100%;padding-top:20px;padding-bottom:15px"
                                src="https://image.comunicacion.abc.es/lib/fe3a11717564047f7c1573/m/1/d0cd787f-84df-4518-bf1d-06a429e612c4.png" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="max-width: 600px;background-color: #000; padding-top:15px;padding-bottom:15px;border-top: 1px solid #00A9AB"
            align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr border="0" cellpadding="0" cellspacing="0">
                    <td align="center"
                        style="font-size: 13px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#FFFFFF">
                        Contacto: <a style="text-decoration: none;color:#FFF " href="mailto:premiosabcsalud@abc.es">premiosabcsalud@abc.es</a>
                    </td>
                </tr>

            </tbody>
        </table>
            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->



        </body>

        </html>    
    ';

    $senderMails .= $formdata["email"] . ',';
    $mail = $cfdata->prop('mail');
    $mail['body'] = $mailContent;
    // $mail["sender"] = "Premio David Gistau";
    // $mail["recipient"] = $formdata["email"][0];
    $mail["recipient"] = $senderMails;
    $cfdata->set_properties(array('mail' => $mail));

    return $cfdata;
};

// add the action
add_action('wpcf7_before_send_mail', 'action_wpcf7_before_send_mail', 10, 1);



function update_canditado_fase()
{
    // Check for nonce security
    $nonce = sanitize_text_field($_POST['nonce']);
    if (!wp_verify_nonce($nonce, 'my-ajax-nonce')) {
        die('Busted!');
    }
    $idpost = $_POST['id'];
    $fase = $_POST['fase'];


    if ($fase != '5') {
        update_post_meta($idpost, 'ronda', intval($fase));
    } else {
        $args = array(
            'post_type' => 'candidato',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'ronda',
                    'value' => 5,
                ),
            ),
        );
        $post = new WP_Query($args);
        update_post_meta($post->posts[0]->ID, 'ronda', 4);
        update_post_meta($idpost, 'ronda', 5);
    }

    wp_die();
}
add_action('wp_ajax_nopriv_updatefaseuno', 'update_canditado_fase');
add_action('wp_ajax_updatefaseuno', 'update_canditado_fase');

add_filter('page_template', 'candidate_page_template');
function candidate_page_template($page_template)
{
    $slug = get_option('slugCandidatePage');
    $slugDetalleCandidato = get_option('slugDetallCandidato');
    if (is_page($slug)) {
        $page_template = ABCSALUD__PLUGIN_DIR . 'templates/candidate-template.php';
    }
    if (is_page($slugDetalleCandidato)) {
        $page_template = ABCSALUD__PLUGIN_DIR . 'templates/detalle-candidato.php';
    }
    return $page_template;
}



function your_function()
{
?>
    <script>
        setTimeout(function() {
            let submitBtn = document.querySelector('.wpcf7-submit');
            let validadorDatosAutor = false;
            let validadorCandidatura = false;
            let validadorCheck = false;
            submitBtn.addEventListener('click', function(evt) {
                evt.preventDefault();
                let formulario = document.querySelector('.wpcf7-form');
                // if(document.querySelector('.nombredelacandidatura').value == null || document.querySelector('.nombredelacandidatura').value == "")
                // {
                //     // formulario.submit();
                //     console.log('111111')
                // }
                // formulario.submit();

                camposAutor('.tituloTrabajo');
                camposAutor('.contenidoDescripcion');
                camposAutor('.nombreCandidato');
                camposAutor('.apellidosCandidatos');
                camposAutor('.empresaentidadCandidatura');
                camposAutor('.telefonoCandidatura');
                camposAutor('.emailCandidatura');


                let cadidaturaDOC = '';
                if (document.querySelector(".CandidaturaFile").files[0]) {
                    cadidaturaDOC = document.querySelector(".CandidaturaFile").files[0].type;
                }

                // if(cadidaturaDOC.includes('officedocument')){
                //     // document.querySelector(".CandidaturaFile").style.border = "0px solid rgb(255, 0, 0)";
                //     document.querySelector(".CandidaturaFile").style.setProperty("border", "0px solid #e64f4f", "important");
                //     mensajeError(false);
                // }else{
                //     document.querySelector(".CandidaturaFile").style.setProperty("border", "1px solid #e64f4f", "important");
                //     mensajeError(true);
                // }


                let dniUNO = '';
                if (document.querySelector(".dniUnoCandidatura").files[0]) {
                    dniUNO = document.querySelector(".dniUnoCandidatura").files[0].type;
                }
                let dniDOS = '';
                if (document.querySelector(".dniDosCandidatura").files[0]) {
                    dniDOS = document.querySelector(".dniDosCandidatura").files[0].type;
                }
                let cif = '';
                if (document.querySelector(".CifCandidatura").files[0]) {
                    cif = document.querySelector(".CifCandidatura").files[0].type;
                }

                // if(dniUNO.includes('jpeg') || dniUNO.includes('jpg') || dniUNO.includes('png') || dniUNO.includes('pdf')){
                //     document.querySelector(".dniUnoCandidatura").style.border = "0px solid rgb(255, 0, 0)";
                //     mensajeError(false);
                // }else{
                //     document.querySelector(".dniUnoCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                //     mensajeError(true);
                // }
                // if(dniDOS.includes('jpeg') || dniDOS.includes('jpg') || dniDOS.includes('png') || dniDOS.includes('pdf')){
                //     document.querySelector(".dniUnoCandidatura").style.border = "0px solid rgb(255, 0, 0)";
                //     mensajeError(false);
                // }else{
                //     document.querySelector(".dniUnoCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                //     mensajeError(true);
                // }
                // if(cif.includes('jpeg') || cif.includes('jpg') || cif.includes('png') || cif.includes('pdf')){
                //     document.querySelector(".dniUnoCandidatura").style.border = "0px solid rgb(255, 0, 0)";
                //     mensajeError(false);
                // }else{
                //     document.querySelector(".dniUnoCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                //     mensajeError(true);
                // }




                // console.log('SELECTOR', document.querySelector(".selectcategoria").value);
                let elemento = document.querySelector('.selectcategoria');
                if (document.querySelector(".selectcategoria").value === 'Selecciona categoría') {
                    elemento.style.setProperty("border", "1px solid red", "important");
                    mensajeError(true);
                } else {
                    elemento.style.setProperty("border", "0px solid red", "important");
                }

                let validarodniuno = false;
                let validadordnidos = false;
                let validadorcif = false;
                let validadorcandidaturafile = false;
                if (document.querySelector(".dniUnoCandidatura").files.length == 0) {
                    document.querySelector(".dniUnoCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                    mensajeError(true);
                } else {
                    if (dniUNO.includes('jpeg') || dniUNO.includes('jpg') || dniUNO.includes('png') || dniUNO.includes('pdf')) {
                        document.querySelector(".dniUnoCandidatura").style.border = "0px solid rgb(255, 0, 0)";
                        if (validadorDatosAutor) mensajeError(false);
                        validadorCandidatura = true;
                        validarodniuno = true;
                    } else {
                        document.querySelector(".dniUnoCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    }
                }

                if (document.querySelector(".dniDosCandidatura").files.length == 0) {
                    document.querySelector(".dniDosCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                    mensajeError(true);
                } else {
                    if (dniDOS.includes('jpeg') || dniDOS.includes('jpg') || dniDOS.includes('png') || dniDOS.includes('pdf')) {
                        document.querySelector(".dniDosCandidatura").style.border = "0px solid rgb(255, 0, 0)";
                        if (validadorDatosAutor) mensajeError(false);
                        validadorCandidatura = true;
                        validadordnidos = true;
                    } else {
                        document.querySelector(".dniDosCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    }
                }

                if (document.querySelector(".CifCandidatura").files.length == 0) {
                    document.querySelector(".CifCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                    mensajeError(true);
                } else {
                    if (cif.includes('jpeg') || cif.includes('jpg') || cif.includes('png') || cif.includes('pdf')) {
                        document.querySelector(".CifCandidatura").style.border = "0px solid rgb(255, 0, 0)";
                        if (validadorDatosAutor) mensajeError(false);
                        validadorCandidatura = true;
                        validadorcif = true;
                    } else {
                        document.querySelector(".CifCandidatura").style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    }
                }

                if (document.querySelector(".CandidaturaFile").files.length == 0) {
                    document.querySelector(".CandidaturaFile").style.setProperty("border", "1px solid #e64f4f", "important");
                    mensajeError(true);
                } else {
                    if (cadidaturaDOC.includes('officedocument')) {
                        document.querySelector(".CandidaturaFile").style.border = "0px solid rgb(255, 0, 0)";
                        if (validadorDatosAutor) mensajeError(false);
                        validadorCandidatura = true;
                        validadorcandidaturafile = true;
                    } else {
                        document.querySelector(".CandidaturaFile").style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    }
                }



                let aceptarBases = document.querySelector('.aceptarBases').checked;
                if (!aceptarBases) {
                    document.querySelector('.firstlabelcheckbox').style.setProperty("border", "1px solid #e64f4f", "important");
                    mensajeError(true);
                    validadorCheck = false;
                } else {
                    document.querySelector(".firstlabelcheckbox").style.border = "0px solid rgb(255, 0, 0)";
                    validadorCheck = true;
                }

                // let aceptarBases2 = document.querySelector('.aceptarBases2').checked;
                // if(!aceptarBases2){
                //     document.querySelector('.secondlabelcheckbox').style.setProperty("border", "1px solid #e64f4f", "important");
                //     mensajeError(true);
                //     validadorCheck = false;
                // }else{
                //     document.querySelector(".secondlabelcheckbox").style.border = "0px solid rgb(255, 0, 0)";
                //     validadorCheck = true;
                // }

                if (validarodniuno === true && validadordnidos === true && validadorcif === true && validadorcandidaturafile === true) {
                    mensajeError(false);
                } else {
                    mensajeError(true);
                }

                if (aceptarBases && validadorCandidatura === true && validadorDatosAutor === true && validadorCheck === true && validarodniuno === true && validadordnidos === true && validadorcif === true && validadorcandidaturafile === true) {
                    // document.querySelector(".wpcf7-not-valid-tip").style.display = 'block !important';
                    formulario.submit();
                }
            }, {
                passive: false
            });
            let camposAutor = (data) => {
                let elementoSeleccionado = document.querySelectorAll(data);
                elementoSeleccionado.forEach(element => {
                    if (element.value === "" || element.value === null || element.value === 'default') {
                        element.style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    } else {
                        element.style.setProperty("border", "1px solid #e3e3e3", "important");
                        mensajeError(false);
                        validadorDatosAutor = true;
                    }
                });
            };
            let mensajeError = (data) => {
                let cajaComentarios = document.querySelector('.wpcf7-response-output');
                let formulario = document.querySelector('.wpcf7-form');
                if (data === true) {
                    cajaComentarios.innerHTML = "Uno o más campos no se han rellenado correctamente. Por favor, revisa el formulario y envíalo de nuevo.";
                    cajaComentarios.style.display = "block";
                    cajaComentarios.style.border = "1px solid red";
                    cajaComentarios.style.borderWidth = "1px";
                    cajaComentarios.style.borderColor = "red";
                    cajaComentarios.style.textAlign = "center";
                    cajaComentarios.style.backgroundColor = "rgb(244, 244, 244)";
                    cajaComentarios.style.color = "red";
                    cajaComentarios.style.padding = "20px";
                    cajaComentarios.style.fontWeight = "bold";
                } else {
                    cajaComentarios.innerHTML = "";
                    cajaComentarios.style.display = "none";
                }

                window.scrollBy({
                    top: 300,
                    left: 100,
                    behavior: 'smooth'
                });
            };
        }, 3000);
    </script>
<?php
}
add_action('wp_footer', 'your_function');


function detalle_canditado_fase()
{
}

add_action('wp_ajax_nopriv_detallecandidato', 'detalle_canditado_fase');
add_action('wp_ajax_detallecandidato', 'detalle_canditado_fase');




/**
 * Acciones en lote para la exportación del fichero excel
 */
add_filter('bulk_actions-edit-candidato', 'exportar_candidatos_seleccionados_actions_bulk');
function exportar_candidatos_seleccionados_actions_bulk($bulk_actions)
{
    $bulk_actions['exportar_candidatos_seleccionados'] = 'Exportar Candidatos Seleccionados';
    return $bulk_actions;
}


// add_filter('handle_bulk_actions-edit-candidato', 'exportar_candidatos_seleccionados_actions_handler', 10, 3);
// function exportar_candidatos_seleccionados_actions_handler($redirect_to, $doaction, $post_ids)
// {

//   //Solo continúa si son las acciones que hemos creado nosotros
//     if ($doaction !== 'exportar_candidatos_seleccionados') {
//         return $redirect_to;
//     }
//     // Si es la acción primera, realizará estas acciones
//     if ($doaction == 'exportar_candidatos_seleccionados') {
//         /** Include PHPExcel */
//         require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
//         $oExcel = new PHPExcel();
//         // first row
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'NOMBRE');
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'APELLIDOS');
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'CATEGORÍA');
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'TITULO');
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'MEDIO PUBLICACIÓN');
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'ENLACE FICHERO');
//         $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'URL TRABAJO');
//         $contador = 2;
//         foreach ($post_ids as $post_id) {
//             // Aquí lo que quieras que se realice con accion_primera_anadida
//             // var_dump($post_id);
//             $data = get_post_meta($post_id, 'candidato_metadata', true);
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d', $post_id));
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, $data["Nombre"][0]);
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, $data["Apellidos"][0]);
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, $data["Categoria"]);
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, $data["TitulodelTrabajo"]);
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, $data["MedioPublicacion"]);
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, $data['fichero']);
//             $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, $data["url"]);
//             $contador ++;
//         }
//         wp_reset_postdata();

//         $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');

//         header('Content-type: application/vnd.ms-excel');
//         header('Content-Disposition: attachment; filename="Candidatos_'. date('Y-m-d').'.xls"');
//         header("Pragma: no-cache");
//         header("Expires: 0");

//         $objWriter->save('php://output');
//         die();

//         $redirect_to = add_query_arg('accion_primera_anadida', count($post_ids), $redirect_to);
//         return $redirect_to;
//     }
// }


function ns_contact_form_excel_pull()
{
    /** Include PHPExcel */
    require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

    $idEstado = $_GET['the_issue_key'];
    $nameFile = $_GET['the_name'];
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'ronda',
                'value' => intval($idEstado),
            ),
        ),
    );
    $loop = new WP_Query($args);

    $oExcel = new PHPExcel();

    // first row
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'NOMBRE');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'APELLIDOS');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'EMAIL');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'TELEFONO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'NOMBRE PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'CATEGORÍA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'TITULO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'MENSAJE');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'ENLACE FICHERO');
    $contador = 2;
    while ($loop->have_posts()) : $loop->the_post();
        $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, $data["nombre"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, $data["apellidos"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, $data["email"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, $data["telefono"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, $data["empresaentidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, $data["Categoria"][0]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, $data["nombredelacandidatura"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, $data["descripcioncandidatura"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $contador, 'https://www.premiodavidgistau.com' . $data['urlcandidatura']);
        $contador++;
    endwhile;
    wp_reset_postdata();


    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');


    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $nameFile . '_' . date('Y-m-d') . '.xls"');
    header("Pragma: no-cache");
    header("Expires: 0");

    $objWriter->save('php://output');
    wp_die();
}
add_action('wp_ajax_excel_pull', 'ns_contact_form_excel_pull');


function ns_contact_form_excel_pull_ext()
{
    /** Include PHPExcel */
    /** Include PHPExcel */
    require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

    $parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
    require_once($parse_uri[0] . 'wp-load.php');

    $idEstado = $_GET['the_issue_key'];
    $nameFile = $_GET['the_name'];

    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'ronda',
                'value' => intval($idEstado),
            ),
        ),
    );

    $loop = new WP_Query($args);

    $oExcel = new PHPExcel();
    // first row
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'NOMBRE');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'APELLIDOS');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'DIRECCION');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'FECHA DE NACIMIENTO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'TELEFONO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'EMAIL');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'NOMBRE PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'APELLIDOS PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'EMPRESA COLECTIVIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'TELEFONO PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'EMAIL PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'CATEGORÍA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'TITULO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'GENERO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'MEDIO PUBLICACIÓN');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'FECHA DE PUBLICACIÓN');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'MENSAJE');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1, 'ENLACE FICHERO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1, 'URL TRABAJO');
    $contador = 2;
    while ($loop->have_posts()) : $loop->the_post();
        $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);
        var_dump($data);
        die('111111');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, $data["Nombre"][0]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, $data["Apellidos"][0]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, $data["Direccion"][0]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, $data["FechadeNacimiento"][0]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, $data["Telefono"][0]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, $data["email"][0]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, $data["NombrePersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, $data["ApellidosPersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $contador, $data["EmpresaColectividad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $contador, $data["TelefonoPersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $contador, $data["EmailPersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, $data["Categoria"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $contador, $data["TitulodelTrabajo"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $contador, $data["GeneroPeriodistico"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $contador, $data["MedioPublicacion"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $contador, $data["FechadePublicacion"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $contador, $data["Mensaje"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $contador, 'https://www.premiodavidgistau.com' . $data['fichero']);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $contador, $data["url"]);
        $contador++;
    endwhile;
    wp_reset_postdata();

    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $nameFile . '_' . date('Y-m-d') . '.xls"');
    header("Pragma: no-cache");
    header("Expires: 0");
    $objWriter->save('php://output');

    die();
}
add_action('wp_ajax_excel_pull_ext', 'ns_contact_form_excel_pull_ext');
add_action('wp_ajax_nopriv_excel_pull_ext', 'ns_contact_form_excel_pull_ext');
