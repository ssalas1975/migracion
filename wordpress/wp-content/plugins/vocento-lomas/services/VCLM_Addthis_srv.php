<?php

/**
 * VCLM_Addthis_srv short summary.
 *
 * VCLM_Addthis_srv description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VCLM_Addthis_srv
{

    static function ADD_CACHE($key,$value,$time=300){
        
        //a cache 5 minutos
        if(function_exists('apc_store')){
            apc_store($key, $value,$time);
        }

        
    }

    static function GET_CACHE($key){
        if(function_exists('apc_fetch')){
            $cached_feed=apc_fetch($key);
            if($cached_feed){
                return $cached_feed;
            }
        }
        return null;
    }



    

    static function   VCLM_STATS_GET_MOST_SHARED_WIDGET($widget_instance){
        
        return VCLM_Addthis_srv::VCLM_STATS_GET_MOST_SHARED($widget_instance["vclm_widget_addthis_count"],$widget_instance["vclm_widget_addthis_show_sharedcount"],$widget_instance["vclm_widget_addthis_domain"],$widget_instance["vclm_widget_addthis_cache_time"],$widget_instance["vclm_widget_addthis_period"]);

    }

    static function  VCLM_STATS_GET_MOST_SHARED($limit,$show_sahreds,$domain,$time,$period){
        
        $stats_options=vclm_get_addthis_config();
        $stats_url= "http://q.addthis.com/feeds/1.0/shared.json?pubid=".$stats_options["vclm_addthis_pubid"];
        
        if($domain)
            $stats_url.="&domain=".$domain;

        if($period)
            $stats_url.="&period=".$period;

        if(!$limit){
            $limit=10;
        }

     

        //Miramos si tenemos en caché
        $cachedValue=VCLM_Stats_srv::GET_CACHE($stats_url."_LIMIT_".$limit);
        if($cachedValue!=null){
            return $cachedValue;
        }


        //Petición
        $json_results=json_decode(vclm_CURL_AUTH($stats_url,$stats_options["vclm_addthis_user"],$stats_options["vclm_addthis_pass"]));

        if($json_results && is_array($json_results))
            $json_results=array_slice($json_results,0,$limit);

        //Guardamos en caché
        VCLM_Stats_srv::ADD_CACHE($stats_url,$json_results,($time*60));


        return $json_results;
		
    }


}