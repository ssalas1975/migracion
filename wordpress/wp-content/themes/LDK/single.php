<?php


get_header();

?>
<article class="amp-wp-article post-single">
	<header class="amp-wp-article-header">
		<h1 class="amp-wp-title"><?php echo the_title(); ?></h1>
	</header>

     <div class="prop">
        <span><?php echo ldk_get_hours_sice($post->post_date)?></span> | 
        <span class="orange category-name"><?php echo ldk_get_first_category($post)?></span>
    </div>
		
      <?php if(has_post_thumbnail($post->ID)){  ?>
       <amp-img src="<?php echo ldk_get_thumbnail_post($post,'post-featured')?>" alt="<?php  echo get_the_title($post)?>" class="post-image" height="378" width="672" layout="responsive"></amp-img>
		
		 <div class="nets single">
            <div class="share-item share-fb"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink($post->ID)?>"><i class="fa fa-facebook"></i></a></div>
            <div class="share-item share-tw"><a target="_blank" href="https://twitter.com/home?status=<?php echo get_the_permalink($post->ID)?>"><i class="fa fa-twitter"></i></a></div>
            <div class="share-item share-li"><a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_the_permalink($post->ID)?>&title=&summary=&source="><i class="fa fa-linkedin"></i></a></div>
			 <div class="share-item share-gp"><a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink($post->ID)?>"><i class="fa fa-google-plus"></i></a></div>
         </div>
		
     <?php }?> 

    <div class="post-content">
        <?php if ($post->post_excerpt){?>
		<h2 class="post-excerpt">
            <?php echo the_excerpt() ?>
        </h2>
		<?php } ?>

         <?php echo the_content() ?>

    </div>

    <div class="post-tags">
        <ul>
        <?php foreach(wp_get_post_tags($post->ID) as $tag){
                  echo "<li><a href='".get_tag_link($tag->term_id)."'>".$tag->name."</a></li>" ;  
              }?>
         </ul>
    </div>

</article>

<?php get_template_part('partials/contact','form'); ?>

<?php get_template_part('partials/carrusel','empresas'); ?>

<?php get_template_part('partials/related','posts'); ?>

<?php

get_footer();

?>