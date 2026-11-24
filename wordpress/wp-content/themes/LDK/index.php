<?php


get_header();

?>
<div class="main-col">
    <section class="amp-ldk-home">

	    <?php 

        $index=0;

        $page=ldk_get_query_page();
        
        foreach(ldk_get_home_posts(false,$page) as $post_home){
                  set_query_var( 'post_home', $post_home );
                  
                  if($index==0 && $page==0)
                    get_template_part('partials/post','featured');
                  else
                    get_template_part('partials/post','list');

                  $index++;
         }

        //Paginacion
      
        get_template_part('partials/post','pagination');
        
        ?>
	

    </section>
</div>

<?php 
    
get_sidebar();


get_footer();

?>