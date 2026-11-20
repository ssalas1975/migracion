
	<?php get_header(); //echo $post->post_name;?>

		<!-- START: content-->
        <main class="content">
           <div class="container">
               <section class="section section-error">
                   <div class="wrap">
                       <!-- START: card-error -->
                       <article class="card card-error">
                            <img class="thumb" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon/404.svg" alt="404">
                           <div class="txt">
                               <p class="title">No hemos encontrado la página que buscas.</p>
                               <p class="subtitle">Para volver a la home pincha <a href="<?php echo esc_url(home_url('/')); ?>" title="Ir a la home WomenNow" aria-label="Ir a la Home WomenNow">aquí.</a></p>
                           </div>
                       </article>
                       <!-- END: card-error -->
                   </div>
               </section>

           </div>
       </main>
       <!-- END: content-->
	
	<?php get_footer();
