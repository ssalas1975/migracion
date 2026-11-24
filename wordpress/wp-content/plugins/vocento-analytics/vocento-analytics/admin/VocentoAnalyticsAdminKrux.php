<?php

class VocentoAnalyticsAdminKrux extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Krux");

        //set inicial de red
        if ($this->options_analytics["vcanly_krux_use_network"] === null) {
            $this->options_analytics["vcanly_krux_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-krux'])) {
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
                            <div class="vcanly_krux_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_krux_use_network" id="vcanly_krux_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_krux_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <div class="vcanly_krux_fields">

                            <label for="vcanly_krux_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_krux_enable"
                                   id="vcanly_krux_enable"
                                   value="true" <?php echo ($this->container->krux->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_krux_voc_site">Krux Control Tag</label>
                            <textarea style="min-height:300px;" name="vcanly_krux_control_tag"
                                      id="vcanly_krux_control_tag"><?php echo $this->container->krux->data["control_tag"] ?></textarea><br/>
                            <br/>
                            <br/>


                        </div>
                    </div>

                </div>

                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-krux'); ?>

            </form>


        </div>


        <?php
    }


    public function save()
    {
        $this->options_analytics["vcanly_krux_use_network"] = isset($_POST["vcanly_krux_use_network"]);
        $this->container->krux->enable = $_POST["vcanly_krux_enable"];

        $this->container->krux->data["control_tag"] = stripslashes($_POST["vcanly_krux_control_tag"]);
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}