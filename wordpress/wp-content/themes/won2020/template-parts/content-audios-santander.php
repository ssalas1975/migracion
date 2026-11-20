
<?php 	

query_posts(
	array(
		'post_type' => 'audio',
		'category_name' => 'santander womennow',
		'posts_per_page' => 10,
		'paged' => get_query_var('paged')
		)
	);
	
$title = "Santander WomenNow";
$classTitle = 'u-color-black';
set_query_var( 'classtitle', $classTitle);
get_template_part('template-parts/content', 'audios');

?>
