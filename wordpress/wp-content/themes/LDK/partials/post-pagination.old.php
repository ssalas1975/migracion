<?php

$pages=ldk_get_home_pages_count();

if(is_category() || is_tag() || is_search()){
    global $wp_query;
    $pages=$wp_query->max_num_pages;
}

$page=ldk_get_query_page();



?>
<div class="pagination">
    <?php $prev_page=($page-1>=1)?(ldk_get_current_medio_meta('akamai_blog_path',true)."page/".($page)):"#";    ?>

    <span class="page arrow">
       <a href="<?php echo $prev_page?>"><i class="fa fa-caret-left"></i></a>
    </span>

    <?php

    //Page numbers


    //From page to page..
    $start_page=$page-2;
    if($start_page<0)
        $start_page=0;

    $end_page=$start_page+4;

    if($end_page>$pages)
    {
        $end_page=$pages;
        $start_page=$end_page-4;
        if($start_page<0)
            $start_page=0;
    }


    for($i=$start_page;$i<=$end_page;$i++){
    $page_active=($i==$page)?" active":"";
    ?>
    <span class="page<?php echo $page_active?>"><a href="<?php echo ldk_get_current_medio_meta('akamai_blog_path',true)?>page/<?php echo $i+1?>"><?php echo ($i+1)?></a></span>
    <?php
    }

    $next_page=($pages>=$page+1)?(ldk_get_current_medio_meta('akamai_blog_path',true)."page/".($page+2)):"#";

    //Next page
    ?>

    <span class="page arrow">
        <a href="<?php echo $next_page?>"><i class="fa fa-caret-right"></i></a>
    </span>


</div>
