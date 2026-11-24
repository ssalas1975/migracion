<?php if(edgtf_quadric_options()->getOptionValue('portfolio_single_hide_pagination') !== 'yes') : ?>

    <?php
    $back_to_link = get_post_meta(get_the_ID(), 'edgtf_portfolio_single_back_to_link', true);
    $nav_same_category = edgtf_quadric_options()->getOptionValue('portfolio_single_nav_same_category') == 'yes';
    ?>

    <div class="edgtf-portfolio-single-nav">
        <?php if(get_previous_post() !== '') : ?>
            <div class="edgtf-portfolio-prev">
                <?php if($nav_same_category) {
                    previous_post_link('%link', '<span class="arrow_carrot-left edgtf-navigation-icon"></span><span class="edgtf-portfolio-prev-next-text">Previous Project</span>', true, '', 'portfolio-category');
                } else {
                    previous_post_link('%link', '<span class="arrow_carrot-left edgtf-navigation-icon"></span><span class="edgtf-portfolio-prev-next-text">Previous Project</span>');
                } ?>
            </div>
        <?php endif; ?>

        <?php if($back_to_link !== '') : ?>
            <div class="edgtf-portfolio-back-btn">
                <a href="<?php echo esc_url(get_permalink($back_to_link)); ?>">
                    <span class="icon-grid"></span>
                </a>
            </div>
        <?php endif; ?>

        <?php if(get_next_post() !== '') : ?>
            <div class="edgtf-portfolio-next">
                <?php if($nav_same_category) {
                    next_post_link('%link', '<span class="edgtf-portfolio-prev-next-text">Next Project</span><span class="arrow_carrot-right edgtf-navigation-icon"></span>', true, '', 'portfolio-category');
                } else {
                    next_post_link('%link', '<span class="edgtf-portfolio-prev-next-text">Next Project</span><span class="arrow_carrot-right edgtf-navigation-icon"></span>');
                } ?>
            </div>
        <?php endif; ?>
    </div>

<?php endif; ?>