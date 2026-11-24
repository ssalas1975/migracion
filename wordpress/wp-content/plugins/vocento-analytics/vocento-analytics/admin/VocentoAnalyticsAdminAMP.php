<?php

class VocentoAnalyticsAdminAMP extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("AMP");

        //set inicial de red
        if ($this->options_analytics["vcanly_amp_use_network"] === null) {
            $this->options_analytics["vcanly_amp_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-amp'])) {
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
                            <div class="vcanly_amp_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_amp_use_network" id="vcanly_amp_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_amp_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <div class="vcanly_amp_fields">

                            <label for="vcanly_amp_enable">Activo</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_amp_enable"
                                   id="vcanly_amp_enable""
                            value="true" <?php echo ($this->container->amp->enable) ? "checked" : "" ?> /><br/>
                            <br/>
                            <br/>
                            <label for="vcanly_amp_voc_site">AMP CLIENT ID</label>
                            <input type="text" name="vcanly_amp_ID" id="vcanly_amp_ID"
                                   value="<?php echo $this->container->amp->data["ID"] ?>"/><br/>
                            <br/>
                            <br/>

                            <label for="vcanly_amp_includeall">Incluir siempre</label>
                            <input style="margin-left:30px;" type="checkbox" name="vcanly_amp_includeall"
                                   id="vcanly_amp_includeall""
                            value="true" <?php echo ($this->container->amp->includeAll) ? "checked" : "" ?> /><br/>
                            <br/>

                            <div class="vcanly_info">
                                <b>ATENCIÓN:</b> Solo las páginas AMP compatibles deben incluir el código AMP.<br/>
                                Si marca "Inlcuir siempre" todas las páginas incluirán el código AMP.<br/>
                                Si desea incluirlo en páginas concretas puede incluir en código asociándolo a un
                                template desde el listado de abajo, o puede indicarlo en la propia página/post mediante
                                la opción "Incluir AMP" en la caja de "Vocento Analytics".
                            </div>

                            <br/>


                            <?php
                            $templates = get_page_templates();
                            ?>


                            <h3>Usar AMP solo en los siguientes templates</h3>
                            <?php
                            if ($templates && count($templates) > 0) {
                                foreach ($templates as $template_name => $template_filename) {
                                    echo '<input type="checkbox" ' . ((in_array(vcanly_get_page_template_name($template_filename),
                                            $this->container->amp->templates)) ? "checked" : '') . ' name="vcanly_amp_template[]" value="' . vcanly_get_page_template_name($template_filename) . '">' . $template_name . '<br /><br />';
                                }
                            } else {
                                ?>
                                <div class="vcanly_info">
                                    No se encontraron templates
                                </div>
                                <?php
                            }
                            ?>
                            <br/>
                            <br/>
                            <?php


                            //AMP:Buscamos por una funcion de AMP
                            if (function_exists('is_amp_endpoint')) {
                                //AMP Activo
                                ?>

                                <br/>
                                <h3>Opciones plugin AMP Automattica</h3>
                                <div class="vcanly_info">
                                    <b><span style="color:red">IMPORTANTE:</span><br/> Para el correcto funcionamiento
                                        del
                                        plugin debe añadir los hooks necesarios.<br/>
                                        Para ello inserte las lineas que se indican en el apartado "Ayuda" en la
                                        apertura y cierre del tag "body" en el fichero:<br/><br/></b>
                                    <b>-plugins\amp\templates\single.php</b><br/><br/>
                                    Si en las páginas AMP generadas por el plugin <b>únicamente</b> desea incluir la
                                    medición AMP active la opción "Incluir solo AMP".<br/>

                                </div>
                                <br/>
                                <label for="vcanly_amp_include_only_plg">Incluir solo AMP</label>
                                <input style="margin-left:30px;" type="checkbox" name="vcanly_amp_include_only_plg"
                                       id="vcanly_amp_include_only_plg"
                                       value="true" <?php echo ($this->container->amp->includeOnlyAMPInPLugin) ? "checked" : "" ?> />
                                <br/>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-amp'); ?>
            </form>
        </div>
        <?php
    }


    public function save()
    {
        $this->options_analytics["vcanly_amp_use_network"] = isset($_POST["vcanly_amp_use_network"]);
        $this->container->amp->enable = $_POST["vcanly_amp_enable"];
        $this->container->amp->includeOnlyAMPInPLugin = $_POST["vcanly_amp_include_only_plg"];
        $this->container->amp->data["ID"] = $_POST["vcanly_amp_ID"];
        $this->container->amp->templates = array();
        if (is_array($_POST["vcanly_amp_template"])) {
            foreach ($_POST["vcanly_amp_template"] as $temp) {
                array_push($this->container->amp->templates, $temp);
            }
        }
        $this->container->amp->includeAll = $_POST["vcanly_amp_includeall"];
        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}