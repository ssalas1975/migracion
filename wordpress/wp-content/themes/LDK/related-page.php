<?php  
header('Content-Type: application/json');


//Como es una versión vieja no podemos usar el rest API

define('WP_USE_THEMES', false); 
require('../../../wp-blog-header.php');

//Update +1 de visitas del medio. 
$medio=ldk_get_medio();
$postID=$_GET["ID"];
$page=$_GET["page"];
$ipp=8;

if($medio){
    
    $related=array();
    
    //$related['items'] = get_posts( array('post_type' => 'post', 'post_status'=> 'publish','offset'=>($page*$ipp), 'category__in' => wp_get_post_categories($postID), 'numberposts' =>$ipp, 'post__not_in' => array($post->ID) ) );
     //$related['items'] = get_posts( array('post_type' => 'post', 'post_status'=> 'publish','offset'=>($page*$ipp), 'numberposts' =>$ipp, 'post__not_in' => array($postID) ) );
	
	
	//$args=array('post_type' => 'post', 'post_status'=> 'publish','offset'=>($page*$ipp), 'numberposts' =>$ipp, 'post__not_in' => array($postID) );
	
	
	$related['items'] = ldk_get_related($page,$postID,$ipp);
	
	
    $related_array= array();

    $related_array['items']= array();
    $related_array['items'][0]= array();
    $related_array['items'][0]['posts']= array();
    $related_array['items'][0]['pages']= array();

    foreach($related['items'] as $realted){
        $item=array();
        $first_category=ldk_get_first_category_object($realted);
        $item['post_title']=$realted->post_title;
        $item['post_content']=strip_tags($realted->post_content);
        $item['post_excerpt']=substr(strip_tags(apply_filters('the_excerpt', $realted->post_excerpt)),0,300);
		if(! $item['post_excerpt'])
		  $item['post_excerpt']=substr(strip_tags($realted->post_content),0,300);
        $item['permalink']=get_the_permalink($realted->ID);
        $item['image']=ldk_get_thumbnail_post($realted,'post-list');
        $item['category_name']=$first_category->name;
        $item['category_link']=get_category_link($first_category);
        $item['created_since']=ldk_get_hours_sice($realted->post_date);
        array_push($related_array['items'][0]['posts'],$item);
    }

    //pages
    //$query = new WP_Query(array( 'cat' => $first_category->term_id ));
    $total=$query->found_posts;
    $total_pages=0;
	
    if($total)
        $total_pages= intval($total/$ipp);

    //From page to page..
    $start_page=$page-2;
    if($start_page<0)
        $start_page=0;

    $end_page=$start_page+4;

    if($end_page>$total_pages)
    {
        $end_page=$total_pages;
        $start_page=$end_page-4;
        if($start_page<0)
            $start_page=0;
    }

    //page structure
    $related_array['items'][0]['pages']['prev']=0;
    $related_array['items'][0]['pages']['next']=($total_pages>$page+1)?$page+1:false;
    $related_array['items'][0]['pages']['pagenumber']=array();
    
    for($i=$start_page;$i<=$end_page;$i++){
        array_push($related_array['items'][0]['pages']['pagenumber'],array('page_index'=>$i,'text'=>($i+1),'class'=>($i==$page)?"active":""));
    }

    
    status_header(200);
    
    echo json_encode($related_array);
}
die();

?>