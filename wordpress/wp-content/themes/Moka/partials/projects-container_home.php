<div class="contenedor-proyectos" id="proyectos-same-home">
		<?php
		$args = array(
         'post_type' => 'proyecto', // enter your custom post type
         'orderby' => 'date',
         'order' => 'DESC',
         'posts_per_page'=> 4, // overrides posts per page in theme settings
         );
        $loop = new WP_Query( $args );
        if( $loop->have_posts() ):
            $project_index=1;
            while( $loop->have_posts() ):
                $loop->the_post(); global $post;
				set_query_var( 'project_index', $project_index );
			    get_template_part('partials/project','list');
				$project_index++;
		   endwhile;
		endif;
		?>
	</div>