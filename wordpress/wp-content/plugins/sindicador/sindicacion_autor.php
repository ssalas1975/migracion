<?php

/*
Plugin Name: Sindicacion Contenido MujerHoy
Plugin URI:
Description: Este plugin genera XML para cada uno de los boletines/podcast
Version: 1.0
Author URI:
*/


class XML_Sindicacion
{
    public function __construct()
    {
        //declare hooks
        add_action('admin_menu', array($this, 'sindicarBlogs'));
        /* Hook cuando enviamos como borrador a un post */
        add_action('wp_trash_post', array($this, 'eliminarSindicador'));
        add_action('save_post', array($this, 'enviarSindicador'));
        /* Hook cuando publicacimos un post */
        add_action('publish_post', array($this, 'enviarSindicador'));
        /* Hook cuando borramos un post */
        add_action('delete_post', array($this, 'eliminarSindicador' ));
        /* Hook cuando deja de encontrarse como borrador */
        add_action('untrash_post', array($this, 'enviarSindicador' ));
        add_action('publish_to_pending', array($this, 'eliminarSindicador' ));
        add_action('publish_to_draft', array($this, 'eliminarSindicador' ));
        add_action('draft_to_publish', array($this, 'enviarSindicador'));
        add_action('pending_to_publish', array($this, 'enviarSindicador'));
        add_action('publish_future_post', array($this, 'enviarSindicador'));

        add_action('wp_ajax_sindicacion_data_action', array($this, 'ajax_sindicacion_data_action'));
        add_action('admin_enqueue_scripts', array($this, 'vcaa_load_scripts'));

        //custom de abcvoz
        add_action('sindicate_abcvoz', array($this, 'enviarSindicador'));
    }


    // Funcion que se encarga de generar el XMl pertinente he enviarlo al sindicador para los blogs.

    public function sindicarBlogs()
    {

        //if ($this->blogs_autor()){
        add_submenu_page('options-general.php', 'sindicarBlog', 'sindicarBlog', 'manage_options', 'sindicarBlog', array( $this, 'form_sindicacion' ));
        //}
    }

    // Pinta el checkbox para la sindicacion

    public function form_sindicacion()
    {
        $idBLog = get_current_blog_id();
        switch_to_blog($idBLog);
        $sindicado = get_option('sindicado');
        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

        if ($_POST['update_sindicacion'] == 'true') {
            $this->sindicacion_update($idBLog);
        } ?>
		<div class='wrap'>
			<h2>Sindicación de blogs</h2>
			<form method="post" action="">
				<p>
					<input type="hidden" name="update_sindicacion" value="true" />
					<strong>Marca la casilla si deseas sindicar este blog</strong>
					<br />
					<br>
					<input type="checkbox" name="sindicado" size="45" <?php if (get_option('sindicado')) {
            echo "checked=".get_option('sindicado');
        } ?> />Sindicar este blog
					<br>
					<br>
					<button id="sindicacionBlogs" type="button">Sindicar Blog Completo</button>
					<br>
					<br>
					<input type="submit" class="button-primary" name="Submit" value="Guardar"/>
				</p>
			</form>

			<!-- Escribimos los mensajes cuando estamos sindicando todos los posts -->

			<div id="mensajeSindicar"></div>

			<span id="ajax_admin_options_nonce" class="hidden"><?php echo wp_create_nonce('ajax_admin_options_nonce'); ?></span>

		</div>
		<?php
        restore_current_blog();
    }


    // Funcion para realizar lo que haga falta mientras sindicamos.

    public function ajax_sindicacion_data_action()
    {
        $result = new XML_Sindicacion();
        $valorDEvuelto = $result->sindicarTodos();
        die();
    }



    // Cargamos el fichero que se encargara de hacer el ajax

    public function vcaa_load_scripts()
    {
        global $vcaa_settings;
        //wp_enqueue_script( 'sindicacion_autor',plugins_url( '/assets/abcvoz_admin_check.js', __FILE__ ) );
        wp_enqueue_script('sindicacion_autor', plugins_url('sindicacion_autor.js', __FILE__));
    }


    // Funcion para actualizar los campos de la sindicacion

    public function sindicacion_update($idBLog)
    {
        // this is where validation would go
        update_blog_option($idBLog, 'sindicado', $_POST['sindicado']);
    }






    // Funcion que se encgarga de enviar los datos al sindicador

    public function enviarSindicador($post_id)
    {
        
        //global $post;
        $post = get_post($post_id);

        $meta_values = get_post_meta($post_id);

        if (is_int($post_id) == false) {
            return;
        }


        $taxName = "Medios";
        $terms =  get_terms($taxName);
        $urlPost = get_permalink();
        $urlPost = wp_make_link_relative($urlPost);
        $urlPost = preg_replace("/\/contentfactory/", '', $urlPost);
        $actual_link = "";
        foreach ($terms as $term) {
            if ($term->slug=='mujerhoy') {
                $url_medio = get_field('url_medio', $term);
                $url_medio = preg_replace("/content-factory/", 'www', $url_medio);
                $actual_link  = $url_medio."/contentfactory/post".$urlPost;
            }
        }

        $idBLog = get_current_blog_id();
        
        $date = date('Y-m-d H:i:s');
        $content=$date." | ".$post_id." | ".$idBLog;
        
        //if( $post_id == null || $post_id == " " || $post_id == "" )
        if (is_int($post_id) == false) {
            $content.=' | no tengo id';
        } else {
            $content.=' | si tengo id y es '.$post_id;
        }
        // Cambiamos al blog en el que nos encontramos
        $flag_edicion_rapida = 0;
        switch_to_blog($idBLog);
        
        /*$status = get_post_status( get_the_ID() );
        $id_post = get_the_ID();*/

        $status = get_post_status($post_id);
        $id_post = $post_id;
        
        if (empty($status) || $status == "") {
            //$status = get_post_status( $post["ID"] );
            $status = get_post_status($post_id);
            //$id_post = $post["ID"];
            $id_post = $post_id;
            $flag_edicion_rapida = 1;
            $content.= " | status = ".$status;
        }
        if (empty($status) || $status == "") { //publicacion programada
            $flag_edicion_rapida = 0;
            $status = get_post_status($post_id);
            $id_post = $post_id;
            $post = get_post($post_id);
            $content.= " | status publicacion programada = ".$status;
        }
        
        $page="";
        
        if ($status == "publish" || $status == "new") {
            /*Comienza el IF */
            if ($actual_link) {
                $plugin_dir_path = WP_CONTENT_DIR;
                //$plugin_dir_path = "/tmp";
                $plugin_dir_path = $plugin_dir_path."/xmlfiles/";
                ////Creamos un directorio para almacenar el XML en caso de que este no exista.
                //if (!is_dir("/tmp/xmlfiles/")) {
                if (!is_dir($plugin_dir_path)) {
                    //   mkdir("/tmp/xmlfiles/");
                    mkdir($plugin_dir_path, 0777, true);
                }
                //START BUFFER
                
                remove_filter('the_excerpt', 'wpautop');
                /** GET XML AND MAP **/
                $xml_template=file_get_contents(plugin_dir_path(__FILE__)."/"."template.xml");
                $page = $this->map_template($xml_template, $id_post, $idBLog, $actual_link);

                $URL = $this->urlSindicar($actual_link);
                $content.= " | URL Sindicacon = ".$URL;
                // Envío de Datos al sindicador
                
                $ch = curl_init($URL);
                // echo do_shortcode(get_post_field('post_content', $page));

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $page);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);


                if (!curl_errno($ch)) {
                    $info = curl_getinfo($ch);
                    $content.= " | http_code = ".$info['http_code']." | url ".$info['url'];
                } else {
                    $content.= " | error al ejecutar la llamada curl. ";
                }


                curl_close($ch);

                //Limpiamos la cache
                ob_end_clean();
            }

            /* Acaba el IF */
        }

        if (!file_exists(WP_CONTENT_DIR."/uploads/checksindicacion")) {
            mkdir(WP_CONTENT_DIR."/uploads/checksindicacion/");
        }

        file_put_contents(WP_CONTENT_DIR."/uploads/checksindicacion/log58d0f7578940bB.txt", $content."\n", FILE_APPEND);
        if ($page) {
            file_put_contents(WP_CONTENT_DIR."/uploads/checksindicacion/last.xml", $page."\n");
        }

        // Restauramos el blog en el que nos encotramos
        restore_current_blog();
    }

    // Funcion para sindicar todos los posts de un blog


    public function map_template($template, $post_id, $blog_id, $actual_link)
    {
        $ogimagen = get_post_meta($post_id, '_webdados_fb_open_graph_specific_image', true);
        $image_url = get_post_meta($post_id, 'image_url', true);
        $imgHeader = get_post_meta($post_id, 'imgHeader', true);
        $blogurl = get_site_url($blog_id);

        $urlBase = 'https://www.mujerhoy.com/contentfactory/post';
        $defaultImg = 'https://www.mujerhoy.com/contentfactory/post/wp-content/uploads/sites/4/2019/11/fondo-textura2.png';

        $imagenSindicacion = '';

        if ($ogimagen) {
            $imagenSindicacion = $urlBase . $ogimagen;
        } else {
            $imagenSindicacion = $defaultImg;
        }
        
        $post_synd=get_post($post_id);
        $genero = get_post_type($post_id);
        
        // $content = apply_filters('the_content', $post_synd->post_content);
        $content = wp_filter_nohtml_kses($content); // this wp_filter_nohtml_kses indicates strip_tags
        $content = do_shortcode($content);
        
        $re = '/\[vc_(.*?)\]/m';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $nuevoarray = [];
        for ($i = 0; $i<sizeof($matches); $i++) {
            array_push($nuevoarray, $matches[$i][0]);
        }
        $onlytext = str_replace($nuevoarray, "", $content);
        
        
        $re = '/\[\/vc_(.*?)\]/m';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        $nuevoarray = [];
        for ($i = 0; $i<sizeof($matches); $i++) {
            array_push($nuevoarray, $matches[$i][0]);
        }
        $onlytext2 = str_replace($nuevoarray, "", $onlytext);
        
        //Etiquetas del post

        $template=str_replace("%%recid%%", $blog_id."_".$post_id, $template);
        $template=str_replace("%%blogid%%", $blog_id, $template);
        $template=str_replace("%%postid%%", $post_id, $template);
        $template=str_replace("%%title%%", get_the_title($post_id), $template);
        $template=str_replace("%%content%%", $onlytext2, $template);
        $template=str_replace("%%blog_url%%", get_site_url($blog_id), $template);
        $template=str_replace("%%blog_title%%", get_bloginfo('name'), $template);
        
        
        $template=str_replace("%%blog_description%%", get_bloginfo('description'), $template);

        $posttags = wp_get_post_terms($post_id);
        $etiquetas = '';
        if ($posttags) {
            foreach ($posttags as $tag) {
                $etiquetas .= '<etiqueta>'.$tag->name.'</etiqueta>'."\n";
            }
        }
        $template=str_replace("%%etiqueta%%", $etiquetas, $template);


        //Autor
        $author_id=$post_synd->post_author;
        $template=str_replace("%%author_name%%", get_the_author_meta('display_name', $author_id), $template);
        $template=str_replace("%%author_email%%", get_the_author_meta('user_email', $author_id), $template);
        $template=str_replace("%%author_url%%", get_the_author_meta('user_url', $author_id), $template);
        $template=str_replace("%%author_avatar%%", get_avatar_url($author_id), $template);
        $template=str_replace("%%post_url%%", $actual_link, $template);
        $template=str_replace("%%created_date%%", get_post_time('Y-m-d\TH:i:s\Z', true, $post_id, false), $template);
        $template=str_replace("%%publish_date%%", get_post_time('Y-m-d\TH:i:s\Z', true, $post_id, false), $template);
        $template=str_replace("%%modified_date%%", get_post_modified_time('Y-m-d\TH:i:s\Z', true, $post_id), $template);
        $template=str_replace("%%post_image%%", get_the_post_thumbnail_url($post_id, 'full'), $template);
        $template=str_replace("%%image%%", $imagenSindicacion, $template);

        return $template;
    }


    public function sindicarTodos()
    {
        global $wp;
        global $post;
        global $wp_query;
        $loop = new WP_Query(array( 
            'post_type' => 'capitulo', 
            'posts_per_page' => -1, 
            'post_status' => 'publish',
            'update_post_meta_cache' => false,
            'no_found_rows' => true,
            'update_post_term_cache' => false,
        ));
        while ($loop->have_posts()) : $loop->the_post();



        $this->enviarSindicador(get_the_ID());



        endwhile;
        wp_reset_query();

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

    // Eliminar del Sindicador

    public function eliminarSindicador()
    {
        global $post;

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

        $idBLog = get_current_blog_id();
        //$idBLog = get_blog_details( $blog_id );
        $id_post = get_the_ID();
        $recid = $idBLog.".".$id_post;
        // Si el valor del id Post es vacio no se elimina el hook salto de una pagina o algo similar

        if (isset($id_post) || !empty($id_post)) {
            $URL = $this->urlSindicar($actual_link);
            $URL .= "?".$recid;
            // Peticion para eliminar del sindicador
            $ch = curl_init($URL);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
        }
    }

    // Genera la URL del sindicador dependiendo de en que medio nos encontremos

    public function urlSindicar($actual_link)
    {
        $contentMujerHoy = strpos($actual_link, 'mujerhoy');

        // foreach($medios as $medio){
        // 	if($medio->slug == 'mujerhoy')
        // 	{
        // 		$url_synd="buscadorpbl.vocento.in/krammer/mujerhoy/blogs/publish";
        // 		return  $url_synd;
        // 	}else{
        // 		return true;
        // 	}
        // }
        if ($contentMujerHoy !== false) {
            // $url_synd="buscadorpbl.vocento.in/krammer/mujerhoy/blogs/publish";
            $url_synd="/krammer/mujerhoy/v2/blogs/publish";
            return  $url_synd;
        } else {
            return true;
        }
    }
}

$pm = new XML_Sindicacion();

?>
