
<?php 	

query_posts(
	array(
		'post_type' => 'audio',
		'category_name' => 'una habitacion propia',
		'posts_per_page' => 10,
		'paged' => get_query_var('paged')
		)
	);

	$classTitle = 'u-color-cyan';
	set_query_var( 'classtitle', $classTitle);
	get_template_part('template-parts/content', 'audios');

?>

