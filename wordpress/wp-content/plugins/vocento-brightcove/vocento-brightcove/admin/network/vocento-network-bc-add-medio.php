<?php
ob_start();

class VCBC_Network_Add_Medio
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	private $messages=array();
    private $_medio;
    /**
     * Start up
     */
    public function __construct()
    {
		
        $this->options = get_site_option( 'vocentovcbc_options');

       

        //Recuperamos state
        $this->get_state();


        //Recuperamos el medio a editar (si pasan)
        $this->get_medio();

        //Recuperamos los valores del POST
       
        if($_POST['submit-vcbc-edit-medio-nw'])
            $this->save();



        if($_POST['delete-vcbc-edit-medio-nw']){
            vcbc_delete_medio($this->_medio,is_network_admin());
            $url = add_query_arg(vcbv_compose_form_params("network_vcbc_list","","","deleted"), network_admin_url('admin.php'));
            header('Location: '. $url);
            return;
        }

        if($this->_medio){

            if(!VC_CMSAPI_OAuth::GET_CURRENT_TOKEN($this->_medio['vcbc_medio_accid'],$this->_medio['vcbc_medio_id']))
            {
                $this->addErrorMessage("Este punto no se encuentra activo. Es posible que los datos de acceso ya no sean válidos");
               
            }

        }

    }

    public function get_medio(){
        if(isset($_GET["id"]))
            $this->_medio=vcbc_get_medio_id($_GET["id"]);
    }
    
    public function get_state(){
        if(isset($_GET["state"]))
        {
            switch($_GET["state"]){
                case "success":
                    $this->addMessage ("Medio guardado correctamente");
                    break;
                default:
                    $this->addErrorMessage("No se ha podido guardar el medio");
                    break;
            }
        }
    }


    public function render_page()
    {
?>

    <div class="wrap vcbc-add-medio-wrapper">
    <h2>Vocento BC: <span>Creación/Edición de medio</span></h2>
    <br />

    <div class="bc-medio-add">
        <?php 
        $this->displayMessages();
        if(!$this->_medio)
            $url = add_query_arg(vcbv_compose_form_params("network_vcbc_add_medio","edit_vcbc_medio",""), network_admin_url('admin.php'));
        else
            $url = add_query_arg(vcbv_compose_form_params("network_vcbc_add_medio","bc_ec_medio",$this->_medio["vcbc_medio_id"]), network_admin_url('admin.php'));
        
        ?>


        <form method="post" action="<?php echo $url?>" enctype="multipart/form-data">

                <div class="vcbc-fields">
                    <h3>Datos del canal</h3>
                    <hr/>
                    <br/>
                    <label for="vcbc_name">Nombre</label>
                    <input required type="text" placeholder="Nombre descriptivo" id="vcbc_medio_name" name="vcbc_medio_name" value="<?php echo (($this->_medio)?$this->_medio["vcbc_medio_name"]:"")  ?>" /><br />
                    <br />
                    <label for="vcbc_url">Account ID</label>
                    <input required type="text" placeholder="Account ID" id="vcbc_medio_accid" name="vcbc_medio_accid" value="<?php echo (($this->_medio)?$this->_medio["vcbc_medio_accid"]:"")  ?>" /><br />
                    <br />
                    <label for="vcbc_url">Client ID</label>
                    <input required type="text" placeholder="Client ID" id="vcbc_medio_clientid" name="vcbc_medio_clientid" value="<?php echo (($this->_medio)?$this->_medio["vcbc_medio_clientid"]:"")  ?>" /><br />
                    <br />
                    <label for="vcbc_url">Client secret</label>
                    <input required type="text" placeholder="Client secret" id="vcbc_medio_clientsecret" name="vcbc_medio_clientsecret" value="<?php echo (($this->_medio)?$this->_medio["vcbc_medio_clientsecret"]:"")  ?>" /><br />
                    <br />
                     <br/> <br/>
                <div class="vcbc-fields">
                     <h3>Opciones del canal</h3>
                    <hr/>
                     <br/>
                <label for="vcbc_name">Tags obligatorios</label>
                <input  type="text" placeholder="Nombre descriptivo" id="vcbc_required_tags" name="vcbc_required_tags" value="<?php echo (($this->_medio)?$this->_medio["vcbc_required_tags"]:"")  ?>" /><br />
                <br />
                <label for="vcbc_name">ID player brightcove</label>
                <input  placeholder="ID player BC: 'default' por defecto" type="text" id="vcbc_vocento_player_bc_id" name="vcbc_vocento_player_bc_id" value="<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_player_bc_id"]:"")  ?>" /><br />
                <br />
                <label for="vcbc_name">Player Vocento</label>
                <input type="checkbox" id="vcbc_vocento_player" name="vcbc_vocento_player" value="true" <?php echo (($this->_medio && $this->_medio["vcbc_vocento_player"])?"checked":"")  ?>  /><br />
                <br />
                <div class="player-vocento-opts vocento-player-<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_player"]:"false")  ?> ">
                    <h3>Opciones player vocento</h3>
                   <!-- <label for="vcbc_name">id Div</label>
                    <input  placeholder="id Div" type="text" id="vcbc_vocento_iddiv" name="vcbc_vocento_iddiv" value="<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_iddiv"]:"")  ?>" /><br />
                    <br />-->
                    <label for="vcbc_name">Template</label>
                    <input  placeholder="Template" type="text" id="vcbc_vocento_template" name="vcbc_vocento_template" value="<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_template"]:"")  ?>" /><br />
                    <br />
                    <label for="vcbc_name">Site</label>
                    <input  placeholder="Site: mujerhoy ,elcorreo,..." type="text" id="vcbc_vocento_site" name="vcbc_vocento_site" value="<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_site"]:"")  ?>" /><br />
                    <br />
                    <!--<label for="vcbc_name">Incluir JS Vocento</label>
                    <input type="checkbox" id="vcbc_vocento_js" name="vcbc_vocento_js" value="true" <?php echo (($this->_medio && $this->_medio["vcbc_vocento_js"])?"checked":"")  ?> /><br />
                    <br /><br/>
                    <label for="vcbc_name">JS Vocento Code</label>
                    <textarea  placeholder="Codigo a incluir" id="vcbc_vocento_js_code" name="vcbc_vocento_js_code"><?php echo (($this->_medio)?$this->_medio["vcbc_vocento_js_code"]:"")  ?> </textarea><br />
                    <br />-->
                    <label for="vcbc_name">Resizer</label>
                    <input type="checkbox" id="vcbc_vocento_resizer" name="vcbc_vocento_resizer" value="true" <?php echo (($this->_medio && $this->_medio["vcbc_vocento_resizer"])?"checked":"")  ?> /><br />
                    <br />
                    <label for="vcbc_name">Modal layer</label>
                    <input type="checkbox" id="vcbc_vocento_modal" name="vcbc_vocento_modal" value="true" <?php echo (($this->_medio && $this->_medio["vcbc_vocento_modal"])?"checked":"")  ?> /><br />
                    <br />
                    <label for="vcbc_name">Autostart</label>
                    <input type="checkbox" id="vcbc_vocento_auto_start" name="vcbc_vocento_auto_start" value="true" <?php echo (($this->_medio && $this->_medio["vcbc_vocento_auto_start"])?"checked":"")  ?> /><br />
                    <br />
                </div>
                <label for="vcbc_name">Url resizer</label>
                <input required placeholder="Url resizer" type="text" id="vcbc_vocento_url_resizer" name="vcbc_vocento_url_resizer" value="<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_url_resizer"]:"")  ?>" /><br />
                <br />
                <label for="vcbc_name">Url secciones</label>
                <input  placeholder="Url secciones methode" type="text" id="vcbc_vocento_url_methode" name="vcbc_vocento_url_methode" value="<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_url_methode"]:"")  ?>" /><br />
                <br />
                <label for="vcbc_name">Patron de seccion</label>
                <input  placeholder="Patron: %%slug%% %%id%%" type="text" id="vcbc_vocento_url_methode_tmpl" name="vcbc_vocento_url_methode_tmpl" value="<?php echo (($this->_medio)?$this->_medio["vcbc_vocento_url_methode_tmpl"]:"")  ?>" /><br />
                <br />
                <div class="vcbc_info">
                    El patr&oacute;n de secci&oacute;n permite configurar como se va a componer el tag de secci&oacute;n.<br />
                    <br />
                    Para ello se facilitan las siguiente particulas:
                        <ul>
                            <li><b>%%_name_%%</b>: Nombre</li>
                            <li><b>%%_cat_%%</b>:  Categoria</li>
                            <li><b>%%_id_%%"</b>:  ID</li>
                            <li><b>%%_ext_%%"</b>: Externo</li>
                        </ul>

                    <br /><br />
                    Ejemplo:<br />
                    <b>%%_ext_%%_%%_name_%%_%%_cat_%%</b> --> 0_Deportes_deportes/0_Tenis_deportes/tenis<br /><br />
                    <b>%%_id_%%_%%_name_%%_%%_cat_%%</b>  -->  623_Deportes_deportes/602_Baloncesto_baloncesto<br /><br />
                </div>
            </div>


                    <div class="submits">
                   <?php    submit_button('Guardar','button button-primary','submit-vcbc-edit-medio-nw');  ?>

                    <?php  
                        if($this->_medio)
                            submit_button('Eliminar','button button-primary alert','delete-vcbc-edit-medio-nw');
                    ?>
                   </div>
                </div>


            </form>


    </div>


</div>

<script>
    jQuery(document).ready(function () {

        jQuery("#delete-vcbc-edit-medio-nw").click(function () {
            return confirm("¿Confirma que desea eliminar el feed?");
        });


        jQuery('#vcf-table').DataTable();

        toggleJSCode();

        jQuery("#vcbc_vocento_player").change(function () {
            jQuery(".player-vocento-opts").toggle();

            if (jQuery(".player-vocento-opts").is(":visible"))
                jQuery("input[type='text']", ".player-vocento-opts").attr("required", true);
            else
                jQuery("input[type='text']", ".player-vocento-opts").removeAttr("required");
        });

        jQuery("#vcbc_vocento_url_methode").change(function () {

            if (jQuery("#vcbc_vocento_url_methode").val().trim() != "")
                jQuery("#vcbc_vocento_url_methode_tmpl").attr("required", true);
            else
                jQuery("#vcbc_vocento_url_methode_tmpl").removeAttr("required");
        });

        jQuery("#vcbc_vocento_js").change(function () {

            toggleJSCode();

        });
    });

    function toggleJSCode() {
        if (jQuery("#vcbc_vocento_js").is(":checked")) {
            jQuery("#vcbc_vocento_js_code").removeAttr("disabled")
        }
        else {
            jQuery("#vcbc_vocento_js_code").attr("disabled", "true")
        }
    }
  

</script>

<?php

    }



    public function addErrorMessage($error)
    {
		array_push($this->messages,array('class'=>'notice-error', 'msg'=>$error));
	}
	
    public function addMessage($mesage)
    {
		array_push($this->messages,array('class'=>'notice-success', 'msg'=>$mesage));
	}

	public function displayMessages()
    {
		foreach($this->messages as $msg){
			echo "<div class='notice  ".$msg['class']."'>".$msg['msg']."</div>";
		}
	}

    public function save( )
    {

		if(isset( $_POST['vcbc_medio_name'] ) && isset( $_POST['vcbc_medio_accid'] ) && isset($_POST['vcbc_medio_clientid'] ) && isset($_POST['vcbc_medio_clientsecret'] ))
        {
            $arrayMedio=array(
                'vcbc_medio_id'=>uniqid ("vcbc_"),
                'vcbc_medio_name'=>$_POST['vcbc_medio_name'],
                'vcbc_medio_accid'=>$_POST['vcbc_medio_accid'],
                'vcbc_medio_clientid'=>$_POST['vcbc_medio_clientid'],
                'vcbc_medio_clientsecret'=>$_POST['vcbc_medio_clientsecret'],
            );

            if($this->_medio)
                $arrayMedio['vcbc_medio_id']=$this->_medio['vcbc_medio_id'];


            if(!$this->fill_form_fields($arrayMedio))
                return;


            if(!VC_CMSAPI_OAuth::GENERATE_TOKEN( $arrayMedio['vcbc_medio_clientid'], $arrayMedio['vcbc_medio_clientsecret'], $arrayMedio['vcbc_medio_accid'], $arrayMedio['vcbc_medio_id']))
            {
                $this->addErrorMessage("No se puede autentificar con estos datos.<br />Revise por favor sus datos de acceso");
                return;
            }

           

            if(!$this->_medio){

                //Si el medio no exite lo guardamos si no se ha creado ya un canal para ese medio

                if(!vcbc_get_medio_account($arrayMedio['vcbc_medio_accid']))
                {
                    $result=vcbc_add_medio($arrayMedio,true);

                }
                else
                {
                    $this->addErrorMessage("Ya se ha creado un canal con esta cuenta");
                    return;
                }

            }
            else{
                
                //Si existe el id marcamos el que viene y y guardamos

                $arrayMedio['vcbc_medio_id']=$this->_medio['vcbc_medio_id'];
                $result=vcbc_edit_medio($arrayMedio,true);
                $this->get_medio();
            }




            if( $result)
            {
                if(!$this->_medio){
                    ob_flush();
                   
                    $redirectLocation =  add_query_arg(vcbv_compose_form_params("network_vcbc_add_medio","edit_vcbc_medio",$arrayMedio['vcbc_medio_id'],"success"), network_admin_url('admin.php'));
                    header("Location: $redirectLocation");
                    die();
                }
                $this->addMessage ("Medio guardado correctamente");
            }
            else
               $this->addErrorMessage("No se ha podido guardar el medio");
        }
        else{
            $this->addErrorMessage("Debe rellenar todos los campos");
        }

    }


    public function fill_form_fields(&$arrayMedio )
    {

        
		


		if(isset($_POST["vcbc_required_tags"]) && isset($_POST["vcbc_vocento_url_resizer"])  && isset($_POST["vcbc_vocento_url_methode"])  && isset($_POST["vcbc_vocento_url_methode_tmpl"]))
        {
            //miramos los campos de vocento player
            if(isset($_POST["vcbc_vocento_player"])){
                if(isset($_POST["vcbc_vocento_template"])  && isset($_POST["vcbc_vocento_site"]))
                {
                    
                    //Player
                    //$arrayMedio['vcbc_vocento_iddiv']=$_POST["vcbc_vocento_iddiv"];
                    $arrayMedio['vcbc_vocento_template']=$_POST["vcbc_vocento_template"];
                    $arrayMedio['vcbc_vocento_site']=$_POST["vcbc_vocento_site"];
                    //$arrayMedio['vcbc_vocento_js_code']=stripslashes(esc_textarea($_POST["vcbc_vocento_js_code"]));
                    //Checkboxes
                    //$arrayMedio['vcbc_vocento_js']=isset($_POST["vcbc_vocento_js"]);
                    $arrayMedio['vcbc_vocento_resizer']=isset($_POST["vcbc_vocento_resizer"]);
                    $arrayMedio['vcbc_vocento_modal']=isset($_POST["vcbc_vocento_modal"]);
                    $arrayMedio['vcbc_vocento_auto_start']=isset($_POST["vcbc_vocento_auto_start"]);

                }
                else{
                    $this->addErrorMessage("Debe rellenar todos los campos");
                    return false;
                }
            }
            
            //Comunes
            $arrayMedio['vcbc_vocento_player_bc_id']=$_POST["vcbc_vocento_player_bc_id"];
            $arrayMedio['vcbc_vocento_player']=isset($_POST["vcbc_vocento_player"]);
            $arrayMedio['vcbc_required_tags']=$_POST["vcbc_required_tags"];
            $arrayMedio['vcbc_vocento_url_resizer']=$_POST["vcbc_vocento_url_resizer"];
            $arrayMedio['vcbc_vocento_url_methode']=$_POST["vcbc_vocento_url_methode"];
            $arrayMedio['vcbc_vocento_url_methode_tmpl']=$_POST["vcbc_vocento_url_methode_tmpl"];

            //Guardamos las copciones
            //$this->options['vcbc_settings']=json_encode( $this->config);
           // update_site_option('vocentovcbc_options',$this->options,true);

            return true;
            
        }
        else{
            $this->addErrorMessage("Debe rellenar todos los campos");
            return false;
        }

    }

}