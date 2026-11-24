<?php

class VocentoAnalyticsAdminCrazyEgg extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Crazy Egg");

        //set inicial de red
        if ($this->options_analytics["vcanly_egg_use_network"] === null) {
            $this->options_analytics["vcanly_egg_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-egg'])) {
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
                                       name="vcanly_egg_use_network" id="vcanly_egg_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_egg_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <div class="vcanly_egg_fields">

                            <label for="vcanly_egg_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_egg_enable"
                                   id="vcanly_egg_enable"
                                   value="true" <?php echo ($this->container->crazyegg->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_egg_ID">Crazy Egg ID</label>
                            <input type="text" name="vcanly_egg_ID" id="vcanly_egg_ID"
                                   value="<?php echo $this->container->crazyegg->data["ID"] ?>"
                                   placeholder="Default: 0036/2229 (carpeta/script)"/><br/>
                            <br/>
                            <br/>


                        </div>
                    </div>

                </div>

                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-egg'); ?>

            </form>


        </div>


        <?php
    }


    public function save()
    {
        $this->options_analytics["vcanly_egg_use_network"] = isset($_POST["vcanly_egg_use_network"]);
        $this->container->crazyegg->enable = $_POST["vcanly_egg_enable"];
        $this->container->crazyegg->data["ID"] = $_POST["vcanly_egg_ID"];
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}