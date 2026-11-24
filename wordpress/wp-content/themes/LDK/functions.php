<?php

require_once dirname( __FILE__ ) . '/includes/metaboxes/medio_metabox.php';
require_once dirname( __FILE__ ) . '/includes/metaboxes/canonical_metabox.php';
require_once dirname( __FILE__ ) . '/includes/metaboxes/metadata_metabox.php';
require_once dirname( __FILE__ ) . '/includes/metaboxes/sticky_metabox.php';
require_once dirname( __FILE__ ) . '/includes/widgets.php';


add_theme_support( 'post-thumbnails' );
add_image_size( 'post-list', 240, 140, true );
add_image_size( 'post-featured', 672, 378, true );
add_image_size( 'logo-icon', 65, 65, true );
ldk_get_query_page();






//Metabox de medios
if(is_admin()){
	$ldk_metabox = new LDK_Metabox();
	$ldk_canonical = new LDK_CanonicalMetabox();
    $ldk_metadata = new LDK_Metadata_Metabox();
    $ldk_sticky = new LDK_MetaboxSticky();
}


if(!function_exists('wp_parse_url')){
    function wp_parse_url(){return false;}
}

/*CPT*/
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_categorias',
		'title' => 'Categorias',
		'fields' => array (
			array (
				'key' => 'field_5a6af6e662584',
				'label' => 'P�xel segmento adserver',
				'name' => 'pixel_adserver',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'ef_taxonomy',
					'operator' => '==',
					'value' => 'category',
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
		'id' => 'acf_medio',
		'title' => 'Medio',
		'fields' => array (
			array (
				'key' => 'field_5a55f9647c9f1',
				'label' => 'medio ID',
				'name' => 'id_medio',
				'type' => 'text',
				'instructions' => 'Identificador del medio.ej: elcorreo, diariovasso,ideal,et..',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'elcorreo, diariovasso,ideal,et..',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a5e25b7bb111',
				'label' => 'Titulo Blog Home',
				'name' => 'home_title',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a55fa0daf124',
				'label' => 'Custom css',
				'name' => 'custom_css',
				'type' => 'textarea',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'none',
			),
			array (
				'key' => 'field_5a5603e9e1616',
				'label' => 'Logo',
				'name' => 'logo',
				'type' => 'image',
				'required' => 1,
				'save_format' => 'url',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_5a5603b9e1617',
				'label' => 'Logo blanco',
				'name' => 'logo_white',
				'type' => 'image',
				'required' => 1,
				'save_format' => 'url',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_5a5e25b7ea549',
				'label' => 'URL',
				'name' => 'url',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a5e25c4ea54a',
				'label' => 'Página 404',
				'name' => 'page_404',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a5f28c239173',
				'label' => 'Imagen por defecto meta',
				'name' => 'default_image_share',
				'type' => 'image',
				'instructions' => 'Imagen por defecto para metas rrss',
				'save_format' => 'url',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_5a856ec095555',
				'label' => 'url buscador',
				'name' => 'url_buscador',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'URL del servicio de buscador de empresas para este medio',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a856ee695556',
				'label' => 'telefono',
				'name' => 'telefono',
				'type' => 'number',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5a856f0d95557',
				'label' => 'email formularios',
				'name' => 'email_formularios',
				'type' => 'email',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_5a8bed55a746c',
				'label' => 'URL directorio empresas',
				'name' => 'url_directorio_empresas',
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
				'key' => 'field_5a8bed55a444e',
				'label' => 'Provincia por defecto',
				'name' => 'default_provincia',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '/alava/,/...',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a8c346bd39b3',
				'label' => 'Texto footer',
				'name' => 'texto_footer',
				'type' => 'textarea',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'br',
			),
            array (
                'key' => 'field_5a8d43168a428',
                'label' => 'akamai blog path',
                'name' => 'akamai_blog_path',
                'type' => 'text',
                'required' => 1,
                'default_value' => '/blog/',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
                ),
				 array (
                'key' => 'field_5a8d44468a428',
                'label' => 'URL LocalDigitalKit',
                'name' => 'url_localdigitalkit',
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
				'key' => 'field_5a8bed55a7458',
				'label' => 'Facebook',
				'name' => 'facebook',
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
				'key' => 'field_5a8bed55a7491',
				'label' => 'Twitter',
				'name' => 'twitter',
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
				'key' => 'field_5a8bed55a7816',
				'label' => 'Google+',
				'name' => 'google',
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
				'key' => 'field_5a8bed55a7984',
				'label' => 'LinkedIn',
				'name' => 'linkedin',
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
				'key' => 'field_5a856ec095556',
				'label' => 'Analítica: MEDIO ID COMSCORE',
				'name' => 'an_comscore_medio',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'MEDIO ID COMSCORE analítica para el medio',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a856ec095557',
				'label' => 'Analítica: ADOBEDTM HOST MEDIO',
				'name' => 'an_adobe_host_medio',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'ADOBEDTM HOST MEDIO analítica para el medio',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a856ec095558',
				'label' => 'Analítica: GTM-ID',
				'name' => 'an_gtm_ID',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'GTM-ID analítica para el medio',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5bb3612095558',
				'label' => 'Meta title HOME',
				'name' => 'home_meta_title',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'Meta title HOME',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5a85612095558',
				'label' => 'Meta description HOME',
				'name' => 'home_meta_desc',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'Meta description HOME',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			)
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'medio',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}


if(!function_exists("get_field"))
{
    function get_field(){
        return false;
    }
}




/******************************************************/
/******************************************************/
/************************HOOKS*************************/
/******************************************************/
/******************************************************/


add_filter('wpseo_canonical',function($canonical){
	return lkd_get_post_canonical();
});

add_filter('wpseo_robots',function($robotsstr ){
	return ldk_meta_robots();
});

/*

add_filter('wpseo_opengraph_url',function($canonical){
	global $post;
	return get_permalink($post->ID);
});

ldk_add_filters(array('wpseo_opengraph_title','wpseo_twitter_title'),function($canonical){
	return ldk_meta_title();
});

add_filter('wpseo_opengraph_desc',function($canonical){
	return ldk_meta_description();
});


add_action('wpseo_add_opengraph_images',function($elem){
	 $elem->add_image( ldk_get_thumbnail() );
});
*/

ldk_add_filters(array('wpseo_opengraph_image','wpseo_twitter_image'),function($image){
	return ldk_get_thumbnail();
});




add_filter('wpseo_title',function($title){

	if(!is_home()){
		return $title;
	}

	$custom_title=ldk_get_current_medio_meta('home_meta_title',true);
	if($custom_title)
		return $custom_title;
});

add_filter('wpseo_metadesc',function($title){


		if(is_category()){
			$cat=get_queried_object();
			return "Listado de la categor�a '".$cat->slug."'";;
		}

		if(is_tag()){
			$tag = get_queried_object();
			return "Listado del tag '".$tag->name."'";
		}

		if(is_search()){
          return "Resultados de b�squeda '".get_search_query()."'";
        }


		if(!is_home()){
			return $title;
		}

		$custom_title=ldk_get_current_medio_meta('home_meta_desc',true);
		if($custom_title)
			return $custom_title;
});

add_action('widgets_init', function() {

	register_sidebar( array(
        'name' => 'Col-C',
        'id' => 'ldk_col_c',

    ) );

	register_widget('ldk_widget_search');
	register_widget('ldk_widget_tags');
	register_widget('ldk_widget_empresas');
	register_widget('ldk_widget_views');
	register_widget('ldk_widget_newsletter');
	register_widget('ldk_widget_contacto');
	register_widget('ldk_widget_social');
	register_widget('ldk_widget_categories');
});

add_filter('robots_txt', 'ldk_custom_robots_txt');

function ldk_custom_robots_txt($text) {
$additions = "Disallow: /wp-admin/
Disallow: /?s=*
Sitemap:".ldk_get_current_medio_meta('url',true)."sitemap.xml?t=".LDK_CURRENT_MEDIO_KEY;
return $additions;
}

add_filter('wp_generate_tag_cloud', 'ldk_tag_cloud',10,1);

function ldk_tag_cloud($string){
    return preg_replace("/style='font-size:.+pt;'/", '', $string);
}


function ldk_admin_scripts() {

    $urls=array();
    if(class_exists('Wpw_Auto_Poster_Li_Posting')){
        global $wpw_auto_poster_options, $wpw_auto_poster_fb_posting;

        $liposting=new Wpw_Auto_Poster_Li_Posting();
        $fbposting = $wpw_auto_poster_fb_posting;

        $liposting->wpw_auto_poster_li_user_logged_in();



        //foreach($wpw_auto_poster_options['li_keys'] as $li_key=>$li_value){

        //    if(!isset( $_SESSION[$li_key.'_wpw_auto_poster_li_user_id'] ) ||  $_SESSION[$li_key.'_wpw_auto_poster_li_user_id'] ==null)
        //    {
        //        array_push($urls,$liposting->wpw_auto_poster_get_li_login_url($li_key,$li_value['linkedin_medioid']));
        //    }
        //}

        $facebook_keys = isset( $wpw_auto_poster_options['facebook_keys'] ) ? $wpw_auto_poster_options['facebook_keys'] : array();


        foreach($facebook_keys as $facebook_key=>$facebook_value){
            array_push($urls,$fbposting->wpw_auto_poster_get_fb_login_url( $facebook_value['app_id']));
        }

        wp_enqueue_script( 'lijs', get_template_directory_uri() . '/assets/admin/li.js' );

        wp_localize_script( 'lijs', 'ajax_object', array( 'li_urls' => $urls ) );
    }
}

add_action( 'admin_enqueue_scripts', 'ldk_admin_scripts' );





function ldk_set_current_medio() {

	if(!is_admin()){

		$current_medio=ldk_get_medio();

        if(!$current_medio && ldk_is_custom_preview()){
            $current_medio=ldk_find_medio_by_id($_GET['ldkmedioprw']);
        }

		if($current_medio!==false){

            	define('LDK_CURRENT_MEDIO_ID',$current_medio->ID);
                define('LDK_CURRENT_MEDIO_NAME',$current_medio->post_title);
                define('LDK_CURRENT_MEDIO_KEY',get_post_meta($current_medio->ID,'id_medio',true));
        }

		//else
			//ldk_redirect_404();

	}

}
add_action( 'init', 'ldk_set_current_medio' );

function ldk_myme_types($mime_types){
    $mime_types['svg'] = 'image/svg+xml';
    return $mime_types;
}
add_filter('upload_mimes', 'ldk_myme_types');


/*Aplica el filtro de medio asociado a un post*/
function ldk_filter_post_medio($query) {

    if ( !is_admin() && (is_single() || is_search() || is_category()|| is_tag()) && is_main_query() && !ldk_is_custom_preview()  ) {

		$meta_query = array(
                 array(
                    'key'=>'ldk_medio',
                    'value'=>LDK_CURRENT_MEDIO_ID,
                    'compare' => 'LIKE'
                 )
		);

		$query->set('meta_query',$meta_query);
    }

}
add_action('pre_get_posts','ldk_filter_post_medio');

/*Filtro para preview post*/
function append_preview_query_vars( $link, $post ) {

    return $link."&ldkmedioprw=diariovasco";

}
add_filter( 'preview_post_link', 'append_preview_query_vars',1,2 );


//Columna personalizada
add_filter( 'manage_medio_posts_columns', 'ldk_custom_columns' );
function ldk_custom_columns($columns) {

    $columns['medio_id'] = "Id Medio";
    return $columns;
}

// A�ade el campo a la columna
add_action( 'manage_medio_posts_custom_column' , 'ldk_manager_custom_column', 10, 2 );
function ldk_manager_custom_column( $column, $post_id ) {
    switch ( $column ) {

        case 'medio_id' :
            $medioid = ldk_get_medio_meta('id_medio',true,$post_id);
            if ( is_string( $medioid ) )
                echo $medioid;

            break;



    }
}

//Si es 404 lleva a p�gina de 404 configurada
function ldk_404_hook(){
    if( is_404() ){
        ldk_redirect_404();
    }
}
add_action( 'template_redirect', 'ldk_404_hook' );


//Custom CSS
function ldk_handle_custom_css(){
    $css=ldk_get_css_file();
    $css.=ldk_get_medio_meta("custom_css",true,LDK_CURRENT_MEDIO_ID);
    echo $css;
}
add_action('ldk_amp_custom_css','ldk_handle_custom_css');



function ldk_get_css_file(){

    $arrContextOptions=array(
      "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

  $css=  file_get_contents(get_stylesheet_directory_uri().'/style.css',false,stream_context_create($arrContextOptions));

  //Minimize
  $css=preg_replace(
    array(
        '/ {2,}/',
        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
    ),
    array(
        ' ',
        ''
    ),
    $css
);

  return $css;
}

/**
 * Reemplazo de URLS internas por publicas
 * @param mixed $url
 * @return mixed
 */
function ldk_replace_domain($url)
{
    if(!is_admin()){

        $current_url=ldk_get_medio_meta("url",true,'LDK_CURRENT_MEDIO_ID');
        return ldk_replace_domain_base($url,$current_url);

    }
    return $url;
}

add_filter('script_uri', 'ldk_replace_domain');
add_filter('stylesheet_uri', 'ldk_replace_domain');
add_filter('bloginfo_url', 'ldk_replace_domain');
add_filter('bloginfo', 'ldk_replace_domain');
add_filter('post_link', 'ldk_replace_domain');
add_filter('category_link', 'ldk_replace_domain');
add_filter('category_feed_link', 'ldk_replace_domain');
add_filter('wp_get_attachment_url', 'ldk_replace_domain');
add_filter('wp_get_attachment_thumb_url', 'ldk_replace_domain');
add_filter('post_comments_feed_link', 'ldk_replace_domain');
add_filter('author_link', 'ldk_replace_domain');
add_filter('author_feed_link', 'ldk_replace_domain');
add_filter('plugins_url', 'ldk_replace_domain');
add_filter('bloginfo_rss', 'ldk_replace_domain');
add_filter('home_url', 'ldk_replace_domain');
add_filter('script_loader_src', 'ldk_replace_domain');
add_filter('style_loader_src', 'ldk_replace_domain');
add_filter('stylesheet_directory_uri', 'ldk_replace_domain');
//add_filter('site_url', 'ldk_replace_domain');
//add_filter('esc_url', 'ldk_replace_domain');


function ldk_replace_content($content)
{

    $current_url=ldk_get_medio_meta("url",true,LDK_CURRENT_MEDIO_ID);
    return ldk_replace_domain_base($content,$current_url);


}
add_filter('the_content', 'ldk_replace_content');




/*Sitemap*/
add_filter('action_vcf_root',function($root_object){



    //Como el action en do_ref de array no podemos hacer asignacion directa

    $posts=ldk_get_latest_posts(LDK_CURRENT_MEDIO_ID,100);
    set_blog_posts($posts,get_current_blog_id());
    $posts=addCustomFieldstoPosts($posts);
    $root_object->posts=$posts;
    return $root_object;
    //for($i=0;$i<=count($posts);$i++){
    //    $vcf_posts[$i]=$posts[$i];
    //}


},10);




/******************************************************/
/******************************************************/
/***********************FUNCIONES**********************/
/******************************************************/
/******************************************************/

function ldk_get_query_page(){

   $page= (int)get_query_var('paged')-(int)1;

   if($page<0)
       $page=0;

   return $page;

}

function ldk_load_empresas_krammer($krammerURL){

    //$filtro = '&hits='.$hits.'&filter=tipo_suscripcion:'.strtoupper($tipo).'&sortby=random';
    //$krammerURL=ldk_get_current_medio_meta("url_buscador",true);
    $krammerURL=str_replace("%%MEDIO%%",LDK_CURRENT_MEDIO_KEY,$krammerURL);
    $json=file_get_contents($krammerURL);
    $data = json_decode($json, TRUE);

    return $data;

}

function ldk_load_empresas_krammer_suscripciones(){

    $entries=array();
    $maxhits = 20;
    $entries=array_merge($entries,ldk_load_empresas_krammer_by_tipo_suscripcion('ELITE', $maxhits));

    if(count($entries)<$maxhits)
         $entries= array_merge($entries,ldk_load_empresas_krammer_by_tipo_suscripcion('PREMIUM', $maxhits-count($entries)));
     if(count($entries)<$maxhits)
         $entries= array_merge($entries,ldk_load_empresas_krammer_by_tipo_suscripcion('BASE', $maxhits-count($entries)));
      if(count($entries)<$maxhits)
         $entries= array_merge($entries,ldk_load_empresas_krammer_by_tipo_suscripcion('MOTORA', $maxhits-count($entries)));

      return  $entries;

}


function ldk_load_empresas_krammer_by_tipo_suscripcion($tipo, $hits){

            $filtro = '&hits='.$hits.'&filter=tipo_suscripcion:'.strtoupper($tipo).'&sortby=random';
            $krammerURL=ldk_get_current_medio_meta("url_buscador",true);
            $krammerURL=str_replace("%%MEDIO%%",LDK_CURRENT_MEDIO_KEY,$krammerURL).$filtro;
            $json=file_get_contents($krammerURL);
            $krammer = json_decode($json, TRUE);

            return $krammer["entries"];

}

/*Menu XML*/

function ldk_load_menu_xml(){

    $xml = simplexml_load_file(get_template_directory()."/assets/xml/".LDK_CURRENT_MEDIO_KEY.".xml");
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
    return $array;
}

function ldk_load_menu_xml_ldk(){

    $xml = simplexml_load_file(get_template_directory()."/assets/xml/ldk_generic.xml");
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
    return $array;
}

/*ANALYTICS INICIAL:MAL
function ldk_load_analytics_template(){

    $footer_path=get_template_directory()."/assets/analytics/analytics.html";
    $str_footer = file_get_contents($footer_path);
    $str_footer=str_replace("{GTM-MEDIO}",ldk_get_current_medio_meta('an_gtm_medio'),$str_footer);
    $str_footer=str_replace("{RS-MEDIO}",ldk_get_current_medio_meta('an_rs_medio'),$str_footer);
	$str_footer=str_replace("{NS-SITE}",ldk_get_current_medio_meta('an_ns_site'),$str_footer);
	$str_footer=str_replace("{PROPERTY-ID}",ldk_get_current_medio_meta('an_property_id'),$str_footer);
	$str_footer=str_replace("{PAGE-URL}",ldk_get_current_url(),$str_footer);
    return $str_footer;
}*/

function ldk_load_analytics_gtm(){

    set_query_var( 'GTM', ldk_get_current_medio_meta('an_gtm_ID'));
    get_template_part('partials/amp','gtm');

}

function ldk_load_analytics_adobe(){
	global $post;

    set_query_var( 'ADOBE_MEDIO', ldk_get_current_medio_meta('an_adobe_host_medio'));
	set_query_var( 'COMSCORE_MEDIO', ldk_get_current_medio_meta('an_comscore_medio'));
	set_query_var( 'vtm_subseccion1', "");
	set_query_var( 'vtm_subseccion2', "");
	set_query_var( 'vtm_subseccion3', "");
	set_query_var( 'vtm_subseccion4', "");

	if(is_single()){
		set_query_var( 'vtm_titular', $post->post_name);
		set_query_var( 'datem', get_the_modified_date('YmdHis',$post));
		set_query_var( 'datec',  get_the_date('YmdHis',$post));
		set_query_var( 'vtm_seccion', 'blog');
		set_query_var( 'vtm_subseccion1', ldk_get_first_category_object($post)->slug);
	}
	else
	{

		if(is_category()){
			$cat=get_queried_object();

			set_query_var( 'vtm_titular', $cat->slug);
			set_query_var( 'vtm_seccion', 'blog');
			set_query_var( 'vtm_subseccion1', $cat->slug);
		}

		if(is_tag()){
			$tag = get_queried_object();
			set_query_var( 'vtm_titular', $tag->slug);
			set_query_var( 'vtm_seccion', 'blog');
			set_query_var( 'vtm_subseccion1', 'tag');

		}
		if(is_home()){
			set_query_var( 'vtm_titular',"blogs-local-digital-kit-".LDK_CURRENT_MEDIO_KEY);
			set_query_var( 'vtm_seccion',"blog");
			set_query_var( 'name', 'blog.blogs-local-digital-kit-'.LDK_CURRENT_MEDIO_KEY);
		}
	}



	if(is_single()){
		set_query_var('vtm_tipoContenido', "detalle");
	}elseif(is_home()){
		set_query_var('vtm_tipoContenido', "portadilla");
	}else{
		set_query_var('vtm_tipoContenido', "listado");

	}


    get_template_part('partials/amp','adobedtm');

}

function ldk_load_analytics_comscore(){
	global $post;

   set_query_var( 'COMSCORE_MEDIO', ldk_get_current_medio_meta('an_comscore_medio'));

	if(is_single()){
		set_query_var( 'voc_tn', $post->post_name);
		set_query_var( 'name', ldk_get_first_category_object($post)->slug.".".$post->post_name);
		set_query_var( 'voc_tc','detalle');
		set_query_var( 'voc_s1',ldk_get_first_category_object($post)->slug);
		set_query_var( 'voc_s2','');
		set_query_var( 'voc_se',"blog");
	}
	else
	{
		if(is_category()){
			$cat=get_queried_object();

			set_query_var( 'voc_tn', $cat->slug);
			set_query_var( 'name', 'blog.'.$cat->slug);
			set_query_var( 'voc_se',"blog");
			set_query_var( 'voc_s1',$cat->slug);
			set_query_var( 'voc_s2','');
			set_query_var( 'voc_tc','listado');
		}

		if(is_tag()){
			$tag = get_queried_object();
			set_query_var( 'voc_tn', $tag->slug);
			set_query_var( 'voc_se',"blog");
			set_query_var( 'name', 'blog.tag.'.$tag->slug);
			set_query_var( 'voc_s1',$tag->slug);
			set_query_var( 'voc_s2','');
			set_query_var( 'voc_tc','listado');
		}
		if(is_home()){
			set_query_var( 'voc_tn',"blogs-local-digital-kit-".LDK_CURRENT_MEDIO_KEY);
			set_query_var( 'voc_tc',"Portada");
			set_query_var( 'voc_se',"");
			set_query_var( 'voc_s1','');
			set_query_var( 'voc_s2','');
			set_query_var( 'name', 'blog.blogs-local-digital-kit-'.LDK_CURRENT_MEDIO_KEY);
		}
	}





    get_template_part('partials/amp','comscore');

}

/*Footer*/
function ldk_load_footer_template(){
    if (strpos(strtolower(LDK_CURRENT_MEDIO_NAME), 'abc') !== false && strpos(strtolower(LDK_CURRENT_MEDIO_NAME), 'sevilla') !== false) {
            $footer_path = get_template_directory() . "/assets/footers/abcsevilla.html";
            $footer_path = apply_filters('ldk_footer_template_path', $footer_path);
            $str_footer = file_get_contents($footer_path);
        } else {
            $footer_path = get_template_directory() . "/assets/footers/generic.html";
            $footer_path = apply_filters('ldk_footer_template_path', $footer_path);
            $str_footer = file_get_contents($footer_path);
            $datos = getimagesize(ldk_get_current_medio_meta('logo'));                
            if (empty($datos)) {
                $url_img_https = ldk_get_current_medio_meta('logo');
	            $url_img_http = str_replace('https','http',$url_img_https);
	            $svg = simplexml_load_file($url_img_http);
	            
	            $svg_attr = $svg->path;

	         	$svg_attr = $svg[0][viewBox];
	         	$view_box = explode(' ', $svg_attr);
	         	$ratio = ($view_box[3] / 15);
                $anchura = round($view_box[2] / $ratio);   
            } else {
                $ratio = ($datos[1] / 15);
                $anchura = round($datos[0] / $ratio);    
            }
            $str_footer = str_replace("%%LOGOMEDIO%%", ldk_get_current_medio_meta('logo'), $str_footer);
            $str_footer = str_replace("%%ANCHO%%", $anchura, $str_footer);
            $str_footer = str_replace("%%FOOTER_TEXT%%", ldk_get_current_medio_meta('texto_footer'), $str_footer);
            $str_footer = str_replace("%%ORG_DOMAIN%%", ldk_get_footer_domain_links(), $str_footer);
            $str_footer = str_replace("%%ORG_DOMAIN_MOBILE%%", ldk_get_footer_domain_mobile_links(), $str_footer);
        }
        return $str_footer;
}


function ldk_get_footer_domain_links(){
	$current_url= "https://" . $_SERVER['HTTP_HOST'];
	$re = '/http(s)?:\/\/([a-z0-9\-]+)/';
    $url=preg_replace($re,'http://www',$current_url);
	return $url;
}

function ldk_get_footer_domain_mobile_links(){
	$current_url= "https://" . $_SERVER['HTTP_HOST'];
	$re = '/http(s)?:\/\/([a-z0-9\-]+)/';
    $url=preg_replace($re,'http://m',$current_url);
	return $url;
}


/*Breadcum*/

function ldk_the_breadcrumb() {
	if (!is_home()) {
        echo "<span><a href='/'>Empresas</a> > <a href='/blog/'>LDK Blogs</a> > </span>";

		if (is_category() || is_single()) {


			if (is_category()) {
				echo "<span class='orange'>";
				single_cat_title();
				echo "</span>";
			}

			if (is_single()) {
				echo "<span>";
				the_category('title_li=');
				echo "  >   </span>";
				echo "<span class='orange'>  ".substr(get_the_title(),0,80)."...</span>";
			}

		} elseif (is_page()) {
			echo "<span class='orange'>  ".substr(get_the_title(),0,80)."...</span>";
		}
        elseif(is_tag()){

            echo "<span class='orange'> ";
			 single_tag_title();
			echo " </span>";
        }
        elseif(is_search()){
            echo "<span>Búsqueda > </span>";
            echo "<span class='orange'>". get_search_query()."</span>";
        }

	}
    else{
       echo "<span><a href='/'>Empresas</a> >  </span><span class='orange'>LDK Blogs</span>";
    }
}

/*
 * Devuelve URL actual
 */

function ldk_get_current_url(){

    $current_url= "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	return ldk_replace_domain_medio($current_url,LDK_CURRENT_MEDIO_ID);

}

/**
 * Reemplazo de URLS internas por publicas
 * @param mixed $url
 * @param mixed $postID
 * @return mixed
 */
function ldk_replace_domain_medio($url,$postID)
{
    $current_url=ldk_get_medio_meta("url",true,$postID);
    return ldk_replace_domain_base($url,$current_url);
}


/**
 * Reemplaza urls internas por publicas
 * @param mixed $url
 * @param mixed $current_url
 * @return mixed
 */
function ldk_replace_domain_base($url,$current_url){

    //El path del blog
    $blog_details = get_blog_details(get_current_blog_id());
    $regexpath=str_replace('/',"\/",$blog_details->path);
    $repath = '/'.$regexpath.'/';
    $url_temp=preg_replace($repath,ldk_get_current_medio_meta('akamai_blog_path',true),$url);

    //akamai path
    $regexpath=str_replace('/',"\/",ldk_get_current_medio_meta('akamai_blog_path',true));
    $repath = '/'.$regexpath.'/';
    $url_temp=preg_replace($repath,'/',$url_temp);

    if($url_temp)
        $url=$url_temp;

    //$re = '/http(s)?:\/\/([a-z0-9\-.]+)\.([a-z]){2,3}'.$regexpath.'/';

    $re = '/http(s)?:\/\/([a-z0-9\-.]+)\.([a-z]){2,3}\//';

    if (substr($current_url, -1) != '/') {
        $current_url=$current_url."/";
    }
    $url_temp=preg_replace($re,$current_url,$url);

    if($url_temp)
        $url=$url_temp;



    return $url;


}




/**
 * Devuelve el stikcy post del medio. Por defecto del medio actual
 * @param mixed $medio_postid
 * @return array|bool|null|WP_Post
 */
function ldk_get_sticky_post($medio_postid){
    $value = get_option( 'ldk_sticky' );
    if($value && $value[$medio_postid])
    {
        return get_post($value[$medio_postid]);
    }
    return false;
}

/**
 * Devuelve los post de la home. Por defecto de medio actual
 * @param mixed $medio_postid
 * @return array
 */
function ldk_get_home_posts($medio_postid=false,$page){

    if(!$medio_postid)
        $medio_postid= LDK_CURRENT_MEDIO_ID;


    $posts=array();
    $sticky_post=false;

    if(!$page){
        $sticky_post=ldk_get_sticky_post($medio_postid);

        //Si hay sticky lo a�adimos
        if($sticky_post)
            array_push($posts,$sticky_post);
    }

    //recuperamos los ultimos salvo el sticky (si hay)
    $filter=array(
        'numberposts'=>get_option( 'posts_per_page' ),
		'post_type'      => 'post',
		'post_status'    => 'publish',
        'meta_query' => array(
                array(
                   'key'=>'ldk_medio',
                    'value'=>$medio_postid,
                    'compare' => 'LIKE'
                )
        )
    );

    if($page){
        $filter['offset']=$page*get_option( 'posts_per_page' );
    }

    if(!$page){
        if($sticky_post)
        {
            $filter['exclude']=$sticky_post->ID;
            $filter['numberposts']=get_option( 'posts_per_page' )-1;
        }
    }

    //mergear y devolver
    return  array_merge($posts,get_posts($filter));


}


/**
 * Devuelve el numero de posts de un medio. Por defecto de medio actual
 * @param mixed $medio_postid
 * @return array
 */
function ldk_get_count_posts($medio_postid=false){

    if(!$medio_postid)
        $medio_postid= LDK_CURRENT_MEDIO_ID;

    //recuperamos los ultimos salvo el sticky (si hay)
    $filter=array(
        'numberposts'=>-1,
        'meta_query' => array(
                array(
                   'key'=>'ldk_medio',
                    'value'=>$medio_postid,
                    'compare' => 'LIKE'
                )
        )
    );

    $query = new WP_Query($filter);
    $total=$query->found_posts;
    return $total;

}

/**
 * Devuelve el numero de posts de un medio. Por defecto de medio actual
 * @param mixed $medio_postid
 * @return int
 */
function ldk_get_home_pages_count($medio_postid=false){

    $total_posts=ldk_get_count_posts($medio_postid);
    if($total_posts)
    {
        $pages=  intval($total_posts/get_option( 'posts_per_page' ));
        if($total_posts%get_option( 'posts_per_page' )>0)
            $pages++;

        return $pages-1;

    }

    return 0;

}


/**
 * Devuelve los ultimos post de la del medio. Por defecto de medio actual (Usado para sitemap)
 * @param mixed $medio_postid
 * @param int $count
 * @return array
 */
function ldk_get_latest_posts($medio_postid=false,$count=10){

     if(!$medio_postid)
        $medio_postid= LDK_CURRENT_MEDIO_ID;

        $filter=array(
            'numberposts'=>$count,
			'post_type'      => 'post',
			'post_status'    => 'publish',
            'meta_query' => array(
                    array(
                       'key'=>'ldk_medio',
                        'value'=>$medio_postid,
                        'compare' => 'LIKE'
                    )
                )
            );


    $posts=  get_posts($filter);



    return $posts;

}

/**
 * Devuelve al canonical configurado para el post actual
 * @return mixed
 */
function lkd_get_post_canonical(){

	global $post;

    if(is_home())
        return ldk_get_current_medio_meta('url',true);


    $canonical="no canonical";

	$medio_canonical=get_post_meta($post->ID,"ldk_canonical",true);

	if($medio_canonical)
		$canonical=ldk_get_medio_meta('url',true,$medio_canonical);
	else
		$canonical=ldk_get_current_medio_meta('url',true);


    $re = '/http(s)?:\/\/([a-z0-9\-.]+)\.([a-z]){2,3}\/blog\//';

    //$canonical=preg_replace($re,$canonical,get_permalink($post->ID));

	//$canonical="https://www.localdigitalkit.com/blog/".get_permalink($post->ID);

	$canonical=preg_replace($re,"https://www.localdigitalkit.com/blog/",get_permalink($post->ID));
	return $canonical;
}


/**
 * Devuelve al canonical configurado para el post Id
 * @return mixed
 */
function lkd_get_post_canonical_by_postid($post_id){



    $canonical=false;

	$medio_canonical=get_post_meta($post_id,"ldk_canonical",true);

	if($medio_canonical){
		$canonical=ldk_get_medio_meta('url',true,$medio_canonical);



        $re = '/\/\/([a-z0-9\-.]+)\.([a-z]){2,3}/';

        $canonical=preg_replace($re,$canonical,get_permalink($post_id));

    }
	return $canonical;
}
/**
 * Devuelve el medio actual
 * @return mixed
 */
function ldk_get_medio() {

	$domain=$_SERVER['SERVER_NAME'];
	$re = '/([a-z0-9].+)\.([a-z0-9].+)\.([a-z0-9].+)/';
	preg_match_all($re, $domain, $matches);
	if(is_array($matches) && count($matches)>=2)
	{
		$medio_id=$matches[2];
		if (strpos($domain, 'sevilla.abc') !== false) {
			$medio_id = 'abcsevilla';
		}
		return ldk_find_medio_by_id($medio_id);

	}

	return false;
}

function ldk_yoast_active(){

    if(function_exists('wpseo_activate') && wpseo_activate()){
        return true;
    }
    return false;
}

/**
 * Devuelve metas del medio actual, por meta_key
 * @param mixed $metakey
 * @param mixed $single
 * @return mixed
 */
function ldk_get_current_medio_meta($metakey,$single=true){

	$meta_value= get_field($metakey,'LDK_CURRENT_MEDIO_ID');
    if(strpos($metakey, 'image') !== false || strpos($metakey, 'logo') !== false){
        if(substr( $meta_value, 0, 4 ) !== "http"){
            $meta_value=ldk_get_current_medio_meta('url',true).$meta_value;
        }
    }
    return $meta_value;
}

/**
 * Devuelve metas de un medio concreto
 * @param mixed $metakey
 * @param mixed $single
 * @param mixed $medioid
 * @return mixed
 */
function ldk_get_medio_meta($metakey,$single=true,$medioid){

	$meta_value= get_field($metakey,$medioid);
    if(strpos($metakey, 'image') !== false || strpos($metakey, 'logo') !== false){
        if(substr( $meta_value, 0, 4 ) !== "http"){
            $meta_value=ldk_get_current_medio_meta('url',true).$meta_value;
        }
    }
    return $meta_value;
}

/**
 * Devuelve el medio por id de medio (correo,diariovasco,etc...)
 * @param mixed $medio_id
 * @return mixed
 */
function ldk_find_medio_by_id($medio_id){

	$args = array(
       'post_type'      => 'medio',
       'meta_key'       => 'id_medio',
       'meta_value'     => $medio_id,
       'posts_per_page' => 1,
	);

	$query = new WP_Query($args);
	$posts = $query->posts;
	if($posts && count($posts)>0){
		return $posts[0];
	}

	return false;

}

/**
 * Devuelve el primer medio que exista
 * @return mixed
 */
function ldk_get_first_medio(){

	$args = array(
       'post_type'      => 'medio',
       'posts_per_page' => 1,
	);

	$query = new WP_Query($args);
	$posts = $query->posts;

	if($posts && count($posts)>0){
		return $posts[0];
	}
	return false;

}

/**
 * Devuelve todos los medios
 * @return array
 */
function ldk_get_all_medios(){

	$args = array(
       'post_type'      => 'medio',
       'posts_per_page' => -1,
	);

	$query = new WP_Query($args);
	$posts = $query->posts;

	return $posts;

}

/**
 * Redirect a 404 del medio actual
 */
function ldk_redirect_404(){

	status_header(404);
    header('Location: '.ldk_get_medio_meta("page_404",true,'LDK_CURRENT_MEDIO_ID'));
    exit();



}


/**
 * Comprueba si la url actual es preview
 * @return bool
 */
function ldk_is_custom_preview(){


    return (isset($_GET['preview_nonce']) || isset($_GET['preview']) || isset($_GET['ldkmedioprw']));
}

/*Medios por visitas*/
function ldk_get_blogs_visits(){
    $sorted=array();

    $allblogs= get_posts(array('post_type'=>'medio','numberposts'=>-1));
    if($allblogs && count($allblogs)){
        foreach($allblogs as $medio){
            $count=get_option('medio_visit_'.$medio->ID);
            if($count)
                array_push($sorted,array('medio'=>$medio,'count'=>$count));
        }
    }

    if(count($sorted)){
        usort($sorted, function($a, $b)
        {
            if ($a['count'] == $b['count']) {
                return 0;
            }
            return ($a['count'] > $b['count']) ? -1 : 1;
        });
    }

    return $sorted;

}

/*Post por visitas*/
function ldk_get_posts_visits(){

	$args = array(
            'post_status' => 'publish',
            'post_type' => 'post',
            'meta_key' => 'post_visit',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
			'posts_per_page'=>5,
			'meta_query'=>array(
				array(
					'key' => 'post_visit'
				),
                 array(
                    'key'=>'ldk_medio',
                    'value'=>LDK_CURRENT_MEDIO_ID,
                    'compare' => 'LIKE'
                 )
		)
        );



	$query = new WP_Query($args);

	$posts=$query->posts;
	return $posts;

}


/**
 * Devuelve thumbail del post, si no tiene devuelve el configurado en las opciones del medio
 * @return mixed
 */
function ldk_get_thumbnail($size='thumbnail'){

    global $post;

    if(has_post_thumbnail($post->ID))
        return ldk_get_the_post_thumbnail_url($post->ID,$size);
    else
        return ldk_get_current_medio_meta('default_image_share',true);

}


/**
 * Devuelve thumbail del post por parametro, si no tiene devuelve el configurado en las opciones del medio
 * @return mixed
 */
function ldk_get_thumbnail_post($post,$size='thumbnail'){


    if(has_post_thumbnail($post->ID))
        return ldk_get_the_post_thumbnail_url($post->ID,$size);
    else
        return ldk_get_current_medio_meta('default_image_share',true);

}

function ldk_get_the_post_thumbnail_url($postID,$size){

    $thumb_id = get_post_thumbnail_id($postID);

    if(!$thumb_id)
        return false;

    $thumb_url = wp_get_attachment_image_src($thumb_id,$size, true);

    if(!$thumb_url || count($thumb_url)==0)
        return false;

    $url= $thumb_url[0];

    if(substr( $url, 0, 4 ) !== "http"){
        $url=ldk_get_current_medio_meta('url',true).$url;
    }


    return $url;

}


/**
 * Devuelve un meta_key de los custom_meta_box del post actual
 * @param mixed $name
 * @return mixed
 */
function ldk_meta($name){

    global $post;

    $name='ldk_meta_'.$name;

    $meta_values=get_post_meta($post->ID,'ldk_metadata',true);

    if($meta_values && array_key_exists($name,$meta_values))
        return $meta_values[$name];

    return false;

}

/**
 * Devuelve el meta_title configurado para el post actual. Por defecto el post_title
 * @return mixed
 */
function ldk_meta_title(){

    $val=ldk_meta('title');
    if($val)
        return $val;

    return get_the_title();
}

/**
 * Devuelve el meta description configurado para el post actual. Por defecto el excerpt
 * @return mixed
 */
function ldk_meta_description(){

    $val=ldk_meta('description');
    if($val)
        return $val;

    return get_the_excerpt();
}

/**
 * Devuelve el meta robots configurado para el post actual. Por defecto 'index,follow'
 * @return mixed
 */
function ldk_meta_robots(){

	if(is_search()){
		return "noindex,nofollow";
	}

    $val=ldk_meta('robots');
    if($val)
        return $val;

    return 'index,follow';
}

/**
 * Devuelve el meta author configurado para el post actual. Por defecto el author del post
 * @return mixed
 */
function ldk_meta_author(){

    global $post;

    $val=ldk_meta('author');
    if($val)
        return $val;

   return get_the_author_meta('display_name',get_the_author_meta('ID'));

}


function ldk_add_filters($tags, $function_to_add, $priority = 10, $accepted_args = 1)
{
  //If the filter names are not an array, create an array containing one item
  if(!is_array($tags))
    $tags = array($tags);

  //For each filter name
  foreach($tags as $index => $tag)
    add_filter($tag, $function_to_add, (int)(is_array($priority) ? $priority[$index] : $priority), (int)(is_array($accepted_args) ? $accepted_args[$index] : $accepted_args));

  return true;
}

function ldk_get_first_category($post){

    $postcat = get_the_category( $post->ID );

    // try print_r($postcat) ;

    if ( ! empty( $postcat ) ) {
        return "<a href='".get_category_link($postcat[0])."'>".esc_html( $postcat[0]->name )."</a>";
    }
    return "";
}

function ldk_get_first_category_object($post){

    $postcat = get_the_category( $post->ID );

    // try print_r($postcat) ;

    if ( ! empty( $postcat ) ) {
        return $postcat[0];
    }
    return false;
}


function ldk_get_hours_sice($post_date){
    // Create two new DateTime-objects...
    $date1 = new DateTime($post_date);
    $date2 = new DateTime();

    // The diff-methods returns a new DateInterval-object...
    $diff = $date2->diff($date1);

    // Call the format method on the DateInterval-object
    if($diff->d ==1){
        return "Ayer";
    }

    if($diff->d >2){
        return $diff->format('Hace %d días');
    }
    else{
        if($diff->h >0)
            return $diff->format('Hace %h horas');
        else
            return $diff->format('Hace %i minutos');
    }
}


/*************************************************************************/
/*************************************************************************/
/*****************************AUTOPOSTER**********************************/
/*************************************************************************/
/*************************************************************************/

/* AUTOPOST Comun */
add_action('ldk_medio_autopost_select_options',function($selected_item){

	foreach(ldk_get_all_medios() as $medio){
		$selected=($medio->ID==$selected_item)?"selected":"";
		echo "<option ".$selected." value=".$medio->ID.">".$medio->post_title."</option>";
	}

},10,1);

/*Replace link with post canonical*/

add_filter('ldk_post_canonical',function($current_link,$post){


    $medio_canonical=get_post_meta($post->ID,"ldk_canonical",true);

	if($medio_canonical){

        $new_link=ldk_replace_domain_medio($current_link,$medio_canonical);
        return $new_link;
    }

    return $current_link;

},10,2);




/* AUTOPOST Facebook */
add_filter('ldk_medio_autopost_fb_send',function($post_to,$send,$post){

    $medio_app=ldk_check_get_fb_app_medio($post_to,$post);

    if($medio_app){

          $send['message'] = ldk_replace_domain_medio(substr( $send['message'], 0, 999),$medio_app);
          $send['link'] = ldk_replace_domain_medio($send['link'],$medio_app);

          return $send;
    }

    return false;

},10,3);
/**
 * Comprueba si el post est� asociado al medio que  est� asociado a la app de FB y en ese caso devuelve el medio id asociado, Si no est� devuelve false.
 * @param mixed $appid
 * @param mixed $post
 * @return mixed
 */
function ldk_check_get_fb_app_medio($post_to,$post){

    global $wpw_auto_poster_options;

    if($wpw_auto_poster_options){

        $post_medios = get_post_meta( $post->ID, 'ldk_medio', true );

        if(!is_array($post_medios))
            return false;

        $fb_keys 	= !empty($wpw_auto_poster_options['fb_medio_account']) ? $wpw_auto_poster_options['fb_medio_account'] : array();

        if( !empty( $fb_keys ) ) {

            foreach ( $fb_keys as $post_to_id => $post_to_medio ){

                if( $post_to_id ==$post_to && in_array( $post_to_medio,$post_medios) ) {
                    return $post_to_medio;

                }

            }
        }

        return false;
    }

    return false;


}



/* AUTOPOST Twitter */


add_filter('ldk_medio_autopost_tw_send',function($tw_consumer_key,$status,$post){

    $medio_app=ldk_check_get_tw_app_medio($tw_consumer_key,$post);


    if($medio_app){

          $status = ldk_replace_domain_medio(substr($status, 0, 999),$medio_app);

          return $status;
    }

    return false;

},10,3);
/**
 * Comprueba si el post est� asociado al medio que  est� asociado a la app de FB y en ese caso devuelve el medio id asociado, Si no est� devuelve false.
 * @param mixed $appid
 * @param mixed $post
 * @return mixed
 */
function ldk_check_get_tw_app_medio($tw_consumer_key,$post){

    global $wpw_auto_poster_options;

    if($wpw_auto_poster_options){

        $post_medios = get_post_meta( $post->ID, 'ldk_medio', true );

        if(!is_array($post_medios))
            return false;

        $tw_keys 	= !empty($wpw_auto_poster_options['twitter_keys']) ? $wpw_auto_poster_options['twitter_keys'] : array();

        if( !empty( $tw_keys ) ) {

            foreach ( $tw_keys as $fb_key_id => $fb_key_data ){

                if( $fb_key_data['consumer_key'] ==$tw_consumer_key && in_array( $fb_key_data['medioid'],$post_medios) ) {
                    return $fb_key_data['medioid'];

                }

            }
        }

        return false;
    }

    return false;


}

/* AUTOPOST LinkedIN */
add_filter('ldk_medio_autopost_li_send',function($li_post_profile,$status,$post){

    $medio_app=ldk_check_get_li_app_medio($li_post_profile,$post);


    if($medio_app){

          $status['submitted-url'] = ldk_replace_domain_medio($status['submitted-url'] ,$medio_app);
          $status['submitted-image-url'] = ldk_replace_domain_medio($status['submitted-image-url'] ,$medio_app);
          return $status;
    }

    return false;

},10,3);
function ldk_check_get_li_app_medio($li_post_profile,$post){

    global $wpw_auto_poster_options;

    if($wpw_auto_poster_options){

        $post_medios = get_post_meta( $post->ID, 'ldk_medio', true );

        if(!is_array($post_medios))
            return false;

        $tw_keys 	= !empty($wpw_auto_poster_options['li_medio_account']) ? $wpw_auto_poster_options['li_medio_account'] : array();

        if( !empty( $tw_keys ) ) {

            foreach ( $tw_keys as $li_profile_id => $li_profile_medioid ){

                if( $li_profile_id ==$li_post_profile && in_array($li_profile_medioid,$post_medios) ) {
                    return $li_profile_medioid;

                }

            }
        }

        return false;
    }

    return false;


}

function ldk_get_related($page,$postID,$ipp){

	$posts=[];
	$args = array(
            'post_status' => 'publish',
            'post_type' => 'post',
			'posts_per_page'=>$ipp,
			'offset'=>($page*$ipp),
			'post__not_in' => array($postID),
			'category__in' => wp_get_post_categories($postID),
			'meta_query'=>array(
                 array(
                    'key'=>'ldk_medio',
                    'value'=>LDK_CURRENT_MEDIO_ID,
                    'compare' => 'LIKE'
                 )
		)
    );

	$query = new WP_Query($args);
	$posts=$query->posts;

	if(count($posts)<$ipp){

		$excludedIds=array();
		array_push($excludedIds,$postID);

		foreach($posts as $p){
			array_push($excludedIds,$p->ID);
		}

		$args = array(
				'post_status' => 'publish',
				'post_type' => 'post',
				'posts_per_page'=>($ipp-count($posts)),
				'offset'=>($page*$ipp),
				'post__not_in' => $excludedIds,
				'meta_query'=>array(
					 array(
						'key'=>'ldk_medio',
						'value'=>LDK_CURRENT_MEDIO_ID,
						'compare' => 'LIKE'
					 )
			)
		);

		$query = new WP_Query($args);
		$posts=array_merge($posts,$query->posts);

	}

	return $posts;
}

function ldk_category_count($categoryID){

	$posts=[];
	$args = array(
            'post_status' => 'publish',
            'post_type' => 'post',
			'posts_per_page'=>1,
			'category__in' => array($categoryID),
			'meta_query'=>array(
                 array(
                    'key'=>'ldk_medio',
                    'value'=>LDK_CURRENT_MEDIO_ID,
                    'compare' => 'LIKE'
                 )
		)
    );

	$query = new WP_Query($args);
	return ($query->post_count>0);
}

function ldk_get_utm(){

	$utm_array=array();
	if(is_home()){
		$utm_array['query']='?utm_source=blog-empresas-ldk&utm_medium=blog-home';

	}

	if(is_single()){
		$utm_array['query']='?utm_source=blog-empresas-ldk&utm_medium=blog-post';
	}

	if(is_category()){
		$category = get_category( get_query_var( 'cat' ) );
		$cat_name = $category->slug;
		$utm_array['query']='?utm_source=blog-empresas-ldk&utm_medium=blog-'.$cat_name;

	}

	return $utm_array;
}

// Devuelve los tags de los post asociados al medio
function ldk_custom_tag_cloud() {
    global $wpdb;
    $tags_query = $wpdb->prepare("SELECT name,slug from {$wpdb->prefix}terms INNER JOIN {$wpdb->prefix}term_relationships ON {$wpdb->prefix}terms.term_id = {$wpdb->prefix}term_relationships.term_taxonomy_id INNER JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}term_relationships.object_id = {$wpdb->prefix}postmeta.post_id WHERE {$wpdb->prefix}term_taxonomy.taxonomy = 'post_tag' AND {$wpdb->prefix}postmeta.meta_key = 'ldk_canonical' AND {$wpdb->prefix}postmeta.meta_value = ".LDK_CURRENT_MEDIO_ID." ORDER BY {$wpdb->prefix}term_taxonomy.count LIMIT 10" );
    $custom_tags_list = $wpdb->get_results( $tags_query );
    return $custom_tags_list;
}

?>