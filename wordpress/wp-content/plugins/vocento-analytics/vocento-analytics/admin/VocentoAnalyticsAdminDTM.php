<?php

class VocentoAnalyticsAdminDTM extends VocentoAnalyticsAdminBase
{
    
    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("DTM");

        //set inicial de red
        if ($this->options_analytics["vcanly_dtm_use_network"] === null) {
            $this->options_analytics["vcanly_dtm_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-dtm'])) {
            $this->save();
        }
        
    }
    
    public function render_page($render_links = true)
    {
        // Set class property

        ?>
        <div class="wrap theme-vcanly">


            <form method="post" action="<?php echo $this->form_url ?>" enctype="multipart/form-data"
                  class="vcanly_comscore_form">

                <?php

                //Menús de navegacion, titulos, etc..
                parent::render_page();

                ?>
                <div class="vcanly-fields form-wrapper">

                    <div class="vcanly-fields localdata" style="display: none;">
                        <div class="vcanly_dtm_fields">

                            <label for="vcanly_dtm_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_dtm_enable"
                                   id="vcanly_dtm_enable"
                                   value="true" <?php echo ($this->container->dtm->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_dtm_data_property_id">DATA PROPERTY ID</label>
                            <input type="text" name="vcanly_dtm_data_property_id" id="vcanly_dtm_data_property_id"
                                   value="<?php echo $this->container->dtm->data["datapropertyid"] ?>"/><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_dtm_reportsuit">REPORT SUITE</label>
                            <input type="text" name="vcanly_dtm_reportsuit" id="vcanly_dtm_reportsuit"
                                   value="<?php echo $this->container->dtm->data["reportsuit"] ?>"/><br/>
                            <br/>
                            <br/>


                        </div>
                    </div>

                </div>

                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-dtm'); ?>

            </form>


        </div>


        <?php
    }
    
    
    public function save()
    {

        $this->container->dtm->enable = $_POST["vcanly_dtm_enable"];
        $this->container->dtm->data["datapropertyid"] = $_POST["vcanly_dtm_data_property_id"];
        $this->container->dtm->data["reportsuit"] = $_POST["vcanly_dtm_reportsuit"];
        
        $blog_ids = get_sites();
        
        foreach( $blog_ids as $blog_id ){
            switch_to_blog( $blog_id->blog_id );
            if( get_option('dtmid') == null || get_option('dtmid') == "" || !get_option('dtmid') ){
                $min = pow(10, $length - 1) ;
                $max = pow(10, $length) - 1;
                $id = random_int(111111111, 999999999);
                add_option( 'dtmid', $id );
            }
            restore_current_blog();
        }

        $this->addMessage("Guardado correctamente");
        parent::save();
        
    }
    
}
