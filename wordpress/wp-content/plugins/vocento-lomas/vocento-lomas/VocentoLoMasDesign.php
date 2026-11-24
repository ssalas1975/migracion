<?php
class VCLM_Design
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
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

        if(isset($_POST['submit-vclm-design']))
            $this->save();

    }

    
    
    public function render_page()
    {
        // Set class property
        
?>
<div class="wrap theme-vclm">
    <h2>Vocento "Lo más":<span>  Configuración visual</span></h2>
    <?php vclm_print_links_settings()?>
    <br />
    <br />
    <?php 
        vclm_displayMessages($this);

        if(is_network_admin()){
            $url = add_query_arg(vclm_composeFormParams("network_vclm_design","",""), network_admin_url('admin.php'));
        }
        else{
            $url = add_query_arg(vclm_composeFormParams("site_vclm_design","",""), admin_url('admin.php'));
        }

       
    ?>

    <form method="post" action="<?php echo $url?>" enctype="multipart/form-data" class="vclm_design_form">

        <?php if(!is_network_admin() && vclm_is_active_network()) { ?>
       <div class="vclm-fields" style="border:0px;">
                <div class="vclm_stats_fields">
                    <h3>Datos de red</h3>
                    <hr />
                    <label for="vclm_name">Utilizar datos de la red</label>
                    <input class="usenetwork"  style="margin-left:30px;" type="checkbox" name="vclm_design_css_use_network" id="vclm_design_css_use_network" value="true" <?php echo ($this->options['vclm_design_css_use_network'])? "checked":"" ?> /><br />
                    <br />
                    
                </div>
            </div>
          <?php } ?>

        <div class="vclm-fields localdata"  style="display:none;">
            <div class="vclm_design_fields">
                <label for="vclm_name">Css</label><br />
                <textarea name="vclm_css" id="vclm_css" class="vclm_css" ><?php echo stripcslashes ($this->options['vclm_design_css'])?></textarea>
             
            </div>

            <style id="vclm-style-inline">

                <?php echo stripcslashes ($this->options['vclm_design_css'])?>

            </style>

            <div class="vclm_design_preview">
                <div class="vocento-lomas-widget">
                      <div class="vclm-widget-title" contenteditable="true">Titulo del widget</div>
                      <ul class="vclm-tabs">
                        <li class="vclm-tab vclm-t-comm"><a href="#tabs-comment"  >Lo más comentado</a></li>
                        <li class="vclm-tab vclm-t-view"><a href="#tabs-view"  >Lo más visto</a></li>
                        <li class="vclm-tab vclm-t-shared"><a href="#tabs-shared"  >Lo más compartido</a></li>
                        <li class="vclm-tab vclm-t-last"><a href="#tabs-last"  >Lo último</a></li>
                      </ul>

                      <ul id="tabs-comment" class="vclm-tab-content vclm-content-comm">
                           <li>
                               <div class="vclm-widget-item-number">1</div>
                                <div class="vclm-widget-item-title"><a href="#" contenteditable="true">Titulo de entrada</a></div>
                           </li>

                            <li>
                                 <div class="vclm-widget-item-number">2</div>
                                 <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                             </li>
                          <li>
                               <div class="vclm-widget-item-number">3</div>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                             </li>
                          <li>
                               <div class="vclm-widget-item-number">4</div>
                                 <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                            </li>
                      </ul>
                      <ul id="tabs-view"  class="vclm-tab-content vclm-content-view">
                        <li>
                               <div class="vclm-widget-item-number">1</div>
                                <div class="vclm-widget-item-title"><a href="#" contenteditable="true">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text" contenteditable="true">1000 visitas</div>
                           </li>

                            <li>
                                 <div class="vclm-widget-item-number">2</div>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text">1000 visita</div>
                            </li>
                          <li>
                               <div class="vclm-widget-item-number">3</div>
                                 <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text">1000 visita</div>
                            </li>
                          <li>
                               <div class="vclm-widget-item-number">4</div>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text">1000 visita</div>
                      </ul>
                      <ul id="tabs-shared"  class="vclm-tab-content vclm-content-shared">
                         <li>
                               <div class="vclm-widget-item-number">1</div>
                                <div class="vclm-widget-item-title"><a href="#" contenteditable="true">Titulo de entrada</a></div>
                            </li>

                            <li>
                                 <div class="vclm-widget-item-number">2</div>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                            </li>
                          <li>
                               <div class="vclm-widget-item-number">3</div>
                                 <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                            </li>
                          <li>
                               <div class="vclm-widget-item-number">4</div>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                           
                       </ul>
                       <ul id="tabs-last"  class="vclm-tab-content vclm-content-last">
                             <li>
                               <div class="vclm-widget-item-number">1</div>
                               <a href="#"><img src="http://dummyimage.com/80x50/ccc/fff" /></a>
                                <div class="vclm-widget-item-title"><a href="#" contenteditable="true">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text" contenteditable="true">Texto de entrada</div>
                           </li>

                            <li>
                                 <div class="vclm-widget-item-number">2</div>
                                <a href="#"><img src="http://dummyimage.com/80x50/ccc/fff" /></a>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text">Texto de entrada</div>
                            </li>
                          <li>
                               <div class="vclm-widget-item-number">3</div>
                                <a href="#"><img src="http://dummyimage.com/80x50/ccc/fff" /></a>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text">Texto de entrada</div>
                            </li>
                          <li>
                               <div class="vclm-widget-item-number">4</div>
                                <a href="#"><img src="http://dummyimage.com/80x50/ccc/fff" /></a>
                                <div class="vclm-widget-item-title"><a href="#">Titulo de entrada</a></div>
                                <div class="vclm-widget-item-text">Texto de entrada</div>
                       </ul>

                </div>
            </div>

            <script>
                jQuery(function () {
                  jQuery(".vocento-lomas-widget").tabs();
                });

                jQuery(document).ready(function () {
                    jQuery("#vclm_css").blur(function () {
                        jQuery("#vclm-style-inline").html(jQuery("#vclm_css").val());
                    });
                });
              </script>
        </div>

           <?php    submit_button('Guardar','button button-primary','submit-vclm-design');  ?>

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
		$this->options['vclm_design_css']=$_POST["vclm_css"];
        if(!is_network_admin()) {
            $this->options['vclm_design_css_use_network']=$_POST["vclm_design_css_use_network"];
        }


        if(is_network_admin())
            update_site_option('vocentovclm_options',$this->options,true);
        else
            update_option('vocentovclm_options',$this->options,true);
    }

    
   

}