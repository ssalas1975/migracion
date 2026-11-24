				<div class="row texto-receta">
                    <div class="col-md-3">
                    	<h2><?php  _e( 'Ingredientes', 'wp-bootstrap-starter' ); ?></h2>
                        <?php echo wp_specialchars_decode( get_field('ingredientes')); ?>
                    </div>
                    <div class="col-md-9">
                    	<h2><?php  _e( 'Preparación', 'wp-bootstrap-starter' ); ?></h2>
                        <?php echo wp_specialchars_decode( get_field('preparacion')); ?>
                        
                        <?php
                        $contenidoDelCampo = get_post_meta($post->ID, 'presentacion', true);
						if($contenidoDelCampo != ''){ ?>
							<h2><?php  _e( 'Presentación', 'wp-bootstrap-starter' ); ?></h2>
                        	<?php  echo wp_specialchars_decode( get_field('presentacion'));
						}
                    	?>
                    </div>
                    
                </div>