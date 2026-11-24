

	
<div class="section section-head">
    <p class="title">OBSERVATORIO</p>
	<span class="navi"></span>
</div>

	<!-- START: section -->
	<section class="section section-list-date">
	    <div class="wrap">
	    	<?php if (have_posts()):?>

				<?php while(have_posts()): the_post(); ?>

					<?php $year=get_the_date('Y'); if($prev_year==NULL||$prev_year!=$year): ?>
						<h2 class="item-title"><span><?php echo get_the_date('Y') ?></span></h2>
					<?php endif; $prev_year=$year; ?>

					<style> span.hover:hover{ border-bottom: 0px solid rgba(0,0,0,0) !important; }</style>

					 <a href="<?php the_permalink(); ?>" class="item">
                        <p class="date"><?php echo get_the_date('d.m') ?></p>
                        <div class="txt">
                            <?php $total=150; $contenido=$total-strlen(get_the_title()); ?>
                            <h3 class="title"><?php the_title() ?></h3>
                            <p class="subtitle"><?php echo excerpt(get_field('contenido',false,false),$contenido) ?></p>
                            <span class="btn hover"  >Leer más</span>
                            <!-- mas info -->
                            <div class="masinfo">
								<?php if( get_field('link')!=''): ?>
	                     			<span class="btn-suscribe hover" title="">Suscribe</span>
	                     		<?php elseif( get_field('pdf')!=''): ?>
	                     			<span class="btn-pdf hover" title="">PDF</span>
	                     		<?php endif; ?>
							</div>
							<!-- mas info -->
                        </div>
						
                    </a>

	        	<?php endwhile; ?>

			<?php endif; ?>

	    </div>
	</section>
	<!-- END: section -->
