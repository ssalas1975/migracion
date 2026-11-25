<?php

/**
 * VC_CMSAPI_Video short summary.
 *
 * VC_CMSAPI_Video description.
 *
 * @version 1.0
 * @author j.godoy
 */

class VC_CMSAPI_Video{
	
	
    public static function READ($accountid,$limit,$offset,$query,$sort,$medioid){
    
        //clean query
        $query=str_replace(" ",".",$query);

        $url="/accounts/$accountid/videos?";

        if($query)
            $url.="&q=".$query;

        if($limit)
            $url.="&limit=".$limit;

        if($offset)
            $url.="&offset=".$offset;

        if($sort)
            $url.="&sort=".$sort;

        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"GET",$medioid);

    }

    /**
     * Summary of CREATE
     * @param mixed $accountid 
     * @param VC_CMSAPI_Video_CreateContainer $video_create_container 
     * @return stdClass
     */
    public static function CREATE($accountid,$video_create_container,$medioid){
        
        $url="/accounts/$accountid/videos";
        
        //Limpiamos NULLS antes de mandar
        $arrayVCC=(array)$video_create_container;
        $arrayVCC = (object)array_filter((array) $arrayVCC);
        $arrayVCC = json_encode($arrayVCC);

        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,$arrayVCC,$accountid,"POST",$medioid);

    }

    /**
     * Summary of CREATE_AND_UPLOAD
     * @param mixed $accountid 
     * @param VC_CMSAPI_Video_CreateContainer $video_create_container 
     * @return stdClass
     */
    public static function CREATE_AND_UPLOAD($accountid,$video_create_container,$video_url,$medioid){
        
        $url="/accounts/$accountid/videos";
        
        //Limpiamos NULLS antes de mandar
        $arrayVCC=(array)$video_create_container;
        $arrayVCC = (object)array_filter((array) $arrayVCC);
        $arrayVCC = json_encode($arrayVCC);
        //creamos el video
        $video=VC_BCAPI_Base::MAKE_CMS_REQUEST($url,$arrayVCC,$accountid,"POST",$medioid);

        if($video && $video->id)
        {
            $videoIC= new VC_CMSAPI_Video_IngestContainer();
            $videoIC->master->url=$video_url;
            return VC_CMSAPI_Video::INGEST($accountid, $video->id,$videoIC,$medioid);
        }

        return null;
    }

    /**
     * Summary of INGEST
     * @param mixed $accountid 
     * @param mixed $video_id 
     * @param VC_CMSAPI_Video_IngestContainer $video_ingest_container 
     * @return stdClass
     */
    public static function INGEST($accountid,$video_id,$video_ingest_container,$medioid){
        
        $url="/accounts/$accountid/videos/$video_id/ingest-requests";
        
        //Limpiamos NULLS antes de mandar
        $arrayVIC=(array)$video_ingest_container;
        $arrayVIC = (object)array_filter((array) $arrayVIC);
        $arrayVIC = json_encode($arrayVIC);

        return VC_BCAPI_Base::MAKE_INGEST_REQUEST($url,$arrayVIC,$accountid,"POST",$medioid);

    }

    /**
     * Summary of UPDATE
     * @param mixed $accountid 
     * @param mixed $video_id 
     * @param VC_CMSAPI_Video_CreateContainer $video_create_container 
     * @return stdClass
     */
    public static function UPDATE($accountid,$video_id,$video_create_container,$medioid){
        
        $url="/accounts/$accountid/videos/$video_id";
        
        //Limpiamos NULLS antes de mandar
        $arrayVCC=(array)$video_create_container;
        $arrayVCC = (object)array_filter((array) $arrayVCC);
        $arrayVCC = json_encode($arrayVCC);

        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,$arrayVCC,$accountid,"PATCH",$medioid);

    }

    public static function DELETE($accountid,$video_id,$medioid){
        $url="/accounts/$accountid/videos/$video_id";
        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"DELETE",$medioid);
    }
	
    public static function COUNT($accountid,$query,$sort,$medioid){
        
        //clean query
        $query=str_replace(" ",".",$query);

        $url="/accounts/$accountid/counts/videos?";

        if($query)
            $url.="&q=".$query;

        if($sort)
            $url.="&sort=".$sort;

        $countresponse= VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"GET",$medioid);

        if($countresponse)
            return $countresponse->count;

        return 0;

    }

    public static function GET_VIDEO_BY_ID($accountid,$video_id,$medioid){
        $url="/accounts/$accountid/videos/$video_id";
        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"GET",$medioid);
    }

    public static function GET_VIDEO_SOURCES($accountid,$video_id,$medioid){
        $url="/accounts/$accountid/videos/$video_id/sources";
        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"GET",$medioid);
    }

    public static function GET_VIDEO_IMAGES($accountid,$video_id,$medioid){
        $url="/accounts/$accountid/videos/$video_id/images";
        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"GET",$medioid);
    }

    public static function GET_VIDEO_MASTER($accountid,$video_id,$medioid){
        $url="/accounts/$accountid/videos/$video_id/digital_master";
        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"GET",$medioid);
    }

    public static function GET_VIDEO_PLASYLISTS($accountid,$video_id,$medioid){
        $url="/accounts/$accountid/videos/$video_id/references";
        return VC_BCAPI_Base::MAKE_CMS_REQUEST($url,false,$accountid,"GET",$medioid);
    }

}



?>