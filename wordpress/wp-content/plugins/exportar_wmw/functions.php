<?php

function getImages($pathFile, $idpost)
{

}

function gen_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function getImageIdByUrl( $url )
{
    global $wpdb;
    // var_dump($url);
    // $url = str_replace('http://xllocal.com','',$url);
    // $url = 'https://www.womennow.es'.$url;
    // $url = 'https://ms-mhsites.srv.vocento.in/womennow/wp-content/uploads/sites/16/2022/12/hoteles-navidad-granada-617x432-1.jpg';
    $url = trim($url);
    // $idThumbnail = 'https://ms-rc.srv.vocento.in/xlsemanal'.trim($data);
    // $id3 = getIdImageFromURLRecetas($idThumbnail);
    // $dir = wp_get_upload_dir();
    $path = $url;
    
    // $site_url = parse_url( $dir['url'] );
    // $image_path = parse_url( $path );

    //force the protocols to match if needed
    // if ( isset( $image_path['scheme'] ) && ( $image_path['scheme'] !== $site_url['scheme'] ) ) {
    //     $path = str_replace( $image_path['scheme'], $site_url['scheme'], $path );
    // }

    // if ( 0 === strpos( $path, $dir['baseurl'] . '/' ) ) {
    //     $path = substr( $path, strlen( $dir['baseurl'] . '/' ) );
    // }

    $basename = wp_basename( $path );
    // var_dump($basename);
    // $dirname = dirname( $path );
    
    $sql = "SELECT post_id, meta_key, meta_value FROM ".$wpdb->prefix."postmeta where meta_key IN ( '_wp_attachment_metadata', '_wp_attachment_backup_sizes' ) AND meta_value LIKE '%". $basename ."%'";
    $results = $wpdb->get_results( $sql );
    foreach ( $results as $row ) {
        return $row->post_id;
    }
}

function handleStrongTag($content, $idpost)
{

	// $content = str_replace('addinstagram', 'addinstagram_exportacion', $content);
	// $content = str_replace('addyoutube', 'addyoutube_exportacion', $content);
	// $content = str_replace('addtwitter', 'addtwitter_exportacion', $content);
	// $content = handleImages($content, $idpost);

	// $content = apply_filters('the_content', $content);
	// // $content = handledContent($content, $idpost, 'noticias');

	// $reSpans = '/<span style="font-weight: 400;">(.*?)<\/span>/ms';
	// preg_match_all($reSpans, $content, $matchesSpans, PREG_SET_ORDER, 0);
	// foreach( $matchesSpans as $span ){
	// 	// $content = str_replace($span[0], '<p>'.$span[1].'</p>', $content);
	// 	$content = str_replace($span[0], $span[1], $content);
	// }
	
	// $content = str_replace("<b>","<span>", $content);
	// $content = str_replace("</b>","</span>", $content);

	// $content = str_replace("<p><p>","<p>", $content);
	// $content = str_replace("</p></p>","</p>", $content);
	// $content = str_replace('<strong>', '<span>', $content);
	// $content = str_replace('</strong>','</span>', $content);
	// $content = str_replace('class="p1"','', $content);
	// $content = str_replace('<p>&nbsp;</p>', '', $content);

	
	// $re = '/\[caption(.*?)]/m';
	// preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// $content = str_replace($matches[0][0],'', $content);

	// $content = str_replace('[/caption]','', $content);

	// $reBlockquote = '/<blockquote class="instagram-media"(.*?)<\/blockquote>/ms';
	// $reBlockquote= '/<div class="widget">(\r\n|\r|\n)+<div class="code">(\r\n|\r|\n)+(.*?)<\/script><\/p>(\r\n|\r|\n)+<\/div>(\r\n|\r|\n)+<\/div>/ms';
	// preg_match_all($reBlockquote, $content, $matchesBlockquote, PREG_SET_ORDER, 0);

	// $reScript = '/<p><script(.*?)<\/p>/ms';
	// preg_match_all($reScript, $content, $matchesScript, PREG_SET_ORDER, 0);
	// $scriptCount = 0;

	// foreach( $matchesBlockquote as $match ){
	// 	// var_dump($match);
	// 	// $content = str_replace($match[0], '', $content); 
	// 	// $content = str_replace($matchesScript[$scriptCount][0], '', $content); 

	// 	// $reEmptyDivs = '/<div class="widget">(\r\n|\r|\n)+<div class="code">(\r\n|\r|\n){2,}<\/div>(\r\n|\r|\n)+<\/div>/ms';
	// 	// preg_match_all($reEmptyDivs, $content, $matchesReEmptyDivs, PREG_SET_ORDER, 0);

	// 	// $content = str_replace($matchesReEmptyDivs[0][0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[0].$matchesScript[$scriptCount][0].'</p></div>', $content); 

	// 	// $scriptCount ++;
	// 	$content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[3].'</script></p></p></div>', $content); 
	// }


	// $reBlockquoteTwitter = '/<div class="widget">(\r\n|\r|\n)+<blockquote class="twitter-tweet"(.*?)<\/div>/ms';
	// preg_match_all($reBlockquoteTwitter, $content, $matchesBlockquoteTwitter, PREG_SET_ORDER, 0);
	// foreach($matchesBlockquoteTwitter as $reBlockquoteTwitter){
	// 	$contentReemplace = str_replace('<div class="widget">', '<div cmpName="cmp-codigo"><p class="voc-codigo">', $reBlockquoteTwitter[0]);
	// 	$contentReemplace = str_replace('</div>', '</p></div>', $contentReemplace);
	// 	$content = str_replace($reBlockquoteTwitter[0], $contentReemplace, $content);
	// }

	// $re = '/<div class="embed">(.*?)<\/div>/ms';
	// preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

	// if(count($matches) > 0){
	// 	foreach($matches as $match)
	// 	{
	// 		$content = str_replace($match[0], $match[1], $content);
	// 	}
		
	// 	$re = '/<div class="code">(.*?)<\/div>/ms';
	// 	preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// 	foreach($matches as $match)
	// 	{
	// 		$content = str_replace($match[0], $match[1], $content);
	// 	}

	// 	$content = str_replace('<div class="widget">','<div cmpName="cmp-codigo">', $content);

	// 	$re = '/<iframe(.*?)<\/iframe>/m';
	// 	preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// 	foreach($matches as $match)
	// 	{
	// 		$content = str_replace($match[0],'<p class="voc-codigo">'.$match[0].'</p>', $content);
	// 	}

	// }
	

	// $content = str_replace('&nbsp;','', $content);
	// // $content = wpautop( $content, false );

	// $re = '/[[:blank:]]async[[:blank:]]/ms';
	// preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// foreach($matches as $match){
	// 	$content = str_replace($match[0],' async="" ', $content);
	// }
	// $re = '/[[:blank:]]defer[[:blank:]]/ms';
	// preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// foreach($matches as $match){
	// 	$content = str_replace($match[0],' defer="" ', $content);
	// }


	// $content = str_replace('<p></p>', '', $content);
	// $content = str_replace('<span class="Apple-converted-space">', '', $content);
	// // $content = str_replace('<p><script','<script', $content);
	// // $content = str_replace('</script></p>', '</script>', $content);
	
	// $re = '/<blockquote><p>(.*?)<\/p><\/blockquote>/ms';
	// preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// foreach($matches as $match)
	// {
	// 	$content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote>'.$match[1].'</blockquote></p></div>', $content);
	// }

	// $re = '/<blockquote>(\r\n|\r|\n)<p\s>(.*?)<\/p>(\r\n|\r|\n)?<\/blockquote>/ms';
	// preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// foreach($matches as $match)
	// {
	// 	$content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote>'.$match[2].'</blockquote></p></div>', $content);
	// }

	// $re = '/<div class="widget">(\r\n|\r|\n)<div class="code">(.*?)<\/div>(\r\n|\r|\n)?<\/div>/ms';
	// preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
	// foreach($matches as $match)
	// {
	// 	$content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[2].'</p></div>', $content);
	// }

	// $content = str_replace('<p ><div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote title="cualquier titulo Instagram"', '<div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote title="cualquier titulo Instagram"', $content);
	

	// $content = str_replace('<script async="" src="//www.instagram.com/embed.js"></script></p', '<p><script async="" src="//www.instagram.com/embed.js"></script></p', $content);

    // return $content;



    $content = str_replace('addinstagram', 'addinstagram_exportacion', $content);
	$content = str_replace('addyoutube', 'addyoutube_exportacion', $content);
	$content = str_replace('addtwitter', 'addtwitter_exportacion', $content);
	$content = handleImages($content, $idpost);

    $re = '/<a(.*)>(\r\n|\r|\n)+<\/a>(\r\n|\r|\n)+/m';
    preg_match_all($re, $content, $matches1, PREG_SET_ORDER, 0);
    // $contentS = str_replace($matches[0][3], '', $matches[0][0]);
    $content = str_replace($matches1[0][0], '<p>'.$matches1[0][0].'</p>', $content);

    $re = '/<div class="widget">(\r\n|\r|\n)+<div class="code">(\r\n|\r|\n)+<div class="embed">(.*?)<\/div>(\r\n|\r|\n)+<\/div>(\r\n|\r|\n)+<\/div>(\r\n|\r|\n)+/ms';
    preg_match_all($re, $content, $matches2, PREG_SET_ORDER, 0);
    foreach($matches2 as $match){
        $content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[3].'</p></div>', $content); 
	}

    $re = '/<span class="Apple-converted-space">(.*?)<\/span>/ms';
    preg_match_all($re, $content, $matches3, PREG_SET_ORDER, 0);
    foreach($matches3 as $match){
        $content = str_replace($match[0], '', $content); 
	}

	$content = apply_filters('the_content', $content);

    // if( !empty($matches1) || !empty($matches2) || !empty($matches3)) {
        if( $idpost == 16125 || $idpost == 23229 || $idpost == 9512 || $idpost == 6141 || $idpost == 4625 || $idpost == 3632 || $idpost == 2268 || $idpost == 17152 || $idpost == 19104 ) {
        // return $idpost;
            $reSpans = '/<span style="font-weight: 400;">(.*?)<\/span>/ms';
            preg_match_all($reSpans, $content, $matchesSpans, PREG_SET_ORDER, 0);
            foreach( $matchesSpans as $span ){
                $content = str_replace($span[0], $span[1], $content);
            }
        
            $reSpans = '/<span style="font-weight: 400">(.*?)<\/span>/ms';
            preg_match_all($reSpans, $content, $matchesSpans, PREG_SET_ORDER, 0);
            foreach( $matchesSpans as $span ){
                $content = str_replace($span[0], $span[1], $content);
            }
            
            $content = str_replace("<b>","<span>", $content);
            $content = str_replace("</b>","</span>", $content);
        
            $content = str_replace("<p><p>","<p>", $content);
            $content = str_replace("</p></p>","</p>", $content);
            $content = str_replace('<strong>', '<span>', $content);
            $content = str_replace('</strong>','</span>', $content);
            $content = str_replace('class="p1"','', $content);
            $content = str_replace('<p>&nbsp;</p>', '', $content);
        
            
            $re = '/\[caption(.*?)]/m';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            $content = str_replace($matches[0][0],'', $content);
        
            $content = str_replace('[/caption]','', $content);
        
        
        
            $reBlockquote = '/<blockquote class="instagram-media"(.*?)<\/blockquote>/ms';
            $reBlockquote= '/<div class="widget">(\r\n|\r|\n)+<div class="code">(\r\n|\r|\n)+(.*?)<\/script><\/p>(\r\n|\r|\n)+<\/div>(\r\n|\r|\n)+<\/div>/ms';
            preg_match_all($reBlockquote, $content, $matchesBlockquote, PREG_SET_ORDER, 0);
        
            $reScript = '/<p><script(.*?)<\/p>/ms';
            preg_match_all($reScript, $content, $matchesScript, PREG_SET_ORDER, 0);
            $scriptCount = 0;
        
            foreach( $matchesBlockquote as $match ){
                $content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[3].'</script></p></p></div>', $content); 
            }
        
            if(empty($matchesBlockquote)) {
                $reBlockquote = '/<div class="widget">(\r\n|\r|\n)+<div class="code">(\r\n|\r|\n)+(.*?)<\/script>/ms';
                preg_match_all($reBlockquote, $content, $matchesBlockquote, PREG_SET_ORDER, 0);
        
                foreach( $matchesBlockquote as $match ){
                    $content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[3].'</script></p></p></div>', $content); 
                }
            
            }
        
            // var_dump($content);
            // die('9999');
        
        
            $reBlockquoteTwitter = '/<div class="widget">(\r\n|\r|\n)+<blockquote class="twitter-tweet"(.*?)<\/div>/ms';
            preg_match_all($reBlockquoteTwitter, $content, $matchesBlockquoteTwitter, PREG_SET_ORDER, 0);
            foreach($matchesBlockquoteTwitter as $reBlockquoteTwitter){
                $contentReemplace = str_replace('<div class="widget">', '<div cmpName="cmp-codigo"><p class="voc-codigo">', $reBlockquoteTwitter[0]);
                $contentReemplace = str_replace('</div>', '</p></div>', $contentReemplace);
                $content = str_replace($reBlockquoteTwitter[0], $contentReemplace, $content);
            }
        
            $re = '/<div class="embed">(.*?)<\/div>/ms';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
        
            if(count($matches) > 0){
                foreach($matches as $match)
                {
                    $content = str_replace($match[0], $match[1], $content);
                }
                
                $re = '/<div class="code">(.*?)<\/div>/ms';
                preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
                foreach($matches as $match)
                {
                    $content = str_replace($match[0], $match[1], $content);
                }
        
                $content = str_replace('<div class="widget">','<div cmpName="cmp-codigo">', $content);
        
                $re = '/<iframe(.*?)<\/iframe>/m';
                preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
                foreach($matches as $match)
                {
                    $content = str_replace($match[0],'<p class="voc-codigo">'.$match[0].'</p>', $content);
                }
        
            }
            
        
            $content = str_replace('&nbsp;','', $content);
            // $content = wpautop( $content, false );
        
            $re = '/[[:blank:]]async[[:blank:]]/ms';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            foreach($matches as $match){
                $content = str_replace($match[0],' async="" ', $content);
            }
            $re = '/[[:blank:]]defer[[:blank:]]/ms';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            foreach($matches as $match){
                $content = str_replace($match[0],' defer="" ', $content);
            }
        
        
            $content = str_replace('<p></p>', '', $content);
            $content = str_replace('<span class="Apple-converted-space">', '', $content);
            // $content = str_replace('<p><script','<script', $content);
            // $content = str_replace('</script></p>', '</script>', $content);
            
            $re = '/<blockquote><p>(.*?)<\/p><\/blockquote>/ms';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            foreach($matches as $match)
            {
                $content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote>'.$match[1].'</blockquote></p></div>', $content);
            }
        
            $re = '/<blockquote>(\r\n|\r|\n)<p\s>(.*?)<\/p>(\r\n|\r|\n)?<\/blockquote>/ms';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            foreach($matches as $match)
            {
                $content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote>'.$match[2].'</blockquote></p></div>', $content);
            }
        
            $re = '/<div class="widget">(\r\n|\r|\n)<div class="code">(.*?)<\/div>(\r\n|\r|\n)?<\/div>/ms';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            foreach($matches as $match)
            {
                $content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[2].'</p></div>', $content);
            }
        
            $content = str_replace('<p ><div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote title="cualquier titulo Instagram"', '<div cmpName="cmp-codigo"><p class="voc-codigo"><blockquote title="cualquier titulo Instagram"', $content);
            
        
            $re = '/<blockquote(.*?)<\/blockquote>/ms';
            preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
            foreach($matches as $match)
            {
                $content = str_replace($match[0], '<div cmpName="cmp-codigo"><p class="voc-codigo">'.$match[0].'</p></div>', $content);
            }
            
            $content = str_replace('<div cmpName="cmp-codigo"><p class="voc-codigo"><div cmpName="cmp-codigo"><p class="voc-codigo">', '<div cmpName="cmp-codigo"><p class="voc-codigo">', $content);

            $content = str_replace('<p><script async="" src="//www.instagram.com/embed.js"></script></p></p></div>', '<p><script async="" src="//www.instagram.com/embed.js"></script></p>', $content);

            $content = str_replace('<p><script async="" src="https://www.tiktok.com/embed.js"></script></p></p></div>', '<p><script async="" src="https://www.tiktok.com/embed.js"></script></p>', $content);
                
            $content = str_replace('</blockquote></p></div></p></div>', '</blockquote></p></div>', $content);


            return $content;
    }else{
        return '0';
    }




}

function handleImages($content, $idpost){
    $re = '/<img(.*?)src="(.*?)"(.*?)\/>/ms';
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

    if(empty($matches)){
        return $content;
    }else{
        $html = '';
         foreach($matches as $url){
             $imagen = getImageIdByUrl($url[2]);
            if($imagen){
                $data = wp_get_attachment(intval($imagen));
                $UUID = gen_uuid();
                $html .= '
                <photo-container cmpName="cmp-foto-texto" class="voc-inner-figure">
                    <photo height="'.$data['height'].'" tmx="624 624 624 624" width="'.$data['width'].'" xtransform="1 0 0 1 0 0" fileref="/mujerhoy_2017/noticias/'.date("Ym").'/'.date("d").'/media/cortadas/'.$data['filename'].'?uuid='.gen_uuid().'" vspace="1"></photo>
                    <caption class="standard">
                        <description>'.$data['caption'].'</description>
                        <credit>'.$data['alt'].'</credit>
                    </caption>
                </photo-container>
                ';
        
                $imagePath = $data['filepath'];
                $newPath = __DIR__."/xmls/".$idpost."/";
                $ext = $data['extension'];
                $newName  = $newPath.$data['filename'];
                $copied = copy($imagePath , $newName);
            }
         }
         $content = str_replace($matches[0][0], $html, $content);
        return $content;   
    }
}

function handleIntagram($content)
{
    $html = '';
    $re = '/\[addinstagram(.*)]/m';
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
    foreach($matches as $match){
        $match = str_replace('addinstagram', 'addinstagram_exportacion', $match[0]);
        $shortcodeInstagram = do_shortcode($match);
        $html .= '
        <div cmpName="cmp-codigo">
            <p class="voc-codigo">
            '.$shortcodeInstagram.'
            </p>
        </div>
        ';
        $content = str_replace($match[0], $html, $content);
    }
    return $content;
}

function handleYoutube($content)
{
    $html = '';
    $re = '/\[addyoutube(.*)]/m';
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
    foreach($matches as $match){
        $match = str_replace('addyoutube', 'addyoutube_exportacion', $match[0]);
        $shortcodeInstagram = do_shortcode($match);
        $html .= '
        <div cmpName="cmp-codigo">
            <p class="voc-codigo">
            '.$shortcodeInstagram.'
            </p>
        </div>
        ';
        $content = str_replace($match[0], $html, $content);
    }
    return $content;
}

function handleTwitter($content)
{
    $html = '';
    $re = '/\[addtwitter(.*)]/m';
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
    foreach($matches as $match){
        $match = str_replace('addtwitter', 'addtwitter_exportacion', $match[0]);
        $shortcodeTwitter = do_shortcode($match[0]);
        $html .= '
        <div cmpName="cmp-codigo">
            <p class="voc-codigo">
            '.$shortcodeTwitter.'
            </p>
        </div>
        ';
        $content = str_replace($match[0], $html, $content);
    }
    return $content;
}

function handleTiktok($content)
{
    $html = '';
    $re = '/\[addtiktok(.*)]/m';
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
    foreach($matches as $match){
        $shortcodeTwitter = do_shortcode($match[0]);
        $html .= '
        <div cmpName="cmp-codigo">
            <p class="voc-codigo">
            '.$shortcodeTwitter.'
            </p>
        </div>
        ';
        $content = str_replace($match[0], $html, $content);
    }
    return $content;
}

function handleFacebook($content)
{
    $html = '';
    $re = '/\[addfacebook(.*)]/m';
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
    foreach($matches as $match){
        $shortcodeTwitter = do_shortcode($match[0]);
        $html .= '
        <div cmpName="cmp-codigo">
            <p class="voc-codigo">
            '.$shortcodeTwitter.'
            </p>
        </div>
        ';
        $content = str_replace($match[0], $html, $content);
    }
    return $content;
}

function handleBrightcove($content, $idpost)
{
    $pretitulo = get_post_meta($idpost, 'pretitulo', true);
    $idvideo = get_post_meta($idpost, 'idvideo', true);

    $html = '
        <photo-container cmpName="cmp-foto-video" class="voc-inner-figure voc-video">
            <photo height="503" width="503" vspace="1"></photo>
            <media>
                <a href="https://players.brightcove.net/6019218709001/rnKhcG3nS_default/index.html?videoId='.$idvideo.'&amp;accountname=womennow&amp;id=6019218709001&amp;title='.$pretitulo.'" title="'.$pretitulo.'">'.$pretitulo.'</a>
            </media>
            <span class="voc-pastilla"></span>
            <caption class="standard">
                <description>
                    <span class="voc-type">Vídeo.</span>
                </description>
                <credit>By WomenNow
                </credit>
            </caption>
        </photo-container>
    ';
    return $html;

}

function handlePodcast($content, $idpost)
{
    $pretitulo = get_post_meta($idpost, 'pretitulo', true);
    $subtitulo = get_post_meta($idpost, 'subtitulo', true);
    $audio = get_post_meta($idpost, 'audio', true);

    $html = '
    <div cmpName="cmp-codigo">
        <p class="voc-codigo">
        '.$audio.'
        </p>
    </div>';

    return $html;

}


function wp_get_attachment( $attachment_id ) {

    $attachment = get_post( $attachment_id );
    $filename = basename ( get_attached_file( intval($attachment_id) ) );
    $src = wp_get_attachment_image_src( intval($attachment_id), 'full' );
    $extension = wp_check_filetype($attachment->guid);

    return array(
        'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => get_permalink( $attachment->ID ),
        'src' => $attachment->guid,
        'title' => $attachment->post_title,
        'width' => $src[1],
        'height' => $src[2],
        'filename' => $filename,
        'filepath' => wp_get_original_image_path($attachment_id),
        'extension' => $extension['ext']
    );
}


function handleGallery($content, $idpost)
{
    $re = '/\[gallery(.*)ids="(.*)"]/m';
    preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
    $shortcodeGallery = do_shortcode($matches[0][0]);
    // var_dump($matches[0][0]); // esto para sustituir en el content
    setlocale(LC_TIME, 'es_ES.UTF-8');
    $ids = $matches[0][2];
    $ids_posts = explode (",", $ids); 
    $html = "";
    foreach($ids_posts as $id){
        $data = wp_get_attachment(intval($id));
        // var_dump($data);
        $UUID = gen_uuid();
        $html .= '
        <photo-container cmpName="cmp-foto-texto" class="voc-inner-figure">
            <photo height="'.$data['height'].'" tmx="624 624 624 624" width="'.$data['width'].'" xtransform="1 0 0 1 0 0" fileref="/mujerhoy_2017/noticias/'.date("Ym").'/'.date("d").'/media/cortadas/'.$data['filename'].'?uuid='.gen_uuid().'" vspace="1"></photo>
            <caption class="standard">
                <description>'.$data['caption'].'</description>
                <credit>'.$data['alt'].'</credit>
            </caption>
        </photo-container>
        ';

        $imagePath = $data['filepath'];
        $newPath = __DIR__."/xmls/".$idpost."/";
        $ext = $data['extension'];
        $newName  = $newPath.$data['filename'];
        $copied = copy($imagePath , $newName);

    }

    $content = str_replace($matches[0][0], $html, $content);
    return $content;

}

function handledContent($content, $idpost, $type){

    if ( ! is_dir( __DIR__."/xmls/".$idpost ) ) {
        wp_mkdir_p( __DIR__."/xmls/".$idpost );
    }

    // $instagram = strpos($content, '[addinstagram');
    // if( $instagram !== false ){
    //     $content = handleIntagram($content);
    // }

    // $youtube = strpos($content, '[addyoutube');
    // if( $youtube !== false ){
    //     $content = handleYoutube($content);
    // }

    // $twitter = strpos($content, '[addtwitter');
    // if( $twitter !== false ){
    //     $content = handleTwitter($content);
    // }

    // $tiktok = strpos($content, '[addtiktok');
    // if( $tiktok !== false ){
    //     $content = handleTiktok($content);
    // }

    // $facebook = strpos($content, '[addfacebook');
    // if( $facebook !== false ){
    //     $content = handleFacebook($content);
    // }

    $gallery = strpos($content, '[gallery');
    if( $gallery !== false){
        $content = handleGallery($content, $idpost);
    }

    // $content = handleImages($content, $idpost);
    $content = handleStrongTag($content, $idpost);

    if( $type == 'audio' )
    {
        $content = handlePodcast($content, $idpost);
    }
    
    if( $type == 'videocpt' )
    {
        $content = handleBrightcove($content, $idpost);
    }

    return $content;
}

// function downLoadXML()
// {
//     $zip_dir = WP_PLUGIN_DIR . '/exportar_wmw/zip/';
//     $totalFileDir = WP_PLUGIN_DIR . '/exportar_wmw/total/';
//     $cdir = scandir($zip_dir);

//     foreach ($cdir as $key => $value) {
//         if (!in_array($value, array(".","..",".DS_Store"))) {
//             $zip = new ZipArchive();
//             //create the file and throw the error if unsuccessful
//             if ($zip->open($totalFileDir.'zip.zip', ZIPARCHIVE::CREATE )==TRUE) {
//                 $zip->addFile($zip_dir.$value,$value);
//                 $zip->close();
//             }

//         }
//     }

//     $zip_file_dir = $totalFileDir . 'zip.zip';

//     header("Content-type: application/zip"); 
//     header("Content-Disposition: attachment; filename=zip.zip");
//     header("Content-length: " . filesize($zip_file_dir));
//     header("Pragma: no-cache"); 
//     header("Expires: 0"); 
//     readfile("$zip_file_dir");
//     exit;
// }