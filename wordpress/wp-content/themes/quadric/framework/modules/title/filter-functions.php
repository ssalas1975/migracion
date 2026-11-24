<?php

if(!function_exists('edgtf_quadric_title_classes')) {
    /**
     * Function that adds classes to title div.
     * All other functions are tied to it with add_filter function
     * @param array $classes array of classes
     */
    function edgtf_quadric_title_classes($classes = array()) {
        $classes = array();
        $classes = apply_filters('edgtf_quadric_title_classes', $classes);

        if(is_array($classes) && count($classes)) {
            echo implode(' ', $classes);
        }
    }
}

if(!function_exists('edgtf_quadric_title_type_class')) {
    /**
     * Function that adds class on title based on title type option
     * @param $classes original array of classes
     * @return array changed array of classes
     */
    function edgtf_quadric_title_type_class($classes) {
        $id = edgtf_quadric_get_page_id();

        if(get_post_meta($id, "edgtf_title_area_type_meta", true) !== ""){
            $title_type = get_post_meta($id, "edgtf_title_area_type_meta", true);
        }else {
            $title_type = edgtf_quadric_options()->getOptionValue('title_area_type');
        }

        $classes[] = 'edgtf-'.$title_type.'-type';

        return $classes;

    }

    add_filter('edgtf_quadric_title_classes', 'edgtf_quadric_title_type_class');
}

if(!function_exists('edgtf_quadric_title_background_image_classes')) {
    function edgtf_quadric_title_background_image_classes($classes) {
        //init variables
        $id                         = edgtf_quadric_get_page_id();
        $is_img_responsive 		    = '';
        $is_image_parallax		    = '';
        $is_image_parallax_array    = array('yes', 'yes_zoom');
        $show_title_img			    = true;
        $title_img				    = '';

        //is responsive image is set for current page?
        if(get_post_meta($id, "edgtf_title_area_background_image_responsive_meta", true) != "") {
            $is_img_responsive = get_post_meta($id, "edgtf_title_area_background_image_responsive_meta", true);
        } else {
            //take value from theme options
            $is_img_responsive = edgtf_quadric_options()->getOptionValue('title_area_background_image_responsive');
        }

        //is title image chosen for current page?
        if(get_post_meta($id, "edgtf_title_area_background_image_meta", true) != ""){
            $title_img = get_post_meta($id, "edgtf_title_area_background_image_meta", true);
        }else{
            //take image that is set in theme options
            $title_img = edgtf_quadric_options()->getOptionValue('title_area_background_image');
        }

        //is image set to be fixed for current page?
        if(get_post_meta($id, "edgtf_title_area_background_image_parallax_meta", true) != ""){
            $is_image_parallax = get_post_meta($id, "edgtf_title_area_background_image_parallax_meta", true);
        }else{
            //take setting from theme options
            $is_image_parallax = edgtf_quadric_options()->getOptionValue('title_area_background_image_parallax');
        }

        //is title image hidden for current page?
        if(get_post_meta($id, "edgtf_hide_background_image_meta", true) == "yes") {
            $show_title_img = false;
        }

        //is title image set and visible?
        if($title_img !== '' && $show_title_img == true) {
            //is image not responsive and parallax title is set?
            $classes[] = 'edgtf-preload-background';
            $classes[] = 'edgtf-has-background';

            if($is_img_responsive == 'no' && in_array($is_image_parallax, $is_image_parallax_array)) {
                $classes[] = 'edgtf-has-parallax-background';

                if($is_image_parallax == 'yes_zoom') {
                    $classes[] = 'edgtf-zoom-out';
                }
            }

            //is image not responsive
            elseif($is_img_responsive == 'yes'){
                $classes[] = 'edgtf-has-responsive-background';
            }
        }

        return $classes;
    }

    add_filter('edgtf_quadric_title_classes', 'edgtf_quadric_title_background_image_classes');
}

if(!function_exists('edgtf_quadric_title_content_alignment_class')) {
    /**
     * Function that adds class on title based on title content alignmnt option
     * Could be left, centered or right
     * @param $classes original array of classes
     * @return array changed array of classes
     */
    function edgtf_quadric_title_content_alignment_class($classes) {

        //init variables
        $id                      = edgtf_quadric_get_page_id();
        $title_content_alignment = 'left';

        if(get_post_meta($id, "edgtf_title_area_content_alignment_meta", true) != "") {
            $title_content_alignment = get_post_meta($id, "edgtf_title_area_content_alignment_meta", true);

        } else {
            $title_content_alignment = edgtf_quadric_options()->getOptionValue('title_area_content_alignment');
        }

        $classes[] = 'edgtf-content-'.$title_content_alignment.'-alignment';

        return $classes;

    }

    add_filter('edgtf_quadric_title_classes', 'edgtf_quadric_title_content_alignment_class');
}

if(!function_exists('edgtf_quadric_title_content_grid_class')) {
	/**
	 * Function that adds class on title based on title content alignmnt option
	 * Could be left, centered or right
	 * @param $classes original array of classes
	 * @return array changed array of classes
	 */
	function edgtf_quadric_title_content_grid_class($classes) {

		//init variables
		$id                      = edgtf_quadric_get_page_id();
		$content_grid = 'yes';

		if(get_post_meta($id, "edgtf_title_area_content_grid_meta", true) != "") {
			$content_grid = get_post_meta($id, "edgtf_title_area_content_grid_meta", true);

		} else {
			$content_grid = edgtf_quadric_options()->getOptionValue('title_area_content_grid');
		}

		if($content_grid == 'no'){
			$classes[] = 'edgtf-title-full-width-content';
		}



		return $classes;

	}

	add_filter('edgtf_quadric_title_classes', 'edgtf_quadric_title_content_grid_class');
}

if(!function_exists('edgtf_quadric_title_text_size_class')) {
	/**
	 * Function that adds class on title based on title text size option
	 * Could be small, medium or large
	 * @param $classes original array of classes
	 * @return array changed array of classes
	 */
	function edgtf_quadric_title_text_size_class($classes) {

		//init variables
		$id                      = edgtf_quadric_get_page_id();
		$title_content_alignment = 'small';

		if(get_post_meta($id, "edgtf_title_area_text_size_meta", true) != "") {
			$title_content_alignment = get_post_meta($id, "edgtf_title_area_text_size_meta", true);

		} else {
			$title_content_alignment = edgtf_quadric_options()->getOptionValue('title_area_text_size');
		}

		$classes[] = 'edgtf-title-'.$title_content_alignment.'-text-size';

		return $classes;

	}

	add_filter('edgtf_quadric_title_classes', 'edgtf_quadric_title_text_size_class');
}


if(!function_exists('edgtf_quadric_title_animation_class')) {
    /**
     * Function that adds class on title based on title animation option
     * @param $classes original array of classes
     * @return array changed array of classes
     */
    function edgtf_quadric_title_animation_class($classes) {

        //init variables
        $id                      = edgtf_quadric_get_page_id();
        $title_animation = 'no';

        if(get_post_meta($id, "edgtf_title_area_animation_meta", true) !== "") {
            $title_animation = get_post_meta($id, "edgtf_title_area_animation_meta", true);

        } else {
            $title_animation = edgtf_quadric_options()->getOptionValue('title_area_animation');
        }

        $classes[] = 'edgtf-animation-'.$title_animation;

        return $classes;

    }

    add_filter('edgtf_quadric_title_classes', 'edgtf_quadric_title_animation_class');
}

if(!function_exists('edgtf_quadric_title_background_image_div_classes')) {
    function edgtf_quadric_title_background_image_div_classes($classes) {

        //init variables
        $id                         = edgtf_quadric_get_page_id();
        $is_img_responsive 		    = '';
        $show_title_img			    = true;
        $title_img				    = '';

        //is responsive image is set for current page?
        if(get_post_meta($id, "edgtf_title_area_background_image_responsive_meta", true) != "") {
            $is_img_responsive = get_post_meta($id, "edgtf_title_area_background_image_responsive_meta", true);
        } else {
            //take value from theme options
            $is_img_responsive = edgtf_quadric_options()->getOptionValue('title_area_background_image_responsive');
        }

        //is title image chosen for current page?
        if(get_post_meta($id, "edgtf_title_area_background_image_meta", true) != ""){
            $title_img = get_post_meta($id, "edgtf_title_area_background_image_meta", true);
        }else{
            //take image that is set in theme options
            $title_img = edgtf_quadric_options()->getOptionValue('title_area_background_image');
        }

        //is title image hidden for current page?
        if(get_post_meta($id, "edgtf_hide_background_image_meta", true) == "yes") {
            $show_title_img = false;
        }

        //is title image set, visible and responsive?
        if($title_img !== '' && $show_title_img == true) {

            //is image responsive?
            if($is_img_responsive == 'yes') {
                $classes[] = 'edgtf-title-image-responsive';
            }
            //is image not responsive?
            elseif($is_img_responsive == 'no') {
                $classes[] = 'edgtf-title-image-not-responsive';
            }
        }

        return $classes;
    }

    add_filter('edgtf_quadric_title_classes', 'edgtf_quadric_title_background_image_div_classes');
}