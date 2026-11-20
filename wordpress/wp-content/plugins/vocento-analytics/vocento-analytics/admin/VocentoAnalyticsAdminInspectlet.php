<?php

class VocentoAnalyticsAdminInspectlet extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Inspeclet");

        //set inicial de red
        if ($this->options_analytics["vcanly_inspectlet_use_network"] === null) {
            $this->options_analytics["vcanly_inspectlet_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-inspectlet'])) {
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
                            <div class="vcanly_inspectlet_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_inspectlet_use_network" id="vcanly_inspectlet_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_inspectlet_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <div class="vcanly_inspectlet_fields">

                            <label for="vcanly_inspectlet_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_inspectlet_enable"
                                   id="vcanly_inspectlet_enable"
                                   value="true" <?php echo ($this->container->inspectlet->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_inspectlet_id">ID Inspectlet</label>
                            <input type="text" name="vcanly_inspectlet_id" id="vcanly_inspectlet_id"
                                   value="<?php echo $this->container->inspectlet->data["ID"] ?>"/><br/>
                            <br/>
                            <br/>


                        </div>

                    </div>
                </div>

                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-inspectlet'); ?>

            </form>


        </div>


        <?php
    }


    public function save()
    {

        $this->options_analytics["vcanly_inspectlet_use_network"] = isset($_POST["vcanly_inspectlet_use_network"]);
        $this->container->inspectlet->enable = $_POST["vcanly_inspectlet_enable"];
        $this->container->inspectlet->data["ID"] = $_POST["vcanly_inspectlet_id"];
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}