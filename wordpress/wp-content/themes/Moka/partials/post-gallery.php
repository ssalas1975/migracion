<?php
	$images = acf_photo_gallery('galeria', $postsingle->ID);
	if( count($images) ){
		foreach($images as $image){
				$url=moka_get_image_gallery($image['id']);
				if($url)
				echo '<img src="'.$url.'" class="detalle-blog">';
			}	
	}