<?php
class VocentocomListadoAccionistasPlugin
{
    //el constructor agrega el shortcode
	public function __construct()
    {
        //lista para junta de accionistas
		add_shortcode( 'vccom_lists_accionistas', array( $this, 'vccom_lists' ) );
        //menu para junta de accionistas
		add_shortcode( 'vccom_menu_accionistas', array( $this, 'vccom_menu' ) );
        //menu para fecha de posts
		add_shortcode( 'vccom_memorias', array( $this, 'vccom_memorias' ) );
		add_shortcode( 'vccom_horizontal', array( $this, 'vccom_horizontal' ) );
    }

	public function voccom_get_posts($atts){


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

            $args['date_query'] = array(
                array(
                    'year'  => date("Y")
                )
            );

        }

        $categoryFound = $this->CategoryVoc($atts['category']);
        $catUrl = $this->CategoryVoc($yearURL);

        if($categoryFound!=false){
            $categoriesYears = get_categories( array( 'parent' => $categoryFound->term_id,'orderby' => 'name','order' => 'DESC',  ));
            $con = strpos($atts['years'],$yearURL);
            if(is_numeric(strpos($atts['years'],$yearURL))){
                $catsSubats = array(  $categoryFound->term_id, $yearURL );
            }elseif(!isset($atts['years'])){
                if(is_numeric($yearURL) || (is_numeric(strpos($yearURL, 'extra'))))
                    $catsSubats = array(  $categoryFound->term_id, $yearURL );
                else
                    $catsSubats = array(  $categoryFound->term_id, $categoriesYears[0]->name );
            }elseif(isset($catUrl->term_id) && is_numeric(strpos($atts['years'],$categoriesYears[0]->name ))){
                $catsSubats = array(  $categoryFound->term_id, $categoriesYears[0]->name);
            }else{
                $catsSubats = array(  $categoryFound->term_id, $categoriesYears[0]->name);
            }



            $args['cat'] = array(
                $categoryFound->term_id
            );

            $args['tax_query'] = array(
                    array(
                        'taxonomy'  => ($atts["taxonomy"])?$atts["taxonomy"]:"category",
                        'field'     => 'name',
                        'terms'     => $catsSubats,
                        'operator' => 'IN',
                        'include_children' => true,
                    ),
                );

            unset($args['date_query']);

            $query = new WP_Query( $args );

            $posts_array =  $query->get_posts($args);


        }
        if(isset($catsSubats))
            return $posts_array;
        else
            return false;
	}

	public function vccom_memorias($atts)
	{
		global $wp;

        $args = array(
            'posts_per_page'   => isset($atts["count"])?$atts["count"]:3,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_status'      => 'publish',
			'post_type' 	   => 'memoria'
        );

		$query = new WP_Query( $args );
        $posts_array =  $query->get_posts($args);
		return $this->voccom_display_memorias($posts_array,$atts);



	}


    public function voccom_display_memorias($posts_array,$atts){
        $blog_id = get_current_blog_id();
        if($atts['slide']){
            $url = plugin_dir_url( __FILE__ );
            wp_enqueue_script( 'jqueryMinVoc', 'http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js', array( 'jquery' ) );
            wp_enqueue_script( 'bxSlider', $url.'assets/slider/jquery.bxslider.js', array( 'jquery' ) );
            wp_enqueue_style( 'style-name', $url.'/assets/slider/jquery.bxslider.css' );
            wp_enqueue_script( 'vocHorizontalCarousel', $url.'assets/slider/vocHorizontalCarousel1.js', array( 'jquery' ) );
            $html_line='<div class="et_pb_row_inner et_pb_row_inner_3 et_pb_row_1-4_1-4_1-4 '.(isset($atts["css"])?$atts["css"]:"").'"><div class="slider1">';
            $slideVoc = 'slide';

        }else{
            $html_line='<div class="et_pb_row_inner et_pb_row_inner_3 et_pb_row_1-4_1-4_1-4 '.(isset($atts["css"])?$atts["css"]:"").'">';
            $slideVoc = '';
        }
		foreach( $posts_array as $post )
        {
			$html_line.='<div class="et_pb_column et_pb_column_1_'.(isset($atts["columns"])?$atts["columns"]:"4").' et_pb_column_inner  et_pb_column_inner_3 '.$slideVoc.'">';

			$feat_image_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

			if($feat_image_url){
				$html_line.='<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_left et_pb_image_3 et_always_center_on_mobile et-animated">';
				$html_line.='<img src="'.$feat_image_url.'" alt="">';
				$html_line.='</div>';
			}

			$html_line.='<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_5 222">';
			$html_line.='<p><strong>'.$post->post_title.'</strong></p>';
			$html_line.='</div>';
			$html_line.='<!-- .et_pb_text -->';

			$attachs=$this->vccom_get_attachments_memorias($post);
			foreach($attachs as $attach){

				$extension=pathinfo(wp_get_attachment_url( $attach->ID), PATHINFO_EXTENSION);
                $blog_id = get_current_blog_id();
                if( $blog_id == 11 )
                {
                    $label='Download '. strtoupper($extension);
                }else{
                    $label='Descargar '. strtoupper($extension);
                }

                $label=($atts["button_text"])?$atts["button_text"]:$label;


				//button descarga adjuntos
				$html_line.='<div class="et_pb_button_module_wrapper et_pb_module">';
				$html_line.='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light 666"  target="_blank" href="'.  wp_get_attachment_url( $attach->ID).'" data-icon="&quot;">'. strtoupper($label).'</a>';
				$html_line.='</div>';

			}

			//button online
			$enlace=get_post_meta($post->ID,'enlace_memoria_online',true);
			if($enlace){

                $html_line.='<div class="et_pb_button_module_wrapper et_pb_module">';
                    if( $blog_id == 11 )
                    {
                        $html_line.='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_1 et_pb_module et_pb_bg_layout_light" href="'.$enlace.'" target="_blank" data-icon="&quot;">WATCH ONLINE</a>';
                    }else{
                        $html_line.='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_1 et_pb_module et_pb_bg_layout_light" href="'.$enlace.'" target="_blank" data-icon="&quot;">VER ONLINE</a>';
                    }

				$html_line.='</div>';
			}


			$html_line.='</div>';
			$html_line.='<!-- .et_pb_column.... -->';

		}

		$html_line.='</div>';
        if($atts['slide']){
            $html_line.='</div>';
        }
		return $html_line;

    }

	//genera las listas
	public function vccom_lists($atts)
	{
        if(isset ($atts['category'])){


            $posts_array=$this->voccom_get_posts($atts);
            $html .= $this->voccom_display_posts($posts_array,$atts);
            //$html .= $this->voccom_display_memorias($posts_array,$atts);

            return $html;


        }else{

            return "Debe insertar una categoria en el shortcode para poder visualizarla";
        }

	}



	//genera las listas
	public function vccom_horizontal($atts)
	{
        if(isset ($atts['category'])){


            $posts_array=$this->voccom_get_posts($atts);

            $html .= $this->voccom_display_memorias($posts_array,$atts);

            return $html;


        }else{

            return "Debe insertar una categoria en el shortcode para poder visualizarla";
        }

	}


    public function vccom_menu($atts)
	{
        if(isset ($atts['category'])){
            global $wp;
            //extrayendo año
            $yearURL = $this->yearUrl($_SERVER['REQUEST_URI']);
            if(isset($yearURL)){
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


            $categoryFound = $this->CategoryVoc($atts['category']);

            if($categoryFound!=false){

                $categoriesYears = get_categories( array( 'parent' => $categoryFound->term_id ));

                $catsSubats = array(  $categoryFound->term_id, $args['date_query'][0]['year'] );

                $args['cat'] = array(
                    $categoryFound->term_id
                );

                $args['tax_query'] = array(
                        array(
                            'taxonomy'  => 'category',
                            'field'     => 'name',
                            'terms'     => $catsSubats,
                            'operator' => 'IN',
                            'include_children' => true,
                        ),
                    );

                unset($args['date_query']);

                $html = '';

                if(isset($categoriesYears)){
                    if($atts['years']){
                        $inputYears = $atts['years'];
                    }else{
                        $inputYears = false;
                    }
                    $html.=$this->voccom_display_years($categoriesYears, $inputYears);
                }

                return $html;

            }else{

                return "La categoría insertada no se encuentra, por favor revise el shortcode";
            }
        }else{

            return "Debe insertar una categoria en el shortcode para poder visualizarla";
        }

	}

    //devuelve el menú con los años que contiene la categoria
	public function voccom_display_years($cat, $inputYears){
		$html='<div class=" et_pb_row_inner et_pb_row_inner_0">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner  et_pb_column_inner_0">

				<div id="botones-anual" class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_4">';
        //extrayendo año
        $yearURL = $this->yearUrl($_SERVER['REQUEST_URI']);
        foreach($this->voccom_getyears($cat, $inputYears) as $year){
            $selecionado = "";
            if(isset($yearURL) && ctype_digit ( $yearURL ) || strpos($yearURL,'extra')!= false){
                if($yearURL== $year){
                    $selecionado = "selected";
                }
                $html.='<a href="./?'.$year.'/" class="small-button smallblue 1 '.$selecionado.'">'.$year.'</a>&nbsp';
            }
            else{
                $html.='<a href="./?'.$year.'/" class="small-button smallblue 2 '.$selecionado.'">'.$year.'</a>&nbsp';
            }
        }


		$html.='</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
			</div>';

        return $html;

	}




    //añade al html los post pasados por parametros
    public function voccom_display_posts($posts_array,$atts){

		$blog_id = get_current_blog_id();
        $html_line='<div class=" et_pb_row_inner et_pb_row_inner_3">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner  et_pb_column_inner_4">

				<div class="et_pb_module et_pb_space et_pb_divider et_pb_divider_5"></div>
			</div> <!-- .et_pb_column -->
			</div>';
        if(isset($posts_array[0])){

            foreach( $posts_array as $post )
            {
                $cat3Obs = wp_get_post_categories($post->ID);
                if($old3Cat!=$cat3Obs[1] && $atts['display_subcat']=="1"){
                    $html.= '<div class=" et_pb_row_inner et_pb_row_inner_2 et_pb_gutters1">
				<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_2">

				                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_5 444">

                <p><strong class="category-voc-list">'.get_cat_name($cat3Obs[2]).'</strong></p>
                <p>&nbsp;</p>

			    </div> <!-- .et_pb_text -->
			    </div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_3 et_pb_column_empty">


			    </div> <!-- .et_pb_column -->
			    </div>';
                    $old3Cat = $cat3Obs[2];
                }

                $html .=$html_line;
                $html .='<div class=" et_pb_row_inner et_pb_row_inner_2 et_pb_gutters1">';
                $html .='<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner  et_pb_column_inner_2">';
                $html .='<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_5 666">';

                if(!isset($atts['links'])){
                    $html .='<p><strong><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'. $post->post_title.'</a></strong><br>';
                }
                else{
                    $html .='<p class="1"><strong>'. $post->post_title.'</strong><br>';
                }


                if(isset($atts["display_date"])){
                    //$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
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

                $attach=$this->vccom_get_attachments($post);

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
                    if( $blog_id== 11 )
                    {
                        $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" href="'. get_permalink( $post->ID).'" data-icon="&quot;">SEE CONTENT</a>';
                    }else{
                        $html .='<a class="et_pb_button et_pb_custom_button_icon  et_pb_button_0 et_pb_module et_pb_bg_layout_light" href="'. get_permalink( $post->ID).'" data-icon="&quot;">VER CONTENIDO</a>';
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

    //obienen los años de las categorias
	public function voccom_getyears($cat, $inputYears){
		//array de valores que devolverá
	    $years=array();
        //comprobamos si se han pasado años a mostrar por el shortcode
        if($inputYears!=false)$inputYears = explode(",", $inputYears);
        //insertamos un valor en la primera posición, ya que la posicion 0 nunca la busca en el array de búsqueda
        if($inputYears!=false)array_unshift(	$inputYears,"valor");
		$categories=$cat;
        //bucle para componer el array de años que se motrará en la pantalla, buscamos coincidencias o lo componemos con todos los años
		foreach ($categories as $c) {
            if($inputYears!=false){
                $YearFound = array_search($c->cat_name,$inputYears,false);
                if($YearFound!=false){
                    array_push(	$years,$c->cat_name);
                }
		    }else{
                array_push(	$years,$c->cat_name);
            }
        }
        //ordenamos array
        arsort($years);
        //devolvemos array
        return $years;
    }

    //obtiene los adjuntos de los post
	public static function vccom_get_attachments($post){
		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_parent' => $post->ID,
			'exclude'     => get_post_thumbnail_id()
		) );

		if ( $attachments ) {
			return $attachments[0];
		}
	}


	public function vccom_get_attachments_memorias($post){
		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'post_parent' => $post->ID,
			'exclude'     => get_post_thumbnail_id()
		) );

		if ( $attachments ) {
			return $attachments;
		}
	}

    public function CategoryVoc($cat){

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
    // public function yearUrl($actual_link){
    //     //extrayendo año
    //     $posBarra = strrpos($actual_link, '/', -2);
    //     $yearURL = substr($actual_link, $posBarra);
    //     $yearURL = str_replace ("/", "", $yearURL );

    //     return $yearURL;
    // }

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


}//end Class
