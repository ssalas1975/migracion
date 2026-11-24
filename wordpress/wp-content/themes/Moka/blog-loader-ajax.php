<?php
header('Content-Type: application/json');

define('WP_USE_THEMES', false);
require('../../../wp-blog-header.php');

$page=$_GET["page"];
$ipp=4;


    $posts_pages=moka_get_posts_paged($ipp,$page);


    $related_array= array();
    $related_array['items']= array();
    $related_array['items']= array();
    $related_array['items']['posts']= array();
    $related_array['items']['posts']['cola']= array();
    $related_array['items']['posts']['colb']= array();
    $related_array['items']['more_pages']= $posts_pages['more_pages'];
    $odd=false;
    foreach($posts_pages['posts'] as $realted){
        $item=array();
        $item['post_title']=$realted->post_title;
        $item['post_content']=strip_tags($realted->post_content);
        $item['post_excerpt']=moka_limit_text(strip_tags(apply_filters('the_excerpt', $realted->post_excerpt)),24);
		if(!$item['post_excerpt'])
		  $item['post_excerpt']=moka_limit_text(strip_tags($realted->post_content),24);
	    $item['post_excerpt']= $item['post_excerpt']."...";
        $item['permalink']=get_the_permalink($realted->ID);
        $item['image']=moka_get_image_detalle($realted->ID);
        $item['date']=get_the_date( '',$realted->ID );
        if(!$odd)
            array_push( $related_array['items']['posts']['cola'],$item);
        else
            array_push( $related_array['items']['posts']['colb'],$item);

        $odd=!$odd;
    }


    status_header(200);

    echo json_encode($related_array);

die();

?>