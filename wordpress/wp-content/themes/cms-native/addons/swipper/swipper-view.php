<?php

// Create Shortcode swipper
// Use the shortcode: [swipper swipperslider=""]
function create_swipper_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'swipperslider' => '',
		),
		$atts,
		'swipper'
	);
	// Attributes in var
	$swipperslider = explode(',',$atts['swipperslider']);

	$output = '';
	$output .= '<!-- ADDONS SWIPPER SLIDER ESTE ADDONS EN PRINCIPIO YA ESTA HECHO -->
				<div class="foto swiper-container"><div class="swiper-wrapper">';
	foreach( $swipperslider as $idslider ){
		$sizeHuge = 'scaled';
		$image = get_post($idslider);
		$imageGuid = str_replace("https://ms-brandedcontent.srv.vocento.in", "", $image->guid);
		// $output .= '<div class="swiper-slide"><img src="/contentfactory/post'.$imageGuid.'"><p>'.$image->post_content.'</p></div>';
		$output .= '<div class="swiper-slide">'.wp_get_attachment_image( $image->ID, $sizeHuge ).'<p>'.$image->post_content.'</p></div>';
	}

	$output .= '</div><!-- Add Pagination -->
	<div class="swiper-button-next">avanti</div>
	<div class="swiper-button-prev">indietro</div>
	<div class="swiper-pagination"></div>
	</div>
	<!-- FIN ADDONS SLIDER -->';

	return $output;
}
add_shortcode( 'swipper', 'create_swipper_shortcode' );


?>