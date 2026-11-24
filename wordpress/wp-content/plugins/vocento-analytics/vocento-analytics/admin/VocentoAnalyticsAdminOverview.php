<?php

class VocentoAnalyticsAdminOverview extends VocentoAnalyticsAdminBase
{


    /**
     * Start up
     */
    public function __construct()
    {
        //Definimos padre
        parent::__construct("Estado de configuración");


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


                    <div class="vcanly-fields localdata" style="display: none;">
                        <h3>Estado de la configuración de analytics</h3>
                        <?php
                        if (is_network_admin()) {
                            $front_container = VocentoAnalyticsContainer::getContainer(true);
                        } else {
                            $front_container = VocentoAnalyticsContainer::getFrontContainer();
                        }

                        $this->printRow("Comscore", ($this->options_analytics["vcanly_commscore_use_network"]),
                            $front_container->comScore->enable);
                        $this->printRow("Chartbeat", ($this->options_analytics["vcanly_chartbeat_use_network"]),
                            $front_container->chartbeat->enable);
                        $this->printRow("GTM", ($this->options_analytics["vcanly_gtm_use_network"]),
                            $front_container->gtm->enable);
                        $this->printRow("GA", ($this->options_analytics["vcanly_ga_use_network"]),
                            $front_container->ga->enable);
                        $this->printRow("AMP", ($this->options_analytics["vcanly_amp_use_network"]),
                            $front_container->amp->enable);
                        $this->printRow("CrazyEgg", ($this->options_analytics["vcanly_egg_use_network"]),
                            $front_container->crazyegg->enable);
                        $this->printRow("SoftClick", ($this->options_analytics["vcanly_softclick_use_network"]),
                            $front_container->softclick->enable);
                        $this->printRow("Inspectlet", ($this->options_analytics["vcanly_inspectlet_use_network"]),
                            $front_container->inspectlet->enable);
                        $this->printRow("DTM", ($this->options_analytics["vcanly_gtm_use_network"]),
                            $front_container->dtm->enable);

                        if (is_network_admin()) {
                            $cutomBlogsConfig = vcanly_custom_analytics_config_overview();
                            if (is_array($cutomBlogsConfig) && count($cutomBlogsConfig)) {
                                ?>
                                <br/>
                                <br/>
                                <div style="float:left; width:100%;">
                                <h3>Blogs con configuración propia</h3>

                                <div class="vcanly_info">
                                    Estos blogs han sobreescrito una o más configuraciones de red.
                                </div>
                                <br/>

                                <?php
                                foreach ($cutomBlogsConfig as $blog) {
                                    $this->printRowCustomConfigBlog($blog);
                                }

                            }

                            ?>
                            </div>

                            <?php

                        }
                        ?>

                    </div>
                </div>


            </form>


        </div>


        <?php
    }


    function printRow($name, $network, $status)
    {


        $icon = 'active.png';
        if (!$status) {
            $icon = 'inactive.png';
        }
        $icon = plugins_url('/vocento-analytics/assets/img/' . $icon);

        $iconetwork = 'network.png';
        if ((($network === false || $network === null || !vcanly_is_active_network()) && $this->options_analytics) && !is_network_admin()) {
            $iconetwork = 'local.png';
        }


        $iconetwork = plugins_url('/vocento-analytics/assets/img/' . $iconetwork);


        echo "<div class='vcanly-net-status " . (($status) ? "active" : "") . "'><div class='vcanly-net-name'>" . $name . "</div><div class='vcanly-net-nw'><img src='$iconetwork'/></div><div class='vcanly-net-active' ><img src='$icon'/></div></div>";
    }

    function printRowCustomConfigBlog($blog)
    {

        echo "<div class='vcanly-net-status'><div class='vcanly-net-name'>" . get_blog_option($blog['blog_id'],
                'blogname') . "</div><div class='vcanly-net-nw'></div><div class='vcanly-net-active blog-config' ><a class='button button-primary' href='" . get_admin_url($blog['blog_id']) . "admin.php?page=site_vcanly_overview'>Ver estado local</a></div></div>";
    }


}