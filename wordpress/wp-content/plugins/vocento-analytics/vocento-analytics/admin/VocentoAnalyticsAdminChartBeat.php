<?php

class VocentoAnalyticsAdminChartBeat extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("ChartBeat");

        //set inicial de red
        if ($this->options_analytics["vcanly_chartbeat_use_network"] === null) {
            $this->options_analytics["vcanly_chartbeat_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-chartbeat'])) {
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

                        <div class="vcanly-fields" style="border:0px;">
                            <div class="vcanly_chartbeat_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_chartbeat_use_network" id="vcanly_chartbeat_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_chartbeat_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display:none;">
                        <div class="vcanly_chartbeat_fields">

                            <label for="vcanly_chartbeat_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_chartbeat_enable"
                                   id="vcanly_chartbeat_enable"
                                   value="true" <?php echo ($this->container->chartbeat->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_chartbeat_voc_site">UID</label>
                            <input type="text" name="vcanly_chartbeat_uid" id="vcanly_chartbeat_uid"
                                   value="<?php echo $this->container->chartbeat->data["uid"] ?>"/><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_chartbeat_voc_ed">Domain</label>
                            <input type="text" name="vcanly_chartbeat_domain" id="vcanly_chartbeat_domain"
                                   value="<?php echo $this->container->chartbeat->data["domain"] ?>"/><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_site">Domain auto</label>
                            <input type="checkbox" name="vcanly_chartbeat_domain_auto" id="vcanly_chartbeat_domain_auto"
                                   value="true" <?php echo(($this->container->chartbeat->data["domain_auto"] == "true") ? "checked" : "") ?>>
                            <br/>
                            <br/>
                            <br/>
                            <label for="vcanly_chartbeat_enable">use canonical</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_chartbeat_canonical"
                                   id="vcanly_chartbeat_canonical"
                                   value="true" <?php echo ($this->container->chartbeat->data["useCanonical"]) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>


                        </div>


                    </div>


                </div>
                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-chartbeat'); ?>

            </form>


        </div>

        <script>
            jQuery(document).ready(function () {
                jQuery('#vcanly_chartbeat_domain_auto').switchable({
                    click: function (ev, checked) {
                        if (checked) {
                            jQuery("#vcanly_chartbeat_domain").attr("disabled", "disabled");
                            jQuery("#vcanly_chartbeat_domain").val("");
                        }
                        else {
                            jQuery("#vcanly_chartbeat_domain").removeAttr("disabled");
                        }
                    }
                });

                if (jQuery('#vcanly_chartbeat_domain_auto').is(":checked")) {
                    jQuery("#vcanly_chartbeat_domain").attr("disabled", "disabled");
                    jQuery("#vcanly_chartbeat_domain").val("");

                }
            });

        </script>


        <?php
    }


    public function save()
    {


        $this->options_analytics["vcanly_chartbeat_use_network"] = isset($_POST["vcanly_chartbeat_use_network"]);
        $this->container->chartbeat->enable = $_POST["vcanly_chartbeat_enable"];
        $this->container->chartbeat->data["uid"] = $_POST["vcanly_chartbeat_uid"];
        $this->container->chartbeat->data["domain"] = $_POST["vcanly_chartbeat_domain"];
        $this->container->chartbeat->data["domain_auto"] = isset($_POST["vcanly_chartbeat_domain_auto"]);
        $this->container->chartbeat->data["useCanonical"] = $_POST["vcanly_chartbeat_canonical"];


        //Show and save
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}