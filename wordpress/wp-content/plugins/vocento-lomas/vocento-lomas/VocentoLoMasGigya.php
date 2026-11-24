<?php
class VCLM_Gigya
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


        $this->options_gigya = $this->options['vclm_design_gigya'];
       
        if(isset($_POST['submit-vclm-gigya']))
            $this->save();

    }



    
    
    public function render_page()
    {
        // Set class property
        
    ?>
    <div class="wrap theme-vclm">
        <h2>Vocento "Lo más":<span>  Configuración Gigya</span></h2>
        <?php vclm_print_links_settings()?>
        <br />
        <br />
        <?php 
        vclm_displayMessages($this);
      
        if(is_network_admin()){
         
            $url = add_query_arg(vclm_composeFormParams("network_vclm_gigya","",""), network_admin_url('admin.php'));
        }
        else{
            $url = add_query_arg(vclm_composeFormParams("site_vclm_gigya","",""), admin_url('admin.php'));
        }

        ?>

        <form method="post" action="<?php echo $url?>" enctype="multipart/form-data" class="vclm_design_form">

            <?php if(!is_network_admin() && vclm_is_active_network()) { ?>
           <div class="vclm-fields" style="border:0px;">
                <div class="vclm_stats_fields">
                    <h3>Datos de red</h3>
                    <hr />
                    <label for="vclm_name">Utilizar datos de la red</label>
                    <input class="usenetwork"  style="margin-left:30px;" type="checkbox" name="vclm_gigya_use_network" id="vclm_gigya_use_network" value="true" <?php echo ($this->options_gigya['vclm_gigya_use_network'])? "checked":"" ?> /><br />
                    <br />
                   
                </div>
            </div>
              <?php } ?>

            <div class="vclm-fields localdata"  style="display:none;">
                <div class="vclm_stats_fields">
                    <h3>Datos API Gigya</h3>
                    <hr />
                    <label for="vclm_name">APIKey</label>
                    <input  type="text" name="vclm_gigya_apikey" id="vclm_gigya_apikey" value="<?php echo $this->options_gigya['vclm_gigya_apikey']?>" /><br />
                    <br />

                     <label for="vclm_name">Secret Key</label>
                    <input type="text" name="vclm_gigya_secretkey" id="vclm_gigya_secretkey" value="<?php echo $this->options_gigya['vclm_gigya_secretkey']?>" /><br />
                    <br />
                    


                
                </div>


            </div>


                <?php    submit_button('Guardar','button button-primary','submit-vclm-gigya');  ?>
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
        

        $this->options_gigya['vclm_gigya_apikey']=$_POST['vclm_gigya_apikey'];
        $this->options_gigya['vclm_gigya_secretkey']=$_POST['vclm_gigya_secretkey'];

        if(!is_network_admin()) {
            $this->options_gigya['vclm_gigya_use_network']=$_POST["vclm_gigya_use_network"];
        }

        $this->options['vclm_design_gigya']=$this->options_gigya;
      
        if(is_network_admin())
            update_site_option('vocentovclm_options',$this->options,true);
        else
            update_option('vocentovclm_options',$this->options,true);
    }

    
    


}