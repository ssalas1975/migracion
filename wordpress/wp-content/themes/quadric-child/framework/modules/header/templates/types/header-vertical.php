<?php do_action('edgtf_quadric_before_page_header'); ?>
<aside class="edgtf-vertical-menu-area">
    <div class="edgtf-vertical-menu-area-inner">
        <div class="edgtf-vertical-area-background" <?php edgtf_quadric_inline_style(array($vertical_header_background_color,$vertical_header_opacity,$vertical_background_image)); ?>></div>
        <?php if(!$hide_logo) {
            edgtf_quadric_get_logo();
        } ?>
        <?php edgtf_quadric_get_vertical_main_menu(); ?>
        <div class="edgtf-vertical-area-widget-holder">
            <?php if(is_active_sidebar('edgtf-vertical-area')) : ?>
                <?php dynamic_sidebar('edgtf-vertical-area'); ?>
            <?php endif; ?>
        </div>
    </div>
</aside>

<?php do_action('edgtf_quadric_after_page_header'); ?>