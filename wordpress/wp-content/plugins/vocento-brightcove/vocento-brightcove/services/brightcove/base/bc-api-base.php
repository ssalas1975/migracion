<?php

/**
 * CMSAPI_Base short summary.
 *
 * VC_BCAPI_Base description.
 *
 * @version 1.0
 * @author j.godoy
 */

class VC_BCAPI_Base
{
    private static  $CMS_END_POINT="https://cms.api.brightcove.com/v1";
    private static  $INGEST_END_POINT="https://ingest.api.brightcove.com/v1";

    private static function  MAKE_SIGNED_BC_REQUEST($url,$data,$accountid,$method="GET",$endpoint,$medioid){

        
        $ch = curl_init($endpoint.$url);

		curl_setopt($ch, CURLOPT_URL,$endpoint.$url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if($data)
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);  

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER , array(

            'Content-Type: application/json',

			'Authorization: Bearer '.VC_CMSAPI_OAuth::GET_CURRENT_TOKEN($accountid,$medioid),
            
		));

		$response = curl_exec($ch);

		curl_close($ch);

        if ($response == false) {
            
            return null;
        }
        else{

            return json_decode($response);
        }

        
        
    }

    public static function  MAKE_CMS_REQUEST($url,$data,$accountid,$method="GET",$medioid){
        return VC_BCAPI_Base::MAKE_SIGNED_BC_REQUEST($url,$data,$accountid,$method,VC_BCAPI_Base::$CMS_END_POINT,$medioid);
    }

    public static function  MAKE_INGEST_REQUEST($url,$data,$accountid,$method="GET",$medioid){
        return VC_BCAPI_Base::MAKE_SIGNED_BC_REQUEST($url,$data,$accountid,$method,VC_BCAPI_Base::$INGEST_END_POINT,$medioid);
    }

   
    
}

?>