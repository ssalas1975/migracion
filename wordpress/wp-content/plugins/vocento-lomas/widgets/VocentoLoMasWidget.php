<?php 
/*
Plugin Name: VOCENTO: Lo más
Description: Generador de Lo más
Version:     1.0
Author:      Meytel
Author URI:  http://www.meytel.net
Text Domain: vocento-lomas
 */



class VCLM_Widget extends WP_Widget {
    
    function __construct() {
        parent::__construct(
        
           'vclm_lomas_widget',
           __('VOCENTO: Lo más', 'vclm_widget' ),
           array (
               'description' => __( 'Muestra pestañas lo m&aacute;s', 'vclm_widget' )
           )
        
       );

    }
	
	
	public static function vocentovclm_widget_enqueue($hook) {
		
        wp_enqueue_style( 'vocentotps_styles_widgets_vclm', plugins_url('/assets/css/style_widgets_vclm.css', __FILE__)  );
		
        wp_enqueue_script( 'ajax-script-ui',
				plugins_url( '/assets/js/jquery-ui.min.js', __FILE__ ),
				array('jquery')

			);

        wp_enqueue_script( 'ajax-script-vclm',
				plugins_url( '/assets/js/widgets_functions.js', __FILE__ ),
				array('jquery')
			);
	}
	
    
	

    function form( $instance ) {

?>

<h4>Ajustes generales</h4>
<hr />
<p>
    <label for="<?php echo $this->get_field_id('vclm_widget_title'); ?>">
        <?php _e('Título widget:'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_title'); ?>" name="<?php echo $this->get_field_name('vclm_widget_title'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_title']); ?>" />
    </label>
</p>

<p>
    <label for="<?php echo $this->get_field_id('vclm_widget_shownumbers'); ?>">
        <?php _e('Mostrar numeración:'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_shownumbers'); ?>" name="<?php echo $this->get_field_name('vclm_widget_shownumbers'); ?>" type="checkbox" value="true" <?php echo (esc_attr($instance['vclm_widget_shownumbers']))?"checked":""?> />
    </label>
</p>

<p>
    <label for="<?php echo $this->get_field_id('vclm_widget_hide_tabs'); ?>">
        <?php _e('Ocultar pestañas:'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_hide_tabs'); ?>" name="<?php echo $this->get_field_name('vclm_widget_hide_tabs'); ?>" type="checkbox" value="true" <?php echo (esc_attr($instance['vclm_widget_hide_tabs']))?"checked":""?> />
    </label>
</p>

<input id="<?php echo $this->get_field_id('vclm_widget_tabs_sort'); ?>" name="<?php echo $this->get_field_name('vclm_widget_tabs_sort'); ?>" type="hidden" value="<?php echo esc_attr($instance['vclm_widget_tabs_sort']); ?>"  />


<p>
    <label for="<?php echo $this->get_field_id('vclm_widget_tabs_sort'); ?>">
        <h4>Visibilidad,ordenación y configuración</h4>
        <hr />
        <br />
        <div class="vclm-widget-info">
            Arrastre las pestañas para ordenarlas<br />
            <br />
            Pulse sobre el icono
            <img src="/wp-content/plugins/vocento-lomas/widgets/assets/img/visible.png" style="width: 15px" />
            para mostrar/ocultar la pestaña.
        </div>
        <div class="vclm-tabs">
            <ul class="vclm-widget-tabs-list">

                <?php 
		$default_values="view,comment,shared,publish";
        if(!$instance['vclm_widget_tabs_sort']){
            $instance['vclm_widget_tabs_sort']=$default_values;
        }
		$tabsSort=explode(',',$instance['vclm_widget_tabs_sort']);
		$array_default_values=explode(',',$default_values);
		$tabsSort=array_unique(array_merge($tabsSort,$array_default_values), SORT_REGULAR);

       
        
        foreach($tabsSort as $tabitem)
        {
            if(!vclm_is_tab_available($tabitem))
                continue;

            switch($tabitem){
                
                case "comment": ?>
                <li data-name="comment"><span class="vclm-wd-visibility-tab <?php echo ($instance['vclm_widget_gigya_hidden']=="true")?"hide":"" ?>"  data-rel="#<?php echo $this->get_field_id('vclm_widget_gigya_hidden'); ?>"></span><a href="#tab-comment">Comentado</a></li>
                <?php
                    break;

                case "view": ?>
                <li data-name="view"><span class="vclm-wd-visibility-tab <?php echo ($instance['vclm_widget_stats_hidden']=="true")?"hide":"" ?>"  data-rel="#<?php echo $this->get_field_id('vclm_widget_stats_hidden'); ?>"></span><a href="#tab-view">Visto</a></li>
                <?php
                    break;

                case "shared": ?>
                <li data-name="shared"><span class="vclm-wd-visibility-tab <?php echo ($instance['vclm_widget_addthis_hidden']=="true")?"hide":"" ?>"  data-rel="#<?php echo $this->get_field_id('vclm_widget_addthis_hidden'); ?>"></span><a href="#tab-shared">Compartido</a></li>
                <?php
                    break;

                case "publish": ?>
                <li data-name="publish"><span class="vclm-wd-visibility-tab <?php echo ($instance['vclm_widget_wp_hidden']=="true")?"hide":"" ?>"  data-rel="#<?php echo $this->get_field_id('vclm_widget_wp_hidden'); ?>"></span><a href="#tab-publish">Publicado</a></li>
                <?php
                    break;

                
            }//Switch
        } //for
                ?>
            </ul>

            <?php     if(vclm_is_tab_available("shared")){?>
            <div id="tab-shared">
                <h4>Ajustes pestaña compartidos (Addthis)</h4>
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_addthis_title'); ?>">
                        <?php _e('Titulo pestaña:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_addthis_title'); ?>" name="<?php echo $this->get_field_name('vclm_widget_addthis_title'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_addthis_title']); ?>"  />
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_addthis_count'); ?>">
                        <?php _e('Nº resultados:'); ?>
                        <input max="20" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_addthis_count'); ?>" name="<?php echo $this->get_field_name('vclm_widget_addthis_count'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_addthis_count']); ?>"  />
                    </label>
                </p>

                 <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_addthis_domain'); ?>">
                        <?php _e('Dominio:'); ?>
                        <input max="20" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_addthis_domain'); ?>" name="<?php echo $this->get_field_name('vclm_widget_addthis_domain'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_addthis_domain']); ?>"  />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_addthis_cache_time'); ?>">
                        <?php _e('Caché minutos (5 minutos por defecto):'); ?>
                        <input max="300" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_addthis_cache_time'); ?>" name="<?php echo $this->get_field_name('vclm_widget_addthis_cache_time'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_addthis_cache_time']); ?>"  />
                    </label>
                </p>

              <!-- <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_addthis_show_sharedcount'); ?>">
                        <?php _e('Mostrar nº compatidos:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_addthis_show_sharedcount'); ?>" name="<?php echo $this->get_field_name('vclm_widget_addthis_show_sharedcount'); ?>" type="checkbox" value="true" <?php echo (esc_attr($instance['vclm_widget_addthis_show_sharedcount']))?"checked":""?> />
                    </label>
                </p>-->


                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_addthis_period'); ?>">
                        <?php _e('Periodo:'); ?>
                        <select id="<?php echo $this->get_field_id('vclm_widget_addthis_period'); ?>" name="<?php echo $this->get_field_name('vclm_widget_addthis_period'); ?>" >
                            <option value="day" <?php echo (esc_attr($instance['vclm_widget_addthis_period'])=="day")?"selected":""?>>Día</option>
                            <option value="week" <?php echo (esc_attr($instance['vclm_widget_addthis_period'])=="week")?"selected":""?>>Semana</option>
                            <option value="month" <?php echo (esc_attr($instance['vclm_widget_addthis_period'])=="month")?"selected":""?>>Mes</option>
                                              </select>
                    </label>
                </p>

                <input id="<?php echo $this->get_field_id('vclm_widget_addthis_hidden'); ?>" name="<?php echo $this->get_field_name('vclm_widget_addthis_hidden'); ?>" type="hidden" value="<?php echo esc_attr($instance['vclm_widget_addthis_hidden']); ?>"  />

            </div>
            <?php } ?>
            <?php     if(vclm_is_tab_available("view")){?>
            <div id="tab-view">
                <h4>Ajustes pestaña más visto (Stats)</h4>
                <hr />
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_title'); ?>">
                        <?php _e('Titulo pestaña:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_stats_title'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_title'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_stats_title']); ?>"  />
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_count'); ?>">
                        <?php _e('Nº resultados:'); ?>
                        <input max="20" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_stats_count'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_count'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_stats_count']); ?>"  />
                    </label>
                </p>

                 <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_cache_time'); ?>">
                        <?php _e('Caché minutos (5 minutos por defecto):'); ?>
                        <input max="300" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_stats_cache_time'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_cache_time'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_stats_cache_time']); ?>"  />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_edition'); ?>">
                        <?php _e('Edición:'); ?>
                        <input  class="widefat" id="<?php echo $this->get_field_id('vclm_widget_stats_edition'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_edition'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_stats_edition']); ?>"  />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_category'); ?>">
                        <?php _e('Categoría:'); ?>
                        <input max="20" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_stats_category'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_category'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_stats_category']); ?>"  />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_origin'); ?>">
                        <?php _e('Origen:'); ?>
                        <select id="<?php echo $this->get_field_id('vclm_widget_stats_origin'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_origin'); ?>" >
                            <option value="" <?php echo (esc_attr($instance['vclm_widget_stats_origin'])=="")?"selected":""?>>Todos</option>
                            <option value="web" <?php echo (esc_attr($instance['vclm_widget_stats_origin'])=="web")?"selected":""?>>Web</option>
                            <option value="movil" <?php echo (esc_attr($instance['vclm_widget_stats_origin'])=="movil")?"selected":""?>>Móvil</option>
                        </select>
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_type'); ?>">
                        <?php _e('Tipo:'); ?>
                        <select id="<?php echo $this->get_field_id('vclm_widget_stats_type'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_type'); ?>" >
                            <option value="" <?php echo (esc_attr($instance['vclm_widget_stats_type'])=="1")?"selected":""?>>Todos</option>
                            <option value="1" <?php echo (esc_attr($instance['vclm_widget_stats_type'])=="1")?"selected":""?>>News</option>
                            <option value="2" <?php echo (esc_attr($instance['vclm_widget_stats_type'])=="2")?"selected":""?>>Video</option>
                            <option value="3" <?php echo (esc_attr($instance['vclm_widget_stats_type'])=="3")?"selected":""?>>Fotogalería</option>
                            <option value="4" <?php echo (esc_attr($instance['vclm_widget_stats_type'])=="4")?"selected":""?>>Foto</option>
                            <option value="5" <?php echo (esc_attr($instance['vclm_widget_stats_type'])=="5")?"selected":""?>>Post</option>
                            <option value="6" <?php echo (esc_attr($instance['vclm_widget_stats_type'])=="6")?"selected":""?>>Audio</option>
                        </select>
                    </label>
                </p>


                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_tags'); ?>">
                        <?php _e('Tags:'); ?>
                        <input max="20" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_stats_tags'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_tags'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_stats_tags']); ?>"  />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_stats_show_visit_count'); ?>">
                        <?php _e('Mostrar nº visitas:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_stats_show_visit_count'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_show_visit_count'); ?>" type="checkbox" value="true" <?php echo (esc_attr($instance['vclm_widget_stats_show_visit_count']))?"checked":""?> />
                    </label>
                </p>


                <input id="<?php echo $this->get_field_id('vclm_widget_stats_hidden'); ?>" name="<?php echo $this->get_field_name('vclm_widget_stats_hidden'); ?>" type="hidden" value="<?php echo esc_attr($instance['vclm_widget_stats_hidden']); ?>"  />


            </div>
            <?php } ?>
            <?php     if(vclm_is_tab_available("publish")){?>
            <div id="tab-publish">
                <h4>Ajustes pestaña publicado (Wordpress)</h4>
                <hr />
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_title'); ?>">
                        <?php _e('Titulo pestaña:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_wp_title'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_title'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_wp_title']); ?>"  />
                    </label>
                </p>

                <?php if (vclm_is_active_network()) {?>
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_show_network'); ?>">
                        <?php _e('Mostrar datos de toda la red:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_wp_show_network'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_show_network'); ?>" type="checkbox" value="true" <?php echo (esc_attr($instance['vclm_widget_wp_show_network']))?"checked":""?> />
                    </label>
                </p>
                <?php } ?>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_count'); ?>">
                        <?php _e('Nº resultados:'); ?>
                        <input max="20" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_wp_count'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_count'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_wp_count']); ?>"  />
                    </label>
                </p>

                 <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_cache_time'); ?>">
                        <?php _e('Caché minutos (5 minutos por defecto):'); ?>
                        <input max="300" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_wp_cache_time'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_cache_time'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_wp_cache_time']); ?>"  />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_show_old'); ?>">
                        <?php _e('Mostrar antigüedad:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_wp_show_old'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_show_old'); ?>" type="checkbox" value="true" <?php echo (esc_attr($instance['vclm_widget_wp_show_old']))?"checked":""?> />
                    </label>
                </p>



                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_categories'); ?>">
                        <?php _e('Categoria:'); ?>
                        <br />
                        <select id="<?php echo $this->get_field_id('vclm_widget_wp_categories'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_categories'); ?>" >
                            <?php 

                        echo "<option value=''>Todas las categorías</option>";


                        foreach(vclm_get_all_categories($instance["vclm_widget_wp_show_network"]) as $category)
                        {
                            $seleted="";
                            if($instance["vclm_widget_wp_categories"]==$category->term_id)
                                $seleted="selected";

                            echo "<option $seleted value='$category->term_id'>$category->name</option>";
                        }
                            ?>
                        </select>
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_tags'); ?>">
                        <?php _e('Tag:'); ?>
                        <br />
                        <select id="<?php echo $this->get_field_id('vclm_widget_wp_tags'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_tags'); ?>" >
                            <?php 
                            echo "<option value=''>Todos los tags</option>";
                            foreach(vclm_get_all_tags($instance["vclm_widget_wp_show_network"]) as $term)
                            {
                                $seleted="";
                                if($instance["vclm_widget_wp_tags"]==$term->slug)
                                    $seleted="selected";

                                echo "<option $seleted value='$term->slug'>$term->name</option>";
                            }
                            ?>
                        </select>
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_autor'); ?>">
                        <?php _e('Autor:'); ?>
                        <br />
                        <select id="<?php echo $this->get_field_id('vclm_widget_wp_autor'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_autor'); ?>" >
                            <?php 
                            echo "<option value=''>Todos los autores</option>";
                            foreach(vclm_get_all_authors($instance["vclm_widget_wp_show_network"]) as $author)
                            {
                                $seleted="";
                                if($instance["vclm_widget_wp_autor"]==$author->ID)
                                    $seleted="selected";

                                $auth_data=$author->data;
                                echo "<option $seleted value='$author->ID'>$auth_data->display_name</option>";
                            }
                            ?>
                        </select>
                    </label>
                </p>

                 <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_wp_pt'); ?>">
                        <?php _e('Post types:'); ?>
                        <br />
                        <select id="<?php echo $this->get_field_id('vclm_widget_wp_pt'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_pt'); ?>" >
                            <?php 

                            echo "<option value=''>Todas los post types</option>";


                            foreach(vclm_get_post_types_mu() as $posttype)
                            {
                                $seleted="";
                                if($instance["vclm_widget_wp_pt"]==$posttype)
                                    $seleted="selected";

                                echo "<option $seleted value='$posttype'>$posttype</option>";
                            }
                            ?>
                        </select>
                    </label>
                </p>

                <input id="<?php echo $this->get_field_id('vclm_widget_wp_hidden'); ?>" name="<?php echo $this->get_field_name('vclm_widget_wp_hidden'); ?>" type="hidden" value="<?php echo esc_attr($instance['vclm_widget_wp_hidden']); ?>"  />




            </div>
            <?php } ?>
            <?php     if(vclm_is_tab_available("comment")){?>
            <div id="tab-comment">

                <h4>Ajustes pestaña comentados (Gigya)</h4>
                <hr />
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_gigya_title'); ?>">
                        <?php _e('Titulo pestaña:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_gigya_title'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_title'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_gigya_title']); ?>"  />
                    </label>
                </p>



                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_gigya_count'); ?>">
                        <?php _e('Nº resultados:'); ?>
                        <input max="20" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_gigya_count'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_count'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_gigya_count']); ?>"  />
                    </label>
                </p>


                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_gigya_cache_time'); ?>">
                        <?php _e('Caché minutos (5 minutos por defecto):'); ?>
                        <input max="300" class="widefat" id="<?php echo $this->get_field_id('vclm_widget_gigya_cache_time'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_cache_time'); ?>" type="number" value="<?php echo esc_attr($instance['vclm_widget_gigya_cache_time']); ?>"  />
                    </label>
                </p>


                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_gigya_categories'); ?>">
                        <?php _e('Categorias:'); ?>
                        <br />

                        <select id="<?php echo $this->get_field_id('vclm_widget_gigya_categories'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_categories'); ?>" >
                            <?php 
                            echo "<option value=''>Todas las categorías</option>";

                                $gigya_categories=VCLM_Gigya_srv::VCLM_GET_CATEGORIES();
                                if($gigya_categories)
                                { 
                                    foreach( $gigya_categories->categories as $gigya_cat){

                                        $seleted="";
                                        if($instance["vclm_widget_gigya_categories"]==$gigya_cat->categoryID)
                                            $seleted="selected";

                                        echo "<option $seleted value='$gigya_cat->categoryID'>$gigya_cat->categoryID</option>";
                                    }
                                }

        
                            ?>
                        </select>
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_gigya_max_age'); ?>">
                        <?php _e('Max Stream Age:'); ?>
                        <br />
                        <select id="<?php echo $this->get_field_id('vclm_widget_gigya_max_age'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_max_age'); ?>" >
                            <?php 
                                for($i=1;$i<=7;$i++)
                                {
                                    $seleted="";
                                    if($instance["vclm_widget_gigya_max_age"]==$i)
                                        $seleted="selected";
                                    echo "<option $seleted value='$i'>$i días</option>";
                                }
                            ?>
                        </select>
                    </label>
                </p>


                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_gigya_tag'); ?>">
                        <?php _e('Tag:'); ?>
                        <input  class="widefat" id="<?php echo $this->get_field_id('vclm_widget_gigya_tag'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_tag'); ?>" type="text" value="<?php echo esc_attr($instance['vclm_widget_gigya_tag']); ?>"  />
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('vclm_widget_gigya_show_last'); ?>">
                        <?php _e('Mostrar último comentario:'); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id('vclm_widget_gigya_show_last'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_show_last'); ?>" type="checkbox" value="true" <?php echo (esc_attr($instance['vclm_widget_gigya_show_last']))?"checked":""?> />
                    </label>
                </p>

                <input id="<?php echo $this->get_field_id('vclm_widget_gigya_hidden'); ?>" name="<?php echo $this->get_field_name('vclm_widget_gigya_hidden'); ?>" type="hidden" value="<?php echo esc_attr($instance['vclm_widget_gigya_hidden']); ?>"  />


            </div>
            <?php } ?>

        </div>
    </label>
</p>


<script>
    jQuery(function () {

        jQuery(".vclm-widget-tabs-list").sortable({
            stop: function (event, ui) {
                $sorttabs = "";
                jQuery("li", this).each(function (i, val) {

                    $sorttabs += jQuery(val).data("name") + ",";

                });

                jQuery("#<?php echo $this->get_field_id('vclm_widget_tabs_sort'); ?>").val($sorttabs);
                console.log($sorttabs);
            }
        });

        jQuery(".vclm-widget-tabs-list").disableSelection();

        jQuery(".vclm-tabs").tabs();


        jQuery(".vclm-wd-visibility-tab").off("click");
        jQuery(".vclm-wd-visibility-tab").on("click", function () {
            console.log("clicked eye");
            var relid = jQuery(this).data("rel");

            if (jQuery(this).hasClass("hide")) {
                jQuery(this).removeClass("hide");
                jQuery(relid).val(false);
            }
            else {
                jQuery(this).addClass("hide");
                jQuery(relid).val(true);
            }

        });

    });
</script>
<?php
    }
    
    function update( $new_instance, $old_instance ) {     

		$instance = $old_instance;

        //Comunes
		$instance['vclm_widget_title'] = $new_instance['vclm_widget_title'];
		$instance['vclm_widget_shownumbers'] = $new_instance['vclm_widget_shownumbers'];
        $instance['vclm_widget_tabs_sort'] = $new_instance['vclm_widget_tabs_sort'];
        $instance['vclm_widget_hide_tabs'] = $new_instance['vclm_widget_hide_tabs'];
        
        


        //Addthis tab
		$instance['vclm_widget_addthis_title'] = $new_instance['vclm_widget_addthis_title'];
		$instance['vclm_widget_addthis_count'] = $new_instance['vclm_widget_addthis_count'];
        $instance['vclm_widget_addthis_cache_time'] = $new_instance['vclm_widget_addthis_cache_time'];
        $instance['vclm_widget_addthis_show_sharedcount'] = $new_instance['vclm_widget_addthis_show_sharedcount'];
        $instance['vclm_widget_addthis_hidden'] = $new_instance['vclm_widget_addthis_hidden'];
        $instance['vclm_widget_addthis_period'] = $new_instance['vclm_widget_addthis_period'];
        $instance['vclm_widget_addthis_domain'] = $new_instance['vclm_widget_addthis_domain'];
        
        //Stats tab
		$instance['vclm_widget_stats_title'] = $new_instance['vclm_widget_stats_title'];
		$instance['vclm_widget_stats_count'] = $new_instance['vclm_widget_stats_count'];
        $instance['vclm_widget_stats_cache_time'] = $new_instance['vclm_widget_stats_cache_time'];
        $instance['vclm_widget_stats_edition'] = $new_instance['vclm_widget_stats_edition'];
        $instance['vclm_widget_stats_category'] = $new_instance['vclm_widget_stats_category'];
        $instance['vclm_widget_stats_origin'] = $new_instance['vclm_widget_stats_origin'];
        $instance['vclm_widget_stats_type'] = $new_instance['vclm_widget_stats_type'];
        $instance['vclm_widget_stats_tags'] = $new_instance['vclm_widget_stats_tags'];
        $instance['vclm_widget_stats_show_visit_count'] = $new_instance['vclm_widget_stats_show_visit_count'];
        $instance['vclm_widget_stats_hidden'] = $new_instance['vclm_widget_stats_hidden'];

        //Wordpress tab
        $instance['vclm_widget_wp_title'] = $new_instance['vclm_widget_wp_title'];
        $instance['vclm_widget_wp_show_network'] = $new_instance['vclm_widget_wp_show_network'];
        $instance['vclm_widget_wp_count'] = $new_instance['vclm_widget_wp_count'];
        $instance['vclm_widget_wp_cache_time'] = $new_instance['vclm_widget_wp_cache_time'];
        $instance['vclm_widget_wp_show_old'] = $new_instance['vclm_widget_wp_show_old'];
        $instance['vclm_widget_wp_categories'] = $new_instance['vclm_widget_wp_categories'];
        $instance['vclm_widget_wp_tags'] = $new_instance['vclm_widget_wp_tags'];
        $instance['vclm_widget_wp_autor'] = $new_instance['vclm_widget_wp_autor'];
        $instance['vclm_widget_wp_show_old'] = $new_instance['vclm_widget_wp_show_old'];
        $instance['vclm_widget_wp_hidden'] = $new_instance['vclm_widget_wp_hidden'];
        $instance['vclm_widget_wp_pt'] = $new_instance['vclm_widget_wp_pt'];
        
        //Gigya tab
        $instance['vclm_widget_gigya_title'] = $new_instance['vclm_widget_gigya_title'];
        $instance['vclm_widget_gigya_count'] = $new_instance['vclm_widget_gigya_count'];
        $instance['vclm_widget_gigya_cache_time'] = $new_instance['vclm_widget_gigya_cache_time'];
        
        $instance['vclm_widget_gigya_categories'] = $new_instance['vclm_widget_gigya_categories'];
        $instance['vclm_widget_gigya_max_age'] = $new_instance['vclm_widget_gigya_max_age'];
        $instance['vclm_widget_gigya_tag'] = $new_instance['vclm_widget_gigya_tag'];
        $instance['vclm_widget_gigya_show_last'] = $new_instance['vclm_widget_gigya_show_last'];
        $instance['vclm_widget_gigya_hidden'] = $new_instance['vclm_widget_gigya_hidden'];

        return $instance;
		
    }
    
    function widget( $args, $instance ) {

        echo "<style>" . stripcslashes(vclm_get_option(array("vclm_design_css")))."</style>";
?>


<div class="vocento-lomas-widget">
    <div class="vclm-widget-title"><?php echo $instance["vclm_widget_title"]?></div>

    <?php 
        $hidetabsStyle="";
        if( $instance['vclm_widget_hide_tabs']=="true")
            $hidetabsStyle="style='display:none;'";
    ?>
    <ul class="vclm-tabs" <?php echo $hidetabsStyle?>>
        <?php 

        //if(!$instance['vclm_widget_tabs_sort']){
        //    $instance['vclm_widget_tabs_sort']="view,comment,shared,publish";
        //}

        //$tabsSort=explode(',',$instance['vclm_widget_tabs_sort']);



        $default_values="view,comment,shared,publish";
        if(!$instance['vclm_widget_tabs_sort']){
            $instance['vclm_widget_tabs_sort']=$default_values;
        }
		$tabsSort=explode(',',$instance['vclm_widget_tabs_sort']);
		$array_default_values=explode(',',$default_values);
		$tabsSort=array_unique(array_merge($tabsSort,$array_default_values), SORT_REGULAR);
        
        foreach($tabsSort as $tabitem)
        {
          
            if(!vclm_is_tab_available($tabitem))
                continue;

            switch($tabitem){
                
                case "comment": 
                    if( $instance['vclm_widget_gigya_hidden']!="true"){
        ?>
        <li class="vclm-tab vclm-t-comm"><a href="#tabs-comment"><?php echo $instance["vclm_widget_gigya_title"]?></a></li>
        <?php
                    }
                    break;

                case "view":
                    if( $instance['vclm_widget_stats_hidden']!="true"){
        ?>
        <li  class="vclm-tab vclm-t-comm"><a href="#tabs-view"><?php echo $instance["vclm_widget_stats_title"]?></a></li>
        <?php
                    }
                    break;

                case "shared": 
                    if( $instance['vclm_widget_addthis_hidden']!="true"){
        ?>
        <li  class="vclm-tab vclm-t-comm"><a href="#tabs-shared"><?php echo $instance["vclm_widget_addthis_title"]?></a></li>
        <?php
                    }
                    break;

                case "publish":
                    if( $instance['vclm_widget_wp_hidden']!="true"){
        ?>
        <li  class="vclm-tab vclm-t-comm"><a href="#tabs-publish"><?php echo $instance["vclm_widget_wp_title"]?></a></li>
        <?php
                    }
                    break;

                
            }//Switch
        } //for
        ?>
    </ul>

    <?php    if( $instance['vclm_widget_gigya_hidden']!="true" && vclm_is_tab_available("comment")){ ?>
    <ul id="tabs-comment" class="vclm-tab-content vclm-content-comm">
       <?php 
                 $results=VCLM_Gigya_srv::VCLM_STATS_GET_MOST_COMMENTED_WIDGET($instance);
                 $index_item=0;
                 if($results->streams)
                 {
                     foreach($results->streams as $gigya_item){
                         $index_item++;
       ?>

        <li>
            
             <?php if($instance["vclm_widget_shownumbers"]=="true"){?>
            <div class="vclm-widget-item-number"><?php echo $index_item?></div>
            <?php } ?>

            <div class="vclm-widget-item-title"><a target="_blank" href="<?php echo $gigya_item->streamURL ?>"><?php echo $gigya_item->streamTitle?></a></div>

            <?php if($instance["vclm_widget_gigya_show_last"]=="true"){

                      if($gigya_item->lastComment->status== "published")
                      {
            ?>
                        <div class="vclm-widget-item-text"><?php echo $gigya_item->lastComment->commentText?></div>
                      <?php  
                      }
                  }  
                      ?>
        </li>
        <?php } 
                     
        } // If results->stream
                 ?>
    </ul>
    <?php } ?>

    <?php    if( $instance['vclm_widget_stats_hidden']!="true"  && vclm_is_tab_available("view")){ ?>
    <ul id="tabs-view" class="vclm-tab-content vclm-content-view">

        <?php 
        $results=VCLM_Stats_srv::VCLM_STATS_GET_MOST_VIEWED_WIDGET($instance);
        $index_item=0;

        if($results->data && is_array($results->data)){
            foreach($results->data as $item_stat){
                $index_item++;
        ?>

        <li>

             <?php if($instance["vclm_widget_shownumbers"]=="true"){?>
            <div class="vclm-widget-item-number"><?php echo $index_item?></div>
            <?php } ?>


              <?php if($item_stat->aux1 && strpos($item_stat->aux1,".jpg")!==false)
                    {
              ?>
                <a href="#"><img src="<?php echo $item_stat->aux1?>" /></a>
              <?php } ?>

            <div class="vclm-widget-item-title"><a target="_blank" href="<?php echo $item_stat->url?>"><?php echo $item_stat->title?></a></div>

            <?php if($instance["vclm_widget_stats_show_visit_count"]=="true"){?>
            <div class="vclm-widget-item-text"><?php echo $item_stat->total_visits?> visitas</div>
            <?php }  ?>
        </li>
        <?php }  
        }
        ?>





    </ul>
    <?php } ?>

    <?php    if( $instance['vclm_widget_addthis_hidden']!="true"  && vclm_is_tab_available("shared")){ ?>
    <ul id="tabs-shared" class="vclm-tab-content vclm-content-shared">
        <?php 
                 $results=VCLM_Addthis_srv::VCLM_STATS_GET_MOST_SHARED_WIDGET($instance);
                 $index_item=0;
                 if(is_array($results)){
                     foreach($results as $item_addthis){
                         $index_item++;
        ?>

        <li>
            <?php if($instance["vclm_widget_shownumbers"]=="true"){?>
            <div class="vclm-widget-item-number"><?php echo $index_item?></div>
            <?php } ?>

            <div class="vclm-widget-item-title"><a target="_blank" href="<?php echo $item_addthis->url?>"><?php echo $item_addthis->title?></a></div>

            <?php if($instance["vclm_widget_addthis_show_sharedcount"]=="true"){?>
            <div class="vclm-widget-item-text"><?php echo $item_addthis->shares?> veces compartido</div>
            <?php }  ?>
        </li>
        <?php } 
                     
                 }//if is_array
                 ?>

    </ul>
    <?php } ?>

    <?php    if( $instance['vclm_widget_wp_hidden']!="true"  && vclm_is_tab_available("publish")){ ?>
    <ul id="tabs-publish" class="vclm-tab-content vclm-content-last">
        <?php 
                 $results=VCLM_WP_srv::VCLM_WORDPRESS_GET_LATEST($instance);
                 $index_item=0;
                 foreach($results as $post_wp){
                     $index_item++;
        ?>

        <li>
              <?php if($instance["vclm_widget_shownumbers"]=="true"){?>
            <div class="vclm-widget-item-number"><?php echo $index_item?></div>
            <?php } ?>


            <?php 
                if(vclm_has_post_thumbnail($post_wp)){
                    $url = vclm_wp_get_attachment_url($post_wp );
            ?>
                    <a href="<?php echo vclm_get_post_permalink($post_wp)?>"><img src="<?php echo $url ?>" /></a>
                   <?php
                }    
            ?>
           


            <div class="vclm-widget-item-title"><a target="_blank" href="<?php echo vclm_get_post_permalink($post_wp)?>"><?php echo $post_wp["post_title"]?></a></div>

            <?php if($instance["vclm_widget_wp_show_old"]=="true"){?>
            <div class="vclm-widget-item-text"><?php echo vclm_get_hours_sice($post_wp["post_date"])?></div>
            <?php }  ?>
        </li>
        <?php }  ?>

    </ul>
    <?php } ?>



</div>

<script>
    jQuery(function () {
        jQuery(".vocento-lomas-widget").tabs();
    });
</script>

<?php
    }
	
	
	
	
    public static function register()
    {
        register_widget( __CLASS__ );
    }
    
}
?>