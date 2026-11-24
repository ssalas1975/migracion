<div class="busquedas contenedor-blanco">
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
                <ul>
                    <?php 

                        for($i=65; $i<=90; $i++) {  
                            $actual= (get_query_var( 'letra' )==chr($i))?'style="font-size: 40px; color: #C9262B;font-weight: 400;"':'';
                            $link=(ICL_LANGUAGE_CODE=='es')? home_url( '/cocineros/' ).'letra/':home_url( '/chefs/' ).'letter/';
                            echo '<li><a '.$actual.' href="'.$link.chr($i) .'">'.chr($i).'</a></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <!--buscador -->
    <div class="formulario-busqueda expandible">
    	<div class="expandir"></div>
        <form role="search" method="get" class="search-form" action="<?php echo home_url( '/cocineros/' ); ?>">
            <label>
                <input required pattern=".{3,}" oninvalid="alert('<?php  _e( 'Tus busquedas deben tener al menos 3 caracteres.', 'wp-bootstrap-starter' ); ?>')" type="search" class="search-field" placeholder="<?php  _e( 'Buscar cocinero |', 'wp-bootstrap-starter' ); ?>" value="<?php echo get_search_query() ?>" name="find" title="<?php  _e( 'Buscar cocinero |', 'wp-bootstrap-starter' ); ?>" onclick="if(this.placeholder=='<?php  _e( 'Buscar cocinero |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e( 'Buscar cocinero |', 'wp-bootstrap-starter' ); ?>'"/>
            </label> 
            <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
        </form>
    </div>
    
    

</div>