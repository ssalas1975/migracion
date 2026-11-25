<?php
ob_start();

class VCBC_Site_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $config;
    private $network_options;
    private $network_config;
	private $messages=array();
    
    /**
     * Start up
     */
    public function __construct()
    {
		
        //site
        $this->options = get_option( 'vocentovcbc_options');
        $config=$this->options['vcbc_settings'];
		if(!$config)
			$config="[]";

		$this->config=json_decode($config,true);


        //RED
        $this->network_options = get_site_option( 'vocentovcbc_options');
        $nwconfig=$this->network_options['vcbc_settings'];

        if(!$nwconfig)
			$nwconfig="[]";

		$this->network_config=json_decode($nwconfig,true);




        //Recuperamos los valores del POST
        if($_POST['submit-vcbc-settings'])
            $this->save();


    }



    public function render_page()
    {
?>

<div class="wrap vcbc-add-medio-wrapper">
    <h2>Vocento BC: <span>Ajustes de site</span></h2>
    <br />

    <div class="bc-medio-add">
        <?php 
        $this->displayMessages();
       
        $url = add_query_arg(vcbv_compose_form_params("site_vcbc_settings","",""), admin_url('admin.php'));
        
        ?>


        <form method="post" action="<?php echo $url?>" enctype="multipart/form-data">

            <div class="vcbc-fields">
                 <h3>Opciones player vocento</h3>
                  
                    

                    <?php if(vcbc_plugin_active_network()){?>
                    <label for="vcbc_name">Usar ajustes red</label>
                    <input type="checkbox" id="vcbc_vocento_js_usenetwork" name="vcbc_vocento_js_usenetwork" value="true" <?php echo ((($this->config["vcbc_vocento_js_usenetwork"]==true || $this->config["vcbc_vocento_js_usenetwork"]===null) && vcbc_plugin_active_network())? "checked":"")  ?> /> <span style="color:#0073aa; cursor:pointer; text-decoration:underline;" id="view-network-settings">Ver ajustes de red</span><br />
                    <br />
                    <div class="preview-network-settings" style="display:none;">
                     <label for="vcbc_name">Incluir JS Vocento</label>
                    <input type="checkbox" disabled  value="true" <?php echo (($this->network_config["vcbc_vocento_js"])? "checked":"")  ?> /><br />
                    <br />
                    <label for="vcbc_name">JS Vocento Code</label>
                    <textarea disabled><?php echo (($this->network_config["vcbc_vocento_js_code"])?$this->network_config["vcbc_vocento_js_code"]:"")  ?></textarea><br />
                    <br />
                  
                    </div>
                    <?php } ?>

                    <label for="vcbc_name">Incluir JS Vocento</label>
                    <input type="checkbox" id="vcbc_vocento_js" name="vcbc_vocento_js" value="true" <?php echo (($this->config["vcbc_vocento_js"])? "checked":"")  ?> /><br />
                    <br />
                    <label for="vcbc_name">JS Vocento Code</label>
                    <textarea  placeholder="Codigo a incluir" id="vcbc_vocento_js_code" name="vcbc_vocento_js_code"><?php echo (($this->config["vcbc_vocento_js_code"])?$this->config["vcbc_vocento_js_code"]:"")  ?></textarea><br />
                    <br />

                  
                <div class="submits">
                    <?php    submit_button('Guardar','button button-primary','submit-vcbc-settings');  ?>

                </div>
            </span>


        </form>


    </div>


</div>

<script>
    jQuery(document).ready(function () {

        jQuery("#view-network-settings").click(function () {
            jQuery(".preview-network-settings").toggle();
        });

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
        
           $this->config['vcbc_vocento_js_code']=stripslashes(esc_textarea($_POST["vcbc_vocento_js_code"]));
           $this->config['vcbc_vocento_js']=isset($_POST["vcbc_vocento_js"]);
           $this->config['vcbc_vocento_js_usenetwork']=isset($_POST["vcbc_vocento_js_usenetwork"]); 

            //Guardamos las copciones
            $this->options['vcbc_settings']=json_encode( $this->config);
            update_option('vocentovcbc_options',$this->options,true);

    }

}