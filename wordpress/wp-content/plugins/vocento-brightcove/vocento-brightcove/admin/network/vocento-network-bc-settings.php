<?php
ob_start();

class VCBC_Network_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $config;
	private $messages=array();
    
    /**
     * Start up
     */
    public function __construct()
    {
		
        
        $this->options = get_site_option( 'vocentovcbc_options');

        $config=$this->options['vcbc_settings'];

		if(!$config)
			$config="[]";

		$this->config=json_decode($config,true);

        //Recuperamos los valores del POST
        if($_POST['submit-vcbc-settings-nw'])
            $this->save();


    }



    public function render_page()
    {
?>

<div class="wrap vcbc-add-medio-wrapper">
    <h2>Vocento BC: <span>Ajustes de red</span></h2>
    <br />

    <div class="bc-medio-add">
        <?php 
        $this->displayMessages();
       
        $url = add_query_arg(vcbv_compose_form_params("network_vcbc_settings","",""), network_admin_url('admin.php'));
        
        ?>


        <form method="post" action="<?php echo $url?>" enctype="multipart/form-data">

            <div class="vcbc-fields">
           <h3>Opciones player vocento</h3>
                   
                    <label for="vcbc_name">Incluir JS Vocento</label>
                    <input type="checkbox" id="vcbc_vocento_js" name="vcbc_vocento_js" value="true" <?php echo (($this->config["vcbc_vocento_js"])? "checked":"")  ?> /><br />
                    <br />
                    <label for="vcbc_name">JS Vocento Code</label>
                    <textarea  placeholder="Codigo a incluir" id="vcbc_vocento_js_code" name="vcbc_vocento_js_code"><?php echo (($this->config["vcbc_vocento_js_code"])?$this->config["vcbc_vocento_js_code"]:"")  ?></textarea><br />
                    <br />
                  
                <div class="submits">
                    <?php    submit_button('Guardar','button button-primary','submit-vcbc-settings-nw');  ?>

                </div>
            </div>


        </form>


    </div>


</div>

<script>
    jQuery(document).ready(function () {

        toggleJSCode();

        jQuery("#vcbc_vocento_player").change(function () {
            jQuery(".player-vocento-opts").toggle();

            if (jQuery(".player-vocento-opts").is(":visible"))
                jQuery("input[type='text']", ".player-vocento-opts").attr("required", true);
            else
                jQuery("input[type='text']", ".player-vocento-opts").removeAttr("required");
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

       
		


        //if(isset($_POST["vcbc_required_tags"]) && isset($_POST["vcbc_vocento_url_resizer"])  && isset($_POST["vcbc_vocento_url_methode"])  && isset($_POST["vcbc_vocento_url_methode_tmpl"]))
        //{
            //miramos los campos de vocento player
            //if(isset($_POST["vcbc_vocento_player"])){
            //    if(isset($_POST["vcbc_vocento_iddiv"]) && isset($_POST["vcbc_vocento_template"])  && isset($_POST["vcbc_vocento_site"]))
            //    {
                   
                    //Player
                    //$this->config['vcbc_vocento_iddiv']=$_POST["vcbc_vocento_iddiv"];
                    //$this->config['vcbc_vocento_template']=$_POST["vcbc_vocento_template"];
                    //$this->config['vcbc_vocento_site']=$_POST["vcbc_vocento_site"];
                    $this->config['vcbc_vocento_js_code']=stripslashes(esc_textarea($_POST["vcbc_vocento_js_code"]));
                    //Checkboxes
                    $this->config['vcbc_vocento_js']=isset($_POST["vcbc_vocento_js"]);
                    //$this->config['vcbc_vocento_resizer']=isset($_POST["vcbc_vocento_resizer"]);
                    //$this->config['vcbc_vocento_modal']=isset($_POST["vcbc_vocento_modal"]);
                    //$this->config['vcbc_vocento_auto_start']=isset($_POST["vcbc_vocento_auto_start"]);

            //    }
            //    else{
            //        $this->addErrorMessage("Debe rellenar todos los campos");
            //        return;
            //    }
            //}
            
            ////Comunes
            //$this->config['vcbc_vocento_player']=isset($_POST["vcbc_vocento_player"]);
            //$this->config['vcbc_required_tags']=$_POST["vcbc_required_tags"];
            //$this->config['vcbc_vocento_url_resizer']=$_POST["vcbc_vocento_url_resizer"];
            //$this->config['vcbc_vocento_url_methode']=$_POST["vcbc_vocento_url_methode"];
            //$this->config['vcbc_vocento_url_methode_tmpl']=$_POST["vcbc_vocento_url_methode_tmpl"];

            //Guardamos las copciones
            $this->options['vcbc_settings']=json_encode( $this->config);
            update_site_option('vocentovcbc_options',$this->options,true);

                
        //}
        //else{
        //    $this->addErrorMessage("Debe rellenar todos los campos");
        //}

    }

}