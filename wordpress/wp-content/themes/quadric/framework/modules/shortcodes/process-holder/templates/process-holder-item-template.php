<div class='edgtf-process-holder-item' <?php edgtf_quadric_inline_style($item_style); ?>>
    <div class='edgtf-process-top-holder'>
    	<?php if($image_shadow == 'yes'){ ?>
        <span class="edgtf-process-shadow"></span>
        <?php } ?>
        <img alt='img' src="<?php echo esc_url($image); ?>">
        <?php if($item_number != ''){ ?>
        <span class='edgtf-process-number'><?php echo esc_attr($item_number); ?></span>
        <?php } ?>
    </div>
    <div class='edgtf-process-bottom-holder'>
        <<?php echo esc_attr($title_tag); ?> class='edgtf-process-title'><?php echo esc_attr($item_title); ?></<?php echo esc_attr($title_tag);?>>
        <p><?php echo esc_attr($item_text); ?></p>
    </div>
</div>