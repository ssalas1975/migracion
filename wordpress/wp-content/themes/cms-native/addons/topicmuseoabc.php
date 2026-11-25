<?php
vc_add_shortcode_param( 'topicmuseoabc', 'select_topics_museo');
function select_topics_museo( $settings, $value ) {
	wp_enqueue_media();
?>
    <div>
		<label for="urlbuscador">Url Buscador</label>
		<input type="text" name="url_buscador_show" id="url_buscador_show" class="regular-text">
		<br/><br/>
		<!--span class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw" name="upload-btn" id="upload-btn">Cargar Resultados</span-->
		<br/><br/>
	</div>
   
	<?php
	$json = json_decode($value, true);
   $filecontent = file_get_contents(esc_attr( $json['url'] ));
   $obj = json_decode($filecontent, true);
//    var_dump($obj["entries"]);
   foreach($obj["entries"] as $entry){
	   echo '
	   <style>
    article.topic {
        max-width: 20%;
        width: 20%;
        display: inline-block;
        vertical-align: top;
        position: relative;
        padding:20px 20px;
        box-sizing: content-box;
    }
    
     article.topic figure {
        padding: 0;
        margin: 0;
    }
    
     article.topic figure img{
        padding: 0;
        margin: 0;
        max-width: 100%;
        width:100%;
    }
    
    
    
    article.topic h1 {font-size:1.3em}
    
    body .vc_properties-list input[type=checkbox]{
    top:10px; left:10px; position:absolute;
    border:3px solid #8698d8;
    }
</style>
	   <article class="topic museo>
			<a href="'.$entry['href'].'"> 
				<figure>
					<img src="'.$entry['imagen'].'">
					<div class="info">
						<address>'.$entry['autores']['autor'][0]['nombre'].'</address>
						<time>'.date('Y-m-d H:m:s', strtotime($entry['fecha'])).'</time>
					</div>
				</figure>                
				<div class="">
				<input type="checkbox" id="recid-'.$entry['recid'].'" class="selectedTopic" name="selectedTopic" value="recid-'.$entry['recid'].'" />
				<h1>'.$entry['titulo'].'</h1>
				<p>'.$entry['entradillas']["entradilla"].'</p>
				</div>
			</a>
		</article>
	   ';
   }
   echo '<input name="' . esc_attr( $settings['param_name'] ) . '" id="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
   esc_attr( $settings['param_name'] ) . ' ' .
   esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

   echo "
   <script>
   let dataValues = {};
   dataValues.ids = [];

   if(document.querySelector('#url_buscador').value !== ''){
	   dataValues = JSON.parse(document.querySelector('#url_buscador').value);
	   document.querySelector('#url_buscador_show').value = dataValues.url;
   }

   const checks = document.querySelectorAll('.selectedTopic');
   checks.forEach(function(e){
	   e.addEventListener( 'change', function() {
		   if(e.checked) {
			   dataValues.ids.push(e.value);
		   } else {
			   const pos = dataValues.ids.indexOf(e.value);
			   dataValues.ids.splice(pos, 1);
		   }
		   document.querySelector('#url_buscador').value = JSON.stringify(dataValues);
	   });
   });

   document.querySelector('#url_buscador_show').addEventListener('change', function(e) {
	   dataValues.url = e.srcElement.value;
	   console.log('VALOR URL RECUPERADO INICIAL', dataValues);
	   console.log('CONVERTIR ARRAY EN JSON', JSON.stringify(dataValues));
	   document.querySelector('#url_buscador').value = JSON.stringify(dataValues);
   });

   dataValues.ids.forEach(function(e){
		document.querySelector('#'+e).checked = true;
	});
   

   </script>
   ";


   die();
}


function create_topicmuseoabc_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'url_buscador' => '',
		),
		$atts,
		'topicmuseoabc'
	);

	$urlBuscador = 'http://buscador.srv.vocento.in/krammer/abc/v2/search?alt=json&filter=idtopic:500d28f04c876&recid=';

	// Attributes in var
	$url_buscador = $atts['url_buscador'];
	$url_buscador = str_replace("`{```", "[\"", $url_buscador);
	$url_buscador = str_replace("```}`", "\"]", $url_buscador);
	$url_buscador = str_replace("``,``","\",\"", $url_buscador);
	$url_buscador = str_replace("``", "\"", $url_buscador);

	
	$json = json_decode($url_buscador , true);
	foreach($json['ids'] as $id){
		$filecontent = file_get_contents($urlBuscador.$id);
		$obj = json_decode($filecontent, true);

		// var_dump($obj["entries"][0]);

		echo '
		<article class=“topic museo”>
				<a href=“#"> 
					<figure>
						<img src="'.$obj["entries"][0]['imagen'].'">
						<div class="info">
							<address>'.$obj["entries"][0]['autores']['autor'][0]['nombre'].'</address>
							<time>'.date('Y-m-d H:m:s', strtotime($obj["entries"][0]['fecha'])).'</time>
						</div>
					</figure>                
					<div class="">
					<h1>'.$obj["entries"][0]['titulo'].'</h1>
					<p>'.$obj["entries"][0]['entradillas']["entradilla"].'</p>
					</div>
				</a>
			</article>
		';
		   
	}

	// Output Code
	// $output = 'Here you can write';
	// $output .= 'your code.';

	// return $output;
}
add_shortcode( 'vocento_topicmuseoabc', 'create_topicmuseoabc_shortcode');


//** TOPICS MUSEO ABC */
vc_map( array(
    "name" => __("Vocento Topics Museo ABC"),
    'base' => 'vocento_topicmuseoabc',
    "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
    'category' => __( 'Vocento', 'js_composer' ),
    'description' => __( 'Topics Museo ABC.', 'js_composer' ),
    'params' => array(
        array(
            'type' => 'topicmuseoabc',
			'holder' => 'div',
			'class' => '',
			'admin_label' => false,
			'param_name' => 'url_buscador',
		),
    ),
));
