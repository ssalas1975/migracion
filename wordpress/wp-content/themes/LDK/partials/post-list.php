<?php setup_postdata($post_home);?>
<div class="post-list">
    <div class="prop">
        <span><?php echo ldk_get_hours_sice($post_home->post_date)?></span> | 
        <span class="orange category-name"><?php echo ldk_get_first_category($post_home)?></span>
    </div>
    <h2 class="title">
        <a href="<?php echo get_permalink($post_home->ID)?>"><?php echo get_the_title($post_home)?></a>
    </h2>
    <div class="content">

        <?php if(has_post_thumbnail($post_home->ID)){  ?>
         <a href="<?php echo get_permalink($post_home->ID)?>"><amp-img src="<?php echo ldk_get_thumbnail_post($post_home,'post-list')?>" alt="<?php  echo get_the_title($post_home)?>" class="post-image" height="140" width="240" layout="responsive"></amp-img></a>
        <?php } 
        
              echo substr(strip_tags(get_the_content()),0,350)."..."?>
    </div>
   <div class="more"> <a class="more-link" href="<?php echo get_the_permalink($post_home->ID)?>">Ver más</a><span>|</span>
           <div class="share">
               <i class="fa fa-share-alt"></i>
            <div class="nets">
            <div class="share-item share-fb"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink($post_home->ID)?>"><i class="fa fa-facebook"></i></a></div>
            <div class="share-item share-tw"><a target="_blank" href="https://twitter.com/home?status=<?php echo get_the_permalink($post_home->ID)?>"><i class="fa fa-twitter"></i></a></div>
            <div class="share-item share-li"><a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_the_permalink($post_home->ID)?>&title=&summary=&source="><i class="fa fa-linkedin"></i></a></div>
			 <div class="share-item share-gp"><a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink($post_home->ID)?>"><i class="fa fa-google-plus"></i></a></div>
            </div>
        </div>
    </div>
</div>
<?php wp_reset_postdata();?>