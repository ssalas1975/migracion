<?php

function create_voc_logos_financieros_shortcode($atts) {
	$atts = shortcode_atts(
		array(
			'items_imagen_href' => '',
			'textologofinanciero' => '',
			'textenlacefinanciadores' => '',
			'enlacefinanciadores' => ''
		),
		$atts,
		'voc_logos_financieros'
	);

	$output = '';
	if ( ! empty( $atts['items_imagen_href'] ) ) {
      $items = vc_param_group_parse_atts( $atts['items_imagen_href'] );
      if ( is_array( $items ) && count( $items ) > 0 ) {
          foreach ( $items as $item ) {
              $img_id  = isset( $item['imagen_item'] ) ? $item['imagen_item'] : '';
              $enlace  = isset( $item['enlace_item'] ) ? $item['enlace_item'] : '#';

              if ( $img_id ) {
                  $img_url = wp_get_attachment_image_url( $img_id, 'medium' );
                  $output .= '<a href="' . esc_url( $enlace ) . '" target="_blank" rel="noopener">';
                  $output .= '<img src="' . esc_url( $img_url ) . '" alt="" />';
                  $output .= '</a>';
              }
          }
      }
  }

  $enlacefinanciadores = '';
  if($atts['enlacefinanciadores'] != ''){
    $enlacefinanciadores = $atts['enlacefinanciadores'];
  }

  $textenlacefinanciadores = '';
  if($atts['textenlacefinanciadores'] != ''){
    $textenlacefinanciadores = '<a class="logos-financiadores-2027-enlace" href="'.$enlacefinanciadores.'" target="_blank" rel="noopener noreferrer">'.$atts['textenlacefinanciadores'].'</a>';
  }

  $textologofinanciero = '';
	if($atts['textologofinanciero'] != ""){
	  $textologofinanciero = $atts['textologofinanciero'];
	}else{
	  $textologofinanciero = "Co-financiado por:";
	}


	$html = '
  	<aside class="logos-financiadores-2027">
      <!-- Texto editable. Por defecto: "Co-financiado por:" -->
      <p class="logos-financiadores-2027-texto">'.$textologofinanciero.'</p>
      <!-- Mapeo: un <img> por cada logo que añada el usuario -->
      <div class="logos-financiadores-2027-fila">
          '.$output.'
      </div>
      <!-- Enlace opcional. Vacío por defecto; si el usuario lo rellena, poner href y texto. Si no, no pintar. -->
      '.$textenlacefinanciadores.'
  	</aside>
	';


	return $html;
}
add_shortcode( 'voc_logos_financieros', 'create_voc_logos_financieros_shortcode' );


add_action( 'vc_before_init', 'voc_logos_financieros_integrateWithVC' );
function voc_logos_financieros_integrateWithVC() {
	vc_map( array(
		'name' => __( 'Logos Financiadores', 'textdomain' ),
		'base' => 'voc_logos_financieros',
		'show_settings_on_create' => true,
		'category' => __( 'Vocento 2027', 'textdomain'),
		'params' => array(
			array(
          'type' => 'textfield',
          'holder' => 'div',
          'class' => '',
          'admin_label' => true,
          'heading' => __('Texto Cabecera', 'textdomain'),
          'param_name' => 'textologofinanciero',
      ),
      // GRUPO REPETIBLE: PARES DE IMAGEN + HREF
			array(
				'type' => 'param_group',
				'heading' => __('Enlaces con Imagen', 'textdomain'),
				'param_name' => 'items_imagen_href',
				'description' => __('Añade tantos pares de imagen y enlace como necesites.', 'textdomain'),
				'value' => '',
				'params' => array(
					array(
						'type' => 'attach_image',
						'heading' => __('Imagen', 'textdomain'),
						'param_name' => 'imagen_item',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __('Enlace (HREF)', 'textdomain'),
						'param_name' => 'enlace_item',
						'description' => __('Introduce la URL de destino (ej: https://tuweb.com)', 'textdomain'),
						'admin_label' => true,
					),
				),
			),
			array(
          'type' => 'textfield',
          'holder' => 'div',
          'class' => '',
          'admin_label' => true,
          'heading' => __('Text Enlace Financiadores', 'textdomain'),
          'param_name' => 'textenlacefinanciadores',
      ),
			array(
          'type' => 'textfield',
          'holder' => 'div',
          'class' => '',
          'admin_label' => true,
          'heading' => __('Enlace financiadores', 'textdomain'),
          'param_name' => 'enlacefinanciadores',
      ),
		)
	) );
}
