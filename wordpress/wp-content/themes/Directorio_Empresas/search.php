<?php


get_header();

?>
<div class="main-col">
    <section class="amp-ldk-search">

	    <?php 

       if ( have_posts() ) : 

			
			while ( have_posts() ) : the_post();

            set_query_var( 'post_home', get_post() );
            
            get_template_part('partials/post','list');

            endwhile;

       else :

            get_template_part('partials/search','empty');

       endif;

       //Paginacion
       
       get_template_part('partials/post','pagination');

        ?>
	

    </section>
</div>

<?php 
    
get_sidebar();


get_footer();

?>