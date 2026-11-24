<?php
/**
* Template Name: Página genérica
 */

get_header(); ?>

<!--titular-->
<div class="degradado titularBlog">
	<h1 class="widget-title"><?php the_title(); ?></h1>
</div>

<div class="container clear">    
      
	<div class="row">
		

    <section id="primary" class="content-area col-sm-12">
    
    
		<main id="main" class="site-main" role="main">

			<div class="contenido-blanco">
			<?php
			 while ( have_posts() ) : the_post();

			 	get_template_part( 'template-parts/content', 'page' );

			 	// If comments are open or we have at least one comment, load up the comment template.
			 	if ( comments_open() || get_comments_number() ) :
			 		comments_template();
			 	endif;

			 endwhile;// End of the loop.
			?>
            </div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
