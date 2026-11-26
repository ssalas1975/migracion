<?php
/*
Plugin Name: Imprime tu Portada en PDF
Description: A través del dorsal, se obtiene el resultado de la carrera y se puede imprimir tu portada de LA VERDAD personalizada en un PDF
Version: 2.0
Author: Raúl Gómez
Author URI: http://www.lasprovincias.es
License: GPL2
*/

/*
 * Mejoras: pagina configuracion de plugin:
 *  - subida de csv con los participantes
 *  - subida de img, plantilla del pdf
 *  - ¿evento analitics para controlar descargas?
 * */


/* funciones para administracion */
add_action("admin_menu","lpportadapdf_plugin_menu");
add_action("admin_init", "lpportadapdf_plugin_init");


function lpportadapdf_plugin_menu(){
	add_options_page("Configuración Tu Portada en PDF", "Tu Portada PDF",'manage_options',"lpportadapdf","tuportadapdf_settingPage");
}

function lpportadapdf_get_default_options() {
	$options = array(
		'filecsv' => '',
		'filejpg' => ''
	);
	return $options;
}

function lpportadapdf_plugin_init(){

	register_setting("lpportadapdf","lpportadapdf_options","lpportadapdf_options_validate");

// Add a form section for the csv
	add_settings_section('lpportadapdf_settings_csv_header', 'Subida de Fichero CSV de Tiempos', 'lpportadapdf_settings_csv_header_text', 'lpportadapdf');
	// Add csv uploader
	add_settings_field('lpportadapdf_settings_csv',  "Fichero CSV", 'lpportadapdf_settings_csv', 'lpportadapdf', 'lpportadapdf_settings_csv_header');


// Add a form section for the jpg template
	add_settings_section('lpportadapdf_settings_jpg_header', 'Subida de Fichero JPG Plantilla', 'lpportadapdf_settings_jpg_header_text', 'lpportadapdf');
	// Add csv uploader
	add_settings_field('lpportadapdf_settings_jpg',  "Fichero JPG", 'lpportadapdf_settings_jpg', 'lpportadapdf', 'lpportadapdf_settings_jpg_header');

}

function lpportadapdf_settings_csv_header_text() {
	?>
	<p><?php _e( 'Gestión de Fichero CSV de Tiempos de la Carrera', 'lpportadapdf' ); ?></p>
	<?php
}

function lpportadapdf_settings_csv() {
	$lpportadapdf_options = get_option( 'lpportadapdf_options' );
	?>
	<input type="file" name="filecsv" value="<?php //echo get_option("rgh-number-of-items"); ?>" /><br/>
	<small>Fichero CSV con los tiempos de la carrera</small>
	<!-- input type="text" id="filecsv_url" name="lpportadapdf_options[filecsv]" value="<?php echo esc_url( $lpportadapdf_options['filecsv'] ); ?>" / -->
	<?php
	if ($lpportadapdf_options['filecsv'] ) {
		?>
		<br/><a target="_blank" href="<?php echo esc_url( $lpportadapdf_options['filecsv'] ); ?>">Descargar csv actual</a>
		<?php
	}
}

function lpportadapdf_settings_jpg_header_text() {
	?>
	<p><?php _e( 'Gestión de Fichero JPG Plantilla del PDF', 'lpportadapdf' ); ?></p>
	<?php
}
function lpportadapdf_settings_jpg() {
	$lpportadapdf_options = get_option( 'lpportadapdf_options' );
	?>
	<input type="file" name="filejpg" value="<?php //echo get_option("rgh-number-of-items"); ?>" /><br/>
	<small>Imagen de tamaño folio que se utilizará como plantilla del PDF</small>
	<!-- input type="text" id="filejpg_url" name="lpportadapdf_options[filejpg]" value="<?php echo esc_url( $lpportadapdf_options['filejpg'] ); ?>" / -->
	<?php
	if ($lpportadapdf_options['filejpg'] ){
	?>
		<br/><a target="_blank" href="<?php echo esc_url( $lpportadapdf_options['filejpg'] ); ?>">Ver imagen actual</a>
	<?php
	}
}

function lpportadapdf_options_validate($plugin_options) {
	$keys = array_keys($_FILES);
	$i = 0;
	foreach ( $_FILES as $nom => $file ) {
		//print_r($_FILES);
		//print_r("sin cabeza");
		// if a files was upload
		if ($file['size']) {
			if ($nom=="filecsv") {
				//     // if it is an image
				if ( preg_match( '/(csv|ms-excel)$/', $file['type'] ) ) {
					//$override = array( 'test_form' => false );
					$override = array( 'test_form' => false, 'test_type' => false );
					// save the file, and store an array, containing its location in $file
					$fileupload                    = wp_handle_upload( $file, $override );
					$plugin_options[ $keys[ $i ] ] = $fileupload['url'];
					//print_r("DENTRO ");
				} else {       // Not an image.
					$options                       = get_option( 'lpportadapdf_options' );
					$plugin_options[ $keys[ $i ] ] = $options["filecsv"];
					// Die and let the user know that they made a mistake.
					//print_r("muriendo...");
					wp_die( 'No image was uploaded.' );
				}
			}
			if ($nom=="filejpg") {
				//     // if it is an image
				if ( preg_match( '/(jpg|jpeg|png|gif)$/', $file['type'] ) ) {
					$override = array( 'test_form' => false );
					// save the file, and store an array, containing its location in $file
					$fileupload                    = wp_handle_upload( $file, $override );
					$plugin_options[ $keys[ $i ] ] = $fileupload['url'];
				} else {       // Not an image.
					$options                       = get_option( 'lpportadapdf_options' );
					$plugin_options[ $keys[ $i ] ] = $options["filejpg"];
					// Die and let the user know that they made a mistake.
					wp_die( 'No image was uploaded.' );
				}
			}
		}   // Else, the user didn't upload a file. Retain the image that's already on file.
		else {
			$options = get_option('lpportadapdf_options');
			$plugin_options[$keys[$i]] = $options[$keys[$i]];
			//print_r("sin tamaño");
		}
		$i++;
	}
	return $plugin_options;
}

function tuportadapdf_settingPage(){
	?>
	<div class="wrap">
		<h2>Configuración Tu Portada en PDF</h2>
		<form action="options.php" method="post" enctype="multipart/form-data">
			<?php //settings_fields("lpportadapdf-group");?>
			<?php //@do_settings_fields("lpportadapdf-group");?>
			<?php
			settings_fields('lpportadapdf');
			//@do_settings_fields("lpportadapdf");
			do_settings_sections('lpportadapdf');
			//@do_settings_fields("lpportadapdf_options");
			?>

			<p class="submit">
				<input name="lpportadapdf_options[submit]" id="submit_options_form" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'lpportadapdf'); ?>" />
				<input name="lpportadapdf_options[reset]" type="submit" class="button-secondary" value="<?php esc_attr_e('Reset Defaults', 'lpportadapdf'); ?>" />
			</p>

		</form>
		<hr/>
	</div>

	<?php
}

add_filter('upload_mimes', 'my_upload_mimes');

function my_upload_mimes ( $existing_mimes=array() ) {
    $existing_mimes['csv'] = 'text/csv';
    return $existing_mimes;
}

/**************************** funciones front-end ************************/

require_once("virtual-themed-pages.php");

add_action("wp","lpportadapdf_init");

// Según el contexto en tiempo de ejecución. O Admin
add_action("wp_ajax_lpportadapdf_dorsal","lpportadapdf_dorsal_process");
add_action("wp_ajax_lpportadapdf_imprimir","lpportadapdf_imprimir_process");
// o anonimo.
add_action("wp_ajax_nopriv_lpportadapdf_dorsal","lpportadapdf_dorsal_process");
add_action("wp_ajax_nopriv_lpportadapdf_imprimir","lpportadapdf_imprimir_process");


function lpportadapdf_init(){
	wp_register_script("tuportadapdf",plugins_url('/lpportadapdf.js',__FILE__),array('jquery'));
	wp_enqueue_script('jquery');
	wp_enqueue_script('tuportadapdf');

	//global $post;
	$upload_dir = wp_upload_dir();
	wp_localize_script("tuportadapdf",'LPPortadaPDF',array('baseurl' => $upload_dir["baseurl"],
                                                           //'ajaxurl' => "/wp-content/ajaxrewrite.php",
                                                            'ajaxurl' => "/wp-admin/admin-ajax.php",
	                                                       'nonce-dorsal'     => wp_create_nonce( 'lpportadapdf-dorsal-nonce'),
	                                                       'nonce-imprimir'     => wp_create_nonce( 'lpportadapdf-imprimir-nonce')));
}

/**
 * Buscador por dorsal.
 *
 * Genera una tabla con los datos de la carrera del corredor
 * cuyo dorsal se le pasa por ajax.
 *
 */
function lpportadapdf_dorsal_process(){
	$nonce = $_POST['nonce-dorsal']; // Assign a local variable for nonce

	if ( ! wp_verify_nonce( $nonce, 'lpportadapdf-dorsal-nonce' ) ) { // If the nonce doesn't check out, fail the request
		wp_die( 'Your request could not be authenticated' ); // Error message for unauthenticated request
	}

	$lpportadapdf_options = get_option( 'lpportadapdf_options' );
	//$f = @fopen(esc_url( $lpportadapdf_options['filecsv'] ), "r");
	$f = @fopen(ABSPATH . $lpportadapdf_options['filecsv'] , "r");

	$keys = fgetcsv($f);
	while (!feof($f)) {
		$values=fgetcsv($f);
		$array1[] = array_combine((array)$keys, (array)$values);
	}
	function findInt($array, $find) {
		$result=array();
		foreach ($array as $row) {
			if (array_intersect_assoc((array)$row, (array)$find) == $find) {
				$result[] = $row;
				break;
			}
		}
		return $result;
	}
	if ($_POST["porDorsal"] && $_POST["Dorsal"]){
		$dorsal=mysql_escape_string($_POST["Dorsal"]);
		$result=findInt($array1,array('Dorsal'=>"$dorsal"));
		if (count($result)==0) {
			print "<blockquote><strong>Tus datos</strong></blockquote><code>Dorsal no encontrado. Prueba de nuevo</code>";
		}
		else {
			print "<blockquote><strong>Datos corredor</strong></blockquote>";
			print "<table class='table table-hover table-condensed table-responsive'>";
			print "<tr><td>Nombre</td><td>".$result[0]["Nombre"]."</td></tr>";
			print "<tr><td>Dorsal</td><td>".$result[0]["Dorsal"]."</td></tr>";
			print "<tr><td>Club</td><td>".$result[0]["Club"]."</td></tr>";
			print "<tr><td>Categoría</td><td>".$result[0]["Categoria"]."</td></tr>";
			print "<tr><td>Posición</td><td>".$result[0]["Posicion"]."</td></tr>";
			print "<tr><td>Tiempo Oficial</td><td>".$result[0]["T. Oficial"]."</td></tr>";
			print "<tr><td>Ritmo</td><td>".$result[0]["Ritmo"]."</td></tr>";
			print "</table>";
		}
	}
	wp_die();
}

/**
 * Genera un pdf personalizado con la portada del periodico.
 *
 * Genera un pdf personalizado con la portada del periodico y los datos de la
 * carrera del corredor cuyo dorsal se le pasa por ajax.
 *
 */
function lpportadapdf_imprimir_process(){
	$nonce = $_POST['nonce-imprimir']; // Assign a local variable for nonce

	if ( ! wp_verify_nonce( $nonce, 'lpportadapdf-imprimir-nonce' ) ) { // If the nonce doesn't check out, fail the request
		exit( 'Your request could not be authenticated' ); // Error message for unauthenticated request
	}

	$lpportadapdf_options = get_option( 'lpportadapdf_options' );

	//$f = @fopen(esc_url( $lpportadapdf_options['filecsv'] ), "r");
    $f = @fopen(ABSPATH . $lpportadapdf_options['filecsv'] , "r");
	$keys = fgetcsv($f);
	while (!feof($f)) {
		$values=fgetcsv($f);
		$array1[] = array_combine((array)$keys, (array)$values);
	}
	function findInt($array, $find) {
		$result=array();
		foreach ($array as $row) {
			if (array_intersect_assoc((array)$row, (array)$find) == $find) {
				$result[] = $row;
				break;
			}
		}
		return $result;
	}
	function creaPDF($corredor) {
		require(dirname(__FILE__)."/fpdf/fpdf.php");

		$lpportadapdf_options = get_option( 'lpportadapdf_options' );

		// creaci?n del PDF
		$pdf=new FPDF();
		$pdf->AddPage();
		$pdf->AddFont('guardian-sans-black','B','guardian-sans-black.php'); // fuente de t?tulo
		$pdf->AddFont('guardian-sans-light','','guardian-sans-light.php');   // fuente de subt?tulo

		// Titulo del documento
		$pdf->SetCreator("laverdad.es");
		$pdf->SetTitle("Portada LA VERDAD", true);
		$pdf->SetAuthor("laverdad.es", true);
		$pdf->SetLineWidth(0);

		// Imagen de Portada
		//$pdf->Image(dirname(__FILE__).'/imagenes-portada/portada-pdf.jpg',0,5,210,285,'JPG');
		//$pdf->Image(esc_url( $lpportadapdf_options['filejpg'] ),0,5,210,285,'JPG');
		$pdf->Image(ABSPATH .  $lpportadapdf_options['filejpg'] ,0,5,210,285,'JPG');

		// Titulo
		$pdf->Ln(58);
		$pdf->SetFont('guardian-sans-black','B',30); //en el PDF tenemos Guardian Sans Nar....... ?????
		$pdf->MultiCell(190,10,utf8_decode($corredor[0]["Nombre"])." triunfa en la carrera de LA VERDAD",0,'C');

		// Subtitulo
		$texto=$corredor[0]["Nombre"].", con el dorsal número ".$corredor[0]["Dorsal"]." e integrante del club ".$corredor[0]["Club"].", ";
		$texto.="ha culminado la carrera organizada por LA VERDAD en tan solo ".$corredor[0]["T. Oficial"]. " a un ritmo de ".$corredor[0]["Ritmo"];
		$texto.=" en la categoría de ".$corredor[0]["Categoria"];
		$texto.=". En la clasificación general de la carrera, ha ocupado ";
		$texto.="el puesto número ".$corredor[0]["Posicion"];


		$pdf->Ln(4);
		$pdf->SetX(5);
		$pdf->SetFont('guardian-sans-light','',10); //en el PDF tenemos Guardian Sans Light ?????   ".$corredor[0]["Dorsal"]."
		$pdf->MultiCell(200,7,utf8_decode($texto),0,'J');
		$upload_dir = wp_upload_dir();
		$pdf->Output($upload_dir["basedir"]."/carrera-".$corredor[0]["Dorsal"].'.pdf','F');   // --> lo abre en PDF o lo guarda

	}
	if ($_REQUEST["imprimirDorsal"] && $_REQUEST["imprimir"]){
		$dorsal=mysql_escape_string($_REQUEST["imprimir"]);
		$result=findInt($array1,array('Dorsal'=>"$dorsal"));
		if (count($result)==0) {
			print "<blockquote><strong>Tus datos</strong></blockquote><code>Dorsal no encontrado. Prueba de nuevo</code>";
		}
		else {
			//generamos PDF
			creaPDF($result);
		}
	}
}

/**
 * Crea la pagina para tu-portada-pdf.
 *
 * Crea la pagina para tu-portada-pdf a partir de la clase Virtual_Themed_Pages_BC
 * Esta clase permite crear una pagina con un slug, titulo y cuerpo
 * De momento es fija en /tu-portada-pdf/
 *
 */
function crearPagePortadaPDF(){
    // require 'BC_Virtual_Themed_pages.php';
    // this code segment requires the WordPress environment /mypattern/unique
    $vp =  new Virtual_Themed_Pages_BC();
    $vp->add('#/tu-portada-pdf/#i', 'tu_portada_pdf');

    // Example of content generating function
    // Must set $this->body even if empty string
    function tu_portada_pdf($v, $url)
    {
        $v->title = "Tu Portada en PDF";
        $v->body = mybody();
        $v->slug='tu-portada-pdf';
        $v->template = 'page'; // optional
        //$v->subtemplate = 'billing'; // optional
    }

    function mybody(){

        $mibody = <<< END
		 <style>
		#imprimir {
    color: #FFF;
    background-color: #6DAB3C;
    border-radius: 5px;
	padding: 14px 20px;
	
		 }
		 blockquote, q {
    quotes: none!important;
}
blockquote {
 
    background-color: #ababab!important;
    background-color: rgba(219, 219, 219, 0.13)!important;
}

blockquote:before {
        color: rgb(0, 0, 0)!important;
}
code, pre {
	 color: rgb(0, 0, 0)!important;
}
	</style>
		 <blockquote><strong>1.- Introduce tu dorsal</strong></blockquote>
		<div class="row">
			<div class="col-xs-5"><input type="text" name="Dorsal" value="" id="Dorsal" class="form-control" placeholder="dorsal" /></div>
			<div class="col-xs-5"><input type="button" id="porDorsal" name="porDorsal" value="Enviar" class="btn btn-primary" /></div>
		<p style="color:#FFF">Si no recuerdas el número de tu dorsal, búscalo aquí: <a href="http://runrock.laverdad.es/clasificacion-5k/" target="_blank">Dorsales 5K</a> | <a href="http://runrock.laverdad.es/clasificacion-10k/" target="_blank">Dorsales 10K</a></p>
		</div>
		<div id="divimprimir"><blockquote><strong>2.- Imprime la portada</strong></blockquote>
			<div class="row">
				<div class="col-xs-10" align="center"><br><a href="#" id="imprimir" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-flat vc_btn3-color-green" target="_blank">Imprimir PDF</a><br>
				<a href="#" id="imprimirlink" target="_blank"></a></div>
			</div>
		</div>
		<form id="generaPDF" method="post">
			<input type="hidden" id="imprimirDorsal" name="imprimirDorsal" />  <input type="hidden" id="imprimir" name="imprimir" />
		</form><style type="text/css">#divimprimir{display:none;}</style><div id="datos" class="row col-xs-12">&nbsp;</div>
END;
        return $mibody;
    }
}

/**
 * Crea la pagina para Clasificacion.
 *
 * Crea la pagina para clasificacion a partir de la clase Virtual_Themed_Pages_BC
 * Esta clase permite crear una pagina con un slug, titulo y cuerpo.
 * De momento es fija en /clasificacion/
 *
 */
function crearPageClasificacion(){
    // require 'BC_Virtual_Themed_pages.php';
    // this code segment requires the WordPress environment /mypattern/unique
    $vp =  new Virtual_Themed_Pages_BC();
    $vp->add('#/clasificacion/#i', 'clasificacion');

    // Example of content generating function
    // Must set $this->body even if empty string
    function clasificacion($v, $url)
    {
        // extract an id from the URL
        /*		$id = 'none';
                if (preg_match('#unique/(\d+)#', $url, $m))
                    $id = $m[1];*/
        // could wp_die() if id not extracted successfully...
        $v->title = "Clasificación General";
        $v->body = mybodyClasificacion();
        $v->slug ='clasificacion';
        $v->template = 'page'; // optional
        //$v->subtemplate = 'billing'; // optional
    }

    function mybodyClasificacion(){

        $lpportadapdf_options = get_option( 'lpportadapdf_options' );
        //$f = @fopen(esc_url( $lpportadapdf_options['filecsv'] ), "r");
        $f = @fopen(ABSPATH . $lpportadapdf_options['filecsv'] , "r");

        $keys = fgetcsv($f);
        while (!feof($f)) {
            $values=fgetcsv($f);
            $array[] = array_combine((array)$keys, (array)$values);
        }
        $numElementos=200;
        $pagina=(!empty($_GET['pagina']) && intval($_GET['pagina']) > 0)?sanitize_text_field( $_GET['pagina'] ):1;

        $inicio=(($pagina-1) * $numElementos);
        $array1=array_slice($array,$inicio,$numElementos);

        $clasificacion= "<table class='table table-hover table-condensed table-responsive clasificacion'>";
        $clasificacion.= "<tr><td>Posición</td><td>Nombre</td><td>Dorsal</td><td>Club</td><td>Categoría</td><td>Tiempo Oficial</td><td>Ritmo</td></tr>";
        foreach ($array1 as $k => $v){

            $clasificacion.= "<tr><td>".$array1[$k]["Posicion"]."</td>";
            $clasificacion.= "<td>".$array1[$k]["Nombre"]."</td>";
            $clasificacion.= "<td>".$array1[$k]["Dorsal"]."</td>";
            $clasificacion.= "<td>".$array1[$k]["Club"]."</td>";
            $clasificacion.= "<td>".$array1[$k]["Categoria"]."</td>";
            $clasificacion.= "<td>".$array1[$k]["T. Oficial"]."</td>";
            $clasificacion.= "<td>".$array1[$k]["Ritmo"]."</td>";

        }
        $clasificacion.= "</table>";
        $corredores=count($array);

        $botonmenos=($pagina > 1)?"<a class='botonMenos' href='clasificacion/?pagina=".($pagina-1)."'>Página anterior</a>":"";
        $botonmas=(($pagina * $numElementos) < $corredores)?"<a class='botonMas' href='clasificacion/?pagina=".($pagina+1)."'>Página siguiente</a>":"";

        $paginacionHTML='<div class="row">'.$botonmenos." ".$botonmas.'</div>';

        $mibody = $paginacionHTML.'<div class="row">'.$clasificacion.'</div>'.$paginacionHTML;

        return $mibody;
    }
}

if (1)
{
    crearPagePortadaPDF();
    crearPageClasificacion();
}

?>