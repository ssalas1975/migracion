<?php

function vcbc_shortcode( $atts, $content = null ) {

    if(is_single() || is_archive() || is_home())
    {

        if($atts["medio"])
        {

            $medio=vcbc_get_medio_id($atts["medio"]);
            if($medio){

                if($atts["player"]){

                    if($atts["player"]=="brightcove"){
                        return vcbc_shortcode_brightcove_player($medio,$atts);
                    }
                    else{
                        return vcbc_shortcode_vocento_player($medio,$atts);
                    }
                }
                else{

                    if($medio["vcbc_vocento_player"] ){
                        return vcbc_shortcode_vocento_player($medio,$atts);
                    }
                    else{
                        return vcbc_shortcode_brightcove_player($medio,$atts);
                    }
                }

            }
        }
    }
}

function vcbc_shortcode_bc( $atts, $content = null ) {


    if(is_single() || is_archive() || is_home())
    {

        if($atts["account_id"])
        {

            $medio=vcbc_get_medio_account($atts["account_id"]);
            if($medio){

                if($atts["video_id"])
                    $atts["videoid"]=$atts["video_id"];

                if($atts["player_id"]){

                    if($atts["player_id"]=="brightcove"){
                        return vcbc_shortcode_brightcove_player($medio,$atts);
                    }
                    else{
                        return vcbc_shortcode_vocento_player($medio,$atts);
                    }
                }
                else{

                    if($medio["vcbc_vocento_player"] ){
                        return vcbc_shortcode_vocento_player($medio,$atts);
                    }
                    else{
                        return vcbc_shortcode_brightcove_player($medio,$atts);
                    }
                }

            }
        }
    }
}



function vcbc_shortcode_brightcove_player_inline($medio,$atts){


    $accid=$medio['vcbc_medio_accid'];
    $videoid=$atts['videoid'];

    $html='';
    $html.='<video data-video-id="'.$videoid.'" id="vid-'.$videoid.'"';
    $html.='data-account="'.$accid.'"';
    $html.='data-embed="default"';
    $html.='class="video-js"';
    $html.='controls></video>';
    $html.='<link href="https://vjs.zencdn.net/5.10.8/video-js.css" rel="stylesheet">';
    $html.='<script src="https://vjs.zencdn.net/5.10.8/video.js"></script>';
    $html.='<script type="text/javascript">';
    $html.="jQuery(document).ready(function(){";
    $html.="   videojs(\"vid-".$videoid."\").on('loadedmetadata', function () {";
    $html.='   var myPlayer = this;';
    $html.='   myPlayer.play();';
    $html.='});';
    $html.='});';
    $html.='</script>';

    return $html;
}

function vcbc_shortcode_brightcove_player($medio,$atts){
    $html='';



    if ( 0 === $atts["width"] && 0 === $atts["height"] ) {
        $html.='<div style="display: block; position: relative; max-width: 100%;"><div style="padding-top: 56.25%;">';
    }

    $width="100%";
    if($atts["width"]){
        $width=$atts["width"];
    }

    $height="100%";
    if($atts["height"]){
        $height=$atts["height"];
    }

    $playerid="default";
    if($atts["bc_player_id"]){
        $playerid=$atts["bc_player_id"];
    }
    else{
        if($medio["vcbc_vocento_player_bc_id"]){
            $playerid=$medio["vcbc_vocento_player_bc_id"];
        }
    }

    $autoStart=(($medio["vcbc_vocento_auto_start"])?'&autoplay':'');

    if($atts["autostart"] && $atts["autostart"]=="true"){
        $autoStart="autoplay";
    }

    $class="";
    if($height!="100%"){
        $class="fixed-height";
    }

    $accid=$medio['vcbc_medio_accid'];
    $videoid=$atts['videoid'];


    //if($height && $width){
    //    $html.="<iframe class='vcbc-frame-bc $class' frameborder='0' style='width:$width; height:$height;' width='$width' height='$height' src='//players.brightcove.net/$accid/$playerid"."_default/index.html?videoId=$videoid$autoStart' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>";

    //}
    //else{
    //    $html.="<iframe class='vcbc-frame-bc $class' frameborder='0' style='width:$width; height:$height;' src='//players.brightcove.net/$accid/$playerid"."_default/index.html?videoId=$videoid$autoStart' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>";
    //}

    $html.="<div id='vcbcp-".$videoid."'></div>";




    if ( 0 === $width && 0 === $height ) {

        $html.='</div></div>';

    }

    $html.='<!-- End of Brightcove Player -->';
    $html.='<script>';
    if($height && $width){
        $html.="var ifr_".$videoid."=\"<iframe class='vcbc-frame-bc $class' frameborder='0' style='width:$width; height:$height;' width='$width' height='$height' src='//players.brightcove.net/$accid/$playerid"."_default/index.html?videoId=$videoid$autoStart' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>\";";
    }
    else{
        $html.="var ifr_".$videoid."=\"<iframe class='vcbc-frame-bc $class' frameborder='0' style='width:$width; height:$height;' src='//players.brightcove.net/$accid/$playerid"."_default/index.html?videoId=$videoid$autoStart' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>\"";
    }

    $html.='jQuery(document).ready(function(){';
    $html.='jQuery("#vcbcp-'.$videoid.'").each(function(i,val){';
    $html.='if(!jQuery(val).hasClass(".fixed-height"))';
    $html.='{';
    $html.='jQuery(val).html(ifr_'.$videoid.');';
    $html.=' var vcbcw=jQuery(val).width();';
    $html.=' var vcbch=vcbcw/(16/9);';
    $html.='jQuery(val).height(vcbch);';
    $html.='jQuery(val).css("height",vcbch);';
    $html.='}';
    $html.=' });';

    $html.='});';
    $html.=' </script>';

    return $html;
}


function vcbc_shortcode_vocento_player($medio,$atts){


    ob_start();

    $autoStart=(($medio["vcbc_vocento_auto_start"])?'true':'false');
    if($atts["autostart"]){
        $autoStart=$atts["autostart"];
    }


    $modal=(($medio["vcbc_vocento_modal"])?'true':'false');
    if($atts["modal"]){
        $autoStart=$atts["modal"];
    }

    $resizer=(($medio["vcbc_vocento_resizer"])?'true':'false');
    if($atts["resizer"]){
        $resizer=$atts["resizer"];
    }

    $template=$medio["vcbc_vocento_template"];
    if($atts["template"]){
        $template=$atts["template"];
    }

    if ( $atts['idplayer'] == 'undefined' )
    {  
        $idplayer = '';
    }else{
        $idplayer = $atts['idplayer'];
    }


?>
<div id="video_<?php echo $atts["videoid"]?>"></div>
<!--p><?php echo $idplayer; ?></p-->
<!--p><?php var_dump($atts); ?></p-->
<script type="text/javascript">
    // var idplayer = '<?php echo $idplayer?>';
    // idplayer = ( idplayer === 'undefined' ) ? '<?php echo $medio["vcbc_vocento_player_bc_id"]?>' : '<?php echo $idplayer?>';
    var options_<?php echo $atts["videoid"]?> = {
        idDiv: 'video_<?php echo $atts["videoid"]?>',

        template: '<?php echo $template ?>',

        site: '<?php echo $medio["vcbc_vocento_site"]?>',

        autoStart: '<?php echo $autoStart?>',

        source: 'brightcove',

        id: '<?php echo $atts["videoid"]?>',
        <?php 
        if($idplayer != ''){ ?>
        idPlayer: '<?php echo $idplayer?>',
        <?php }else{
        if($medio["vcbc_vocento_player_bc_id"]){?>
        idPlayer: '<?php echo $medio["vcbc_vocento_player_bc_id"]?>',
        <?php }} ?>

        <?php if($atts["width"]){?>
        width: <?php echo $atts["width"]?>,
        <?php } ?>

		<?php if($atts["height"]){?>
        height: <?php echo $atts["height"]?>,
        <?php } ?>

        <?php if($atts["stillurl"]){?>
        stillURL: '<?php echo str_replace('http:','',$atts["stillurl"])?>',
        <?php } ?>

        resizer: '<?php echo $resizer ?>',

        modalLayer: '<?php echo $modal ?>'

    }


    if(!options_<?php echo $atts["videoid"]?>.width){
        var pw=jQuery('#video_<?php echo $atts["videoid"]?>').parent().width();
            options_<?php echo $atts["videoid"]?>.width=pw;
            options_<?php echo $atts["videoid"]?>.height=parseInt((pw*9)/16);
        }


        if(mediaVideo)
            mediaVideo.showVideoPlayer(options_<?php echo $atts["videoid"]?>);
            else
                console.log("mediaVideo no inicializado!");



</script>
<?php

    $output = ob_get_clean();
    return $output;
}

add_shortcode( 'vcbc', 'vcbc_shortcode' );

add_shortcode( 'bc_video', 'vcbc_shortcode_bc' );
