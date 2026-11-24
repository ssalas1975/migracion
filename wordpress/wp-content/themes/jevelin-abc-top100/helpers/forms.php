<?php


function action_wpcf7_before_send_mail_new($cfdata)
{
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        $formdata = $cfdata->posted_data;
    }

    if($cfdata->title === 'interes_evento'){
        // var_dump($formdata);
        // var_dump($formdata["nombrefarmacia_interes"]);
        // die('gggggg222');
        $data['nombreInteres'] = $formdata["nombrefarmacia_interes"];
        $data['apellidosInteres'] = $formdata["apellidosfarmacia_interes"];
        $data['telefonoInteres'] = $formdata["telefono_interes"];
        $data['emailInteres'] = $formdata["email_interes"];

        $idUsuario = handlerFormInteres($data);

        $senderMails = "";

        if($data['emailInteres'] !== "" || $data['emailInteres']  !== null){
            $senderMails .= ', '. $data['emailInteres'] ;
        }
        
        $senderMails =  trim($senderMails);
        if(substr($senderMails, -1) == ','){
            $senderMails = substr($senderMails, 0, -1);
        }

        $mail = $cfdata->prop('mail');

        $mail["sender"] = "[_site_title] <premiosfarmaceuticoscontigo@arquia.es>";

 
        $mail["recipient"] = $senderMails;
        $cfdata->set_properties(array( 'mail' => $mail ));


        return $cfdata;

    }



    $data = [];
    switch($cfdata->title){
        case 'trayectoria-destacada':
            $data['type'] = 'trayectoria-destacada';
            $data['nombrefarmacia'] = $formdata["nombrefarmacia"];
            $data['apellidos'] = $formdata["apellidosfarmacia"];
            $data['numerocolegiado'] = $formdata["numerocolegiado"];
            $data['numerofarmacia'] = $formdata["numerofarmacia"];
            $data['direccion'] = $formdata["direccion"];
            $data['codigopostal'] = $formdata["codigopostal"];
            $data['provincia'] = $formdata["provincia"];
            $data['poblacion'] = $formdata["poblacion"];
            $data['telefono'] = $formdata["telefono"];
            $data['email'] = $formdata["email"];
            $data['razonprincipal'] = $formdata["razonprincipal"];
            $data['persona_nombre'] = $formdata["persona_nombre"];
            $data['persona_apellidos'] = $formdata["persona_apellidos"];
            $data['direccionpersonal'] = $formdata["direccionpersonal"];
            $data['persona_farmacia'] = $formdata["persona_farmacia"];
            $data['persona_telefono'] = $formdata["persona_telefono"];
            $data['persona_dni'] = $formdata["persona_dni"];
            $data['persona_cif'] = $formdata["persona_cif"];
            $data['persona_email'] = $formdata["persona_email"];
            $data['persona_relacion'] = $formdata["relacioncandidato"];
            $data['aceptobases'] = $formdata["aceptobases"];
            $data['upload_file_cat1'] = $formdata["file-upload"];
            $data['work-url'] = $formdata["work-url"];
            break;
        case 'compromiso-comunitario':
            $data['type'] = 'compromiso-comunitario';
            $data['nombrefarmacia'] = $formdata["nombrefarmacia_cc"];
            $data['apellidos'] = $formdata["apellidosfarmacia_cc"];
            $data['numerocolegiado'] = $formdata["numerocolegiado_cc"];
            $data['numerofarmacia'] = $formdata["numerofarmacia_cc"];
            $data['direccion'] = $formdata["direccion_cc"];
            $data['codigopostal'] = $formdata["codigopostal_cc"];
            $data['provincia'] = $formdata["provincia_cc"];
            $data['poblacion'] = $formdata["poblacion_cc"];
            $data['telefono'] = $formdata["telefono_cc"];
            $data['email'] = $formdata["email_cc"];
            $data['cif'] = $formdata["cif_cc"];
            $data['razonprincipal'] = $formdata["razonprincipal_cc"];
            $data['justificacion'] = $formdata["justificacion_cc"];
            $data['persona_nombre'] = $formdata["persona_nombre_cc"];
            $data['persona_apellidos'] = $formdata["persona_apellidos_cc"];
            $data['persona_farmacia'] = $formdata["persona_farmacia_cc"];
            $data['persona_telefono'] = $formdata["persona_telefono_cc"];
            $data['persona_dni'] = $formdata["persona_dni_cc"];
            $data['persona_cif'] = $formdata["persona_cif_cc"];
            $data['persona_email'] = $formdata["persona_email_cc"];
            $data['persona_relacion'] = $formdata["persona_relacion_cc"];
            $data['aceptobases'] = $formdata["aceptobases_cc"];
            $data['upload_file_cat1'] = $formdata["file-upload_cc"];
            $data['work-url'] = $formdata["work-url_cc"];
            break;
        case 'proyecto-singular':
            $data['type'] = 'proyecto-singular';
            $data['nombrefarmacia'] = $formdata["nombrefarmacia_ps"];
            $data['apellidos'] = $formdata["apellidosfarmacia_ps"];
            $data['numerocolegiado'] = $formdata["numerocolegiado_ps"];
            $data['numerofarmacia'] = $formdata["numerofarmacia_ps"];
            $data['direccion'] = $formdata["direccion_ps"];
            $data['codigopostal'] = $formdata["codigopostal_ps"];
            $data['provincia'] = $formdata["provincia_ps"];
            $data['poblacion'] = $formdata["poblacion_ps"];
            $data['telefono'] = $formdata["telefono_ps"];
            $data['email'] = $formdata["email_ps"];
            $data['razonprincipal'] = $formdata["razonprincipal_ps"];
            $data['persona_nombre'] = $formdata["persona_nombre_ps"];
            $data['persona_apellidos'] = $formdata["persona_apellidos_ps"];
            $data['direccionpersonal'] = $formdata["direccionpersonal_ps"];
            $data['persona_farmacia'] = $formdata["persona_farmacia_ps"];
            $data['persona_telefono'] = $formdata["persona_telefono_ps"];
            $data['persona_dni'] = $formdata["persona_dni_ps"];
            $data['persona_cif'] = $formdata["persona_cif_ps"];
            $data['persona_email'] = $formdata["persona_email_ps"];
            $data['persona_relacion'] = $formdata["persona_relacion_ps"];
            $data['aceptobases'] = $formdata["aceptobases_ps"];
            $data['upload_file_cat1'] = $formdata["file-upload_ps"];
            $data['work-url'] = $formdata["work-url_ps"];
            break;
    }

    $idUsuario = handlerFormOne($data);

    $senderMails = "";
    $mailContent = '';
    // $urlCabecera = str_replace('ms-mhsites.srv.vocento.in/premiodavidgistau', 'www.premiodavidgistau.com/inscripcion', plugin_dir_url( __DIR__));
    $mailContent .= '
            <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Candidatura</title>
</head>
<body style="font-family: NeueHaasDisplayLight; margin: 0; padding: 0; background-color: #f4f4f4;">

    <table class="container" style="width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <tr class="header">
            <figure style="display: flex; justify-content: center;">
                <img style="max-width:600px" src="https://www.premiosfarmaceuticoscontigoarquia.com/wp-content/uploads/sites/25/2024/09/cabecera-email-arquia.png"/>
            </figure>
        </tr>
        <tr>
            <td colspan="2" class="first-parraf" style="padding: 20px 25px;">
                <p>Hola <strong>'.$data["persona_nombre"].'</strong>,</p>
                <p>Gracias por presentar su candidatura a los <strong style="color: #FBBA00;">Premios Farmacéuticos Contigo Arquia.</strong> Los datos de su inscripción son los siguientes:</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th colspan="2" class="box-title" style="font-weight: 100; background-color: #757A7D; color: #fff; padding: 10px; font-size: 15.5px; font-family: Arial; margin: 0; text-align:left">DATOS DE LA CANDIDATURA</th>
                    </tr>
                    <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Categoría:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["type"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nombre:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["nombrefarmacia"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Apellidos:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["apellidos"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Dirección de la farmacia:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["direccion"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nº de colegiado:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["numerocolegiado"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nº de la farmacia:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["numerofarmacia"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Teléfono:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["telefono"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Email:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["email"].'</td>
                                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th colspan="2" class="box-title" style="font-weight: 100; background-color: #757A7D; color: #fff; padding: 10px; font-size: 15.5px; font-family: Arial; margin: 0; text-align:left;">DATOS DE LA PERSONA O ENTIDAD QUE PRESENTA LA CANDIDATURA</th>
                    </tr>
                    <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Nombre:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["persona_nombre"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Apellidos:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["persona_apellidos"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>DNI:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["persona_dni"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Teléfono:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["persona_telefono"].'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><strong>Email:</strong></td>
                                    <td style="padding: 10px; text-align: left;">'.$data["persona_email"].'</td>
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
                    <img src="https://www.premiosfarmaceuticoscontigoarquia.com/wp-content/uploads/sites/25/2024/09/logo-contigoarquia-email.png"/>
                </figure>
                <p>&copy; 2024 Premios Farmacéuticos Contigo Arquia | <a href="mailto:premiosfarmaceuticoscontigo@arquia.es" style="color: #ffffff; text-decoration: none;">premiosfarmaceuticoscontigo@arquia.es</a></p>
            </td>
        </tr>
    </table>

</body>
</html>
    ';


    if($data['persona_email'] !== "" || $data['persona_email']  !== null){
        $senderMails .= ', '. $data['persona_email'] ;
    }
    
    $senderMails =  trim($senderMails);
    if(substr($senderMails, -1) == ','){
        $senderMails = substr($senderMails, 0, -1);
    }

    // var_dump($cfdata->title);
    // var_dump($data['persona_email']);
    // var_dump($mailContent);
    // var_dump($senderMails);
    // die('tttttt');
    
    $mail = $cfdata->prop('mail');
    $mail['body'] = $mailContent;
    $mail["recipient"] = $senderMails;
    $cfdata->set_properties(array( 'mail' => $mail ));

    return $cfdata;

}

add_action('wpcf7_before_send_mail', 'action_wpcf7_before_send_mail_new', 10, 1);


function handlerFormOne($formdata)
{
    $new_post = array(
        'post_title'    => $formdata["numerocolegiado"],
        'post_status'   => 'publish',
        'post_type'     => 'candidato'
    );
    $pid = wp_insert_post($new_post);
    update_post_meta($pid, 'candidato_metadata', $formdata);
    update_post_meta($pid, 'ronda', 0);

    return $pid;

}

function handlerFormInteres($formdata)
{

    $new_post = array(
        'post_title'    => $formdata["nombreInteres"],
        'post_status'   => 'publish',
        'post_type'     => 'candidatointeresado'
    );
    $pid = wp_insert_post($new_post);
    update_post_meta($pid, 'candidato_metadata', $formdata);
    update_post_meta($pid, 'ronda', 0);

    return $pid;

}