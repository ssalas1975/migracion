

	<?php //if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<p>','</p>' ); } ?>

	<!-- START: section -->
    <section class="section section-values">
        <article class="wrap">
            <h1 class="title"><?php echo get_field('intro',false,false) ?></h1>
            <h2 class="subtitle">Nuestros valores</h2>
            <div class="txt">
                <p><?php echo get_field('valores',false,false) ?></p>
            </div>
        </article>
    </section>
    <!-- END: section -->
