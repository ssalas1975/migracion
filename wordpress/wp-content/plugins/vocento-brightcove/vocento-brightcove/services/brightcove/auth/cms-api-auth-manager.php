<?php

/**
 * VC_CMSAPI_OAuth_Manager short summary.
 *
 * VC_CMSAPI_OAuth_Manager description.
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
class VC_CMSAPI_OAuth_Manager{
	
	
    public static function GENERATE_TOKENS($credentials){

        foreach($credentials as $credential){
            if(!VC_CMSAPI_OAuth::IS_VALID_TOKEN($credential["accid"],$credential["medio_id"]))
                VC_CMSAPI_OAuth::GENERATE_TOKEN($credential["clientid"],$credential["clientsecret"],$credential["accid"],$credential["medio_id"]);
        }

    }

}



?>