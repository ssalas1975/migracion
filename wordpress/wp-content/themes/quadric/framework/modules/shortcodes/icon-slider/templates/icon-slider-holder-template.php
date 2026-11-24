<?php
global $edgtfQuadricIconCollections;
?>
<div class="edgtf-icon-slider-container">
    <div class="edgtf-icon-slider-controls-holder" <?php edgtf_quadric_inline_style($nav_style); ?> <?php echo edgtf_quadric_get_inline_attrs($nav_data); ?>>
        <div class="edgtf-icon-slider-controls-holder-inner">
        <?php 
        preg_match_all('/\[edgtf_icon_slider([^\]]*)\]/', $content, $matches, PREG_OFFSET_CAPTURE);
        $counter = 0;
        foreach ($matches[0] as $icon_family){
            preg_match('/icon_family="([^\"]+)/i', $icon_family[0], $icon_family_match, PREG_OFFSET_CAPTURE);
            $icon_collection_obj = $edgtfQuadricIconCollections->getIconCollection($icon_family_match[1][0]);
            preg_match('/icon_'.$icon_collection_obj->param.'\=\"([^\"]+)\"/i', $icon_family[0], $icon_match, PREG_OFFSET_CAPTURE);?>
            <div class="edgtf-icon-slider-nav-icon" <?php edgtf_quadric_inline_style($icon_style); ?>>
                <?php
                if (method_exists($icon_collection_obj, 'render')) { 
                    $icon_label = '';        
                    print $icon_collection_obj->render($icon_match[1][0], array(
                        'icon_attributes' => array(
                            'style' => '',
                            'class' => 'edgtf-icon-slider-icon'.($counter==0?' active':'')
                        )
                    ));
                }                
                ?>
            </div>
        <?php
            ++$counter;
        }
        ?>
        </div>  <!-- close .icon-slider-controls-holder-inner -->
    </div> <!-- close .icon-slider-controls-holder -->
    
    <div class="edgtf-icon-slider-container-inner">
        <?php echo do_shortcode($content); ?>
    </div>    

</div> <!-- close .icon-slider-container -->