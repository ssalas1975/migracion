<?php

/**
 * VC_CMSAPI_OAuth short summary.
 *
 * VC_CMSAPI_OAuth description.
 *
 * @version 1.0
 * @author j.godoy
 */

    /*
    * NOTAS:
    * Como pueden existir varias cuentas configuradas en el sistema y cada tiene que utilizar su propio token, la clave de la variable
    * de session donde se almacena el token temporal añade el accountid como particula 'vc_bc_token_'.$accountid
    * Cuando se quiera recuperar un token hay que pasar el id de la cuenta del que quieres el token

    */
 class VC_CMSAPI_OAuth{
	
	
    private static  $END_POINT="https://oauth.brightcove.com/v3/access_token";
	
    public static function GET_CURRENT_TOKEN($accountid,$medioid){
        if(VC_CMSAPI_OAuth::IS_VALID_TOKEN($accountid,$medioid))
            return $_SESSION["vc_bc_token_".$accountid."_".$medioid]->access_token;
        return null;
    }

	public static function GENERATE_TOKEN($client_id,$client_secret,$accountid,$medioid){
		

        if(VC_CMSAPI_OAuth::IS_VALID_TOKEN($accountid,$medioid))
            return $_SESSION["vc_bc_token_".$accountid."_".$medioid];

        //Si ha expirado el token solicitamos otro

		$ch = curl_init(VC_CMSAPI_OAuth::$END_POINT);

		curl_setopt($ch, CURLOPT_URL,VC_CMSAPI_OAuth::$END_POINT);

		curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS,"grant_type=client_credentials");  

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $auth="$client_id:$client_secret";

		curl_setopt($ch, CURLOPT_HTTPHEADER , array(
			'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '.base64_encode($auth)
		));
		
		$response = curl_exec($ch);

		curl_close($ch);

        if ($response == false) {
            return null;
        }
        else{

            $responseClass=json_decode($response);

            if($responseClass && !property_exists($responseClass,"error"))
            {
                VC_CMSAPI_OAuth::SET_TOKEN(json_decode($response),$accountid,$medioid);
                return $_SESSION["vc_bc_token_".$accountid."_".$medioid];
            }
            else{
                return null;
            }
        }
		
	}

    private static function SET_TOKEN ($token,$accountid,$medioid){
        $token = (array)$token;
        $now=new DateTime();
        $token['date'] = $now->getTimestamp();
        $token['expires_date'] =intval($token['date'])+intval($token['expires_in']);
        $token = (object)$token;
        $_SESSION["vc_bc_token_".$accountid."_".$medioid]=$token;

       
    }
	
    public static function IS_VALID_TOKEN ($accountid,$medioid){
         
        if( $_SESSION["vc_bc_token_".$accountid."_".$medioid]!=null){
          
             $now=new DateTime();
             $nowTS=$now->getTimestamp();
             if($_SESSION["vc_bc_token_".$accountid."_".$medioid]->expires_date>$nowTS)
                 return true;
             else{
                 $_SESSION["vc_bc_token_".$accountid."_".$medioid]=null;
                 return false;
             }
         }

         return false;
        
     }


}



?>