<?php

// Create Shortcode hero_2027
// Use the shortcode: [hero_2027 htmlposttype=""]
function create_hero_2027_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'gcsponsor2027' => '',
			'gctitulo2027' => '',
			'gcentrada2027' => '',
			'gcpieentrada2027' => '',
			'gcimgfirma2027' => '',
			'gcnombrefirma2027' => '',
			'gcfechafirma2027' => '',
		),
		$atts,
		'hero_2027'
	);


	$gcsponsor2027 = $atts['gcsponsor2027'];
	if($gcsponsor2027){
    $gcsponsor2027 = '<p class="gc-sponsor2027">'.$gcsponsor2027.'</p>';
	}else{
	  $gcsponsor2027 = '';
	}
	$gctitulo2027 = $atts['gctitulo2027'];
	$gcentrada2027 = $atts['gcentrada2027'];
	$gcpieentrada2027 = $atts['gcpieentrada2027'];
	$gcimgfirma2027 = $gcimagen2027 = wp_get_attachment_url($atts['gcimgfirma2027']);
	$gcnombrefirma2027 = $atts['gcnombrefirma2027'];
	$gcfechafirma2027 = $atts['gcfechafirma2027'];


	$host = str_replace('content-factory', 'https://www', $_SERVER[HTTP_HOST]);
	$actual_link = $host.'/contentfactory/post'.$_SERVER[REQUEST_URI];


	global $post;
	$gcimagencontainer2027 = '';
	$imgOpen = get_post_meta($post->ID, '_webdados_fb_open_graph_specific_image', true);
  $imgOpen = str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', $imgOpen);
  if($imgOpen){
    $gcimagencontainer2027 = $imgOpen;
  }else{
    $gcimagencontainer2027 = "https://www.abc.es/contentfactory/post/wp-content/uploads/2026/02/no-image.webp";
  }


	$html = '
	<section class="gc-hero2027">
    <div class="gc-hero2027-wrap">
        <div class="gc-entrada2027-container">
            <!-- este contenido sera opcional, si lo mete se pinta, si no, no se pinta -->
            '.$gcsponsor2027.'
            <!-- este contenido es obligatorio -->
            <h2 class="gc-titulo2027">'.$gctitulo2027.'</h2>
            <p class="gc-entrada2027">'.$gcentrada2027.'</p>
            <!-- este contenido va con la creacion de la url del sharer, el usuario no necesita meter nada -->
            <div class="gc-sharer">
                <p class="gc-sharer-text">Compartir</p>
                <div class="line-sharer"></div>
                <a class="gc-facebook"
                    href="https://www.facebook.com/sharer/sharer.php?u='.$actual_link.'"
                    target="_blank" rel="nofollow" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M12.9619 5.5H14.5V6.83301H13.7783C13.2732 6.83304 12.8111 6.90791 12.5029 7.21875C12.1901 7.53437 12.167 7.96506 12.167 8.27734V10.167H14.4375L14.2793 11.5H12.167V18.5H10.1406V11.5H8.5V10.167H10.1406V8.11035C10.1406 7.18471 10.3364 6.5635 10.7227 6.16699C11.1071 5.77237 11.7783 5.50001 12.9619 5.5Z" />
                    </svg>
                </a>
                <a class="gc-twitter"
                    href="https://www.twitter.com/sharer/sharer.php?u='.$actual_link.'"
                    target="_blank" rel="nofollow" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M13.33 10.9283L18.5436 5H17.3088L12.7833 10.1471L9.1685 5H5L10.4656 12.7833L5 19H6.2348L11.0137 13.5645L14.8315 19H19L13.33 10.9283ZM11.6402 12.8519L11.0865 12.077L6.68 5.91H8.577L12.133 10.887L12.6867 11.6619L17.3067 18.1313H15.4097L11.6402 12.8526V12.8519Z" />
                    </svg>
                </a>
                <a class="gc-whatsapp"
                    href="whatsapp://send?text='.$actual_link.'"
                    target="_blank" rel="nofollow" aria-label="WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 797 800" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M680.384 116.258C605.517 41.328 505.95 0.0421153 399.873 0C181.302 0 3.41242 177.821 3.32494 396.393C3.29579 466.26 21.5565 534.461 56.2562 594.576L0 800L210.216 744.875C268.134 776.455 333.346 793.098 399.714 793.127H399.877C618.425 793.127 796.33 615.282 796.419 396.71C796.461 290.786 755.253 191.187 680.384 116.258ZM399.877 726.173H399.742C340.601 726.15 282.593 710.267 231.99 680.247L219.954 673.106L95.2107 705.818L128.508 584.233L120.67 571.768C87.6775 519.307 70.2515 458.673 70.2774 396.418C70.3497 214.75 218.206 66.9525 400.008 66.9525C488.04 66.9859 570.795 101.303 633.023 163.582C695.251 225.861 729.501 308.644 729.468 396.684C729.392 578.365 581.538 726.173 399.877 726.173ZM580.665 479.406C570.756 474.448 522.042 450.484 512.96 447.179C503.878 443.873 497.273 442.221 490.667 452.138C484.062 462.053 465.073 484.364 459.292 490.974C453.513 497.585 447.734 498.413 437.825 493.454C427.917 488.496 395.991 478.037 358.145 444.291C328.691 418.029 308.804 385.589 303.025 375.674C297.245 365.757 302.41 360.395 307.37 355.458C311.827 351.02 317.278 343.889 322.232 338.105C327.186 332.321 328.837 328.189 332.139 321.579C335.443 314.968 333.792 309.183 331.314 304.226C328.837 299.268 309.022 250.513 300.765 230.681C292.723 211.367 284.553 213.98 278.471 213.678C272.698 213.39 266.086 213.329 259.482 213.329C252.876 213.329 242.142 215.808 233.059 225.724C223.978 235.64 198.382 259.604 198.382 308.357C198.382 357.11 233.885 404.21 238.84 410.821C243.793 417.432 308.707 517.482 408.101 560.387C431.741 570.592 450.197 576.685 464.586 581.253C488.323 588.79 509.923 587.726 526.996 585.176C546.032 582.333 585.617 561.214 593.875 538.076C602.132 514.939 602.132 495.107 599.654 490.975C597.178 486.844 590.573 484.364 580.665 479.406Z" />
                    </svg>
                </a>
                <div class="line-sharer"></div>
            </div>
            <!-- esta imagen molaria que se guardara como opengraph en caso de que no rellenen el apartado de opengraph de abajo si es posible-->
            <figure class="gc-imagencontainer2027">
                <img src="'.$gcimagencontainer2027.'"
                    alt="imagen de entrada 2027">
                <figcaption class="gc-pie-entrada2027">'.$gcpieentrada2027.'</figcaption>
            </figure>
        </div>
        <!-- este contenido es obligatorio, foto, autor y fecha -->
        <div class="gc-firmacontainer2027">
            <div class="firma2027">
                <img src="'.$gcimgfirma2027.'"
                    alt="firma de entrada 2027">
                <div class="line-sharer"></div>
                <div class="firma2027-info">
                    <p class="gc-nombre-firma2027">'.$gcnombrefirma2027.'</p>
                    <p class="gc-fecha-firma2027">'.$gcfechafirma2027.'</p>
                </div>
            </div>
        </div>
    </div>
	</section>
	';


	return $html;
}
add_shortcode( 'hero_2027', 'create_hero_2027_shortcode' );

add_action( 'vc_before_init', 'hero_2027_integrateWithVC' );
function hero_2027_integrateWithVC() {
	vc_map( array(
		'name' => __( 'Hero 2027', 'textdomain' ),
		'base' => 'hero_2027',
		'show_settings_on_create' => true,
		'category' => __( 'Vocento 2027', 'textdomain'),
		'params' => array(
			array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Sponsor', 'textdomain'),
                'param_name' => 'gcsponsor2027',
            ),
      array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Titulo', 'textdomain'),
                'param_name' => 'gctitulo2027',
            ),
      array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Entrada', 'textdomain'),
                'param_name' => 'gcentrada2027',
            ),
      array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Pie Entrada', 'textdomain'),
                'param_name' => 'gcpieentrada2027',
            ),
      array(
                'type' => 'attach_image',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Imagen Firma', 'textdomain'),
                'param_name' => 'gcimgfirma2027',
            ),
      array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Nombre Firma', 'textdomain'),
                'param_name' => 'gcnombrefirma2027',
            ),
      array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Fecha Firma', 'textdomain'),
                'param_name' => 'gcfechafirma2027',
            ),
		)
	) );
}
