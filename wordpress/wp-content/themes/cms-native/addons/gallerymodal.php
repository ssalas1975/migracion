<?php

function create_gallerymodal_shortcode($atts, $content) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'imagen' => '',
			'titular' => '',
			'contenido' => '',
			'textfield' => '',
		),
		$atts,
		'gallerymodal'
	);
	// Attributes in var
	$imagen = $atts['imagen'];
	$titular = $atts['titular'];
	$ordenid = $atts['textfield'];

	$content = explode(PHP_EOL . PHP_EOL, apply_filters('the_content', $content));
	$htmlcontent = '';
    foreach($content as $line){
        $htmlcontent .= str_replace(PHP_EOL, '' , $line);
    }
    $htmlcontent = preg_replace("/[\r\n|\n|\r]+/", '', $htmlcontent);
	$htmlcontent = str_replace('"', "'", $htmlcontent);
	$htmlcontent2 = "<div>".$ordenid."</div><div><h2>".$titular."</h2>$htmlcontent</div>";

	// Output Code
	$output = '
		<div class="foto" itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
			<a id="'.$ordenid.'" href="'.wp_get_attachment_image_src($imagen, "full")[0].'" class="fondo_img">
				<figure class="imgarticle"><img src="'.wp_get_attachment_image_src($imagen, "full")[0].'" alt="'.$htmlcontent2.'" class="horizontal"></figure>
			</a>
			<p class="foto-numero">'.$ordenid.'</p>
			<h2 class="foto-titulo">'.$titular.'</h2>
			<p class="foto-texto">'.$htmlcontent.'</p>
		</div>
	';

	return $output;
}
add_shortcode( 'gallerymodal', 'create_gallerymodal_shortcode' );

vc_add_shortcode_param( 'order_id', 'order_id_field', get_template_directory_uri().'/js/orden_id.js');
function order_id_field( $param, $value ) {
	$param_line = '';
	$param_line .= "<input type='hidden' id='ordenid' value='".$value."' name='".esc_attr( $param["param_name"])."' class='wpb_vc_param_value wpb-input wpb-select ". esc_attr( $param["param_name"] )." ". esc_attr($param["type"])." contentvalues idpost'>";
   return  $param_line;
}