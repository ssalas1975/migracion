<?php

/**
 * VCLM_WP_srv short summary.
 *
 * VCLM_WP_srv description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VCLM_WP_srv
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

    static function   VCLM_WORDPRESS_GET_LATEST($widget_instance){
        
        if($widget_instance["vclm_widget_wp_show_network"] && function_exists("wp_get_sites"))
            return VCLM_WP_srv::vclm_get_latest_post_network($widget_instance["vclm_widget_wp_categories"],$widget_instance["vclm_widget_wp_tags"],$widget_instance["vclm_widget_wp_autor"],$widget_instance["vclm_widget_wp_count"],$widget_instance["vclm_widget_wp_cache_time"],$widget_instance["vclm_widget_wp_pt"]);
        else
            return VCLM_WP_srv::vclm_get_latest_post($widget_instance["vclm_widget_wp_categories"],$widget_instance["vclm_widget_wp_tags"],$widget_instance["vclm_widget_wp_autor"],$widget_instance["vclm_widget_wp_count"],$widget_instance["vclm_widget_wp_cache_time"],$widget_instance["vclm_widget_wp_pt"]);

    }

    static function   VCLM_WORDPRESS_GET_LATEST_NETWORK($widget_instance){
        
        return VCLM_WP_srv::vclm_get_latest_post_network($widget_instance["vclm_widget_wp_categories"],$widget_instance["vclm_widget_wp_tags"],$widget_instance["vclm_widget_wp_autor"],$widget_instance["vclm_widget_wp_count"],$widget_instance["vclm_widget_wp_cache_time"],$widget_instance["vclm_widget_wp_pt"]);

    }

    static function composeCacheKey($args){
        
        $key="";
        foreach ($args as $clave => $valor){
            $key.=$clave."_".$valor."_";
        }

        return $key;
    }

    static function vclm_get_latest_post($category,$tags,$author,$count,$time,$pt){
        
        $args = VCLM_WP_srv::vclm_compose_args_posts($category,$tags,$author,$count,$pt);

        
        //Cache Key
        $keyCache=VCLM_WP_srv::composeCacheKey($args);

        //Miramos si tenemos en caché
        $cachedValue=VCLM_Stats_srv::GET_CACHE($keyCache);
        if($cachedValue!=null){
            return $cachedValue;
        }
        
        $recent_posts = get_posts( $args );
        VCLM_WP_srv::set_blog_posts($recent_posts,get_current_blog_id());


        //Guardamos en caché
        VCLM_Stats_srv::ADD_CACHE($keyCache,$recent_posts,($time*60));

        
        return $recent_posts;
    }

    static function set_blog_posts(&$blogs_posts,$blog_id){

        foreach($blogs_posts as &$post)
        {
            $post=VCLM_WP_srv::post_to_array($post);
            $post["blog_id"]=$blog_id;
        }
    }


    function post_to_array($wppost) {
        $post = get_object_vars( $wppost );

        foreach ( array( 'ancestors', 'page_template', 'post_category', 'tags_input' ) as $key ) {
            if ( $wppost->__isset( $key ) )
                $post[ $key ] = $wppost->__get( $key );
        }

        return $post;
    }

    function wpPosts_to_array($wppost_array) {

        $posts=array();
        foreach($wppost_array as $wppost){
            $post = get_object_vars( $wppost );

            foreach ( array( 'ancestors', 'page_template', 'post_category', 'tags_input' ) as $key ) {
                if ( $wppost->__isset( $key ) )
                    $post[ $key ] = $wppost->__get( $key );
            }
            array_push($posts,$post);
        }

        return $posts;
    }


    static function vclm_get_latest_post_network($category,$tags,$author,$count,$time,$pt){
        
        if(!$count)
            $count=5;
        
        $args = VCLM_WP_srv::vclm_compose_args_posts($category,$tags,$author,$count,$pt);

        //Cache Key
        $keyCache="network_".VCLM_WP_srv::composeCacheKey($args);

        //Miramos si tenemos en caché
        $cachedValue=VCLM_Stats_srv::GET_CACHE($keyCache);
        if($cachedValue!=null){
            return $cachedValue;
        }
        

        $recent_posts = array();

        foreach(wp_get_sites() as $site){
            
            switch_to_blog($site["blog_id"]);
            $blogs_posts=get_posts($args);
            VCLM_WP_srv::set_blog_posts($blogs_posts,$site["blog_id"]);
            $recent_posts=array_merge($recent_posts , $blogs_posts);
            
        }

        restore_current_blog();
        
        

        usort($recent_posts, 'vclm_sort_post_date'); 

        $recent_posts=array_slice($recent_posts,0 ,$count);

        //Guardamos en caché
        VCLM_Stats_srv::ADD_CACHE($keyCache,$recent_posts,($time*60));
        
        return $recent_posts;
    }


    
    


    static function vclm_compose_args_posts($category,$tag,$author,$size,$pt){


        $args = array(
            'offset' => 0,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_status' => 'publish',
            'suppress_filters' => true 
        );


        //Elementos

       
        if(!$size || !is_numeric($size)){
            $size=10;
        }

        $args['posts_per_page']=$size;
        


        //Categorias
        
        if($category){
            $args['category']= $category;
        }

        
        
        
        //tags
        
        if($tag){
            $args['tag']=$tag;   
        }

      

        //author 
        if($author){
            $author=get_user_by('slug',$author);
            if($author)
                $args['author']=$author->ID;  
            else
                $args['author']=-1; 
        }

        //post types
        if($pt){
            $args['post_type']=explode(",",$pt);
        }
        
        return $args;

    }

}