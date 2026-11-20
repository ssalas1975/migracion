<?php

/*
Plugin Name: Exportar contenido womennow
Plugin URI:
Description: Este plugin genera XML para methode
Version: 1.0
*/

require_once(__DIR__."/acfandcpt.php");
require_once(__DIR__."/shortcodes.php");
require_once(__DIR__."/functions.php");

class XML_wow
{
    public function __construct($postid = null)
    {
        
       //declare hooks
       add_action('admin_menu', array($this, 'generarXMLwow'));
       add_action('wp_ajax_xml_data_action', array($this, 'ajax_xml_data_action'));
       add_action('wp_ajax_download_xml_action', array($this, 'ajax_download_xml_action'));

       add_action('wp_ajax_xml_count_post', array($this, 'ajax_xml_count_post'));

       add_action('admin_enqueue_scripts', array($this, 'vcaa_load_scripts'));

    }

    public function generarXMLwow()
    {
        add_submenu_page('options-general.php', 'generarxmlwow', 'generarxmlwow', 'manage_options', 'generarxmlwow', array( $this, 'form_xmlgeneration' ));
    }

    // Pinta el checkbox para la sindicacion

    public function form_xmlgeneration()
    {
        $idBLog = get_current_blog_id();
        // switch_to_blog($idBLog); --> TOD PRODUCCION
        $sindicado = get_option('sindicado');
        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
     ?>
		<div class='wrap'>
			<h2>Generar XML</h2>
			<form method="post" action="">
				<p>
                    <select name="posttype" id="posttype">
                        <option value="selecciona">Selecciona...</option>
                        <option value="noticia">Noticias</option>
                        <option value="videocpt">Videos</option>
                        <option value="audio">Podcast</option>
                    </select>
					<br>
					<br>
                    <input type="number" min="0" max="2000" id="numeropost" />
                    <br><br>
					<button id="genereasxmlWOW" type="button">Generar ficheros</button>
                    <button id="generearzipWOW" type="button">Generar zip</button>
				</p>
			</form>

			<!-- Escribimos los mensajes cuando estamos sindicando todos los posts -->

			<div id="mensajeWOW"></div>
            <a href="/wp-content/plugins/exportar_wmw/total/zip" id="descarga" style="display: none;">Descargar</a>

			<span id="ajax_admin_options_nonce" class="hidden"><?php echo wp_create_nonce('ajax_admin_options_nonce'); ?></span>


		<?php
        // restore_current_blog(); --> TODO PRODUCCION();
    }



    // Funcion para realizar lo que haga falta mientras sindicamos.

    public function ajax_xml_data_action()
    {
        $result = new XML_wow();
        $postType = $_POST['postType'];
        if($postType=='selecciona'){
            return 1;
        }
        $numeropost = $_POST['numeropost'];
        $offset = $_POST['offset'];
        $totalpost = $_POST['totalpost'];
        $valorDEvuelto = $result->generarTodos($postType, $numeropost, $totalpost, $offset);
    }

    public function ajax_download_xml_action()
    {

        $zip_dir = WP_PLUGIN_DIR . '/exportar_wmw/zip/';
        $totalFileDir = WP_PLUGIN_DIR . '/exportar_wmw/total/';
        $cdir = scandir($zip_dir);



        $zip = new ZipArchive();
        $zip->open($totalFileDir.'zip', ZIPARCHIVE::CREATE);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".","..",".DS_Store"))) {
                    $zip->addFile($zip_dir.$value,$value);
                }
            }
        $zip->close();
    
        XML_wow::deleteDir(__DIR__."/xmls/");
        XML_wow::deleteDir(__DIR__."/zip/");
    

        wp_send_json('funcion ajax para descargar fichero zip');

        // exit;
    }

    public function ajax_xml_count_post()
    {
        $postType = $_POST['postType'];
        $count = wp_count_posts($postType);
        wp_send_json($count);
    }

    // Cargamos el fichero que se encargara de hacer el ajax

    public function vcaa_load_scripts()
    {
        global $vcaa_settings;
        wp_enqueue_script('generarxmlwpw', plugins_url('xml_wow.js', __FILE__), array('jquery'));
    }

    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function generarTodos($postType, $numeropost = 0, $totalpost, $offset = 0)
    {
        global $wp;
        global $post;
        global $wp_query;

        $args = array( 
            'post_type' => $postType, 
            'posts_per_page' => intval($numeropost), 
            'offset' => intval($offset),
            'post_status' => 'publish'
        );

        $posts = get_posts($args);

        // $loop = new WP_Query($args);


        if(!empty($posts)){

            // while ($loop->have_posts()) : $loop->the_post();
            foreach($posts as $post){

                $status = $post->post_status;

            
                if ($status == "publish" || $status == "new") {

                    $plugin_dir_path = WP_CONTENT_DIR;
                    //$plugin_dir_path = "/tmp";
                    $plugin_dir_path = $plugin_dir_path."/xmlfiles/";

                    ob_start();
                    // $id_post = get_the_ID();
                    // $post = get_post($id_post);
                    /**
                     * Comenzamos con la generación del xml
                     */
                    if( $postType == 'audio' || $postType == 'videocpt' )
                    {
                        $pretitulo = get_post_meta($post->ID, 'pretitulo', true);
                        $subtitulo = get_post_meta($post->ID, 'subtitulo', true);
                        $headline = $post->post_content;
                    }else{
                        $pretitulo = get_post_meta($post->ID, 'pretitulo', true);
                        $subtitulo = get_post_meta($post->ID, 'resumen', true);
                        $headline = get_post_meta($post->ID, 'resumen', true);
                    }

        
                    $html = $post->post_content;
                    if($html == '' || $html == null){
                        $html = get_post_meta($post->ID, 'contenido', true);
                    }
                    $html = handledContent($html, $post->ID, $postType);
                    if($html == '0'){
                        wp_reset_query();
                        wp_send_json([$postType, $numeropost, $totalpost, $offset+$numeropost, 1, count($posts), $html]);
                    }else{
                        $xml = '';

                        $xml .='<?xml version="1.0" encoding="utf-8"?>'."\n";
                        $xml .='<!DOCTYPE doc SYSTEM "/SysConfig/2017/rules/vocento-2017.dtd">'."\n";
                        $xml .='<?EM-dtdExt /SysConfig/2017/rules/vocento-2017.dtx?>'."\n";
                        $xml .='<?EM-templateName /SysConfig/2017/plantillas/articulos/articulo-general-2017.xml?>'."\n";
                        $xml .='<?xml-stylesheet type="text/css" href="/SysConfig/2017/rules/css/articulo/articulo-general-2017.css"?>'."\n";
                        $xml .='<doc xml:lang="es">'."\n";
                        $xml .='<lead class="r2017">'."\n";
                            $xml .='<photo-container class="H1">'."\n";
                                $xml .='<photo width="984" height="601" vspace="1"/>'."\n";
                                $xml .='<media>'."\n";
                                    $xml .='<?EM-dummyText [media link]?>'."\n";
                                $xml .='</media>'."\n";
                                $xml .='<caption>'."\n";
                                    $xml .='<description>'."\n";
                                    $xml .='<?EM-dummyText [descripción pie foto]?>'."\n";
                                    $xml .='</description>'."\n";
                                    $xml .='<credit>'."\n";
                                    $xml .='<?EM-dummyText [firma pie foto]?>'."\n";
                                    $xml .='</credit>'."\n";
                                $xml .='</caption>'."\n";
                            $xml .='</photo-container>'."\n";
                            $xml .='<photo-container class="H3">'."\n";
                            $xml .='<photo width="984" height="468" vspace="1"/>'."\n";
                                $xml .='<media>'."\n";
                                $xml .='<?EM-dummyText [media link]?>'."\n";
                                $xml .='</media>'."\n";
                                $xml .='<caption>'."\n";
                                    $xml .='<description>'."\n";
                                    $xml .='<?EM-dummyText [descripción pie foto]?>'."\n";
                                    $xml .='</description>'."\n";
                                    $xml .='<credit>'."\n";
                                    $xml .='<?EM-dummyText [firma pie foto]?>'."\n";
                                    $xml .='</credit>'."\n";
                                $xml .='</caption>'."\n";
                            $xml .='</photo-container>'."\n";
                            $xml .='<photo-container class="C1">'."\n";
                                $xml .='<photo width="750" height="750" vspace="1"/>'."\n";
                                $xml .='<media>'."\n";
                                $xml .='<?EM-dummyText [media link]?>'."\n";
                                $xml .='</media>'."\n";
                                $xml .='<caption>'."\n";
                                    $xml .='<description>'."\n";
                                    $xml .='<?EM-dummyText [descripción pie foto]?>'."\n";
                                    $xml .='</description>'."\n";
                                    $xml .='<credit>'."\n";
                                    $xml .='<?EM-dummyText [firma pie foto]?>'."\n";
                                    $xml .='</credit>'."\n";
                                $xml .='</caption>'."\n";
                            $xml .='</photo-container>'."\n";
                            $xml .='<photo-container class="V1">'."\n";
                                $xml .='<photo width="480" height="637" vspace="1"/>'."\n";
                                $xml .='<media>'."\n";
                                    $xml .='<?EM-dummyText [media link]?>'."\n";
                                $xml .='</media>'."\n";
                                $xml .='<caption>'."\n";
                                    $xml .='<description>'."\n";
                                    $xml .='<?EM-dummyText [descripción pie foto]?>'."\n";
                                    $xml .='</description>'."\n";
                                    $xml .='<credit>'."\n";
                                    $xml .='<?EM-dummyText [firma pie foto]?>'."\n";
                                    $xml .='</credit>'."\n";
                               $xml .=' </caption>'."\n";
                            $xml .='</photo-container>'."\n";
                            $xml .='<photo-container class="s1280revista">'."\n";
                                $xml .='<photo width="1280" height="791" vspace="1"/>'."\n";
                                $xml .='<media>'."\n";
                                $xml .='<?EM-dummyText [media link]?>'."\n";
                                $xml .='</media>'."\n";
                                $xml .='<caption>'."\n";
                                    $xml .='<description>'."\n";
                                    $xml .='<?EM-dummyText [descripción pie foto]?>'."\n";
                                    $xml .='</description>'."\n";
                                    $xml .='<credit>'."\n";
                                    $xml .='<?EM-dummyText [firma pie foto]?>'."\n";
                                    $xml .='</credit>'."\n";
                                $xml .='</caption>'."\n";
                            $xml .='</photo-container>'."\n";
                            $xml .='<overhead>'."\n";
                            $xml .='<?EM-dummyText [Antetítulo para deportes]?>'."\n";
                            $xml .='</overhead>'."\n";
                            $xml .='<headline>'."\n";
                            $xml .='<?EM-dummyText [título título título título]?>'."\n";
                            $xml .='</headline>'."\n";
                            $xml .='<photo-container class="H1-bis">'."\n";
                                $xml .='<photo width="984" height="601" vspace="1"/>'."\n";
                                $xml .='<media>'."\n";
                                $xml .='<?EM-dummyText [media link]?>'."\n";
                                $xml .='</media>'."\n";
                                $xml .='<caption>'."\n";
                                    $xml .='<description>'."\n";
                                    $xml .='<?EM-dummyText [descripción pie foto]?>'."\n";
                                    $xml .='</description>'."\n";
                                    $xml .='<credit><?EM-dummyText [firma pie foto]?>'."\n";
                                    $xml .='</credit>'."\n";
                                $xml .='</caption>'."\n";
                            $xml .='</photo-container>'."\n";
                            $xml .='<photo-container class="H3-bis">'."\n";
                                $xml .='<photo width="984" height="468" vspace="1"/>'."\n";
                                $xml .='<media>'."\n";
                                $xml .='<?EM-dummyText [media link]?>'."\n";
                                $xml .='</media>'."\n";
                                $xml .='<caption>'."\n";
                                    $xml .='<description>'."\n";
                                    $xml .='<?EM-dummyText [descripción pie foto]?>'."\n";
                                    $xml .='</description>'."\n";
                                    $xml .='<credit>'."\n";
                                    $xml .='<?EM-dummyText [firma pie foto]?>'."\n";
                                    $xml .='</credit>'."\n";
                                $xml .='</caption>'."\n";
                            $xml .='</photo-container>'."\n";
                            $xml .='<byline>'."\n";
                                $xml .='<locator>'."\n";
                                $xml .='Women Now'."\n";
                                $xml .='</locator>'."\n";
                                $xml .='<author>'."\n";
                                    $xml .='<name>'."\n";
                                    $xml .='Women Now'."\n";
                                    $xml .='</name>'."\n";
                                $xml .='</author>'."\n";
                            $xml .='</byline>'."\n";
                            $xml .='<subhead>'."\n";
                            $xml .='<?EM-dummyText [subtitulo subtítulo subtítulo subtítulo]?>'."\n";
                            $xml .='</subhead>'."\n";
                            $xml .='<link-container>'."\n";
                                $xml .='<ul class="voc-list-support"><?EM-dummyText [Inserte apoyo noticia, galería, vídeo, gráfico, directo, interactivo, pdf ]?>'."\n";
                                $xml .='</ul>'."\n";
                            $xml .='</link-container>'."\n";
                            $xml .='<subarticles>'."\n";
                            $xml .='<?EM-dummyText [Insertar componente-noticia-subordinada]?>'."\n";
                            $xml .='</subarticles>'."\n";
                        $xml .='</lead>'."\n";
                        $xml .='<story class="r2017">'."\n";
                        $xml .='<grouphead class="estandar">'."\n";
    
    
                            $xml .='<span class="voc-pretitle-sports">'."\n";
                                $xml .= $pretitulo."\n";
                            $xml .='</span>'."\n";
                            $xml .='<headline>'."\n";
                                $xml .= $post->post_title."\n";
                            $xml .='</headline>'."\n";
                            $xml .='<subhead-group>'."\n";
                                $xml .='<heading>'."\n";
                                $xml .='</heading>'."\n";
                                $xml .='<subhead>'."\n";
                                    $xml .= $headline."\n";
                                $xml .='</subhead>'."\n";
                            $xml .='</subhead-group>'."\n";
    
    
                            $thumbnail = get_the_post_thumbnail_url($post->ID);
                            if($thumbnail){
                                $url = 'https://www.womennow.es'.$thumbnail;
                                $imagen = getImageIdByUrl($url);
                                if($imagen){
                                    $data = wp_get_attachment(intval($imagen));
                                    $UUID = gen_uuid();
                                    $xml .= '<photo-container cmpName="cmp-foto-texto" class="voc-inner-figure">';
    
    
                                    $time = get_the_time('Y-m-d', $post->ID);
                                    $time = explode("-", $time);
                                    $dia = zeroise($time[2], 2);
                                    $mes = zeroise($time[1], 2);
                                    $anno = zeroise($time[0], 4);
                                    $time = $anno.$mes.$dia;
                                    $timeSegundos = $anno.$mes.$dia.get_the_time('His', $post->ID);
    
    
                                    if( $postType == 'videocpt' ){
                                            $xml .= '<photo height="'.$data['height'].'" tmx="624 624 624 624" width="'.$data['width'].'" xtransform="1 0 0 1 0 0" fileref="" vspace="1"></photo>';
                                            $xml .= $html;
                                    }else{
                                        $xml .= '<photo height="'.$data['height'].'" tmx="624 624 624 624" width="'.$data['width'].'" xtransform="1 0 0 1 0 0" fileref="/mujerhoy_2017/noticias/'.$anno.$mes.'/'.$dia.'/media/'.$data['filename'].'?uuid='.gen_uuid().'" vspace="1"></photo>';
                                    }
    
    
                                    $xml .='<caption class="standard">
                                            <description>'.$data['caption'].'</description>
                                            <credit>'.$data['alt'].'</credit>
                                        </caption>
                                    </photo-container>'."\n";
                                    $imagePath = $data['filepath'];
                                    $newPath = __DIR__."/xmls/".$post->ID."/";
                                    $ext = $data['extension'];
                                    $newName  = $newPath.$data['filename'];
                                    $copied = copy($imagePath , $newName);
                                }
                            }
    
                        $xml .='</grouphead>'."\n";
                        $xml .='<text>'."\n";
                        if( $postType != 'videocpt' ){
                            $xml .= $html;
                        }
                        $xml .='</text>'."\n";
                        $xml .='</story>'."\n";
                        $xml .='</doc>'."\n";
    
    
                        $fichero = __DIR__."/xmls/".$post->ID."/".$post->post_name.'.cont';
                        file_put_contents($fichero, $xml, FILE_USE_INCLUDE_PATH);
    
                        $time = get_the_time('Y-m-d', $post->ID);
                        $time = explode("-", $time);
                        $dia = zeroise($time[2], 2);
                        $mes = zeroise($time[1], 2);
                        $anno = zeroise($time[0], 4);
                        $time = $anno.$mes.$dia;
                        $timeSegundos = $anno.$mes.$dia.get_the_time('His', $post->ID);
    
                        $xmlSysmeta = '';
                        $xmlSysmeta .= '<props>'."\n";
                            $xmlSysmeta .= '<productInfo>'."\n";
                                $xmlSysmeta .= '<name>MujerHoy</name>'."\n";
                                $xmlSysmeta .= '<issueDate>'.$time.'</issueDate>'."\n";
                            $xmlSysmeta .= '</productInfo>'."\n";
                            if( $postType == 'audio' ){
                                $xmlSysmeta .= '<workFolder>/MujerHoy/Contenidos/womennow/podcast/</workFolder>'."\n";
                            }elseif( $postType == 'videocpt' ){
                                $xmlSysmeta .= '<workFolder>/MujerHoy/Contenidos/womennow/videos/</workFolder>'."\n";
                            }else{
                                $xmlSysmeta .= '<workFolder>/MujerHoy/Contenidos/womennow/noticias/</workFolder>'."\n";
                            }
                            $xmlSysmeta .= '<templateName>/SysConfig/2017/plantillas/articulos/articulo-general-2017.xml</templateName>'."\n";
                            $xmlSysmeta .= '<fechaManualTimestamp/>'."\n";
                            $xmlSysmeta .= '<primeraEjecucion>true</primeraEjecucion>'."\n";
                        $xmlSysmeta .= '</props>'."\n";
    
                        $ficheroSysmeta = __DIR__."/xmls/".$post->ID."/".$post->post_name.".sysmeta";
                        file_put_contents($ficheroSysmeta, $xmlSysmeta, FILE_USE_INCLUDE_PATH);
    
    
                        //** METADATA */
    
                        $xmlMetadata = "";
                        $xmlMetadata .= '<!DOCTYPE Metadata SYSTEM "/SysConfig/2017/classify/classify.dtd">'."\n";
                        if( $postType == 'audio' ){
                            $xmlMetadata .= '<Metadata path="/mujerhoy_2017/noticias/'.$anno.$mes.'/'.$dia.'/womennow/podcast" imagepath="/mujerhoy_2017/noticias/'.$anno.$mes.'/'.$dia.'/media" >'."\n";
                        }elseif( $postType == 'videocpt' ){
                            $xmlMetadata .= '<Metadata path="/mujerhoy_2017/noticias/'.$anno.$mes.'/'.$dia.'/womennow/videos" imagepath="/mujerhoy_2017/noticias/'.$anno.$mes.'/'.$dia.'/media" >'."\n";
                        }else{
                            $xmlMetadata .= '<Metadata path="/mujerhoy_2017/noticias/'.$anno.$mes.'/'.$dia.'/womennow/noticias" imagepath="/mujerhoy_2017/noticias/'.$anno.$mes.'/'.$dia.'/media" >'."\n";
                        }
                        $xmlMetadata .= '<General>'."\n";
                        $xmlMetadata .= '<webtype>Noticia</webtype>'."\n";
                        $xmlMetadata .= '<shared-information>'."\n";
                        $xmlMetadata .= '<vocento_share>No</vocento_share>'."\n";
                        $xmlMetadata .= '<shared>No</shared>'."\n";
                        $xmlMetadata .= '<sharedRC>No</sharedRC>'."\n";
                        $xmlMetadata .= '</shared-information>'."\n";
                        $xmlMetadata .= '<Metas>'."\n";
                        $xmlMetadata .= '<Titulo>'.$post->post_title.'</Titulo>'."\n";
                        $xmlMetadata .= '<Titulo-SEO></Titulo-SEO>'."\n";
                        $xmlMetadata .= '<Titulo-Newsletter>'.$post->post_title.'</Titulo-Newsletter>'."\n";
                        $xmlMetadata .= '<Antetitulo/>'."\n";
                        $xmlMetadata .= '<Antetitulo-SEO>No</Antetitulo-SEO>'."\n";
                        $xmlMetadata .= '<Description>'.$subtitulo.'</Description>'."\n";
                        $xmlMetadata .= '<Description-SEO></Description-SEO>'."\n";
                        $xmlMetadata .= '<NewKeyWords></NewKeyWords>'."\n";
                        $xmlMetadata .= '<Robots>index,follow</Robots>'."\n";
                        $xmlMetadata .= '<CanonicalPPLL/>'."\n";
                        $xmlMetadata .= '<FechaPrimeraPublicacion/>'."\n";
                        $xmlMetadata .= '<Idioma>es</Idioma>'."\n";
                        $xmlMetadata .= '<CintilloNoticia/>'."\n";
                        $xmlMetadata .= '</Metas>'."\n";
                        $xmlMetadata .= '<genero>estandar</genero>'."\n";
                        $xmlMetadata .= '<fecha>fecha_manual</fecha>'."\n";
                        $xmlMetadata .= '<FechaManual>'.$timeSegundos.'</FechaManual>'."\n";
                        $xmlMetadata .= '<fechaBuscador/>'."\n";
                        $xmlMetadata .= '<categoria>'."\n";
                        $xmlMetadata .= '<principal>womennow/'.get_the_category()[0]->slug.'</principal>'."\n";
                        $xmlMetadata .= '<secundaria/>'."\n";
                        $xmlMetadata .= '</categoria>'."\n";
                        $xmlMetadata .= '<componenteOpta>No</componenteOpta>'."\n";
                        $xmlMetadata .= '<Origen>Migracion-WomenNow</Origen>'."\n";
                        $xmlMetadata .= '<NoticiaDeportes>No</NoticiaDeportes>'."\n";
                        $xmlMetadata .= '<componenteReceta>No</componenteReceta>'."\n";
                        $xmlMetadata .= '<componenteScribble>No</componenteScribble>'."\n";
                        $xmlMetadata .= '<enDirectoScribble>No</enDirectoScribble>'."\n";
                        $xmlMetadata .= '<foto360>No</foto360>'."\n";
                        $xmlMetadata .= '<marcador>'."\n";
                        $xmlMetadata .= '<Manual>'."\n";
                        $xmlMetadata .= '<Competicion/>'."\n";
                        $xmlMetadata .= '<Escudo_Local/>'."\n";
                        $xmlMetadata .= '<Equipo_Local/>'."\n";
                        $xmlMetadata .= '<Marcador_Local/>'."\n";
                        $xmlMetadata .= '<Marcador_Visitante/>'."\n";
                        $xmlMetadata .= '<Equipo_Visitante/>'."\n";
                        $xmlMetadata .= '<Escudo_Visitante/>'."\n";
                        $xmlMetadata .= '<Penaltis/>'."\n";
                        $xmlMetadata .= '<Estado/>'."\n";
                        $xmlMetadata .= '<Url/>'."\n";
                        $xmlMetadata .= '</Manual>'."\n";
                        $xmlMetadata .= '<Automatico>'."\n";
                        $xmlMetadata .= '<Deporte/>'."\n";
                        $xmlMetadata .= '<PopMarcador/>'."\n";
                        $xmlMetadata .= '<Url/>'."\n";
                        $xmlMetadata .= '<OcultarCompeticion>No</OcultarCompeticion>'."\n";
                        $xmlMetadata .= '</Automatico>'."\n";
                        $xmlMetadata .= '</marcador>'."\n";
                        $xmlMetadata .= '<Video>'."\n";
                        $xmlMetadata .= '<Autoplay>Si</Autoplay>'."\n";
                        $xmlMetadata .= '<AutoplayHome>No</AutoplayHome>'."\n";
                        $xmlMetadata .= '<PlayInSitu>No</PlayInSitu>'."\n";
                        $xmlMetadata .= '<Autorefresh>Si</Autorefresh>'."\n";
                        $xmlMetadata .= '</Video>'."\n";
                        $xmlMetadata .= '<autor>system</autor>'."\n";
                        $xmlMetadata .= '<AnadirEnlaces>Si</AnadirEnlaces>'."\n";
                        $xmlMetadata .= '<topics>'."\n";
                        $xmlMetadata .= '<topics-required>no</topics-required>'."\n";
                        $xmlMetadata .= '</topics>'."\n";
                        $xmlMetadata .= '<destination>mujerhoy_2017</destination>'."\n";
                        $xmlMetadata .= '<PayWall>'."\n";
                        $xmlMetadata .= '<Meter>Yes</Meter>'."\n";
                        $xmlMetadata .= '<Extra>No</Extra>'."\n";
                        $xmlMetadata .= '</PayWall>'."\n";
                        $xmlMetadata .= '<EstiloTitular>estandar</EstiloTitular>'."\n";
                        $xmlMetadata .= '</General>'."\n";
                        $xmlMetadata .= '<Publicacion>'."\n";
                        $xmlMetadata .= '<Sindicacion>Si</Sindicacion>'."\n";
                        $xmlMetadata .= '<VersionAMP>Si</VersionAMP>'."\n";
                        $xmlMetadata .= '<AbrirEnWebView>No</AbrirEnWebView>'."\n";
                        $xmlMetadata .= '<Comentarios/>'."\n";
                        $xmlMetadata .= '<isLocal>No</isLocal>'."\n";
                        $xmlMetadata .= '<isHiperlocal>No</isHiperlocal>'."\n";
                        $xmlMetadata .= '<PortalDestino>mujerhoy_2017</PortalDestino>'."\n";
                        $xmlMetadata .= '<Afiliacion>No</Afiliacion>'."\n";
                        $xmlMetadata .= '<EnvioPaginaWeb>'."\n";
                        $xmlMetadata .= '<Nivel1/>'."\n";
                        $xmlMetadata .= '<Nivel2/>'."\n";
                        $xmlMetadata .= '<Nivel3/>'."\n";
                        $xmlMetadata .= '<NivelHL/>'."\n";
                        $xmlMetadata .= '</EnvioPaginaWeb>'."\n";
                        $xmlMetadata .= '<GenerarMultiplesHTMLs>No</GenerarMultiplesHTMLs>'."\n";
                        $xmlMetadata .= '<PaginaWeb/>'."\n";
    
                        $re = '/-[a-z]{1,3}-/m';
                        $str = $post->post_name;
                        $subst = "-";
                        $result = preg_replace($re, $subst, $str);
    
                        $xmlMetadata .= '<url>'.$result.'-'.$timeSegundos.'-nt</url>'."\n";
                        $xmlMetadata .= '<Publicacion_Programada>'."\n";
                        $xmlMetadata .= '<Inicio/>'."\n";
                        $xmlMetadata .= '<Fin/>'."\n";
                        $xmlMetadata .= '</Publicacion_Programada>'."\n";
                        $xmlMetadata .= '<EnvioStats>null</EnvioStats>'."\n";
                        $xmlMetadata .= '<urlObjeto/>'."\n";
                        $xmlMetadata .= '</Publicacion>'."\n";
                        $xmlMetadata .= '</Metadata>'."\n";
    
                        //** METADATA */
                        $fichero = __DIR__."/xmls/".$post->ID."/".$post->post_name.'.meta';
                        file_put_contents($fichero, $xmlMetadata, FILE_USE_INCLUDE_PATH);
                        if ( ! is_dir( __DIR__."/zip/" ) ) {
                            wp_mkdir_p( __DIR__."/zip/");
                        }
                        $zipfile = __DIR__."/zip/".$post->ID.'.zip';
                        $cdir = scandir(__DIR__."/xmls/".$post->ID."/");
                        $statusCode = 0;
                        foreach ($cdir as $key => $value) {
                            if (!in_array($value, array(".","..",".DS_Store"))) {
                                $zip = new ZipArchive;
                                if ($zip->open($zipfile, ZipArchive::CREATE) === TRUE)
                                {
                                    $zip->addFile(__DIR__."/xmls/".$post->ID."/".$value, $value);
                                    $zip->close();
                                }
                            }
                        }
                        
                        //Limpiamos la cache
                        ob_end_clean();
                            
                        wp_reset_query();
                        wp_send_json([$postType, $numeropost, $totalpost, $offset+$numeropost, 1, count($posts)]);
                    }

                    
                }


            }

            // wp_reset_query();
            // wp_send_json([$postType, $numeropost, $totalpost, $offset+$numeropost, 1, count($posts)]);
        }else{
            XML_wow::deleteDir(__DIR__."/total/");
            wp_send_json([$postType, $numeropost, $totalpost, $offset+$numeropost, 0, count($posts)]);
        }
        
        
        return 1;
    }

    // Funcion para normalizar caracteres especiales

    public function normalize_str($str)
    {
        $invalid = array('Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z',
        'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A',
        'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
        'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
        'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a',
        'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e',  'ë'=>'e', 'ì'=>'i', 'í'=>'i',
        'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
        'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y',  'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', "`" => "'", "´" => "'", "„" => ",", "`" => "'",
        "´" => "'", "“" => "\"", "”" => "\"", "´" => "'", "&acirc;€™" => "'", "{" => "",
        "~" => "", "–" => "-", "’" => "'");
         
        $str = str_replace(array_keys($invalid), array_values($invalid), $str);
         
        return $str;
    }


    // Genera la URL del sindicador dependiendo de en que medio nos encontremos

    public function urlSindicar($actual_link)
    {
        die('generar structura carpetas');
        // return  "http://pre.buscadorpbl.srv.vocento.in/krammer/colpisa/v1/blogs/publish";

        // if (!defined('KRAMMER_HOST')) {
        //     if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        //         define('KRAMMER_HOST', 'http://pre.buscadorpbl.srv.vocento.in/krammer/colpisa/v1/blogs/publish');
        //     } else {
        //         define('KRAMMER_HOST', 'http://buscadorpbl.srv.vocento.in/krammer/colpisa/v1/blogs/publish');
        //     }
        // }
        
        // return KRAMMER_HOST;
    }
}

$pm = new XML_wow();

?>
