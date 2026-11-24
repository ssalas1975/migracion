<header class="navbar fixed-top encabezado muestra" role="banner" style="background-color: transparent;">

         <div class="container">

            <h1>
             <a class="logo" href="/">
              <img src="<?php echo get_stylesheet_directory_uri()?>/img/<?php echo apply_filters('moka_logo_header','logo-moka-01.svg')?>" alt="Logo del sitio">
            </a>
          </h1> 
                
				 
		  

          <button type="button" class="boton-menu hidden-sm-up menu-button" data-toggle="collapse" data-target="#menu-principal" aria-expanded="false">
            <img class="hamb" src="<?php echo get_stylesheet_directory_uri()?>/img/<?php echo apply_filters('moka_menu_icon','hamb.png')?>" alt="hamburguesa" id="hamburguesa" <?php echo apply_filters('moka_data_menu','')?>>
          </button>
   
               <?php get_template_part('partials/menu');?>
        </div>

</header>     