<div class="col-c-module blogs">
    <div class="title">Posts más leídos</div>
    <div class="blogs-content">
       <?php 
           
       $posts_visits=ldk_get_posts_visits();
	   $index=0;
	   
       foreach($posts_visits as $post){
		   if($index==5) break;
		   
		   $title=substr($post->post_title,0,50);
		   
		   if(strlen($title) < strlen($post->post_title))
			{$title.="...";}
       ?>
        <div class="blog-item">
			 <a href="<?php echo get_permalink($post->ID)?>"><amp-img src="<?php echo ldk_get_thumbnail_post($post,'logo-icon')?>" alt="<?php echo $post->post_title?>"  height="65" width="65"></amp-img></a>
            <a href="<?php echo get_permalink($post->ID)?>" class="txt-blog"><?php echo $title ?></a>
       </div>
       <?php
	   $index++;
       }
       
       ?>
    </div>
</div>