<!--hamburguesa-->
<div class="menu-blog-contenedor hamburguesa-blog-contenedor">
	<div class="contenedor-centrado">
    	<div class="contenido-centrado">
			<div class="menu-blog hamburguesa-blog"><?php  _e( 'Continentes', 'wp-bootstrap-starter' ); ?></div>
        </div>
    </div>
</div>
<!--menu-->
<div class="menu-blog-contenedor">
	<div class="contenedor-centrado">
    	<div class="contenido-centrado">
			<ul>
			<?php 
				$cont = ( get_query_var( 'cont' ) ) ? get_query_var( 'cont' ) : 1;
				$continentes = get_terms( "continentes", array( "hide_empty" => 1 ) );
				foreach ($continentes as $key => $continente) : ?>
				
                <li><a href="<?php echo (ICL_LANGUAGE_CODE=='es')?home_url('/restaurantes/continente/'):home_url('/restaurants/continent/'); echo $continente->slug ?>"><h2><?php echo $continente->name ?></h2></a></li>

				<?php endforeach ?>
           </ul>
        </div>
    </div>
</div>

<!--formularios-->
<div class="container contenido-blanco row buscadorRestaurante">
    <!--formulario 1-->
    <div class="formulario-busqueda col-md-8">
        <form role="search" method="get" class="search-form" action="<?php echo home_url( '/restaurantes/' ); ?>">
          <label>
            <?php /*?><span class="screen-reader-text"><?php echo _x( 'Buscar restaurante |', 'label' ) ?></span><?php */?>
            <input required pattern=".{3,}" oninvalid="alert('<?php  _e( 'Tus busquedas deben tener al menos 3 caracteres.', 'wp-bootstrap-starter' ); ?>')" type="search" class="search-field" placeholder="<?php  _e( 'Restaurante |', 'wp-bootstrap-starter' ); ?>" value="<?php echo get_search_query() ?>" name="find" title="<?php  _e( 'Restaurante |', 'wp-bootstrap-starter' ); ?>"  onclick="if(this.placeholder=='<?php  _e( 'Restaurante |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e( 'Restaurante |', 'wp-bootstrap-starter' ); ?>'"/>
            
            <?php /*?><div class="lupa"><?php if (!dynamic_sidebar('enlace-buscador-restaurante')) {} ?></div><?php */?>
            
          </label> 
          <input type="submit" class="search-submit lupa" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
          
        </form>
    </div>
    <!--formulario 1-->
    <div class="formulario-busqueda col-md-4">
        <div class="textoBuscador"><div>Pais |</div></div>
        <form role="search" method="get" class="search-form" action="<?php echo home_url( '/restaurantes/' ); ?>">
          <label>
            <?php /*?><span class="screen-reader-text"><?php echo _x( 'País |', 'label' ) ?></span><?php */?>
            <input required pattern=".{3,}" oninvalid="alert('<?php  _e( 'Tus busquedas deben tener al menos 3 caracteres.', 'wp-bootstrap-starter' ); ?>')" type="search" class="search-field" placeholder="<?php  _e( 'País |', 'wp-bootstrap-starter' ); ?>" value="<?php echo get_search_query() ?>" name="id_pais" title="<?php  _e( 'País |', 'wp-bootstrap-starter' ); ?>"  onclick="if(this.placeholder=='<?php  _e( 'País |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e( 'País |', 'wp-bootstrap-starter' ); ?>'"/>
            
            <?php /*?><div class="lupa"><?php if (!dynamic_sidebar('enlace-buscador-restaurante')) {} ?></div><?php */?>
            
          </label> 
          <input type="submit" class="search-submit lupa" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
          
        </form>
    </div>
</div>