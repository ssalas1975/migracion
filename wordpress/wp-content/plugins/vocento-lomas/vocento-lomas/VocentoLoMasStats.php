<?php
class VCLM_Stats
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $options_stats;
	public $messages=array();

    /**
     * Start up
     */
    public function __construct()
    {
        

        //Set options
        if(is_network_admin())
            $this->options = get_site_option( 'vocentovclm_options');
        else
            $this->options = get_option( 'vocentovclm_options');


        $this->options_stats = $this->options['vclm_design_stats'];
        $this->options_stats_maps = $this->options_stats['vclm_design_stats_maps'];
        if(isset($_POST['submit-vclm-stats']))
            $this->save();

    }

    public function print_post_types(){
        
        $posts_types=vclm_get_post_types_by_blog(get_current_blog_id());
        $selected="";
        foreach( $posts_types as $posts_type){
        ?>
        <div class="vclm-pt-mapper">
            <div class="vclm-pt"><?php echo $posts_type?></div>
            <div class="vclm-pt-map">

                <select name="<?php echo sanitize_title("post-map-".$posts_type)?>">
                    <option value="0">--No enviar a Stats</option>

                    <?php ($this->options_stats_maps[sanitize_title($posts_type)]=="1")? $selected="selected":$selected="" ?>

                    <option value="1" <?php echo $selected?> >news</option>

                      <?php ($this->options_stats_maps[sanitize_title($posts_type)]=="2")? $selected="selected":$selected="" ?>

                    <option value="2" <?php echo $selected?> >video</option>

                      <?php ($this->options_stats_maps[sanitize_title($posts_type)]=="3")? $selected="selected":$selected="" ?>

                    <option value="3" <?php echo $selected?> >fotogaleria</option>

                      <?php ($this->options_stats_maps[sanitize_title($posts_type)]=="4")? $selected="selected":$selected="" ?>

                    <option value="4" <?php echo $selected?> >foto</option>

                      <?php ($this->options_stats_maps[sanitize_title($posts_type)]=="5")? $selected="selected":$selected="" ?>

                    <option value="5" <?php echo $selected?> >post</option>

                      <?php ($this->options_stats_maps[sanitize_title($posts_type)]=="6")? $selected="selected":$selected="" ?>

                    <option value="6" <?php echo $selected?> >audio</option>


                </select>

            </div>
            </div>
    <?php
        }

    }

    
    
    public function render_page()
    {
        // Set class property
        
    ?>
    <div class="wrap theme-vclm">
        <h2>Vocento "Lo más":<span>  Configuración Stats</span></h2>
        <?php vclm_print_links_settings()?>
        <br />
        <br />
        <?php 
        vclm_displayMessages($this);
       

        if(is_network_admin()){
            $url = add_query_arg(vclm_composeFormParams("network_vclm_stats","",""), network_admin_url('admin.php'));
        }
        else{
            $url = add_query_arg(vclm_composeFormParams("site_vclm_stats","",""), admin_url('admin.php'));
        }


        ?>

        <form method="post" action="<?php echo $url?>" enctype="multipart/form-data" class="vclm_design_form">

            <?php if(!is_network_admin() && vclm_is_active_network()) { ?>
             <div class="vclm-fields" style="border:0px;">
                <div class="vclm_stats_fields">
                    <h3>Datos de red</h3>
                    <hr />
                    <label for="vclm_name">Utilizar datos de la red</label>
                    <input class="usenetwork"  style="margin-left:30px;" type="checkbox" name="vclm_stats_use_network" id="vclm_stats_use_network" value="true" <?php echo ($this->options_stats['vclm_stats_use_network'])? "checked":"" ?> /><br />
                    <br />
                    
                </div>
            </div>
              <?php } ?>

            <div class="vclm-fields localdata"  style="display:none;">
                <div class="vclm_stats_fields">
                    <h3>Datos generales</h3>
                    <hr />
                     <br />
                      <label for="vclm_name">Incluir sembrado</label>
                    <input  style="margin-left:30px;" type="checkbox" name="vclm_stats_include" id="vclm_stats_include" value="true" <?php echo ($this->options_stats['vclm_stats_include'])? "checked":"" ?> /><br />
                    
                      <br />
                    <label for="vclm_name">URL Metrics</label>
                    <input  type="text" placeholder="http://dominio.com" name="vclm_stats_metrics_url" id="vclm_stats_metrics_url" value="<?php echo $this->options_stats['vclm_stats_metrics_url']?>" /><br />
                    <br />
                    
                    <label for="vclm_name">URL Stats</label>
                    <input  type="text" placeholder="http://dominio.com" name="vclm_stats_url" id="vclm_stats_url" value="<?php echo $this->options_stats['vclm_stats_url']?>" /><br />
                    <br />

                     <label for="vclm_name">Patrón ID</label>
                    <input  type="text" name="vclm_stats_id_pattern" id="vclm_stats_id_pattern" value="<?php echo $this->options_stats['vclm_stats_id_pattern']?>" /><br />
                    <br />
                    <div class="vclm_info">
                       <p>Id que se enviará al sistema de Stats para identificar el contenido. Componga este ID utilizando las particulas.</b></p>
                       <p>Particulas disponibles: <b>%%ID%%</b>,   <b>%%BLOG_ID%%</b></p>
                       <p>Ej:mujerhoy.%%BLOG_ID%%.%%ID%% --> mujerhoy.1.10</p>
                    </div>
                    <br />
                    <br />
                    <label for="vclm_name">Medio</label>
                    <input type="text" name="vclm_stats_medio" id="vclm_stats_medio" value="<?php echo $this->options_stats['vclm_stats_medio']?>" /><br />
                    <br />

                    <label for="vclm_name">Edición</label>
                    <input type="text" name="vclm_stats_edicion" id="vclm_stats_edicion" value="<?php echo $this->options_stats['vclm_stats_edicion']?>" /><br />
                    <br />

                     <label for="vclm_name">Categoria</label>
                    <input type="text" name="vclm_stats_category" id="vclm_stats_category" value="<?php echo $this->options_stats['vclm_stats_category']?>" /><br />
                    <br />
                      <div class="vclm_info" style="margin-bottom: 10px;padding: 0px;line-height: 10px;padding-left: 10px;">
                       <p><b>Si rellena la "categoría" este valor será enviado será enviado de manera forzada. En caso de no rellenarlo se enviará la categoría del post.</b></p>
                       
                    </div>

                    <label for="vclm_name">Tags</label>
                    <input type="text" name="vclm_stats_tags" id="vclm_stats_tags" value="<?php echo $this->options_stats['vclm_stats_tags']?>" /><br />
                    <br />
                     <div class="vclm_info" style="margin-bottom: 10px;padding: 0px;line-height: 10px;padding-left: 10px;">
                       <p><b>Tags que han de enviarse de manera obligatoria. Se añadirán a los que tenga el post.</b></p>
                       
                    </div>

                     <label for="vclm_name">Click type</label>
                    <input type="text" name="vclm_stats_click_type" id="vclm_stats_click_type" value="<?php echo ($this->options_stats['vclm_stats_click_type'])? $this->options_stats['vclm_stats_click_type']:"1"?>" /><br />
                    <br />
                    <!--
                    <label for="vclm_name">Aux 1</label>
                    <input type="text" name="vclm_stats_aux1" id="vclm_stats_aux1" value="<?php echo $this->options_stats['vclm_stats_aux1']?>" /><br />
                    <br />

                    <label for="vclm_name">Aux 2</label>
                    <input type="text" name="vclm_stats_aux2" id="vclm_stats_aux2" value="<?php echo $this->options_stats['vclm_stats_aux2']?>" /><br />
                    <br />-->


                   

                    <div class="vclm-post-map-content">
                    <h3>Relación Post type > Tipo contenido Stats</h3>
                    <hr />
                    <div class="vclm_info">
                       <p>Desde este apartado puede configurar qué "Tipo de contenido" se enviará al sistema de Stats.</p>
                        <p>Para ello <b>asocie cada post type que exista con un tipo de contenido</b></p>
                    </div>
                    <br />
                    <?php $this->print_post_types() ?>
                    </div>


                  
                </div>


            </div>

              <?php    submit_button('Guardar','button button-primary','submit-vclm-stats');  ?>

        </form>



    </div>

    <script>
        jQuery(document).ready(function () {
            if (jQuery(".usenetwork").is(":checked")) {
                jQuery(".localdata").hide();
            }
            else {
                jQuery(".localdata").show();
            }
            jQuery(".usenetwork").change(function () {
                if (jQuery(this).is(":checked")) {
                    jQuery(".localdata").hide();
                }
                else
                    jQuery(".localdata").show();
            });
        });
     </script>

<?php
    }

    
	
	
	

    public function save(  )
    {
        
        
        $this->options_stats['vclm_stats_include']=$_POST["vclm_stats_include"];
        $this->options_stats['vclm_stats_url']=$_POST["vclm_stats_url"];
        $this->options_stats['vclm_stats_metrics_url']=$_POST["vclm_stats_metrics_url"];
        
        $this->options_stats['vclm_stats_medio']=$_POST["vclm_stats_medio"];
        $this->options_stats['vclm_stats_edicion']=$_POST["vclm_stats_edicion"];
        $this->options_stats['vclm_stats_category']=$_POST["vclm_stats_category"];
        
        $this->options_stats['vclm_stats_tags']=$_POST["vclm_stats_tags"];
        $this->options_stats['vclm_stats_aux1']=$_POST["vclm_stats_aux1"];
        $this->options_stats['vclm_stats_aux2']=$_POST["vclm_stats_aux2"];
        $this->options_stats['vclm_stats_id_pattern']=$_POST["vclm_stats_id_pattern"];
        $this->options_stats['vclm_stats_click_type']=$_POST["vclm_stats_click_type"];

        if(!is_network_admin()) {
            $this->options_stats['vclm_stats_use_network']=$_POST["vclm_stats_use_network"];
        }

        //Post types maps
        $posts_types=vclm_get_post_types_by_blog(get_current_blog_id());

        foreach( $posts_types as $posts_type){
            $fieldname=sanitize_title("post-map-".$posts_type);
            $this->options_stats_maps[sanitize_title($posts_type)]=$_POST[$fieldname];
        }

        $this->options_stats['vclm_design_stats_maps']=$this->options_stats_maps;
		$this->options['vclm_design_stats']=$this->options_stats;
        
        if(is_network_admin())
            update_site_option('vocentovclm_options',$this->options,true);
        else
            update_option('vocentovclm_options',$this->options,true);
    }

    
    


}