<?php

add_filter('the_content', 'add_cdn_images', 99);

function add_cdn_images($content)
{

    global $post;
    $siteLink = get_bloginfo('url');
    $content = do_shortcode($content);

    if ($post->ID == 1 || $post->ID == 910) {

        ob_start();
        echo $content; 
        $content = ob_get_contents();
        ob_end_clean();

        $cdnlink = 'https://s1.ppllstatics.com/elcomercio/';
        $content = preg_replace_callback('/<img [^>]+/x', function ($matches) use ($siteLink, $cdnlink) {
            $href = $matches[0];
            if (preg_match("%src=\S\/contentfactory/post/%i", $href)) {
                $href = preg_replace("%src=\S\/contentfactory/post/%", 'src="' . $cdnlink . 'contentfactory/post/', $href);
            }
            return $href;
        }, $content);
    }
    remove_filter('the_content','wpautop');
    return $content;
}
