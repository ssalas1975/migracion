<?php

class VocentoAnalyticsAdminBase
{

    //Opciones contenedor
    protected $options;

    //Opciones individuales
    protected $options_analytics;
    protected $options_analytics_config;
    protected $options_analytics_config_wp;
    protected $options_analytics_options;
    protected $options_analytics_softclick;

    //Variables generales
    public $messages = array();
    public $title = "";
    public $form_url = "";
    public $container;

    /**
     * Start up
     */
    public function __construct($title)
    {


        //Titulo de página
        $this->title = $title;

        //Set options
        if (vcanly_is_active_network() && is_network_admin()) {
            $this->options = get_site_option('vocentovcanly_options');
        } else {
            $this->options = get_option('vocentovcanly_options');
        }

        $this->options_analytics = $this->options['vocentovcanly_options_analytics'];
        $this->options_analytics_config = $this->options['vocentovcanly_options_analytics_config'];
        $this->options_analytics_config_wp = $this->options['vocentovcanly_options_analytics_config_wp'];
        $this->options_analytics_options = $this->options['vocentovcanly_options_analytics_options'];

        //Set defaults
        $this->options_analytics["vcanly_commscore_use_network"] = $this->get_default_network($this->options_analytics["vcanly_commscore_use_network"]);
        $this->options_analytics["vcanly_chartbeat_use_network"] = $this->get_default_network($this->options_analytics["vcanly_chartbeat_use_network"]);
        $this->options_analytics["vcanly_gtm_use_network"] = $this->get_default_network($this->options_analytics["vcanly_gtm_use_network"]);
        $this->options_analytics["vcanly_ga_use_network"] = $this->get_default_network($this->options_analytics["vcanly_ga_use_network"]);
        $this->options_analytics["vcanly_amp_use_network"] = $this->get_default_network($this->options_analytics["vcanly_amp_use_network"]);
        $this->options_analytics["vcanly_egg_use_network"] = $this->get_default_network($this->options_analytics["vcanly_egg_use_network"]);
        $this->options_analytics["vcanly_softclick_use_network"] = $this->get_default_network($this->options_analytics["vcanly_softclick_use_network"]);
        $this->options_analytics["vcanly_inspectlet_use_network"] = $this->get_default_network($this->options_analytics["vcanly_inspectlet_use_network"]);
        $this->options_analytics["vcanly_krux_use_network"] = $this->get_default_network($this->options_analytics["vcanly_krux_use_network"]);


        if (!is_array($this->options_analytics_options)) {
            $this->options_analytics_options = array();
        }

        //definimos contenedor
        $this->setContainer($this->options_analytics_config);

    }


    function setContainer($json)
    {
        if ($json) {
            $this->container = VocentoAnalyticsContainer::deserialize($json);
        } else {
            $this->container = new VocentoAnalyticsContainer();
        }
    }


    function addErrorMessage($error)
    {
        array_push($this->messages, array('class' => 'notice-error', 'msg' => $error));
    }


    function addMessage($mesage)
    {
        array_push($this->messages, array('class' => 'notice-success', 'msg' => $mesage));
    }


    function displayMessages()
    {
        foreach ($this->messages as $msg) {
            echo "<div class='notice  " . $msg['class'] . "'>" . $msg['msg'] . "</div>";
        }
    }

    public function render_page($render_links = true)
    {
        ?>

        <script>
            jQuery(document).ready(function () {
                if (jQuery(".usenetwork").is(":checked")) {
                    jQuery(".localdata").hide();
                }
                else {
                    jQuery(".localdata").show();
                }
                jQuery(".usenetwork").change(function () {
                    if (jQuery(this).is(":checked")) {
                        jQuery(".localdata").hide();
                    }
                    else
                        jQuery(".localdata").show();
                });


                jQuery('input[type=checkbox]', '.vcanly-fields.localdata').not('#vcanly_commscore_voc_site_auto').not("#vcanly_chartbeat_domain_auto").switchable();

            });
        </script>

        <h2>Vocento Analytics: <span> <?php echo $this->title ?> </span></h2>
        <?php
        if ($render_links) {
            $this->print_links_settings();
        }
        ?>

        <br/>
        <br/>

        <?php
        $this->displayMessages($this);

        //if(is_network_admin()){
        //    $this->form_url = add_query_arg(vcanly_composeFormParams("network_vcanly_commscore","",""), network_admin_url('admin.php'));
        //}
        //else{
        //    $this->form_url = add_query_arg(vcanly_composeFormParams("site_vcanly_commscore","",""), admin_url('admin.php'));
        //}

    }

    function get_default_network($value)
    {

        if ($value === true) {
            return true;
        }

        return (($value === null) ? true : false);
    }

    function print_links_settings()
    {
        if (is_network_admin()) {
            ?>
            <ul class="vcanly-miga-settings">
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_options", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. Opciones</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_comscore", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. comScore</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_ga", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. GA</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_gtm", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. GTM</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_amp", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. AMP</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_chartbeat", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. ChartBeat</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_crazyegg", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. Crazy Egg</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_softclick", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. SoftClick / GA Events</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_inspectlet", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. Inspectlet</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_krux", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. Krux</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("network_vcanly_wordpress", "", ""),
                        network_admin_url('admin.php')); ?>">Conf. Wordpress</a>
            </ul>
            <?php
        } else {
            ?>
            <ul class="vcanly-miga-settings">

                <?php if (!vcanly_is_active_network()) { ?>

                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_options", "", ""),
                        admin_url('admin.php')); ?>">Conf. Opciones</a>

                    <?php } ?>

                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_comscore", "", ""),
                        admin_url('admin.php')); ?>">Conf. comScore</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_ga", "", ""),
                        admin_url('admin.php')); ?>">Conf. GA</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_gtm", "", ""),
                        admin_url('admin.php')); ?>">Conf. GTM</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_amp", "", ""),
                        admin_url('admin.php')); ?>">Conf. AMP</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_chartbeat", "", ""),
                        admin_url('admin.php')); ?>">Conf. ChartBeat</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_crazyegg", "", ""),
                        admin_url('admin.php')); ?>">Conf. Crazy Egg</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_softclick", "", ""),
                        admin_url('admin.php')); ?>">Conf. SoftClick / GA Events</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_inspectlet", "", ""),
                        admin_url('admin.php')); ?>">Conf. Inspectlet</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_krux", "", ""),
                        admin_url('admin.php')); ?>">Conf. Krux</a>
                <li><a href="<?php echo add_query_arg(vcanly_composeFormParams("site_vcanly_wordpress", "", ""),
                        admin_url('admin.php')); ?>">Conf. Wordpress</a>
            </ul>
            <?php
        }
    }

    public function save()
    {

        $this->options['vocentovcanly_options_analytics'] = $this->options_analytics;
        $this->options['vocentovcanly_options_analytics_config'] = $this->container->serialize();
        $this->options['vocentovcanly_options_analytics_config_wp'] = $this->options_analytics_config_wp;
        $this->options['vocentovcanly_options_analytics_options'] = $this->options_analytics_options;
        // $this->options['vocentovcanly_options_analytics_softclick']=  $this->options_analytics_softclick;
        if (is_network_admin()) {
            update_site_option('vocentovcanly_options', $this->options, true);
        } else {
            update_option('vocentovcanly_options', $this->options, true);
        }
    }


}