<?php

/**
 * VCLM_Stats_srv short summary.
 *
 * VCLM_Stats_srv description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VCLM_Stats_srv
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



    static function  VCLM_STATS_PRINT_METRIC($post){


        $options_stats_maps = vclm_get_option(array('vclm_design_stats','vclm_design_stats_maps'));

        $stats_metric_url= vclm_get_option(array('vclm_design_stats',"vclm_stats_metrics_url"));
        
        $stats_content_id=$options_stats_maps[sanitize_title($post->post_type)];

        if($stats_content_id=="0")
            return;
        
        $stats_edition= vclm_get_option(array('vclm_design_stats',"vclm_stats_medio"));
        
        $stats_tags= vclm_get_option(array('vclm_design_stats',"vclm_stats_tags"));

        foreach( wp_get_post_tags($post) as $tag){
            $stats_tags.=",".$tag->name;
        }

        $stats_click_type= vclm_get_option(array('vclm_design_stats',"vclm_stats_click_type"));
        
        $stats_edition= vclm_get_option(array('vclm_design_stats',"vclm_stats_edicion"));

        $stats_category= vclm_get_option(array('vclm_design_stats',"vclm_stats_category"));
        
        $stats_pattern =vclm_get_option(array('vclm_design_stats',"vclm_stats_id_pattern"));
        
        $stats_pattern= str_replace("%%BLOG_ID%%",((function_exists("get_current_blog_id"))?get_current_blog_id():"1"),$stats_pattern);
        
        $stats_pattern= str_replace("%%ID%%",$post->ID,$stats_pattern);
        
        $stats_title=  vclm_cleanencoded(urlencode(mb_convert_encoding($post->post_title,"ISO-8859-1")));
        
        $stats_url=get_permalink($post);


       $stats_final_URL=$stats_metric_url."/stats/v2/visit?id=".$stats_pattern."&title=". $stats_title."&url=".$stats_url."&tags=".urlencode($stats_tags);
       
       if($stats_edition)
           $stats_final_URL.="&edition=".$stats_edition;

       if($stats_content_id)
           $stats_final_URL.="&content_type=".$stats_content_id;

       if($stats_click_type)
           $stats_final_URL.="&click_type=".$stats_click_type;

       if($stats_options["vclm_stats_aux1"]){
           $stats_final_URL.="&aux1=".$stats_options["vclm_stats_aux1"];
       }

       if($stats_options["vclm_stats_aux2"]){
           $stats_final_URL.="&aux2=".$stats_options["vclm_stats_aux2"];
       }


       //Si no se ha definido una categoria "a fuego" en la configiracion del plugin se manda la primera categoria del post
       if(!$stats_category){
           $categories=get_the_category($post->ID);
           if($categories && is_array($categories)){
               $stats_final_URL.="&section=".urlencode($categories[0]->name);
           }
       }
       else{
           //si se ha definido se manda la definida
           $stats_final_URL.="&section=".urlencode($stats_category);
       }

?>
<script>
    jQuery(function () {

        jQuery.ajax({
            url: "<?php echo $stats_final_URL ?>"
                , dataType: 'jsonp'
                , async: true

        });
    });
</script>
        <?php

        
    }

    



    static function   VCLM_STATS_GET_MOST_VIEWED_WIDGET($widget_instance){
        
        return VCLM_Stats_srv::VCLM_STATS_GET_MOST_VIEWED($widget_instance["vclm_widget_stats_edition"],$widget_instance["vclm_widget_stats_category"],$widget_instance["vclm_widget_stats_origin"],$widget_instance["vclm_widget_stats_type"],$widget_instance["vclm_widget_stats_tags"],$widget_instance["vclm_widget_stats_count"],$widget_instance["vclm_widget_stats_cache_time"]);

    }

    static function  VCLM_STATS_GET_MOST_VIEWED($edicion,$category,$origen,$tipo,$tags,$limit,$time){
        
        $stats_options=vclm_get_stats_config();

        if($stats_options["vclm_stats_url"]){

            $stats_url= $stats_options["vclm_stats_url"]."/stats/v2/most-viewed/json?";
            if($edicion)
                $stats_url.="edition=".$edicion;

            if($category)
                $stats_url.="&section=".$category;

            if($origen)
                $stats_url.="&origin=".$origen;

            if($tipo)
                $stats_url.="&content_type=".$tipo;

            if($tags)
                $stats_url.="&tags=".$tags;

            if(!$limit){
                $limit=10;
            }

            $stats_url.="&limit=".$limit;

            //Miramos si tenemos en caché
            $cachedValue=VCLM_Stats_srv::GET_CACHE($stats_url);
            if($cachedValue!=null){
                return $cachedValue;
            }


            //Petición
            $json_results=json_decode(file_get_contents($stats_url));


            //Guardamos en caché
            VCLM_Stats_srv::ADD_CACHE($stats_url,$json_results,($time*60));

            return $json_results;

        }

        return array();

       
		
    }


}