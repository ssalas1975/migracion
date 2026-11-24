<?php

/**
 * VocentoAnalyticsFrontManager short summary.
 *
 * VocentoAnalyticsFrontManager description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VocentoAnalyticsFrontManager
{
    private $container;

    public function __construct()
    {
        add_action('wp_head', array($this, 'setConfigContainer'), 1);
        add_action('wp_footer', array($this, 'setFooterScripts'), 1);
        add_action('vocento_analytics_after_body_start', array($this, 'bodyTagStart'), 100);
        add_action('vocento_analytics_script', array($this, 'VocentoWP_Analytics_Script'), 100);
        add_action('vocento_analytics_before_body_end', array($this, 'bodyTagEnd'), 100);

        //Plugin AMP
        add_action('amp_post_template_head', function () {
            if (!$this->container) {
                $this->setConfigContainer();
            }
            if ($this->container->amp->enable) {
                $this->loadAMP_Header_JS();
            }
        });
    }

    public function registerHeadScripts()
    {
        global $post;
        $protocol = 'https://';
        $canonicalURL = str_replace("http://", $protocol, get_post_meta($post->ID, 'canonical-url', true));
         $pos = strpos($canonicalURL, 'contentfactory/post/eslaliga/');
        if ($pos === false) {
            if ($canonicalURL!="") {
                //Obligatorios
                $this->registerNormatScript();
                $this->registerAdBlockScript();
                $this->registerConfigScript();
                $this->loadCommon_Header_JS();
                $this->registerDigitalContentKruxScript();
                $this->registerHeadScriptsCommon();
            }
        }else{
            //Obligatorios
            // $this->registerNormatScript();
            $this->registerAdBlockScript();
            $this->registerConfigScript();
            // $this->loadCommon_Header_JS();
            $this->registerDigitalContentKruxScript();
            $this->registerHeadScriptsCommon();
        }


        // //Obligatorios
        // // $this->registerNormatScript();
        // $this->registerAdBlockScript();
        // $this->registerConfigScript();
        // // $this->loadCommon_Header_JS();
        // $this->registerDigitalContentKruxScript();
        // $this->registerHeadScriptsCommon();
    }

    public function registerHeadScriptsCommon()
    {
        //Si es sun endpoint AMP si está configurado para solo incluir AMP salimos sin incluir el resto de redes
        if (vcanly_is_amp_endpoint()) {
            return;
        }
        //Si chartbeat está activo..
        if ($this->container->chartbeat->enable) {
            $this->loadChartBeat_Header_JS();
        }
        //Si GA está activo..
        if ($this->container->ga->enable) {
            $this->loadGA_Header_JS();
        }
        //Si Crazyegg está activo..
        if ($this->container->crazyegg->enable) {
            $this->loadCrazyEgg_Header_JS();
        }

        //Si está activo y configurado para incluirse en todas las paginas, o la página tiene el meta de AMP se mete.
        if ($this->container->amp->enable) {
            $meta = vcanly_get_config_meta();
            //Solo lo registramos si no es AMP End point ya que lo registra el handler del hook del plugin
            /*if (!vcanly_is_amp_endpoint() && (($meta && $meta["amp"]) || in_array(vcanly_get_page_template_name(get_page_template_slug()),
                        $this->container->amp->templates))
            ) {
                $this->loadAMP_Header_JS();
            }*/
            /*NO TIENE SENTIDO ESTE BLOQUE DE ARRIBA*/
        }

        //Inspectlet activo...
        if ($this->container->inspectlet->enable) {
            $this->loadInspectlet_Header_JS();
        }


        //Krux activo...
        if ($this->container->krux->enable) {
            $this->loadKrux_Header_JS();
        }
    }


    public function bodyTagStart()
    {
        if ($this->container->amp->enable) {

            //si está activo y configurado para incluirse en todas las paginas, o la página tiene el meta de AMP se mete.
            $meta = vcanly_get_config_meta();
            if (vcanly_is_amp_endpoint() || $this->container->amp->includeAll || ($meta && $meta["amp"]) || in_array(
                vcanly_get_page_template_name(get_page_template_slug()),
                $this->container->amp->templates
            )
            ) {
                $this->loadAMP_Start_JS();
            }
        }

        //Si e sun endpoint AMP si está configurado para solo incluir AMP salimos sin incluir el resto de redes
        if (vcanly_is_amp_endpoint()) {
            return;
        }

        if ($this->container->comScore->enable) {
            $this->loadCommScoreInlineJS();
        }

        if ($this->container->gtm->enable) {
            $this->loadGTM_Start_JS();
        }

        if ($this->container->krux->enable) {
            $this->loadKrux_Start_JS();
        }
    }

    public function VocentoWP_Analytics_Script()
    {
        if ($this->container->gtm->enable) {
            $this->load_VocentoWP_Analytics();
        }
    }


    public function bodyTagEnd()
    {

        //Si e sun endpoint AMP si está configurado para solo incluir AMP salimos sin incluir el resto de redes
        if (vcanly_is_amp_endpoint()) {
            return;
        }

        if ($this->container->chartbeat->enable) {
            $this->loadChartBeat_Footer_JS();
        }
    }


    public function setFooterScripts()
    {
        if (!function_exists('is_amp_endpoint') || (function_exists('is_amp_endpoint') && !is_amp_endpoint())) {
            if ($this->container->softclick->enable) {
                $this->loadSoftClick_Footer_JS();
            }
        }
    }

    public function loadCommScoreInlineJS()
    {
        $html = apply_filters(
            'vcanly_comscore_body_start',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/comscore/body_start.html')
        );

        $vars = $this->container->comScore->data;

        $params = '';
        foreach ($vars as $k => $v) {
            $params .= $k . '=' . sanitize_title($v) . '&amp;';
        }

        $noscript = '<noscript><p><img src="//b.scorecardresearch.com/p?' . $params . 'c1=2&amp;c2=7715761" height="1" width="1" alt="*"></p></noscript>';

        $html = str_replace("##no_script##", $noscript, $html);

        echo $html;
    }

    public function loadGTM_Start_JS()
    {
        $html = apply_filters(
            'vcanly_gtm_body_start',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/gtm/body_start.html')
        );

        $html = str_replace("##gtmid##", $this->container->gtm->data['ID'], $html);
        echo $html;
    }

    public function load_VocentoWP_Analytics()
    {
        $html = apply_filters(
            'vcanly_vocentoWP_Analytics',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/gtm/vocentoWP_Analytics.html')
        );

        echo $html;
    }

    public function loadAMP_Start_JS()
    {
        $html="";
        
        if ($this->container->comScore->enable) {
            $html_comscore = apply_filters('vcanly_amp_body_start_comscore', file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/amp/comscore.html'));
            $html .=str_replace("##extra##", json_encode(apply_filters('vcanly_pre_amp_comscore', $this->container->comScore->data)), $html_comscore);
        }
            
        if ($this->container->chartbeat->enable) {
            $html_chartbeat = apply_filters('vcanly_amp_body_start_chartbeat', file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/amp/chartbeat.html'));
            $html .=str_replace("##ampchartbeat##", json_encode(apply_filters('vcanly_pre_amp_chartbeat', $this->container->chartbeat->data)), $html_chartbeat);
        }
            
        if ($this->container->gtm->enable) {
            $html_gtm = apply_filters('vcanly_amp_body_start_gtm', file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/amp/gtm.html'));
            $html .=str_replace("##gtmid##", apply_filters('vcanly_pre_amp_gtm', $this->container->gtm->data['ID']), $html_gtm);
        }
            
            
        echo $html;
    }

    public function loadKrux_Start_JS()
    {
        $html = apply_filters(
            'vcanly_krux_body_start',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/krux/body_start.html')
        );

        echo $html;
    }

    public function loadSoftClick_Footer_JS()
    {
        $html = apply_filters(
            'vcanly_softclick_footer',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/softclick/footer.html')
        );

        echo $html;
    }

    public function loadChartBeat_Header_JS()
    {
        $html = apply_filters(
            'vcanly_chartbeat_head',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/chartbeat/head.html')
        );
        echo $html;
    }

    public function loadGA_Header_JS()
    {
        $html = apply_filters(
            'vcanly_ga_head',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/ga/head.html')
        );
        echo $html;
    }

    public function loadAMP_Header_JS()
    {
        $html = apply_filters(
            'vcanly_amp_head',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/amp/head.html')
        );
        echo $html;
    }

    public function loadCommon_Header_JS()
    {
        $html = apply_filters(
            'vcanly_common_head',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/common/head.html')
        );
        echo $html;
    }


    public function loadInspectlet_Header_JS()
    {
        $html = apply_filters(
            'vcanly_inspectlet_head',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/inspectlet/head.html')
        );
        echo $html;
    }

    public function loadKrux_Header_JS()
    {
        $html = apply_filters(
            'vcanly_krux_head',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/krux/head.html')
        );

        $html = str_replace("##controltag##", $this->container->krux->data['control_tag'], $html);

        echo $html;
    }

    public function loadCrazyEgg_Header_JS()
    {
        $html = apply_filters(
            'vcanly_crazyegg_head',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/crazyegg/head.html')
        );
        echo $html;
    }

    public function loadChartBeat_Footer_JS()
    {
        $html = apply_filters(
            'vcanly_chartbeat_body_end',
            file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/chartbeat/body_end.html')
        );
        echo $html;
    }


    public function registerAdBlockScript()
    {

        //AdBlock y normat (SIEMPRE)
        wp_register_script('vcanly-adblock-checker', plugins_url('../../assets/js/adframe.js', __FILE__));
        wp_print_scripts(array('vcanly-adblock-checker'));
    }

    public function registerDigitalContentKruxScript()
    {

        //AdBlock y normat (SIEMPRE)
        wp_register_script('vcanly-krux-checker', plugins_url('../../assets/js/adframe.krux.js', __FILE__));
        wp_print_scripts(array('vcanly-krux-checker'));
    }

    public function registerNormatScript()
    {
        wp_register_script('vcanly-normat', plugins_url('../../assets/js/normat.js', __FILE__));

        wp_localize_script('vcanly-normat', 'VocentoWP_Analytics', $this->container);
        wp_print_scripts(array('vcanly-normat'));
    }

    public function registerConfigScript()
    {
        wp_register_script('vcanly-config', plugins_url('../../assets/js/VocentoWP_Analytics.js', __FILE__));
        wp_print_scripts(array('vcanly-config'));
    }

    public function setSectionSE($container, $section_se, $index, $post)
    {
        return ($container->comScore->data[$section_se]) ? $container->comScore->data[$section_se] : $this->getVocCategoryIndex(
            $index,
            $post
        );
    }

    public function fillConfigContainer(&$container)
    {
        global $post;


        //status
        $container->comScore->data['ns_http_status'] = http_response_code();


        //home
        if (is_home()) {

            //si es home de red
            if (function_exists('is_main_site') && is_main_site()) {
                vcanly_get_wp_home_config($container);
            } else {
                vcanly_get_wp_home_blog_config($container);
            }
        }

        //Entrada
        if (is_single()) {
            //Comunes
            $container->comScore->data['voc_au'] = apply_filters(
                'vcanly_author_filter',
                sanitize_title(get_the_author_meta('user_nicename', $post->post_author))
            );
            $container->comScore->data['voc_fep'] = get_the_time('YmdHis');
            $container->comScore->data['voc_fem'] = get_the_modified_time('YmdHis');
            $container->comScore->data['voc_ho'] = '';

            //Tipo de voc_ho
            switch ($container->comScore->ho_type) {
                case VocentoAnalyticsHOType::TAGS:
                    $container->comScore->data['voc_ho'] = implode(';', $this->getPostTags($post));
                    break;
                case VocentoAnalyticsHOType::TOPICS:
                    $container->comScore->data['voc_ho'] = implode(';', $this->getPostTopics($post));
                    break;
            }

            $container->gtm->tags = $this->getPostTags($post);

            //Miramos si tiene analytics personalizados
            $post_custom_config = $this->getPostCustomConfig();
            $general = maybe_unserialize(get_option('vocentovcanly_options'));

            if ($post_custom_config) {
                $container->comScore->data['voc_tn'] = ($post_custom_config['voc_tn']) ? $post_custom_config['voc_tn'] : $post->post_name;
                //$container->comScore->data['voc_tc'] = ($post_custom_config['voc_tc'])?$post_custom_config['voc_tc']:get_post_type();
                //$container->comScore->data['voc_tc'] = ($post_custom_config['voc_tc']) ? $post_custom_config['voc_tc'] : "";
                if (is_home() || is_front_page()) {
                    $container->comScore->data['voc_tc'] = ($post_custom_config['voc_tc']) ? $post_custom_config['voc_tc'] : $general["vocentovcanly_options_analytics_config_wp"]["home_blog"]["tc"];
                } else {
                    $container->comScore->data['voc_tc'] = ($post_custom_config['voc_tc']) ? $post_custom_config['voc_tc'] : $general["vocentovcanly_options_analytics_config_wp"]["category"]["tc"];
                }
                $container->comScore->data['voc_se'] = ($post_custom_config['voc_se']) ? $post_custom_config['voc_se'] : $this->setSectionSE(
                    $container,
                    'voc_se',
                    0,
                    $post
                );
                $container->comScore->data['voc_s1'] = ($post_custom_config['voc_s1']) ? $post_custom_config['voc_s1'] : $this->setSectionSE(
                    $container,
                    'voc_s1',
                    1,
                    $post
                );
                $container->comScore->data['voc_s2'] = ($post_custom_config['voc_s2']) ? $post_custom_config['voc_s2'] : $this->setSectionSE(
                    $container,
                    'voc_s2',
                    2,
                    $post
                );
                $container->comScore->data['voc_s3'] = ($post_custom_config['voc_s3']) ? $post_custom_config['voc_s3'] : $this->setSectionSE(
                    $container,
                    'voc_s3',
                    3,
                    $post
                );
                $container->comScore->data['voc_s4'] = ($post_custom_config['voc_s4']) ? $post_custom_config['voc_s4'] : $this->setSectionSE(
                    $container,
                    'voc_s4',
                    4,
                    $post
                );
                $container->comScore->data['voc_name'] = ($post_custom_config['name']) ? $post_custom_config['name'] : '';

                //si se oclutan las fechas desde metabox
                if ($post_custom_config['voc_hide_dates']) {
                    $container->comScore->data['voc_fep'] = '';
                    $container->comScore->data['voc_fem'] = '';
                }
            } else {
                $container->comScore->data['voc_se'] = $this->setSectionSE($container, 'voc_se', 0, $post);
                $container->comScore->data['voc_s1'] = $this->setSectionSE($container, 'voc_s1', 1, $post);
                $container->comScore->data['voc_s2'] = $this->setSectionSE($container, 'voc_s2', 2, $post);
                $container->comScore->data['voc_s3'] = $this->setSectionSE($container, 'voc_s3', 3, $post);
                $container->comScore->data['voc_s4'] = $this->setSectionSE($container, 'voc_s4', 4, $post);
                $container->comScore->data['voc_tn'] = $post->post_name;
                $container->comScore->data['voc_tc'] = get_post_type();
                $container->comScore->data['voc_name'] = $container->comScore->data['voc_se'] . '.' . $container->comScore->data['voc_tn'];
            }
        }


        //Page
        if (is_page()) {

            //Comunes
            //Comunes de la página
            $container->comScore->data['voc_ho'] = '';
            $container->comScore->data['voc_au'] = '';
            $container->comScore->data['voc_fep'] = date('YmdHis');
            $container->comScore->data['voc_fem'] = date('YmdHis');

            //Tipo de voc_ho
            switch ($container->comScore->ho_type) {
                case VocentoAnalyticsHOType::TAGS:
                    $container->comScore->data['voc_ho'] = $this->getPostTags($post);
                    break;
                case VocentoAnalyticsHOType::TOPICS:
                    $container->comScore->data['voc_ho'] = $this->getPostTopics($post);
                    break;
            }

            $container->gtm->tags = $this->getPostTags($post);


            //Miramos si tiene analytics personalizados
            $page_custom_config = $this->getPostCustomConfig();

            //si existe custom  config
            if ($page_custom_config) {
                $container->comScore->data['voc_tn'] = ($page_custom_config['voc_tn']) ? $page_custom_config['voc_tn'] : $post->post_name;
                //$container->comScore->data['voc_tc'] = ($page_custom_config['voc_tc'])?$page_custom_config['voc_tc']:get_post_type();
                $container->comScore->data['voc_tc'] = ($page_custom_config['voc_tc']) ? $page_custom_config['voc_tc'] : "";
                $container->comScore->data['voc_se'] = ($page_custom_config['voc_se']) ? $page_custom_config['voc_se'] : $this->setSectionSE(
                    $container,
                    'voc_se',
                    0,
                    $post
                );
                $container->comScore->data['voc_s1'] = ($page_custom_config['voc_s1']) ? $page_custom_config['voc_s1'] : $this->setSectionSE(
                    $container,
                    'voc_s1',
                    1,
                    $post
                );
                $container->comScore->data['voc_s2'] = ($page_custom_config['voc_s2']) ? $page_custom_config['voc_s2'] : $this->setSectionSE(
                    $container,
                    'voc_s2',
                    2,
                    $post
                );
                $container->comScore->data['voc_s3'] = ($page_custom_config['voc_s3']) ? $page_custom_config['voc_s3'] : $this->setSectionSE(
                    $container,
                    'voc_s3',
                    3,
                    $post
                );
                $container->comScore->data['voc_s4'] = ($page_custom_config['voc_s4']) ? $page_custom_config['voc_s4'] : $this->setSectionSE(
                    $container,
                    'voc_s4',
                    4,
                    $post
                );
                $container->comScore->data['voc_name'] = ($page_custom_config['name']) ? $page_custom_config['name'] : '';

                //si se oclutan las fechas desde metabox
                if ($page_custom_config['voc_hide_dates']) {
                    $container->comScore->data['voc_fep'] = '';
                    $container->comScore->data['voc_fem'] = '';
                }
            } else {
                $container->comScore->data['voc_se'] = $this->setSectionSE($container, 'voc_se', 0, $post);
                $container->comScore->data['voc_s1'] = $this->setSectionSE($container, 'voc_s1', 1, $post);
                $container->comScore->data['voc_s2'] = $this->setSectionSE($container, 'voc_s2', 2, $post);
                $container->comScore->data['voc_s3'] = $this->setSectionSE($container, 'voc_s3', 3, $post);
                $container->comScore->data['voc_s4'] = $this->setSectionSE($container, 'voc_s4', 4, $post);
                $container->comScore->data['voc_tn'] = $post->post_name;
                //$container->comScore->data['voc_tc'] = get_post_type();
                $container->comScore->data['voc_tc'] = "";
            }
        }

        //Categoría
        if (is_category()) {
            vcanly_get_wp_category_config($container);
        }

        if (is_tag()) {
            vcanly_get_wp_tag_config($container);
        }


        if (is_archive()) {
            vcanly_get_wp_archive_config($container);
        }

        if (is_search()) {
            vcanly_get_wp_search_config($container);
        }


        if (is_404()) {
            vcanly_get_wp_404_config($container);
        }

        if (is_author()) {
            vcanly_get_wp_author_config($container);
        }


        //SoftClick
        $this->setSoftClickEvents();


        apply_filters('vocento_analytics_on_fill_container', $this->container);
    }

    public function getVocCategoryIndex($category_index, $post)
    {
        $categories = get_the_category($post->ID);
        return ((is_array($categories) && count($categories) > $category_index) ? ($categories[$category_index]->slug) : '');
    }

    public function setConfigContainer()
    {
        $this->container = VocentoAnalyticsContainer::getFrontContainer();

        $this->fillConfigContainer($this->container);
        
        if (!function_exists('is_amp_endpoint') || (function_exists('is_amp_endpoint') && !is_amp_endpoint())) {
            $this->registerHeadScripts();
        }

        return $this->container;
    }


    public function getPostTags($post)
    {
        $tags = array();
        $args = array(
            'fields' => 'all',

        );
        $post_tags = wp_get_post_tags($post->ID, $args);
        foreach ($post_tags as $tag) {
            array_push($tags, $tag->slug);
        }

        return $tags;
    }

    public function getPostTopics($post)
    {
        if (isVocentoTopicsActive()) {
            $arr = array();
            if (isVocentoTopicsActive()) {
                return vocentotps_get_topics_post($post->ID);
            }
            return $arr;
        } else {
            return $this->getPostTags($post);
        }
    }

    public function getPostCustomConfig()
    {

        //TODO: Crear todo el metabox y recuperar aqui sus datos de página

        global $post;

        $pagecustom_config = get_post_meta($post->ID, 'vcanly_page_config', true);

        return $pagecustom_config;
    }

    public function setSoftClickEvents()
    {
        $arrEvents = array();

        if ($this->container->softclick->enable) {


            //todos
            $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage("all"));

            if (is_home()) {
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage("wp-home"));
            }

            if (is_category()) {
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage("wp-category"));
            }

            if (is_tag()) {
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage("wp-tag"));
            }

            if (is_author()) {
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage("wp-author"));
            }

            if (is_search()) {
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage("wp-search"));
            }

            if (is_404()) {
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage("wp-404"));
            }

            if (is_single()) {
                $pagename = 'pt-' . get_post_type();
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage($pagename));
            }

            if (is_page()) {
                $page_template = 'tmp-' . vcanly_get_page_template_name(get_page_template());
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage($page_template));

                //Páginas individuales
                $page_name = 'page-' . get_post()->post_name;
                $arrEvents = array_merge($arrEvents, $this->getSoftClickForPage($page_name));
            }
        }

        //Reemplazamos para la salida solo con los eventos que pasan filtro
        $this->container->softclick->data = array_filter($arrEvents, function ($k) {
            return count($k) > 0;
        });
    }

    public function getSoftClickForPage($page)
    {
        $arr = array();
        foreach ($this->container->softclick->data as $sfconfig) {
            if ($sfconfig['page'] == $page) {
                //quitamos ID para no ver en JS
                unset($sfconfig['id']);
                array_push($arr, $sfconfig);
            }
        }

        return $arr;
    }
}
