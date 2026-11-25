<?php
function curlCall($post_id, $post, $update)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ('auto-draft' === $post->post_status) return;
    if ('draft' === $post->post_status) return;
    if ('trash' === $post->post_status) return;
    if ('inherit' === $post->post_status) return;


    $terms = get_terms(array(
        'taxonomy' => 'Medios',
        'hide_empty' => false,
    ));

    // $ch = curl_init('http://rmcache.srv.vocento.in/akamai/invalidate/url');
    // $ch = curl_init('http://rmcache2.srv.vocento.in/fastly/purge');
    $ch = curl_init('https://purgeservice.srv.vocento.in/purge');
 
    
    // if($post->ID == 1){
        foreach ($terms as $term) { 

            $url_medioCheck = get_field('url_medio', $term);
            if (checkdomain($post->ID, $url_medioCheck)) {
                $custom_value = get_field('slug', $term);
                $url_medioCheck_original = $url_medioCheck;
                $permalink = get_permalink($post->ID);
    
                if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
                    $url_medioCheck = str_replace('http://', '', $url_medioCheck);
                    $permalink = str_replace('https://ms-brandedcontent.srv.vocento.in', '', $permalink);
                } else {
                    $url_medioCheck = str_replace('http://', '', $url_medioCheck);
                    $permalink = str_replace('https://ms-brandedcontent.srv.vocento.in', '', $permalink);
                }
                $urlmedio = '/NFS/code/ms/vocento/current/wp-content/uploads/cache/' . $url_medioCheck . '/all' . $permalink . 'index.html';
    
                unlink($urlmedio);
    
                $post_permalink = "";
                if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
                    $post_permalink = str_replace('http://pre-contentfactory.', 'https://pre-www.', $url_medioCheck_original);
                } else {
                    $post_permalink = str_replace('http://content-factory.', 'https://www.', $url_medioCheck_original);
                }
    
                $pos = strpos($post_permalink, 'www.sevilla.abc.es');
                if ($pos !== false) {
                    $post_permalink = str_replace('www.sevilla.abc.es', 'sevilla.abc.es', $post_permalink);
                }
    
    
                if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
                    $post_permalink = str_replace('https://ms-brandedcontent.srv.vocento.in/', $post_permalink . '/contentfactory/post/', get_permalink($post->ID));
                } else {
                    $post_permalink = str_replace('https://ms-brandedcontent.srv.vocento.in/', $post_permalink . '/contentfactory/post/', get_permalink($post->ID));
                }

                // $payload = json_encode(array('urls' => $post_permalink));
                $payload = json_encode(array($post_permalink));
                // -> https://www.diariovasco.com/contentfactory/post/2016/11/08/hello-world/
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_exec($ch);
            }
            
        }

    // }
    curl_close($ch);
}

add_action('post_updated', 'curlCall', 10, 3);
add_action('save_post', 'curlCall', 10, 3);
