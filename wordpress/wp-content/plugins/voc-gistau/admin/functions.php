<?php

if ($_GET['action'] == 'wp_ajax_nopriv_excel_pull_ext') {
    ns_contact_form_excel_pull_ext();
}

/**
 * Declaramos el slug por defecto para la página de candidatos
 */
(get_option('slugCandidatePage') == "" || get_option('slugCandidatePage') == null) ? update_option('slugCandidatePage', 'defaultPageCandidate') : "";


// define the wpcf7_before_send_mail callback
function action_wpcf7_before_send_mail($cfdata, &$abort, $submission)
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

    $args = array(
        'post_type' => 'candidato',
        'meta_key' => 'candidatoDNI',
        'meta_query' => array(
            array(
                'key' => 'candidatoDNI',
                'value' => $formdata["dni"][0],
                'compare' => '=',
            )
        )
    );

    $posts = get_posts($args);
    $pid = 0;

    if (!empty($posts)) {
        $abort = true;
        $errMsg = '';
        unset($formdata['not-sending-data']);
        $errMsg = "<strong>Este autor ya ha sido registrado.</strong> En caso de que tengas que volver a inscribir tu candidatura, ponte en contacto con nosotros a través de info@premiodavidgistau.com. Recuerda que solo se admite un único trabajo por autor.";

        if ($errMsg) {
            $submission->set_status('validation_failed');
            $submission->set_response($cfdata->filter_message($errMsg));
        }
        return $cfdata;
    } else {
        $new_post = array(
            'post_title' => $formdata["TitulodelTrabajo"],
            'post_status' => 'publish',
            'post_type' => 'candidato'
        );
        $pid = wp_insert_post($new_post);
        update_post_meta($pid, 'candidatoDNI', $formdata["dni"][0]);
        update_post_meta($pid, 'ronda', 0);
    }

    // "SubirArchivo" no es una etiqueta CF7 real (es un <input type="file"> plano), así que
    // $submission->uploaded_files() nunca lo recoge y $formdata["SubirArchivo"] tampoco existe
    // (los campos type="file" solo llegan por $_FILES, nunca por $_POST). Leemos directamente
    // de $_FILES, igual que ya hace tiene_archivo_o_url() para la validación.
    if (!empty($_FILES['SubirArchivo']['tmp_name']) && is_uploaded_file($_FILES['SubirArchivo']['tmp_name'])) {
        $originalName = sanitize_file_name($_FILES['SubirArchivo']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $filenameDNIUNO = $formdata["dni"][0] . '-' . time() . ($ext ? '.' . $ext : '');
        $ficherosubida = wp_upload_bits($filenameDNIUNO, null, file_get_contents($_FILES['SubirArchivo']['tmp_name']));
        if (empty($ficherosubida['error'])) {
            $candidatoMeta['fichero'] = $ficherosubida['url']; //url fichero subida
        }
    }



    $candidatoMeta["Categoria"] = $formdata["Categoria"];

    $candidatoMeta["TitulodelTrabajo"] = $formdata["TitulodelTrabajo"];

    $candidatoMeta["GeneroPeriodistico"] = $formdata["GeneroPeriodistico"];

    $candidatoMeta["MedioPublicacion"] = $formdata["MedioPublicacion"];

    $candidatoMeta["FechadePublicacion"] = $formdata["FechadePublicacion"];

    $candidatoMeta["Mensaje"] = $formdata["Mensaje"];

    $candidatoMeta["Nombre"] = $formdata["Nombre"];

    $candidatoMeta["Apellidos"] = $formdata["Apellidos"];

    $candidatoMeta["Direccion"] = $formdata["Direccion"];

    $candidatoMeta["provincia"] = $formdata["provincia"];

    $candidatoMeta["codigopostal"] = $formdata["codigopostal"];

    $candidatoMeta["FechadeNacimiento"] = $formdata["FechadeNacimiento"];

    $candidatoMeta["Telefono"] = $formdata["Telefono"];

    $candidatoMeta["email"] = $formdata["email"];

    $candidatoMeta["NombrePersonaEntidad"] = $formdata["NombrePersonaEntidad"];

    $candidatoMeta["ApellidosPersonaEntidad"] = $formdata["ApellidosPersonaEntidad"];

    $candidatoMeta["EmpresaColectividad"] = $formdata["EmpresaColectividad"];

    $candidatoMeta["TelefonoPersonaEntidad"] = $formdata["TelefonoPersonaEntidad"];

    $candidatoMeta["EmailPersonaEntidad"] = $formdata["EmailPersonaEntidad"];

    $candidatoMeta["url"] = $formdata["url"];

    $candidatoMeta["dni"] = $formdata["dni"];

    // $new_post = array(
    //     'post_title'    => $formdata["TitulodelTrabajo"],
    //     'post_status'   => 'publish',
    //     'post_type'     => 'candidato'
    // );
    // $pid = wp_insert_post($new_post);
    update_post_meta($pid, 'candidato_metadata', $candidatoMeta);
    // update_post_meta($pid, 'ronda', 0);

    $senderMails = "";
    $esNominacionMail = !empty($formdata["EsNominador"]);
    $nombreSaludo = $esNominacionMail ? $formdata["NombrePersonaEntidad"] : $formdata["Nombre"][0];


    $mailContent = '';

    $urlCabecera = str_replace('ms-mhsites.srv.vocento.in/premiodavidgistau', 'www.premiodavidgistau.com/inscripcion', plugin_dir_url(__DIR__));


    $mailContent .= '
    <!DOCTYPE html>
        <html lang="en">

        <head>
        <meta name="from" content="PREMIO DE PERIODISMO DAVID GISTAU" />
        <meta name="subject" content="PREMIO DE PERIODISMO DAVID GISTAU" />
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style>

        </style>
    </head>


        <body style="background-color: #fff;margin:0px">
        <!--[if (gte mso 9)|(IE)]><table border="0"  cellpadding="0" cellspacing="0" width="600" align="center"><tr><td><![endif]-->


        <table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #3a3a3a;"
		align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr border="0" cellpadding="0" cellspacing="0">
                <td><img width="600" style="max-width:600px; width:100%;display:block"
                    src="' . $urlCabecera . 'assets/2022/cabmail.jpg" />
                </td>
            </tr>
            </tbody>
        </table>


        <table width="100%" style="max-width: 600px;background-color: #fff; " align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr border="0" cellpadding="0" cellspacing="0">
                <td align="center" style="padding-right: 3%;padding-left: 3%;">

                <table width="100%" style="max-width: 530px;background-color: #fff; padding-top:30px;padding-bottom:30px;"
                    align="center" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr border="0" cellpadding="0" cellspacing="0">
                            <td align="left"
                            style="font-size: 14px; line-height:19px;font-family: Arial, Helvetica, sans-serif;color:#000000;">
                            Hola <strong>' . $nombreSaludo . '</strong>,
                            </td>
                        </tr>
                        <tr border="0" cellpadding="0" cellspacing="0">
                            <td align="left" style="font-size: 14px; line-height:19px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-top:15px">
                                    Tu candidatura al <strong style="color:#4088D0">Premio de Periodismo David Gistau</strong> se ha registrado correctamente.
                            </td>
                        </tr>
                        <tr border="0" cellpadding="0" cellspacing="0">
                            <td align="left" style="font-size: 14px; line-height:19px;font-family: Arial, Helvetica, sans-serif;color:#000000;padding-top:15px">
                                ¡Gracias por participar!
                            </td>
                        </tr>
                    </tbody>
                </table>

                </td>
            </tr>
            </tbody>
        </table>';

    if (!$esNominacionMail) {
        for ($i = 0; $i < count($formdata["Nombre"]); $i++) {
            if ($i > 0) {
                $senderMails .= ', ' . $formdata["email"][$i];
            } else {
                $senderMails .= $formdata["email"][$i];
            }
        }
    }

    $mailContent .= '

        <table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color:#3A3A3A ;"
            align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr align="center" border="0" cellpadding="0" cellspacing="0">
                <td align="center"><img width="222" style="max-width:222px; width:100%;padding-top:20px;padding-bottom:15px"
                    src="' . $urlCabecera . 'assets/2022/footergistau.png" />
                </td>
            </tr>
            </tbody>
        </table>
        <table width="100%" style="max-width: 600px;background-color: #000; padding-top:10px;padding-bottom:10px;"
            align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr border="0" cellpadding="0" cellspacing="0">
                <td align="center"
                style="font-size: 13px; line-height:20px;font-family: Arial, Helvetica, sans-serif;color:#FFFFFF">
                <a style="text-decoration: none;color:#FFFFFF "
                    href="mailto:info@premiodavidgistau.com">info@premiodavidgistau.com</a>
                </td>
            </tr>

            </tbody>
        </table>
        <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->



        </body>

</html>
    ';

    if ($esNominacionMail) {
        // Si es una nominación, el correo solo va al nominador (no a la persona nominada).
        $senderMails = $formdata["EmailPersonaEntidad"];
    } elseif ($formdata["EmailPersonaEntidad"] !== "" && $formdata["EmailPersonaEntidad"] !== null) {
        $senderMails .= ', ' . $formdata["EmailPersonaEntidad"];
    }

    $senderMails = trim($senderMails);
    if (substr($senderMails, -1) == ',') {
        $senderMails = substr($senderMails, 0, -1);
    }

    // $senderMails = "ssalas@ces.vocento.com";

    $mail = $cfdata->prop('mail');

    $mail['body'] = $mailContent;
    // $mail["sender"] = "Premio David Gistau";
    // $mail["recipient"] = $formdata["email"][0];
    $mail["recipient"] = $senderMails;
    $cfdata->set_properties(array('mail' => $mail));

    return $cfdata;
}
;

// add the action
add_action('wpcf7_before_send_mail', 'action_wpcf7_before_send_mail', 15, 3);

add_filter(
    'wpcf7_recaptcha_verify_response',

    function ($is_human, $response_body) {

        $score = isset($response_body['score']) ? $response_body['score'] : 0;

        $threshold = 0.3;

        $is_human = $threshold < $score;

        return $is_human;

    },

    10,
    2

);

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
        $page_template = GISTAU__PLUGIN_DIR . 'templates/candidate-template.php';
    }
    if (is_page($slugDetalleCandidato)) {
        $page_template = GISTAU__PLUGIN_DIR . 'templates/detalle-candidato.php';
    }
    return $page_template;
}



function your_function()
{
    ?>
    <script>

        setTimeout(function () {
            let submitBtn = document.querySelector('.wpcf7-submit');
            let validadorDatosAutor = false;
            let validadorCandidatura = false;

            let camposdeautorlist = {
                nombreAutor: false,
                apellidosAutor: false,
                direccionAutor: false,
                codigopostalAutor: false,
                provinciaAutor: false,
                paisAutor: false,
                fechanacimientoAutor: false,
                telefonoAutor: false,
                emailAutor: false,
                provinciaAutor: false,
                tituloTrabajo: false,
                GeneroPeriodistico: false,
                MedioPublicacion: false,
                FechadePublicacion: false,
                dniAutor: false,
                selectCategoria: false,
                fechapublicacion: false
            }


            if (submitBtn) {
                /*
                submitBtn.addEventListener('click', function (evt) {
                    // evt.preventDefault(); // Commented out to allow standard CF7 AJAX and PHP filters to run
                    let formulario = document.querySelector('.wpcf7-form');

                    camposAutor('.nombreAutor');
                    camposAutor('.apellidosAutor');
                    camposAutor('.direccionAutor');
                    camposAutor('.codigopostalAutor');
                    camposAutor('.provinciaAutor');
                    camposAutor('.paisAutor');
                    camposAutor('.fechanacimientoAutor');
                    camposAutor('.telefonoAutor');
                    camposAutor('.emailAutor');
                    camposAutor('.provinciaAutor');
                    camposAutor('.tituloTrabajo');
                    camposAutor('.GeneroPeriodistico');
                    camposAutor('.MedioPublicacion');
                    camposAutor('.FechadePublicacion');
                    camposAutor('.dniAutor');
                    camposAutor('.selectCategoria');
                    camposAutor('.fechapublicacion');



                    if (document.querySelector(".subidaFicheroTrabajo").files.length == 0 && (document.querySelector(".urlFicheroTrabajo").value == null || document.querySelector(".urlFicheroTrabajo").value == "")) {
                        document.querySelector(".subidaFicheroTrabajo").style.setProperty("border", "1px solid #e64f4f", "important");
                        document.querySelector(".urlFicheroTrabajo").style.setProperty("border", "1px solid #e64f4f", "important");
                        //mensajeError(true);
                    } else {
                        document.querySelector(".subidaFicheroTrabajo").style.border = "0px solid rgb(255, 0, 0)";
                        document.querySelector(".urlFicheroTrabajo").style.border = "0px solid rgb(255, 0, 0)";
                        // if(validadorDatosAutor)mensajeError(false);
                        validadorCandidatura = true;
                    }
                    let aceptarBases = document.querySelector('.aceptarBases') ? document.querySelector('.aceptarBases').checked : false;


                    if (allTrue(camposdeautorlist)) {
                        validadorDatosAutor = true;
                    }


                    if (aceptarBases && validadorCandidatura === true && validadorDatosAutor === true) {
                        mensajeError(false);
                        setTimeout(function () {
                            if (formulario) {
                                formulario.submit();
                            }
                        }, 3000);
                    }
                }, { passive: false });
                */
            }

            const allTrue = (obj) => {
                for (var o in obj)
                    if (!obj[o]) return false;

                return true;
            }

            // let camposAutor = (data) => {
            // 	let elementoSeleccionado = document.querySelectorAll(data);
            // 	elementoSeleccionado.forEach(element => {
            // 		if(element.value === "" || element.value === null || element.value === 'default'){
            // 			element.style.setProperty("border", "1px solid #e64f4f", "important");
            // 			camposdeautorlist[data.replace('.','')] = false;
            // 			mensajeError(true);
            // 		}else{
            // 			element.style.setProperty("border", "1px solid #e3e3e3", "important");
            //                 camposdeautorlist[data.replace('.','')] = true;
            // 		}
            // 	});
            // };

            let camposAutor = (data) => {
                if (data === '.selectCategoria') {
                    let element = document.querySelector('.selectCategoria');
                    if (element && element.value === 'Selecciona...') {
                        let p = document.querySelector('.SumoSelect')
                        if (p) p.style.setProperty("border", "1px solid #e64f4f", "important");
                        camposdeautorlist[data.replace('.', '')] = false;
                        mensajeError(true);
                    } else {
                        let p = document.querySelector('.SumoSelect')
                        if (p) p.style.setProperty("border", "1px solid #e3e3e3", "important");
                        camposdeautorlist[data.replace('.', '')] = true;
                    }
                } else {
                    let elementoSeleccionado = document.querySelectorAll(data);
                    elementoSeleccionado.forEach(element => {
                        if (element.value === "" || element.value === null || element.value === 'default') {
                            element.style.setProperty("border", "1px solid #e64f4f", "important");
                            camposdeautorlist[data.replace('.', '')] = false;
                            mensajeError(true);
                        } else {
                            element.style.setProperty("border", "1px solid #e3e3e3", "important");
                            camposdeautorlist[data.replace('.', '')] = true;
                        }
                    });
                }

            };


            let mensajeError = (data) => {
                let cajaComentarios = document.querySelector('.wpcf7-response-output');
                let formulario = document.querySelector('.wpcf7-form');

                if (!cajaComentarios) {
                    return;
                }

                if (data === true) {
                    cajaComentarios.innerHTML = "Uno o más campos no se han rellenado correctamente. Por favor, revisa el formulario y envíalo de nuevo.";
                    cajaComentarios.style.display = "block";
                    cajaComentarios.style.borderWidth = "1";
                    cajaComentarios.style.textAlign = "center";
                    cajaComentarios.style.backgroundColor = "#f4f4f4";
                    cajaComentarios.style.borderColor = "red";
                    cajaComentarios.style.color = "#8d8d8d";
                    cajaComentarios.style.padding = "20px";
                    cajaComentarios.style.fontWeight = "bold";
                } else {
                    cajaComentarios.innerHTML = "";
                    cajaComentarios.style.display = "none";
                    return true;
                }

                // check for common mobile user agents
                if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {
                    if (el.offsetParent) {
                        console.log('mobile')
                        window.scrollBy({
                            top: 3400,
                            left: 100,
                            behavior: 'smooth'
                        });
                    }
                } else {
                    console.log('desktop')
                    if (el.offsetParent) {
                        window.scrollBy({
                            top: 1700,
                            left: 100,
                            behavior: 'smooth'
                        });
                    }
                }
            };


        }, 3000);



    </script>
    <?php
}
add_action('wp_footer', 'your_function');

function scrollToBox()
{
    ?>
    <script>
        setTimeout(function () {

            let el = document.querySelector(".wpcf7-response-output");

            // check for common mobile user agents
            if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {
                if (el.offsetParent) {
                    console.log('mobile')
                    window.scrollBy({
                        top: 3400,
                        left: 100,
                        behavior: 'smooth'
                    });
                }
            } else {
                console.log('desktop')
                if (el.offsetParent) {
                    window.scrollBy({
                        top: 1700,
                        left: 100,
                        behavior: 'smooth'
                    });
                }
            }


        }, 3000);
    </script>
    <?php
}
add_action('wp_footer', 'scrollToBox');


/**
 * Acciones en lote para la exportación del fichero excel
 */
add_filter('bulk_actions-edit-candidato', 'exportar_candidatos_seleccionados_actions_bulk');
function exportar_candidatos_seleccionados_actions_bulk($bulk_actions)
{
    $bulk_actions['exportar_candidatos_seleccionados'] = 'Exportar Candidatos Seleccionados';
    return $bulk_actions;
}

function gistau_excel_author_values($data, $key)
{
    if (!isset($data[$key])) {
        return '';
    }

    $values = is_array($data[$key]) ? $data[$key] : array($data[$key]);
    return implode(' | ', array_map('strval', $values));
}


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
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'CATEGORÍA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'TÍTULO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'GÉNERO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'MEDIO PUBLICACIÓN');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'FECHA DE PUBLICACIÓN');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'NOMBRE AUTOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'APELLIDOS AUTOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'DNI');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'TELÉFONO AUTOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'EMAIL AUTOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'NOMBRE PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'APELLIDOS PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'EMPRESA COLECTIVIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'TELÉFONO PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'EMAIL PERSONA O ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'ENLACE FICHERO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'URL TRABAJO');
    $contador = 2;
    while ($loop->have_posts()):
        $loop->the_post();
        $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d'));
        $categoria = is_array($data["Categoria"]) ? $data["Categoria"][0] : $data["Categoria"];
        $enlaceFichero = !empty($data["fichero"]) ? $data["fichero"] : '';
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, $categoria);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, $data["TitulodelTrabajo"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, $data["GeneroPeriodistico"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, $data["MedioPublicacion"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, $data["FechadePublicacion"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, gistau_excel_author_values($data, 'Nombre'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, gistau_excel_author_values($data, 'Apellidos'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, gistau_excel_author_values($data, 'dni'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $contador, gistau_excel_author_values($data, 'Telefono'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $contador, gistau_excel_author_values($data, 'email'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $contador, $data["NombrePersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, $data["ApellidosPersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $contador, $data["EmpresaColectividad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $contador, $data["TelefonoPersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $contador, $data["EmailPersonaEntidad"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $contador, $enlaceFichero);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $contador, $data["url"]);
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
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'CATEGORÍA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'TÍTULO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'GÉNERO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'MEDIO PUBLICACIÓN');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'FECHA DE PUBLICACIÓN');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'NOMBRE AUTOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'APELLIDOS AUTOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'ENLACE FICHERO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'URL TRABAJO');
    $contador = 2;
    while ($loop->have_posts()):
        $loop->the_post();
        $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);
        $categoria = is_array($data["Categoria"]) ? $data["Categoria"][0] : $data["Categoria"];
        $enlaceFichero = !empty($data["fichero"]) ? $data["fichero"] : '';
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, $categoria);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, $data["TitulodelTrabajo"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, $data["GeneroPeriodistico"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, $data["MedioPublicacion"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, $data["FechadePublicacion"]);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, gistau_excel_author_values($data, 'Nombre'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, gistau_excel_author_values($data, 'Apellidos'));
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, $enlaceFichero);
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, $data["url"]);
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

add_filter('wpcf7_autop_or_not', '__return_false');





add_filter('wpcf7_validate', 'validar_mi_input_personalizado', 10, 2);

// NOTA: este formulario está escrito en HTML plano (sin etiquetas CF7 [text*]/[select*]/...),
// así que los hooks 'wpcf7_validate_text'/'_select'/'_url'/'_file' nunca se disparan para él
// (solo se disparan para etiquetas CF7 declaradas de ese tipo). El único hook que sí se ejecuta
// siempre para este formulario es el genérico 'wpcf7_validate' de abajo, así que TODA la
// validación de campos obligatorios vive aquí.

function validar_mi_input_personalizado($result, $tags)
{
    if (!isset($_POST["Categoria"]) || empty(trim($_POST["Categoria"]))) {
        ValidateData("select", "Categoria", $result);
    }

    if (!isset($_POST["TitulodelTrabajo"]) || empty(trim($_POST["TitulodelTrabajo"]))) {
        ValidateData("text", "TitulodelTrabajo", $result);
    }

    if (!isset($_POST["GeneroPeriodistico"]) || empty(trim($_POST["GeneroPeriodistico"]))) {
        ValidateData("text", "GeneroPeriodistico", $result);
    }

    if (!isset($_POST["MedioPublicacion"]) || empty(trim($_POST["MedioPublicacion"]))) {
        ValidateData("text", "MedioPublicacion", $result);
    }

    // FechadePublicacion es opcional según diseño (Figma), no se valida como obligatoria.

    foreach ($_POST['Nombre'] as $nombre) {
        if (empty(trim($nombre))) {
            $result->invalidate(
                array(
                    'name' => 'Nombre',
                    'type' => 'text',
                ),
                'Este campo es obligatorio.'
            );
        }
    }

    foreach ($_POST['Apellidos'] as $Apellidos) {
        if (empty(trim($Apellidos))) {
            $result->invalidate(
                array(
                    'name' => 'Apellidos',
                    'type' => 'text',
                ),
                'Este campo es obligatorio.'
            );
        }
    }


    foreach ($_POST['dni'] as $dni) {
        if (empty(trim($dni))) {
            $result->invalidate(
                array(
                    'name' => 'dni',
                    'type' => 'text',
                ),
                'Este campo es obligatorio.'
            );
        }
    }


    foreach ($_POST['Telefono'] as $Telefono) {
        if (empty(trim($Telefono))) {
            $result->invalidate(
                array(
                    'name' => 'Telefono',
                    'type' => 'tel',
                ),
                'Este campo es obligatorio.'
            );
        }
    }


    foreach ($_POST['email'] as $email) {
        if (empty(trim($email))) {
            $result->invalidate(
                array(
                    'name' => 'email',
                    'type' => 'email',
                ),
                'Este campo es obligatorio.'
            );
        }
    }

    // El paso Nominador solo es obligatorio si el usuario ha elegido "Nominar a un autor" en el switch.
    $esNominador = !empty($_POST["EsNominador"]);
    if ($esNominador) {
        if (!isset($_POST["NombrePersonaEntidad"]) || empty(trim($_POST["NombrePersonaEntidad"]))) {
            ValidateData("text", "NombrePersonaEntidad", $result);
        }
        if (!isset($_POST["ApellidosPersonaEntidad"]) || empty(trim($_POST["ApellidosPersonaEntidad"]))) {
            ValidateData("text", "ApellidosPersonaEntidad", $result);
        }
        if (!isset($_POST["TelefonoPersonaEntidad"]) || empty(trim($_POST["TelefonoPersonaEntidad"]))) {
            ValidateData("text", "TelefonoPersonaEntidad", $result);
        }
        if (!isset($_POST["EmailPersonaEntidad"]) || empty(trim($_POST["EmailPersonaEntidad"]))) {
            ValidateData("text", "EmailPersonaEntidad", $result);
        }
    }

    // Basta con archivo o URL, no hacen falta los dos.
    if (!tiene_archivo_o_url()) {
        ValidateData("url", "url", $result);
        ValidateData("file", "SubirArchivo", $result);
    }

    return $result;
}

function ValidateData($type, $name, $result)
{
    $result->invalidate(
        array(
            'name' => $name,
            'type' => $type,
        ),
        'Este campo es obligatorio.'
    );
    return $result;
}

function tiene_archivo_o_url()
{
    $hasUrl = isset($_POST["url"]) && !empty(trim($_POST["url"]));
    $hasFile = isset($_FILES["SubirArchivo"]) && !empty($_FILES["SubirArchivo"]["type"]);
    return $hasUrl || $hasFile;
}








add_action('wp_enqueue_scripts', function () {

    wp_dequeue_script('cf7-popups-frontend');
    wp_deregister_script('cf7-popups-frontend');

    wp_add_inline_script('sweetalert2', "
        jQuery(document).ready(function ($) {
            $('.wpcf7-response-output').remove();

            function gistauPopup(iconType, title, text, buttonText, onConfirm) {
                var iconHtml = iconType === 'success'
                    ? '<div class=\"gistau-swal-icon gistau-swal-icon--success\">&#10003;</div>'
                    : '<div class=\"gistau-swal-icon gistau-swal-icon--error\">&#10005;</div>';

                swal({
                    html: iconHtml +
                        '<div class=\"gistau-swal-title\">' + title + '</div>' +
                        '<div class=\"gistau-swal-text\">' + text + '</div>',
                    showCloseButton: true,
                    showConfirmButton: true,
                    confirmButtonText: buttonText,
                    customClass: 'gistau-swal-popup'
                }).then(function () {
                    if (typeof onConfirm === 'function') onConfirm();
                }, function (dismiss) {});
            }

            function gistauResponseMessage(event, fallback) {
                if (event && event.detail && event.detail.apiResponse && event.detail.apiResponse.message) {
                    return event.detail.apiResponse.message;
                }
                return fallback;
            }

            document.addEventListener('wpcf7invalid', function (event) {
                var msg = gistauResponseMessage(event, 'Revisa los campos marcados en el formulario e inténtalo de nuevo.');
                gistauPopup('error', '¡Ups! Ocurrió un error', msg, 'Inscribir de nuevo');
            }, false);

            document.addEventListener('wpcf7spam', function (event) {
                var msg = gistauResponseMessage(event, 'No se ha podido enviar el email por posible actividad de spam.');
                gistauPopup('error', '¡Ups! Ocurrió un error', msg, 'Inscribir de nuevo');
            }, false);

            document.addEventListener('wpcf7mailfailed', function (event) {
                var msg = gistauResponseMessage(event, 'No se ha podido enviar el email. Inténtalo de nuevo más tarde.');
                gistauPopup('error', '¡Ups! Ocurrió un error', msg, 'Inscribir de nuevo');
            }, false);

document.addEventListener('wpcf7mailsent', function () {
    gistauPopup(
        'success',
        'Tu candidatura se ha registrado correctamente',
        'Gracias por presentar tu candidatura <br> al Premio de Periodismo David Gistau <br><br> Si tienes cualquier duda, puedes ponerte en contacto con nosotros a través del mail: <a href=\"mailto:contacto@premiodavidgistau.com\">contacto@premiodavidgistau.com</a>',
        'Ir a portada',
        function () {
            window.location.href = '" . esc_url(home_url('/')) . "';
        }
    );
}, false);
        });
    ", 'after');

    // 3) Estilos del popup
    wp_add_inline_style('cf7-popups-frontend', "
        .swal2-modal.gistau-swal-popup {
            border-radius: 0px !important;
            font-family: 'Inter Tight', sans-serif !important;
            padding: 40px 30px 30px !important;
            max-width: 480px !important;
        }
        .gistau-swal-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: #fff;
            font-size: 32px;
            font-weight: bold;
            line-height: 1;
        }
        .gistau-swal-icon--success { background-color: #23A476; }
        .gistau-swal-icon--error { background-color: #C90022; }
        .gistau-swal-title {
            color: #202020;
            font-family: 'Inter Tight', sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 26px;
            margin: 0 0 8px;
        }
        .gistau-swal-text {
            color: #797979;
            font-family: 'Inter Tight', sans-serif;
            font-weight: 400;
            font-size: 14px;
            line-height: 20px;
            max-width: 390px;
            margin: 0 auto 10px;
        }
        .swal2-modal.gistau-swal-popup .swal2-styled {
            background-color: #4088D0 !important;
            font-family: 'Inter Tight', sans-serif !important;
            font-weight: 400 !important;
            font-size: 14px !important;
            border-radius: 2px !important;
            padding: 12px 30px !important;
            margin-top: 20px !important;
            border: none!important;
        }
        .swal2-modal.gistau-swal-popup .swal2-styled:hover {
            background-color: #3574b5 !important;
        }
        .swal2-modal.gistau-swal-popup .swal2-close {
            color: #4088D0 !important;
            font-size: 26px !important;
            top: 12px !important;
            right: 14px !important;
        }
        .swal2-modal.gistau-swal-popup .swal2-close:hover {
            color: #3574b5 !important;
        }
    ", 'after');

}, 20);
