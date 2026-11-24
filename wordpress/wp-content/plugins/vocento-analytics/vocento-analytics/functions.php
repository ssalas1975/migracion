<?php
/*Register options*/
add_action('admin_init', function () {

    register_setting(
        'vocentovcanly_option_group', // Option group
        'vocentovcanly_options' // Option name
    );

});

function vcanly_compose_admin_menu()
{

    if (is_network_admin() && vcanly_is_active_network()) {

        vcanly_compose_admin_menu_network();
    } else {
        if (!is_network_admin()) {
            vcanly_compose_admin_menu_local();
        }
    }

}

function vcanly_compose_admin_menu_network()
{

    add_menu_page("Vocento Analytics", "Vocento Analytics", 'manage_options', "network_vcanly_overview");

    add_submenu_page('network_vcanly_overview', 'Estado', 'Estado', 'manage_options', 'network_vcanly_overview',
        function () {
            $listMedioPage = new VocentoAnalyticsAdminOverview();
            $listMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. comScore', 'Conf. comScore', 'manage_options',
        'network_vcanly_comscore', function () {
            $listMedioPage = new VocentoAnalyticsAdminComScore();
            $listMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. GA', 'Conf. GA', 'manage_options', 'network_vcanly_ga',
        function () {
            $addMedioPage = new VocentoAnalyticsAdminGA();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. GTM', 'Conf. GTM', 'manage_options', 'network_vcanly_gtm',
        function () {
            $addMedioPage = new VocentoAnalyticsAdminGTM();
            $addMedioPage->render_page();
        });


    add_submenu_page('network_vcanly_overview', 'Conf. AMP', 'Conf. AMP', 'manage_options', 'network_vcanly_amp',
        function () {
            $addMedioPage = new VocentoAnalyticsAdminAMP();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. ChartBeat', 'Conf. ChartBeat', 'manage_options',
        'network_vcanly_chartbeat', function () {
            $addMedioPage = new VocentoAnalyticsAdminChartBeat();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. Crazy Egg', 'Conf. Crazy Egg', 'manage_options',
        'network_vcanly_crazyegg', function () {
            $addMedioPage = new VocentoAnalyticsAdminCrazyEgg();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. Wordpress', 'Conf. Wordpress', 'manage_options',
        'network_vcanly_wordpress', function () {
            $addMedioPage = new VocentoAnalyticsAdminWordpress();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. SoftClick', 'Conf. SoftClick', 'manage_options',
        'network_vcanly_softclick', function () {
            $addMedioPage = new VocentoAnalyticsAdminSoftClick();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. Inspectlet', 'Conf. Inspectlet', 'manage_options',
        'network_vcanly_inspectlet', function () {
            $addMedioPage = new VocentoAnalyticsAdminInspectlet();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. Krux', 'Conf. Krux', 'manage_options', 'network_vcanly_krux',
        function () {
            $addMedioPage = new VocentoAnalyticsAdminKrux();
            $addMedioPage->render_page();
        });
        
    add_submenu_page('network_vcanly_overview', 'Conf. DTM', 'Conf. DTM', 'manage_options', 'network_vcanly_dtm',
        function () {
            $addMedioPage = new VocentoAnalyticsAdminDTM();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Conf. Opciones', 'Conf. Opciones', 'manage_options',
        'network_vcanly_options', function () {
            $addMedioPage = new VocentoAnalyticsAdminOptions();
            $addMedioPage->render_page();
        });

    add_submenu_page('network_vcanly_overview', 'Ayuda', 'Ayuda', 'manage_options', 'network_vcanly_help', function () {
        $addMedioPage = new VocentoAnalyticsAdminHelp();
        $addMedioPage->render_page();
    });

    add_submenu_page('network_vcanly_overview', 'Import/Export', 'Import/Export', 'manage_options',
        'network_vcanly_import', function () {
            $addMedioPage = new VocentoAnalyticsAdminImportExport();
            $addMedioPage->render_page();
        });
}

function vcanly_compose_admin_menu_local()
{

    add_menu_page("Vocento Analytics", "Vocento Analytics", 'manage_options', "site_vcanly_overview");


    add_submenu_page('site_vcanly_overview', 'Estado', 'Estado', 'manage_options', 'site_vcanly_overview', function () {
        $listMedioPage = new VocentoAnalyticsAdminOverview();
        $listMedioPage->render_page();
    });

    if (!is_multisite() || !vcanly_is_active_network()) {
        add_submenu_page('site_vcanly_overview', 'Conf. Opciones', 'Conf. Opciones', 'manage_options',
            'site_vcanly_options', function () {
                $listMedioPage = new VocentoAnalyticsAdminOptions();
                $listMedioPage->render_page();
            });


    }


    add_submenu_page('site_vcanly_overview', 'Conf. comScore', 'Conf. comScore', 'manage_options',
        'site_vcanly_comscore', function () {
            $listMedioPage = new VocentoAnalyticsAdminComScore();
            $listMedioPage->render_page();
        });

    add_submenu_page('site_vcanly_overview', 'Conf. GA', 'Conf. GA', 'manage_options', 'site_vcanly_ga', function () {
        $listMedioPage = new VocentoAnalyticsAdminGA();
        $listMedioPage->render_page();
    });
    add_submenu_page('site_vcanly_overview', 'Conf. GTM', 'Conf. GTM', 'manage_options', 'site_vcanly_gtm',
        function () {
            $listMedioPage = new VocentoAnalyticsAdminGTM();
            $listMedioPage->render_page();
        });
    add_submenu_page('site_vcanly_overview', 'Conf. AMP', 'Conf. AMP', 'manage_options', 'site_vcanly_amp',
        function () {
            $listMedioPage = new VocentoAnalyticsAdminAMP();
            $listMedioPage->render_page();
        });
    add_submenu_page('site_vcanly_overview', 'Conf. ChartBeat', 'Conf. ChartBeat', 'manage_options',
        'site_vcanly_chartbeat', function () {
            $listMedioPage = new VocentoAnalyticsAdminChartBeat();
            $listMedioPage->render_page();
        });
    add_submenu_page('site_vcanly_overview', 'Conf. Crazy Egg', 'Conf. Crazy Egg', 'manage_options',
        'site_vcanly_crazyegg', function () {
            $listMedioPage = new VocentoAnalyticsAdminCrazyEgg();
            $listMedioPage->render_page();
        });
    add_submenu_page('site_vcanly_overview', 'Conf. Wordpress', 'Conf. Wordpress', 'manage_options',
        'site_vcanly_wordpress', function () {
            $listMedioPage = new VocentoAnalyticsAdminWordpress();
            $listMedioPage->render_page();
        });

    add_submenu_page('site_vcanly_overview', 'Conf. SoftClick', 'Conf. SoftClick', 'manage_options',
        'site_vcanly_softclick', function () {
            $listMedioPage = new VocentoAnalyticsAdminSoftClick();
            $listMedioPage->render_page();
        });

    add_submenu_page('site_vcanly_overview', 'Conf. Inspectlet', 'Conf. Inspectlet', 'manage_options',
        'site_vcanly_inspectlet', function () {
            $listMedioPage = new VocentoAnalyticsAdminInspectlet();
            $listMedioPage->render_page();
        });

    add_submenu_page('site_vcanly_overview', 'Conf. Krux', 'Conf. Krux', 'manage_options', 'site_vcanly_krux',
        function () {
            $listMedioPage = new VocentoAnalyticsAdminKrux();
            $listMedioPage->render_page();
        });
        
    add_submenu_page('site_vcanly_overview', 'Conf. DTM', 'Conf. DTM', 'manage_options', 'site_vcanly_dtm',
        function () {
            $listMedioPage = new VocentoAnalyticsAdminDTM();
            $listMedioPage->render_page();
        });

    add_submenu_page('site_vcanly_overview', 'Ayuda', 'Ayuda', 'manage_options', 'site_vcanly_help', function () {
        $listMedioPage = new VocentoAnalyticsAdminHelp();
        $listMedioPage->render_page();
    });


    if (!is_multisite() || !vcanly_is_active_network()) {
        add_submenu_page('site_vcanly_overview', 'Import/Export', 'Import/Export', 'manage_options',
            'network_vcanly_import', function () {
                $listMedioPage = new VocentoAnalyticsAdminImportExport();
                $listMedioPage->render_page();
            });


    }

}

function vcanly_enqueue($hook)
{


    wp_enqueue_script('ajax-script-ui-analytics',
        plugins_url('../assets/js/jquery-ui.min.js', __FILE__),
        array('jquery')
    );

    wp_enqueue_script('ajax-script-switchable-analytics',
        plugins_url('../assets/js/switchable.min.js', __FILE__)
    );

    wp_enqueue_script('datatables-analytics',
        plugins_url('../assets/js/jquery.dataTables.min.js', __FILE__),
        array('jquery')
    );

    wp_enqueue_style('vocentovcanly_analytics_switchable', plugins_url('../assets/css/switchable.css', __FILE__));
    wp_enqueue_style('vocentovcanly_analytics_styles', plugins_url('../assets/css/style.css', __FILE__));
    wp_enqueue_style('vocentovcanly_analytics_tables',
        plugins_url('../assets/css/jquery.dataTables.min.css', __FILE__));


}

function vcanly_composeFormParams($page, $action, $id)
{
    $params = array(
        'page' => $page
    );

    if ($action) {
        $params['action'] = $action;
    }

    if ($id) {
        $params['vcanly_opt_id'] = $id;
    }

    return $params;
}

function vcanly_composeFormParamsForSoftClick($page, $action, $id)
{
    $params = array(
        'page' => $page
    );

    if ($action) {
        $params['action'] = $action;
    }

    if ($id) {
        $params['vcanly_softclick_id'] = $id;
    }

    return $params;
}

function vcanly_is_active_network()
{
    if (!is_multisite()) {
        return false;
    }

    return is_plugin_active_for_network('vocento-analytics/vocento-analytics.php');
}

function vcanly_get_post_types_mu()
{

    $postTypes = array();
    if (is_plugin_active_for_network('vocento-analytics/vocento-analytics.php')) {

        foreach (wp_get_sites() as $site) {
            $postTypes = array_merge($postTypes, vcanly_get_post_types_by_blog($site['blog_id']));
        }

    } else {

        $postTypes = vcanly_get_post_types_by_blog(get_current_blog_id());
    }
    $postTypes = array_unique($postTypes);
    return $postTypes;
}

function vcanly_custom_analytics_config_overview()
{

    $blogs = array();
    if (is_plugin_active_for_network('vocento-analytics/vocento-analytics.php')) {

        foreach (wp_get_sites() as $site) {

            $blogid = $site['blog_id'];
            $options = get_blog_option($blogid, 'vocentovcanly_options');
            $options_analytics = $options['vocentovcanly_options_analytics'];

            if (is_array($options_analytics)) {
                foreach ($options_analytics as $opt) {
                    if ($opt === false) {
                        array_push($blogs, $site);
                        break;
                    }
                }
            }

        }

    }

    return $blogs;
}

function vcanly_get_post_types_by_blog($blogid = null)
{

    global $wpdb;

    $args = array(
        'public' => true,
    );

    $output = 'names'; // names or objects
    if ($blogid && function_exists('switch_to_blog')) {
        switch_to_blog($blogid);
    }

    $pts = get_post_types($args, $output);

    if ($blogid && function_exists('switch_to_blog')) {
        restore_current_blog();
    }


    if (is_multisite()) {
        $select_query = "SELECT DISTINCT " . $wpdb->base_prefix . $blogid . "_posts.post_type from " . $wpdb->base_prefix . $blogid . "_posts";
    } else {
        $select_query = "SELECT DISTINCT " . $wpdb->base_prefix . "posts.post_type from " . $wpdb->base_prefix . "posts";
    }


    $search = $wpdb->get_results($select_query, OBJECT);

    foreach ($search as $pt) {
        array_push($pts, $pt->post_type);
    }

    return array_unique($pts);
}

function vcanly_fill_meta_config(&$container)
{

    $meta_page = vcanly_get_config_meta();

    if ($meta_page) {

        $container->comScore->data['voc_se'] = $meta_page['voc_se'];
        $container->comScore->data['voc_tn'] = $meta_page['voc_tn'];
        $container->comScore->data['voc_tc'] = $meta_page['voc_tc'];

        return true;
    }

    return false;

}

function vcanly_get_wp_home_config(&$container)
{

    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = false;

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['home_ms']) && vcanly_has_wp_config_for_section($config['home_ms'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['home_ms']['se'];
        $container->comScore->data['voc_tn'] = $config['home_ms']['tn'];
        $container->comScore->data['voc_tc'] = $config['home_ms']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['home_ms']) || !vcanly_has_wp_config_for_section($config['home_ms'])) && !$has_meta_config) {


        //miramos si tiene configuracion de blog
        if (is_array($config['home_blog'])) {

            vcanly_get_wp_home_blog_config($container);

        } else {
            //Si no tiene nada aplicamos default
            $container->comScore->data['voc_se'] = 'portada';
            $container->comScore->data['voc_tn'] = 'portada';
            $container->comScore->data['voc_tc'] = 'portada';
            $container->comScore->data['voc_ho'] = '';
            $container->comScore->data['voc_au'] = '';
        }
    }
}

function vcanly_get_wp_home_blog_config(&$container)
{
    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = false;

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['home_blog']) && vcanly_has_wp_config_for_section($config['home_blog'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['home_blog']['se'];
        $container->comScore->data['voc_tn'] = $config['home_blog']['tn'];
        $container->comScore->data['voc_tc'] = $config['home_blog']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['home_blog']) || !vcanly_has_wp_config_for_section($config['home_blog'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = 'portada';
        $container->comScore->data['voc_tn'] = 'portada';
        $container->comScore->data['voc_ho'] = '';
        $container->comScore->data['voc_tc'] = 'portada';
        $container->comScore->data['voc_au'] = '';
        $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];
    }
}

function vcanly_get_wp_category_config(&$container)
{
    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = false;

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['category']) && vcanly_has_wp_config_for_section($config['category'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['category']['se'];
        $container->comScore->data['voc_tn'] = $config['category']['tn'];
        $container->comScore->data['voc_tc'] = $config['category']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['category']) || !vcanly_has_wp_config_for_section($config['category'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = get_category(get_query_var('cat'))->slug;
        $container->comScore->data['voc_tn'] = 'portadilla';
        $container->comScore->data['voc_tc'] = 'portada';
        $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];

    }
    //comunes
    $container->comScore->data['voc_fep'] = date('YmdHis');
    $container->comScore->data['voc_fem'] = date('YmdHis');

}

function vcanly_get_wp_tag_config(&$container)
{

    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = false;

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['tag']) && vcanly_has_wp_config_for_section($config['tag'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['tag']['se'];
        $container->comScore->data['voc_tn'] = $config['tag']['tn'];
        $container->comScore->data['voc_tc'] = $config['tag']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['tag']) || !vcanly_has_wp_config_for_section($config['tag'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = strtolower(get_query_var('tag'));
        $container->comScore->data['voc_tn'] = 'portadilla';
        $container->comScore->data['voc_tc'] = 'portada';
        $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];

    }
    //comunes
    $container->comScore->data['voc_fep'] = date('YmdHis');
    $container->comScore->data['voc_fem'] = date('YmdHis');

}

function vcanly_get_wp_archive_config(&$container)
{

    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = false;

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['archive']) && vcanly_has_wp_config_for_section($config['archive'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['archive']['se'];
        $container->comScore->data['voc_tn'] = $config['archive']['tn'];
        $container->comScore->data['voc_tc'] = $config['archive']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['archive']) || !vcanly_has_wp_config_for_section($config['archive'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = '';
        $container->comScore->data['voc_tn'] = 'portadilla';
        $container->comScore->data['voc_tc'] = 'portada';
        $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];

    }

    //comunes
    $container->comScore->data['voc_fep'] = date('YmdHis');
    $container->comScore->data['voc_fem'] = date('YmdHis');

}

function vcanly_get_wp_search_config(&$container)
{

    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = false;

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['search']) && vcanly_has_wp_config_for_section($config['search'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['search']['se'];
        $container->comScore->data['voc_tn'] = $config['search']['tn'];
        $container->comScore->data['voc_tc'] = $config['search']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['search']) || !vcanly_has_wp_config_for_section($config['search'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = 'buscador';
        $container->comScore->data['voc_tn'] = 'buscador';
        $container->comScore->data['voc_tc'] = 'buscador';
        $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];

    }

    //comunes
    $container->comScore->data['voc_fep'] = date('YmdHis');
    $container->comScore->data['voc_fem'] = date('YmdHis');


}

function vcanly_get_wp_author_config(&$container)
{

    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = false;

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['author']) && vcanly_has_wp_config_for_section($config['author'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['author']['se'];
        $container->comScore->data['voc_tn'] = $config['author']['tn'];
        $container->comScore->data['voc_tc'] = $config['author']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['author']) || !vcanly_has_wp_config_for_section($config['author'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = get_user_by('slug', get_query_var('author_name'))->user_nicename;
        $container->comScore->data['voc_tn'] = 'portadilla';
        $container->comScore->data['voc_tc'] = 'portada';
        $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];
    }

    //comunes
    $container->comScore->data['voc_fep'] = date('YmdHis');
    $container->comScore->data['voc_fem'] = date('YmdHis');

}

function vcanly_get_wp_404_config(&$container)
{

    //Configuracion de WP admin
    $config = vcanly_get_wp_config();

    //Rellenamos con meta de página
    $has_meta_config = vcanly_fill_meta_config($container);

    //Si no tiene config de página y tiene config de wp admin...
    if ((is_array($config['404']) && vcanly_has_wp_config_for_section($config['404'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = $config['404']['se'];
        $container->comScore->data['voc_tn'] = $config['404']['tn'];
        $container->comScore->data['voc_tc'] = $config['404']['tc'];
    }

    //Si no tiene config de wp ni metas de página, metemos los valores por defecto
    if ((!is_array($config['404']) || !vcanly_has_wp_config_for_section($config['404'])) && !$has_meta_config) {

        $container->comScore->data['voc_se'] = '404';
        $container->comScore->data['voc_tn'] = '404';
        $container->comScore->data['voc_tc'] = 'error';
        $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];

    }

    //comunes
    $container->comScore->data['voc_fep'] = date('YmdHis');
    $container->comScore->data['voc_fem'] = date('YmdHis');
    $container->comScore->data['ns_http_status'] = '404';


}

function vcanly_has_wp_config_for_section($arr_section)
{
    //Si están vacios o nulos...
    return (($arr_section['tc'] != "" || $arr_section['tn'] != "" || $arr_section['se'] != "") && ($arr_section['tc'] != null || $arr_section['tn'] != null || $arr_section['se'] != null));

}

function vcanly_get_wp_config()
{
    //Set options
    $options_network = get_site_option('vocentovcanly_options');
    $options = get_option('vocentovcanly_options');
    $config = $options['vocentovcanly_options_analytics_config_wp'];

    //Si est� marcado para la red o no existe config local, usamos la cofig de red
    if (!is_array($config) || $config['vcanly_wp_use_network'] || $config['vcanly_wp_use_network'] == null) {
        $config = $options_network['vocentovcanly_options_analytics_config_wp'];
    }

    return $config;
}

function vcanly_get_config_meta()
{
    global $post;

    $value = get_post_meta($post->ID, 'vcanly_page_config', true);

    return $value;
}

function vcanly_get_page_template_name($template)
{

    return "vcanly-tmp-" . sanitize_key(str_replace(".php", "", $template));
}

function isVocentoTopicsActive()
{

    return is_plugin_active('vocento-topics/vocento-topics.php');

}

function vcanly_is_amp_endpoint()
{
    if (function_exists('is_amp_endpoint')) {
        return is_amp_endpoint();
    }
    return false;
}

?>