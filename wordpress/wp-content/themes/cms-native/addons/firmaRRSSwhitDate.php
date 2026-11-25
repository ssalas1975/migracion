<?php

function create_firmarssfecha_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'postauthor' => '',
			'authorimage' => '',
			'fecha' => '',
			'el_class' => '',
		),
		$atts,
		'firma_rss_fecha'
	);
	// Attributes in var
	$postauthor = $atts['postauthor'];
	$authorimage = $atts['authorimage'];
	$fecha = $atts['fecha'];
	$el_class = $atts['el_class'];


	// Output Code
	if (isset($atts['el_class'])) {
        $clasePerso = $atts['el_class'];
    } else {
        $clasePerso = "";
    }
    //firma autor
    if (isset($atts['postauthor'])) {
        $autorfirma = $atts['postauthor'];
    } else {
        $autorfirma = 'Content Factory' ;
    }
    //añadiendo metadatos para relacionados
    if (! add_post_meta(get_the_ID(), 'autor', $autorfirma, true)) {
        update_post_meta(get_the_ID(), 'autor', $autorfirma);
    }

    if (! add_post_meta(get_the_ID(), 'image_url', $atts['authorimage'], true)) {
        update_post_meta(get_the_ID(), 'image_url', $atts['authorimage']);
    }

    //obteniendo imagen
    $imgAutor="";
    if (isset($atts['authorimage'])) {
        $imgAutor = '<img src="'.wp_get_attachment_url($atts['authorimage']).'">';
    }

   if($fecha == ""){
	   $fecha = get_the_modified_date('j F, Y') .' - '. get_the_modified_time('G:i');
	   if (!isset($fecha)) {
		   $fecha = get_the_time('j F, Y') .' - '. get_the_time('G:i') ;
	   }
   }else{
	$timestamp = strtotime($fecha); 
	$timezone = new DateTimeZone( 'UTC' );
	$fecha = wp_date( 'j F, Y - G:i', $timestamp, $timezone );
   }


    //fecha de modificación o creación si es nuevo

    $url = preg_replace("/content-factory./", '', $_SERVER['HTTP_HOST']);
    // $permalink = get_the_permalink();
	$permalink = str_replace("https://ms-brandedcontent.srv.vocento.in", "/contentfactory/post", get_the_permalink());
    return "<div class='firmaRRSS ".$clasePerso."'>
       <div class='info'><address class='firmaAutor' data-autor='".$autorfirma."'>".$imgAutor." ". $autorfirma ."</address><time>
         ".$fecha."</time></div>
           <div class='compartir'><p>Compartir</p><ul>
                <li class='facebook'>
                            <a href='https://www.facebook.com/sharer/sharer.php?u=https://www.".$url.$permalink."' data-voc-vtm-id='compartirrrss' data-voc-vtm-info='facebook' target='_blank' title='Compartelo en Facebook' rel='nofollow'>
                        <i class='facebook'></i>
                    </a>
                </li><li class='twitter'>
                    <a href='https://twitter.com/intent/tweet?text=https://www.".$url.$permalink."' ata-voc-vtm-id='compartirrrss' data-voc-vtm-info='twitter' class='twitter' target='_blank' title='Compartelo en Twitter' rel='nofollow'>
                        <i class='twitter'></i>
                    </a>
                </li><li class='whatsapp'>
                    <a href='whatsapp://send?text=https://www.".$url.$permalink."' data-voc-vtm-id='compartirrrss' data-voc-vtm-info='whatsapp' class='whatsapp' target='_blank' title='Compartelo en whatsapp' rel='nofollow'>
                        <i class='whatsapp'></i>
                    </a>
                </li>
    </ul></div></div>";
}
add_shortcode( 'firma_rss_fecha', 'create_firmarssfecha_shortcode' );


vc_add_shortcode_param( 'dateselect', 'firmarss_dateselect_field' );
function firmarss_dateselect_field( $settings, $value) {
	return '
    <input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['param_name'] ) . ' ' .
    esc_attr( $settings['type'] ) . '_field inputcontenido" placeholder="dd-mm-yyyy" type="datetime-local" value="' . esc_attr( $value ) . '" />';
}



// Create Vocento Firma RRSS con Fecha element for Visual Composer
add_action( 'vc_before_init', 'firmarssfecha_integrateWithVC' );
function firmarssfecha_integrateWithVC() {
	vc_map( array(
		'name' => __( 'Vocento Firma RRSS con Fecha', 'textdomain' ),
		'base' => 'firma_rss_fecha',
		'show_settings_on_create' => true,
		'category' => __( 'Vocento', 'textdomain'),
		'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'admin_label' => true,
				'heading' => __( 'Autor del Post', 'textdomain' ),
				'param_name' => 'postauthor',
			),
			array(
				'type' => 'attach_image',
				'holder' => 'div',
				'class' => '',
				'admin_label' => true,
				'heading' => __( 'Imagen Autor', 'textdomain' ),
				'param_name' => 'authorimage',
			),
			array(
				'type' => 'dateselect',
				'holder' => 'div',
				'class' => '',
				'admin_label' => true,
				'heading' => __( 'Fecha', 'textdomain' ),
				'param_name' => 'fecha',
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'admin_label' => true,
				'heading' => __( 'Nombre de la clase extra', 'textdomain' ),
				'param_name' => 'el_class',
			),
		)
	) );
}