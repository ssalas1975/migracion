<amp-state id="col-c-module  relatedposts">
  <!--<script type="application/json">
    {
      "page": 0,
      "total_pages":0,
      "moreposts": {
		  "src":"<?php echo get_stylesheet_directory_uri().'/related-page.php?ID='.$post->ID?>"
	  }
    }
  </script>-->
</amp-state>

<?php 
$medio=ldk_get_medio();
$postID=$post->ID;
$ipp=8;
$rel=ldk_get_related(0,$postID,$ipp);
if($rel && count($rel)>0){
?>
<div class="related-posts"  id="post-relarted-anhor">

    <div class="module-title">
        también te puede interesar
    </div>

    <div class="container">
        <?php // ya no es necesario sacar related con json porque no hay paginación ?>
      <!--<amp-list width="auto"
      height="200"
	  layout="flex-item"
	  src="<?php echo get_stylesheet_directory_uri().'/related-page.php?ID='.$post->ID?>"
	  [src]="relatedposts.moreposts.src"
	  class="hidden"
	  [class]="relatedposts.moreposts.visible"
	  >
	  <template type="amp-mustache"
		id="amp-template-related">
          {{#posts}}
            <div class="post-list">
                <div class="prop">
                    <span>{{created_since}}</span> | 
                    <span class="orange category-name"><a href="{{category_link}}">{{category_name}}</a></span>
                </div>
                <h2 class="title">
                    <a href="{{permalink}}">{{post_title}}</a>
                </h2>
                <div class="content">
                    {{#image}}
                       <a href="{{permalink}}"><amp-img src="{{image}}" alt="{{post_title}}>" class="post-image" height="140" width="240"  layout="responsive"></amp-img></a>
                    {{/image}}
                    {{post_excerpt}}         
                </div>
               <div class="more"><a class="more-link" href="{{permalink}}">Ver más</a><span>|</span>
                       <div class="share">
                           <i class="fa fa-share-alt"></i>
                        <div class="nets">
                        <div class="share-item share-fb"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{permalink}}"><i class="fa fa-facebook"></i></a></div>
                        <div class="share-item share-tw"><a target="_blank" href="https://twitter.com/home?status={{permalink}}"><i class="fa fa-twitter"></i></a></div>
                        <div class="share-item share-li"><a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url={{permalink}}&title={{post_title}}&summary=&source="><i class="fa fa-linkedin"></i></a></div>
						<div class="share-item share-gp"><a target="_blank" href="https://plus.google.com/share?url={{permalink}}"><i class="fa fa-google-plus"></i></a></div>
          
                        </div>
                    </div>
                </div>
            </div>-->
            <?php // ya se pueden sacar los related de forma "tradicional" ?>
            <?php foreach($rel as $realted){
                
                $first_category=ldk_get_first_category_object($realted);
                $post_title=$realted->post_title;
                $post_content=strip_tags($realted->post_content);
                $post_excerpt=substr(strip_tags(apply_filters('the_excerpt', $realted->post_excerpt)),0,200);
                if(! $post_excerpt )
                    $post_excerpt=substr(strip_tags($realted->post_content),0,200).'...';
                $permalink=get_the_permalink($realted->ID);
                $image=ldk_get_thumbnail_post($realted,'post-list');
                $category_name=$first_category->name;
                $category_link=get_category_link($first_category);
                $created_since=ldk_get_hours_sice($realted->post_date);
                //array_push($related_array['items'][0]['posts'],$item); ?>
                <div class="post-list">
                    <div class="prop">
                        <span><?php echo $created_since; ?></span> | 
                        <span class="orange category-name"><a href="<?php echo $category_link; ?>"><?php echo $category_name; ?></a></span>
                    </div>
                    <h2 class="title">
                        <a href="<?php echo $permalink; ?>"><?php echo $post_title; ?></a>
                    </h2>
                    <div class="content">
                        
                           <a href="<?php echo $permalink; ?>"><amp-img src="<?php echo $image; ?>" alt="<?php echo $post_title; ?>>" class="post-image" height="140" width="240"  layout="responsive"></amp-img></a>
                       
                        <?php echo $post_excerpt; ?>         
                    </div>
                   <div class="more"><a class="more-link" href="<?php echo $permalink; ?>">Ver más</a><span>|</span>
                           <div class="share">
                               <i class="fa fa-share-alt"></i>
                                <div class="nets">
                                <div class="share-item share-fb"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink; ?>"><i class="fa fa-facebook"></i></a></div>
                                <div class="share-item share-tw"><a target="_blank" href="https://twitter.com/home?status=<?php echo $permalink; ?>"><i class="fa fa-twitter"></i></a></div>
                                <div class="share-item share-li"><a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&title=<?php echo $post_title; ?>&summary=&source="><i class="fa fa-linkedin"></i></a></div>
                                <div class="share-item share-gp"><a target="_blank" href="https://plus.google.com/share?url=<?php echo $permalink; ?>"><i class="fa fa-google-plus"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
          <!--{{/posts}}-->

       
         
         <!-- <div class="pagination">
                <a href="#post-relarted-anhor" class="page arrow" tabindex="0" role="button" on="tap:AMP.setState({relatedposts:{page:0,moreposts:{src: '<?php echo get_stylesheet_directory_uri().'/related-page.php?ID='.$post->ID.'&page=0'?>',visible:'visible'}}})">
                   <i class="fa fa-caret-left"></i>
                </a>
                {{#pages.pagenumber}}
                <a href="#post-relarted-anhor" role="button" tabindex="{{page_index}}" class="page{{pagenumber}} {{class}}" on="tap:AMP.setState({relatedposts:{page:{{page_index}},moreposts:{src: '<?php echo get_stylesheet_directory_uri().'/related-page.php?ID='.$post->ID.'&page='?>{{page_index}}',visible:'visible'}}})">{{text}}</a>
                {{/pages.pagenumber}}
              {{#pages.next}}
               <a href="#post-relarted-anhor" class="page arrow" tabindex="100" role="button" on="tap:AMP.setState({relatedposts:{page:{{pages.next}},moreposts:{src: '<?php echo get_stylesheet_directory_uri().'/related-page.php?ID='.$post->ID.'&page='?>{{pages.next}}',visible:'visible'}}})">
                    <i class="fa fa-caret-right"></i>
               </a>
              {{/pages.next}}
           </div>-->
          <!--{{/pages.pagenumber.length}}
         
	  </template>
	</amp-list>-->


        <?php

       // $related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' =>4, 'post__not_in' => array($post->ID) ) );
       // if( $related ) foreach( $related as $realted_post ) {
       //     set_query_var( 'post_home', $realted_post );
       //     get_template_part('partials/post','list');
       //     } ?>

    </div>
</div>
<?php } ?>