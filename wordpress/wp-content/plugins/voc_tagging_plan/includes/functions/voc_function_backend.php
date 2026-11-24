<?php

/**
 * Undocumented function
 *
 * @return void
 */
function voc_adobe_settings()
{
    require plugin_dir_path( __DIR__ ) . 'views/voonto_settings_view.php';
}

/**
 * Undocumented function
 *
 * @return void
 */
function marcado_analitica()
{
    require plugin_dir_path( __DIR__ ) . 'views/marcado_analitica.php';
}

/**
 * Undocumented function
 *
 * @param [type] $hook
 * @return void
 */
function load_voc_dtm_wp_admin_style($hook) {
    //die($hook);
    // Load only on ?page=mypluginname
    if($hook != 'adobe-dtm_page_marcado_analitica' && $hook != 'adobe-dtm_page_createtagging' && $hook != 'toplevel_page_adobe-settings') {
            return;
    }
    wp_register_style( 'voc_bootstrap_admin_css', plugin_dir_url( __DIR__ ).'assets/css/bootstrap.min.css', false, '1.0.0' );
    wp_enqueue_style( 'voc_bootstrap_admin_css' );

    wp_register_style( 'voc_back_dtm_admin_css', plugin_dir_url( __DIR__ ).'assets/css/voc_back_dtmadobe.css', false, '1.0.0' );
    wp_enqueue_style( 'voc_back_dtm_admin_css' );

}
add_action( 'admin_enqueue_scripts', 'load_voc_dtm_wp_admin_style' );



function load_voc_dtm_wp_admin_script($hook) {
    if ($hook != 'adobe-dtm_page_marcado_analitica' && $hook != 'adobe-dtm_page_createtagging' && $hook != 'toplevel_page_adobe-settings') {
        return;
    }
    wp_enqueue_script( 'voc_jquery_slim_script', plugin_dir_url( __DIR__ ).'assets/js/jquery-3.3.1.slim.min.js' );
    wp_enqueue_script( 'voc_bootstrap_admin_script', plugin_dir_url( __DIR__ ).'assets/js/bootstrap.min.js' );
    wp_enqueue_script( 'voc_back_dtm_admin_script', plugin_dir_url( __DIR__ ).'assets/js/voc_back_dtmadobe.js' );
}
add_action( 'admin_enqueue_scripts', 'load_voc_dtm_wp_admin_script', true );

add_action( 'wp_ajax_save_variables', 'save_variables' );
add_action( 'wp_ajax_nopriv_save_variables', 'save_variables' );
function save_variables() {
    $obj = $_POST['obj'];
    $perico = "";
    $voc_vars = get_option('voc_vars');
	if( $voc_vars == false || $voc_vars == "" || $voc_vars == null ){
        $patron[] = $obj;
        update_option('voc_vars',$patron);
        exit( json_encode($patron) );
    }else{
        if( array_search($obj['idpatron'], array_column($voc_vars, 'idpatron')) !== false )
        {
            $idArray = array_search($obj['idpatron'], array_column($voc_vars, 'idpatron'));
            foreach ($obj['variables'] as $clave => $valor) {
                foreach($voc_vars[$idArray]['variables'] as $key => $value)
                {
                    if($key == $clave){
                        $voc_vars[$idArray]['variables'][$key] = $valor;
                    }else{
                        $voc_vars[$idArray]['variables'][$clave] = $valor;
                    }
                }
            }
            update_option('voc_vars', $voc_vars);
            exit( json_encode($voc_vars) );
        }else{
            array_push($voc_vars, $obj);
            update_option('voc_vars', $voc_vars);
            exit( json_encode($voc_vars) );
        }
    }
}

add_action( 'wp_ajax_save_url', 'save_url' );
add_action( 'wp_ajax_nopriv_save_url', 'save_url' );
function save_url() {
    $obj = $_POST['obj'];
    $options = get_option('patron_url');
    if( get_option('patron_url') == false)
    {
        $obj['id'] = 1;
        $patron[] = $obj;
        update_option('patron_url',$patron);
    }else{
        $count = count(get_option('patron_url'));
        $obj['id'] = $count+1;
         $patron[] = $obj;
         array_push($options,$obj);
         update_option('patron_url',$options);
    }
	exit( json_encode($options) );
}

add_action( 'wp_ajax_get_urls', 'get_urls' );
add_action( 'wp_ajax_nopriv_get_urls', 'get_urls' );
function get_urls() {
	$urls = get_option('patron_url');
	exit( json_encode($urls) );
}


add_action( 'wp_ajax_get_voc_vars', 'get_voc_vars' );
add_action( 'wp_ajax_nopriv_get_voc_vars', 'get_voc_vars' );
function get_voc_vars() {
    $voc_vars = get_option('voc_vars');
    $id = intval($_POST['id']);
    $length = count($voc_vars);

    for($i =0; $i < $length; $i++)
    {
        if( $voc_vars[$i]['idpatron'] == $id)
        {
            exit( json_encode($voc_vars[$i]) );
        }else{
            continue;
        }
    }
}


add_action( 'wp_ajax_updateUrl', 'updateUrl' );
add_action( 'wp_ajax_nopriv_updateUrl', 'updateUrl' );
function updateUrl() {
    $voc_vars = get_option('voc_vars');

    $id = intval($_POST['id']);
    $url = $_POST['url'];
    $urls = get_option('patron_url');

    $key = array_search($id, array_column($urls, 'id'));
    $urls[$key]['url'] = $url;

    update_option('patron_url', $urls);
    exit( json_encode($url) );
}
