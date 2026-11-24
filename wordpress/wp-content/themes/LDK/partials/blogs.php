<div class="col-c-module blogs">
    <div class="title">Los blogs mas visitados</div>
    <div class="blogs-content">
       <?php 
           
       $medios=ldk_get_blogs_visits();
       foreach($medios as $medio){
       ?>
        <div class="blog-item">

    <?php
           $image = get_field('logo',$medio['medio']->ID,false);
           $size = 'logo-icon';
           //echo wp_get_attachment_image( $image, $size );

    ?>

           <amp-img src="<?php echo ldk_get_medio_meta('logo',true,$medio['medio']->ID) ?>" alt="Logo" class="logomedio" height="65" width="65"></amp-img>
            <a href="<?php echo ldk_get_medio_meta('url',true,$medio['medio']->ID) ?>"><?php echo $medio['medio']->post_title?></a>
       </div>
       <?php
       }
       
       ?>
    </div>
</div>