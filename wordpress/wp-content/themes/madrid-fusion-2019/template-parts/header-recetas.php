<div class="busquedas container contenido-blanco contenedor-blanco">
    <!--hamburguesa-->
    <div class="menu-blog-contenedor hamburguesa-blog-contenedor hamburguesa-cocineros abecedario">
        <div class="contenedor-centrado">
            <div class="contenido-centrado">
                <div class="menu-blog hamburguesa-blog"></div>
            </div>
        </div>
    </div>
	<!--menu letras-->
    <div class="menu-blog-contenedor abecedario">
        <div class="contenedor-centrado">
            <div class="contenido-centrado">
                <?php if (!dynamic_sidebar('cocineros-abecedario')) {} ?>
            </div>
        </div>
    </div>
    <!--formulario-->
    <div class="formulario-busqueda expandibleNO">
        <!--<div class="expandir"></div>-->
        <form role="search" method="get" class="search-form" action="<?php echo home_url( '/recetas/' ); ?>">
            <label>
                <input required pattern=".{3,}" oninvalid="alert('<?php  _e( 'Tus busquedas deben tener al menos 3 caracteres.', 'wp-bootstrap-starter' ); ?>')" type="search" class="search-field" placeholder="<?php  _e( 'Buscar receta |', 'wp-bootstrap-starter' ); ?>" value="<?php echo get_search_query() ?>" name="find" title="<?php  _e( 'Buscar receta |', 'wp-bootstrap-starter' ); ?>" onclick="if(this.placeholder=='<?php  _e( 'Buscar receta |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e( 'Buscar receta |', 'wp-bootstrap-starter' ); ?>'"/>
            </label> 
            <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
        </form>
    </div>
</div>