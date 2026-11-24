 <?php $utm_array=ldk_get_utm(); ?>
<div class="carrusel-empresas">
     <div class="module-title"><a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true).ldk_get_current_medio_meta('default_provincia',true).$utm_array['query']?>">Directorio de empresas</a></div>
    <div class="empresa-item-medio">
		<a class="logo-a-link" href="<?php echo ldk_get_current_medio_meta('url_localdigitalkit',true).$utm_array['query']?>">
        <div class="logo-item">
			<?php if(ldk_get_current_medio_meta('logo_white',true)){ ?>
		  <div class="carousel-logo-header"><amp-img src="<?php echo ldk_get_current_medio_meta('logo_white') ?>" alt="Logo" height="20" width="130" layout="responsive"></amp-img></div>
		<?php } ?>
		</div>
		</a>
		<div class="txt"><a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_localdigitalkit',true).$utm_array['query']?>">Hacemos que internet trabaje para ti</a></div>
       <div class="phone-item"><a href="tel:<?php echo ldk_get_current_medio_meta('telefono',true)?>"><?php echo ldk_get_current_medio_meta('telefono',true)?></a></div>
		
    </div>
	
    <amp-carousel height="200"
        layout="fixed-height"
        type="slides" class="carrusel-empresas-amp" loop>

        <div>
          <?php 
              $empresas=ldk_load_empresas_krammer_suscripciones();
                if($empresas){
                    //$index = 1;
                  //echo count($empresas);
                    foreach($empresas as $empresa){


                        if(!array_key_exists('titulo',$empresa)) 
                            continue;

                        $index++;
                        if($index===6) {
                            echo "</div><div>";
                            
                            set_query_var( 'empresa', $empresa );

                            get_template_part('partials/empresa','item');
                             $index =0;
                        } else {
                          set_query_var( 'empresa', $empresa );

                          get_template_part('partials/empresa','item');

                        }
                    }
                    
                }
          
           
          ?>

        </div>
    </amp-carousel>
</div>


<div class="carrusel-empresas mobile">
     <div class="module-title"><a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true).ldk_get_current_medio_meta('default_provincia',true).$utm_array['query']?>">Directorio de empresas</a></div>
    <div class="empresa-item-medio">
	<div class="car-mobile-left">
		<a class="logo-a-link" href="<?php echo ldk_get_current_medio_meta('url_localdigitalkit',true).$utm_array['query']?>">
        <div class="logo-item">
			<?php if(ldk_get_current_medio_meta('logo_white',true)){ ?>
		  <div class="carousel-logo-header"><amp-img src="<?php echo ldk_get_current_medio_meta('logo_white') ?>" alt="Logo" height="20" width="130" layout="responsive"></amp-img></div>
		<?php } ?>
		</div>
		</a>
		</div>
		<div class="car-mobile-right">
		<div class="txt"><a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_localdigitalkit',true).$utm_array['query']?>">Hacemos que internet trabaje para ti</a></div>
       <div class="phone-item"><a href="tel:<?php echo ldk_get_current_medio_meta('telefono',true)?>"><?php echo ldk_get_current_medio_meta('telefono',true)?></a></div>
		</div>
    </div>

	<div class="html-carousel">
       <div class="html-carousel-flow">
          <?php 
              $empresas=ldk_load_empresas_krammer_suscripciones();
                if($empresas){
                    
                  
                    foreach($empresas as $empresa){


                        if(!array_key_exists('titulo',$empresa))
                            continue;

                        $index++;
                          set_query_var( 'empresa', $empresa );

                          get_template_part('partials/empresa','item');

                        }
                    
                }
           
          ?>
 </div>
      </div>
 
</div>