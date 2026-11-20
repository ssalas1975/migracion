<?php

$tag_name = get_term(get_queried_object()->term_id);

$args = array(
    'meta_key' => 'my_sticky_box_check',
    'meta_value' => 'on',
    'posts_per_page' => 1
);

$the_query = new WP_Query($args);
if ($the_query->have_posts()) :
    while ($the_query->have_posts()) : $the_query->the_post();
?>

<?php
$content = get_the_content();
preg_match('/^([^.!?\s]*[\.!?\s]+){0,50}/', strip_tags($content), $abstract);
?>

    <span class="td_module_5 td_module_wrap td-animation-stack">
    <span class="content-td-module-head-mov">
            <div class="td-category-wrap-header">
                <a href="<?php echo esc_url(get_category_link(get_the_category()[0]->term_id)); ?>" class="td-category"><?php echo esc_html(get_the_category()[0]->name); ?></a>
            </div>
            <h3 class="entry-title td-module-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
</span>
        <div class="td-module-image td-module-image-float">
            <div class="td-module-thumb">
                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
                    <img width="260" height="195" class="entry-thumb td-animation-stack-type0-2 img-sticky" src="<?php echo the_post_thumbnail_url(array(300,200)); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
                </a>
            </div> 
        </div>
        <div class="td-item-details td-category-small">
            <a href="<?php echo esc_url(get_category_link(get_the_category()[0]->term_id)); ?>" class="td-category head2"><?php echo esc_html(get_the_category()[0]->name); ?></a>
            
            <span class="content-td-module-head-des">
            <div class="td-module-meta-info">
                <span class="td-post-date">
                    <time class="entry-date updated td-module-date" datetime="<?php the_time("F d, Y"); ?>">
                        Publicado el <?php the_time("d"); ?> de <?php the_time("F"); ?>,<?php the_time("Y"); ?>
                    </time>
                </span> 
            </div>
            <h3 class="entry-title td-module-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
            <div class="td-excerpt-head">
            <?php echo $abstract[0] . '...'; ?> 
            </div>
            <div class="td-load-more-wrap-head">
                <a href="<?php the_permalink(); ?>" class="td_ajax_load_more">Sigue Leyendo</a>
            </div>
            </span>

            <span class="content-td-module-head-mov">
            <div class="td-module-meta-info">
                <span class="td-post-date">
                    <time class="entry-date updated td-module-date" datetime="<?php the_time("F d, Y"); ?>">
                        Publicado el <?php the_time("d"); ?> de <?php the_time("F"); ?>,<?php the_time("Y"); ?>
                    </time>
                </span>                
            </div>
            <div class="td-excerpt">
            <?php echo $abstract[0] . '...'; ?> 
            </div>
            <div class="td-load-more-wrap-footer">
                <a href="<?php the_permalink(); ?>" class="td_ajax_load_more">Seguir leyendo</a>
            </div>
            </span>

            
            
        </div>
        <div class="clearfix"></div>
    </div>

    


<?php
    endwhile;
 else:
?>
    
<?php
 endif;


/* Restore original Post Data */
wp_reset_postdata();


//var_dump($post[0]);

/**
 * If you are looking for the loop that's handling the single post page (single.php), check out loop-single.php
 * */
// $global_flag_to_hide_no_post_to_display - comes from page-category-big-grid.php and is a flag to hide the 'No posts to display' message if on category page there are between 1 and 5  posts
global $loop_module_id, $loop_sidebar_position, $global_flag_to_hide_no_post_to_display;

///if we are in wordpress loop; used by quotes in blocks to check if the blocks are displayed in blocks or in loop
td_global::$is_wordpress_loop = true;

$td_template_layout = new td_template_layout($loop_sidebar_position);

if (empty($loop_module_id)) {  //not sure if we need a default here
    $loop_module_id = 1;
}

$td_sidebar_position = '';
if ($loop_sidebar_position == 'no_sidebar') {
    $td_sidebar_position = 'td-module-no-sidebar';
} else {
    $td_sidebar_position = 'td-module-with-sidebar';
}

$td_module_class = td_api_module::_helper_get_module_class_from_loop_id($loop_module_id);


//disable the grid for some of the modules
$td_module = td_api_module::get_by_id($td_module_class);
if ($td_module['uses_columns'] === false) {
    $td_template_layout->disable_output();
}

$args = array(
    'post_type' => 'post',
    'tag_id' => get_queried_object()->term_id,
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key'     => 'my_sticky_box_check',
            'value'   => 'on',
            'compare' => '!=',
        ),
        array(
            'key'     => 'my_sticky_box_check',
            'compare' => 'NOT EXISTS',
        )
    ),
    'posts_per_page' => 6
);


$the_query = new WP_Query($args);
$cont_div = 0;
if ($the_query->have_posts()) :
    while ($the_query->have_posts()) : $the_query->the_post();
?>


<?php
if ($cont_div == 0) {
    echo '<div class="td-block-row">';
}
if (($cont_div % 3) == 0 && $cont_div != 0) {
    echo '</div><div class="td-block-row">';
}

?>
<div class="td-block-span4 tag">
        <div class="td-category-wrap-header">
        <a href="<?php echo str_replace("blog.pidecita.com", "www.pidecita.com", esc_url(get_category_link(get_the_category()[0]->term_id))); ?>" class="td-category">
            <?php echo esc_html(get_the_category()[0]->name); ?>
        </a>
        <h3 class="entry-title td-module-title upper-title">
            <a href="<?php echo str_replace("blog.pidecita.com", "www.pidecita.com", get_permalink()); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h3>
    </div>
        <div class="td_module_2 td_module_wrap td-animation-stack">
            <?php
            $importContent = get_post_meta(get_the_ID(), 'importedmagento', true);
            if($importContent == '1'){
            ?>
                <div class="td-module-image">
                <div class="td-module-thumb">
                    <a href="<?php echo str_replace("blog.pidecita.com", "www.pidecita.com", get_permalink()); ?>" rel="bookmark" title="<?php the_title(); ?>">
                    <img width="300" height="220" class="entry-thumb td-animation-stack-type0-2" src="<?php echo get_post_meta(get_the_ID(),'image_1', true); ?>" srcset="<?php echo get_post_meta(get_the_ID(),'image_1', true); ?> 300w, <?php echo get_post_meta(get_the_ID(),'image_1', true); ?> 80w, <?php echo get_post_meta(get_the_ID(),'image_1', true); ?> 198w" sizes="(max-width: 300px) 100vw, 300px" alt="" title="<?php the_title(); ?>"></a>
                </div>                
            </div>
            <?php
            }else{
            ?>
                <div class="td-module-image">
                    <div class="td-module-thumb">
                        <a href="<?php echo str_replace("blog.pidecita.com", "www.pidecita.com", get_permalink()); ?>" rel="bookmark" title="<?php the_title(); ?>">
                        <img width="300" height="220" class="entry-thumb td-animation-stack-type0-2" src="<?php echo the_post_thumbnail_url(array(300,220)); ?>" srcset="<?php echo the_post_thumbnail_url(array(300,220)); ?> 300w, <?php echo the_post_thumbnail_url(array(300,220)); ?> 80w, <?php echo the_post_thumbnail_url(array(300,220)); ?> 198w" sizes="(max-width: 300px) 100vw, 300px" alt="" title="<?php the_title(); ?>"></a>
                    </div>                
                </div>
            <?php
            }
            ?>
            

            <h3 class="entry-title td-module-title low-title"><a href="<?php echo str_replace("blog.pidecita.com", "www.pidecita.com", get_permalink()); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
            <div class="td-module-meta-info">
                <span class="td-post-date"><time class="entry-date updated td-module-date" datetime="<?php the_time("F d, Y"); ?>">Publicado el <?php the_time("d"); ?> de <?php the_time("F"); ?>,<?php the_time("Y"); ?> </time></span>               
            </div>

            <div class="td-excerpt">
                <?php the_excerpt(); ?>            
            </div>
                <div class="td-load-more-wrap-footer">
                    <a href="<?php echo str_replace("blog.pidecita.com", "www.pidecita.com", get_permalink()); ?>" class="td_ajax_load_more ">Seguir leyendo</a>
                </div>
        </div>
    </div> <!-- ./td-block-span4 -->
<?php

$cont_div ++;
?>


<?php
    endwhile;
    echo '</div>';
 endif;


/* Restore original Post Data */
wp_reset_postdata();


if (have_posts()) {
    ?>  
    <div class="td-modules-container td-module-number<?php echo $loop_module_id; ?> <?php echo $td_sidebar_position; ?>">
        <div id="1" class="td_block_inner" id="td-content-post-category"></div>
    </div>
    <?php echo do_shortcode('[ajax_load_more container_type="div" css_classes="td-block-row"  pause="true" post_type="post" posts_per_page="3" meta_key="my_sticky_box_check:my_sticky_box_check" meta_value="on:on" meta_compare="NOT EXISTS:!=" meta_type="CHAR:CHAR" meta_relation="OR" scroll_container="td-content-post-category"  tag="'.$tag_name->slug.'" button_label="Ver más artículos" button_loading_label="Ver más artículos" offset="3"]'); ?>
    <?php
} else {
        echo 'No hay publicaciones para mostrar';
    }
