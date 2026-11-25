<?php
/**
 * VC_CMSAPI_Video_CreateContainer short summary.
 *
 * VC_CMSAPI_Video_CreateContainer description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VC_CMSAPI_Video_CreateContainer
{

    public $name;	

    public $description;	

    public $long_description; 	

    public $economics;	

    public $reference_id;	

    public $state;

    /**
     * @var Array
     */
    public $tags;	

    public $custom_fields; 

    /**
     * @var VC_CMSAPI_Video_Geo
     */
    public $geo;

    /**
     * @var VC_CMSAPI_Video_Link
     */
    public $link;

    public $schedule;	

    public $starts_at;	

    public $ends_at;

    public $src;

    public $kind;

    public $srclang;	

    public $mime_type;	

    public $label;	

    public $default;

    public function __construct(){
        
        $this->link=new VC_CMSAPI_Video_Link();
    }
}