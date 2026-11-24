<?php
/**
 * The single post loop Default template
 **/

if (have_posts()) {
    the_post();
    $td_mod_single = new td_module_single($post);
    $importContent = get_post_meta($td_mod_single->post->ID, 'importedmagento', true);
    ?>

    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
        <div class="td-post-header">
                    <div class="td-post-source-tags detallenoticia">
                        <div class="td-404-sub-sub-title head_blog_movil">BLOG</div>          
                    </div>
            <?php //echo $td_mod_single->get_category(); ?>
            <a href="<?php echo str_replace("blog.pidecita.com", "www.pidecita.com", esc_url( get_category_link( get_the_category()[0]->term_id ) )); ?>" class="td-category"><?php echo esc_html(get_the_category()[0]->name ); ?></a>
 
            <header class="td-post-title">
                <?php echo $td_mod_single->get_title();?>
                <?php if (!empty($td_mod_single->td_post_theme_settings['td_subtitle'])) { ?>
                    <p class="td-post-sub-title"><?php echo $td_mod_single->td_post_theme_settings['td_subtitle'];?></p>
                <?php } ?>
            </header>

        </div>

        <div class="td-post-content">

        <?php
        
        if($importContent == '1'){
            require_once(__DIR__.'/galleryImportPost.php');
        }

        // override the default featured image by the templates (single.php and home.php/index.php - blog loop)
        if (!empty(td_global::$load_featured_img_from_template)) {
            $content_head = $td_mod_single->get_image(td_global::$load_featured_img_from_template);
            $content_head = str_replace('srcset="/wp-content/uploads','srcset="/blog/wp-content/uploads', $content_head);
            echo str_replace(' /wp-content/uploads','/blog/wp-content/uploads', $content_head);
        } else {
            $content_head = $td_mod_single->get_image('td_640x0');
            $content_head = str_replace('srcset="/wp-content/uploads','srcset="/blog/wp-content/uploads', $content_head);
            echo str_replace(' /wp-content/uploads','/blog/wp-content/uploads', $content_head);
        }
        ?>
        <div class="td-module-meta-info">
         <?php //echo $td_mod_single->get_author();?>
         <?php echo 'Publicado el '.$td_mod_single->get_date(false);?>
         <?php //echo $td_mod_single->get_comments();?>
         <?php echo do_shortcode( "[TheChamp-Sharing]" );?>
        </div> 
        <?php
         $content = $td_mod_single->get_content();
         $content = str_replace('srcset="/wp-content/uploads','srcset="/blog/wp-content/uploads', $content);
         echo str_replace(' /wp-content/uploads','/blog/wp-content/uploads', $content);
        ?>
        </div>

        <?php     
            if($importContent == '1'){
                echo '<p class="td-post-sub-title">'.get_post_meta($td_mod_single->post->ID,'address', true).'</p>';
                echo '
                <div class="static-map-container microsite-map">
                    <a href="#" class="map-btn" data-toggle="modal" data-target="#map-dynamic-modal">
                        <img src="'.get_post_meta($td_mod_single->post->ID, 'image_map', true).'" class="img-map" alt="'.get_post_meta($td_mod_single->post->ID,'address', true).'" title="'.get_post_meta($td_mod_single->post->ID,'address', true).'">
                    </a>
                </div>
                ';
            }
        ?>
        

        <footer>
            <?php //echo $td_mod_single->get_post_pagination();?>
            <div class="td-post-source-tags">
                <?php echo $td_mod_single->get_source_and_via();?>
                <?php echo $td_mod_single->get_the_tags();?>
            </div>
            <?php echo $td_mod_single->get_next_prev_posts();?>
        </footer>
    </article> <!-- /.post -->

   

<?php
} else {
    //no posts
    echo td_page_generator::no_posts();
}