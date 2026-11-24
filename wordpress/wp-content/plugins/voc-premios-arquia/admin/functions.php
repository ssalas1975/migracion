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
        'post_type'  => 'candidato',
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

	 if(!empty($posts)){
		$abort = true;
		$errMsg = '';
		unset($formdata['not-sending-data']);
		$errMsg = "<strong>Este autor ya ha sido registrado.</strong> En caso de que tengas que volver a inscribir tu candidatura, ponte en contacto con nosotros a través de info@premiodavidgistau.com. Recuerda que solo se admite un único trabajo por autor.";

		if( $errMsg ){
			$submission->set_status( 'validation_failed' );
			$submission->set_response( $cfdata->filter_message($errMsg) );
		}
		return $cfdata;
	 }else{
		$new_post = array(
			'post_title'    => $formdata["TitulodelTrabajo"],
			'post_status'   => 'publish',
			'post_type'     => 'candidato'
		);
		$pid = wp_insert_post($new_post);
		update_post_meta($pid, 'candidatoDNI', $formdata["dni"][0]);
		update_post_meta($pid, 'ronda', 0);
	 }

    $uploaded_files = $submission->uploaded_files();
    $re = '/\.(.*)?/m';


    if (!empty($uploaded_files)) {
        // $upload = wp_upload_bits($formdata["SubirArchivo"], null, file_get_contents($uploaded_files["SubirArchivo"]));
        // $candidatoMeta['fichero'] = $upload['url']; //url fichero subida

        preg_match_all($re, $uploaded_files["SubirArchivo"][0], $matches, PREG_SET_ORDER, 0);
        $filenameDNIUNO = $formdata["SubirArchivo"] . $matches[0][0];
        $ficherosubida = wp_upload_bits($filenameDNIUNO, null, file_get_contents($uploaded_files["SubirArchivo"][0]));
        $candidatoMeta['fichero'] = $ficherosubida['url']; //url fichero subida

    }



    $candidatoMeta["Categoria"]=$formdata["Categoria"];

    $candidatoMeta["TitulodelTrabajo"]=$formdata["TitulodelTrabajo"];

    $candidatoMeta["GeneroPeriodistico"]=$formdata["GeneroPeriodistico"];

    $candidatoMeta["MedioPublicacion"]=$formdata["MedioPublicacion"];

    $candidatoMeta["FechadePublicacion"]=$formdata["FechadePublicacion"];

    $candidatoMeta["Mensaje"]=$formdata["Mensaje"];

    $candidatoMeta["Nombre"]=$formdata["Nombre"];

    $candidatoMeta["Apellidos"]=$formdata["Apellidos"];

    $candidatoMeta["Direccion"]=$formdata["Direccion"];

    $candidatoMeta["provincia"]=$formdata["provincia"];

    $candidatoMeta["codigopostal"]=$formdata["codigopostal"];

    $candidatoMeta["FechadeNacimiento"]=$formdata["FechadeNacimiento"];

    $candidatoMeta["Telefono"]=$formdata["Telefono"];

    $candidatoMeta["email"]=$formdata["email"];

    $candidatoMeta["NombrePersonaEntidad"]=$formdata["NombrePersonaEntidad"];

    $candidatoMeta["ApellidosPersonaEntidad"]=$formdata["ApellidosPersonaEntidad"];

    $candidatoMeta["EmpresaColectividad"]=$formdata["EmpresaColectividad"];

    $candidatoMeta["TelefonoPersonaEntidad"]=$formdata["TelefonoPersonaEntidad"];

    $candidatoMeta["EmailPersonaEntidad"]=$formdata["EmailPersonaEntidad"];

    $candidatoMeta["url"]=$formdata["url"];

    $candidatoMeta["dni"]=$formdata["dni"][0];

    // $new_post = array(
    //     'post_title'    => $formdata["TitulodelTrabajo"],
    //     'post_status'   => 'publish',
    //     'post_type'     => 'candidato'
    // );
    // $pid = wp_insert_post($new_post);
    update_post_meta($pid, 'candidato_metadata', $candidatoMeta);
    // update_post_meta($pid, 'ronda', 0);

    $senderMails = "";


    $mailContent = '';

    $urlCabecera = str_replace('ms-mhsites.srv.vocento.in/premiodavidgistau', 'www.premiodavidgistau.com/inscripcion', plugin_dir_url( __DIR__));


    $datePublicacion=date_create($formdata["FechadePublicacion"]);
    $dateFormatPublicacion = date_format($datePublicacion,"d/m/Y");

    $mailContent .= '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Registro de Candidatura</title>
            </head>
            <body style="font-family: "NeueHaasDisplayLight"; margin: 0; padding: 0; background-color: #f4f4f4;">

                <table class="container" style="width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                    <tr class="header" style="background-image: url("./portada-arquia-email.png"); background-size: cover; height: 20vh; text-align: center; color: white;">
                        <td colspan="2">
                            <h1 style="margin: 0; width: 50%; text-transform: uppercase; font-size: 19px; padding-left: 5%;">¡Hemos registrado su candidatura!</h1>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="first-parraf" style="padding: 20px 25px;">
                            <p>Hola <strong>nombre</strong>,</p>
                            <p>Gracias por presentar su candidatura a los <strong style="color: #FBBA00;">Premios Farmacéuticos Contigo Arquia.</strong> Los datos de su inscripción son los siguientes:</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <th colspan="2" class="box-title" style="font-weight: 100; background-color: #757A7D; color: #fff; padding: 10px; font-size: 15.5px; font-family: "Arial"; margin: 0;">DATOS DE LA CANDIDATURA</th>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Categoría:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Categoria"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nombre:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Nombre"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Apellidos:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Apellidos"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Dirección de la farmacia:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Direccion"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nº de colegiado:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Categoria"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nº de la farmacia:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Categoria"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Teléfono:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Categoria"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Email:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$candidatoMeta["Categoria"].'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <th colspan="2" class="box-title" style="font-weight: 100; background-color: #757A7D; color: #fff; padding: 10px; font-size: 15.5px; font-family: "Arial"; margin: 0;">DATOS DE LA PERSONA O ENTIDAD QUE PRESENTA LA CANDIDATURA</th>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nombre:</strong></td>
                                    <td style="padding: 10px; text-align: left;">Leonardo</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Apellidos:</strong></td>
                                    <td style="padding: 10px; text-align: left;">Espinosa Fernández-Caballero</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>DNI:</strong></td>
                                    <td style="padding: 10px; text-align: left;">732527IR</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Teléfono:</strong></td>
                                    <td style="padding: 10px; text-align: left;">661 112 345</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Email:</strong></td>
                                    <td style="padding: 10px; text-align: left;">leonardoefc@gmail.com</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="last-message" style="padding: 20px 25px;">
                        <td colspan="2">
                            <p><strong>Se publicará en esta web el fallo del premio una vez que tenga lugar la reunión del jurado.</strong></p>
                            <p>Atentamente,</p>
                            <p><strong>Premios Contigo Arquia</strong><br>
                                premiosfarmaceuticoscontigo@arquia.es</p>
                        </td>
                    </tr>
                    <tr class="footer" style="background-color: #01211B; color: #ffffff; text-align: center; padding: 10px; font-size: 14px;">
                        <td colspan="2">
                            <figure>
                                <img src="logo-arquia-premiosfarma.svg"/>
                            </figure>
                            <p>&copy; 2024 Premios Farmacéuticos Contigo Arquia | <a href="mailto:premiosfarmaceuticoscontigo@arquia.es" style="color: #ffffff; text-decoration: none;">premiosfarmaceuticoscontigo@arquia.es</a></p>
                        </td>
                    </tr>
                </table>

            </body>
            </html>






    ';

    if($$formdata["EmailPersonaEntidad"] !== "" || $formdata["EmailPersonaEntidad"] !== null){
        $senderMails .= ', '. $formdata["EmailPersonaEntidad"];
    }

    $senderMails =  trim($senderMails);
    if(substr($senderMails, -1) == ','){
        $senderMails = substr($senderMails, 0, -1);
    }

    // $senderMails = "ssalas@ces.vocento.com";
    $mail = $cfdata->prop('mail');
    $mail['body'] = $mailContent;
    // $mail["sender"] = "Premio David Gistau";
    // $mail["recipient"] = $formdata["email"][0];
    $mail["recipient"] = $senderMails;
    $cfdata->set_properties(array( 'mail' => $mail ));

    return $cfdata;
};

// add the action
// add_action('wpcf7_before_send_mail', 'action_wpcf7_before_send_mail', 15, 3);

add_filter( 'wpcf7_recaptcha_verify_response',

        function( $is_human, $response_body ) {

               $score = isset( $response_body['score'] ) ? $response_body['score'] : 0;

               $threshold = 0.3;

               $is_human = $threshold < $score;

               return $is_human;

        },

        10, 2

);

function update_canditado_fase()
{
    // Check for nonce security
    $nonce = sanitize_text_field($_POST['nonce']);
    if (! wp_verify_nonce($nonce, 'my-ajax-nonce')) {
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
    // $slug = get_option('slugCandidatePage');

    $slug_td = get_option('slugCandidatePage_td');
    $slug_cc = get_option('slugCandidatePage_cc');
    $slug_ps = get_option('slugCandidatePage_ps');

    $slugDetalleCandidato = get_option('slugDetallCandidato');
    if (is_page($slug_td)) {
        $page_template = GISTAU__PLUGIN_DIR . 'templates/candidate-template-td.php';
    }
    if (is_page($slug_cc)) {
        $page_template = GISTAU__PLUGIN_DIR . 'templates/candidate-template-cc.php';
    }
    if (is_page($slug_ps)) {
        $page_template = GISTAU__PLUGIN_DIR . 'templates/candidate-template-ps.php';
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
        setTimeout(function(){
            let submitBtn = document.querySelector('.boton-enviar');
				let validadorDatosAutor = false;
				let validadorCandidatura = false;

                let camposdeautorlist = {
                    nombrefarmacia: false,
                    apellidosfarmacia: false,
                    numerocolegiado: false,
                    direccion: false,
                    codigopostal: false,
                    provincia: false,
                    telefono: false,
                    email: false,
                    razonprincipal: false,
                    persona_nombre: false,
                    persona_apellidos: false,
                    persona_telefono: false,
                    persona_dni: false,
                    persona_email: false,
                    // aceptobases: false,
                    relacioncandidato: false,
                }


				submitBtn.addEventListener('click', function(evt){


  		  let form = event.target;
        let isSubmitting = true;

        if($(form).find('input[name=_wpcf7]').val() === '4633')
        {
          var $form = $(form);
          var $button = $form.find('input[type=submit], button[type=submit]').first();
          if (!$button.length) return;

          if (isSubmitting) {
              if (!$button.data('gistau-original-value')) {
                  $button.data('gistau-original-value', $button.val() || $button.text());
              }
              $button.addClass('gistau-submit-loading').prop('disabled', true);
              if ($button.is('input')) {
                  $button.val('Enviando candidatura...');
              } else {
                  $button.text('Enviando candidatura...');
              }
              return;
          }


					evt.preventDefault();
					let formulario = document.querySelector('.wpcf7-form');
					// if(document.querySelector('.tituloTrabajo').value == null || document.querySelector('.tituloTrabajo').value == "")
					// {
					// 	formulario.submit();
					// }
                    camposAutor('.nombrefarmacia');
                    camposAutor('.apellidosfarmacia');
                    camposAutor('.numerocolegiado');
                    camposAutor('.numerofarmacia');
                    camposAutor('.direccion');
                    camposAutor('.codigopostal');
                    camposAutor('.provincia');
                    camposAutor('.telefono');
                    camposAutor('.email');
                    camposAutor('.razonprincipal');
                    camposAutor('.persona_nombre');
                    camposAutor('.persona_apellidos');
                    camposAutor('.direccionpersonal');
                    camposAutor('.poblacion');
                    // camposAutor('.persona_farmacia');
                    camposAutor('.persona_telefono');
                    camposAutor('.persona_dni');
                    camposAutor('.persona_email');
                    camposAutor('.aceptobases');
                    camposAutor('.file-upload');
                    // camposAutor('.work_url');
                    // camposAutor('.persona_cif');
                    camposAutor('.relacioncandidato');




					if( document.querySelector(".file-upload").files.length == 0  ){
						document.querySelector(".file-upload").style.setProperty("border", "1px solid #e64f4f", "important");
						// document.querySelector(".work_url").style.setProperty("border", "1px solid #e64f4f", "important");
						//mensajeError(true);
					}else{
						document.querySelector(".file-upload").style.border = "0px solid rgb(255, 0, 0)";
						// document.querySelector(".work_url").style.border = "0px solid rgb(255, 0, 0)";
						// if(validadorDatosAutor)mensajeError(false);
						validadorCandidatura=true;
					}
					let aceptarBases = document.querySelector('.aceptobases');
					console.log(aceptarBases)
					/*if(!aceptarBases){
						document.querySelector('.aceptobases').style.setProperty("border", "1px solid #e64f4f", "important");
						mensajeError(true);
					}*/


				   if(allTrue(camposdeautorlist)){
				       validadorDatosAutor=true;
				   }


                   console.log(camposdeautorlist);
                   console.log(aceptarBases);
                   console.log(validadorCandidatura);
                   console.log(validadorCandidatura);

					if(aceptarBases && validadorCandidatura === true && validadorDatosAutor === true){
                        mensajeError(false);
                        setTimeout(function(){
                            formulario.submit();
                        },1000);
					}
				},{passive:false});

                const allTrue = (obj) =>
                {
                for(var o in obj)
                    if(!obj[o]) return false;

                return true;
                }


                let camposAutor = (data) => {

                    var ficheros = document.querySelector('.dnd-upload-status');
                    if(ficheros === null){
                        var psFile = document.querySelector('.codedropz-btn-wrap');
                        if(psFile){
                            psFile.style.setProperty("border", "1px solid #e64f4f", "important");
                            mensajeError(true);
                        }
                    }else{
                        var psFile = document.querySelector('.codedropz-btn-wrap');
                        if(psFile){
                            psFile.style.setProperty("border", "1px solid #757A7D", "important");
                        }

                    }


                    var textareaRazonprincipal = document.querySelector('textarea.razonprincipal');
                    if(textareaRazonprincipal){
                        var textareaRazonprincipalValue = document.querySelector('textarea.razonprincipal').value;
                        if(textareaRazonprincipalValue === ""){
                            var textareaRazonprincipal = document.querySelector('textarea.razonprincipal');
                            textareaRazonprincipal.style.setProperty("border", "1px solid #e64f4f", "important");
                            mensajeError(true);
                        }else{
                            var textareaRazonprincipal = document.querySelector('textarea.razonprincipal');
                            textareaRazonprincipal.style.setProperty("border", "1px solid #757A7D", "important");
                            camposdeautorlist['razonprincipal'] = true;
                        }
                    }


                    var textareaRelacioncandidato = document.querySelector('textarea.relacioncandidato');
                    if(textareaRelacioncandidato){
                        var textareaRelacioncandidatoValue = document.querySelector('textarea.relacioncandidato').value;
                        if(textareaRelacioncandidatoValue === ""){
                            var textareaRelacioncandidato = document.querySelector('textarea.relacioncandidato');
                            textareaRelacioncandidato.style.setProperty("border", "1px solid #e64f4f", "important");
                            mensajeError(true);
                        }else{
                            var textareaRelacioncandidato = document.querySelector('textarea.relacioncandidato');
                            textareaRelacioncandidato.style.setProperty("border", "1px solid #757A7D", "important");
                            camposdeautorlist['relacioncandidato'] = true;
                        }
                    }



                    if(data === '.provincia'){
                        let ps = document.querySelector('p.SelectBox');
                        if(ps){
                            const innerHTMLContent = ps.innerHTML;
                            let result = innerHTMLContent.includes("Provincia*");
                            if(result === true){
                                ps.style.setProperty("border", "1px solid #e64f4f", "important");
                                mensajeError(true);
                            }else{
                                ps.style.setProperty("border", "1px solid #757A7D", "important");
                                camposdeautorlist['provincia'] = true;
                            }
                        }

                    }else{
                        //let elementoSeleccionado = document.querySelectorAll(data);
                        let elementoSeleccionado = document.querySelector('input'+data);
                        if(elementoSeleccionado){
                            console.log(elementoSeleccionado)
                             if(elementoSeleccionado.value === "" || elementoSeleccionado.value === null || elementoSeleccionado.value === 'default'){
                                elementoSeleccionado.style.setProperty("border", "1px solid #e64f4f", "important");
                                camposdeautorlist[data.replace('.','')] = false;
                                mensajeError(true);
                            }else{
                                elementoSeleccionado.style.setProperty("border", "1px solid #757A7D", "important");
                                camposdeautorlist[data.replace('.','')] = true;
                            }
                        }
                    }

                };


				let mensajeError = (data) => {
					let cajaComentarios = document.querySelector('.wpcf7-response-output');
					let formulario = document.querySelector('.wpcf7-form');
					if(data === true){
  					if(cajaComentarios){
  						cajaComentarios.innerHTML = "Faltan uno o más campos por completar. Asegúrese de haber leído y aceptado las bases.";
  						cajaComentarios.style.display = "block";
  						cajaComentarios.style.borderWidth = "1";
  						cajaComentarios.style.textAlign = "center";
  						cajaComentarios.style.backgroundColor = "#f4f4f4";
  						cajaComentarios.style.borderColor = "red";
  						cajaComentarios.style.color = "#8d8d8d";
  						cajaComentarios.style.padding = "20px";
  						cajaComentarios.style.fontWeight = "bold";
  					}
					}else{
					if(cajaComentarios){
						cajaComentarios.innerHTML = "";
						cajaComentarios.style.display = "none";
            return true;
					}
					}

					// check for common mobile user agents
                    if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {

                    }else{

                    }
				};
			}, 3000);
		</script>
		<?php
	}
	add_action('wp_footer', 'your_function');

    function scrollToBox(){
        ?>
        <script>
            setTimeout(function(){

                let el = document.querySelector(".wpcf7-response-output");

                // check for common mobile user agents
                if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {
                if(el.offsetParent){
                    console.log('mobile')
                    window.scrollBy({
                        top: 3400,
                        left: 100,
                        behavior: 'smooth'
                        });
                    }
                }else{
                    console.log('desktop')
                    if(el.offsetParent){
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
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'NOMBRE');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'APELLIDOS');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'DIRECCION');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'FECHA DE NACIMIENTO');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'TELEFONO');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'EMAIL');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'NOMBRE PERSONA O ENTIDAD');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'APELLIDOS PERSONA O ENTIDAD');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'EMPRESA COLECTIVIDAD');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'TELEFONO PERSONA O ENTIDAD');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'EMAIL PERSONA O ENTIDAD');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'CATEGORÍA');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'TITULO');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'GENERO');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'MEDIO PUBLICACIÓN');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'FECHA DE PUBLICACIÓN');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'MENSAJE');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1, 'ENLACE FICHERO');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1, 'URL TRABAJO');
    $contador = 2;
    while ($loop->have_posts()) : $loop->the_post();
    $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);



    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'TIPO CANDIDATURA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'NOMBRE CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'APELLIDO CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'NUM COLEGIADO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'NUM FARMACIA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'DIRECCIÓN FARMACIA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'CÓDIGO POSTAL');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'PROVINCIA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'LOCALIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'TELÉFONO CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'EMAIL CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'RAZÓN PRINCIPAL');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'motivos');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'NOMBRE PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'APELLIDOS PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'DIRECCIÓN PERSONAL');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'CIF ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1, 'TELÉFONO PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1, 'DNI PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 1, 'EMAIL PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(21, 1, 'RELACIÓN CON CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(22, 1, 'TRABAJOS');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(23, 1, 'URL TRABAJO');


    $trabajos = "";
    foreach($data['upload_file_cat1'] as $file){
        $trabajos .= 'https://www.premiosfarmaceuticoscontigoarquia.com'.$file."\n";
    }


    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d'));
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, $data['type']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, $data['nombrefarmacia']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, $data['apellidos']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, $data['numerocolegiado']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, $data['numerofarmacia']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, $data['direccion']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, $data['codigopostal']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, $data['provincia'][0]);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $contador, $data['poblacion']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $contador, $data['telefono']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $contador, $data['email']);


    if( $data['type'] == 'compromiso-comunitario'){
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, $data['justificacion']);
    }else{
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, $data['razonprincipal']);
    }

    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, $data['razonprincipal']);
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $contador, $data['justificacion']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $contador, $data['persona_nombre']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $contador, $data['persona_apellidos']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $contador, $data['direccionpersonal']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $contador, $data['persona_farmacia']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $contador, $data['cif']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $contador, $data['persona_telefono']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $contador, $data['persona_dni']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $contador, $data['persona_email']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $contador, $data['persona_relacion']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $contador, $trabajos);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $contador, $data['work-url']);
    $oExcel->getActiveSheet()->getStyle(23)->getAlignment()->setWrapText(true);

    $contador ++;
    endwhile;
    wp_reset_postdata();

    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$nameFile.'_'. date('Y-m-d').'.xls"');
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
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'TIPO CANDIDATURA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'NOMBRE CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'APELLIDO CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'NUM COLEGIADO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'NUM FARMACIA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'DIRECCIÓN FARMACIA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'CÓDIGO POSTAL');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'PROVINCIA');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'LOCALIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'TELÉFONO CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'EMAIL CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'RAZÓN PRINCIPAL');
    // $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'motivos');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'NOMBRE PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'APELLIDOS PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'DIRECCIÓN PERSONAL');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'CIF ENTIDAD');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1, 'TELÉFONO PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1, 'DNI PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 1, 'EMAIL PRESENTADOR');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(21, 1, 'RELACIÓN CON CANDIDATO');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(22, 1, 'TRABAJOS');
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(23, 1, 'URL TRABAJO');
    $contador = 2;
    while ($loop->have_posts()) : $loop->the_post();
    $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);


    var_dump($data['work-url']);
    die();


    $trabajos = "";
    foreach($data['upload_file_cat1'] as $file){
        $trabajos .= 'https://www.premiosfarmaceuticoscontigoarquia.com'.$file;
    }


    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d'));
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, $data['type']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, $data['nombrefarmacia']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, $data['apellidos']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, $data['numerocolegiado']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, $data['numerofarmacia']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, $data['direccion']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, $data['codigopostal']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, $data['provincia'][0]);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $contador, $data['poblacion']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $contador, $data['telefono']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $contador, $data['email']);

    if( $data['type'] == 'compromiso-comunitario'){
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, $data['razonprincipal']);
    }else{
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, $data['justificacion']);
    }


    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $contador, $data['persona_nombre']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $contador, $data['persona_apellidos']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $contador, $data['direccionpersonal']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $contador, $data['persona_farmacia']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $contador, $data['cif']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $contador, $data['persona_telefono']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $contador, $data['persona_dni']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $contador, $data['persona_email']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $contador, $data['persona_relacion']);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $contador, $trabajos);
    $oExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $contador, $data['work-url']);
    $contador ++;
    endwhile;
    wp_reset_postdata();

    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$nameFile.'_'. date('Y-m-d').'.xls"');
    header("Pragma: no-cache");
    header("Expires: 0");
    $objWriter->save('php://output');

    die();
}
add_action('wp_ajax_excel_pull_ext', 'ns_contact_form_excel_pull_ext');
add_action('wp_ajax_nopriv_excel_pull_ext', 'ns_contact_form_excel_pull_ext');



function your_function_2()
	{
		?>
		<script>
setTimeout(function(){
				let submitBtn = document.querySelector('.boton-enviar_cc');
				let validadorDatosAutor = false;
				let validadorCandidatura = false;

                let camposdeautorlist = {
                    nombrefarmacia_cc: false,
                    apellidosfarmacia_cc: false,
                    numerocolegiado_cc: false,
                    numerofarmacia_cc: false,
                    direccion_cc: false,
                    codigopostal_cc: false,
                    provincia_cc: false,
                    telefono_cc: false,
                    email_cc: false,
                    persona_nombre_cc: false,
                    persona_apellidos_cc: false,
                    persona_relacion_cc: false,
                    justificacion_cc: false,
                    persona_telefono_cc: false,
                    persona_dni_cc: false,
                    persona_email_cc: false,
                }


				submitBtn.addEventListener('click', function(evt){




				let form = evt.target;
      let isSubmitting = true;

      if($(form).find('input[name=_wpcf7]').val() === '4633')
      {
        var $form = $(form);
        var $button = $form.find('input[type=submit], button[type=submit]').first();
        if (!$button.length) return;

        if (isSubmitting) {
            if (!$button.data('gistau-original-value')) {
                $button.data('gistau-original-value', $button.val() || $button.text());
            }
            $button.addClass('gistau-submit-loading').prop('disabled', true);
            if ($button.is('input')) {
                $button.val('Enviando candidatura...');
            } else {
                $button.text('Enviando candidatura...');
            }
            return;
        }

					evt.preventDefault();
					let formulario = document.querySelector('.wpcf7-form');
					// if(document.querySelector('.tituloTrabajo').value == null || document.querySelector('.tituloTrabajo').value == "")
					// {
					// 	formulario.submit();
					// }
                    camposAutor('.nombrefarmacia_cc');
                    camposAutor('.apellidosfarmacia_cc');
                    camposAutor('.numerocolegiado_cc');
                    camposAutor('.numerofarmacia_cc');
                    camposAutor('.direccion_cc');
                    camposAutor('.codigopostal_cc');
                    camposAutor('.provincia_cc');
                    camposAutor('.telefono_cc');
                    camposAutor('.email_cc');
                    camposAutor('.poblacion_cc');
                    // camposAutor('.razonprincipal');
                    camposAutor('.persona_nombre_cc');
                    camposAutor('.persona_apellidos_cc');
                    camposAutor('.persona_relacion_cc');
                    camposAutor('.justificacion_cc');
                    // camposAutor('.persona_farmacia');
                    camposAutor('.persona_telefono_cc');
                    camposAutor('.persona_dni_cc');
                    camposAutor('.persona_email_cc');
                    camposAutor('.file-upload_cc');
                    camposAutor('.aceptobases_cc');
                    //camposAutor('.persona_cif_cc');
                    // camposAutor('.relacioncandidato');




					if( document.querySelector(".file-upload_cc").files.length == 0  ){
						document.querySelector(".file-upload_cc").style.setProperty("border", "1px solid #e64f4f", "important");
						// document.querySelector(".work_url").style.setProperty("border", "1px solid #e64f4f", "important");
						//mensajeError(true);
					}else{
						document.querySelector(".file-upload_cc").style.border = "0px solid rgb(255, 0, 0)";
						// document.querySelector(".work_url").style.border = "0px solid rgb(255, 0, 0)";
						// if(validadorDatosAutor)mensajeError(false);
						validadorCandidatura=true;
					}
					let aceptarBases = document.querySelector('.aceptobases_cc');
					console.log(aceptarBases)
					if(!aceptarBases){
						document.querySelector('.aceptobases').style.setProperty("border", "1px solid #e64f4f", "important");
						mensajeError(true);
					}


				   if(allTrue(camposdeautorlist)){
				       validadorDatosAutor=true;
				   }

                   console.log(camposdeautorlist);
                   console.log(validadorDatosAutor);

                   if(aceptarBases && validadorCandidatura === true && validadorDatosAutor === true){
                        mensajeError(false);
                        setTimeout(function(){
                            formulario.submit();
                        },1000);
					}
				},{passive:false});

                const allTrue = (obj) =>
                {
                for(var o in obj)
                    if(!obj[o]) return false;

                return true;
                }


                let camposAutor = (data) => {
                    // if(data === '.selectCategoria'){
                    //     let element = document.querySelector('.selectCategoria');
                    //     if(element.value === 'Selecciona...'){
                    //         let p = document.querySelector('.SumoSelect')
                    //         p.style.setProperty("border", "1px solid #e64f4f", "important");
                    //         camposdeautorlist[data.replace('.','')] = false;
                    //         mensajeError(true);
                    //     }else{
                    //         let p = document.querySelector('.SumoSelect')
                    //         p.style.setProperty("border", "1px solid #757A7D", "important");
                    //         camposdeautorlist[data.replace('.','')] = true;
                    //     }
                    // }else{
                    //     let elementoSeleccionado = document.querySelectorAll(data);
                    //             elementoSeleccionado.forEach(element => {
                    //                 if(element.value === "" || element.value === null || element.value === 'default'){
                    //                     element.style.setProperty("border", "1px solid #e64f4f", "important");
                    //                     camposdeautorlist[data.replace('.','')] = false;
                    //                     mensajeError(true);
                    //                 }else{
                    //                     element.style.setProperty("border", "1px solid #e3e3e3", "important");
                    //                     camposdeautorlist[data.replace('.','')] = true;
                    //                 }
                    //             });
                    // }


                        var ficheros = document.querySelector('.dnd-upload-status');
                        if(ficheros === null){
                            var psFile = document.querySelector('.codedropz-btn-wrap');
                            if(psFile){
                                psFile.style.setProperty("border", "1px solid #e64f4f", "important");
                                mensajeError(true);
                            }
                        }else{
                            var psFile = document.querySelector('.codedropz-btn-wrap');
                            if(psFile){
                                psFile.style.setProperty("border", "1px solid #757A7D", "important");
                            }

                        }


                        var textareaRazonprincipal = document.querySelector('textarea.justificacion_cc');
                        if(textareaRazonprincipal){
                            var textareaRazonprincipalValue = document.querySelector('textarea.justificacion_cc').value;
                            if(textareaRazonprincipalValue === ""){
                                var textareaRazonprincipal = document.querySelector('textarea.justificacion_cc');
                                textareaRazonprincipal.style.setProperty("border", "1px solid #e64f4f", "important");
                                mensajeError(true);
                            }else{
                                var textareaRazonprincipal = document.querySelector('textarea.justificacion_cc');
                                textareaRazonprincipal.style.setProperty("border", "1px solid #757A7D", "important");
                                camposdeautorlist['justificacion_cc'] = true;
                            }
                        }


                        var textareaRelacioncandidato = document.querySelector('textarea.persona_relacion_cc');
                        if(textareaRelacioncandidato){
                            var textareaRelacioncandidatoValue = document.querySelector('textarea.persona_relacion_cc').value;
                            if(textareaRelacioncandidatoValue === ""){
                                var textareaRelacioncandidato = document.querySelector('textarea.persona_relacion_cc');
                                textareaRelacioncandidato.style.setProperty("border", "1px solid #e64f4f", "important");
                                mensajeError(true);
                            }else{
                                var textareaRelacioncandidato = document.querySelector('textarea.persona_relacion_cc');
                                textareaRelacioncandidato.style.setProperty("border", "1px solid #757A7D", "important");
                                camposdeautorlist['persona_relacion_cc'] = true;
                            }
                        }



                        if(data === '.provincia_cc'){
                            let ps = document.querySelector('p.SelectBox');
                            if(ps){
                                const innerHTMLContent = ps.innerHTML;
                                let result = innerHTMLContent.includes("Provincia*");
                                if(result === true){
                                    ps.style.setProperty("border", "1px solid #e64f4f", "important");
                                    mensajeError(true);
                                }else{
                                    ps.style.setProperty("border", "1px solid #757A7D", "important");
                                    camposdeautorlist['provincia_cc'] = true;
                                }
                            }

                        }else{
                            //let elementoSeleccionado = document.querySelectorAll(data);
                            let elementoSeleccionado = document.querySelector('input'+data);
                            if(elementoSeleccionado){
                                console.log(elementoSeleccionado)
                                if(elementoSeleccionado.value === "" || elementoSeleccionado.value === null || elementoSeleccionado.value === 'default'){
                                    elementoSeleccionado.style.setProperty("border", "1px solid #e64f4f", "important");
                                    camposdeautorlist[data.replace('.','')] = false;
                                    mensajeError(true);
                                }else{
                                    elementoSeleccionado.style.setProperty("border", "1px solid #757A7D", "important");
                                    camposdeautorlist[data.replace('.','')] = true;
                                }
                            }
                        }



                };


				let mensajeError = (data) => {
					let cajaComentarios = document.querySelector('.wpcf7-response-output');
					let formulario = document.querySelector('.wpcf7-form');
					if(data === true){
  					if(cajaComentarios){

  						cajaComentarios.innerHTML = "Faltan uno o más campos por completar. Asegúrese de haber leído y aceptado las bases.";
  						cajaComentarios.style.display = "block";
  						cajaComentarios.style.borderWidth = "1";
  						cajaComentarios.style.textAlign = "center";
  						cajaComentarios.style.backgroundColor = "#f4f4f4";
  						cajaComentarios.style.borderColor = "red";
  						cajaComentarios.style.color = "#8d8d8d";
  						cajaComentarios.style.padding = "20px";
  						cajaComentarios.style.fontWeight = "bold";
  					}
					}else{
					if(cajaComentarios){
						cajaComentarios.innerHTML = "";
						cajaComentarios.style.display = "none";
                        return true;
					}
					}

					// check for common mobile user agents
                    if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {

                    }else{

                    }
				};


			}, 3000);



		</script>
		<?php
	}
	add_action('wp_footer', 'your_function_2');





function your_function_3()
{
    ?>
    <script>


setTimeout(function(){
            let submitBtn = document.querySelector('.boton-enviar_ps');
            let validadorDatosAutor = false;
            let validadorCandidatura = false;

            let camposdeautorlist = {
                nombrefarmacia_ps: false,
                apellidosfarmacia_ps: false,
                numerocolegiado_ps: false,
                numerofarmacia_ps: false,
                direccion_ps: false,
                codigopostal_ps: false,
                provincia_ps: false,
                telefono_ps: false,
                email_ps: false,
                persona_nombre_ps: false,
                persona_apellidos_ps: false,
                persona_relacion_ps: false,
                razonprincipal_ps: false,
                persona_telefono_ps: false,
                persona_dni_ps: false,
                persona_email_ps: false,
            }


            submitBtn.addEventListener('click', function(evt){


            let form = event.target;
                  let isSubmitting = true;

                  if($(form).find('input[name=_wpcf7]').val() === '4633')
                  {
                    var $form = $(form);
                    var $button = $form.find('input[type=submit], button[type=submit]').first();
                    if (!$button.length) return;

                    if (isSubmitting) {
                        if (!$button.data('gistau-original-value')) {
                            $button.data('gistau-original-value', $button.val() || $button.text());
                        }
                        $button.addClass('gistau-submit-loading').prop('disabled', true);
                        if ($button.is('input')) {
                            $button.val('Enviando candidatura...');
                        } else {
                            $button.text('Enviando candidatura...');
                        }
                        return;
                    }


                evt.preventDefault();
                let formulario = document.querySelector('.wpcf7-form');
                // if(document.querySelector('.tituloTrabajo').value == null || document.querySelector('.tituloTrabajo').value == "")
                // {
                // 	formulario.submit();
                // }
                camposAutor('.nombrefarmacia_ps');
                camposAutor('.apellidosfarmacia_ps');
                camposAutor('.numerocolegiado_ps');
                camposAutor('.numerofarmacia_ps');
                camposAutor('.direccion_ps');
                camposAutor('.codigopostal_ps');
                camposAutor('.provincia_ps');
                camposAutor('.telefono_ps');
                camposAutor('.email_ps');
                camposAutor('.poblacion_ps');
                // camposAutor('.razonprincipal');
                camposAutor('.persona_nombre_ps');
                camposAutor('.persona_apellidos_ps');
                camposAutor('.persona_relacion_ps');
                camposAutor('.razonprincipal_ps');
                // camposAutor('.persona_farmacia');
                camposAutor('.persona_telefono_ps');
                camposAutor('.persona_dni_ps');
                camposAutor('.persona_email_ps');
                camposAutor('.aceptobases');
                camposAutor('.file-upload_ps');
                camposAutor('.aceptobases_ps');
                // camposAutor('.persona_cif');
                // camposAutor('.relacioncandidato');




                if( document.querySelector(".file-upload_ps").files.length == 0  ){
                    document.querySelector(".file-upload_ps").style.setProperty("border", "1px solid #e64f4f", "important");
                    // document.querySelector(".work_url").style.setProperty("border", "1px solid #e64f4f", "important");
                    //mensajeError(true);
                }else{
                    document.querySelector(".file-upload_ps").style.border = "0px solid rgb(255, 0, 0)";
                    // document.querySelector(".work_url").style.border = "0px solid rgb(255, 0, 0)";
                    // if(validadorDatosAutor)mensajeError(false);
                    validadorCandidatura=true;
                }
                let aceptarBases = document.querySelector('.aceptobases_ps');
                console.log(aceptarBases)
                if(!aceptarBases){
                    document.querySelector('.aceptobases').style.setProperty("border", "1px solid #e64f4f", "important");
                    mensajeError(true);
                }

               if(allTrue(camposdeautorlist)){
				       validadorDatosAutor=true;
				   }

               console.log(camposdeautorlist);
                   console.log(validadorDatosAutor);

                   if(aceptarBases && validadorCandidatura === true && validadorDatosAutor === true){
                        mensajeError(false);
                        setTimeout(function(){
                            formulario.submit();
                        },1000);
					}
				},{passive:false});


            const allTrue = (obj) =>
            {
            for(var o in obj)
                if(!obj[o]) return false;

            return true;
            }


            let camposAutor = (data) => {
                // if(data === '.selectCategoria'){
                //     let element = document.querySelector('.selectCategoria');
                //     if(element.value === 'Selecciona...'){
                //         let p = document.querySelector('.SumoSelect')
                //         p.style.setProperty("border", "1px solid #e64f4f", "important");
                //         camposdeautorlist[data.replace('.','')] = false;
                //         mensajeError(true);
                //     }else{
                //         let p = document.querySelector('.SumoSelect')
                //         p.style.setProperty("border", "1px solid #757A7D", "important");
                //         camposdeautorlist[data.replace('.','')] = true;
                //     }
                // }else{
                //     let elementoSeleccionado = document.querySelectorAll(data);
                //             elementoSeleccionado.forEach(element => {
                //                 if(element.value === "" || element.value === null || element.value === 'default'){
                //                     element.style.setProperty("border", "1px solid #e64f4f", "important");
                //                     camposdeautorlist[data.replace('.','')] = false;
                //                     mensajeError(true);
                //                 }else{
                //                     element.style.setProperty("border", "1px solid #e3e3e3", "important");
                //                     camposdeautorlist[data.replace('.','')] = true;
                //                 }
                //             });
                // }

                var ficheros = document.querySelector('.dnd-upload-status');
                if(ficheros === null){
                    var psFile = document.querySelector('.codedropz-btn-wrap');
                    if(psFile){
                        psFile.style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    }
                }else{
                    var psFile = document.querySelector('.codedropz-btn-wrap');
                    if(psFile){
                        psFile.style.setProperty("border", "1px solid #757A7D", "important");
                    }

                }


                var textareaRazonprincipal = document.querySelector('textarea.razonprincipal_ps');
                if(textareaRazonprincipal){
                    var textareaRazonprincipalValue = document.querySelector('textarea.razonprincipal_ps').value;
                    if(textareaRazonprincipalValue === ""){
                        var textareaRazonprincipal = document.querySelector('textarea.razonprincipal_ps');
                        textareaRazonprincipal.style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    }else{
                        var textareaRazonprincipal = document.querySelector('textarea.razonprincipal_ps');
                        textareaRazonprincipal.style.setProperty("border", "1px solid #757A7D", "important");
                        camposdeautorlist['razonprincipal_ps'] = true;
                    }
                }


                var textareaRelacioncandidato = document.querySelector('textarea.persona_relacion_ps');
                if(textareaRelacioncandidato){
                    var textareaRelacioncandidatoValue = document.querySelector('textarea.persona_relacion_ps').value;
                    if(textareaRelacioncandidatoValue === ""){
                        var textareaRelacioncandidato = document.querySelector('textarea.persona_relacion_ps');
                        textareaRelacioncandidato.style.setProperty("border", "1px solid #e64f4f", "important");
                        mensajeError(true);
                    }else{
                        var textareaRelacioncandidato = document.querySelector('textarea.persona_relacion_ps');
                        textareaRelacioncandidato.style.setProperty("border", "1px solid #757A7D", "important");
                        camposdeautorlist['persona_relacion_ps'] = true;
                    }
                }



                if(data === '.provincia_ps'){
                    let ps = document.querySelector('p.SelectBox');
                    if(ps){
                        const innerHTMLContent = ps.innerHTML;
                        let result = innerHTMLContent.includes("Provincia*");
                        if(result === true){
                            ps.style.setProperty("border", "1px solid #e64f4f", "important");
                            mensajeError(true);
                        }else{
                            ps.style.setProperty("border", "1px solid #757A7D", "important");
                            camposdeautorlist['provincia_ps'] = true;
                        }
                    }

                }else{
                    //let elementoSeleccionado = document.querySelectorAll(data);
                    let elementoSeleccionado = document.querySelector('input'+data);
                    if(elementoSeleccionado){
                        console.log(elementoSeleccionado)
                        if(elementoSeleccionado.value === "" || elementoSeleccionado.value === null || elementoSeleccionado.value === 'default'){
                            elementoSeleccionado.style.setProperty("border", "1px solid #e64f4f", "important");
                            camposdeautorlist[data.replace('.','')] = false;
                            mensajeError(true);
                        }else{
                            elementoSeleccionado.style.setProperty("border", "1px solid #757A7D", "important");
                            camposdeautorlist[data.replace('.','')] = true;
                        }
                    }
                }

            };


            let mensajeError = (data) => {
                let cajaComentarios = document.querySelector('.wpcf7-response-output');
                let formulario = document.querySelector('.wpcf7-form');
                if(data === true){
                    if(cajaComentarios){
                      cajaComentarios.innerHTML = "Faltan uno o más campos por completar. Asegúrese de haber leído y aceptado las bases.";
                      cajaComentarios.style.display = "block";
                      cajaComentarios.style.borderWidth = "1";
                      cajaComentarios.style.textAlign = "center";
                      cajaComentarios.style.backgroundColor = "#f4f4f4";
                      cajaComentarios.style.borderColor = "red";
                      cajaComentarios.style.color = "#8d8d8d";
                      cajaComentarios.style.padding = "20px";
                      cajaComentarios.style.fontWeight = "bold";
                    }
                }else{
                  if(cajaComentarios){
                    cajaComentarios.innerHTML = "";
                    cajaComentarios.style.display = "none";
                    return true;
                  }
                }

                // check for common mobile user agents
                if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {

                }else{

                }
            };


        }, 3000);






    </script>
    <?php
}
add_action('wp_footer', 'your_function_3');


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
                    showConfirmButton: buttonText !== '',
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

            function gistauIsCandidateForm(event) {
                var formId = event && event.detail ? String(event.detail.contactFormId) : '';
                return formId === '4633';
            }

            function gistauIsFamilyForm(form) {
                return \$(form).find('input[name=_wpcf7]').val() === '4633';
            }

            function gistauSetSubmitting(form, isSubmitting) {
                var \$form = \$(form);
                var \$button = \$form.find('input[type=submit], button[type=submit]').first();
                if (!\$button.length) return;

                if (isSubmitting) {
                    if (!\$button.data('gistau-original-value')) {
                        \$button.data('gistau-original-value', \$button.val() || \$button.text());
                    }
                    \$button.addClass('gistau-submit-loading').prop('disabled', true);
                    if (\$button.is('input')) {
                        \$button.val('Enviando candidatura...');
                    } else {
                        \$button.text('Enviando candidatura...');
                    }
                    return;
                }

                var originalValue = \$button.data('gistau-original-value');
                \$button.removeClass('gistau-submit-loading').prop('disabled', false);
                if (originalValue) {
                    if (\$button.is('input')) {
                        \$button.val(originalValue);
                    } else {
                        \$button.text(originalValue);
                    }
                    \$button.removeData('gistau-original-value');
                }
            }

            document.addEventListener('submit', function (event) {
                if (gistauIsFamilyForm(event.target)) {
                    gistauSetSubmitting(event.target, true);
                }
            }, true);

            document.addEventListener('wpcf7invalid', function (event) {
                if (!gistauIsCandidateForm(event)) return;
                gistauSetSubmitting(event.target, false);
                var msg = gistauResponseMessage(event, 'Revisa los campos marcados en el formulario e inténtalo de nuevo.');
                gistauPopup('error', '¡Ups! Ocurrió un error', msg, 'Inscribir de nuevo');
            }, false);

            document.addEventListener('wpcf7spam', function (event) {
                if (!gistauIsCandidateForm(event)) return;
                gistauSetSubmitting(event.target, false);
                var msg = gistauResponseMessage(event, 'No se ha podido enviar el email por posible actividad de spam.');
                gistauPopup('error', '¡Ups! Ocurrió un error', msg, 'Inscribir de nuevo');
            }, false);

            document.addEventListener('wpcf7mailfailed', function (event) {
                if (!gistauIsCandidateForm(event)) return;
                gistauSetSubmitting(event.target, false);
                var msg = gistauResponseMessage(event, 'No se ha podido enviar el email. Inténtalo de nuevo más tarde.');
                gistauPopup('error', '¡Ups! Ocurrió un error', msg, 'Inscribir de nuevo');
            }, false);

            document.addEventListener('wpcf7mailsent', function (event) {
                if (!gistauIsCandidateForm(event)) return;
                gistauSetSubmitting(event.target, false);
                gistauPopup('success', 'Tu candidatura se ha registrado correctamente', 'Gracias por presentar tu candidatura', '');
            }, false);
        });
    ", 'after');

    // 3) Estilos del popup
    wp_add_inline_style('cf7-popups-frontend', "
        .swal2-modal.gistau-swal-popup {
            border-radius: 0px !important;
            font-family: 'Micrograph Regular', sans-serif !important;
            font-weight: 400 !important;
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
            font-family: 'Micrograph Regular', sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 26px;
            margin: 0 0 8px;
        }
        .gistau-swal-text {
            color: #797979;
            font-family: 'Micrograph Regular', sans-serif;
            font-weight: 400;
            font-size: 14px;
            margin: 0 0 10px;
        }
        .swal2-modal.gistau-swal-popup .swal2-styled {
            background-color: #4088D0 !important;
            font-family: 'Micrograph Regular', sans-serif !important;
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
        .wpcf7 form .gistau-submit-loading {
            cursor: wait !important;
            opacity: 0.75;
        }
        .wpcf7 form .gistau-submit-loading::before {
            content: '';
            display: inline-block;
            width: 13px;
            height: 13px;
            margin-right: 8px;
            vertical-align: -2px;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: gistau-submit-spin 0.7s linear infinite;
        }
        @keyframes gistau-submit-spin {
            to { transform: rotate(360deg); }
        }
    ", 'after');

}, 20);
