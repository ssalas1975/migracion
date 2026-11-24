<?php if(edgtf_quadric_options()->getOptionValue('portfolio_single_hide_date') !== 'yes') : ?>

    <div class="edgtf-portfolio-info-item edgtf-portfolio-date">
        <span class="edgtf-portfolio-info-title"><i class="icon-calender"></i><?php esc_html_e('Date', 'quadric'); ?></span>

        <p><?php the_time(get_option('date_format')); ?></p>
    </div>

<?php endif; ?>