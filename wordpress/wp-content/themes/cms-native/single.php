<?php
$apiheaderdate = get_option('apiheaderdate');

global $wp_query;
$id = $wp_query->post->ID;

$date1 = new DateTime($apiheaderdate);
$date2 = new DateTime(get_the_time('Y-m-d', $id));

// if($id == 53353){
// 	get_template_part( 'partials/single', '2024' );
// }else{
// 	get_template_part( 'partials/single', '' );
// }
if ($date1 < $date2) {
	$url_medio2 = $_SERVER['HTTP_HOST'];

	$re2 = '/(.+?)\./s';
	preg_match($re2, $url_medio2, $matches, PREG_OFFSET_CAPTURE, 0);
	$tld = str_replace($matches[0][0], "", $_SERVER['HTTP_HOST']);
	$id_krux = '';
	$medios = array('eleconomista.es', 'eslang.es', 'finanzas.com', 'lavozdegalicia.es', 'menorca.info', 'mujerhoy.com', 'periodicodeibiza.es', 'ultimahora.es', 'welife.es', 'womennow.es', 'xlsemanal.com', 'relevo.com', 'sevilla.abc.es', 'turium.es');


	$pos = strpos($url_medio2, 'abc.es');

	if (in_array($tld, $medios)) {
	  echo 'entro en lo bueno';
		get_template_part('partials/single', '');
	} else {
		if ($id == 638) {
			get_template_part('partials/single', '2026');
		} else {
			get_template_part('partials/single', '2024');
		}
		// if ($pos !== false) {
		// 	if ($id == 638) {
		// 		get_template_part('partials/single', '2026');
		// 	} else {
		// 		get_template_part('partials/single', '2024');
		// 	}
		// } else {
		// 	get_template_part('partials/single', '2024');
		// }
	}
} else {
	get_template_part('partials/single', '');
}
