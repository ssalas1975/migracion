<?php get_header(); ?>
	    <!-- START: content-->
				<main class="content">
					<div class="o-container">
                        <aside  class="o-section">
                            <div class="c-add c-add-d980x250 u-flex">
                                <div class="c-add__creatividad">
                                    <div class="sticky-footer voc-advertising voc-adver-header hidden-lg hidden-md hidden-sm fixed-position" data-voc-advertising-close="" style="display: block;">
                                        <div class="voc-advertising__close">
                                            <div class="voc-advertising__close-btn" data-voc-adver-close-btn="">
                                                <i class="fa cb-times cierremegabanner"><b>x</b></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- START: c-add -->
                                    <div id="megabanner-id" class="megabanner">
                                         <script type="vocento/script">
                                            googletag.cmd.push(function() { googletag.display('megabanner-id'); });
                                        </script>
                                    </div>
                                    <!-- END: c-add -->
                                </div>
                            </div>
                        </aside>
						<!-- home -->
						<?php if ($post->post_name=='home'): get_template_part('template-parts/content', 'home'); ?>
						<!-- videos -->
						<?php elseif ($post->post_name=='te-interesa'):  get_template_part('template-parts/content', 'noticias'); ?>
						<!-- money & career -->
						<?php elseif ($post->post_name=='inversiones-finanzas'):  get_template_part('template-parts/content', 'money'); ?>
						<!-- videos -->
						<?php elseif ($post->post_name=='videos'):  get_template_part('template-parts/content', 'videos'); ?>
						<!-- podcasts -->
						<?php elseif ($post->post_name==='podcasts'):  get_template_part('template-parts/content', 'audios-landing'); ?>
						<?php elseif ($post->post_name==='santander-womennow'):  get_template_part('template-parts/content', 'audios-santander'); ?>
						<?php elseif ($post->post_name==='una-habitacion-propia'):  get_template_part('template-parts/content', 'audios-habitacion'); ?>
						<!-- newsletter -->
						<?php elseif ($post->post_name=='newsletter'):  get_template_part('template-parts/content', 'newsletter'); ?>

						<!-- resto -->
						<?php else: ?>
							<!-- START: o-section resto -->
							<section class="o-section">

												<?php if ($post->post_name!='aviso-legal'&&$post->post_name!='politica-de-privacidad'&&$post->post_name!='politica-de-cookies'): ?>

														<!-- contenido -->
                                                        <?php if($post->post_name=='the-summit'){?>
                                                        <p class="c-title-small u-color-cyan">Reset</p>
                                                        <?}else{?><p class="c-title-small u-color-cyan"><?php the_title() ?></p>
                                                        <?php } ?>
														<?php if ($post->post_name=='quienes-somos'): ?>
															<a href="<?php echo get_permalink(get_page_by_title('Nuestros Valores')); ?>" title="Ir a Nuestros valores" aria-label="Ir a Nuestros valores" class="btn-section" >Nuestros valores</a>
														<?php elseif ($post->post_name=='nuestros-valores'): ?>
															<a href="<?php echo get_permalink(get_page_by_title('Quiénes somos')); ?>" title="Ir a Quiénes somos" aria-label="Ir a Quiénes somos" class="btn-section" >Quiénes somos</a>
														<?php elseif ($post->post_name=='the-summit'): ?>
															<a href="https://2019.womennow.es/" title="Ir al evento Summit 2019" aria-label="Ir al evento Summit 2019" target="_blank" class="btn-section btn-section-1" ><span class="u-hidden-xs">Summit</span> 2019</a>
															<a href="https://2020.womennow.es/" title="Ir al evento Summit 2020" aria-label="Ir al evento Summit 2020" target="_blank" class="btn-section btn-section-2" ><span class="u-hidden-xs">Summit</span> 2020</a>
															<a href="https://reset.womennow.es?data-voc-vtm-id=download-file&data-voc-vtm-info=reset" title="Ir al evento Reset" aria-label="Ir al evento Reset" target="_blank" class="btn-section btn-section-3" ><span class="u-hidden-xs"></span>Reset</a>
                                                    <?php endif; ?>
														<article class="c-body c-body-center">
															<h1><?php echo get_field('intro_pagina');?></h1>
															<?php if ($post->post_name=='the-summit'): ?>
																	<!-- START: o-slider -->
																	<div class="o-slider" data-type="slide" data-slide-autoplay="true">
																					<div class="o-slider__wrap ">
																								<?php if (have_rows('slide')): while (have_rows('slide')) : the_row();?>
																										<a href="#" title="Leer <?php echo (str_replace('"', '\'', strip_tags($post->post_title))); ?>" aria-label="Leer <?php echo (str_replace('"', '\'', strip_tags($post->post_title))); ?>" class="c-claim">
																															<picture class="c-claim__thumb">
																																			<!-- <source srcset="assets/img/news/slide-2.jpg" media="(min-width: 768px)"> -->
																																			<img src="<?php echo get_site_url().get_sub_field('imagen')['url'] ?>" alt="slide <?php echo (str_replace('"', '\'', strip_tags($post->post_title))); ?>">
																															</picture>
																										</a>
																							<?php  endwhile; endif; ?>
																					</div>
																	</div>
																	<!-- END: o-slider -->
															<?php endif; ?>
															<?php echo get_the_content(); ?>
													</article>

												<?php else: ?>

													<!-- legales -->
													<article class="card card-static">
															<header class="top">
																			<h1 class="title"><?php the_title(); ?></h1>
															</header>
															<div class="body"><?php the_content() ?></div>
												 </article>

												<?php endif; ?>

							</section>
							<!-- END: o-section -->
						<?php endif; ?>

					</div>
    </main>
    <!-- END: content-->

<?php if ($post->post_name=='newsletter'):  '';

?>
<?php else:?>
	<?php if($_ENV["ENVIRON"] == "PRE" || $_ENV["ENVIRON"] == "DES"){ ?>
		<!--<div class='widget-prospect <?php echo $post->post_name=='newsletter' ? "" : "wrapperNewsletter hide" ?>' data-widget-id=<?php echo $post->post_name=='newsletter' ? "5f8711304aaf961d9708e46f" : "5f804b2ae83d491a2352b146" ?>> </div>-->
    <?php } elseif($_ENV["ENVIRON"] == "PRO") { ?>
      <!--<div class='widget-prospect <?php echo $post->post_name=='newsletter' ? "" : "wrapperNewsletter hide" ?>' data-widget-id=<?php echo $post->post_name=='newsletter' ? "5f8efbedac778664a737e08c" : "5f8efb90ac778664a737e08b" ?>> </div>-->
    <?php } ?><?php endif; ?>

<?php get_footer(); ?>



