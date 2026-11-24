<?php

class VocentocomListadoPlugin
{
    private $accionistaHelper;

    //el constructor agrega el shortcode
	public function __construct()
    {
        //incluyendo para llamar a otras funciones
        require_once 'VocentocomListadoAccionistasPlugin.php';
        // instantiate helper plugin
        $this->accionistaHelper = new VocentocomListadoAccionistasPlugin();
        //listas por fecha de posts
        add_shortcode( 'vccom_lists', array( $this, 'vccom_lists_date' ) );
        //menu para fecha de posts
        add_shortcode( 'vccom_menu', array( $this, 'vccom_menu_date' ) );
    }
	public function voccom_get_posts_date($atts){

        global $wp;
        $posts_array =array();
        $args = array(
           'posts_per_page'   => isset($atts["count"])?$atts["count"]:-1,
           'orderby'          => 'date',
           'order'            => 'DESC',
           'post_status'      => 'publish'

       );

        if( $atts["tags"]){
            $args['tag']= $atts["tags"];
        }

        if(is_numeric($atts['category'])){
            $args['cat']= (int)$atts['category'];
        }
        else{
            $args['category_name']= $atts['category'];
        }

        $yearURL = $this->yearUrl($_SERVER['REQUEST_URI']);

        if(isset($yearURL) && is_numeric($yearURL)){
            $args['date_query'] = array(
                array(
                    'year'  => $yearURL
                )
            );
        }
        else{
            global $wp;
            global $wpdb;

            //extraer años de posts de bbdd
            //$catsSubats = $wpdb->get_results( 'SELECT distinct year(post_date) as year FROM '. $wpdb->posts);
            //$catsSubats = array(  $categoryFound->term_id, $args['date_query'][0]['year'] );

            $categoryFound = $this->CategoryVocResult($atts['category']);
            $cat_id=$categoryFound->term_id;
            $catsSubats = $wpdb->get_results( "SELECT
				distinct year(post_date) as year FROM $wpdb->posts p
				JOIN $wpdb->term_relationships tr ON (p.ID = tr.object_id)
				JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
				JOIN $wpdb->terms t ON (tt.term_id = t.term_id)
				WHERE p.post_type='post'
				AND p.post_status = 'publish'
				AND tt.taxonomy = 'category'
				AND t.term_id = $cat_id ORDER BY year(post_date) DESC");

            if($atts['category'] != 'presentaciones-corporativas'){
                $args['date_query'] = array(
                    array(
                        'year'  => $catsSubats[0]->year
                    )
                );
            }

        }
        if($atts['years']){
            $args['date_query'] = array(
                array(
                    'year'  => $atts['years']
                )
            );
        }


		//si se define ultimos posts se quita el filtro de año
		if(isset($atts["lastest"])){
			unset($args['date_query']);
		}

        $categoryFound = $this->CategoryVocResult($atts['category']);

        $args['cat'] = array(
            $categoryFound->term_id
        );


        $query = new WP_Query( $args );

        $posts_array =  $query->get_posts($args);

        return $posts_array;
	}
    //genera las listas con fechas de posts
	public function vccom_lists_date($atts)
	{
        
        if(isset ($atts['category'])){

            $posts_array=$this->voccom_get_posts_date($atts);

            $html = '';
        $html .= $this->voccom_display_posts($posts_array, $atts);

            return $html;


        }else{

            return "Debe insertar una categoria en el shortcode para poder visualizarla";
        }

	}


    //genera menús con fechas de posts de categorias
    public function vccom_menu_date($atts)
	{
        if(isset ($atts['category'])){
            global $wp;
            global $wpdb;
            //extrayendo año
            $yearURL = $this->yearUrl($_SERVER['REQUEST_URI']);
            if(isset($yearURL) && is_numeric($yearURL)){
                $args['date_query'] = array(
                    array(
                        'year'  => $yearURL
                    )
                );
            }
            else{

                $args['date_query'] = array(
                    array(
                        'year'  => date("Y")
                    )
                );

            }


            $categoryFound = $this->CategoryVocResult($atts['category']);

            if($categoryFound!=false){
                $blog_prefix = $wpdb->get_blog_prefix(BLOG_ID_CURRENT_SITE);
                //extraer años de posts de bbdd
                //$catsSubats = $wpdb->get_results( 'SELECT distinct year(post_date) as year FROM '. $wpdb->posts);
                //$catsSubats = array(  $categoryFound->term_id, $args['date_query'][0]['year'] );
                $cat_id=$categoryFound->term_id;

				$catsSubats = $wpdb->get_results( "SELECT
				distinct year(post_date) as year FROM $wpdb->posts p
				JOIN $wpdb->term_relationships tr ON (p.ID = tr.object_id)
				JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
				JOIN $wpdb->terms t ON (tt.term_id = t.term_id)
				WHERE p.post_type='post'
				AND p.post_status = 'publish'
				AND tt.taxonomy = 'category'
				AND t.term_id = $cat_id");

				$args['cat'] = array(
                    $categoryFound->term_id
                );

                $args['tax_query'] = array(
                        array(
                            'taxonomy'  => 'category',
                            'field'     => 'name',
                            'terms'     => $atts['category'],
                            'operator' => 'IN',
                            'include_children' => true,
                        ),
                    );

                //unset($args['date_query']);

                $html = '';

                if(isset($catsSubats)){
                    if($atts['years']){
                        $inputYears = $atts['years'];
                    }else{
                        $inputYears = false;
                    }
                    $html.=$this->voccom_display_years_date($catsSubats, $inputYears );
                }

                return $html;

            }else{

                return "La categoría insertada no se encuentra, por favor revise el shortcode";
            }
        }else{

            return "Debe insertar una categoria en el shortcode para poder visualizarla";
        }

	}




    //devuelve el menú con los años que contiene los años de posts existentes
	public function voccom_display_years_date($years, $inputYears){
		$html='<div class=" et_pb_row_inner et_pb_row_inner_0">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner  et_pb_column_inner_0">

				<div id="botones-anual" class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_4">';
        arsort($years);
        //extrayendo año
        if($inputYears!=false){
            $inputYears = explode(",",$inputYears);
            arsort($years);
        }
        $yearURL = $this->yearUrl($_SERVER['REQUEST_URI']);

        foreach($years as $year){
            $selecionado = "";
            if($yearURL== $year->year){
                $selecionado = "selected";
            }
            if(isset($inputYears) && $inputYears!=""){
                               if(in_array( $year->year, $inputYears) && is_numeric($yearURL)){
                    $html.='<a href="./?'.$year->year.'/" class="small-button smallblue a'.$selecionado.'">'.$year->year.'</a>&nbsp';
                }
                else{
                    if(in_array( $year->year, $inputYears))
                        $html.='<a href="./?'.$year->year.'/" class="small-button smallblue b'.$selecionado.'">'.$year->year.'</a>&nbsp';
                }
            }else{
                if(isset($year->year) && is_numeric($yearURL)){
                    $html.='<a href="./?'.$year->year.'/" class="small-button smallblue c'.$selecionado.'">'.$year->year.'</a>&nbsp';
                }else{
                    if(is_object($year))
                        $html.='<a href="./?'.$year->year.'/" class="small-button smallblue d'.$selecionado.'">'.$year->year.'</a>&nbsp';
                }
            }
        }

		$html.='</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
			</div>';

        return $html;

	}

    //añade al html los post pasados por parametros
    public function voccom_display_posts($arrayPost, $atts){

        if($atts['display_subcat']=="1"){
            $postsChilds = $this->displayCatsChilds($atts);
            $html = $postsChilds;
        }else{
        $postsChilds = $this->voccom_display_Cat($arrayPost,$atts);
        $html = $postsChilds;

        }
        return $html;

    }


    public function CategoryVocResult($cat){

		$categoryFound='';
		if(is_numeric($cat)){
			$categoryFound = get_term_by('id', (int)$cat,'category');
		}
		else{
			//primero por slug
            $categoryFound = get_term_by('slug', $cat, 'category');
            //despues por name
            if(!$categoryFound)
                $categoryFound = get_term_by('name', $cat, 'category');

		}


        return $categoryFound;

    }

    //devuelve la categoria año de una URL
    public function yearUrl($actual_link){
        //extrayendo año
        $posBarra = strrpos($actual_link, '/', -2);
        $yearURL = substr($actual_link, $posBarra);
        $yearURL = str_replace ("/", "", $yearURL );
        $yearURL = str_replace("?","", $yearURL);

        return $yearURL;
    }

    //convierte url absolutas en relativas
    function rw_remove_root( $url ) {
        $url = str_replace( home_url(), '', $url );
        return '/' . ltrim( $url, '/' );
    }

    function displayCatsChilds($atts){
        $blog_id = get_current_blog_id();
        $catParentID = $this->CategoryVocResult($atts['category']);
        $html_line='<div class=" et_pb_row_inner et_pb_row_inner_3">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner  et_pb_column_inner_4">

				<div class="et_pb_module et_pb_space et_pb_divider et_pb_divider_5"></div>
			</div> <!-- .et_pb_column -->
			</div>';
        $categories=get_categories( array( 'parent' => $catParentID->term_id) );

		$categoriesDev=array();
        foreach ($categories as $cat) {
            if(strpos($cat->slug, 'diciembre')!=false){
                $categoriesDev[0] = $cat;
            }elseif(strpos($cat->slug, 'septiembre')!=false){
                $categoriesDev[1] = $cat;

            } elseif(strpos($cat->slug, 'junio')!=false){
                $categoriesDev[2] = $cat;

            }elseif(strpos($cat->slug, 'marzo')!=false){
                $categoriesDev[3] = $cat;
            }
        }
        ksort($categoriesDev);

        foreach ($categoriesDev as $cat) {

            $args = array(
            'posts_per_page'   => isset($atts["count"])?$atts["count"]:-1,
            'category__in' => array($cat->term_id),
            'post_status'      => 'publish'
            );
            if( $atts["tags"]){
                $args['tag']= $atts["tags"];
            }
            $yearURL = $this->yearUrl($_SERVER['REQUEST_URI']);

            if(isset($yearURL) && is_numeric($yearURL)){
                $args['date_query'] = array(
                    array(
                        'year'  => $yearURL
                    )
                );
            }
            else{
                global $wp;
                global $wpdb;

                //extraer años de posts de bbdd
                //$catsSubats = $wpdb->get_results( 'SELECT distinct year(post_date) as year FROM '. $wpdb->posts);
                //$catsSubats = array(  $categoryFound->term_id, $args['date_query'][0]['year'] );
                $categoryFound = $this->CategoryVocResult($atts['category']);
                $cat_id=$categoryFound->term_id;
                $catsSubats = $wpdb->get_results( "SELECT
				distinct year(post_date) as year FROM $wpdb->posts p
				JOIN $wpdb->term_relationships tr ON (p.ID = tr.object_id)
				JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
				JOIN $wpdb->terms t ON (tt.term_id = t.term_id)
				WHERE p.post_type='post'
				AND p.post_status = 'publish'
				AND tt.taxonomy = 'category'
				AND t.term_id = $cat_id ORDER BY year(post_date) DESC");

                $args['date_query'] = array(
                    array(
                        'year'  => $catsSubats[0]->year
                    )
                );
                $yearURL = $catsSubats[0]->year;
            }

            if(isset($cat->term_id) ){
                $args['cat'] =  $cat->term_id;
            }

            $my_query = new WP_Query($args);
            $subcat_posts =  $my_query->get_posts();

            if(isset($subcat_posts[0])){
                $html.= '<div class=" et_pb_row_inner et_pb_row_inner_2 et_pb_gutters1">
				<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_2">

				                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_5 777">

                <p><strong class="category-voc-list">'.$cat->name.'</strong></p>
                <p>&nbsp;</p>

			    </div> <!-- .et_pb_text -->
			    </div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_3 et_pb_column_empty">


			    </div> <!-- .et_pb_column -->
			    </div>';
                $old3Cat = $cat;
            }
            foreach($subcat_posts as $post) {

                $html .=$html_line;
                $html .='<div class=" et_pb_row_inner et_pb_row_inner_2 et_pb_gutters1">';
                $html .='<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_2">';
                $html .='<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_5 888">';

                if(!isset($atts['links'])){
                    $html .='<p class="3"><strong><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'. $post->post_title.'</a></strong><br>';
                }
                else{
                    $html .='<p class="3"><strong>'. $post->post_title.'</strong><br>';
                }

                if(isset($atts["display_date"])){
                    if( $blog_id == 11 )
                    {
                        $mes = array("January","February","March","April","May","June","July","August","September","October","November","December");
                    }else{
                        $mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    }

                    if(get_post_meta($post->ID, 'fecha_publica', true)){
                        setlocale(LC_TIME, 'es_ES.UTF-8');
                        $fecha = get_post_meta($post->ID, 'fecha_publica', true);
                        $timestamp = strtotime($fecha);
                        $fecha_espanol = strftime("%d de %B de %Y", $timestamp);
                        $html .= $fecha_espanol;
                    }else{
                        $html .=''. date('j ', strtotime($post->post_date));
                        $html .=''. $mes[date('n', strtotime($post->post_date))-1];
                        $html .=''. date(' Y ', strtotime($post->post_date));
                    }

                    // $html .=''. date('j ', strtotime($post->post_date));
                    // $html .=''. $mes[date('n', strtotime($post->post_date))-1];
                    // $html .=''. date(' Y ', strtotime($post->post_date));
                }
                $html .='</p>';
                $html .='<p>&nbsp;</p>';
                $html .='</div> <!-- .et_pb_text -->';
                $html .='</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_3">';
                $html .='<div class="et_pb_button_module_wrapper et_pb_module et_pb_button_alignment_right">';

                $attach=VocentocomListadoAccionistasPlugin::vccom_get_attachments($post);

                if($attach){
                    $extension=pathinfo(wp_get_attachment_url( $attach->ID), PATHINFO_EXTENSION);
                    $blog_id = get_current_blog_id();
                    if( $blog_id == 11 )
                    {
                        $label='Download '. strtoupper($extension);
                    }else{
                        $label='Descargar '. strtoupper($extension);
                    }


                    $label=($atts["button_text"])?$atts["button_text"]:$label;
                    $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" target="_blank" href="'.wp_make_link_relative( wp_get_attachment_url( $attach->ID)).'" data-icon="&quot;">'.$label.'</a>';
                }
                else{
                    if( $blog_id == 11 )
                    {
                        $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" href="'. get_permalink( $post->ID).'" data-icon="&quot;">See Content</a>';
                    }else{
                        $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" href="'. get_permalink( $post->ID).'" data-icon="&quot;">Ver Contenido</a>';
                    }

                }

                $html .='</div>';
                $html .='</div> <!-- .et_pb_column -->';
                $html .='</div>';

            }
        }
		$custom_class="";
		if(isset($atts['css'])){
			$custom_class=$atts['css'];
		}

        return "<div class='".$custom_class."'>".$html."</div>";

    }


    //añade al html los post pasados por parametros
    public function voccom_display_Cat($posts_array,$atts){
        $blog_id = get_current_blog_id();
        $html_line='<div class=" et_pb_row_inner et_pb_row_inner_3">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner  et_pb_column_inner_4">

				<div class="et_pb_module et_pb_space et_pb_divider et_pb_divider_5"></div>
			</div> <!-- .et_pb_column -->
			</div>';
        if(isset($posts_array[0])){
            foreach( $posts_array as $post )
            {

                $html .=$html_line;
                $html .='<div class=" et_pb_row_inner et_pb_row_inner_2 et_pb_gutters1">';
                $html .='<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_2">';
                $html .='<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_5 999">';

                if(!isset($atts['links'])){
                    $html .='<p><strong><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'. $post->post_title.'</a></strong><br>';
                }
                else{
                    $html .='<p class="2"><strong>'. $post->post_title.'</strong><br>';
                }

                if(isset($atts["display_date"])){
                    if( $blog_id == 11 )
                    {
                        $mes = array("January","February","March","April","May","June","July","August","September","October","November","December");
                    }else{
                        $mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    }

                    if(get_post_meta($post->ID, 'fecha_publica', true)){
                        setlocale(LC_TIME, 'es_ES.UTF-8');
                        $fecha = get_post_meta($post->ID, 'fecha_publica', true);
                        $timestamp = strtotime($fecha);
                        $fecha_espanol = strftime("%d de %B de %Y", $timestamp);

                        $html .= $fecha_espanol;
                    }else{
                        $html .=''. date('j ', strtotime($post->post_date));
                        $html .=''. $mes[date('n', strtotime($post->post_date))-1];
                        $html .=''. date(' Y ', strtotime($post->post_date));
                    }

                    // $html .=''. date('j ', strtotime($post->post_date));
                    // $html .=''. $mes[date('n', strtotime($post->post_date))-1];
                    // $html .=''. date(' Y ', strtotime($post->post_date));

                }
                $html .='</p>';
                $html .='<p>&nbsp;</p>';
                $html .='</div> <!-- .et_pb_text -->';
                $html .='</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_3">';
                $html .='<div class="et_pb_button_module_wrapper et_pb_module et_pb_button_alignment_right">';

                $attach=VocentocomListadoAccionistasPlugin::vccom_get_attachments($post);

                if($attach){
                    $extension=pathinfo(wp_get_attachment_url( $attach->ID), PATHINFO_EXTENSION);
                    $blog_id = get_current_blog_id();
                    if( $blog_id == 11 )
                    {
                        $label='Download '. strtoupper($extension);
                    }else{
                        $label='Descargar '. strtoupper($extension);
                    }

                    $label=($atts["button_text"])?$atts["button_text"]:$label;
                    $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" target="_blank" href="'.wp_make_link_relative( wp_get_attachment_url( $attach->ID)).'" data-icon="&quot;">'.$label.'</a>';
                }
                else{
                    if( $blog_id == 11 )
                    {
                        $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" href="'. get_permalink( $post->ID).'" data-icon="&quot;">See Content</a>';
                    }else{
                        $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" href="'. get_permalink( $post->ID).'" data-icon="&quot;">Ver Contenido</a>';
                    }
                }

                $html .='</div>';
                $html .='</div> <!-- .et_pb_column -->';
                $html .='</div>';

            }
        }else{
            $html = "<h3>No existen registros en este año</h3>";
        }
		$custom_class="";
		if(isset($atts['css'])){
			$custom_class=$atts['css'];
		}

        return "<div class='".$custom_class."'>".$html."</div>";
    }



}//end Class
