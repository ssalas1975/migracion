<?php
$arrayImgs = [];
if(get_post_meta($td_mod_single->post->ID,'image_1', true)) array_push($arrayImgs, get_post_meta($td_mod_single->post->ID,'image_1', true));
if(get_post_meta($td_mod_single->post->ID,'image_2', true))  array_push($arrayImgs, get_post_meta($td_mod_single->post->ID,'image_2', true));
if(get_post_meta($td_mod_single->post->ID,'image_3', true))  array_push($arrayImgs, get_post_meta($td_mod_single->post->ID,'image_3', true));
if(get_post_meta($td_mod_single->post->ID,'image_4', true))  array_push($arrayImgs, get_post_meta($td_mod_single->post->ID,'image_4', true));
if(get_post_meta($td_mod_single->post->ID,'image_5', true))  array_push($arrayImgs, get_post_meta($td_mod_single->post->ID,'image_5', true));


echo '
    <link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/stilosgaleria.css">
    <div class="container">
        <div id="slider" style="width:35%;">
';
        $contador = 1;
        foreach($arrayImgs as $img){
            if($contador == 1){
                echo '<input checked type="radio" name="slider" id="slide1" />';
            }else{
                echo '<input type="radio" name="slider" id="slide'.$contador.'" />';
            }
            $contador ++;
        }

        if(count($arrayImgs) > 0){
            echo '
                <div id="slides">
                    <div id="overflow">
                        <div class="inner">
            ';
                            foreach($arrayImgs as $img){
                                if($img != ""){
                                    echo '
                                    <article>
                                        <img src="'.$img.'" />
                                    </article>
                                    ';
                                }
                            }
            echo '
                        </div>
                    </div>
                </div>
            ';
        }
echo '
            <div id="controls">
    ';
                $contador = 1;
                foreach($arrayImgs as $img){
                    if($img != ""){
                        echo '<label for="slide'.$contador.'"></label>';
                    }
                    $contador ++;
                }
echo '
            </div>
            <div id="active">
    ';
                $contador = 1;
                foreach($arrayImgs as $img){
                    if($img != ""){
                        echo '<label for="slide'.$contador.'"></label>';
                    }
                    $contador ++;
                }
echo '
            </div>
        </div>
    </div>


';