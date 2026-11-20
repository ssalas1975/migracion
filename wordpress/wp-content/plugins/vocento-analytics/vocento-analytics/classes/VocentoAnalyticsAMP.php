<?php

class VocentoAnalyticsAMP extends VocentoAnalyticsBase
{
    public $templates = array();

    public $includeAll = false;

    public $includeOnlyAMPInPLugin = false;

    public function __construct()
    {

        parent::__construct();


        $this->data["ID"] = "";

        $this->templates = array();


    }


}