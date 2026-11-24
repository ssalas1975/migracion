<?php
//$pages=ldk_get_home_pages_count();
//if(is_category() || is_tag() || is_search()){
//    global $wp_query;
//    $pages=$wp_query->max_num_pages;
//}
//$page=ldk_get_query_page();
?>

<div class="pagination">
    <?php echo paginate_links(array('base'=>ldk_get_current_medio_meta('url').'%_%','prev_text'=>'<i class="fa fa-caret-left"></i>','next_text'=>'<i class="fa fa-caret-right"></i>','format'=> 'page/%#%'))?>

</div>
