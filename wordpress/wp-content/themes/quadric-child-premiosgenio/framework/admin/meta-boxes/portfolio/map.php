<?php

$edgtf_pages = array();
$pages = get_pages(); 
foreach($pages as $page) {
	$edgtf_pages[$page->ID] = $page->post_title;
}

global $edgtfQuadricIconCollections;

//Portfolio Images

$edgtPortfolioImages = new EdgeQuadricMetaBox("portfolio-item", "Portfolio Images (multiple upload)");
$edgtQuadricFramework->edgtMetaBoxes->addMetaBox("portfolio_images",$edgtPortfolioImages);

	$edgtf_portfolio_image_gallery = new EdgeQuadricMultipleImages("edgt_portfolio-image-gallery","Portfolio Images","Choose your portfolio images");
	$edgtPortfolioImages->addChild("edgt_portfolio-image-gallery",$edgtf_portfolio_image_gallery);

//Portfolio Images/Videos 2

$edgtPortfolioImagesVideos2 = new EdgeQuadricMetaBox("portfolio-item", "Portfolio Images/Videos (single upload)");
$edgtQuadricFramework->edgtMetaBoxes->addMetaBox("portfolio_images_videos2",$edgtPortfolioImagesVideos2);

	$edgtf_portfolio_images_videos2 = new EdgeQuadricImagesVideosFramework("Portfolio Images/Videos 2","ThisIsDescription");
	$edgtPortfolioImagesVideos2->addChild("edgtf_portfolio_images_videos2",$edgtf_portfolio_images_videos2);

//Portfolio Additional Sidebar Items

$edgtAdditionalSidebarItems = new EdgeQuadricMetaBox("portfolio-item", "Additional Portfolio Sidebar Items");
$edgtQuadricFramework->edgtMetaBoxes->addMetaBox("portfolio_properties",$edgtAdditionalSidebarItems);

	$edgtf_portfolio_properties = new EdgeQuadricOptionsFramework("Portfolio Properties","ThisIsDescription");
	$edgtAdditionalSidebarItems->addChild("edgtf_portfolio_properties",$edgtf_portfolio_properties);

