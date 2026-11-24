<div class="td-footer-wrapper td-footer-template-2 <?php echo td_util::get_option('td_full_footer'); ?>">
    <div class="td-container">
        <div class="td-pb-row">
            <div class="td-pb-span4">
                <?php dynamic_sidebar('Footer 1'); ?>
            </div>

            <div class="td-pb-span4 social">
                <?php dynamic_sidebar('Footer 2'); ?>
            </div>

            <div class="td-pb-span4 incluye_tu_negocio">
                <?php dynamic_sidebar('Footer 3'); ?>
            </div>

        </div>

        <div class="td-pb-row">
            <?php //locate_template('parts/footer/td_footer_extra_bottom.php', true); ?>
        </div>
    </div>
</div>