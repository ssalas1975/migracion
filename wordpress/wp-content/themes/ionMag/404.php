<?php

//set the template id, used to get the template specific settings
$template_id = '404';

td_global::$current_template = $template_id;

td_global::$load_featured_img_from_template = 'full';

//prepare the loop variables
global $loop_module_id, $loop_sidebar_position;
$loop_sidebar_position = 'no_sidebar';
$loop_module_id = td_util::get_option('tds_' . $template_id . '_page_layout', 1); //module 1 is default

get_header();

?>
    <div class="td-main-content-wrap td-container-wrap">
        <div class="td-container">
        <div class="td-crumb-container">
                <?php echo td_page_generator::get_archive_breadcrumbs(); // get the breadcrumbs - /includes/wp_booster/td_page_generator.php ?>
            </div>
            <div class="td-pb-span12">
                <div class="td-404-head">
                    <div class="td-404-title">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/404/404error.png">
                    </div>

                    <div class="td-404-sub-title">
                        WHOOOOOPS!
                    </div>

                    <div class="td-404-sub-sub-title">
                        Lo sentimos, ¡esa página no existe!
                    </div>

                    <div class="td-404-sub-404">
                        404
                    </div>

                    <a href="<?php echo get_home_url(); ?>" class="btn btn-primary btn-block error-button">Volver</a>

                </div>

            </div>
        </div> <!-- /.td-container -->
    </div> <!-- /.td-main-content-wrap -->

<?php
get_footer();
?>