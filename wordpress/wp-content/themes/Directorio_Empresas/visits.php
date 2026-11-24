<?php  

//Como es una versión vieja no podemos usar el rest API


define('WP_USE_THEMES', false); 
require('../../../wp-blog-header.php');

/*
//Update +1 de visitas del medio. 
$medio=ldk_get_medio();
if($medio){
   $count= get_option('medio_visit_'.$medio->ID);
   update_option('medio_visit_'.$medio->ID,$count+1);
}*/


//Update +1 de visitas del medio. 

if(isset($_GET["pid"]) && $_GET["pid"]){
	
	$post_id=$_GET["pid"];
	$count= get_post_meta($post_id,'post_visit',true);
	update_post_meta( $post_id, 'post_visit', $count+1 );
}

status_header(200);
die();

?>