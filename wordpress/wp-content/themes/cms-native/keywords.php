<?php

/**
 * Keywords for Medios.
 *
 *
 * @version 1.0
 * @author j.marin
 */
 
//añadiendo JS necesario
wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), null, false);
wp_enqueue_script('keywors_script', get_template_directory_uri() . '/js/keywords.js');
 
//añadiendo metabox para Keywords

add_action('add_meta_boxes', 'meta_box_keyword');
function meta_box_keyword()
{
    add_meta_box('meta-box-keyword', 'Keywords Meta Header', 'print_meta_box_keyword', 'post', 'side', 'low');
}

//pintando metabox keyword
function print_meta_box_keyword()
{
    $customList = print_keyword_callback();
    echo $customList;
}

//guarda los metadatos del keyword
function keyword_save_meta_box($post_id)
{
    if (isset($_POST['keyword-url'])) {
        $urlkeyword = $_POST['keyword-url'];
        update_post_meta($post_id, "keyword-url", $urlkeyword);
    }
}
add_action('save_post', 'keyword_save_meta_box');

//elimina los metadatos del keyword
function keyword_delete_meta_box($post_id)
{
    delete_post_meta($post_id, "keyword-url");
}
add_action('delete_post', 'keyword_save_meta_box');



function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
    global $wpdb;
    if( empty( $key ) )
        return;
    $r = $wpdb->get_results( $wpdb->prepare( "
        SELECT p.ID, pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s' 
        AND p.post_status = '%s' 
        AND p.post_type = '%s'
    ", $key, $status, $type ));

    foreach ( $r as $my_r )
        $metas[$my_r->ID] = $my_r->meta_value;

    return $metas;
}


//devuelve la lista con los medios y sus keywords
function print_keyword_callback()
{


    $keywords = get_meta_values('keyword-url');


    // //obteniendo todos los campos para keywords
    // $posts = get_posts(
    //     array(
    //         'meta_key' => 'keyword-url',
    //         'post_status ' => 'publish',
    //         'posts_per_page' => -1,
    //         'update_post_meta_cache' => false,
    //         'no_found_rows' => true,
    //         'update_post_term_cache' => false,
    //     )
    // );

    // $meta_values = array();
    // foreach ($posts as $post) {
    //     $meta_values[] = get_post_meta($post->ID, 'keyword-url', true);
    // }
    //creando datalist y inputs
    $html='
	<input type="hidden" name="keyword-url">';
    global $is_safari;
    
    if ($is_safari) {
        $html .= '<input id="keywordsinput" placeholder="añadir nuevo" style="width: 70%;"><select id="keywords" style="width: 70%;">';
    } else {
        $html .= '<input id="keywordsinput" list="keywords" name="keywords"><datalist id="keywords">';
    }
    
    global $pagenow;
    
    if ('post.php' == $pagenow) {
        foreach ($keywords as $value) {
            $keywords = explode(",", $value);
            foreach ($keywords as $keyword) {
                if ($keyword != "") {
                    if ($is_safari) {
                        $html .= '<option value="'.$keyword.'">'.$keyword.'</option>';
                    } else {
                        $html .= '<option value="'.$keyword.'">';
                    }
                }
            }
        }
        if ($is_safari) {
            $html .= '</select>';
        } else {
            $html .= '</datalist>';
        }
    
        $html .= '</form><input type="button" class="button keywordadd" value="Añadir">';
        
        $html .= '<div class="tagchecklist" id="keywordsDiv">';
        
        
        $keywordsselected = get_post_meta(get_the_ID(), 'keyword-url', true);
        if ($keywordsselected != "") {
            $keywordsselected = explode(",", $keywordsselected);
            foreach ($keywordsselected as $keyword) {
                $html .= '<li id="keyword-'.$keyword.'"><a class="ntdelbutton" data-id="'.$keyword.'">X</a>&nbsp;'.$keyword.'</li>';
            }
        }

        
        $html .= '</div>';
        
        return $html;
    }
}
