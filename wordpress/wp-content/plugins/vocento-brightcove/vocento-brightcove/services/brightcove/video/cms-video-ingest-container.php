<?php
/**
 * VC_CMSAPI_Video_IngestContainer short summary.
 *
 * VC_CMSAPI_Video_IngestContainer description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VC_CMSAPI_Video_IngestContainer
{

    /**
     * @var VC_CMSAPI_Video_Master
     */
    public $master;  
    public $profile;
    public $capture_images;
    /**
     * @var VC_CMSAPI_Video_Image
     */
    public $poster;
    /**
     * @var VC_CMSAPI_Video_Image
     */
    public $thumbnail;
    public $text_tracks;
    /**
     * @var array
     */
    public $callbacks;


    public function __construct(){
        
        $this->master= new VC_CMSAPI_Video_Master();
    }
}