<?php

/**
 * VCLM_Gigya_srv short summary.
 *
 * VCLM_Gigya_srv description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VCLM_Gigya_srv
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

   
   

     static function   VCLM_STATS_GET_MOST_COMMENTED_WIDGET($widget_instance){
        
       
         return VCLM_Gigya_srv::VCLM_STATS_GET_MOST_COMMENTED($widget_instance["vclm_widget_gigya_categories"],$widget_instance["vclm_widget_gigya_show_last"],$widget_instance["vclm_widget_gigya_max_age"],$widget_instance["vclm_widget_gigya_tag"],$widget_instance["vclm_widget_gigya_count"],$widget_instance["vclm_widget_gigya_cache_time"]);

    }

    static function  VCLM_STATS_GET_MOST_COMMENTED($category,$showlast,$maxage,$tags,$limit,$time){
        
        $stats_options=vclm_get_gigya_config();
        $stats_url= "http://comments.eu1.gigya.com/comments.getTopStreams?apiKey=".$stats_options["vclm_gigya_apikey"];
        
        if($category)
            $stats_url.="&categoryID=".$category;

        if($tags)
            $stats_url.="&streamTag=".$tags;

        if($maxage)
            $stats_url.="&maxStreamAge=".$maxage;

        if($showlast)
            $stats_url.="&includeLastComment=".$showlast;

        if(!$limit){
            $limit=10;
        }

        $stats_url.="&limit=".$limit;

        //Miramos si tenemos en caché
        $cachedValue=VCLM_Gigya_srv::GET_CACHE($stats_url);
        if($cachedValue!=null){
            return $cachedValue;
        }

        //Json petición
        $json_results=json_decode(file_get_contents($stats_url));



        //Guardamos en caché
        VCLM_Gigya_srv::ADD_CACHE($stats_url,$json_results,($time*60));


        return $json_results;
		
    }

    static function  VCLM_GET_CATEGORIES($cache_time=5){
        
        $stats_options=vclm_get_gigya_config();
        $stats_url= "https://comments.eu1.gigya.com/comments.getCategories?apiKey=".$stats_options["vclm_gigya_apikey"]."&secret=".$stats_options["vclm_gigya_secretkey"];

        //Miramos si tenemos en caché
        $cachedValue=VCLM_Gigya_srv::GET_CACHE($stats_url);
        if($cachedValue!=null){
            return $cachedValue;
        }

        //Json petición
        $json_results=json_decode(vclm_CURL($stats_url));



        //Guardamos en caché
        VCLM_Gigya_srv::ADD_CACHE($stats_url,$json_results,($cache_time*60));


        return $json_results;
		
    }
}