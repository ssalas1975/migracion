<?php
ob_start();

class VocentoAnalyticsAdminSoftClick extends VocentoAnalyticsAdminBase
{

    private $opt_id = null;
    private $opt = null;


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Conf. SoftClick");

        if ($_GET['vcanly_softclick_id']) {
            $this->opt_id = $_GET['vcanly_softclick_id'];
            $this->opt = $this->findOption($this->opt_id);
            if (!$this->opt) {
                $this->redirectToList();
                return;
            }
        }

        if ($_GET['action'] && $_GET['action'] == 'deleted') {
            $this->addMessage("Eliminado correctamente");
        }

        if (isset($_POST['submit-vcanly-softclick'])) {
            $this->save();
        }

        if (isset($_POST['submit-delete-vcanly-softclick'])) {
            $this->delete();
        }

        //Refresh data
        if ($_GET['vcanly_softclick_id']) {
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
                <div class="vcanly-fields form-wrapper">

                    <?php

                    if (vcanly_is_active_network() && !is_network_admin()) { ?>

                        <div class="vcanly-fields" style="border: 0px;">
                            <div class="vcanly_gtm_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_softclick_use_network" id="vcanly_softclick_use_network"
                                       value="true" <?php echo ($this->options_analytics['vcanly_softclick_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">

                        <label for="vcanly_softclick_enable">Activo</label>
                        <input style="margin-left:30px;" type="checkbox" name="vcanly_softclick_enable"
                               id="vcanly_softclick_enable"
                               value="true" <?php echo ($this->container->softclick->enable) ? "checked" : "" ?> /><br/>
                        <br/>
                        <br/>


                        <h3>Añadir/Editar opción

                            <?php
                            if ($this->opt) {
                                if (is_network_admin()) {
                                    $this->form_url = add_query_arg(vcanly_composeFormParams("network_vcanly_softclick",
                                        "", ""), network_admin_url('admin.php'));
                                } else {
                                    $this->form_url = add_query_arg(vcanly_composeFormParams("site_vcanly_softclick",
                                        "", ""), admin_url('admin.php'));
                                }
                                echo "<a href='" . $this->form_url . "' class='button' style='float:right;'>Crear</a>";
                            }
                            ?>

                        </h3>

                        <div class="vcanly_gtm_fields">


                            <div class="vcanly-fields-group four">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_tn">Página</label><br/>
                                    <select name="vcanly_softclick_page">
                                        <?php

                                        //Todas
                                        echo '<option value="all"' . (($this->opt && $this->opt['page'] == 'all') ? "selected" : "") . '>Todas las páginas</option>';


                                        //post types
                                        $pt = vcanly_get_post_types_mu();
                                        foreach ($pt as $posttype) {
                                            echo '<option value="pt-' . $posttype . '"' . (($this->opt && $this->opt['page'] == 'pt-' . $posttype) ? "selected" : "") . '>Post type: ' . $posttype . '</option>';
                                        }
                                        //Templates
                                        $templates = get_page_templates();
                                        if ($templates && count($templates > 0)) {
                                            foreach ($templates as $template_name => $template_filename) {
                                                echo '<option value="tmp-' . vcanly_get_page_template_name($template_filename) . '" ' . (($this->opt && $this->opt['page'] == 'tmp-' . vcanly_get_page_template_name($template_filename)) ? "selected" : "") . ' >Template: ' . $template_name . '<br /><br />';
                                            }
                                        }

                                        if (!is_network_admin()) {
                                            //Paginas
                                            $pages = get_pages();
                                            foreach ($pages as $page) {
                                                echo '<option value="page-' . $page->post_name . '"' . (($this->opt && $this->opt['page'] == 'page-' . $page->post_name) ? "selected" : "") . '>Página: ' . $page->post_name . '</option>';
                                            }
                                        }

                                        //wordpress
                                        echo '<option value="wp-home"' . (($this->opt && $this->opt['page'] == 'wp-home') ? "selected" : "") . '>WordPress: Home</option>';
                                        echo '<option value="wp-category"' . (($this->opt && $this->opt['page'] == 'wp-category') ? "selected" : "") . '>WordPress: Categoría</option>';
                                        echo '<option value="wp-tag"' . (($this->opt && $this->opt['page'] == 'wp-tag') ? "selected" : "") . '>WordPress: Tags</option>';
                                        echo '<option value="wp-author"' . (($this->opt && $this->opt['page'] == 'wp-author') ? "selected" : "") . '>WordPress: Autor</option>';
                                        echo '<option value="wp-search"' . (($this->opt && $this->opt['page'] == 'wp-search') ? "selected" : "") . '>WordPress: Búsqueda</option>';
                                        echo '<option value="wp-404"' . (($this->opt && $this->opt['page'] == 'wp-404') ? "selected" : "") . '>WordPress: 404</option>';


                                        ?>
                                    </select>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_tc">.class/#ID</label>
                                    <input type="text" name="vcanly_softclick_selector" id="vcanly_softclick_selector"
                                           value="<?php echo(($this->opt && $this->opt['selector']) ? $this->opt['selector'] : "") ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_se">Categoría</label>
                                    <input type="text" placeholder="GA Category" name="vcanly_softclick_category"
                                           id="vcanly_softclick_category"
                                           value="<?php echo(($this->opt && $this->opt['cat']) ? $this->opt['cat'] : "") ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_se">Medición/Action</label>
                                    <input type="text" name="vcanly_softclick_med" id="vcanly_softclick_med"
                                           value="<?php echo(($this->opt && $this->opt['med']) ? $this->opt['med'] : "") ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_se">Evento</label>

                                    <select name="vcanly_softclick_event">
                                        <option
                                            value="clickin" <?php echo(($this->opt && $this->opt['event'] == "clickin") ? "selected" : "") ?>>
                                            clickin
                                        </option>
                                        <option
                                            value="clickout" <?php echo(($this->opt && $this->opt['event'] == "clickout") ? "selected" : "") ?>>
                                            clickout
                                        </option>
                                        <option
                                            value="pdf" <?php echo(($this->opt && $this->opt['event'] == "pdf") ? "pdf" : "") ?>>
                                            pdf
                                        </option>
                                        <option
                                            value="hidden" <?php echo(($this->opt && $this->opt['event'] == "hidden") ? "hidden" : "") ?>>
                                            hidden
                                        </option>
                                    </select>

                                </div>

                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_se">Tipo</label>
                                    <select name="vcanly_softclick_event_type">
                                        <option
                                            value="dax" <?php echo(($this->opt && $this->opt['event_type'] == "dax") ? "selected" : "") ?>>
                                            DAx
                                        </option>
                                        <option
                                            value="ga" <?php echo(($this->opt && $this->opt['event_type'] == "ga") ? "selected" : "") ?>>
                                            GA
                                        </option>
                                    </select>

                                </div>

                            </div>
                            <br/>
                            <br/>
                            <div class="submits">
                                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-softclick'); ?>

                                <?php
                                if ($this->opt) {
                                    submit_button('Eliminar', 'button button-primary alert',
                                        'submit-delete-vcanly-softclick');
                                }
                                ?>
                            </div>
                            <hr/>

                            <br/>
                            <br/>
                            <br/>
                            <br/>
                            <h3>Opciones existentes</h3>
                            <?php
                            if (isset($this->container->softclick->data)) {

                                $jsonconfig = $this->container->softclick->data;
                                echo "<table id='vcanly_opts-table' class='vcf-c-lines'>";
                                echo "<thead class='line-head'>";
                                echo "<tr>";
                                echo "<th class='name' style='text-align:left;'>Página</th>";
                                echo "<th class='name' style='text-align:left;'>Selector</th>";
                                echo "<th class='name' style='text-align:left;'>Categoría</th>";
                                echo "<th class='name' style='text-align:left;'>Medición</th>";
                                echo "<th class='name' style='text-align:left;'>Evento</th>";
                                echo "<th class='name' style='text-align:left;'>Tipo</th>";
                                echo "<th class='name' style='text-align:left;'>Editar</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                foreach ($jsonconfig as $option) {
                                    $edit_url = add_query_arg(vcanly_composeFormParamsForSoftClick("site_vcanly_softclick",
                                        "edit", $option["id"]), admin_url('admin.php'));

                                    if (is_network_admin()) {
                                        $edit_url = add_query_arg(vcanly_composeFormParamsForSoftClick("network_vcanly_softclick",
                                            "edit", $option["id"]), network_admin_url('admin.php'));

                                    }
                                    echo "<tr >";
                                    echo "<td class='name'>" . $option["page"] . "</td>";
                                    echo "<td class='name'>" . $option["selector"] . "</td>";
                                    echo "<td class='name'>" . $option["cat"] . "</td>";
                                    echo "<td class='name'>" . $option["med"] . "</td>";
                                    echo "<td class='name'>" . $option["event"] . "</td>";
                                    echo "<td class='name'>" . $option["event_type"] . "</td>";
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
                <div class="submits">
                    <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-softclick'); ?>
                </div>


            </form>

        </div>


        <?php
    }


    public function save()
    {
        $this->options_analytics["vcanly_softclick_use_network"] = isset($_POST["vcanly_softclick_use_network"]);
        $this->container->softclick->enable = $_POST["vcanly_softclick_enable"];
        //Insert

        if ($_POST['vcanly_softclick_selector'] && $_POST['vcanly_softclick_med']) {
            $arr = array();
            $arr["id"] = uniqid("vcanly_softclick_");
            $arr["page"] = $_POST['vcanly_softclick_page'];
            $arr["selector"] = $_POST['vcanly_softclick_selector'];
            $arr["cat"] = $_POST['vcanly_softclick_category'];
            $arr["med"] = $_POST['vcanly_softclick_med'];
            $arr["event"] = $_POST['vcanly_softclick_event'];
            $arr["event_type"] = $_POST['vcanly_softclick_event_type'];

            if (!is_array($this->container->softclick->data)) {
                $this->container->softclick->data = array();
            }

            if (!$this->opt) {
                //Insert
                array_push($this->container->softclick->data, $arr);
            } else {
                //Update
                $this->updateOption($this->opt_id, $arr);
            }


        }


        $this->addMessage("Guardado correctamente");
        parent::save();
    }

    public function findOption($id)
    {

        if ($this->container->softclick->data) {
            foreach ($this->container->softclick->data as $opt) {
                if ($opt['id'] == $id) {
                    return $opt;
                }
            }
        }
        return null;

    }

    public function updateOption($id, $newValues)
    {

        if ($this->container->softclick->data) {
            foreach ($this->container->softclick->data as &$opt) {
                if ($opt['id'] == $id) {
                    $opt["page"] = $newValues["page"];
                    $opt["selector"] = $newValues["selector"];
                    $opt["med"] = $newValues["med"];
                    $opt["cat"] = $newValues["cat"];
                    $opt["event"] = $newValues["event"];
                    $opt["event_type"] = $newValues["event_type"];
                }
            }
        }


    }

    public function delete()
    {

        $index = 0;
        $found = false;
        if ($this->container->softclick->data) {
            foreach ($this->container->softclick->data as $opt) {
                if ($opt['id'] == $this->opt_id) {
                    $found = true;
                    break;
                }
                $index++;
            }
        }

        if ($found) {
            array_splice($this->container->softclick->data, $index, 1);
        }

        $this->addMessage("Eliminado correctamente");


        $deleted_url = add_query_arg(vcanly_composeFormParamsForSoftClick("site_vcanly_softclick", "deleted", null),
            admin_url('admin.php'));

        if (is_network_admin()) {
            $deleted_url = add_query_arg(vcanly_composeFormParamsForSoftClick("network_vcanly_softclick", "deleted",
                null), network_admin_url('admin.php'));

        }

        parent::save();

        header('Location: ' . $deleted_url);


    }

    public function redirectToList()
    {
        $list_url = add_query_arg(vcanly_composeFormParamsForSoftClick("site_vcanly_softclick", "", null),
            admin_url('options-general.php'));

        if (is_network_admin()) {
            $list_url = add_query_arg(vcanly_composeFormParamsForSoftClick("network_vcanly_softclick", "", null),
                network_admin_url('admin.php'));

        }


        header('Location: ' . $list_url);
    }

}