<?php

function vclm_compose_admin_menu(){

    if(is_network_admin() && vclm_is_active_network()){
        
        vclm_compose_admin_menu_network();
    }
    else{
        if(!is_network_admin()){
            vclm_compose_admin_menu_local();
        }
    }

}

function vclm_compose_admin_menu_network(){
	
    add_menu_page( "Vocento Lo más", "Vocento Lo más", 'manage_options', "network_vclm_design" );

    add_submenu_page ( 'network_vclm_design','Conf. Visual', 'Conf. Visual', 'manage_options', 'network_vclm_design', function(){
       $listMedioPage=new VCLM_Design();
       $listMedioPage->render_page();
    } );

    add_submenu_page ( 'network_vclm_design','Conf. Stats', 'Conf. Stats', 'manage_options', 'network_vclm_stats', function(){
        $addMedioPage=new VCLM_Stats();
        $addMedioPage->render_page();
    } );

    add_submenu_page ( 'network_vclm_design','Conf. Gigya', 'Conf. Gigya', 'manage_options', 'network_vclm_gigya', function(){
        $addMedioPage=new VCLM_Gigya();
        $addMedioPage->render_page();
    } );

    add_submenu_page ( 'network_vclm_design','Conf. Addthis', 'Conf. Addthis', 'manage_options', 'network_vclm_addthis', function(){
        $settingsPage=new VCLM_Addthis();
        $settingsPage->render_page();
   } );
}

function vclm_compose_admin_menu_local(){
	
    add_menu_page( "Vocento Lo más", "Vocento Lo más", 'manage_options', "site_vclm_design");

    add_submenu_page ( 'site_vclm_design','Conf. Visual', 'Conf. Visual', 'manage_options', 'site_vclm_design', function(){
		$listMedioPage=new VCLM_Design();
        $listMedioPage->render_page();
    } );

    add_submenu_page ( 'site_vclm_design','Conf. Stats', 'Conf. Stats', 'manage_options', 'site_vclm_stats', function(){
       $listMedioPage=new VCLM_Stats();
       $listMedioPage->render_page();
   } );

    add_submenu_page ( 'site_vclm_design','Conf. Gigya', 'Conf. Gigya', 'manage_options', 'site_vclm_gigya', function(){
		$addMedioPage=new VCLM_Gigya();
        $addMedioPage->render_page();
    } );

    add_submenu_page ( 'site_vclm_design','Conf. Addthis', 'Conf. Addthis', 'manage_options', 'site_vclm_addthis', function(){
		$settingsPage=new VCLM_Addthis();
        $settingsPage->render_page();
   } );
}

function vclm_enqueue($hook) {
    
    wp_enqueue_script("jquery-ui");

   
    
    wp_enqueue_script( 'datatables-lomas',
                plugins_url( '../assets/js/jquery.dataTables.min.js', __FILE__ ),
                array('jquery')
    );
    
    wp_enqueue_style( 'vocentovclm_lomas_styles', plugins_url('../assets/css/style.css', __FILE__)  );

    wp_enqueue_style( 'vocentovclm_datatable-lomas', plugins_url('../assets/css/jquery.dataTables.min.css', __FILE__)  );
    
}


add_action( 'admin_init', function(){
         
           register_setting(
               'vocentovclm_option_group', // Option group
               'vocentovclm_options' // Option name
           );

       } );

function vclm_print_links_settings(){
    if(is_network_admin()){
?>
<ul class="vclm-miga-settings">
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("network_vclm_design","",""), network_admin_url('admin.php'));?>">Conf. Visual</a>
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("network_vclm_stats","",""), network_admin_url('admin.php'));?>">Conf. Stats</a>
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("network_vclm_gigya","",""), network_admin_url('admin.php'));?>">Conf.Gigya</a>
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("network_vclm_addthis","",""), network_admin_url('admin.php'));?>">Conf. Addthis</a>
</ul>
<?php
    }
    else{
?>
<ul class="vclm-miga-settings">
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("site_vclm_design","",""), admin_url('admin.php'));?>">Conf. Visual</a>
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("site_vclm_stats","",""), admin_url('admin.php'));?>">Conf. Stats</a>
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("site_vclm_gigya","",""), admin_url('admin.php'));?>">Conf.Gigya</a>
    <li><a href="<?php echo add_query_arg(vclm_composeFormParams("site_vclm_addthis","",""), admin_url('admin.php'));?>">Conf. Addthis</a>
 </ul>
<?php
    }
}


function vclm_composeFormParams($page,$action,$id)
	{
		$params= array(
            'page'=>$page
        );
		
		if($action)
			$params['action']=$action;

        if($id)
			$params['id']=$id;
		
		return $params;
	}

function vclm_addErrorMessage($error,$page)
{
    array_push($page->messages,array('class'=>'notice-error', 'msg'=>$error));
}
	
function vclm_addMessage($mesage,$page)
{
	array_push($page->messages,array('class'=>'notice-success', 'msg'=>$mesage));
}

function vclm_displayMessages($page)
{
	foreach($page->messages as $msg){
		echo "<div class='notice  ".$msg['class']."'>".$msg['msg']."</div>";
	}
}

function vclm_get_post_types_mu(){
	
	$postTypes=array();
	if(vclm_is_active_network()){
		
		foreach(wp_get_sites() as $site){
			$postTypes=array_merge($postTypes,vclm_get_post_types_by_blog($site['blog_id']));
		}
        
	}
	else{
		
		$postTypes=vclm_get_post_types_by_blog(get_current_blog_id());
	}
	$postTypes= array_unique($postTypes);
    return $postTypes;
}

function vclm_get_post_types_by_blog($blogid=null){

    global $wpdb;

	$args = array(
		'public'   => true,
	);

	$output = 'names'; // names or objects
    if($blogid && function_exists('switch_to_blog'))
	    switch_to_blog($blogid);

	$pts = get_post_types( $args, $output );
	
    if($blogid && function_exists('switch_to_blog'))
	    restore_current_blog();



   

    if(is_multisite())
        $select_query="SELECT DISTINCT ".$wpdb->base_prefix.$blogid."_posts.post_type from ".$wpdb->base_prefix.$blogid."_posts"; 
    else
        $select_query="SELECT DISTINCT ".$wpdb->base_prefix."posts.post_type from ".$wpdb->base_prefix."posts"; 


    $search = $wpdb->get_results($select_query,OBJECT);
   
    foreach($search as $pt){
        array_push($pts,$pt->post_type);
    }

	return array_unique($pts);	
}

function vclm_get_post_types_names(){

	$args = array(
		'public'   => true,
	);

	$output = 'names'; // names or objects

	$post_types = get_post_types( $args, $output );
	

	return $post_types;
}

function vclm_get_post_types(){

	$args = array(
		'public'   => true,
	);

	$output = 'objects'; // names or objects

	$post_types = get_post_types( $args, $output );
	

	return $post_types;
}

function vclm_add_site_to_category(&$categories,$sitename){

    foreach($categories as &$category){
        $category->name=$sitename.": ".$category->name;
    }
}

function vclm_add_site_to_tag(&$tags,$sitename){

    foreach($tags as &$tag){
        $tag->name=$sitename.": ".$tag->name;
    }
}

function vclm_add_site_to_author(&$authors,$sitename){

    foreach($authors as &$author){
        $author->data->display_name=$sitename.": ".$author->data->display_name;
    }
}

function vclm_get_all_categories($network=false){

    if(!function_exists("wp_get_sites") || $network==false){
        $categories=get_categories();
    } 
    else{
        $categories=array();
    }

   

    if(function_exists("wp_get_sites") && $network){
        foreach(wp_get_sites() as $site){

            if(function_exists("switch_to_blog"))
                switch_to_blog($site['blog_id']);

            $site_categories=get_categories();

            vclm_add_site_to_category($site_categories,get_blog_details($site['blog_id'])->blogname);

            $categories=array_merge($categories,$site_categories);
        }
    }

    if(function_exists("restore_current_blog"))
        restore_current_blog();
     
    return $categories;
}

function vclm_get_all_tags($network=false){

    if(!function_exists("wp_get_sites") || $network==false){
        $categories=get_tags();
    }
    else{
        $categories=array();
    }

    if(function_exists("wp_get_sites")  && $network){
        foreach(wp_get_sites() as $site){

            if(function_exists("switch_to_blog"))
                switch_to_blog($site['blog_id']);

            $site_categories=get_tags();

            vclm_add_site_to_tag($site_categories,get_blog_details($site['blog_id'])->blogname);

            $categories=array_merge($categories,$site_categories);
        }
    }

    if(function_exists("restore_current_blog"))
        restore_current_blog();
    
    return $categories;
}

function vclm_get_all_authors($network=false){

    if(!function_exists("wp_get_sites") || $network==false){
        $categories= get_users('orderby=nicename&role=editor');
    }
    else{
        $categories=array();
    }

   

    if(function_exists("wp_get_sites")  && $network){
        foreach(wp_get_sites() as $site){

            if(function_exists("switch_to_blog"))
                switch_to_blog($site['blog_id']);

            $site_categories=get_users('orderby=nicename&role=editor' );

            vclm_add_site_to_author($site_categories,get_blog_details($site['blog_id'])->blogname);

            $categories=array_merge($categories,$site_categories );
        }
    }

    if(function_exists("restore_current_blog"))
        restore_current_blog();
    
    return $categories;
}

function vclm_get_stats_config($network=false){


    $config = get_option( 'vocentovclm_options');
    $stats_config = $config ['vclm_design_stats'];

    //si tiene marcado que quiere la red se devuelve la red
    if(!$network){
        if($stats_config["vclm_stats_use_network"]=="true"){
            return vclm_get_stats_config(true);
        }
    }


    if(!$config || !$stats_config || $network){
        $config = get_site_option( 'vocentovclm_options');
        $stats_config = $config ['vclm_design_stats'];
    }

    return $stats_config;

}

function vclm_get_gigya_config($network=false){


    $config = get_option( 'vocentovclm_options');
    $stats_config = $config ['vclm_design_gigya'];

    //si tiene marcado que quiere la red se devuelve la red
    if(!$network){
        if($stats_config["vclm_gigya_use_network"]=="true"){
            return vclm_get_gigya_config(true);
        }
    }


    if(!$config || !$stats_config || $network){
        $config = get_site_option( 'vocentovclm_options');
        $stats_config = $config ['vclm_design_gigya'];
    }

    return $stats_config;

}

function vclm_get_addthis_config($network=false){


    $config = get_option( 'vocentovclm_options');
    $stats_config = $config ['vclm_design_addthis'];
    
    //si tiene marcado que quiere la red se devuelve la red
    if(!$network){
        if($stats_config["vclm_addthis_use_network"]=="true"){
            return vclm_get_addthis_config(true);
        }
    }

        if(!$config || !$stats_config  || $network){
        $config = get_site_option( 'vocentovclm_options');
        $stats_config = $config ['vclm_design_addthis'];
    }

    return $stats_config;

}


function vclm_is_tab_available($tabname){

    

    $available=false;
    switch($tabname){
    
        case "comment": {$available= vclm_get_option(array('vclm_design_gigya','vclm_gigya_apikey'));} break;
        case "view":  {$available= vclm_get_option(array('vclm_design_stats','vclm_stats_url'));} break;
        case "shared": {$available= vclm_get_option(array('vclm_design_addthis','vclm_addthis_pubid'));}  break;
        case "publish": $available= true; break;
    }

    return $available;

}

function vclm_get_option($options_names,$network=false){

    if($network)
        $config = get_site_option( 'vocentovclm_options');
    else
        $config = get_option( 'vocentovclm_options');

    $child_config = $config [$options_names[0]];

    //si pido de un blog tengo que ver si el blog tiene marcada la opcion de usar datos de red.
    if(!$network){
        $use_network_key="";

        switch($options_names[0]){
            case "vclm_design_addthis":$use_network_key="vclm_addthis_use_network"; break;
            case "vclm_design_css": $use_network_key="vclm_design_css_use_network"; break;
            case "vclm_design_gigya": $use_network_key="vclm_gigya_use_network"; break;
            case "vclm_design_stats": $use_network_key="vclm_stats_use_network"; break;
        }

        $must_use_network=false;

        if($use_network_key){
            if($use_network_key=="vclm_design_css_use_network")
                $must_use_network=$config [$use_network_key]=="true";
            else{
                $must_use_network=$child_config [$use_network_key]=="true";
            }

            if($must_use_network==true)
                return vclm_get_option($options_names,true);
        }


    }




    if(count($options_names)>1){
        $child_config = $child_config [$options_names[1]];
    }



    //if($child_config==null && !$network){
    //    return vclm_get_option($options_names,true);
    //}

    return $child_config;

}

function vclm_get_hours_sice($post_date){
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
            return $diff->format('Hace %h horas y %i minutos');
        else
            return $diff->format('Hace %i minutos');
    }
}

function vclm_sort_post_date($a, $b) {
    return $b->post_date - $a->post_date;
}


function vclm_get_post_permalink($post){

    if($post["blog_id"]  && function_exists("switch_to_blog"))
        switch_to_blog($post["blog_id"]);

    $link=get_permalink($post["ID"]);

	if(function_exists("restore_current_blog"))
		restore_current_blog();

    return $link;
}

function vclm_has_post_thumbnail($postarray){
    if($postarray["blog_id"] && function_exists("switch_to_blog"))
        switch_to_blog($postarray["blog_id"]);

    $has=has_post_thumbnail($postarray["ID"]);
	
	if(function_exists("restore_current_blog"))
		restore_current_blog();

    return $has;

       
}

function vclm_wp_get_attachment_url($postarray){

    if($postarray["blog_id"])
        switch_to_blog($postarray["blog_id"]);

    $url= wp_get_attachment_url( get_post_thumbnail_id($postarray["ID"]) );

    restore_current_blog();

    return $url;

       
}

function vclm_CURL($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}





function vclm_CURL_AUTH($url,$username,$password) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function vclm_cleanencoded( $encoded ) {

    $encoded = str_replace("amp%3B", "",$encoded);

    return $encoded;
}


function vclm_footer_scripts(){
    
    if(vclm_get_option(array("vclm_design_stats","vclm_stats_include"))==true){
        if(is_single()){
            VCLM_Stats_srv::VCLM_STATS_PRINT_METRIC(get_post());
        }
    }

}


add_action("wp_print_footer_scripts","vclm_footer_scripts");


function vclm_is_active_network(){
    if(!is_multisite())
        return false;

    return is_plugin_active_for_network('vocento-lomas/vocento-lomas.php');
}


?>