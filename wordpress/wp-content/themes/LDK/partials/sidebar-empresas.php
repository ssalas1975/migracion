 <?php $utm_array=ldk_get_utm(); ?>
<div class="sidebar-empresas col-c-module">
	
    <div class="title">
	<a class="a-width" href="<?php  echo ldk_get_current_medio_meta('url_localdigitalkit',true).$utm_array['query']?>">
       	<?php if(ldk_get_current_medio_meta('logo_white',true)){ ?>
		  <div class="empresas-logo-header"><amp-img src="<?php echo ldk_get_current_medio_meta('logo_white') ?>" alt="Logo" height="20" width="130" layout="responsive"></amp-img></div>
		<?php } ?>
		</a>
    </div>
	
    <div class="content">

       <?php 
      $empresas=ldk_load_empresas_krammer_suscripciones();

       if($empresas){
           $index=0;
           foreach($empresas as $empresa){

               if(!array_key_exists('titulo',$empresa))
                   continue;

               $index++;
               
               set_query_var( 'empresa', $empresa );

               get_template_part('partials/sidebar','empresa');

               if($index==10)
                   break;

           }
       }

       ?>

    </div>
  
     <div class="sidebar-empresas-footer">
       <a href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true).ldk_get_current_medio_meta('default_provincia',true).$utm_array['query']?>">Entra en nuestro directorio de empresas <i class="fa fa-angle-double-right"></i></a>
    </div>
   
</div>