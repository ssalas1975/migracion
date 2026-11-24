<?php
ob_start();

class VocentoAnalyticsAdminOptions extends VocentoAnalyticsAdminBase
{

    private $opt_id = null;
    private $opt = null;


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Conf. Listado de opciones");

        if ($_GET['vcanly_opt_id']) {
            $this->opt_id = $_GET['vcanly_opt_id'];
            $this->opt = $this->findOption($this->opt_id);
            if (!$this->opt) {
                $this->redirectToList();
                return;
            }
        }

        if ($_GET['action'] && $_GET['action'] == 'deleted') {
            $this->addMessage("Eliminado correctamente");
        }

        if (isset($_POST['submit-vcanly-opts'])) {
            $this->save();
        }

        if (isset($_POST['submit-delete-vcanly-opts'])) {
            $this->delete();
        }

        //Refresh data
        if ($_GET['vcanly_opt_id']) {
            $this->opt = $this->findOption($this->opt_id);

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
                <div class="vcanly-fields form-wrapper wordpress">

                    <?php

                    if (vcanly_is_active_network() && !is_network_admin()) { ?>

                        <div class="vcanly-fields" style="border: 0px;">
                            <div class="vcanly_gtm_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_opts_use_network" id="vcanly_opts_use_network"
                                       value="true" <?php echo ($this->options_analytics_options['vcanly_opts_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <h3>Añadir/Editar opción

                            <?php
                            if ($this->opt) {
                                if (is_network_admin()) {
                                    $this->form_url = add_query_arg(vcanly_composeFormParams("network_vcanly_options",
                                        "", ""), network_admin_url('admin.php'));
                                } else {
                                    $this->form_url = add_query_arg(vcanly_composeFormParams("network_vcanly_options",
                                        "", ""), admin_url('admin.php'));
                                }
                                echo "<a href='" . $this->form_url . "' class='button' style='float:right;'>Crear</a>";
                            }
                            ?>

                        </h3>

                        <div class="vcanly_gtm_fields">

                            <label for="vcanly_tmpl_category">Rellene los siguientes campos</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_tn">Grupo</label><br/>
                                    <select name="vcanly_opts_group" id="vcanly_opts_group" style="width:90%;">
                                        <option
                                            value="voc_ed" <?php echo(($this->opt && $this->opt['group'] == "voc_ed") ? "selected" : "") ?>>
                                            voc_ed
                                        </option>
                                        <option
                                            value="voc_or" <?php echo(($this->opt && $this->opt['group'] == "voc_or") ? "selected" : "") ?>>
                                            voc_or
                                        </option>
                                        <option
                                            value="voc_pl" <?php echo(($this->opt && $this->opt['group'] == "voc_pl") ? "selected" : "") ?>>
                                            voc_pl
                                        </option>
                                        <option
                                            value="voc_pr" <?php echo(($this->opt && $this->opt['group'] == "voc_pr") ? "selected" : "") ?>>
                                            voc_pr
                                        </option>
                                        <option
                                            value="voc_pu" <?php echo(($this->opt && $this->opt['group'] == "voc_pu") ? "selected" : "") ?>>
                                            voc_pu
                                        </option>
                                        <option
                                            value="voc_ate" <?php echo(($this->opt && $this->opt['group'] == "voc_ate") ? "selected" : "") ?>>
                                            voc_ate
                                        </option>
                                        <option
                                            value="voc_tc" <?php echo(($this->opt && $this->opt['group'] == "voc_tc") ? "selected" : "") ?>>
                                            voc_tc
                                        </option>
                                    </select>

                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_tc">Nombre</label>
                                    <input type="text" name="vcanly_opts_name" id="vcanly_opts_name"
                                           value="<?php echo(($this->opt && $this->opt['name']) ? $this->opt['name'] : "") ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_se">Valor</label>
                                    <input type="text" name="vcanly_opts_value" id="vcanly_opts_value"
                                           value="<?php echo(($this->opt && $this->opt['value']) ? $this->opt['value'] : "") ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>
                            <div class="submits">
                                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-opts'); ?>

                                <?php
                                if ($this->opt) {
                                    submit_button('Eliminar', 'button button-primary alert',
                                        'submit-delete-vcanly-opts');
                                }
                                ?>
                            </div>
                            <hr/>

                            <br/>
                            <h3>Opciones existentes</h3>
                            <?php

                            if (isset($this->options_analytics_options['options'])) {

                                $jsonconfig = $this->options_analytics_options['options'];
                                echo "<table id='vcanly_opts-table' class='vcf-c-lines'>";
                                echo "<thead class='line-head'>";
                                echo "<tr>";
                                echo "<th class='name' style='text-align:left;'>Grupo</th>";
                                echo "<th class='name' style='text-align:left;'>Nombre</th>";
                                echo "<th class='name' style='text-align:left;'>Valor</th>";
                                echo "<th class='name' style='text-align:left;'>Editar</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                foreach ($jsonconfig as $option) {
                                    $edit_url = add_query_arg(vcanly_composeFormParams("site_vcanly_options", "edit",
                                        $option["id"]), admin_url('options-general.php'));

                                    if (is_network_admin()) {
                                        $edit_url = add_query_arg(vcanly_composeFormParams("network_vcanly_options",
                                            "edit", $option["id"]), network_admin_url('admin.php'));

                                    }
                                    echo "<tr >";
                                    echo "<td class='name'>" . $option["group"] . "</td>";
                                    echo "<td class='name'>" . $option["name"] . "</td>";
                                    echo "<td class='name'>" . $option["value"] . "</td>";
                                    echo "<td class='editar'><a href='" . $edit_url . "'>editar</a></td>";

                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";

                            }

                            ?>


                            <script>
                                jQuery(document).ready(function () {
                                    jQuery('#vcanly_opts-table').DataTable();
                                });
                            </script>


                        </div>
                    </div>

                </div>


            </form>


        </div>


        <?php
    }


    public function save()
    {
        $this->options_analytics_options["vcanly_opts_use_network"] = $_POST["vcanly_opts_use_network"];

        //Insert

        if ($_POST['vcanly_opts_group'] && $_POST['vcanly_opts_name']) {
            $arr = array();
            $arr["id"] = uniqid("vcanly_opt_");
            $arr["group"] = $_POST['vcanly_opts_group'];
            $arr["name"] = $_POST['vcanly_opts_name'];
            $arr["value"] = $_POST['vcanly_opts_value'];

            if (!is_array($this->options_analytics_options['options'])) {
                $this->options_analytics_options['options'] = array();
            }


            if (!$this->opt) {
                //Insert
                array_push($this->options_analytics_options['options'], $arr);
            } else {
                //Update
                $this->updateOption($this->opt_id, $arr);
            }


        }


        $this->addMessage("Guardado correctamente");
        parent::save();
    }


    public static function getOptionsForGroup($group)
    {

        //Forzado al site, las opciones son comunes a todos los blogs
        $arr = array();
        if (vcanly_is_active_network()) {
            $options = get_site_option('vocentovcanly_options');
        } else {
            $options = get_option('vocentovcanly_options');
        }

        $options_analytics_options = $options['vocentovcanly_options_analytics_options'];
        if ($options_analytics_options['options']) {
            foreach ($options_analytics_options['options'] as $opt) {
                if ($opt['group'] == $group) {
                    array_push($arr, $opt);
                }
            }
        }
        return $arr;

    }

    public function findOption($id)
    {

        if ($this->options_analytics_options['options']) {
            foreach ($this->options_analytics_options['options'] as $opt) {
                if ($opt['id'] == $id) {
                    return $opt;
                }
            }
        }
        return null;

    }

    public function updateOption($id, $newValues)
    {

        if ($this->options_analytics_options['options']) {
            foreach ($this->options_analytics_options['options'] as &$opt) {
                if ($opt['id'] == $id) {
                    $opt["name"] = $newValues["name"];
                    $opt["group"] = $newValues["group"];
                    $opt["value"] = $newValues["value"];
                }
            }
        }


    }

    public function delete()
    {

        $index = 0;
        $found = false;
        if ($this->options_analytics_options['options']) {
            foreach ($this->options_analytics_options['options'] as $opt) {
                if ($opt['id'] == $this->opt_id) {
                    $found = true;
                    break;
                }
                $index++;
            }
        }

        if ($found) {
            array_splice($this->options_analytics_options['options'], $index, 1);
        }

        $this->addMessage("Eliminado correctamente");


        $deleted_url = add_query_arg(vcanly_composeFormParams("site_vcanly_options", "deleted", null),
            admin_url('options-general.php'));

        if (is_network_admin()) {
            $deleted_url = add_query_arg(vcanly_composeFormParams("network_vcanly_options", "deleted", null),
                network_admin_url('admin.php'));

        }

        parent::save();

        header('Location: ' . $deleted_url);


    }

    public function redirectToList()
    {
        $list_url = add_query_arg(vcanly_composeFormParams("site_vcanly_options", "", null),
            admin_url('options-general.php'));

        if (is_network_admin()) {
            $list_url = add_query_arg(vcanly_composeFormParams("network_vcanly_options", "", null),
                network_admin_url('admin.php'));

        }


        header('Location: ' . $list_url);
    }

}