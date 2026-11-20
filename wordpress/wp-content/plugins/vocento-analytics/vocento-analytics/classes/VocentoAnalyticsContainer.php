<?php

class VocentoAnalyticsContainer
{

    public $comScore;
    public $ga;
    public $gtm;
    public $chartbeat;
    public $amp;
    public $crazyegg;
    public $softclick;
    public $inspectlet;
    public $krux;
    public $dtm;

    public function __construct()
    {

        //set 
        $this->comScore = new VocentoAnalyticsComScore();
        $this->chartbeat = new VocentoAnalyticsChartBeat();
        $this->ga = new VocentoAnalyticsGA();
        $this->gtm = new VocentoAnalyticsGTM();
        $this->amp = new VocentoAnalyticsAMP();
        $this->crazyegg = new VocentoAnalyticsCrazyEgg();
        $this->softclick = new VocentoAnalyticsSoftClick();
        $this->inspectlet = new VocentoAnalyticsInspectlet();
        $this->krux = new VocentoAnalyticsKrux();
        $this->dtm = new VocentoAnaliticsDTM();
    }

    public static function deserialize($json)
    {

        $vac = new VocentoAnalyticsContainer();
        $vac = unserialize($json);
        return $vac;

    }

    public function serialize()
    {

        return serialize($this);

    }

    public function toJSON()
    {

        return json_encode(get_object_vars($this));

    }

    public static function getAdminContainer()
    {

        $options = [];


        if (is_network_admin()) {
            $options = get_site_option('vocentovcanly_options');
        } else {
            $options = get_option('vocentovcanly_options');
        }

        $options_analytics_config = $options['vocentovcanly_options_analytics_config'];


        if ($options_analytics_config) {
            return VocentoAnalyticsContainer::deserialize($options_analytics_config);
        } else {
            return new VocentoAnalyticsContainer();
        }

    }

    public static function getContainer($network)
    {

        $options = [];


        if ($network) {
            $options = get_site_option('vocentovcanly_options');
        } else {
            $options = get_option('vocentovcanly_options');
        }

        $options_analytics_config = $options['vocentovcanly_options_analytics_config'];


        if ($options_analytics_config) {
            return VocentoAnalyticsContainer::deserialize($options_analytics_config);
        } else {
            return new VocentoAnalyticsContainer();
        }

    }

    public static function getFrontContainer()
    {


        $localContainer = VocentoAnalyticsContainer::getContainer(false);
        $networkContainer = VocentoAnalyticsContainer::getContainer(true);


        $options_local = get_option('vocentovcanly_options');
        $options_analytics_opts = $options_local["vocentovcanly_options_analytics"];


        if ($options_analytics_opts["vcanly_commscore_use_network"] || $options_analytics_opts["vcanly_commscore_use_network"] === null) {
            $localContainer->comScore = ($networkContainer->comScore) ? $networkContainer->comScore : new VocentoAnalyticsComScore();
        }

        if ($options_analytics_opts["vcanly_chartbeat_use_network"] || $options_analytics_opts["vcanly_chartbeat_use_network"] === null) {
            $localContainer->chartbeat = ($networkContainer->chartbeat) ? $networkContainer->chartbeat : new VocentoAnalyticsChartBeat();
        }

        if ($options_analytics_opts["vcanly_ga_use_network"] || $options_analytics_opts["vcanly_ga_use_network"] === null) {
            $localContainer->ga = ($networkContainer->ga) ? $networkContainer->ga : new VocentoAnalyticsGA();
        }

        if ($options_analytics_opts["vcanly_gtm_use_network"] || $options_analytics_opts["vcanly_gtm_use_network"] === null) {
            $localContainer->gtm = ($networkContainer->gtm) ? $networkContainer->gtm : new VocentoAnalyticsGTM();
        }
        
        if ($options_analytics_opts["vcanly_dtm_use_network"] || $options_analytics_opts["vcanly_dtm_use_network"] === null) {
            $localContainer->dtm = ($networkContainer->dtm) ? $networkContainer->dtm : new VocentoAnaliticsDTM();
        }

        if ($options_analytics_opts["vcanly_amp_use_network"] || $options_analytics_opts["vcanly_amp_use_network"] === null) {
            $localContainer->amp = ($networkContainer->amp) ? $networkContainer->amp : new VocentoAnalyticsAMP();
        }

        if ($options_analytics_opts["vcanly_egg_use_network"] || $options_analytics_opts["vcanly_egg_use_network"] === null) {
            $localContainer->crazyegg = ($networkContainer->crazyegg) ? $networkContainer->crazyegg : new VocentoAnalyticsCrazyEgg();
        }

        if ($options_analytics_opts["vcanly_softclick_use_network"] || $options_analytics_opts["vcanly_softclick_use_network"] === null) {
            $localContainer->softclick = ($networkContainer->softclick) ? $networkContainer->softclick : new VocentoAnalyticsSoftClick();
        }

        if ($options_analytics_opts["vcanly_inspectlet_use_network"] || $options_analytics_opts["vcanly_inspectlet_use_network"] === null) {
            $localContainer->inspectlet = ($networkContainer->inspectlet) ? $networkContainer->inspectlet : new VocentoAnalyticsInspectlet();
        }

        if ($options_analytics_opts["vcanly_krux_use_network"] || $options_analytics_opts["vcanly_krux_use_network"] === null) {
            $localContainer->krux = ($networkContainer->krux) ? $networkContainer->krux : new VocentoAnalyticsKrux();
        }

        return $localContainer;


    }

}