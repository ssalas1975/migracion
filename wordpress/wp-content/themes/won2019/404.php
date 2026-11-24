

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="https://gmpg.org/xfn/11">

        <?php wp_head(); ?>

        <!--CSS-->
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/estilos.css"/>
        <!-- <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/mediaqueries.css"/> -->
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/swiper.min.css"/>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/hamburgers.min.css"/>

        <!--JS-->
        <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
        <script src="<?php echo get_template_directory_uri(); ?>/js/swiper.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.transit.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
        

    </head>

    <body <?php body_class(); ?>>

			<section class="error container">

				<section class="header"></section>

				<span class="center">
					<div class="rojo">
						<span class="num">404</span>
						<span class="now">NOW</span>
					</div>
					<div class="negro">
						<span class="women">WOMEN</span>
						<span class="txt">
							<p>No hemos encontrado la página que buscas.</p>
							<p class="small">Para volver a la home pincha 
								<a href="<?php echo get_site_url(); ?>">aquí.</a></p>
						</span>
					</div>
				</span>

			</section>
    


	<?php wp_footer(); ?>

	</body>

</html>
    
                    
       
