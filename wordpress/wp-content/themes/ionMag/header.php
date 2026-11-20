<!doctype html >
<!--[if IE 8]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta charset="<?php bloginfo('charset');?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php
    wp_head(); /** we hook up in wp_booster @see td_wp_booster_functions::hook_wp_head */
    ?>
    
    <?php
    switch_to_blog(get_current_blog_id());
    $nameofblog = get_bloginfo('name');
    //$nameofblog = str_replace("-"," ",iconv('UTF-8','ASCII//TRANSLIT',$nameofblog));
    
    function cleanString($text)
    {
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/:/'			=>	 '',
            '/ /'			=>   ' ',
            '/_/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
            '/[“”«»„]/u'    =>   ' ', // Double quote
            '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
    
    $nameofblog = cleanString($nameofblog);
    $nameofblog = str_replace(" ", "-", $nameofblog);
    $valueUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if (strpos($valueUrl, 'contacto') !== false) {
        $nameofblog = "contacto";
    }

    $author_id = $post->post_author;
    $value_voc_au = get_the_author_meta('user_nicename', $author_id);

    $value_voc_s1 = strtolower($nameofblog);
    $value_voc_s2 = "";
    $value_voc_s3 = "";
    $value_voc_s4 = "";
    $value_voc_tn = "blog";
    $value_voc_tc = "portadilla";
    $value_voc_tcnew = "portadilla";
    $value_voc_fep = "";
    $value_voc_fem = "";
    $value_voc_top = "";


    if (is_front_page()) {
        $value_voc_s1 = "";
    }

    if (is_category()) {
        $value_voc_s2 = "categorias";
        $category = get_the_category();
        $value_voc_s1 = $category[0]->cat_name;
        $value_voc_tn = $category[0]->cat_name;
    }

    if (is_author()) {
        $value_voc_s2 = "autor";
        $value_voc_s3 = get_the_author_meta('user_nicename');
    }

    if (is_date()) {
        $value_voc_s2 = "archivo";
        $value_voc_s3 = get_the_time('Y').'/'.get_the_time('m');
    }

    if (is_singular('post')) {
        $postname= cleanString($post->post_name);
        $value_voc_tn = $postname;
        $value_voc_tc = "portadilla";
        $value_voc_tcnew = "portadilla";
        $value_voc_fep = gmdate("Ymdshm", get_post_time());
        $value_voc_fem = gmdate("Ymdshm", get_post_time());
        
        global $post;
        $tags = wp_get_post_tags($post->ID);
        $tagCount = 0;
        $max = count($tags);
        foreach ($tags as $tag) {
            $value_voc_top .= $tag->slug;
            if ($tagCount >= 0 && $tagCount < ($max-1)) {
                $value_voc_top.= ",";
            }
            $tagCount ++;
        }
    }

    $valueUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if (strpos($valueUrl, 'tag') !== false) {
        $value_voc_s1 = "tag";
        //$value_voc_s2 = cets_get_topic_name($topicid);
        $path = parse_url($valueUrl);
        $tema = explode("/", $path['path']);
        array_pop($tema);
        $value_voc_s2 = end($tema);
        $value_voc_tn = end($tema);
    }

    if (is_tag()) {
        $value_voc_s2 = "tag";
        $value_voc_s3 = single_tag_title("", false);
    }
    ?>
    <script>
    var vot_ate = 'opinion';
    var voc_se = 'blogs';

    var voc_s1 = '<?php echo $value_voc_s1; ?>';
    var voc_s2 = '<?php echo $value_voc_s2; ?>';
    var voc_s3 = '<?php echo $value_voc_s3; ?>';
    var voc_s4 = '<?php echo $value_voc_s4; ?>';
    var voc_tn = '<?php echo $value_voc_tn; ?>';
    var voc_ho = '';
    var voc_tc = '<?php echo $value_voc_tc; ?>';
    var voc_tcnew = '<?php echo $value_voc_tcnew; ?>';

    var cov_ed = '';
    var voc_au = '<?php echo $value_voc_au; ?>';
    var voc_fep = '<?php echo $value_voc_fep; ?>';
    var voc_fem = '<?php echo $value_voc_fem; ?>';
    var voc_or = 'RL';
    var voc_pr = '1';
    var voc_pl = '';
    var voc_pu = 'wordpress';
    var voc_ate = 'opinion';
    var voc_adp = '';
    var voc_adb = '';
    var voc_top = '<?php echo $value_voc_top; ?>';
    var voc_idi = 'es';
    </script>

<!-- ADOBE ANALYTICS -->
<script src="//static.vocstatic.com/voonto2/latest/voonto.js " data-cmp="true"></script>
<script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-property-id="9da47caf865b2cd698a833b04020423690ee500d" data-analytics-id="ppee_pro" data-legacy-krux="ruqshtgtu" data-legacy-comscore="7715761"></script>
<script>TagManager.ready.then(function() {TagManager.set({}).track();});</script>
<!-- FIN ADOBE ANALYTICS -->

    <!-- Start vocento GTM -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-WXGJV36');</script>
    <!-- End Vocento GTM -->

</head>

<body <?php body_class() ?> itemscope="itemscope" itemtype="<?php echo td_global::$http_or_https?>://schema.org/WebPage">
<!-- Start vocento GTM + DTM B v2.2.52 + comscore -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WXGJV36" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocpidecita,voces/1/G.4--S/283625431?pageName=&g=<?php  echo wp_get_canonical_url(); ?>" width="1" height="1" border="0" /></noscript>

<noscript><p><img src="//sb.scorecardresearch.com/p?c1=2&amp;c2=7715761&amp;ns_site=pidecita" height="1" width="1" alt="*" /></p></noscript>
<!-- End vocento GTM + DTM B + comscore -->
<?php /* scroll to top */?>
<div class="td-scroll-up"><i class="td-icon-menu-up"></i></div>

<?php locate_template('parts/menu-mobile.php', true);?>
<?php locate_template('parts/search.php', true);?>


<div id="td-outer-wrap" class="td-theme-wrap<?php if (td_util::get_option('tds_search_placement') == 'top_bar') {
        echo ' td-search-top-bar';
    } ?>">
    <?php //this is closing in the footer.php file?>

<?php
/*
 * loads the header template set in Theme Panel -> Header area
 * the template files are located in ../parts/header
 */
td_api_header_style::_helper_show_header();

do_action('td_wp_booster_after_header'); //used by unique articles
