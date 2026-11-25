<?php
class VocentoAnalyticsAdminAPM extends VocentoAnalyticsAdminBase
{
    

    /**
     * Start up
     */
    public function __construct()
    {	
		//Definimos padre
        parent::__construct("AMP");

        //set inicial de red
        if($this->options_analytics["vcanly_apm_use_network"]===null)
            $this->options_analytics["vcanly_apm_use_network"]=true;
		
        if(isset($_POST['submit-vcanly-apm']))
            $this->save();

    }

    public function render_page($render_links=true)
    {
        // Set class property
        
?>
<div class="wrap theme-vcanly">


    <form method="post" action="<?php echo $this->form_url?>" enctype="multipart/form-data" class="vcanly_comscore_form">

        <?php 
        
        //Menús de navegacion, titulos, etc..
        parent::render_page();
        ?>

        <div class="vcanly-fields form-wrapper">

            <?php
        
        if(vcanly_is_active_network() && !is_network_admin()) {  ?>

            <div class="vcanly-fields" style="border: 0px;">
                <div class="vcanly_apm_fields">
                    <h3>Datos de red</h3>
                    <hr />
                    <label for="vcanly_name">Utilizar datos de la red</label>
                    <input class="usenetwork"  style="margin-left:30px;" type="checkbox" name="vcanly_apm_use_network" id="vcanly_apm_use_network" value="true" <?php echo ($this->options_analytics['vcanly_apm_use_network'])? "checked":"" ?> /><br />
                    <br />

                </div>
            </div>

            <?php } ?>


            <div class="vcanly-fields localdata" style="display: none;">
                <div class="vcanly_apm_fields">

                    <label for="vcanly_apm_enable">Activo</label>
                    <input  style="margin-left:30px;" type="checkbox" name="vcanly_apm_enable" id="vcanly_apm_enable"" value="true" <?php echo ($this->container->apm->enable)? "checked":"" ?> /><br />
                    <br />
                     <br />
                    <label for="vcanly_apm_voc_site">AMP CLIENT ID</label>
                    <input  type="text"  name="vcanly_apm_ID" id="vcanly_apm_ID" value="<?php echo $this->container->apm->data["ID"]?>" /><br />
                    <br />
                    <br />

                    <label for="vcanly_apm_includeall">Incluir siempre</label>
                    <input  style="margin-left:30px;" type="checkbox" name="vcanly_apm_includeall" id="vcanly_apm_includeall"" value="true" <?php echo ($this->container->apm->includeAll)? "checked":"" ?> /><br />
                    <br />

                    <div class="vcanly_info">
                        <b>ATENCIÓN:</b> Solo las páginas AMP compatibles deben incluir el código AMP.<br />
                        Si  marca "Inlcuir siempre" todas las páginas incluirán el código AMP.<br />
                        Si  desea incluirlo en páginas concretas  puede incluir en código asociándolo a un template desde el listado de abajo, o puede indicarlo en la propia página/post mediante la opción "Incluir AMP" en la caja de "Vocento Analytics".
                    </div>

                    <br />
                  
                 
                    
                  

                        <?php 
                        $templates = get_page_templates(); 
                        ?>
                       
                      
                        <h3>Usar APM solo en los siguientes templates</h3>
                        <?php
                        if($templates && count($templates>0)){
                            foreach($templates as $template_name => $template_filename){
                                echo '<input type="checkbox" '.((in_array(vcanly_get_page_template_name($template_filename),$this->container->apm->templates))?"checked":'').' name="vcanly_apm_template[]" value="'.vcanly_get_page_template_name($template_filename).'">'.$template_name.'<br /><br />';
                            }
                        }
                        else{?>
                        <div class="vcanly_info">
                          No se encontraron templates
                        </div>
                       <?php
                        }
                       ?>
                      <br />
                      <br />
                    <?php



                        //AMP:Buscamos por una funcion de AMP
                            if(function_exists('is_amp_endpoint')){
                            //AMP Activo
                    ?>
                            
                            <br />
                            <h3>Opciones plugin AMP Automattica</h3>
                            <div class="vcanly_info">
                             
                                <b><font color="red">IMPORTANTE:</font><br /> Para el correcto funcionamiento del plugin debe añadir los hooks necesarios.<br />
                                Para ello inserte las lineas que se indican en el apartado "Ayuda" en la apertura y cierre del tag "body" en el fichero:<br /><br /></b>
                                <b>-plugins\amp\templates\single.php</b><br /><br />
                                Si en las páginas AMP generadas por el plugin <b>únicamente</b> desea incluir la medición AMP active la opción "Incluir solo AMP".<br />
                              
                            </div>
                            <br />
                             
                            <label for="vcanly_apm_include_only_plg">Incluir solo AMP</label>
                            <input  style="margin-left:30px;" type="checkbox" name="vcanly_apm_include_only_plg" id="vcanly_apm_include_only_plg"" value="true" <?php echo ($this->container->apm->includeOnlyAMPInPLugin)? "checked":"" ?> /><br />
                            

                    <?php
                        }

                    ?>

                </div>


            </div>
        </div>
        <?php  submit_button('Guardar','button button-primary','submit-vcanly-apm');  ?>

    </form>



</div>


<?php
    }

    
	
	
	

    public function save(  )
    {
        $this->options_analytics["vcanly_apm_use_network"]=isset($_POST["vcanly_apm_use_network"]);
        $this->container->apm->enable=$_POST["vcanly_apm_enable"];
        $this->container->apm->includeOnlyAMPInPLugin=$_POST["vcanly_apm_include_only_plg"];
        $this->container->apm->data["ID"]=$_POST["vcanly_apm_ID"];
        $this->container->apm->templates=array();
        if(is_array($_POST["vcanly_apm_template"])){
            foreach($_POST["vcanly_apm_template"] as $temp){
                array_push($this->container->apm->templates,$temp);
            }
        }
        $this->container->apm->includeAll=$_POST["vcanly_apm_includeall"];
        $this->addMessage("Guardado correctamente");
		parent::save();
    }

    
    


}