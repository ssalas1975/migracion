<?php
ob_start();
include_once(ABSPATH.'wp-admin/includes/plugin.php');





function vcbc_plugin_active_network(){
    return (is_plugin_active_for_network('vocento-brightcove/vocento-brightcove.php')===true);
}

function vcbc_compose_admin_menu(){

    if(is_network_admin() && vcbc_plugin_active_network()){
        
        vcbc_compose_admin_menu_network();
    }
    else{
        if(!is_network_admin()){
            vcbc_compose_admin_menu_local();
        }
    }

    

}

function vcbc_compose_admin_menu_network(){
	
    add_menu_page( "Vocento Brightcove", "Vocento Brightcove", 'manage_options', "network_vcbc_list" );

    add_submenu_page ( 'network_vcbc_list','Lista medios', 'Lista medios', 'manage_options', 'network_vcbc_list', function(){
       $listMedioPage=new VCBC_Network_List_Medio();
       $listMedioPage->render_page();
    } );

    add_submenu_page ( 'network_vcbc_list','Nuevo medio', 'Nuevo medio', 'manage_options', 'network_vcbc_add_medio', function(){
        $addMedioPage=new VCBC_Network_Add_Medio();
        $addMedioPage->render_page();
    } );

    add_submenu_page ( 'network_vcbc_list','Ajustes', 'Ajustes', 'manage_options', 'network_vcbc_settings', function(){
        $settingsPage=new VCBC_Network_Settings();
        $settingsPage->render_page();
   } );
}

function vcbc_compose_admin_menu_local(){
	
    add_menu_page( "Vocento Brightcove", "Vocento Brightcove", 'manage_options', "site_vcbc_list" );

    add_submenu_page ( 'site_vcbc_list','Lista medios', 'Lista medios', 'manage_options', 'site_vcbc_list', function(){
      $listMedioPage=new VCBC_Site_List_Medio();
       $listMedioPage->render_page();
    } );

    add_submenu_page ( 'site_vcbc_list','Nuevo medio', 'Nuevo medio', 'manage_options', 'site_vcbc_add_medio', function(){
      $addMedioPage=new VCBC_Site_Add_Medio();
        $addMedioPage->render_page();
    } );

    add_submenu_page ( 'site_vcbc_list','Ajustes', 'Ajustes', 'manage_options', 'site_vcbc_settings', function(){
     $settingsPage=new VCBC_Site_Settings();
        $settingsPage->render_page();
   } );
}

function vcbv_compose_form_params($page,$action,$id,$state="")
{
    $params= array(
        'page'=>$page
    );
    
    if($action)
        $params['action']=$action;

    if($id)
        $params['id']=$id;

    if($state)
        $params['state']=$state;
    
    return $params;
}


function vcbc_add_medio($medio_array,$network=false){

    $mediosClass=vcbc_get_medios($network);

    array_push($mediosClass,$medio_array);

    $newvalue=json_encode($mediosClass);

    if($newvalue)
    {

        if($network)
            $options = get_site_option( 'vocentovcbc_options');
        else
            $options = get_option( 'vocentovcbc_options');


        $options['vcbc_medios']=$newvalue;

        if($network)
            update_site_option('vocentovcbc_options',$options,true);
        else
            update_option('vocentovcbc_options',$options,true);


        return true;
    }

    return false;

}

function vcbc_edit_medio($medio_array,$network=false){

    $mediosClass=vcbc_get_medios($network);

    $mediosClass= vcbc_remove_medio($mediosClass,$medio_array);

    array_push($mediosClass,$medio_array);

    $newvalue=json_encode($mediosClass);

    if($newvalue)
    {
        if($network)
            $options = get_site_option( 'vocentovcbc_options');
        else
            $options = get_option( 'vocentovcbc_options');


        $options['vcbc_medios']=$newvalue;

        if($network)
            update_site_option('vocentovcbc_options',$options,true);
        else
            update_option('vocentovcbc_options',$options,true);


        return true;
    }

    return false;

}

function vcbc_delete_medio($medio_array,$network=false){

    $mediosClass=vcbc_get_medios($network);

    $mediosClass= vcbc_remove_medio($mediosClass,$medio_array);

    $newvalue=json_encode($mediosClass);

    if($newvalue)
    {
        $options['vcbc_medios']=$newvalue;

        if($network)
            update_site_option('vocentovcbc_options',$options,true);
        else
            update_option('vocentovcbc_options',$options,true);


        return true;
    }

    return false;

}

function vcbc_remove_medio(&$medios,$medio){
    $index=-1;
    $founded=false;
    foreach($medios as $medio_item){
        $index++;
        if($medio_item["vcbc_medio_id"]==$medio["vcbc_medio_id"])
        {
            $founded=true;
            break;
        }
    }

    if($index>=0 && $founded)
        array_splice($medios,$index,1);

    return $medios;
}

function vcbc_get_all_medios(){

    //Recuperamos todos medios de red y locales
    $medios=array();

    if(vcbc_plugin_active_network())
        $medios=vcbc_get_medios(true);

    $medios=array_merge($medios,vcbc_get_medios(false));
    return $medios;
}

function vcbc_get_medios($network=false){

    if($network)
        $options = get_site_option( 'vocentovcbc_options');
    else
        $options = get_option( 'vocentovcbc_options');


    $medios=$options['vcbc_medios'];

    if(!$medios)
        $medios="[]";

    $mediosClass=json_decode($medios,true);

    return $mediosClass;
}


function vcbc_get_active_medios(){

    //Recuperamos todos medios de red y locales
    $medios=vcbc_get_all_medios();


    //Solos los medios activos
    $active_medios=array();

    foreach($medios as $medio){
        if(VC_CMSAPI_OAuth::IS_VALID_TOKEN($medio["vcbc_medio_accid"],$medio["vcbc_medio_id"]))
            array_push($active_medios,$medio);
    }

    return $active_medios;

}

function vcbc_get_medio_id($id){
    $medios=vcbc_get_all_medios();
    foreach($medios as $medio){
        if($id==$medio["vcbc_medio_id"])
            return $medio;
    }

    return null;
}


function vcbc_get_medio_account($accountid){
    $medios=vcbc_get_all_medios();
    foreach($medios as $medio){
        if($accountid==$medio["vcbc_medio_accid"])
            return $medio;
    }

    return null;
}


function vcbc_get_medio_account_site($accountid){
    $medios=vcbc_get_medios(false);
    foreach($medios as $medio){
        if($accountid==$medio["vcbc_medio_accid"])
            return $medio;
    }

    return null;
}


function vcbc_init_tokens(){

    //Recuperamos los medios de la red y del blog (un blog debe tener disponibles los de la red)
    $mediosClass=vcbc_get_all_medios();

    $credentials= array();

    foreach($mediosClass as $medio){
        $credential=array(
            'clientid'=>$medio['vcbc_medio_clientid'],
            'clientsecret'=>$medio['vcbc_medio_clientsecret'],
            'accid'=>$medio['vcbc_medio_accid'],
            'medio_id'=>$medio['vcbc_medio_id']
        );

        array_push($credentials,$credential);
    }
    
    VC_CMSAPI_OAuth_Manager::GENERATE_TOKENS($credentials);
}



function vcbc_enqueue($hook) {
    wp_enqueue_script('thickbox');

    wp_enqueue_script("jquery");
    
    wp_enqueue_script( 'datatables',
                plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/js/jquery.dataTables.min.js' ),
                array('jquery')
    );
    
    wp_enqueue_script( 'plvcbc',
               plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/js/plupload.full.min.js' ),
                array('jquery')
   );

    

    wp_enqueue_style( 'vocentobc_styles', plugins_url('/vocento-brightcove/vocento-brightcove/admin/assets/css/style.css')  );

    wp_enqueue_style( 'vocentobc_datatable', plugins_url('/vocento-brightcove/vocento-brightcove/admin/assets/css/jquery.dataTables.min.css')  );
    
}


function vcbc_add_video_tab( $tabs ) {
    $newtab = array( 'vcbc_media' => 'Vocento Brightcove' );
    return array_merge( $tabs, $newtab );
}


function vcbc_add_video_tab_content() {
    wp_enqueue_script('thickbox');

    wp_enqueue_script( 'plvcbc',
                plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/js/plupload.full.min.js' ),
                 array('jquery','jqueryui')
    );

    wp_enqueue_script("jquery-ui-core"); 

    wp_enqueue_script( 'vcbc-ajax-script-ui',
                    plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/js/jquery-ui.min.js'),
                    array('jquery')
        );

    wp_enqueue_style( 'jqueryui', plugins_url('/vocento-brightcove/vocento-brightcove/admin/assets/css/jquery-ui.css')  );

    wp_enqueue_style( 'vocentobc_styles', plugins_url('/vocento-brightcove/vocento-brightcove/admin/assets/css/style.css')  );

    wp_iframe( 'vcbc_render_video_tab_content' );


}

function vcbc_render_video_tab_content() {

    $vcmediaUpload=new VCBC_Media_Upload_Page();
    $vcmediaUpload->render_page();
}

function validate_media_form_fields(){
    return (isset($_POST["vcbc_media_channel"]) &&isset($_POST["vcbc_media_main_category"]) && isset($_POST['vcbc_media_name']) && isset($_POST['vcbc_media_small_desc']) && isset($_POST['vcbc_media_desc'])  && isset($_POST['vcbc_media_tags']) && isset($_POST['vcbc_video_tmp_name'])); 
}

function validate_media_edit_form_fields(){
    return (isset($_POST["vcbc_media_main_category"]) && isset($_POST['vcbc_media_name']) && isset($_POST['vcbc_media_small_desc']) && isset($_POST['vcbc_media_desc'])  && isset($_POST['vcbc_media_tags'])); 
}

function vcbc_compose_filter_video(){

    $filter= array();
    $filter['criteria']=array();

    //Es un filtrado
    if(isset($_POST["vcbc_select_date"]))
        $filter['criteria']['created_at']=$_POST["vcbc_select_date"];

    if(isset($_POST["vcbc_select_tags"])){
        $filter['criteria']['tags']=$_POST["vcbc_select_tags"];
        
    }

    if(isset($_POST["vcbc_select_txt"]))
    {
        $filter['criteria']['q']=$_POST["vcbc_select_txt"];
        
    }

    if(isset($_POST["vcbc_filter_medio"]))
    {
        $themedio=vcbc_get_medio_id($_POST["vcbc_filter_medio"]);
        $filter['medio']=  $themedio["vcbc_medio_accid"];
        $filter['medio_id']=  $_POST["vcbc_filter_medio"];
    }

    return $filter;

}


function vcbc_compose_params_read_video($array_filter){

    $url="";
    if($array_filter["criteria"]){

        if($array_filter["criteria"]['q'])
            $url.="%2B".$array_filter["criteria"]['q'];

        if($array_filter["criteria"]['tags']){
            if($url)
                $url.="+%2B";

            $url.="tags:".$array_filter["criteria"]['tags'];
        }
        if($array_filter["criteria"]['created_at']){
            if($url)
                $url.="+%2B";
            $url.="created_at:".$array_filter["criteria"]['created_at'];
        }
    }

    return $url;

}


function vcbc_get_plugin_option($option_name){

    $option=vcbc_get_plugin_local_option($option_name);
    if(!$option)
        $option= vcbc_get_plugin_network_option($option_name);

    return $option;

}

function vcbc_get_plugin_local_option($option_name){

    $options = get_option( 'vocentovcbc_options');

    $config=$options['vcbc_settings'];

    if(!$config)
        $config="[]";

    $config=json_decode($config,true);

    return $config[$option_name];
}



function vcbc_get_plugin_network_option($option_name){

    $options = get_site_option( 'vocentovcbc_options');

    $config=$options['vcbc_settings'];

    if(!$config)
        $config="[]";

    $config=json_decode($config,true);

    return $config[$option_name];
}


function vcbc_secciones_methode_to_array($url,$pattern,$main){

    $ddoptions=array();
    if($url){
        try{
            
            $xml = file_get_contents($url, false);
            $xml = simplexml_load_string($xml);
            $secciones=$xml->secciones->seccion;

            //Chapu, debería ser recursivo :TODO:

            if($secciones){
                foreach($secciones as $seccion){
                    array_push($ddoptions, vcbc_compose_dd_value_pattern($seccion,false,$pattern,$main));
                    foreach($seccion->seccion as $child_seccion){
                        array_push($ddoptions, vcbc_compose_dd_value_pattern($child_seccion,false,$pattern,$main));

                        foreach($child_seccion->seccion as $child_l2_seccion){
                            array_push($ddoptions, vcbc_compose_dd_value_pattern($child_seccion,$child_l2_seccion,$pattern,$main));
                        }
                    }
                }
            }
        }
        catch(Exception $e){
            
        }
    }
    return $ddoptions;
    
}

function vcbc_is_checked_category_tags($arr_tags,$category){

    foreach($arr_tags as $tag)
    {
        if(strtolower($tag)==strtolower($category))
            return true;
    }
    return false;
}

function vcbc_secciones_methode_to_dropdown($url,$pattern,$main,$tags=false){
    $xml = file_get_contents($url, false);
    $xml = simplexml_load_string($xml);
    $secciones=$xml->secciones->seccion;
    

    $ddoptions="";

    //Chapu, debería ser recursivo :TODO:
    foreach($secciones as $seccion){

        $optionValue=vcbc_compose_dd_value_pattern($seccion,false,$pattern,$main);

        $checked="";
        if($tags && vcbc_is_checked_category_tags($tags,$optionValue))
            $checked="selected";

        $ddoptions.="<option ".$checked." name='".$seccion->nombre."' value='".$optionValue ."'>".$seccion->nombre."</option>";
        
        foreach($seccion->seccion as $child_seccion){

            $optionValueL1=vcbc_compose_dd_value_pattern($child_seccion,false,$pattern,$main);

            $checked="";
            if($tags && vcbc_is_checked_category_tags($tags,$optionValueL1))
                $checked="selected";

            $ddoptions.="<option ".$checked." name='".$child_seccion->nombre."' value='".$optionValueL1."'>-->".$child_seccion->nombre."</option>";

            foreach($child_seccion->seccion as $child_l2_seccion){

                $optionValueL2=vcbc_compose_dd_value_pattern($child_seccion,$child_l2_seccion,$pattern,$main);
                
                $checked="";
                if($tags && vcbc_is_checked_category_tags($tags,$optionValueL2))
                    $checked="selected";

                $ddoptions.="<option ".$checked."  name='".$child_l2_seccion->nombre."' value='".$optionValueL2."'>------>".$child_l2_seccion->nombre."</option>";
            }
        }
    }
    return $ddoptions;
    
}

function vcbc_compose_dd_value_pattern($parent,$child,$pattern,$main){
    
    $section_slug="";
    if($child)
        $section_slug=vcbc_create_section_methode($parent,$pattern,false);
    else
        $section_slug=vcbc_create_section_methode($parent,$pattern,$main);

    if($child)
        $section_slug.="/".vcbc_create_section_methode($child,$pattern,$main);

    return $section_slug;

}

function vcbc_create_section_methode($node,$pattern,$main){

    $aux_pattern=$pattern;

    $ext="0";

    if(strpos($node->url,"http:")!=false)
        $ext="1";
    $aux_pattern=str_replace("%%_ext_%%",$ext,$aux_pattern);

    //nombre
    $aux_pattern=str_replace("%%_name_%%",$node->nombre,$aux_pattern);

    //categoria
    $aux_pattern=str_replace("%%_cat_%%",$node->categoria,$aux_pattern);

    //id
    if(property_exists($node,"id"))
        $aux_pattern=str_replace("%%_id_%%",$node->id,$aux_pattern);

    if($main)
        $aux_pattern.="/main";

    return $aux_pattern;
}



function vcbc_format_miliseconds($milliseconds) {
    $seconds = floor($milliseconds / 1000);
    $minutes = floor($seconds / 60);
    $hours = floor($minutes / 60);
    $milliseconds = $milliseconds % 1000;
    $seconds = $seconds % 60;
    $minutes = $minutes % 60;

    $format = '%u:%02u:%02u.%03u';
    $time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
    return rtrim($time, '0');
}

function vcbc_format_bytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 



function vcbc_header_handler() {


    //Si se ha marcado para usar la red cargamos la config de la red o si no se ha guardado nada en la config local (null)
    if(vcbc_plugin_active_network() && (vcbc_get_plugin_local_option("vcbc_vocento_js_usenetwork")===null || vcbc_get_plugin_local_option("vcbc_vocento_js_usenetwork"))){
        
        if(vcbc_get_plugin_network_option("vcbc_vocento_js"))
        {
            echo html_entity_decode(vcbc_get_plugin_network_option("vcbc_vocento_js_code"));
        }
    }
    else{ 
        // si no cargamos la local
        if(vcbc_get_plugin_local_option("vcbc_vocento_js"))
        {
            echo html_entity_decode(vcbc_get_plugin_local_option("vcbc_vocento_js_code"));
        }
    }

}




function vcbc_ajax_methode_sections() {
	

    $jsonSections=array();
    if(isset($_POST['medio_id'])){
        $medio=vcbc_get_medio_id($_POST['medio_id']);
        if($medio && $medio["vcbc_vocento_url_methode"]){
            $jsonSections["main"]= vcbc_secciones_methode_to_dropdown($medio["vcbc_vocento_url_methode"],$medio["vcbc_vocento_url_methode_tmpl"],true);
            $jsonSections["secondary"]= vcbc_secciones_methode_to_dropdown($medio["vcbc_vocento_url_methode"],$medio["vcbc_vocento_url_methode_tmpl"],false);
        }
        
    }
    echo json_encode($jsonSections);
	wp_die(); 
}

function vcbc_ajax_vcbc_edit_video() {
    //Refrescamos tokens
    vcbc_init_tokens();

    $output="";
    if(isset($_POST['vcbc_video_id']) && isset($_POST['vcbc_medio_id'])){
        ob_start();
        
        $vcbc_videoid=$_POST['vcbc_video_id'];
        $vcbc_medioid=$_POST['vcbc_medio_id'];
        $edit=new VCBC_Media_Edit($vcbc_videoid,$vcbc_medioid);
        $edit->render_page();

        $output = ob_get_clean();
        
    }
    echo $output;
	wp_die(); 

}

function vcbc_ajax_vcbc_edit_video_post() {
    //Refrescamos tokens
    vcbc_init_tokens();

    $output="";
    if(isset($_POST['vcbc_video_id']) && isset($_POST['vcbc_medio_id'])){
        ob_start();
        
        $vcbc_videoid=$_POST['vcbc_video_id'];
        $vcbc_medioid=$_POST['vcbc_medio_id'];
        $edit=new VCBC_Media_Edit($vcbc_videoid,$vcbc_medioid);
        $edit->render_page();

        $output = ob_get_clean();
        
    }
    echo $output;
	wp_die(); 

}


/**
 * Busca en los tags de un video el de X:No_Publicado
 * @param mixed $tags 
 */
function vcbc_video_is_published($tags){

    if(is_array($tags)){
        $tags=array_map(function($var) {
                   return mb_strtolower($var, mb_detect_encoding($var));
           }, $tags);

        if(array_search("x:no_publicar",$tags))
            return false;
        return true;
    }
    return true;

}


/**
 * Devuelve los tags introducidos por el usuario. Se calculan limpiando los obligatorios del medio y el X:No_Publicado y los de categorias
 * @param mixed $tags 
 */
function vcbc_get_user_tags($tags,$medio){

    $userTags=array();
    

    if($medio)
    {
        $vcbc_required_tags=$medio['vcbc_required_tags'];

        
        if(!is_array($vcbc_required_tags))
            $vcbc_required_tags=explode(",",$vcbc_required_tags);

        $vcbc_required_tags=array_map(function($var) {
              return mb_strtolower($var, mb_detect_encoding($var));
      }, $vcbc_required_tags);


        //tags principales
        $maincategorytags= vcbc_secciones_methode_to_array($medio['vcbc_vocento_url_methode'],$medio['vcbc_vocento_url_methode_tmpl'],true);
        $maincategorytags=array_map(function($var) {
                return mb_strtolower($var, mb_detect_encoding($var));
        }, $maincategorytags);

        //tags secundarios
        $categorytags= vcbc_secciones_methode_to_array($medio['vcbc_vocento_url_methode'],$medio['vcbc_vocento_url_methode_tmpl'],false);
        $categorytags=array_map(function($var) {
                return mb_strtolower($var, mb_detect_encoding($var));
        }, $categorytags);



        //comprobacion de tags
        if(is_array($tags))
        {
            foreach($tags as $tag){

                $finded=false;

                //Tags requeridos
                if(array_search(mb_strtolower($tag, mb_detect_encoding($tag)),$vcbc_required_tags)!==false){
                    $finded=true;
                }
                


                //Si ya ha sido encontrado no seguimos buscando
                if(!$finded){
                    if(array_search(mb_strtolower($tag, mb_detect_encoding($tag)),$maincategorytags)!==false){
                        $finded=true;
                    }
                }

                //Si ya ha sido encontrado no seguimos buscando
                if(!$finded){
                    if(array_search(mb_strtolower($tag, mb_detect_encoding($tag)),$categorytags)!==false){
                        $finded=true;
                    }
                }


                //El no publicar tambien lo excluimos, lo marcamos como encontrado
                if(mb_strtolower($tag)=="x:no_publicar")
                    $finded=true;

                if(!$finded)
                    array_push($userTags,$tag);


            }
        }

    }
    return array_unique ($userTags);


}

?>