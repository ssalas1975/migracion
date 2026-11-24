<?php
class td_category_template_1 extends td_category_template {

    function render() {
        ?>
        <!-- subcategory -->
        <div class="td-category-header td-container-wrap">
            <div class="td-container">
                <div class="td-pb-row">
                    <div class="td-pb-span12">
                        <div class="td-category-crumb-filter">
                            <?php //echo parent::get_breadcrumbs(); ?>
                            <?php echo str_replace("blog.pidecita.com", "www.pidecita.com", parent::get_breadcrumbs() ); ?>
                            <?php //echo parent::get_pull_down(); ?>
                        </div>
                            <div class="td-post-source-tags">
                            <div class="td-404-sub-sub-title head_blog_movil">BLOG</div>          
                            </div>
                        <h1 itemprop="name" class="entry-title td-page-title">
                            <?php //echo parent::get_title(); ?>
                            <?php echo get_bloginfo('name'); ?>
                        </h1>
                        <h2 class="title-head"><?php echo get_bloginfo('description'); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
}
