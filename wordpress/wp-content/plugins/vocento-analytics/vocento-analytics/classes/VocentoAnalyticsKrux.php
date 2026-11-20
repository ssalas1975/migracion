<?php

class VocentoAnalyticsKrux extends VocentoAnalyticsBase
{
    public $tags = array();

    public function __construct()
    {

        parent::__construct();

        //comScore
        $this->data["control_tag"] = "";

    }


}