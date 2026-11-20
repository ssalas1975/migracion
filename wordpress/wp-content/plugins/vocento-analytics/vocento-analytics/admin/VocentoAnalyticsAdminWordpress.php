<?php

class VocentoAnalyticsAdminWordpress extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Conf. Wordpress");

        //set inicial de red
        if ($this->options_analytics["vcanly_wp_use_network"] === null) {
            $this->options_analytics["vcanly_wp_use_network"] = true;
        }

        if (isset($_POST['submit-vcanly-tmpl'])) {
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
                <div class="vcanly-fields form-wrapper wordpress">

                    <?php

                    if (vcanly_is_active_network() && !is_network_admin()) { ?>

                        <div class="vcanly-fields" style="border: 0px;">
                            <div class="vcanly_gtm_fields">
                                <h3>Datos de red</h3>
                                <hr/>
                                <label for="vcanly_name">Utilizar datos de la red</label>
                                <input class="usenetwork" style="margin-left:30px;" type="checkbox"
                                       name="vcanly_wp_use_network" id="vcanly_wp_use_network"
                                       value="true" <?php echo ($this->options_analytics_config_wp['vcanly_wp_use_network']) ? "checked" : "" ?> /><br/>
                                <br/>

                            </div>
                        </div>

                    <?php } ?>


                    <div class="vcanly-fields localdata" style="display: none;">
                        <h3>Páginas de Wordpress</h3>
                        <div class="vcanly_info">
                            Desde esta página podrá configurar las variables <b>voc_tc</b>, <b>voc_se</b> , y
                            <b>voc_tn</b> de algunas de las páginas más comunes de Wordpress.<br/>
                            En caso de no rellenar algun grupo se utilizarán las de la red.<br/>
                            En caso de rellenar algun valor de uno de los grupos se utilizarán los valores definidos
                            para ese grupo en esta pantalla (incluyendo campos vacíos)
                        </div>
                        <br/>
                        <br/>

                        <div class="vcanly_gtm_fields">
                            <?php if (is_network_admin()) { ?>
                                <label for="vcanly_tmpl_category">Página de home main site</label>
                                <div class="vcanly-fields-group three">
                                    <div class="vcanly-field-group">
                                        <label for="vcanly_tmpl_home_tn">voc_tn</label>
                                        <input type="text" placeholder="portada" name="vcanly_tmpl_home_tn"
                                               id="vcanly_tmpl_home_tn"
                                               value="<?php echo $this->options_analytics_config_wp['home_ms']['tn'] ?>"/><br/>
                                    </div>
                                    <div class="vcanly-field-group">
                                        <label for="vcanly_tmpl_home_tc">voc_tc</label>
                                        <input type="text" placeholder="portada" name="vcanly_tmpl_home_tc"
                                               id="vcanly_tmpl_home_tc"
                                               value="<?php echo $this->options_analytics_config_wp['home_ms']['tc'] ?>"/><br/>
                                    </div>
                                    <div class="vcanly-field-group">
                                        <label for="vcanly_tmpl_home_se">voc_se</label>
                                        <input type="text" placeholder="portada" name="vcanly_tmpl_home_se"
                                               id="vcanly_tmpl_home_se"
                                               value="<?php echo $this->options_analytics_config_wp['home_ms']['se'] ?>"/><br/>
                                    </div>

                                </div>
                                <br/>
                                <br/>
                            <?php } ?>
                            <label for="vcanly_tmpl_category">Página de home blog</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_blog_tn">voc_tn</label>
                                    <input type="text" placeholder="portada" name="vcanly_tmpl_home_blog_tn"
                                           id="vcanly_tmpl_home_blog_tn"
                                           value="<?php echo $this->options_analytics_config_wp['home_blog']['tn'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_blog_tc">voc_tc</label>
                                    <input type="text" placeholder="portada" name="vcanly_tmpl_home_blog_tc"
                                           id="vcanly_tmpl_home_blog_tc"
                                           value="<?php echo $this->options_analytics_config_wp['home_blog']['tc'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_home_blog_se">voc_se</label>
                                    <input type="text" placeholder="portada" name="vcanly_tmpl_home_blog_se"
                                           id="vcanly_tmpl_home_blog_se"
                                           value="<?php echo $this->options_analytics_config_wp['home_blog']['se'] ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>
                            <label for="vcanly_tmpl_category">Página de categoría</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tn</label>
                                    <input type="text" placeholder="portadilla" name="vcanly_tmpl_category_tn"
                                           id="vcanly_tmpl_category_tn"
                                           value="<?php echo $this->options_analytics_config_wp['category']['tn'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tc</label>
                                    <input type="text" placeholder="portada" name="vcanly_tmpl_category_tc"
                                           id="vcanly_tmpl_category_tc"
                                           value="<?php echo $this->options_analytics_config_wp['category']['tc'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_se</label>
                                    <input type="text" placeholder="" name="vcanly_tmpl_category_se"
                                           id="vcanly_tmpl_category_se"
                                           value="<?php echo $this->options_analytics_config_wp['category']['se'] ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>

                            <label for="vcanly_tmpl_category">Página de archivo</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tn</label>
                                    <input type="text" placeholder="portadilla" name="vcanly_tmpl_archive_tn"
                                           id="vcanly_tmpl_archive_tn"
                                           value="<?php echo $this->options_analytics_config_wp['archive']['tn'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tc</label>
                                    <input type="text" placeholder="portada" name="vcanly_tmpl_archive_tc"
                                           id="vcanly_tmpl_archive_tc"
                                           value="<?php echo $this->options_analytics_config_wp['archive']['tc'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_se</label>
                                    <input type="text" name="vcanly_tmpl_archive_se" id="vcanly_tmpl_archive_se"
                                           value="<?php echo $this->options_analytics_config_wp['archive']['se'] ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>
                            <label for="vcanly_tmpl_category">Página de tags</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tn</label>
                                    <input type="text" placeholder="portadilla" name="vcanly_tmpl_tags_tn"
                                           id="vcanly_tmpl_tags_tn"
                                           value="<?php echo $this->options_analytics_config_wp['tag']['tn'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tc</label>
                                    <input type="text" placeholder="portada" name="vcanly_tmpl_tags_tc"
                                           id="vcanly_tmpl_tags_tc"
                                           value="<?php echo $this->options_analytics_config_wp['tag']['tc'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_se</label>
                                    <input type="text" name="vcanly_tmpl_tags_se" id="vcanly_tmpl_tags_se"
                                           value="<?php echo $this->options_analytics_config_wp['tag']['se'] ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>
                            <label for="vcanly_tmpl_category">Página de búsquedas</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tn</label>
                                    <input type="text" name="vcanly_tmpl_search_tn" id="vcanly_tmpl_search_tn"
                                           value="<?php echo $this->options_analytics_config_wp['search']['tn'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tc</label>
                                    <input type="text" name="vcanly_tmpl_search_tc" id="vcanly_tmpl_search_tc"
                                           value="<?php echo $this->options_analytics_config_wp['search']['tc'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_se</label>
                                    <input type="text" name="vcanly_tmpl_search_se" id="vcanly_tmpl_search_se"
                                           value="<?php echo $this->options_analytics_config_wp['search']['se'] ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>
                            <label for="vcanly_tmpl_category">Página de autores</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tn</label>
                                    <input type="text" name="vcanly_tmpl_author_tn" id="vcanly_tmpl_author_tn"
                                           value="<?php echo $this->options_analytics_config_wp['author']['tn'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tc</label>
                                    <input type="text" name="vcanly_tmpl_author_tc" id="vcanly_tmpl_author_tc"
                                           value="<?php echo $this->options_analytics_config_wp['author']['tc'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_se</label>
                                    <input type="text" name="vcanly_tmpl_author_se" id="vcanly_tmpl_author_se"
                                           value="<?php echo $this->options_analytics_config_wp['author']['se'] ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>
                            <label for="vcanly_tmpl_category">Página de 404</label>
                            <div class="vcanly-fields-group three">
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tn</label>
                                    <input type="text" name="vcanly_tmpl_404_tn" id="vcanly_tmpl_404_tn"
                                           value="<?php echo $this->options_analytics_config_wp['404']['tn'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_tc</label>
                                    <input type="text" name="vcanly_tmpl_404_tc" id="vcanly_tmpl_404_tc"
                                           value="<?php echo $this->options_analytics_config_wp['404']['tc'] ?>"/><br/>
                                </div>
                                <div class="vcanly-field-group">
                                    <label for="vcanly_tmpl_category_tn">voc_se</label>
                                    <input type="text" name="vcanly_tmpl_404_se" id="vcanly_tmpl_404_se"
                                           value="<?php echo $this->options_analytics_config_wp['404']['se'] ?>"/><br/>
                                </div>

                            </div>
                            <br/>
                            <br/>


                        </div>
                    </div>

                </div>

                <?php submit_button('Guardar', 'button button-primary', 'submit-vcanly-tmpl'); ?>

            </form>


        </div>

        <script>

            function refreshPlaceHolders() {

                jQuery('.vcanly-fields-group').each(function (i, val) {
                    $group = jQuery(val);
                    var groupHasValue = false;
                    jQuery('input', $group).each(function (j, inpt) {
                        if (jQuery(inpt).val() != "") {
                            groupHasValue = true;
                            return false;
                        }
                    });

                    if (groupHasValue) {
                        jQuery('input', $group).attr("placeholder", "");
                    }
                    else {
                        <?php if(vcanly_is_active_network() && !is_network_admin()) {?>
                        jQuery('input', $group).attr("placeholder", "Dato configurado en la red");
                        <?php } ?>
                    }
                });
            }

            jQuery(document).ready(function () {

                refreshPlaceHolders();

                jQuery('input', '.vcanly-fields-group').blur(function () {
                    refreshPlaceHolders();
                });


            });
        </script>


        <?php
    }


    public function save()
    {
        $this->options_analytics_config_wp["vcanly_wp_use_network"] = isset($_POST["vcanly_wp_use_network"]);

        //Home main site
        $this->options_analytics_config_wp['home_ms'] = array();
        $this->options_analytics_config_wp['home_ms']['tc'] = $_POST["vcanly_tmpl_home_tc"];
        $this->options_analytics_config_wp['home_ms']['tn'] = $_POST["vcanly_tmpl_home_tn"];
        $this->options_analytics_config_wp['home_ms']['se'] = $_POST["vcanly_tmpl_home_se"];
        //Home blog site
        $this->options_analytics_config_wp['home_blog'] = array();
        $this->options_analytics_config_wp['home_blog']['tc'] = $_POST["vcanly_tmpl_home_blog_tc"];
        $this->options_analytics_config_wp['home_blog']['tn'] = $_POST["vcanly_tmpl_home_blog_tn"];
        $this->options_analytics_config_wp['home_blog']['se'] = $_POST["vcanly_tmpl_home_blog_se"];
        //Home category
        $this->options_analytics_config_wp['category'] = array();
        $this->options_analytics_config_wp['category']['tc'] = $_POST["vcanly_tmpl_category_tc"];
        $this->options_analytics_config_wp['category']['tn'] = $_POST["vcanly_tmpl_category_tn"];
        $this->options_analytics_config_wp['category']['se'] = $_POST["vcanly_tmpl_category_se"];
        //Home archive
        $this->options_analytics_config_wp['archive'] = array();
        $this->options_analytics_config_wp['archive']['tc'] = $_POST["vcanly_tmpl_archive_tc"];
        $this->options_analytics_config_wp['archive']['tn'] = $_POST["vcanly_tmpl_archive_tn"];
        $this->options_analytics_config_wp['archive']['se'] = $_POST["vcanly_tmpl_archive_se"];
        //Home tag
        $this->options_analytics_config_wp['tag'] = array();
        $this->options_analytics_config_wp['tag']['tc'] = $_POST["vcanly_tmpl_tags_tc"];
        $this->options_analytics_config_wp['tag']['tn'] = $_POST["vcanly_tmpl_tags_tn"];
        $this->options_analytics_config_wp['tag']['se'] = $_POST["vcanly_tmpl_tags_se"];
        //Home search
        $this->options_analytics_config_wp['search'] = array();
        $this->options_analytics_config_wp['search']['tc'] = $_POST["vcanly_tmpl_search_tc"];
        $this->options_analytics_config_wp['search']['tn'] = $_POST["vcanly_tmpl_search_tn"];
        $this->options_analytics_config_wp['search']['se'] = $_POST["vcanly_tmpl_search_se"];
        //Home search
        $this->options_analytics_config_wp['author'] = array();
        $this->options_analytics_config_wp['author']['tc'] = $_POST["vcanly_tmpl_author_tc"];
        $this->options_analytics_config_wp['author']['tn'] = $_POST["vcanly_tmpl_author_tn"];
        $this->options_analytics_config_wp['author']['se'] = $_POST["vcanly_tmpl_author_se"];
        //Home 404
        $this->options_analytics_config_wp['404'] = array();
        $this->options_analytics_config_wp['404']['tc'] = $_POST["vcanly_tmpl_404_tc"];
        $this->options_analytics_config_wp['404']['tn'] = $_POST["vcanly_tmpl_404_tn"];
        $this->options_analytics_config_wp['404']['se'] = $_POST["vcanly_tmpl_404_se"];

        $this->addMessage("Guardado correctamente");
        parent::save();
    }


}