<?php

class VocentoAnalyticsBase
{

    public $data = array();
    public $enable = false;


    public function __construct()
    {

        $this->data = [];
    }

    public function toJSON()
    {
        return json_encode($this->expose());
    }

    public function expose()
    {
        return get_object_vars($this);
    }

}