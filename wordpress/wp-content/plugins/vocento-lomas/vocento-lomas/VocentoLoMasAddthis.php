<?php
class VCLM_Addthis
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


        $this->options_addthis = $this->options['vclm_design_addthis'];
       
        if(isset($_POST['submit-vclm-addthis']))
            $this->save();

    }



    
    
    public function render_page()
    {
        // Set class property
        
    ?>
    <div class="wrap theme-vclm">
        <h2>Vocento "Lo más":<span>  Configuración Addthis</span></h2>
        <?php vclm_print_links_settings()?>
        <br />
        <br />
        <?php 
        vclm_displayMessages($this);

        if(is_network_admin()){
            $url = add_query_arg(vclm_composeFormParams("network_vclm_addthis","",""), network_admin_url('admin.php'));
        }
        else{
            $url = add_query_arg(vclm_composeFormParams("site_vclm_addthis","",""), admin_url('admin.php'));
        }
        ?>

        <form method="post" action="<?php echo $url?>" enctype="multipart/form-data" class="vclm_design_form">


            <?php if(!is_network_admin() && vclm_is_active_network()) { ?>
           <div class="vclm-fields" style="border:0px;">
                <div class="vclm_stats_fields">
                    <h3>Datos de red</h3>
                    <hr />
                    <label for="vclm_name">Utilizar datos de la red</label>
                    <input class="usenetwork"  style="margin-left:30px;" type="checkbox" name="vclm_addthis_use_network" id="vclm_addthis_use_network" value="true" <?php echo ($this->options_addthis['vclm_addthis_use_network'])? "checked":"" ?> /><br />
                    <br />
                   
                </div>
            </div>
            <?php } ?>

            <div class="vclm-fields localdata" style="display:none;">
                <div class="vclm_stats_fields">
                    <h3>Datos Addthis</h3>
                    <hr />
                    <label for="vclm_name">PUB ID</label>
                    <input   type="text" name="vclm_addthis_pubid" id="vclm_addthis_pubid" value="<?php echo $this->options_addthis['vclm_addthis_pubid']?>" /><br />
                    <br />
                     <label for="vclm_name">Username</label>
                    <input   type="text" name="vclm_addthis_user" id="vclm_addthis_user" value="<?php echo $this->options_addthis['vclm_addthis_user']?>" /><br />
                    <br />
                     <label for="vclm_name">Password</label>
                    <input   type="password" name="vclm_addthis_pass" id="vclm_addthis_pass" value="<?php echo $this->options_addthis['vclm_addthis_pass']?>" /><br />
                    <br />

                   
                </div>


            </div>

             <?php    submit_button('Guardar','button button-primary','submit-vclm-addthis');  ?>

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
        

        $this->options_addthis['vclm_addthis_pubid']=$_POST['vclm_addthis_pubid'];
        $this->options_addthis['vclm_addthis_user']=$_POST['vclm_addthis_user'];
        $this->options_addthis['vclm_addthis_pass']=$_POST['vclm_addthis_pass'];

        if(!is_network_admin()) {
            $this->options_addthis['vclm_addthis_use_network']=$_POST["vclm_addthis_use_network"];
        }

        $this->options['vclm_design_addthis']=$this->options_addthis;
       


        if(is_network_admin())
            update_site_option('vocentovclm_options',$this->options,true);
        else
            update_option('vocentovclm_options',$this->options,true);
    }

    
    


}