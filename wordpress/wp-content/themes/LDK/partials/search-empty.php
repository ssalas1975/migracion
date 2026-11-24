<?php

?>

<div class="empty-search"> No hemos encontrado resultados para <b><?php echo get_search_query()?></b>, pero tal vez te puede interesar:</div>

<?php

    foreach(ldk_get_home_posts(false,0) as $post_home){
		
					set_query_var( 'post_home', $post_home );
                    get_template_part('partials/post','list');
    }

?>