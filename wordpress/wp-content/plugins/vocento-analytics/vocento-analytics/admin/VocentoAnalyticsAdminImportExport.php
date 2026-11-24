<?php

class VocentoAnalyticsAdminImportExport extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */

    protected $options_page;
    protected $json_to_export;

    public function __construct()
    {


        parent::__construct("Import/Export");

        if (vcanly_is_active_network() && is_network_admin()) {
            $this->options_page = get_site_option('vocentovcanly_options');
        } else {
            $this->options_page = get_option('vocentovcanly_options');
        }


        //if($this->options_page['vocentovcanly_options_analytics_config']){
        //    $this->options_page['vocentovcanly_options_analytics_config']=VocentoAnalyticsContainer::deserialize( $this->options_page['vocentovcanly_options_analytics_config']);
        //}


        $this->json_to_export = json_encode($this->options_page);

        if (isset($_POST['submit-vcanly-import'])) {
            $this->save();
        }

    }

    public function render_page($render_links = true)
    {
        // Set class property

        ?>
        <div class="wrap theme-vcanly">

            <?php

            //Menús de navegacion, titulos, etc..
            parent::render_page();

            ?>

            <div class="vcanly-fields form-wrapper">

                <div class="vcanly-fields localdata">
                    <h3>Import/Export</h3>
                    <br/>

                    <div class="vcanly_info">
                        Desde esta pantalla puede importar o exportar la configuración actual de analítica.
                        Seleccione el código de la caja "exportar" y peguelo en la caja de "importar" en el site donde
                        quiera importar las opciones.
                    </div>

                    <br/>

                    <form method="post" action="<?php echo $this->form_url ?>" enctype="multipart/form-data"
                          class="vcanly_comscore_form">

                        <p><b>Código para exportar</b></p>
                        <textarea name="vcanly_export" id="vcanly_export" style="height:500px; width: 100%;"
                                  readonly><?php echo $this->json_to_export; ?></textarea>


                        <p><b>Código para importar</b></p>
                        <textarea name="vcanly_import" id="vcanly_import" style="height:500px; width: 100%;"></textarea>


                        <?php submit_button('Importar', 'button button-primary', 'submit-vcanly-import'); ?>

                    </form>

                </div>
            </div>

        </div>
        <?php
    }


    public function save()
    {
        $data = $_POST['vcanly_import'];
        $data = stripcslashes($data);

        $temp_options = json_decode($data, true);
        if ($temp_options && $temp_options != null) {

            $this->options = $temp_options;

            if (is_network_admin()) {
                update_site_option('vocentovcanly_options', $this->options);
            } else {
                update_option('vocentovcanly_options', $this->options, true);
            }

            $this->addMessage("Importado correctamente");
        } else {
            $this->addErrorMessage("Los datos no son válidos");
        }
    }


}