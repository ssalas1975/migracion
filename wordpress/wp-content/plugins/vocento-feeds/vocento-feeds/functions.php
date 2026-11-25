<?php

add_action('init', 'vcf_serices_create_rules');

add_action('parse_request', 'vcf_serices_parse_request');



function getFeedById($vcfid)
{
    $options = get_option('vocentovcf_options');
    $config=$options['vcf_feeds_config'];
    if (!$config) {
        $config="[]";
    }
    
    $configClass=json_decode($config, true);
    

    foreach ($configClass as $config) {
        if ($config["id"]==$vcfid) {
            return $config;
        }
    }
    return null;
}

function getFeedByNetworkId($vcfid)
{
    $options = get_site_option('vocentovcf_options');
    $config=$options['vcf_feeds_config'];
    if (!$config) {
        $config="[]";
    }
    
    $configClass=json_decode($config, true);
    

    foreach ($configClass as $config) {
        if ($config["id"]==$vcfid) {
            return $config;
        }
    }
    return null;
}

function vcf_generate_network_root_object($feed)
{
    $root_object= new vcf_root_object();
    $root_object->site=get_details_blog_custom(get_current_blog_id());
    do_action_ref_array('action_vcf_site_options', array( &$root_object->site_options ));
    $root_object->posts=vcf_get_latest_post_network($feed["pt"]);
    do_action_ref_array('action_vcf_posts', array( &$root_object->posts ));
    return $root_object;
}

function vcf_generate_network_root_sample_object($pt)
{
    $root_object= new vcf_root_object();
    $root_object->site=get_details_blog_custom(get_current_blog_id());
    do_action_ref_array('action_vcf_site_options', array( &$root_object->site_options ));
    $root_object->posts=vcf_get_latest_post_network($pt);
    do_action_ref_array('action_vcf_posts', array( &$root_object->posts ));
    return $root_object;
}

function vcf_generate_root_sample_object($pt)
{
    $root_object= new vcf_root_object();
    $root_object->site=get_details_blog_custom(get_current_blog_id());
    do_action_ref_array('action_vcf_site_options', array( &$root_object->site_options ));
    $root_object->posts=vcf_get_latest_post($pt);
    do_action_ref_array('action_vcf_posts', array( &$root_object->posts ));
    return $root_object;
}


function vcf_generate_root_object($feed)
{
    $root_object= new vcf_root_object();
    $root_object->site=get_details_blog_custom(get_current_blog_id());
    do_action_ref_array('action_vcf_site_options', array( &$root_object->site_options ));
    $root_object->posts=vcf_get_latest_post($feed["pt"]);
    do_action_ref_array('action_vcf_posts', array( &$root_object->posts ));
    $root_object=apply_filters('action_vcf_root', $root_object);
    return $root_object;
}




function get_details_blog_custom($blogid)
{
    if (is_multisite()) {
        return get_blog_details($blogid);
    } else {
        return get_bloginfo();
    }
}

function vcf_generate_root_object_xml($root_object)
{
    return XMLSerializer::generateValidXmlFromMixiedObj($root_object);
}

function vcf_get_latest_post($pt)
{
    $args = compose_args_posts($pt);
    $recent_posts = get_posts($args);
    set_blog_posts($recent_posts, get_current_blog_id());
    $recent_posts=addCustomFieldstoPosts($recent_posts);
    return $recent_posts;
}

function vcf_get_latest_post_network($pt)
{
    $recent_posts = array();

    foreach (wp_get_sites() as $site) {
        switch_to_blog($site["blog_id"]);
        $args = compose_args_posts($pt);
        $blogs_posts=get_posts($args);
        set_blog_posts($blogs_posts, $site["blog_id"]);
        $recent_posts=array_merge($recent_posts, $blogs_posts);
    }

    restore_current_blog();
    
    $recent_posts=addCustomFieldstoPosts($recent_posts);

    usort($recent_posts, 'sort_post_date');

    $recent_posts=array_slice($recent_posts, 0, $args['posts_per_page']);

    
    return $recent_posts;
}

function set_blog_posts(&$blogs_posts, $blog_id)
{
    foreach ($blogs_posts as &$post) {
        $post=post_to_array($post);
        $post["blog_id"]=$blog_id;
    }
}




function sort_post_date($a, $b)
{
    return $b["post_date"] - $a["post_date"];
}


function compose_args_posts($pt)
{
    $args = array(
        'offset' => 0,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => $pt,
        'post_status' => 'publish',
        'suppress_filters' => true
    );


    //Elementos

    $size=20;
    if ($_GET["size"] && is_numeric($_GET["size"])) {
        $size=$_GET["size"];
    }
    $args['posts_per_page']=$size;
    


    //Categorias
    
    if ($_GET["category"]) {

        /*$categories=$_GET["category"];
        $categories_ids='';
        foreach(explode(',',$categories) as $category){

            $wp_category=get_category_by_slug($category);
            if($wp_category){
                $categories_ids+=$wp_category->term_id.",";
            }
            else{
                $categories_ids+="0";
            }

        }

        if($categories_ids)
            $args['category_name']=$categories_ids;*/

        $args['category_name']= $_GET["category"];
    }

    
    
    
    //tags
    
    if ($_GET["tag"]) {
        $args['tag']=$_GET["tag"];
    }

    //tax
    if ($_GET["term"]) {
        $args['term']=$_GET["term"];
    }

    //author
    if ($_GET["author"]) {
        $author=get_user_by('slug', $_GET["author"]);
        if ($author) {
            $args['author']=$author->ID;
        } else {
            $args['author']=-1;
        }
    }

    //post types
    if ($_GET["pt"]) {
        $args['post_type']=explode(",", $_GET["pt"]);
    }
   
    return $args;
}


function post_to_array($wppost)
{
    $post = get_object_vars($wppost);

    foreach (array( 'ancestors', 'page_template', 'post_category', 'tags_input' ) as $key) {
        if ($wppost->__isset($key)) {
            $post[ $key ] = $wppost->__get($key);
        }
    }

    return $post;
}

function wpPosts_to_array($wppost_array)
{
    $posts=array();
    foreach ($wppost_array as $wppost) {
        $post = get_object_vars($wppost);

        foreach (array( 'ancestors', 'page_template', 'post_category', 'tags_input' ) as $key) {
            if ($wppost->__isset($key)) {
                $post[ $key ] = $wppost->__get($key);
            }
        }
        array_push($posts, $post);
    }

    return $posts;
}

function images_to_array($wppost_images)
{
    $images=array();
    foreach ($wppost_images as $wppost) {
        $post = get_object_vars($wppost);

        foreach (array( 'ancestors', 'page_template', 'post_category', 'tags_input' ) as $key) {
            if ($wppost->__isset($key)) {
                $post[ $key ] = $wppost->__get($key);
            }
        }
        array_push($images, $post);
    }

    //Tenemos las imagenes en array.Ahora a�adimos los sizes

    $sizes=get_intermediate_image_sizes();
    
    foreach ($images as &$image) {
        $image["sizes"]=array();
        foreach ($sizes as $size) {
            $imgsrc=wp_get_attachment_image_src($image["ID"], $size);
            if ($imgsrc && is_array($imgsrc)) {
                $image["sizes"][$size]=array();
                $image["sizes"][$size]["url"]=$imgsrc[0]; //url
                $image["sizes"][$size]["width"]=$imgsrc[1]; //url
                $image["sizes"][$size]["height"]=$imgsrc[2]; //url
            }
        }
    }






    return $images;
}

function addCustomFieldstoPosts(&$posts)
{
    foreach ($posts as &$post) {
        if (is_multisite()) {
            switch_to_blog($post["blog_id"]);
        }

        //UNIX Dates
        $post["post_date_unix"]=get_post_time('U', false, $post["ID"]);
        $post["post_date_gtm_unix"]=get_post_time('U', true, $post["ID"]);
        $post["post_modified_unix"]=get_post_modified_time('U', false, $post["ID"]);
        $post["post_modified_gtm_unix"]=get_post_modified_time('U', true, $post["ID"]);
        $post["post_permalink"]=get_permalink($post["ID"]);
  

        //nombre autor
        try {
            if (function_exists('get_userdata')) {
                $post["post_author_name"]=get_the_author_meta('user_nicename', $post["post_author"]);
            }
        } catch (Exception $e) {
            echo '';
        }
        
        //Categorias
        $post["post_categories"]=array();
        foreach (wp_get_post_categories($post["ID"]) as $category) {
            array_push($post["post_categories"], get_category($category, ARRAY_A));
        }
        
        //Imagenes
        
        $post["post_images"]=images_to_array(get_attached_media('image', $post["ID"]));

        //Featured image
    
        $post["post_featured_image"]= wp_image_to_array(wp_get_attachment_image_src(get_post_thumbnail_id($post["ID"]), 'full'));
      
        //audios
        $post["post_audio"]=wpPosts_to_array(get_attached_media('audio', $post["ID"]));
        
        //Videos
        $post["post_video"]=wpPosts_to_array(get_attached_media('video', $post["ID"]));

        if (has_filter("vocento_topics")) {
            $post["topics"]=get_topics_post($post["ID"]);
        }
        
        //Imagenes
        $post["tags"]=wp_get_post_tags($post["ID"]);
        
        //aplicando filtro para el contenido de los posts
        $post["post_content"] = apply_filters('the_content', $post["post_content"]);

        //Custom fields
        $custom_fields=get_post_type_fields($post);
        if ($custom_fields) {
            $post["custom_fields"]=$custom_fields;
        }

        if (is_multisite()) {
            restore_current_blog();
        }
    }
   

 
    return $posts;
}


function wp_image_to_array($array)
{
    if ($array!=false) {
        $arrayR['url']=$array[0];
        $arrayR['width']=$array[1];
        $arrayR['height']=$array[2];
        return $arrayR;
    }
}
function process_xsl($xslpath, $xmlstring)
{
    try {
        $xslDoc = new DOMDocument();
        $xslDoc->load($xslpath);

        $xmlDoc = new DOMDocument();
        $xmlDoc->loadXML($xmlstring);

        $proc = new XSLTProcessor();
        $proc->importStylesheet($xslDoc);
        return $proc->transformToXML($xmlDoc);
    } catch (Exception $e) {
        echo '';
        return "";
    }
}

function process_xsl_text($xslstring, $xmlstring)
{
    try {
        $xslDoc = new DOMDocument();
        $xslDoc->loadXML($xslstring);

        $xmlDoc = new DOMDocument();
        $xmlDoc->loadXML($xmlstring);

        $proc = new XSLTProcessor();
        $proc->importStylesheet($xslDoc);
        return $proc->transformToXML($xmlDoc);
    } catch (Exception $e) {
        echo '';
        return "";
    }
}

function vcf_serices_create_global_rules()
{
    $vcf_options= get_site_option('vocentovcf_options');
    $config=$vcf_options['vcf_feeds_config'];

    if ($config) {
        $configArr=json_decode($config, true);
        foreach ($configArr as $cfg) {
            add_rewrite_rule(
                $cfg["slug"].'/?$',
                'index.php?vcfid_net='. $cfg['id'],
                'top'
            );
        }
        flush_rewrite_rules();
    }
}

function vcf_serices_create_rules()
{
    vcf_serices_create_global_rules();

    $vcf_options= get_option('vocentovcf_options');
    $config=$vcf_options['vcf_feeds_config'];

    if ($config) {
        $configArr=json_decode($config, true);
        foreach ($configArr as $cfg) {
            add_rewrite_rule(
                $cfg["slug"].'/?$',
                'index.php?vcfid='. $cfg['id'],
                'top'
            );
        }
        flush_rewrite_rules();
    }
}



function get_sample_xml()
{
    return format_xml_render_html(file_get_contents('https://' . $_SERVER['HTTP_HOST'] .'/vocento-feeds/sample-xml', true));
}
function format_xml_render($xmltext)
{
    $pattern = '/<\/(.[^>]+)>/';
    // $replace = '</${1}>&#13;&#10;';
    $replace = '</${1}>';
    return preg_replace($pattern, $replace, $xmltext);
}

function format_xml_render_html($xmltext)
{
    $pattern = '/<\/(.[^>]+)>/';
    $replace = '</${1}><br />';
    return preg_replace($pattern, $replace, $xmltext);
}

function get_sample_xslt($xslt)
{
    try {
        if (file_exists(WP_CONTENT_DIR .'/uploads/vocento-feeds/'.$xslt)) {
            return format_xml_render(file_get_contents(WP_CONTENT_DIR .'/uploads/vocento-feeds/'.$xslt, true));
        }
        return "";
    } catch (Exception $e) {
        return "";
    }
}

function get_sample_xslt_raw($xslt)
{
    try {
        if (file_exists(WP_CONTENT_DIR .'/uploads/vocento-feeds/'.$xslt)) {
            return file_get_contents(WP_CONTENT_DIR .'/uploads/vocento-feeds/'.$xslt, true);
        }
        return "";
    } catch (Exception $e) {
        echo '';
        return "";
    }
}

function get_sample_xml_raw()
{
    try {
        return file_get_contents('https://' . $_SERVER['HTTP_HOST'] .get_blog_path().'/vocento-feeds/sample-xml', true);
    } catch (Exception $e) {
        echo '';
        return "";
    }
}

function get_sample_xml_raw_posttype($pt)
{
    try {
        if ($pt) {
            $pt=implode(",", $pt);
            return file_get_contents('https://' . $_SERVER['HTTP_HOST'] .get_blog_path().'/vocento-feeds/sample-xml?pt='.$pt, true);
        }
        return get_sample_xml_raw();
    } catch (Exception $e) {
        echo '';
        return "";
    }
}

function get_sample_xml_url()
{
    return 'https://' . $_SERVER['HTTP_HOST'] .get_blog_path().'/vocento-feeds/sample-xml';
}

function get_blog_path()
{
    if (is_multisite()) {
        return get_details_blog_custom(get_current_blog_id())->path;
    } else {
        return "/";
    }
}

function compose_feed_link($feed)
{
    return 'https://' . $_SERVER['HTTP_HOST'] .get_blog_path().$feed["slug"];
}

function get_feed_link_content($feed)
{
    try {
        return file_get_contents(compose_feed_link($feed), true);
    } catch (Exception $e) {
        echo '';
        return "";
    }
}

function compose_key_cache($vcfid)
{
    $final_key="";
    foreach ($_GET as $key => $value) {
        $final_key = "_" . $key . "_" . $value;
    }
    $final_key=$vcfid."_b_".get_current_blog_id()."_".$final_key;

    return $final_key;
}



function get_xslt_filepath($name)
{
    return WP_CONTENT_DIR .'/uploads/vocento-feeds/'.$name;
}

function get_xslt_filepath_clone()
{
    return plugin_dir_path(__FILE__)."../assets/clone.xslt";
}

function get_upload_dir()
{
    return WP_CONTENT_DIR .'/uploads/vocento-feeds/';
}

function vcf_serices_parse_request(&$wp)
{
    if ($wp->matched_query && strpos($wp->matched_query, 'vcfid_net=')!== false) {
        //IMPORTANTE, esta variable vcfid se usar� en el include de abajo
        
        parse_str($wp->matched_query, $get_array);
        $vcfid=$get_array['vcfid_net'];
        include plugin_dir_path(__FILE__).'/services/wildcard_network.php';
        exit();
    }

    if ($wp->matched_query && strpos($wp->matched_query, 'vcfid=')!== false) {
        //IMPORTANTE, esta variable vcfid se usar� en el include de abajo
        
        parse_str($wp->matched_query, $get_array);
        $vcfid=$get_array['vcfid'];
        include plugin_dir_path(__FILE__).'/services/wildcard.php';
        exit();
    }

    //ruta del fichero de ejemplo
    if ($wp->query_vars['pagename'] && $wp->query_vars['pagename']=='vocento-feeds/sample-xml') {
        include plugin_dir_path(__FILE__).'/services/sample.php';
        exit();
    }

    
    
    return;
}


function vcf_get_post_types_mu()
{
    $postTypes=array();
    if (is_plugin_active_for_network('vocento-feeds/vocento-feeds.php')) {
        foreach (wp_get_sites() as $site) {
            $postTypes=array_merge($postTypes, vcf_get_post_types_by_blog($site['blog_id']));
        }
    } else {
        $postTypes=vcf_get_post_types_by_blog(current_blog_id());
    }
    $postTypes= array_unique($postTypes);
    return $postTypes;
}


function vcf_get_post_types_by_blog($blogid)
{
    global $wpdb;

    $args = array(
        'public'   => true,
    );

    $output = 'names'; // names or objects
    if ($blogid) {
        switch_to_blog($blogid);
    }

    $pts = get_post_types($args, $output);
    
    if ($blogid) {
        restore_current_blog();
    }



    

    if (is_multisite()) {
        $select_query="SELECT DISTINCT ".$wpdb->base_prefix.$blogid."_posts.post_type from ".$wpdb->base_prefix.$blogid."_posts";
    } else {
        $select_query="SELECT DISTINCT ".$wpdb->base_prefix."posts.post_type from ".$wpdb->base_prefix."posts";
    }


    $search = $wpdb->get_results($select_query, OBJECT);
    
    foreach ($search as $pt) {
        array_push($pts, $pt->post_type);
    }

    return array_unique($pts);
}

function vcf_get_post_types_names()
{
    $args = array(
        'public'   => true,
    );

    $output = 'names'; // names or objects

    $post_types = get_post_types($args, $output);
    

    return $post_types;
}

function vcf_get_post_types()
{
    $args = array(
        'public'   => true,
    );

    $output = 'objects'; // names or objects

    $post_types = get_post_types($args, $output);
    

    return $post_types;
}

function xml_to_json($xmlcontent)
{
    $fileContents = trim(str_replace('"', "'", $xmlcontent));
    $simpleXml = simplexml_load_string($fileContents);
    $json = json_encode($simpleXml);
    return $json;
}

function xml_to_json_path($xmlpath)
{
    $simpleXml = simplexml_load_file($xmlpath);
    $json = json_encode($simpleXml);
    return $json;
}
function get_post_type_fields($post)
{
    global $wp_query;
    /*$args = array(
        'posts_per_page' => 1,
        'post_type' => $post["post_type"],
        'orderby'=> 'post_date',
        'order' => 'DESC'
    );

    $pt = get_posts( $args );*/
    
    
    if ($post) {
        $fields=array();
        $allfields=array();
        
        
        //$allfields=extract_properties_from_post($pt[0]);
        
        //Usa ACF
        if (class_exists('acf')) {
            $fields = get_fields($post["ID"]);
            $allfields=extract_properties_from_acf($fields);
        } else {
            //Si no devuelve miramos si usa PODS
            if (!$fields && class_exists('pod')) {
                $pod = pods($post['post_type'], $post['ID']);
               
                $allfields=extract_properties_from_pods($pod);
            } else {
                $allfields=get_post_custom_keys($post["ID"]);
            }
        }
        
        
        
        
        return $allfields;
    }
    return null;
}
//BuiltIn fields
function extract_properties_from_post($post)
{
    $fields=array();
    foreach ($post as $key => $field) {
        $fields[$key]="pod_".$key;
        //array_push($fields,$key=>"bi_".$key);
    }
    return $fields;
}

//ACF fields
function extract_properties_from_acf($fields_acf)
{
    $fields=array();
    if (is_array($fields_acf)) {
        foreach ($fields_acf as $key => $field) {
            if ($key && $key!='') {
                $fields[$key]=$field;
            }
        }
    }
    return $fields;
}

//PODS fields
function extract_properties_from_pods($pod)
{
    $fields = $pod->fields();
    $fields_pods=array();

    foreach ($fields as $field) {
        $fields_pods[$field['name']]=$pod->display($field['name']);
    }


    return $fields_pods;
}
