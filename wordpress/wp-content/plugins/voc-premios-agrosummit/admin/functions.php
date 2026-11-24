<?php

if ($_GET['action'] == 'wp_ajax_nopriv_excel_pull_ext') {
    ns_contact_form_excel_pull_ext();
}

/**
 * Declaramos el slug por defecto para la página de candidatos
 */
(get_option('slugCandidatePage') == "" || get_option('slugCandidatePage') == null) ? update_option('slugCandidatePage', 'defaultPageCandidate') : "";


function directivospymes ($cfdata, &$abort, $submission)
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


  $candidatoMeta["categoria_propuesta"] = "";
  if (isset($candidatoMeta["categoria_propuesta"])) {
      $candidatoMeta["categoria_propuesta"] = $formdata["categoria_propuesta"];
  }

  $candidatoMeta["tipo_solicitud"] = "";
  if (isset($candidatoMeta["tipo_solicitud"])) {
      $candidatoMeta["tipo_solicitud"] = $formdata["tipo_solicitud"];
  }
  $candidatoMeta["nombre_candidato"] = "";
  if (isset($candidatoMeta["nombre_candidato"])) {
      $candidatoMeta["nombre_candidato"] = $formdata["nombre_candidato"];
  }
  $candidatoMeta["apellidos_candidato"] = "";
  if (isset($candidatoMeta["apellidos_candidato"])) {
      $candidatoMeta["apellidos_candidato"] = $formdata["apellidos_candidato"];
  }
  $candidatoMeta["cargo_candidato"] = "";
  if (isset($candidatoMeta["cargo_candidato"])) {
      $candidatoMeta["cargo_candidato"] = $formdata["cargo_candidato"];
  }
  $candidatoMeta["empresa_candidato"] = "";
  if (isset($candidatoMeta["empresa_candidato"])) {
      $candidatoMeta["empresa_candidato"] = $formdata["empresa_candidato"];
  }
  $candidatoMeta["email_candidato"] = "";
  if (isset($candidatoMeta["email_candidato"])) {
      $candidatoMeta["email_candidato"] = $formdata["email_candidato"];
  }
  $candidatoMeta["telefono_candidato"] = "";
  if (isset($candidatoMeta["telefono_candidato"])) {
      $candidatoMeta["telefono_candidato"] = $formdata["telefono_candidato"];
  }
  $candidatoMeta["linkedin_candidato"] = "";
  if (isset($candidatoMeta["linkedin_candidato"])) {
      $candidatoMeta["linkedin_candidato"] = $formdata["linkedin_candidato"];
  }
  $candidatoMeta["web_empresa_candidato"] = "";
  if (isset($candidatoMeta["web_empresa_candidato"])) {
      $candidatoMeta["web_empresa_candidato"] = $formdata["web_empresa_candidato"];
  }
  $candidatoMeta["meritos_liderazgo"] = "";
  if (isset($candidatoMeta["meritos_liderazgo"])) {
      $candidatoMeta["meritos_liderazgo"] = $formdata["meritos_liderazgo"];
  }
  $candidatoMeta["meritos_vision"] = "";
  if (isset($candidatoMeta["meritos_vision"])) {
      $candidatoMeta["meritos_vision"] = $formdata["meritos_vision"];
  }
  $candidatoMeta["meritos_impacto"] = "";
  if (isset($candidatoMeta["meritos_impacto"])) {
      $candidatoMeta["meritos_impacto"] = $formdata["meritos_impacto"];
  }
  $candidatoMeta["inc_facturacion"] = "";
  if (isset($candidatoMeta["inc_facturacion"])) {
      $candidatoMeta["inc_facturacion"] = $formdata["inc_facturacion"];
  }
  $candidatoMeta["inc_resultados"] = "";
  if (isset($candidatoMeta["inc_resultados"])) {
      $candidatoMeta["inc_resultados"] = $formdata["inc_resultados"];
  }
  $candidatoMeta["inc_plantilla"] = "";
  if (isset($candidatoMeta["inc_plantilla"])) {
      $candidatoMeta["inc_plantilla"] = $formdata["inc_plantilla"];
  }
  $candidatoMeta["nombre_nominador"] = "";
  if (isset($candidatoMeta["nombre_nominador"])) {
      $candidatoMeta["nombre_nominador"] = $formdata["nombre_nominador"];
  }
  $candidatoMeta["apellidos_nominador"] = "";
  if (isset($candidatoMeta["apellidos_nominador"])) {
      $candidatoMeta["apellidos_nominador"] = $formdata["apellidos_nominador"];
  }
  $candidatoMeta["cargo_nominador"] = "";
  if (isset($candidatoMeta["cargo_nominador"])) {
      $candidatoMeta["cargo_nominador"] = $formdata["cargo_nominador"];
  }
  $candidatoMeta["empresa_nominador"] = "";
  if (isset($candidatoMeta["empresa_nominador"])) {
      $candidatoMeta["empresa_nominador"] = $formdata["empresa_nominador"];
  }
  $candidatoMeta["email_nominador"] = "";
  if (isset($candidatoMeta["email_nominador"])) {
      $candidatoMeta["email_nominador"] = $formdata["email_nominador"];
  }
  $candidatoMeta["telefono_nominador"] = "";
  if (isset($candidatoMeta["telefono_nominador"])) {
      $candidatoMeta["telefono_nominador"] = $formdata["telefono_nominador"];
  }
  $candidatoMeta["linkedin_nominador"] = "";
  if (isset($candidatoMeta["linkedin_nominador"])) {
      $candidatoMeta["linkedin_nominador"] = $formdata["linkedin_nominador"];
  }
  $candidatoMeta["web_empresa_nominador"] = "";
  if (isset($candidatoMeta["web_empresa_nominador"])) {
      $candidatoMeta["web_empresa_nominador"] = $formdata["web_empresa_nominador"];
  }


  if ($candidatoMeta["tipo_solicitud"] == "1") {
      $name = $candidatoMeta["nombre_nominador"] . " " . $candidatoMeta["apellidos_nominador"];
  } else {
      $name = $candidatoMeta["nombre_candidato"] . " " . $candidatoMeta["apellidos_candidato"];
  }


  $args = array(
      'post_type' => 'candidato',
      'meta_key' => 'candidatoDNI',
      'meta_query' => array(
          array(
              'key' => 'candidatoDNI',
              'value' => $candidatoMeta["email_candidato"],
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
    $errMsg = "<strong>Este candidato ya ha sido registrado.</strong> En caso de que tengas que volver a inscribir tu candidatura, ponte en contacto con nosotros a través de <a href=\"mailto:contacta-top100@abc.es\">contacta-top100@abc.es</a>.";

      if ($errMsg) {
          $submission->set_status('validation_failed');
          $submission->set_response($cfdata->filter_message($errMsg));
      }
      return $cfdata;
  } else {
      $new_post = array(
          'post_title' => $name,
          'post_status' => 'publish',
          'post_type' => 'candidato'
      );
      $pid = wp_insert_post($new_post);
      if ($candidatoMeta["tipo_solicitud"] == "0") {
          update_post_meta($pid, 'candidatoDNI', $candidatoMeta["email_candidato"]);
      }
      update_post_meta($pid, 'tipo_solicitud', $candidatoMeta["tipo_solicitud"]);
      update_post_meta($pid, 'ronda', 0);
  }


  if (isset($_FILES["foto_directivo"]) && $_FILES["foto_directivo"]['error'] === UPLOAD_ERR_OK) {
      $tmp_path = $_FILES["foto_directivo"]['tmp_name'];
      $original_name = $_FILES["foto_directivo"]['name'];
      $ext = pathinfo($original_name, PATHINFO_EXTENSION);
      if (empty($ext))
          $ext = 'jpg';

      $filename = 'foto_directivo_' . $pid . '.' . $ext;
      $ficherosubida = wp_upload_bits($filename, null, file_get_contents($tmp_path));

      if (!$ficherosubida['error']) {
          $candidatoMeta['foto_directivo'] = $ficherosubida['url'];
      }
  }


  if (isset($_FILES["logo_empresa"]) && $_FILES["logo_empresa"]['error'] === UPLOAD_ERR_OK) {
      $tmp_path = $_FILES["logo_empresa"]['tmp_name'];
      $original_name = $_FILES["logo_empresa"]['name'];
      $ext = pathinfo($original_name, PATHINFO_EXTENSION);
      if (empty($ext))
          $ext = 'png';

      $filename = 'logo_empresa_' . $pid . '.' . $ext;
      $ficherosubida = wp_upload_bits($filename, null, file_get_contents($tmp_path));

      if (!$ficherosubida['error']) {
          $candidatoMeta['logo_empresa'] = $ficherosubida['url'];
      }
  }




  update_post_meta($pid, 'candidato_metadata', $candidatoMeta);

  $senderMails = "";


  $mailContent = '';

  $urlCabecera = str_replace('ms-mhsites.srv.vocento.in/premiodavidgistau', 'www.premiodavidgistau.com/inscripcion', plugin_dir_url(__DIR__));


  $datePublicacion = date_create($formdata["FechadePublicacion"]);
  $dateFormatPublicacion = date_format($datePublicacion, "d/m/Y");

  $mailContent .= '
<table style="width: 100%; margin: 0; padding: 0; background-color: #efefef;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="padding: 40px 20px;" align="center">
<table style="width: 600px; max-width: 600px; background-color: #ffffff; border-collapse: collapse;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody><!-- CABECERA -->
<tr>
<td style="padding: 0; background-color: #ffffff;" align="center">
<table style="width: 100%; border-collapse: collapse;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="height: 5px; line-height: 5px; font-size: 0;" height="5"></td>
</tr>
<tr>
<td style="line-height: 0; font-size: 0;" align="center"><a href="#" style="text-decoration:none;"><img style="display: block; width: 100%; max-width: 600px; height: auto; border: 0;" src="https://top.abc.es/wp-content/uploads/sites/37/2026/03/selloabc.svg" alt="ABC Top 100 Directivos PYMES 2025" width="600" /></a></td>
</tr>
</tbody>
</table>
</td>
</tr>
<!-- CONTENIDO -->
<tr>
<td style="padding: 26px 40px 110px 40px; font-family: Arial, Helvetica, sans-serif; color: #003b7a; font-size: 16px; line-height: 22px; text-align: left;" align="center">
<p style="margin: 0 0 20px 0;">¡Hola <strong>' . $name . '</strong><span style="font-weight: normal;">!</span></p>
<p style="margin: 0 0 20px 0;">Tu candidatura a <strong>ABC Top 100 Directivos PYMES 2025</strong> se ha registrado correctamente.</p>
<p style="margin: 0 0 20px 0;">El equipo de la organización revisará la información enviada dentro del proceso de evaluación del ranking.</p>
<p style="margin: 0;">¡Gracias por participar!</p>
</td>
</tr>
<!-- FOOTER SUPERIOR -->
<tr>
<td style="background-color: #0e3a6f; padding: 0 100px;">
<table style="width: 100%; border-collapse: collapse;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="padding: 0;" align="left"><img style="display: block; width: 142px; max-width: 142px; height: auto; border: 0;" src="https://top.abc.es/wp-content/uploads/sites/37/2026/03/logo.svg" alt="ABC Top 100 Directivos PYMES 2025" width="142" /></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="background-color: #7892b2; padding: 0px 100px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 38px; font-weight: bold; color: #ffffff;" align="right"><a style="color: #ffffff; text-decoration: none;" href="mailto:contacta-top@abc.es">contacta-top@abc.es</a></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
  ';



  if ($candidatoMeta["tipo_solicitud"] == "1" && ($candidatoMeta["email_nominador"] != null || $candidatoMeta["email_nominador"] != "")) {
      $senderMails .= $candidatoMeta["email_nominador"];
  } else {
      $senderMails .= $candidatoMeta["email_candidato"];
  }


  $mail = $cfdata->prop('mail');
  $mail['body'] = $mailContent;
  $mail["recipient"] = $senderMails;
  $cfdata->set_properties(array('mail' => $mail));


  $mail2 = $cfdata->prop('mail');
  $mail2['body'] = $mailContent;
  $mail2["recipient"] = $senderMails;
  $cfdata->set_properties(array('mail' => $mail2));

  return $cfdata;
}

function pymesfamilias ($cfdata, &$abort, $submission)
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


  $candidatoMeta["tipo_solicitud"] = "";
  if (isset($candidatoMeta["tipo_solicitud"])) {
      $candidatoMeta["tipo_solicitud"] = $formdata["tipo_solicitud"];
  }
  $candidatoMeta["tipo_concurso"] = "";
  if (isset($candidatoMeta["tipo_concurso"])) {
      $candidatoMeta["tipo_concurso"] = $formdata["tipo_concurso"];
  }
  $candidatoMeta["nombre_comercial_empresa"] = "";
  if (isset($candidatoMeta["nombre_comercial_empresa"])) {
      $candidatoMeta["nombre_comercial_empresa"] = $formdata["nombre_comercial_empresa"];
  }
  $candidatoMeta["cif"] = "";
  if (isset($candidatoMeta["cif"])) {
      $candidatoMeta["cif"] = isset($formdata["cif"]) ? $formdata["cif"] : "";
  }
  $candidatoMeta["email_candidato"] = "";
  if (isset($candidatoMeta["email_candidato"])) {
      $candidatoMeta["email_candidato"] = $formdata["email_candidato"];
  }
  $candidatoMeta["telefono_candidato"] = "";
  if (isset($candidatoMeta["telefono_candidato"])) {
      $candidatoMeta["telefono_candidato"] = $formdata["telefono_candidato"];
  }
  $candidatoMeta["sector_actividad"] = "";
  if (isset($candidatoMeta["sector_actividad"])) {
      $candidatoMeta["sector_actividad"] = $formdata["sector_actividad"];
  }
  $candidatoMeta["provincia_principal"] = "";
  if (isset($candidatoMeta["provincia_principal"])) {
      $candidatoMeta["provincia_principal"] = $formdata["provincia_principal"];
  }
  $candidatoMeta["generacion_familiar_actual"] = "";
  if (isset($candidatoMeta["generacion_familiar_actual"])) {
      $candidatoMeta["generacion_familiar_actual"] = $formdata["generacion_familiar_actual"];
  }
  $candidatoMeta["linkedin_empresa"] = "";
  if (isset($candidatoMeta["linkedin_empresa"])) {
      $candidatoMeta["linkedin_empresa"] = $formdata["linkedin_empresa"];
  }
  $candidatoMeta["web_empresa"] = "";
  if (isset($candidatoMeta["web_empresa"])) {
      $candidatoMeta["web_empresa"] = $formdata["web_empresa"];
  }
  $candidatoMeta["nombre_candidato"] = "";
  if (isset($candidatoMeta["nombre_candidato"])) {
      $candidatoMeta["nombre_candidato"] = $formdata["nombre_candidato"];
  }
  $candidatoMeta["apellidos_candidato"] = "";
  if (isset($candidatoMeta["apellidos_candidato"])) {
      $candidatoMeta["apellidos_candidato"] = $formdata["apellidos_candidato"];
  }
  $candidatoMeta["cargo_candidato"] = "";
  if (isset($candidatoMeta["cargo_candidato"])) {
      $candidatoMeta["cargo_candidato"] = $formdata["cargo_candidato"];
  }
  $candidatoMeta["apellidos_candidato"] = "";
  if (isset($candidatoMeta["apellidos_candidato"])) {
      $candidatoMeta["apellidos_candidato"] = $formdata["apellidos_candidato"];
  }
  $candidatoMeta["anio_fundacion"] = "";
  if (isset($candidatoMeta["anio_fundacion"])) {
      $candidatoMeta["anio_fundacion"] = $formdata["anio_fundacion"];
  }
  $candidatoMeta["linkedin_candidato"] = "";
  if (isset($candidatoMeta["linkedin_candidato"])) {
      $candidatoMeta["linkedin_candidato"] = $formdata["linkedin_candidato"];
  }
  if (isset($candidatoMeta["nombre_comercial_empresa"])) {
      if ($candidatoMeta["tipo_solicitud"] == "1") {
          $nombre_comercial_empresa = isset($formdata["nombre_comercial_empresa_nominada"]) ? $formdata["nombre_comercial_empresa_nominada"] : "";
          $candidatoMeta["nombre_comercial_empresa"] = is_scalar($nombre_comercial_empresa) ? $nombre_comercial_empresa : "";
      }
  }
  $candidatoMeta["sector_actividad_nominador"] = "";
  if (isset($candidatoMeta["sector_actividad_nominador"])) {
      $candidatoMeta["sector_actividad_nominador"] = $formdata["sector_actividad_nominador"];
  }
  $candidatoMeta["provincia_principal_nominador"] = "";
  if (isset($candidatoMeta["provincia_principal_nominador"])) {
      $candidatoMeta["provincia_principal_nominador"] = $formdata["provincia_principal_nominador"];
  }
  $candidatoMeta["motivo_nominacion"] = "";
  if (isset($candidatoMeta["motivo_nominacion"])) {
      $candidatoMeta["motivo_nominacion"] = $formdata["motivo_nominacion"];
  }
  $candidatoMeta["nombre_persona_contacto"] = "";
  if (isset($candidatoMeta["nombre_persona_contacto"])) {
      $candidatoMeta["nombre_persona_contacto"] = $formdata["nombre_persona_contacto"];
  }
  $candidatoMeta["cargo_persona_contacto"] = "";
  if (isset($candidatoMeta["cargo_persona_contacto"])) {
      $candidatoMeta["cargo_persona_contacto"] = $formdata["cargo_persona_contacto"];
  }
  $candidatoMeta["email_empresa_nominada"] = "";
  if (isset($candidatoMeta["email_empresa_nominada"])) {
      $candidatoMeta["email_empresa_nominada"] = $formdata["email_empresa_nominada"];
  }
  $candidatoMeta["telefono_empresa_nominada"] = "";
  if (isset($candidatoMeta["telefono_empresa_nominada"])) {
      $candidatoMeta["telefono_empresa_nominada"] = $formdata["telefono_empresa_nominada"];
  }
  $candidatoMeta["meritos_liderazgo"] = "";
  if (isset($candidatoMeta["meritos_liderazgo"])) {
      $candidatoMeta["meritos_liderazgo"] = $formdata["meritos_liderazgo"];
  }
  $candidatoMeta["meritos_vision"] = "";
  if (isset($candidatoMeta["meritos_vision"])) {
      $candidatoMeta["meritos_vision"] = $formdata["meritos_vision"];
  }
  $candidatoMeta["meritos_impacto"] = "";
  if (isset($candidatoMeta["meritos_impacto"])) {
      $candidatoMeta["meritos_impacto"] = $formdata["meritos_impacto"];
  }
  $candidatoMeta["porcentaje_ventas"] = "";
  if (isset($candidatoMeta["porcentaje_ventas"])) {
      $candidatoMeta["porcentaje_ventas"] = $formdata["porcentaje_ventas"];
  }
  $candidatoMeta["dato_cerrado_ventas_2025"] = "";
  if (isset($candidatoMeta["dato_cerrado_ventas_2025"])) {
      $candidatoMeta["dato_cerrado_ventas_2025"] = $formdata["dato_cerrado_ventas_2025"];
  }
  $candidatoMeta["porcentaje_ebita"] = "";
  if (isset($candidatoMeta["porcentaje_ebita"])) {
      $candidatoMeta["porcentaje_ebita"] = $formdata["porcentaje_ebita"];
  }
  $candidatoMeta["dato_cerrado_ebita_2025"] = "";
  if (isset($candidatoMeta["dato_cerrado_ebita_2025"])) {
      $candidatoMeta["dato_cerrado_ebita_2025"] = $formdata["dato_cerrado_ebita_2025"];
  }
  $candidatoMeta["tendencia_evolucion_ebita"] = "";
  if (isset($candidatoMeta["tendencia_evolucion_ebita"])) {
      $candidatoMeta["tendencia_evolucion_ebita"] = $formdata["tendencia_evolucion_ebita"];
  }
  $candidatoMeta["porcentaje_empleo_neto"] = "";
  if (isset($candidatoMeta["porcentaje_empleo_neto"])) {
      $candidatoMeta["porcentaje_empleo_neto"] = $formdata["porcentaje_empleo_neto"];
  }
  $candidatoMeta["dato_cerrado_empleo_neto_2025"] = "";
  if (isset($candidatoMeta["dato_cerrado_empleo_neto_2025"])) {
      $candidatoMeta["dato_cerrado_empleo_neto_2025"] = $formdata["dato_cerrado_empleo_neto_2025"];
  }
  $candidatoMeta["nombre_nominador"] = "";
  if (isset($candidatoMeta["nombre_nominador"])) {
      $candidatoMeta["nombre_nominador"] = $formdata["nombre_nominador"];
  }
  $candidatoMeta["apellidos_nominador"] = "";
  if (isset($candidatoMeta["apellidos_nominador"])) {
      $candidatoMeta["apellidos_nominador"] = $formdata["apellidos_nominador"];
  }
  $candidatoMeta["email_nominador"] = "";
  if (isset($candidatoMeta["email_nominador"])) {
      $candidatoMeta["email_nominador"] = $formdata["email_nominador"];
  }
  $candidatoMeta["telefono_nominador"] = "";
  if (isset($candidatoMeta["telefono_nominador"])) {
      $candidatoMeta["telefono_nominador"] = $formdata["telefono_nominador"];
  }
  $candidatoMeta["relacion_con_nominado"] = "";
  if (isset($candidatoMeta["relacion_con_nominado"])) {
      $candidatoMeta["relacion_con_nominado"] = $formdata["relacion_con_nominado"];
  }
  $candidatoMeta["politica_privacidad_001_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_001_nominado"])) {
      $candidatoMeta["politica_privacidad_001_nominado"] = $formdata["politica_privacidad_001_nominado"];
  }
  $candidatoMeta["politica_privacidad_002_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_002_nominado"])) {
      $candidatoMeta["politica_privacidad_002_nominado"] = $formdata["politica_privacidad_002_nominado"];
  }
  $candidatoMeta["politica_privacidad_003_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_003_nominado"])) {
      $candidatoMeta["politica_privacidad_003_nominado"] = $formdata["politica_privacidad_003_nominado"];
  }
  $candidatoMeta["politica_privacidad_004_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_004_nominado"])) {
      $candidatoMeta["politica_privacidad_004_nominado"] = $formdata["politica_privacidad_004_nominado"];
  }
  $candidatoMeta["politica_privacidad_005_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_005_nominado"])) {
      $candidatoMeta["politica_privacidad_005_nominado"] = $formdata["politica_privacidad_005_nominado"];
  }
  $candidatoMeta["politica_privacidad_001_nominador"] = "";
  if (isset($candidatoMeta["politica_privacidad_001_nominador"])) {
      $candidatoMeta["politica_privacidad_001_nominador"] = $formdata["politica_privacidad_001_nominador"];
  }
  $candidatoMeta["politica_privacidad_002_nominador"] = "";
  if (isset($candidatoMeta["politica_privacidad_002_nominador"])) {
      $candidatoMeta["politica_privacidad_002_nominador"] = $formdata["politica_privacidad_002_nominador"];
  }
  $candidatoMeta["politica_privacidad_003_nominador"] = "";
  if (isset($candidatoMeta["politica_privacidad_003_nominador"])) {
      $candidatoMeta["politica_privacidad_003_nominador"] = $formdata["politica_privacidad_003_nominador"];
  }

  if ($candidatoMeta["tipo_solicitud"] == "1") {
      $name = $candidatoMeta["nombre_nominador"] . " " . $candidatoMeta["apellidos_nominador"];
  } else {
      $name = $candidatoMeta["nombre_candidato"] . " " . $candidatoMeta["apellidos_candidato"];
  }

  $posts = [];
  if ($candidatoMeta["tipo_solicitud"] == "0") {
      $args = array(
          'post_type' => 'candidato',
          'meta_key' => 'cif',
          'meta_query' => array(
              array(
                  'key' => 'cif',
                  'value' => $candidatoMeta["cif"],
                  'compare' => '=',
              )
          )
      );

      $posts = get_posts($args);
  }
  $pid = 0;

  if (!empty($posts)) {
      $abort = true;
      $errMsg = '';
      unset($formdata['not-sending-data']);
      $errMsg = "<strong>Este candidato ya ha sido registrado.</strong> En caso de que tengas que volver a inscribir tu candidatura, ponte en contacto con nosotros a través de <a href=\"mailto:contacta-top100@abc.es\">contacta-top100@abc.es</a>.";

      if ($errMsg) {
          $submission->set_status('validation_failed');
          $submission->set_response($cfdata->filter_message($errMsg));
      }
      return $cfdata;
  } else {
      $new_post = array(
          'post_title' => $name,
          'post_status' => 'publish',
          'post_type' => 'candidato'
      );
      $pid = wp_insert_post($new_post);
      if ($candidatoMeta["tipo_solicitud"] == "0") {
          update_post_meta($pid, 'cif', $candidatoMeta["cif"]);
      }
      update_post_meta($pid, 'tipo_solicitud', $candidatoMeta["tipo_solicitud"]);
      update_post_meta($pid, 'ronda', 0);
      update_post_meta($pid, "tipo_concurso", $formdata["tipo_concurso"]);
  }


  if (isset($_FILES["foto_familia"]) && $_FILES["foto_familia"]['error'] === UPLOAD_ERR_OK) {
      $tmp_path = $_FILES["foto_familia"]['tmp_name'];
      $original_name = $_FILES["foto_familia"]['name'];
      $ext = pathinfo($original_name, PATHINFO_EXTENSION);
      if (empty($ext))
          $ext = 'jpg';

      $filename = 'foto_familia_' . $pid . '.' . $ext;
      $ficherosubida = wp_upload_bits($filename, null, file_get_contents($tmp_path));

      if (!$ficherosubida['error']) {
          $candidatoMeta['foto_familia'] = $ficherosubida['url'];
      }
  }


  if (isset($_FILES["logo_empresa"]) && $_FILES["logo_empresa"]['error'] === UPLOAD_ERR_OK) {
      $tmp_path = $_FILES["logo_empresa"]['tmp_name'];
      $original_name = $_FILES["logo_empresa"]['name'];
      $ext = pathinfo($original_name, PATHINFO_EXTENSION);
      if (empty($ext))
          $ext = 'png';

      $filename = 'logo_empresa_' . $pid . '.' . $ext;
      $ficherosubida = wp_upload_bits($filename, null, file_get_contents($tmp_path));

      if (!$ficherosubida['error']) {
          $candidatoMeta['logo_empresa'] = $ficherosubida['url'];
      }
  }




  update_post_meta($pid, 'candidato_metadata', $candidatoMeta);

  $senderMails = "";


  $mailContent = '';

  $urlCabecera = str_replace('ms-mhsites.srv.vocento.in/premiodavidgistau', 'www.premiodavidgistau.com/inscripcion', plugin_dir_url(__DIR__));


  $datePublicacion = date_create($formdata["FechadePublicacion"]);
  $dateFormatPublicacion = date_format($datePublicacion, "d/m/Y");

  $mailContent .= '
  <table style="width: 100%; margin: 0; padding: 0; background-color: #efefef;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
  <tbody>
  <tr>
  <td style="padding: 40px 20px;" align="center">
  <table style="width: 600px; max-width: 600px; background-color: #ffffff; border-collapse: collapse;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0">
  <tbody><!-- CABECERA -->
  <tr>
  <td style="padding: 0; background-color: #ffffff;" align="center">
  <table style="width: 100%; border-collapse: collapse;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
  <tbody>
  <tr>
  <td style="height: 5px; line-height: 5px; font-size: 0;" height="5"></td>
  </tr>
  <tr>
  <td style="line-height: 0; font-size: 0;" align="center"><a href="#" style="text-decoration:none;"><img style="display: block; width: 100%; max-width: 600px; height: auto; border: 0;" src="https://top.abc.es/wp-content/uploads/sites/37/2026/09/cab.svg" alt="ABC Top 100 Empresas Familiares 2025" width="600" /></a></td>
  </tr>
  </tbody>
  </table>
  </td>
  </tr>
  <!-- CONTENIDO -->
  <tr>
  <td style="padding: 26px 40px 110px 40px; font-family: Arial, Helvetica, sans-serif; color: #003b7a; font-size: 16px; line-height: 22px; text-align: left;" align="center">
  <p style="margin: 0 0 20px 0;">¡Hola <strong>' . $name . '</strong><span style="font-weight: normal!important;">!</span></p>
  <p style="margin: 0 0 20px 0;">Tu candidatura a <strong>ABC Top 100 Empresas Familiares 2025</strong> se ha registrado correctamente.</p>
  <p style="margin: 0 0 20px 0;">El equipo de la organización revisará la información enviada dentro del proceso de evaluación del ranking.</p>
  <p style="margin: 0;">¡Gracias por participar!</p>
  </td>
  </tr>
  <!-- FOOTER SUPERIOR -->
  <tr>
  <td style="background-color: #0e3a6f; padding: 0 100px;">
  <table style="width: 100%; border-collapse: collapse;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
  <tbody>
  <tr>
  <td style="padding: 0;" align="left"><img style="display: block; width: 142px; max-width: 142px; height: auto; border: 0;" src="https://top.abc.es/wp-content/uploads/sites/37/2026/09/logo-100empresasfamiliares.svg" alt="ABC Top 100 Empresas Familiares 2025" width="142" /></td>
  </tr>
  </tbody>
  </table>
  </td>
  </tr>
  <tr>
  <td style="background-color: #7892b2; padding: 0px 100px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 38px; font-weight: bold; color: #ffffff;" align="right"><a style="color: #ffffff; text-decoration: none;" href="mailto:contacta-top@abc.es">contacta-top@abc.es</a></td>
  </tr>
  </tbody>
  </table>
  </td>
  </tr>
  </tbody>
  </table>
  ';



  if ($candidatoMeta["tipo_solicitud"] == "1" && !empty($candidatoMeta["email_nominador"])) {
      $senderMails .= $candidatoMeta["email_nominador"];
  } else {
      $senderMails .= $candidatoMeta["email_candidato"];
  }


  $mail = $cfdata->prop('mail');
  $mail['body'] = $mailContent;
  $mail["recipient"] = $senderMails;
  $cfdata->set_properties(array('mail' => $mail));


  $mail2 = $cfdata->prop('mail');
  $mail2['body'] = $mailContent;
  $mail2["recipient"] = $senderMails;
  $cfdata->set_properties(array('mail' => $mail2));

  return $cfdata;
}

function agrofood ($cfdata, &$abort, $submission)
{
  // if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
  //     $submission = WPCF7_Submission::get_instance();
  //     if ($submission) {
  //         $formdata = $submission->get_posted_data();
  //     }
  // } elseif (isset($cfdata->posted_data)) {
  //     $formdata = $cfdata->posted_data;
  // }
  //
  //


      static $ya_ejecutado = false;
      if ( $ya_ejecutado ) {
          return;
      }
      $ya_ejecutado = true; // Marcamos como ejecutado
      $formdata = array();
      if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
          $submission = WPCF7_Submission::get_instance();
          if ($submission) {
              $formdata = $submission->get_posted_data();
          }
      } elseif (isset($cfdata->posted_data)) {
          $formdata = $cfdata->posted_data;
      }

      if ( empty( $formdata ) ) {
          return;
      }

  $candidatoMeta = [];

  $candidatoMeta["categoria_propuesta"] = "";
  if (isset($candidatoMeta["categoria_propuesta"])) {
      $candidatoMeta["categoria_propuesta"] = $formdata["categoria_propuesta"];
  }

  $candidatoMeta["tipo_solicitud"] = "";
  if (isset($candidatoMeta["tipo_solicitud"])) {
      $candidatoMeta["tipo_solicitud"] = $formdata["tipo_solicitud"];
  }
  $candidatoMeta["tipo_concurso"] = "";
  if (isset($candidatoMeta["tipo_concurso"])) {
      $candidatoMeta["tipo_concurso"] = $formdata["tipo_concurso"];
  }
  $candidatoMeta["nombre_comercial_empresa"] = "";
  if (isset($candidatoMeta["nombre_comercial_empresa"])) {
      $candidatoMeta["nombre_comercial_empresa"] = $formdata["nombre_comercial_empresa"];
  }
  $candidatoMeta["cif"] = "";
  if (isset($candidatoMeta["cif"])) {
      $candidatoMeta["cif"] = isset($formdata["cif"]) ? $formdata["cif"] : "";
  }
  $candidatoMeta["email_candidato"] = "";
  if (isset($candidatoMeta["email_candidato"])) {
      $candidatoMeta["email_candidato"] = $formdata["email_candidato"];
  }
  $candidatoMeta["telefono_candidato"] = "";
  if (isset($candidatoMeta["telefono_candidato"])) {
      $candidatoMeta["telefono_candidato"] = $formdata["telefono_candidato"];
  }
  $candidatoMeta["sector_actividad"] = "";
  if (isset($candidatoMeta["sector_actividad"])) {
      $candidatoMeta["sector_actividad"] = $formdata["sector_actividad"];
  }
  $candidatoMeta["provincia_principal"] = "";
  if (isset($candidatoMeta["provincia_principal"])) {
      $candidatoMeta["provincia_principal"] = $formdata["provincia_principal"];
  }
  $candidatoMeta["generacion_familiar_actual"] = "";
  if (isset($candidatoMeta["generacion_familiar_actual"])) {
      $candidatoMeta["generacion_familiar_actual"] = $formdata["generacion_familiar_actual"];
  }
  $candidatoMeta["linkedin_empresa"] = "";
  if (isset($candidatoMeta["linkedin_empresa"])) {
      $candidatoMeta["linkedin_empresa"] = $formdata["linkedin_empresa"];
  }
  $candidatoMeta["web_empresa"] = "";
  if (isset($candidatoMeta["web_empresa"])) {
      $candidatoMeta["web_empresa"] = $formdata["web_empresa"];
  }
  $candidatoMeta["nombre_candidato"] = "";
  if (isset($candidatoMeta["nombre_candidato"])) {
      $candidatoMeta["nombre_candidato"] = $formdata["nombre_candidato"];
  }
  $candidatoMeta["apellidos_candidato"] = "";
  if (isset($candidatoMeta["apellidos_candidato"])) {
      $candidatoMeta["apellidos_candidato"] = $formdata["apellidos_candidato"];
  }
  $candidatoMeta["cargo_candidato"] = "";
  if (isset($candidatoMeta["cargo_candidato"])) {
      $candidatoMeta["cargo_candidato"] = $formdata["cargo_candidato"];
  }
  $candidatoMeta["apellidos_candidato"] = "";
  if (isset($candidatoMeta["apellidos_candidato"])) {
      $candidatoMeta["apellidos_candidato"] = $formdata["apellidos_candidato"];
  }
  $candidatoMeta["anio_fundacion"] = "";
  if (isset($candidatoMeta["anio_fundacion"])) {
      $candidatoMeta["anio_fundacion"] = $formdata["anio_fundacion"];
  }
  $candidatoMeta["linkedin_candidato"] = "";
  if (isset($candidatoMeta["linkedin_candidato"])) {
      $candidatoMeta["linkedin_candidato"] = $formdata["linkedin_candidato"];
  }
  if (isset($candidatoMeta["nombre_comercial_empresa"])) {
      if ($candidatoMeta["tipo_solicitud"] == "1") {
          $nombre_comercial_empresa = isset($formdata["nombre_comercial_empresa_nominada"]) ? $formdata["nombre_comercial_empresa_nominada"] : "";
          $candidatoMeta["nombre_comercial_empresa"] = is_scalar($nombre_comercial_empresa) ? $nombre_comercial_empresa : "";
      }
  }
  $candidatoMeta["sector_actividad_nominador"] = "";
  if (isset($candidatoMeta["sector_actividad_nominador"])) {
      $candidatoMeta["sector_actividad_nominador"] = $formdata["sector_actividad_nominador"];
  }
  $candidatoMeta["provincia_principal_nominador"] = "";
  if (isset($candidatoMeta["provincia_principal_nominador"])) {
      $candidatoMeta["provincia_principal_nominador"] = $formdata["provincia_principal_nominador"];
  }
  $candidatoMeta["motivo_nominacion"] = "";
  if (isset($candidatoMeta["motivo_nominacion"])) {
      $candidatoMeta["motivo_nominacion"] = $formdata["motivo_nominacion"];
  }
  $candidatoMeta["nombre_persona_contacto"] = "";
  if (isset($candidatoMeta["nombre_persona_contacto"])) {
      $candidatoMeta["nombre_persona_contacto"] = $formdata["nombre_persona_contacto"];
  }
  $candidatoMeta["cargo_persona_contacto"] = "";
  if (isset($candidatoMeta["cargo_persona_contacto"])) {
      $candidatoMeta["cargo_persona_contacto"] = $formdata["cargo_persona_contacto"];
  }
  $candidatoMeta["email_empresa_nominada"] = "";
  if (isset($candidatoMeta["email_empresa_nominada"])) {
      $candidatoMeta["email_empresa_nominada"] = $formdata["email_empresa_nominada"];
  }
  $candidatoMeta["telefono_empresa_nominada"] = "";
  if (isset($candidatoMeta["telefono_empresa_nominada"])) {
      $candidatoMeta["telefono_empresa_nominada"] = $formdata["telefono_empresa_nominada"];
  }
  $candidatoMeta["meritos_liderazgo"] = "";
  if (isset($candidatoMeta["meritos_liderazgo"])) {
      $candidatoMeta["meritos_liderazgo"] = $formdata["meritos_liderazgo"];
  }
  $candidatoMeta["meritos_vision"] = "";
  if (isset($candidatoMeta["meritos_vision"])) {
      $candidatoMeta["meritos_vision"] = $formdata["meritos_vision"];
  }
  $candidatoMeta["meritos_impacto"] = "";
  if (isset($candidatoMeta["meritos_impacto"])) {
      $candidatoMeta["meritos_impacto"] = $formdata["meritos_impacto"];
  }
  $candidatoMeta["porcentaje_ventas"] = "";
  if (isset($candidatoMeta["porcentaje_ventas"])) {
      $candidatoMeta["porcentaje_ventas"] = $formdata["porcentaje_ventas"];
  }
  $candidatoMeta["dato_cerrado_ventas_2025"] = "";
  if (isset($candidatoMeta["dato_cerrado_ventas_2025"])) {
      $candidatoMeta["dato_cerrado_ventas_2025"] = $formdata["dato_cerrado_ventas_2025"];
  }
  $candidatoMeta["porcentaje_ebita"] = "";
  if (isset($candidatoMeta["porcentaje_ebita"])) {
      $candidatoMeta["porcentaje_ebita"] = $formdata["porcentaje_ebita"];
  }
  $candidatoMeta["dato_cerrado_ebita_2025"] = "";
  if (isset($candidatoMeta["dato_cerrado_ebita_2025"])) {
      $candidatoMeta["dato_cerrado_ebita_2025"] = $formdata["dato_cerrado_ebita_2025"];
  }
  $candidatoMeta["tendencia_evolucion_ebita"] = "";
  if (isset($candidatoMeta["tendencia_evolucion_ebita"])) {
      $candidatoMeta["tendencia_evolucion_ebita"] = $formdata["tendencia_evolucion_ebita"];
  }
  $candidatoMeta["porcentaje_empleo_neto"] = "";
  if (isset($candidatoMeta["porcentaje_empleo_neto"])) {
      $candidatoMeta["porcentaje_empleo_neto"] = $formdata["porcentaje_empleo_neto"];
  }
  $candidatoMeta["dato_cerrado_empleo_neto_2025"] = "";
  if (isset($candidatoMeta["dato_cerrado_empleo_neto_2025"])) {
      $candidatoMeta["dato_cerrado_empleo_neto_2025"] = $formdata["dato_cerrado_empleo_neto_2025"];
  }
  $candidatoMeta["nombre_nominador"] = "";
  if (isset($candidatoMeta["nombre_nominador"])) {
      $candidatoMeta["nombre_nominador"] = $formdata["nombre_nominador"];
  }
  $candidatoMeta["apellidos_nominador"] = "";
  if (isset($candidatoMeta["apellidos_nominador"])) {
      $candidatoMeta["apellidos_nominador"] = $formdata["apellidos_nominador"];
  }
  $candidatoMeta["email_nominador"] = "";
  if (isset($candidatoMeta["email_nominador"])) {
      $candidatoMeta["email_nominador"] = $formdata["email_nominador"];
  }
  $candidatoMeta["telefono_nominador"] = "";
  if (isset($candidatoMeta["telefono_nominador"])) {
      $candidatoMeta["telefono_nominador"] = $formdata["telefono_nominador"];
  }
  $candidatoMeta["relacion_con_nominado"] = "";
  if (isset($candidatoMeta["relacion_con_nominado"])) {
      $candidatoMeta["relacion_con_nominado"] = $formdata["relacion_con_nominado"];
  }
  $candidatoMeta["politica_privacidad_001_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_001_nominado"])) {
      $candidatoMeta["politica_privacidad_001_nominado"] = $formdata["politica_privacidad_001_nominado"];
  }
  $candidatoMeta["politica_privacidad_002_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_002_nominado"])) {
      $candidatoMeta["politica_privacidad_002_nominado"] = $formdata["politica_privacidad_002_nominado"];
  }
  $candidatoMeta["politica_privacidad_003_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_003_nominado"])) {
      $candidatoMeta["politica_privacidad_003_nominado"] = $formdata["politica_privacidad_003_nominado"];
  }
  $candidatoMeta["politica_privacidad_004_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_004_nominado"])) {
      $candidatoMeta["politica_privacidad_004_nominado"] = $formdata["politica_privacidad_004_nominado"];
  }
  $candidatoMeta["politica_privacidad_005_nominado"] = "";
  if (isset($candidatoMeta["politica_privacidad_005_nominado"])) {
      $candidatoMeta["politica_privacidad_005_nominado"] = $formdata["politica_privacidad_005_nominado"];
  }
  $candidatoMeta["politica_privacidad_001_nominador"] = "";
  if (isset($candidatoMeta["politica_privacidad_001_nominador"])) {
      $candidatoMeta["politica_privacidad_001_nominador"] = $formdata["politica_privacidad_001_nominador"];
  }
  $candidatoMeta["politica_privacidad_002_nominador"] = "";
  if (isset($candidatoMeta["politica_privacidad_002_nominador"])) {
      $candidatoMeta["politica_privacidad_002_nominador"] = $formdata["politica_privacidad_002_nominador"];
  }
  $candidatoMeta["politica_privacidad_003_nominador"] = "";
  if (isset($candidatoMeta["politica_privacidad_003_nominador"])) {
      $candidatoMeta["politica_privacidad_003_nominador"] = $formdata["politica_privacidad_003_nominador"];
  }

  if ($candidatoMeta["tipo_solicitud"] == "1") {
      $name = $candidatoMeta["nombre_nominador"] . " " . $candidatoMeta["apellidos_nominador"];
  } else {
      $name = $candidatoMeta["nombre_candidato"] . " " . $candidatoMeta["apellidos_candidato"];
  }

  $candidatoMeta["nombre_apellidos_empresario"] = "";
  if (isset($candidatoMeta["nombre_apellidos_empresario"])) {
      $candidatoMeta["nombre_apellidos_empresario"] = $formdata["nombre_apellidos_empresario"];
  }
  $candidatoMeta["dni_nie_empresario"] = "";
  if (isset($candidatoMeta["dni_nie_empresario"])) {
      $candidatoMeta["dni_nie_empresario"] = $formdata["dni_nie_empresario"];
  }
  $candidatoMeta["provincia_ccaa_empresario"] = "";
  if (isset($candidatoMeta["provincia_ccaa_empresario"])) {
      $candidatoMeta["provincia_ccaa_empresario"] = $formdata["provincia_ccaa_empresario"];
  }
  $candidatoMeta["cargo_empresario"] = "";
  if (isset($candidatoMeta["cargo_empresario"])) {
      $candidatoMeta["cargo_empresario"] = $formdata["cargo_empresario"];
  }
  $candidatoMeta["empresa_empresario"] = "";
  if (isset($candidatoMeta["empresa_empresario"])) {
      $candidatoMeta["empresa_empresario"] = $formdata["empresa_empresario"];
  }
  $candidatoMeta["meritos_liderazgo_empresario"] = "";
  if (isset($candidatoMeta["meritos_liderazgo_empresario"])) {
      $candidatoMeta["meritos_liderazgo_empresario"] = $formdata["meritos_liderazgo_empresario"];
  }
  $candidatoMeta["meritos_vision_empresario"] = "";
  if (isset($candidatoMeta["meritos_vision_empresario"])) {
      $candidatoMeta["meritos_vision_empresario"] = $formdata["meritos_vision_empresario"];
  }
  $candidatoMeta["meritos_impacto_empresario"] = "";
  if (isset($candidatoMeta["meritos_impacto_empresario"])) {
      $candidatoMeta["meritos_impacto_empresario"] = $formdata["meritos_impacto_empresario"];
  }
  $candidatoMeta["porcentaje_ventas_empresario"] = "";
  if (isset($candidatoMeta["porcentaje_ventas_empresario"])) {
      $candidatoMeta["porcentaje_ventas_empresario"] = $formdata["porcentaje_ventas_empresario"];
  }
  $candidatoMeta["dato_cerrado_ventas_2025_empresario"] = "";
  if (isset($candidatoMeta["dato_cerrado_ventas_2025_empresario"])) {
      $candidatoMeta["dato_cerrado_ventas_2025_empresario"] = $formdata["dato_cerrado_ventas_2025_empresario"];
  }
  $candidatoMeta["porcentaje_ebita_empresario"] = "";
  if (isset($candidatoMeta["porcentaje_ebita_empresario"])) {
      $candidatoMeta["porcentaje_ebita_empresario"] = $formdata["porcentaje_ebita_empresario"];
  }
  $candidatoMeta["dato_cerrado_ebita_2025_empresario"] = "";
  if (isset($candidatoMeta["dato_cerrado_ebita_2025_empresario"])) {
      $candidatoMeta["dato_cerrado_ebita_2025_empresario"] = $formdata["dato_cerrado_ebita_2025_empresario"];
  }
  $candidatoMeta["porcentaje_empleo_neto_empresario"] = "";
  if (isset($candidatoMeta["porcentaje_empleo_neto_empresario"])) {
      $candidatoMeta["porcentaje_empleo_neto_empresario"] = $formdata["porcentaje_empleo_neto_empresario"];
  }
  $candidatoMeta["dato_cerrado_empleo_neto_2025_empresario"] = "";
  if (isset($candidatoMeta["dato_cerrado_empleo_neto_2025_empresario"])) {
      $candidatoMeta["dato_cerrado_empleo_neto_2025_empresario"] = $formdata["dato_cerrado_empleo_neto_2025_empresario"];
  }

  $posts = [];
  if ($candidatoMeta["tipo_solicitud"] == "0") {
      $args = array(
          'post_type' => 'candidato',
          'meta_key' => 'cif',
          'meta_query' => array(
              array(
                  'key' => 'cif',
                  'value' => $candidatoMeta["cif"],
                  'compare' => '=',
              )
          )
      );

      $posts = get_posts($args);
  }
  $pid = 0;

  if (!empty($posts)) {
      $abort = true;
      $errMsg = '';
      unset($formdata['not-sending-data']);
      $errMsg = "<strong>Este candidato ya ha sido registrado.</strong> En caso de que tengas que volver a inscribir tu candidatura, ponte en contacto con nosotros a través de <a href=\"mailto:contacta-top100@abc.es\">contacta-top100@abc.es</a>.";

      if ($errMsg) {
          $submission->set_status('validation_failed');
          $submission->set_response($cfdata->filter_message($errMsg));
      }
      return $cfdata;
  } else {
      if($name );
      $new_post = array(
          'post_title' => $name,
          'post_status' => 'publish',
          'post_type' => 'candidato'
      );
      $pid = wp_insert_post($new_post);
      if ($candidatoMeta["tipo_solicitud"] == "0") {
          update_post_meta($pid, 'cif', $candidatoMeta["cif"]);
      }
      update_post_meta($pid, 'tipo_solicitud', $candidatoMeta["tipo_solicitud"]);
      update_post_meta($pid, 'ronda', 0);
      update_post_meta($pid, "tipo_concurso", $formdata["tipo_concurso"]);
  }


  if (isset($_FILES["foto_familia"]) && $_FILES["foto_familia"]['error'] === UPLOAD_ERR_OK) {
      $tmp_path = $_FILES["foto_familia"]['tmp_name'];
      $original_name = $_FILES["foto_familia"]['name'];
      $ext = pathinfo($original_name, PATHINFO_EXTENSION);
      if (empty($ext))
          $ext = 'jpg';

      $filename = 'foto_familia_' . $pid . '.' . $ext;
      $ficherosubida = wp_upload_bits($filename, null, file_get_contents($tmp_path));

      if (!$ficherosubida['error']) {
          $candidatoMeta['foto_familia'] = $ficherosubida['url'];
      }
  }


  if (isset($_FILES["logo_empresa"]) && $_FILES["logo_empresa"]['error'] === UPLOAD_ERR_OK) {
      $tmp_path = $_FILES["logo_empresa"]['tmp_name'];
      $original_name = $_FILES["logo_empresa"]['name'];
      $ext = pathinfo($original_name, PATHINFO_EXTENSION);
      if (empty($ext))
          $ext = 'png';

      $filename = 'logo_empresa_' . $pid . '.' . $ext;
      $ficherosubida = wp_upload_bits($filename, null, file_get_contents($tmp_path));

      if (!$ficherosubida['error']) {
          $candidatoMeta['logo_empresa'] = $ficherosubida['url'];
      }
  }

  if (isset($_FILES["foto_empresario"]) && $_FILES["foto_empresario"]['error'] === UPLOAD_ERR_OK) {
      $tmp_path = $_FILES["foto_empresario"]['tmp_name'];
      $original_name = $_FILES["foto_empresario"]['name'];
      $ext = pathinfo($original_name, PATHINFO_EXTENSION);
      if (empty($ext))
          $ext = 'png';

      $filename = 'foto_empresario_' . $pid . '.' . $ext;
      $ficherosubida = wp_upload_bits($filename, null, file_get_contents($tmp_path));

      if (!$ficherosubida['error']) {
          $candidatoMeta['foto_empresario'] = $ficherosubida['url'];
      }
  }



  update_post_meta($pid, 'candidato_metadata', $candidatoMeta);

  $senderMails = "";
  $mailContent = '';

  $mailContent .= '
  <table style="width: 100%; margin: 0; padding: 0; background-color: #efefef;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="padding: 40px 20px;" align="center">

      <table style="width: 600px; max-width: 600px; background-color: #ffffff; border-collapse: collapse;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0">
      <tbody>

      <!-- CABECERA -->
      <tr>
      <td style="padding: 0; background-color: #ffffff;" align="center">
      <table style="width: 100%; border-collapse: collapse;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="height: 5px; line-height: 5px; font-size: 0;" height="5"></td>
      </tr>
      <tr>
      <td style="line-height: 0; font-size: 0;" align="center">
      <a href="#" style="text-decoration:none;">
      <img style="display: block; width: 100%; max-width: 600px; height: auto; border: 0;" src="https://premiosagrofood.es/wp-content/uploads/sites/42/2026/09/sello-premios-agro.svg" alt="Premios AgroFood" width="600"/>
      </a>
      </td>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>

      <!-- CONTENIDO -->
      <tr>
      <td style="padding: 26px 40px 110px 40px; font-family: Arial, Helvetica, sans-serif; color: #202020; font-size: 16px; line-height: 22px; text-align: left;" align="center">

      <p style="margin: 0 0 20px 0;">
      ¡Hola <strong>' . $name . '</strong><span style="font-weight: normal;">!</span>
      </p>

      <p style="margin: 0 0 20px 0;">
      Tu candidatura a los <strong>Premios AgroFood</strong> se ha registrado correctamente.
      </p>

      <p style="margin: 0 0 20px 0;">
      El equipo de Vocento AgroSummit revisará la información enviada.
      </p>

      <p style="margin: 0 0 20px 0;">
      ¡Gracias por participar!
      </p>

      <p style="margin: 0;">
      <strong>Comité Organizador Premios AgroFood</strong>
      </p>

      </td>
      </tr>

      <!-- FOOTER SUPERIOR -->
      <tr>
      <td style="background-color: #70775A; padding: 0 100px;">
      <table style="width: 100%; border-collapse: collapse;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="padding: 0;" align="left">
      <img style="display: block; width: 142px; max-width: 142px; height: auto; border: 0;" src="https://premiosagrofood.es/wp-content/uploads/sites/42/2026/09/logo-premios-agro.svg" alt="Premios AgroFood" width="142"/>
      </td>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>

      <tr>
      <td style="background-color: #F4F1EB; padding: 0px 100px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 38px; font-weight: bold; color: #ffffff;" align="right">
      <a style="color: #202020; text-decoration: none;" href="mailto:contacto@vocentoagrosummit.com">
      contacto@vocentoagrosummit.com
      </a>
      </td>
      </tr>

      </tbody>
      </table>

      </td>
      </tr>
      </tbody>
      </table>
  ';



  if ($candidatoMeta["tipo_solicitud"] == "1" && !empty($candidatoMeta["email_nominador"])) {
      $senderMails .= $candidatoMeta["email_nominador"];
  } else {
      $senderMails .= $candidatoMeta["email_candidato"];
  }


  $mail = $cfdata->prop('mail');
  $mail['body'] = $mailContent;
  $mail["recipient"] = $senderMails;
  $cfdata->set_properties(array('mail' => $mail));

  $mail2 = $cfdata->prop('mail');
  $mail2['body'] = $mailContent;
  $mail2["recipient"] = $senderMails;
  $cfdata->set_properties(array('mail' => $mail2));

  return $cfdata;
}

// define the wpcf7_before_send_mail callback
function action_wpcf7_before_send_mail($cfdata, &$abort, $submission)
{
  switch ($cfdata->id){
    case 8348:
      directivospymes($cfdata, $abort, $submission);
      break;
    case 8329:
      pymesfamilias($cfdata, $abort, $submission);
      break;
    case 8373:
      agrofood($cfdata, $abort, $submission);;
      break;
  }
};

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

function update_canditado_fase_top100()
{
    // Check for nonce security
    $idpost = $_POST['id'];
    $fase = $_POST['fase'];

    $nonce = sanitize_text_field($_POST['nonce']);
    if (!wp_verify_nonce($nonce, 'my-ajax-nonce')) {
        die('Busted!');
    }

    if ($fase != '5') {
        update_post_meta(intval($idpost), 'ronda', intval($fase));
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
                    'value' => 0,
                ),
            ),
        );
        $post = new WP_Query($args);
        update_post_meta($post->posts[0]->ID, 'ronda', 4);
        update_post_meta($idpost, 'ronda', 5);
    }

    wp_die();
}
add_action('wp_ajax_nopriv_updatefaseunotop100', 'update_canditado_fase_top100');
add_action('wp_ajax_updatefaseunotop100', 'update_canditado_fase_top100');

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
// add_action('wp_footer', 'scrollToBox');






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
    $thePage = isset($_GET['the_page']) ? sanitize_text_field($_GET['the_page']) : '';
    $es_pymes_familiares = in_array($thePage, array('candidatospymesfamilias', 'nominadorespymesfamiliares'), true);
    $es_inscripcion_familiar = $thePage === 'candidatospymesfamilias';

    $meta_query = array('relation' => 'AND');

    if ($thePage === 'candidatospymesfamilias' || $thePage === 'candidatos') {
        // $meta_query[] = array(
        //     'key' => 'tipo_solicitud',
        //     'value' => '0',
        //     'compare' => '='
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => '0',
                'compare' => '='
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
            ...($thePage === 'candidatospymesfamilias' ? array(array(
                'key' => 'tipo_concurso',
                'value' => 'pymesfamiliares',
            )) : array()),
        );
    } elseif ($thePage === 'nominadorespymesfamiliares' || $thePage === 'nominadores') {
        // $meta_query[] = array(
        //     'key' => 'tipo_solicitud',
        //     'value' => '1',
        //     'compare' => '='
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
            ...($thePage === 'nominadorespymesfamiliares' ? array(array(
                'key' => 'tipo_concurso',
                'value' => 'pymesfamiliares',
            )) : array()),
        );
    } else {
        // $meta_query[] = array(
        //     'key' => 'ronda',
        //     'value' => intval($idEstado),
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => '0',
            ),
            array(
                'key' => 'ronda',
                'value' => intval($idEstado),
            ),
        );
    }

    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => $meta_query,
    );
    $loop = new WP_Query($args);

    $oExcel = new PHPExcel();

    $headers = array(
      'FECHA INSCRIPCION','categoria propuesta','tipo solicitud','tipo concurso','nombre comercial empresa','cif','email candidato','telefono candidato','sector actividad','provincia principal','generacion familiar actual','linkedin empresa','web empresa','nombre candidato','apellidos candidato','cargo candidato','apellidos candidato','anio fundacion','linkedin candidato','nombre comercial empresa','sector actividad nominador','provincia principal nominador','motivo nominacion','nombre persona contacto','cargo persona contacto','email empresa nominada','telefono empresa nominada','meritos liderazgo','meritos vision','meritos impacto','porcentaje ventas','dato cerrado ventas 2025','porcentaje ebita','dato cerrado ebita 2025','tendencia evolucion ebita','porcentaje empleo neto','dato cerrado empleo neto 2025','nombre nominador','apellidos nominador','email nominador','telefono nominador','relacion con nominado','nombre nominador','nombre apellidos empresario','dni nie empresario','provincia ccaa empresario','cargo empresario','empresa empresario','meritos liderazgo empresario','meritos vision empresario','meritos impacto empresario','porcentaje ventas empresario','dato cerrado ventas 2025 empresario','porcentaje ebita empresario','dato cerrado ebita 2025 empresario','porcentaje empleo neto empresario','dato cerrado empleo neto 2025 empresario','foto familia','logo empresa','foto empresario'
    );
    foreach ($headers as $column => $header) {
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $header);
    }

    $contador = 2;
    while ($loop->have_posts()):
        $loop->the_post();
        $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);

        $family_values = array(
          get_the_date('Y/m/d'),
          isset($data['categoria_propuesta']) ? $data['categoria_propuesta'] : '',
          isset($data['tipo_solicitud']) ? $data['tipo_solicitud'] : '',
          isset($data['tipo_concurso']) ? $data['tipo_concurso'] : '',
          isset($data['nombre_comercial_empresa']) ? $data['nombre_comercial_empresa'] : '',
          isset($data['cif']) ? $data['cif'] : '',
          isset($data['email_candidato']) ? $data['email_candidato'] : '',
          isset($data['telefono_candidato']) ? $data['telefono_candidato'] : '',
          isset($data['sector_actividad']) ? $data['sector_actividad'] : '',
          isset($data['provincia_principal']) ? $data['provincia_principal'] : '',
          isset($data['generacion_familiar_actual']) ? $data['generacion_familiar_actual'] : '',
          isset($data['linkedin_empresa']) ? $data['linkedin_empresa'] : '',
          isset($data['web_empresa']) ? $data['web_empresa'] : '',
          isset($data['nombre_candidato']) ? $data['nombre_candidato'] : '',
          isset($data['apellidos_candidato']) ? $data['apellidos_candidato'] : '',
          isset($data['cargo_candidato']) ? $data['cargo_candidato'] : '',
          isset($data['apellidos_candidato']) ? $data['apellidos_candidato'] : '',
          isset($data['anio_fundacion']) ? $data['anio_fundacion'] : '',
          isset($data['linkedin_candidato']) ? $data['linkedin_candidato'] : '',
          isset($data['nombre_comercial_empresa']) ? $data['nombre_comercial_empresa'] : '',
          isset($data['sector_actividad_nominador']) ? $data['sector_actividad_nominador'] : '',
          isset($data['provincia_principal_nominador']) ? $data['provincia_principal_nominador'] : '',
          isset($data['motivo_nominacion']) ? $data['motivo_nominacion'] : '',
          isset($data['nombre_persona_contacto']) ? $data['nombre_persona_contacto'] : '',
          isset($data['cargo_persona_contacto']) ? $data['cargo_persona_contacto'] : '',
          isset($data['email_empresa_nominada']) ? $data['email_empresa_nominada'] : '',
          isset($data['telefono_empresa_nominada']) ? $data['telefono_empresa_nominada'] : '',
          isset($data['meritos_liderazgo']) ? $data['meritos_liderazgo'] : '',
          isset($data['meritos_vision']) ? $data['meritos_vision'] : '',
          isset($data['meritos_impacto']) ? $data['meritos_impacto'] : '',
          isset($data['porcentaje_ventas']) ? $data['porcentaje_ventas'] : '',
          isset($data['dato_cerrado_ventas_2025']) ? $data['dato_cerrado_ventas_2025'] : '',
          isset($data['porcentaje_ebita']) ? $data['porcentaje_ebita'] : '',
          isset($data['dato_cerrado_ebita_2025']) ? $data['dato_cerrado_ebita_2025'] : '',
          isset($data['tendencia_evolucion_ebita']) ? $data['tendencia_evolucion_ebita'] : '',
          isset($data['porcentaje_empleo_neto']) ? $data['porcentaje_empleo_neto'] : '',
          isset($data['dato_cerrado_empleo_neto_2025']) ? $data['dato_cerrado_empleo_neto_2025'] : '',
          isset($data['nombre_nominador']) ? $data['nombre_nominador'] : '',
          isset($data['apellidos_nominador']) ? $data['apellidos_nominador'] : '',
          isset($data['email_nominador']) ? $data['email_nominador'] : '',
          isset($data['telefono_nominador']) ? $data['telefono_nominador'] : '',
          isset($data['relacion_con_nominado']) ? $data['relacion_con_nominado'] : '',
          isset($data['nombre_nominador']) ? $data['nombre_nominador'] : '',
          isset($data['nombre_apellidos_empresario']) ? $data['nombre_apellidos_empresario'] : '',
          isset($data['dni_nie_empresario']) ? $data['dni_nie_empresario'] : '',
          isset($data['provincia_ccaa_empresario']) ? $data['provincia_ccaa_empresario'] : '',
          isset($data['cargo_empresario']) ? $data['cargo_empresario'] : '',
          isset($data['empresa_empresario']) ? $data['empresa_empresario'] : '',
          isset($data['meritos_liderazgo_empresario']) ? $data['meritos_liderazgo_empresario'] : '',
          isset($data['meritos_vision_empresario']) ? $data['meritos_vision_empresario'] : '',
          isset($data['meritos_impacto_empresario']) ? $data['meritos_impacto_empresario'] : '',
          isset($data['porcentaje_ventas_empresario']) ? $data['porcentaje_ventas_empresario'] : '',
          isset($data['dato_cerrado_ventas_2025_empresario']) ? $data['dato_cerrado_ventas_2025_empresario'] : '',
          isset($data['porcentaje_ebita_empresario']) ? $data['porcentaje_ebita_empresario'] : '',
          isset($data['dato_cerrado_ebita_2025_empresario']) ? $data['dato_cerrado_ebita_2025_empresario'] : '',
          isset($data['porcentaje_empleo_neto_empresario']) ? $data['porcentaje_empleo_neto_empresario'] : '',
          isset($data['dato_cerrado_empleo_neto_2025_empresario']) ? $data['dato_cerrado_empleo_neto_2025_empresario'] : '',
          isset($data['foto_familia']) ? $data['foto_familia'] : '',
          isset($data['logo_empresa']) ? $data['logo_empresa'] : '',
          isset($data['foto_empresario']) ? $data['foto_empresario'] : ''
        );
        foreach ($family_values as $column => $value) {
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $contador, $value);
        }

        $contador++;
    endwhile;
    wp_reset_postdata();

    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');

    $sufijo_archivo = '';
    if (isset($thePage)) {
        if ($thePage === 'candidatos' || $thePage === 'candidatospymesfamilias' || $thePage === '') {
            $sufijo_archivo = '_inscripcion';
        } elseif ($thePage === 'nominadores' || $thePage === 'nominadorespymesfamiliares') {
            $sufijo_archivo = '_nominacion';
        }
    }

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $nameFile . $sufijo_archivo . '_' . date('Y-m-d') . '.xls"');
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
    $thePage = isset($_GET['the_page']) ? sanitize_text_field($_GET['the_page']) : '';

    $meta_query = array('relation' => 'AND');

    if ($thePage === 'candidatos') {
        // $meta_query[] = array(
        //     'key' => 'tipo_solicitud',
        //     'value' => '0',
        //     'compare' => '='
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 0,
                'compare' => '='
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
        );
    } elseif ($thePage === 'nominadores') {
        // $meta_query[] = array(
        //     'key' => 'tipo_solicitud',
        //     'value' => '1',
        //     'compare' => '='
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 1,
                'compare' => '='
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
        );
    } else {
        // $meta_query[] = array(
        //     'key' => 'ronda',
        //     'value' => intval($idEstado),
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => '0',
            ),
            array(
                'key' => 'ronda',
                'value' => intval($idEstado),
            ),
        );
    }

    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => $meta_query,
    );

    $loop = new WP_Query($args);

    $oExcel = new PHPExcel();
    // first row
    if ($thePage === 'candidatos' || $thePage === '') {
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'TIPO SOLICITUD');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'NOMBRE CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'APELLIDOS CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'CARGO CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'EMPRESA CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'EMAIL CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'TELEFONO CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'LINKEDIN CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'WEB EMPRESA CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, '1. LIDERAZGO HUMANO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, '2. VISIÓN ESTRATÉGICA');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, '3. IMPACTO REAL');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'INCREMENTO FACTURACIÓN (%)');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'INCREMENTO RESULTADOS (%)');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'INCREMENTO PLANTILLA (%)');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'FOTO DIRECTIVO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'LOGO EMPRESA');
    } else {
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'FECHA INSCRIPCION');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'TIPO SOLICITUD');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'NOMBRE NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'APELLIDOS NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'CARGO NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'EMPRESA NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'EMAIL NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'TELEFONO NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'LINKEDIN NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'WEB EMPRESA NOMINADOR');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'NOMBRE CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'APELLIDOS CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'CARGO CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'EMPRESA CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'EMAIL CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'TELEFONO CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'LINKEDIN CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'WEB EMPRESA CANDIDATO');
    }

    $contador = 2;
    while ($loop->have_posts()):
        $loop->the_post();
        $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);

        if ($thePage === 'candidatos' || $thePage === '') {
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d'));
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, 'Inscripción propia');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, isset($data['nombre_candidato']) ? $data['nombre_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, isset($data['apellidos_candidato']) ? $data['apellidos_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, isset($data['cargo_candidato']) ? $data['cargo_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, isset($data['empresa_candidato']) ? $data['empresa_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, isset($data['email_candidato']) ? $data['email_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, isset($data['telefono_candidato']) ? $data['telefono_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, isset($data['linkedin_candidato']) ? $data['linkedin_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $contador, isset($data['web_empresa_candidato']) ? $data['web_empresa_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $contador, isset($data['meritos_liderazgo']) ? $data['meritos_liderazgo'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $contador, isset($data['meritos_vision']) ? $data['meritos_vision'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, isset($data['meritos_impacto']) ? $data['meritos_impacto'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $contador, isset($data['inc_facturacion']) ? $data['inc_facturacion'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $contador, isset($data['inc_resultados']) ? $data['inc_resultados'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $contador, isset($data['inc_plantilla']) ? $data['inc_plantilla'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $contador, isset($data['foto_directivo']) ? $data['foto_directivo'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $contador, isset($data['logo_empresa']) ? $data['logo_empresa'] : '');
        } else {
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, get_the_date('Y/m/d'));
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, 'Nominación');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, isset($data['nombre_nominador']) ? $data['nombre_nominador'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, isset($data['apellidos_nominador']) ? $data['apellidos_nominador'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $contador, isset($data['cargo_nominador']) ? $data['cargo_nominador'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $contador, isset($data['empresa_nominador']) ? $data['empresa_nominador'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $contador, isset($data['email_nominador']) ? $data['email_nominador'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $contador, isset($data['telefono_nominador']) ? $data['telefono_nominador'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $contador, isset($data['linkedin_nominador']) ? $data['linkedin_nominador'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $contador, isset($data['web_empresa_nominador']) ? $data['web_empresa_nominador'] : '');

            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $contador, isset($data['nombre_candidato']) ? $data['nombre_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $contador, isset($data['apellidos_candidato']) ? $data['apellidos_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $contador, isset($data['cargo_candidato']) ? $data['cargo_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $contador, isset($data['empresa_candidato']) ? $data['empresa_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $contador, isset($data['email_candidato']) ? $data['email_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $contador, isset($data['telefono_candidato']) ? $data['telefono_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $contador, isset($data['linkedin_candidato']) ? $data['linkedin_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $contador, isset($data['web_empresa_candidato']) ? $data['web_empresa_candidato'] : '');
        }

        $contador++;
    endwhile;
    wp_reset_postdata();

    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');

    $sufijo_archivo = '';
    if (isset($thePage)) {
        if ($thePage === 'candidatos' || $thePage === '') {
            $sufijo_archivo = '_inscripcion';
        } elseif ($thePage === 'nominadores') {
            $sufijo_archivo = '_nominacion';
        }
    }

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $nameFile . $sufijo_archivo . '_' . date('Y-m-d') . '.xls"');
    header("Pragma: no-cache");
    header("Expires: 0");
    $objWriter->save('php://output');

    die();
}
add_action('wp_ajax_excel_pull_ext', 'ns_contact_form_excel_pull_ext');
add_action('wp_ajax_nopriv_excel_pull_ext', 'ns_contact_form_excel_pull_ext');

add_action('wp_enqueue_scripts', function () {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'voc_listado_top100')) {
        wp_enqueue_style('voc-listado3-css', GISTAU__PLUGIN_URL . 'assets/css/listado3.css', array(), '1.4.3');
        wp_enqueue_script('voc-listado3-js', GISTAU__PLUGIN_URL . 'assets/js/listado3.js', array(), '1.4.4', true);
    }
});
















function ns_contact_form_excel_pull_imgs()
{
    /** Include PHPExcel */
    require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

    $idEstado = $_GET['the_issue_key'];
    $nameFile = $_GET['the_name'];
    $thePage = isset($_GET['the_page']) ? sanitize_text_field($_GET['the_page']) : '';

    $thePage = 'descartadossecretaria';


    $meta_query = array('relation' => 'AND');

    if ($thePage === 'candidatos') {
        // $meta_query[] = array(
        //     'key' => 'tipo_solicitud',
        //     'value' => '0',
        //     'compare' => '='
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => '0',
                'compare' => '='
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
        );
    } elseif ($thePage === 'nominadores') {
        // $meta_query[] = array(
        //     'key' => 'tipo_solicitud',
        //     'value' => '1',
        //     'compare' => '='
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
        );
    } else {
        // $meta_query[] = array(
        //     'key' => 'ronda',
        //     'value' => intval($idEstado),
        // );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => '0',
            ),
            array(
                'key' => 'ronda',
                'value' => intval($idEstado),
            ),
        );
    }

    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => $meta_query,
    );
    $loop = new WP_Query($args);

    $oExcel = new PHPExcel();

    if (strpos($thePage, 'candidatos') !== false || strpos($thePage, 'descartadossecretaria') !== false) {
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'NOMBRE CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'EMAIL CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'TELEFONO CANDIDATO');
        $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'FOTO DIRECTIVO');
    }

    $contador = 2;
    while ($loop->have_posts()):
        $loop->the_post();
        $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);

        // if ($thePage === 'candidatos' || $thePage === '') {
        if (strpos($thePage, 'candidatos') !== false || strpos($thePage, 'descartadossecretaria') !== false) {
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $contador, isset($data['nombre_candidato']) ? $data['nombre_candidato'] . '  ' . $data['apellidos_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $contador, isset($data['email_candidato']) ? $data['email_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $contador, isset($data['telefono_candidato']) ? $data['telefono_candidato'] : '');
            $oExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $contador, isset($data['foto_directivo']) ? $data['foto_directivo'] : '');
        }

        $contador++;
    endwhile;
    wp_reset_postdata();

    $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');

    $sufijo_archivo = '';
    if (isset($thePage)) {
        if ($thePage === 'candidatos' || $thePage === '') {
            $sufijo_archivo = '_inscripcion';
        } elseif ($thePage === 'nominadores') {
            $sufijo_archivo = '_nominacion';
        }
    }

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $nameFile . $sufijo_archivo . '_' . date('Y-m-d') . '.xls"');
    header("Pragma: no-cache");
    header("Expires: 0");

    $objWriter->save('php://output');
    wp_die();
}
add_action('wp_ajax_excel_pull_imgs', 'ns_contact_form_excel_pull_imgs');









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
                return formId === '8373';
            }

            function gistauIsFamilyForm(form) {
                return \$(form).find('input[name=_wpcf7]').val() === '8373';
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
