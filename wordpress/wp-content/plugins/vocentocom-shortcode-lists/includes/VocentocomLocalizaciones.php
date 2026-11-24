<?php
class VocentocomLocalizaciones
{
    //el constructor agrega el shortcode
	public function __construct()
    {
      add_shortcode( 'vccom_localizacion', array( $this, 'vccom_localizacion' ) );
	  }
	
		public function voccom_get_posts($atts){

		
        global $wp;	
        $posts_array =array(); 
        $args = array(
           'posts_per_page'   => isset($atts["count"])?$atts["count"]:-1,
           'orderby'          => 'date',
           'order'            => 'DESC',
           'post_status'      => 'publish',
		   'post_type' 	   => 'localizacion'
           
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
	
	public function vccom_localizacion($atts)
	{
		
        $posts_array =  $this->voccom_get_posts($atts);
		return $this->voccom_display_localizacion($posts_array,$atts);
		
		
        
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
	
	
    public function voccom_display_localizacion($posts_array,$atts){ 
		$html_line="";
        if(isset($posts_array[0]) && $posts_array[0]!=null){
            foreach( $posts_array as $post )
            {
                $html_line.=$post->post_content;
            }
            return $html_line;  
        }else{
            return  "<h3>No existe localización en este año</h3>";
        }
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
        
}//end Class
