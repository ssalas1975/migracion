<?php
if(!function_exists('any_edge_map_portfolio_meta_fields')) {

    function any_edge_map_portfolio_meta_fields() {
    	global $any_edge_Framework;

		$edgt_pages = array();
		$pages = get_pages(); 
		foreach($pages as $page) {
			$edgt_pages[$page->ID] = $page->post_title;
		}

		//Portfolio Images

		$edgtPortfolioImages = new AnyEdgeMetaBox("portfolio-item", esc_html__("Portfolio Images (multiple upload)", 'any'), '', '', 'portfolio_images');
		$any_edge_Framework->edgtMetaBoxes->addMetaBox("portfolio_images",$edgtPortfolioImages);

			$edgt_portfolio_image_gallery = new AnyEdgeMultipleImages("edgt_portfolio-image-gallery",esc_html__("Portfolio Images", 'any'),esc_html__("Choose your portfolio images", 'any'));
			$edgtPortfolioImages->addChild("edgt_portfolio-image-gallery",$edgt_portfolio_image_gallery);

		//Portfolio Images/Videos 2

		$edgtPortfolioImagesVideos2 = new AnyEdgeMetaBox("portfolio-item", esc_html__("Portfolio Images/Videos (single upload)", 'any'));
		$any_edge_Framework->edgtMetaBoxes->addMetaBox("portfolio_images_videos2",$edgtPortfolioImagesVideos2);

			$edgt_portfolio_images_videos2 = new AnyEdgeImagesVideosFramework(esc_html__("Portfolio Images/Videos 2", 'any'),esc_html__("ThisIsDescription", 'any'));
			$edgtPortfolioImagesVideos2->addChild("edgt_portfolio_images_videos2",$edgt_portfolio_images_videos2);

		//Portfolio Additional Sidebar Items

		$edgtAdditionalSidebarItems = any_edge_add_meta_box(
		    array(
		        'scope' => array('portfolio-item'),
		        'title' => esc_html__('Additional Portfolio Sidebar Items', 'any'),
		        'name' => 'portfolio_properties'
		    )
		);

			$edgt_portfolio_properties = any_edge_add_options_framework(
			    array(
			        'label' => esc_html__('Portfolio Properties', 'any'),
			        'name' => 'edgt_portfolio_properties',
			        'parent' => $edgtAdditionalSidebarItems
			    )
			);
		}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_portfolio_meta_fields');
}