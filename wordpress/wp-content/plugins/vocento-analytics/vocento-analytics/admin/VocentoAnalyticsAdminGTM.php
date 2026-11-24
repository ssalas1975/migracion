<?php

class VocentoAnalyticsAdminGTM extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Google Tag Manager");

        //set inicial de red
        if ($this->options_analytics["vcanly_gtm_use_network"] === null) {
            $this->options_analytics["vcanly_gtm_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-gtm'])) {
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

                    <?php

                    if (vcanly_is_active_network() && !is_network_admin()) { ?>

                        <div class="vcanly-fields" style="border: 0px;">
                            <div class="vcanly_gtm_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_gtm_use_network" id="vcanly_gtm_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_gtm_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <div class="vcanly_gtm_fields">

                            <label for="vcanly_gtm_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_gtm_enable"
                                   id="vcanly_gtm_enable"
                                   value="true" <?php echo ($this->container->gtm->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_gtm_voc_site">GTM ID</label>
                            <input type="text" name="vcanly_gtm_ID" id="vcanly_gtm_ID"
                                   value="<?php echo $this->container->gtm->data["ID"] ?>"/><br/>
                            <br/>
                            <br/>


                        </div>
                    </div>

                </div>

                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-gtm'); ?>

            </form>


        </div>


        <?php
    }


    public function save()
    {
        $this->options_analytics["vcanly_gtm_use_network"] = isset($_POST["vcanly_gtm_use_network"]);
        $this->container->gtm->enable = $_POST["vcanly_gtm_enable"];
        $this->container->gtm->data["ID"] = $_POST["vcanly_gtm_ID"];
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}