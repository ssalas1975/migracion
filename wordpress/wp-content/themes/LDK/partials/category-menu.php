<div class="col-c-module category-menu">
<div class="category-menu-title">
<?php
if(is_category())
	$title= single_cat_title();
else
	$title="Categorías";

echo $title;
?>

<i class="fa fa-angle-down"></i>
</div>
<ul>
<?php 
foreach(get_categories() as $category){

if(ldk_category_count($category->term_id))
	echo '<li><a href="'.get_category_link($category->term_id).'">'.$category->name."</a></li>";
}
?>
</ul>
</div>