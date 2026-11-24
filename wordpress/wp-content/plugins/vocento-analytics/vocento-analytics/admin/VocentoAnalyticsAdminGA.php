<?php

class VocentoAnalyticsAdminGA extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Google Analytics");

        //set inicial de red
        if ($this->options_analytics["vcanly_ga_use_network"] === null) {
            $this->options_analytics["vcanly_ga_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-ga'])) {
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
                            <div class="vcanly_ga_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_ga_use_network" id="vcanly_ga_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_ga_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <div class="vcanly_ga_fields">

                            <label for="vcanly_ga_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_ga_enable"
                                   id="vcanly_ga_enable"
                                   value="true" <?php echo ($this->container->ga->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>

                            <?php
                            $gacode_first = "";
                            if (is_array($this->container->ga->data["UA"])) {
                                $gacode_first = $this->container->ga->data["UA"];
                                $gacode_first = $gacode_first[0];
                            }
                            ?>
                            <div class="ga-block">
                                <label for="vcanly_ga_voc_site">Código UA</label>
                                <input class="gacode" type="text" name="vcanly_ga_ua[]" id="vcanly_ga_ua"
                                       value="<?php echo $gacode_first ?>"/>
                                <div class="addgacode">+</div>
                                <br/>
                            </div>


                            <div class="gacode-container">
                                <?php if (is_array($this->container->ga->data["UA"])) {
                                    $gacodes = $this->container->ga->data["UA"];
                                    for ($i = 1; $i < count($gacodes); $i++) {
                                        ?>
                                        <div class="ga-block">
                                            <label for="vcanly_ga_voc_site">Código UA</label>
                                            <input class="gacode" type="text" name="vcanly_ga_ua[]" id="vcanly_ga_ua"
                                                   value="<?php echo $gacodes[$i] ?>"/>
                                            <div class="removegacode">-</div>
                                            <br/>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                <?php }
                                ?>

                            </div>
                            <br/>
                            <br/>


                        </div>

                    </div>
                </div>

                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-ga'); ?>

            </form>


        </div>
        <script>
            jQuery(document).ready(function () {
                jQuery(".addgacode").click(function () {
                    $inputclone = jQuery(jQuery(".ga-block").eq(0).clone());

                    jQuery(".gacode-container").append($inputclone);
                    jQuery(".addgacode", $inputclone).toggleClass("removegacode");
                    jQuery(".removegacode", $inputclone).html("-");
                    jQuery(".gacode", $inputclone).attr("placeholder", "UA Code");
                    jQuery(".gacode", $inputclone).val("");
                    jQuery(".removegacode", $inputclone).click(function () {
                        jQuery(this).closest(".ga-block").remove();
                    });
                });

                jQuery(".removegacode").click(function () {
                    jQuery(this).closest(".ga-block").remove();
                });

            });


        </script>


        <?php
    }


    public function save()
    {

        $this->options_analytics["vcanly_ga_use_network"] = isset($_POST["vcanly_ga_use_network"]);
        $this->container->ga->enable = $_POST["vcanly_ga_enable"];
        $this->container->ga->data["UA"] = $_POST["vcanly_ga_ua"];
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}