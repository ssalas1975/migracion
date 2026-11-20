	
    <?php 

        $args = array(
            'posts_per_page'=> -1,
            'post_type'     => 'speaker',
            'meta_query' => array(
                array(
                    'key' => 'categoria',
                    'value' => 1,
                    'compare'  => '==',
                )
            ),
            'meta_key'      => 'orden',
            'orderby'       => 'meta_value',
            'order'         => 'ASC',
        );
        $keynote = new WP_Query($args);

        $args = array(
            'posts_per_page'=> -1,
            'post_type'     => 'speaker',
            'meta_query' => array(
                array(
                    'key' => 'categoria',
                    'value' => 0,
                    'compare'  => '==',
                )
            ),
            'meta_key'      => 'orden',
            'orderby'       => 'meta_value',
            'order'         => 'ASC'
        );
        $inspiring = new WP_Query($args);

    ?>
    
	<section class="cabecera speaker container">

        <span class="color_title"></span>
        <span class="title">WON</span>

    </section>
    

    <?php if( $keynote->have_posts() ) : ?>
        <section class="speakers keynote container">

                <span class="title_speak">Speakers</span>
                <span class="title_speak2">WON Keynote Speakers 2019</span>
                <ul>

                   <?php while ( $keynote->have_posts() ) : $keynote->the_post(); ?>

                        <li>
                            <div class="image" style="background-image: url('<?php echo get_the_post_thumbnail_url();?>')"></div>
                            <div class="info open">
                                <span class="vcenter">
                                    <h1><?php the_title() ?></h1>
                                    <p><?php echo get_field('cargo') ?></p>
                                </span>
                                <nav class="rrss">
                                    <?php if(get_field('fb')!='' ): ?> 
                                        <a href="<?php echo get_field('fb') ?>"><i class="icon-fb"></i></a>
                                    <?php endif; ?>
                                    <?php if(get_field('twt')!='' ): ?> 
                                        <a href="<?php echo get_field('twt') ?>"><i class="icon-twt"></i></a>
                                    <?php endif; ?>
                                    <?php if(get_field('link')!='' ): ?> 
                                        <a href="<?php echo get_field('link') ?>"><i class="icon-link"></i></a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                            <div class="content open">
                                <span class="vcenter">
                                    <?php echo get_field('texto') ; ?>
                                </span>
                            </div>
                            <span class="x">X</span>
                        </li>

                    <?php  endwhile; ?>

                </ul>
        </section>
    <?php endif; ?>


    <?php if( $inspiring->have_posts() ) : ?>
    	<section class="speakers inspiring container">

            <span class="title_speak">Speakers</span>
            <span class="title_speak2">WON Speakers</span>
            <ul>

                <?php while ( $inspiring->have_posts() ) : $inspiring->the_post(); ?>

                    <li>
                    	<div class="image open" style="background-image: url('<?php echo get_the_post_thumbnail_url();?>')"></div>
                        <div class="info open">
                            <span class="vcenter">
                                <h1><?php the_title() ?></h1>
                                <p><?php echo get_field('cargo') ?></p>
                            </span>
                            <nav class="rrss">
                                <?php if(get_field('fb')!='' ): ?> 
                                    <a href="<?php echo get_field('fb') ?>"><i class="icon-fb"></i></a>
                                <?php endif; ?>
                                <?php if(get_field('twt')!='' ): ?> 
                                    <a href="<?php echo get_field('twt') ?>"><i class="icon-twt"></i></a>
                                <?php endif; ?>
                                <?php if(get_field('link')!='' ): ?> 
                                    <a href="<?php echo get_field('link') ?>"><i class="icon-link"></i></a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <div class="content">
                            <span class="vcenter">
                                <?php echo get_field('texto') ; ?>
                            </span>
                        </div>
                        <span class="x">X</span>
                    </li>

                <?php  endwhile; ?>

                    <!-- link comming soon -->
                    <li class="coming_soon">
                        <div class="image" style="background-image: url('<?php echo get_template_directory_uri() ;?>/images/coming_soon.jpg')">
                            <span><strong>speakers</strong>coming soon</span>
                        </div>
                    </li>

            </ul>
        </section>
    <?php endif; ?>