<?php
/* function to create sitemap.xml file in root directory of site  */
add_action('parse_request', 'parse_request_sitemap', 2, 1);
function parse_request_sitemap($request){
    switch($request->request){
        case 'sitemap.incremental.xml':

            $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";


            // $actual_link = "http://pre-contentfactory.abc.es";

            if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
                $host = str_replace("pre-contentfactory", "www", $actual_link);
            }elseif ($_ENV["ENVIRON"] == "PRO") {
                $host = str_replace("content-factory","www",$actual_link);
            }


            $re = '/(.*?)\./m';
            preg_match_all($re, $actual_link, $matches, PREG_SET_ORDER, 0);
    
            // $actual_link = str_replace("content-factory","www", $actual_link);
            
            $args = array(
                'post_type' => 'post',
                'numberposts' => -1,
                'tax_query' => array(
                            array(
                                'taxonomy' => 'Medios',
                                'field' => 'slug',
                                'terms' => $matches[1][1]
                            )
                        )
                );
            $postsForSitemap = get_posts($args);

            header('Content-Type: text/xml');
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach($postsForSitemap as $post) {
                $permalink = str_replace('https://ms-brandedcontent.srv.vocento.in', $host . '/contentfactory/post', get_permalink($post->ID));
                $permalink = str_replace('http://', 'https://', $permalink);
         
                setup_postdata($post);
                $postdate = explode(" ", $post->post_modified);
                $sitemap .= '<url>'.
                '<loc>'. $permalink .'</loc>'.
                '<priority>1</priority>'.
                '<lastmod>'. $postdate[0] .'</lastmod>'.
                '<changefreq>daily</changefreq>'.
                '</url>'."\n";
            }
            $sitemap .= '</urlset>';
            echo $sitemap;
            exit();
            break;
        case 'sitemap.xml':

            break;
    }

}