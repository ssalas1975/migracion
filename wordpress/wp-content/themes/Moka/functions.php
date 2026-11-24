<?php

add_theme_support( 'post-thumbnails' );
show_admin_bar(false);
add_image_size( 'project-single', 960, 650, true );
add_image_size( 'project-list', 654, 476, true );
add_image_size( 'project-gallery', 480, 273, true );


//For YOAST
if(!function_exists('wp_parse_url')){
    function wp_parse_url(){return false;}
}

remove_filter('template_redirect', 'redirect_canonical');
add_filter('redirect_canonical', 'moka_redirect_canonical', 10, 2);
function moka_redirect_canonical($redirect_url, $requested_url) {
	return $requested_url;
}
 

add_action('init', 'moka_change_page_permalink', -1);
function moka_change_page_permalink() {
    global $wp_rewrite;
    if ( strstr($wp_rewrite->get_page_permastruct(), '.html') != '.html' )
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
}


function moka_custom_rewrite_basic() {
    add_rewrite_rule('^blog/?$', 'index.php?page_id=7', 'top');
}

add_action('init', 'moka_custom_rewrite_basic');

add_filter('the_content',function($the_content){
	
	if(is_single()){
		
		$the_content= preg_replace("/\[embed\]/x",'[embed width="960" height="540"]', $the_content);
		
	
	}
	
    return  $the_content;
},1);


add_filter('the_content',function($the_content){
	
	if(is_single()){
	
		
		$the_content= str_replace('width="960" height="540"','width="100%" height="540"', $the_content);
		$the_content= str_replace('width="500" height="281"','width="100%" height="540"', $the_content);
		
	}
	
    return  $the_content;
},10);

add_action( 'rewrite_rules_array', 'moka_rewrite_rules' );
function moka_rewrite_rules( $rules ) {
    $new_rules = array();
    //$new_rules[ 'project' . '/([^/]+)\.html$' ] = 'index.php?post_type=' . 'proyecto' . '&name=$matches[1]';
	$new_rules[ 'projects' . '/([^/]+)\.html$' ] = 'index.php?post_type=' . 'proyecto' . '&name=$matches[1]';
    return $new_rules + $rules;
}

add_filter( 'post_type_link', 'moka_custom_post_permalink',10 ); // for cpt post_type_link (rather than post_link)
function moka_custom_post_permalink ( $post_link ) {
    global $post;

    $type = get_post_type( $post->ID );

    if($type=="proyecto")
        return home_url( $post_link. '.html' );

        

    return $post_link;
}


if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_post',
		'title' => 'Post',
		'fields' => array (
			array (
				'key' => 'field_5a97dc622111f',
				'label' => 'Subtitulo',
				'name' => 'subtitulo',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97dc7121120',
				'label' => 'Autor',
				'name' => 'autor',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97dc8521121',
				'label' => 'Imagen listado',
				'name' => 'imagen_listado',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'instructions' => 'Tamaño óptimo:  654 x 476',
				'placeholder' => 'Tamaño óptimo:  654 x 476',
			),
			array (
				'key' => 'field_5a97dc9c21122',
				'label' => 'Imagen detalle',
				'name' => 'imagen_detalle',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'instructions' => 'Tamaño óptimo:  960 x 650',
				'placeholder' => 'Tamaño óptimo:  960 x 650',
			),
			array (
				'key' => 'field_5a97dca721123',
				'label' => 'Video detalle',
				'name' => 'video_detalle',
				'type' => 'text',
				'instructions' => 'URL del EMBED del video',
				'default_value' => '',
				'placeholder' => 'URL del EMBED del video',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97dd0221124',
				'label' => 'Galeria',
				'name' => 'galeria',
				'type' => 'photo_gallery',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_proyecto',
		'title' => 'Proyecto',
		'fields' => array (
			array (
				'key' => 'field_5a97dd228ac5d',
				'label' => 'Subtitulo',
				'name' => 'subtitulo',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97dd2b8ac5e',
				'label' => 'Autor',
				'name' => 'autor',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97dd308ac5f',
				'label' => 'Imagen listado',
				'name' => 'imagen_listado',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'instructions' => 'Tamaño óptimo:  654 x 476',
				'placeholder' => 'Tamaño óptimo:  654 x 476',
			),
			array (
				'key' => 'field_5a97dd468ac60',
				'label' => 'Imagen detalle',
				'name' => 'imagen_detalle',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'instructions' => 'Tamaño óptimo:  960 x 650',
				'placeholder' => 'Tamaño óptimo:  960 x 650',
			),
			array (
				'key' => 'field_5a97dd508ac61',
				'label' => 'Video detalle',
				'name' => 'video_detalle',
				'type' => 'text',
				'instructions' => 'URL Embed del video',
				'default_value' => '',
				'placeholder' => 'URL Embed del video',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'proyecto',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}


function moka_register_menu() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Menu principal' )
     )
   );
 }
add_action( 'init', 'moka_register_menu' );

add_filter('instant_articles_content','cf_fbia_format',10,1);

add_filter('moka_default_image',function($url){


	if(strpos($url,'default.')!==false){
		//return '/wp-content/themes/Moka/default.jpg';
		return false;
	}
	return $url;

},10,1);

if(!function_exists('get_the_post_thumbnail_url')){

		function get_the_post_thumbnail_url($size){
			$thumb_id = get_post_thumbnail_id();
			$thumb_url = wp_get_attachment_image_src($thumb_id,$size, true);
			return apply_filters('moka_default_image',$thumb_url[0]);
		}

}

if(!function_exists('wp_get_attachment_image_srcset')){

		function wp_get_attachment_image_srcset(){
			return false;
		}

}


function moka_get_thumbnail_url($id,$size){
			$thumb_id =$id;
			$thumb_url = wp_get_attachment_image_src($thumb_id,$size, true);

			return $thumb_url[0];
}





function moka_make_relative($url){

	if(!is_admin()){
		$re = '/http(s)?:\/\/([a-z0-9\-.]+)\.([a-z]){2,3}\//';
		$url_temp=preg_replace($re,'/',$url);
		return $url_temp;
	}else{
		return $url;
	}
	
}




add_filter('script_uri', 'moka_make_relative');
add_filter('stylesheet_uri', 'moka_make_relative');
add_filter('bloginfo_url', 'moka_make_relative');
add_filter('bloginfo', 'moka_make_relative');
add_filter('post_link', 'moka_make_relative');
add_filter('category_link', 'moka_make_relative');
add_filter('category_feed_link', 'moka_make_relative');
add_filter('wp_get_attachment_url', 'moka_make_relative');
add_filter('wp_get_attachment_thumb_url', 'moka_make_relative');
add_filter('post_comments_feed_link', 'moka_make_relative');
add_filter('author_link', 'moka_make_relative');
add_filter('author_feed_link', 'moka_make_relative');
add_filter('plugins_url', 'moka_make_relative');
add_filter('bloginfo_rss', 'moka_make_relative');
add_filter('home_url', 'moka_make_relative');
add_filter('script_loader_src', 'moka_make_relative');
add_filter('style_loader_src', 'moka_make_relative');
add_filter('stylesheet_directory_uri', 'moka_make_relative');

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_post',
		'title' => 'Post',
		'fields' => array (
			array (
				'key' => 'field_5a97ce0291539',
				'label' => 'Subtitulo',
				'name' => 'subtitulo',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97ce149153a',
				'label' => 'Autor',
				'name' => 'autor',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97ce209153b',
				'label' => 'Imagen listado',
				'name' => 'imagen_listado',
				'type' => 'image',
				'required' => 1,
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_5a97ce679153c',
				'label' => 'Imagen detalle',
				'name' => 'imagen_detalle',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_5a97ce789153d',
				'label' => 'Video detalle',
				'name' => 'video_detalle',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Video URL',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97ccb6b9afe',
				'label' => 'Imagenes lateral',
				'name' => 'imagenes_lateral',
				'type' => 'photo_gallery',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_proyecto',
		'title' => 'Proyecto',
		'fields' => array (
			array (
				'key' => 'field_5a97d830f92e3',
				'label' => 'Subtitulo',
				'name' => 'subtitulo',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97d846f92e4',
				'label' => 'Autor',
				'name' => 'autor',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a97d855f92e5',
				'label' => 'Imagen listado',
				'name' => 'imagen_listado',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_5a97d85ef92e6',
				'label' => 'Imagen detalle',
				'name' => 'imagen_detalle',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_5a97d870f92e7',
				'label' => 'Video detalle',
				'name' => 'video_detalle',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Video URL EMBED',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'proyecto',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}



add_filter('moka_logo_header',function($image){
    global $post;

    if(!is_home() && (strtolower($post->post_name)=="about" || strtolower($post->post_name)=="blog" || $post->post_type=="post")){
        return 'logo-moka-blog.svg';
    }
    return $image;

},10,1);

add_filter('moka_data_menu',function($empty){
    global $post;

    if(strtolower($post->post_name)=="contact"){
        return ' data-contact="true"';
    }
    return "";

},10,1);


add_filter('moka_menu_icon',function($image){
    global $post;

    if(strtolower($post->post_name)=="contact"){
        return 'hamb-contacto.png';
    }
    return $image;

},10,1);

function moka_get_image_gallery($imgid){

	return moka_get_thumbnail_url($imgid,'project-gallery');

}

function moka_get_image_listado($postID){

	$image=get_field('imagen_listado',$postID);

	if(!$image){
		if(function_exists('get_the_post_thumbnail_url'))
			$image= get_the_post_thumbnail_url($postID,'project-list');
	}
	else{
		$image= moka_get_thumbnail_url($image,'project-list');

	}

	 return apply_filters('moka_default_image',$image);

}

function moka_get_image_detalle($postID){

	$image=get_field('imagen_detalle',$postID);



	if(!$image){
		if(function_exists('get_the_post_thumbnail_url'))
			$image= get_the_post_thumbnail_url($postID,'project-single');

	}
	else{
		$image= moka_get_thumbnail_url($image,'project-single');

	}
	 return apply_filters('moka_default_image',$image);


}


function moka_exists_posts(){

	$args = array(
		'numberposts' => 1,
		'post_type'   => 'post'
	);

	$p = get_posts( $args );
	return ($p && count($p)>0);
}

function moka_exists_projects(){

	$args = array(
		'numberposts' => 1,
		'post_type'   => 'proyecto'
	);

	$p = get_posts( $args );
	return ($p && count($p)>0);
}


function moka_get_posts_paged($ipp,$page){

    $args = array(
        'post_status' => 'publish',
        'post_type' => 'post',
        'posts_per_page'=>$ipp,
        'offset'=>($page*$ipp),
    );


	$query = new WP_Query($args);
	$posts=$query->posts;
	$more_pages=($query->found_posts > $ipp && ($query->found_posts>(($page+1)*$ipp)));
	return array('posts'=>$posts,'more_pages'=>$more_pages);
}

function moka_limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
}

function moka_get_video_url($videoURL){
	
		if(!$videoURL)
			return false;
	
		//youtube
		if (strpos($videoURL, 'youtube') !== false) {
			$re = '/v=(.[^&]+)/';
			$str = $videoURL;
			preg_match_all($re, $str, $matches);
			if(count($matches)>=1){
					return "https://www.youtube.com/embed/".$matches[1][0];
			}
		}
		
		//vimeo
		if (strpos($videoURL, 'vimeo') !== false) {
			$re = '/([0-9]+)/';
			$str = $videoURL;
			preg_match_all($re, $str, $matches);
			if($matches){
					return "https://player.vimeo.com/video/".$matches[0][0];
			}
		}
		
		return false;
}


?>
