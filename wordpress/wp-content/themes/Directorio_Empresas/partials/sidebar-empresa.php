   <?php $utm_array=ldk_get_utm(); ?>
  <div class="empresa-sidebar">
             <div class="top-info">
               <?php 
                   $img_empresa = ldk_get_current_medio_meta('url_directorio_empresas',true).'/'.ldk_get_current_medio_meta('id_medio',true) .  $empresa['logo'];
                   $info_img = getimagesize($img_empresa);
                   $ratio = $info_img[0]/$info_img[1];
                   if( $ratio > 1) {
                        $width = 50;
                        $height = 50/$ratio;
                    }
                    else {
                        $width = 50*$ratio;
                        $height = 50;
                    }
               ?>
					<div class="image info-empresa-logo">
					<a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true) . $empresa['url_detalle'].$utm_array['query']?>">
					<amp-img src="<?php echo $img_empresa; ?>" alt="<?php echo $empresa['titulo']?>" class="post-image" height="<?php echo $height; ?>" width="<?php echo $width; ?>"></amp-img></a></div>
					<div class="info-empresa-data">
						<div class="service"><a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true).ldk_get_current_medio_meta('default_provincia',true) . $empresa['categoria']['slug'].$utm_array['query']?>"><?php echo $empresa['categoria']['nombre']?></a></div>
						<div class="name">
						
						 <a target="_blank" href="<?php echo ldk_get_current_medio_meta('url_directorio_empresas',true) . $empresa['url_detalle'].$utm_array['query']?>">
						
						<?php echo $empresa['titulo']?>
						
						</a>
						
						</div>
						<div class="city"><?php echo $empresa['direccion']['localidad']?></div>
					    <?php if($empresa['telefono']){?>
					   <div class="phone"><a href="tel:<?php echo $empresa['telefono']?>"><i class="fa fa-phone"></i> <?php echo $empresa['telefono']?></a></div>
					     <?php if($empresa['href']){?>
							<div class="web"><a target="_blank" href="<?php echo $empresa['href'].$utm_array['query']?>">web</a></div>
						<?php }?>
						<?php }?>
				    </div>
             </div>
             <div class="bottom-info">
                
             </div>
        </div>