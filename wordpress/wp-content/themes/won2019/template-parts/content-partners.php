	
	<?php 
        $tipo_partner = get_terms( array( 'taxonomy' => 'tipo_partner', 'hide_empty' => false) );
        for ($i=0; $i<count($tipo_partner); $i++) {
                $tipo_partner[$i]->orden = get_field('orden', 'tipo_partner'.'_'.$tipo_partner[$i]->term_id);
                $tipo_partner[$i]->cantidad = get_field('cantidad', 'tipo_partner'.'_'.$tipo_partner[$i]->term_id);
        }
        usort($tipo_partner, 'sortTaxonomy');

        function sortTaxonomy($a, $b) {
                if ($a->orden == $b->orden) {return 0;} 
                elseif ($a->orden < $b->orden) { return -1;} 
                else { return 1;}
        }
    ?>

    <section class="cabecera no_marge_b container">

	    <span class="color_title color"></span>
	    <span class="title">WON</span>

		<span class="cont-img">
			<p class="claim no_padd_l">/Partners</p>
			<img class="fondo" src="<?php echo get_the_post_thumbnail_url();?>">
		</span>

		<span class="color_title bottom color"></span>
	    
	</section>

	<section class="partners container">

                <h1 class="title">
                        <?php echo get_field('titulo') ?><span class="low"><?php echo get_field('subtitulo') ?></span>
                </h1>

                <?php  foreach ($tipo_partner as $tipo): ?>
                        <?php 
                                $args = Array(
                                        'posts_per_page'=> -1,
                                        'post_type' => 'partner',
                                        'tax_query' => array( array(
                                                'taxonomy'  => 'tipo_partner',
                                                'field'     => 'name',
                                                'terms'     => $tipo->name,
                                                'operator'  => 'IN'
                                        ),),
                                        'order' => 'ASC',
                                        'meta_key' => 'orden',
                                        'orderby' => 'meta_value',

                                );
                                $partners = new WP_Query($args); 
                        ?> 
                        <?php if($partners->have_posts()): ?>
                                <h2><?php echo $tipo->name ?></h2>
                                <ul class="partner<?php echo $tipo->cantidad ?>">
                                        <?php while ( $partners->have_posts() ) :$partners->the_post();?>   
                                                 <a href="<?php echo get_field('url');?>"><li style="background-image: url('<?php echo get_the_post_thumbnail_url();?>')"></li></a>
                                                <?php if($tipo->cantidad==1): ?>
                                                        <li class="text"><span class="vcenter"><?php echo get_field('texto') ?></span> </li>
                                                <?php endif; ?>
                                        <?php endwhile;?>
                                </ul>
                        <?php endif; ?>
                <?php endforeach; ?>
        
	</section>