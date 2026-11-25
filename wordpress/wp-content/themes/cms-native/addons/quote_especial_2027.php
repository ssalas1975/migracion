<?php

function create_voc_quote_especial_2027_shortcode($atts, $content) {
	$atts = shortcode_atts(
		array(
		  'imagequote' => '',
			'antetituloquote' => '',
			'nombrequote' => '',
			'cargoquote' => '',
			'ficherocoquote2027' => '',

		),
		$atts,
		'voc_quote_especial_2027'
	);


	$imagequote = "";
	if($atts['imagequote'] != ""){
	  $imagequoteurl = wp_get_attachment_url($atts['imagequote']);
		$imagequote = '
		<figure class="quote-especial-2027-foto">
        <img src="'.$imagequoteurl.'" alt="">
    </figure>
		';
	}

	$antetituloquote = "";
	if($atts['antetituloquote'] != ""){
	  $antetituloquote = '<p class="quote-especial-2027-antetitulo">'.$atts['antetituloquote'].'</p>';
	}

	$nombrequote = "";
	if($atts['nombrequote'] != ""){
	  $nombrequote = '<p class="quote-especial-2027-nombre">'.$atts['nombrequote'].'</p>';
	}

	$cargoquote = "";
	if($atts['cargoquote'] != ""){
	  $cargoquote = '<p class="quote-especial-2027-cargo">'.$atts['cargoquote'].'</p>';
	}

	$ficherocoquote2027 = "";
	if($atts['ficherocoquote2027'] != ""){
	  $src = wp_get_attachment_url($atts['ficherocoquote2027']);
			var_dump($src);
	  $ficherocoquote2027 = '
			<div class="quote-especial-2027-audio">
            <button type="button" class="quote-especial-2027-play" aria-label="Reproducir audio">
                <svg class="quote-especial-2027-icon-play" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 2.8v18.4L21.5 12 5 2.8z" />
                </svg>
                <svg class="quote-especial-2027-icon-pause" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="4.5" y="3" width="5.5" height="18" rx="1.2" />
                    <rect x="14" y="3" width="5.5" height="18" rx="1.2" />
                </svg>
            </button>
            <div class="quote-especial-2027-progress">
                <div class="quote-especial-2027-progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <span class="quote-especial-2027-progress-fill"></span>
                </div>
                <span class="quote-especial-2027-time">0:00 / 0:00</span>
            </div>
            <audio class="quote-especial-2027-src" src="'.$src.'" preload="metadata"></audio>
        </div>
    </div>
			';
	}




	$html = '
	<blockquote class="quote-especial-2027">
    '.$imagequote .'
    <div class="quote-especial-2027-body">
        '.$antetituloquote.'

        <p class="quote-especial-2027-texto">'.$content.'</p>

        '.$nombrequote.'

        '.$cargoquote.'

        '.$ficherocoquote2027.'
	</blockquote>
	';


	return $html;
}
add_shortcode( 'voc_quote_especial_2027', 'create_voc_quote_especial_2027_shortcode' );


if ( function_exists( 'vc_add_shortcode_param' ) ) {
    vc_add_shortcode_param( 'voc_attach_file', 'voc_attach_file_settings_field',  get_template_directory_uri().'/addons/js/vc-file-param.js', __FILE__ );
}

function voc_attach_file_settings_field( $settings, $value ) {
    $dependency = '';
    $output = '<div class="voc_attach_file_block">';
        $output .= '<input type="hidden" class="wpb_vc_param_value wpb-text-input ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '" />';

        // Mostrar la URL actual del archivo si existe
        $file_url = $value ? wp_get_attachment_url( $value ) : '';
        $output .= '<div class="voc-file-preview" style="margin-bottom: 10px; word-break: break-all;"><strong>' . __('Archivo seleccionado:', 'textdomain') . '</strong> <span>' . esc_html( $file_url ? $file_url : __('Ningún archivo seleccionado', 'textdomain') ) . '</span></div>';

        $output .= '<input type="button" class="button voc_upload_file_btn" value="' . __('Seleccionar / Subir Fichero', 'textdomain') . '" />';
        $output .= '<input type="button" class="button voc_remove_file_btn" value="' . __('Eliminar', 'textdomain') . '" style="margin-left: 5px; ' . (empty($value) ? 'display:none;' : '') . '" />';
    $output .= '</div>';

    return $output;
}


add_action( 'vc_before_init', 'voc_quote_especial_2027_integrateWithVC' );
function voc_quote_especial_2027_integrateWithVC() {
	vc_map( array(
		'name' => __( 'Quote Especial 2027', 'textdomain' ),
		'base' => 'voc_quote_especial_2027',
		'show_settings_on_create' => true,
		'category' => __( 'Vocento 2027', 'textdomain'),
		'params' => array(
      array(
        			'type' => 'attach_image',
        			'holder' => 'div',
        			'class' => '',
        			'admin_label' => true,
        			'heading' => __('Imagen', 'textdomain'),
        			'param_name' => 'imagequote',
        		),
		  array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Antetítulo', 'textdomain'),
                'param_name' => 'antetituloquote',
            ),
      array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Texto', 'textdomain'),
                'param_name' => 'content',
            ),
      array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Nombre', 'textdomain'),
                'param_name' => 'nombrequote',
            ),
      array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Cargo', 'textdomain'),
                'param_name' => 'cargoquote',
            ),
      array(
        				'type' => 'voc_attach_file',
        				'holder' => 'div',
        				'class' => '',
        				'admin_label' => true,
        				'heading' => __('Fichero Adjunto', 'textdomain'),
        				'param_name' => 'ficherocoquote2027',
        				'description' => __('Selecciona un archivo, documento o cualquier otro formato.', 'textdomain'),
        			)
		)
	) );
}
