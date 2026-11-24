<?php


get_header();

?>
<div class="main-col">
    <section class="amp-ldk-tags">

	    <?php 

       if ( have_posts() ) : 

			
			while ( have_posts() ) : the_post();

            set_query_var( 'post_home', get_post() );
            
            get_template_part('partials/post','list');

            endwhile;

       else :

           echo "No hay resultados";

       endif;
        ?>
	

    </section>
</div>

<?php 
    
get_sidebar();


get_footer();

?>