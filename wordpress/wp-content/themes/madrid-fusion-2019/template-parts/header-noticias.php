<!--hamburguesa-->
<div class="menu-blog-contenedor hamburguesa-blog-contenedor">
	<div class="contenedor-centrado">
    	<div class="contenido-centrado">
			<div class="menu-blog hamburguesa-blog"><?php  _e( 'Secciones', 'wp-bootstrap-starter' ); ?></div>
        </div>
    </div>
</div>
<!--menu-->
<div class="menu-blog-contenedor">
	<div class="contenedor-centrado">
    	<div class="contenido-centrado">

			<ul>
			<?php 
				$noticias = get_terms( "blog", array( "hide_empty" => 1 ) );
				foreach ($noticias as $key => $noticia) : ?>
				
                    <li><a href="<?php echo (ICL_LANGUAGE_CODE=='es')?home_url('/el-blog/seccion/'):home_url('/the-blog/section/'); echo $noticia->slug ?>"><?php echo $noticia->name ?></a></li>

				<?php endforeach ?>
           </ul>
        </div>
    </div>
</div>
<!--formulario-->

<!--/* NO AUTORES */-->
<div class="busquedas container contenido-blanco">
<!--/* NO AUTORES */-->

<div class="formulario-busqueda">
    <form role="search" method="get" class="search-form" action="<?php echo home_url( '/el-blog/' ); ?>">
        <?php /*?><div class="textoBuscador"><?php  _e( 'Buscar noticia |', 'wp-bootstrap-starter' ); ?></div><?php */?>
        <label>
            <input required pattern=".{3,}" oninvalid="alert('<?php  _e( 'Tus busquedas deben tener al menos 3 caracteres.', 'wp-bootstrap-starter' ); ?>')" type="search" class="search-field" placeholder="<?php  _e( 'Buscar noticia |', 'wp-bootstrap-starter' ); ?>" value="<?php echo get_search_query() ?>" name="find" title="<?php  _e( 'Buscar noticia |', 'wp-bootstrap-starter' ); ?>" onclick="if(this.placeholder=='<?php  _e( 'Buscar noticia |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e( 'Buscar noticia |', 'wp-bootstrap-starter' ); ?>'"/>
        </label> 
        <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
    </form>
</div>

<!--/* NO AUTORES */-->
</div>
<!--/* NO AUTORES */-->