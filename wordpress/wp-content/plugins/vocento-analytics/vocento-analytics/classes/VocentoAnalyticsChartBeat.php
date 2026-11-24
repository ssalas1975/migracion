<?php

class VocentoAnalyticsChartBeat extends VocentoAnalyticsBase
{

    public function __construct()
    {

        parent::__construct();

        //comScore
        $this->data["uid"] = "";
        $this->data["domain"] = "";
        $this->data["domain_auto"] = "";
        $this->data["useCanonical"] = "";
        $this->data["sections"] = "";
        $this->data["authors"] = "";

    }


}