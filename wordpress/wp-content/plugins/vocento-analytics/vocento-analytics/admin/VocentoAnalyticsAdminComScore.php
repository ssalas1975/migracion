<?php

class VocentoAnalyticsAdminComScore extends VocentoAnalyticsAdminBase
{
    /* Vars */

    public $comscore_pt_maps = [];

    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("comScore");

        $this->comscore_pt_maps = $this->options['vcanly_comscore_pt_maps'];

        //set inicial de red
        if ($this->options_analytics["vcanly_commscore_use_network"] === null) {
            $this->options_analytics["vcanly_commscore_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-comscore'])) {
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
                            <div class="vcanly_commscore_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_commscore_use_network" id="vcanly_commscore_use_network"
                                       value="true" <?php echo(($this->options_analytics['vcanly_commscore_use_network'] === true) ? "checked" : "") ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">

                        <div class="vcanly_commscore_fields">

                            <label for="vcanly_commscore_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_commscore_enable"
                                   id="vcanly_commscore_enable"
                                   value="true" <?php echo ($this->container->comScore->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_site">voc_site</label>
                            <input type="text" name="vcanly_commscore_voc_site" id="vcanly_commscore_voc_site"
                                   value="<?php echo $this->container->comScore->data["voc_site"] ?>"
                                   style="width:80%"/><br/>
                            <br/>
                            <label for="vcanly_commscore_voc_site">voc_site auto</label>
                            <input type="checkbox" name="vcanly_commscore_voc_site_auto"
                                   id="vcanly_commscore_voc_site_auto"
                                   value="true" <?php echo(($this->container->comScore->data["voc_site_auto"] == "true") ? "checked" : "") ?>>

                            <br/>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_ho_type">voc_ho (tipo)</label>
                            <input type="radio" name="vcanly_commscore_voc_ho_type"
                                   value="-1" <?php echo(($this->container->comScore->ho_type == VocentoAnalyticsHOType::NONE) ? "checked" : "") ?>>
                            Ninguno
                            <input type="radio" name="vcanly_commscore_voc_ho_type"
                                   value="0" <?php echo(($this->container->comScore->ho_type == VocentoAnalyticsHOType::HOME_REF) ? "checked" : "") ?>>
                            Home referer
                            <input type="radio" name="vcanly_commscore_voc_ho_type"
                                   value="1" <?php echo(($this->container->comScore->ho_type == VocentoAnalyticsHOType::TAGS) ? "checked" : "") ?>>
                            Tags
                            <?php if (isVocentoTopicsActive()) { ?>
                                <input type="radio" name="vcanly_commscore_voc_ho_type"
                                       value="2" <?php echo(($this->container->comScore->ho_type == VocentoAnalyticsHOType::TOPICS) ? "checked" : "") ?>>
                                Topics
                            <?php } ?>
                            <br/>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_ed">voc_ed</label>
                            <select name="vcanly_commscore_voc_ed">
                                <?php foreach (VocentoAnalyticsAdminOptions::getOptionsForGroup('voc_ed') as $opt) { ?>
                                    <option <?php echo(($this->container->comScore->data["voc_ed"] == $opt['value']) ? "selected" : "") ?>
                                        value="<?php echo $opt['value'] ?>"><?php echo $opt['name'] ?></option>
                                <?php } ?>
                            </select>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_or">voc_or</label>
                            <select name="vcanly_commscore_voc_or">
                                <?php foreach (VocentoAnalyticsAdminOptions::getOptionsForGroup('voc_or') as $opt) { ?>
                                    <option <?php echo(($this->container->comScore->data["voc_or"] == $opt['value']) ? "selected" : "") ?>
                                        value="<?php echo $opt['value'] ?>"><?php echo $opt['name'] ?></option>
                                <?php } ?>
                            </select>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_pl">voc_pl</label>
                            <select name="vcanly_commscore_voc_pl">
                                <?php foreach (VocentoAnalyticsAdminOptions::getOptionsForGroup('voc_pl') as $opt) { ?>
                                    <option <?php echo(($this->container->comScore->data["voc_pl"] == $opt['value']) ? "selected" : "") ?>
                                        value="<?php echo $opt['value'] ?>"><?php echo $opt['name'] ?></option>
                                <?php } ?>
                            </select>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_pr">voc_pr</label>
                            <select name="vcanly_commscore_voc_pr">
                                <?php foreach (VocentoAnalyticsAdminOptions::getOptionsForGroup('voc_pr') as $opt) { ?>
                                    <option <?php echo(($this->container->comScore->data["voc_pr"] == $opt['value']) ? "selected" : "") ?>
                                        value="<?php echo $opt['value'] ?>"><?php echo $opt['name'] ?></option>
                                <?php } ?>
                            </select>


                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_pu">voc_pu</label>
                            <select name="vcanly_commscore_voc_pu">
                                <?php foreach (VocentoAnalyticsAdminOptions::getOptionsForGroup('voc_pu') as $opt) { ?>
                                    <option <?php echo(($this->container->comScore->data["voc_pu"] == $opt['value']) ? "selected" : "") ?>
                                        value="<?php echo $opt['value'] ?>"><?php echo $opt['name'] ?></option>
                                <?php } ?>
                            </select>
                            <br/>
                            <br/>
                            <label for="vcanly_commscore_voc_ate">voc_ate</label>
                            <select name="vcanly_commscore_voc_ate">
                                <?php foreach (VocentoAnalyticsAdminOptions::getOptionsForGroup('voc_ate') as $opt) { ?>
                                    <option <?php echo(($this->container->comScore->data["voc_ate"] == $opt['value']) ? "selected" : "") ?>
                                        value="<?php echo $opt['value'] ?>"><?php echo $opt['name'] ?></option>
                                <?php } ?>
                            </select>
                            <br/>
                            <br/>
                            <div class="vcanly_info">
                                <h3>Si configura estos campos serán comunes a todas las entradas. Si quiere que se
                                    calculen dinámicamente no los rellene</h3>
                                <br/>
                                <br/>
                                <label for="vcanly_commscore_voc_se">voc_se</label>
                                <input type="text" name="vcanly_commscore_voc_se" id="vcanly_commscore_voc_se"
                                       value="<?php echo $this->container->comScore->data["voc_se"] ?>"
                                       style="width:80%"/><br/>
                                <br/>
                                <br/>
                                <label for="vcanly_commscore_voc_s1">voc_s1</label>
                                <input type="text" name="vcanly_commscore_voc_s1" id="vcanly_commscore_voc_s1"
                                       value="<?php echo $this->container->comScore->data["voc_s1"] ?>"
                                       style="width:80%"/><br/>
                                <br/>
                                <br/>
                                <label for="vcanly_commscore_voc_se">voc_s2</label>
                                <input type="text" name="vcanly_commscore_voc_s2" id="vcanly_commscore_voc_s2"
                                       value="<?php echo $this->container->comScore->data["voc_s2"] ?>"
                                       style="width:80%"/><br/>
                                <br/>
                                <br/>
                                <label for="vcanly_commscore_voc_se">voc_s3</label>
                                <input type="text" name="vcanly_commscore_voc_s3" id="vcanly_commscore_voc_s3"
                                       value="<?php echo $this->container->comScore->data["voc_s3"] ?>"
                                       style="width:80%"/><br/>
                                <br/>
                                <br/>
                                <label for="vcanly_commscore_voc_se">voc_s4</label>
                                <input type="text" name="vcanly_commscore_voc_s4" id="vcanly_commscore_voc_s4"
                                       value="<?php echo $this->container->comScore->data["voc_s4"] ?>"
                                       style="width:80%"/><br/>
                                <br/>
                                <br/>
                            </div>
                            <br/>
                            <br/>

                            <!--Para la red se activa el mapeo de post_type-->
                            <?php if (is_network_admin()) { ?>

                                <label for="vcanly_commscore_voc_tc">voc_tc</label>
                                <hr/>
                                <br/>
                                <div class="vcanly_info">
                                    Desde este apartado puede asociar tipos de contenido (voc_tc) a sus post types.
                                    Cuando se visualice un post type se enviará al sistema de analitica el tipo de
                                    contenido asociado asocie al mismo.
                                </div>
                                <br/>
                                <?php $this->print_post_types() ?><br/>
                                <br/>
                                <br/>

                            <?php } ?>


                        </div>


                    </div>

                </div>
                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-comscore'); ?>

            </form>


        </div>
        <script>
            jQuery(document).ready(function () {
                jQuery('#vcanly_commscore_voc_site_auto').switchable({
                    click: function (ev, checked) {
                        if (checked) {
                            jQuery("#vcanly_commscore_voc_site").attr("disabled", "disabled");
                            jQuery("#vcanly_commscore_voc_site").val("");
                        }
                        else {
                            jQuery("#vcanly_commscore_voc_site").removeAttr("disabled");
                        }
                    }
                });

                if (jQuery('#vcanly_commscore_voc_site_auto').is(":checked")) {
                    jQuery("#vcanly_commscore_voc_site").attr("disabled", "disabled");
                    jQuery("#vcanly_commscore_voc_site").val("");

                }
            });

        </script>


        <?php
    }


    public function print_post_types()
    {

        $posts_types = vcanly_get_post_types_mu();
        $selected = "";
        foreach ($posts_types as $posts_type) {
            ?>
            <div class="vcanly-pt-mapper">
                <div class="vcanly-pt"><?php echo $posts_type ?></div>
                <div class="vcanly-pt-map">

                    <select name="<?php echo sanitize_title("post-map-" . $posts_type) ?>">

                        <?php foreach (VocentoAnalyticsAdminOptions::getOptionsForGroup('voc_tc') as $opt) {
                            ($this->comscore_pt_maps[sanitize_title($posts_type)] == $opt['value']) ? $selected = "selected" : $selected = ""
                            ?>
                            <option <?php echo $selected ?>
                                value="<?php echo $opt['value'] ?>"><?php echo $opt['name'] ?></option>
                        <?php } ?>


                    </select>

                </div>
            </div>
            <?php
        }

    }


    public function save()
    {


        //update values
        $this->options_analytics["vcanly_commscore_use_network"] = isset($_POST["vcanly_commscore_use_network"]);

        if ($_POST["vcanly_commscore_voc_ho_type"]) {
            $this->container->comScore->ho_type = intval($_POST["vcanly_commscore_voc_ho_type"]);
        } else {
            $this->container->comScore->ho_type = VocentoAnalyticsHOType::HOME_REF;
        }

        $this->container->comScore->enable = $_POST["vcanly_commscore_enable"];
        $this->container->comScore->data["voc_site"] = $_POST["vcanly_commscore_voc_site"];
        $this->container->comScore->data["voc_site_auto"] = isset($_POST["vcanly_commscore_voc_site_auto"]);
        $this->container->comScore->data["voc_ed"] = $_POST["vcanly_commscore_voc_ed"];
        $this->container->comScore->data["voc_or"] = $_POST["vcanly_commscore_voc_or"];
        $this->container->comScore->data["voc_pl"] = $_POST["vcanly_commscore_voc_pl"];
        $this->container->comScore->data["voc_pu"] = $_POST["vcanly_commscore_voc_pu"];
        $this->container->comScore->data["voc_ate"] = $_POST["vcanly_commscore_voc_ate"];
        $this->container->comScore->data["voc_pr"] = $_POST["vcanly_commscore_voc_pr"];
        $this->container->comScore->data["voc_se"] = $_POST["vcanly_commscore_voc_se"];
        $this->container->comScore->data["voc_s1"] = $_POST["vcanly_commscore_voc_s1"];
        $this->container->comScore->data["voc_s2"] = $_POST["vcanly_commscore_voc_s2"];
        $this->container->comScore->data["voc_s3"] = $_POST["vcanly_commscore_voc_s3"];
        $this->container->comScore->data["voc_s4"] = $_POST["vcanly_commscore_voc_s4"];


        //Post types maps
        $posts_types = vcanly_get_post_types_mu();

        foreach ($posts_types as $posts_type) {
            $fieldname = sanitize_title("post-map-" . $posts_type);
            $this->comscore_pt_maps[sanitize_title($posts_type)] = $_POST[$fieldname];
        }


        $this->options['vcanly_comscore_pt_maps'] = $this->comscore_pt_maps;
        //Show and save
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}