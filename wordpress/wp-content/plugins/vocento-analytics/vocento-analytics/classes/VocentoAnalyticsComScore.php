<?php

class VocentoAnalyticsComScore extends VocentoAnalyticsBase
{

    public $ho_type = VocentoAnalyticsHOType::HOME_REF;

    public function __construct()
    {

        parent::__construct();

        //comScore
        $this->data["voc_site"] = "";
        $this->data["voc_site_auto"] = "";
        $this->data["voc_se"] = "";
        $this->data["voc_s1"] = "";
        $this->data["voc_s2"] = "";
        $this->data["voc_s3"] = "";
        $this->data["voc_s4"] = "";
        $this->data["voc_tn"] = "";
        $this->data["voc_ho"] = "";
        $this->data["voc_ed"] = "";
        $this->data["voc_au"] = "";
        $this->data["voc_fep"] = "";
        $this->data["voc_fem"] = "";
        $this->data["voc_or"] = "";
        $this->data["voc_pl"] = "";
        $this->data["voc_pr"] = "";
        $this->data["voc_tc"] = "";
        $this->data["voc_pu"] = "";
        $this->data["voc_ate"] = "";
        $this->data["voc_adp"] = "";
        $this->data["voc_abp"] = "";
        $this->data["voc_idi"] = "";
        $this->data["voc_name"] = "";
        $this->data["ns_http_status"] = "";


        //gigya
        $this->data["voc_usrid"] = "";
        $this->data["voc_tusr"] = "";
        $this->data["voc_usrag"] = "";
        $this->data["voc_usrcp"] = "";
        $this->data["voc_usrgen"] = "";
        $this->data["voc_usrser"] = "";
        $this->data["voc_usrsvs"] = "";

        //paywall
        $this->data["voc_pw_pid"] = "";
        $this->data["voc_pw_meter"] = "";
        $this->data["voc_pw_count"] = "";
        $this->data["voc_pw_max"] = "";
        $this->data["voc_pw_result"] = "";
        $this->data["voc_usrser"] = "";
        $this->data["voc_pw_cookie"] = "";


    }


}