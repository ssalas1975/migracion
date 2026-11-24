 <?php $utm_array=ldk_get_utm(); ?>
 <div class="empresa-item">
        <div class="empresa-image">
		
         <a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true) . $empresa['url_detalle'].$utm_array['query']?>"><amp-img src="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true).'/'.ldk_get_current_medio_meta('id_medio',true) .  $empresa['logo']?>" alt="<?php echo $empresa['titulo']?>" class="post-image" height="90" width="140" layout="responsive"></amp-img></a> 
        </div>
        <div class="empresa-info">
            <div class="area"><a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true).ldk_get_current_medio_meta('default_provincia',true) . $empresa['categoria']['slug'].$utm_array['query']?>"><?php echo $empresa['categoria']['nombre']?></a></div>
             <div class="name">
			   <?php if($empresa['url_detalle']){?>
			 <a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true) . $empresa['url_detalle'].$utm_array['query']?>">
			  <?php }?>
			 <?php echo $empresa['titulo']?>
			   <?php if($empresa['url_detalle']){?>
			 </a>
			  <?php }?>
			 </div>
             <div class="info">
			
                 <span class="phone">
				   <?php if($empresa['telefono']){?>
					<a href="tel:<?php echo $empresa['telefono']?>"><i class="fa fa-phone"></i><?php echo $empresa['telefono']?></a>
				  <?php }?>
				 </span>
				
				  <?php if($empresa['href']){?>
                 <span class="web"><a target="_blank" href="<?php echo  $empresa['href'].$utm_array['query']?>">web</a></span>
				  <?php }?>
             </div>
        </div>

    </div>
