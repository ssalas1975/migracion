<?php if($icon_animation_holder) : ?>
    <span class="edgtf-icon-animation-holder <?php echo esc_attr($type);?>" <?php edgtf_quadric_inline_style($icon_animation_holder_styles); ?>>
<?php endif; ?>

    <span <?php edgtf_quadric_class_attribute($icon_holder_classes); ?> <?php edgtf_quadric_inline_style($icon_holder_styles); ?> <?php echo edgtf_quadric_get_inline_attrs($icon_holder_data); ?>>
        <?php if($link !== '') : ?>
            <a href="<?php echo esc_url($link); ?>" target="<?php echo esc_attr($target); ?>">
        <?php endif; ?>

        <?php echo edgtf_quadric_icon_collections()->renderIcon($icon, $icon_pack, $icon_params); ?>

        <?php if($link !== '') : ?>
            </a>
        <?php endif; ?>
        <?php if($icon_animation_holder) : ?>
            <span class='edgtf-animation-overlay' <?php edgtf_quadric_inline_style($icon_animation_overlay);?>></span>
        <?php endif; ?>
    </span>

<?php if($icon_animation_holder) : ?>
    </span>
<?php endif; ?>
