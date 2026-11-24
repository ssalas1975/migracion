<!doctype html>
<html amp>

<head>
    <meta charset="utf-8">

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri().'/assets/img/fav_LDK.ico' ?>">
	
	<?php if(is_single()){?>
		<title><?php the_title()?></title>
		<meta name="title" content="<?php the_title()?>">
		
	<?php }?>
	
	<?php if(is_category()){?>
		<title><?php single_cat_title()?></title>
		<meta name="title" content="<?php single_cat_title()?>">
		
	<?php }?>
	
	<?php if(is_tag()){?>
		<title><?php single_tag_title()?></title>
		<meta name="title" content="<?php single_tag_title()?>">
		
	<?php }?>
	
	<?php 
	
	if(is_home()){
		$meta_title=ldk_get_current_medio_meta('home_meta_title',true);
		if($meta_title){
		?>
		<title><?php echo $meta_title?></title>
		<meta name="title" content="<?php echo $meta_title?>">
		
	<?php }else{?>
		
		<title>Blogs Local Digital Kit <?php echo LDK_CURRENT_MEDIO_NAME?></title>
		<meta name="title" content="Blogs Local Digital Kit <?php echo LDK_CURRENT_MEDIO_NAME?>">
		
	<?php }
	
	}?>
	
    <script async src="https://cdn.ampproject.org/v0.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	
	
    <?php do_action('wpseo_head');?>
    <?php do_action('wpseo_twitter');?>
    <link rel="amphtml" href="<?php echo ldk_get_current_url()?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style>
    <noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
	
	
    <?php
        if(is_single())
            apply_filters('ldk_pre_jsonld',get_template_part('includes/json','ld'));
    ?>

    <style amp-custom>
		<?php do_action('ldk_amp_custom_css'); ?>
	</style>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-font" src="https://cdn.ampproject.org/v0/amp-font-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
   	<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
	<script async custom-element="amp-live-list" src="https://cdn.ampproject.org/v0/amp-live-list-0.1.js"></script>
	<script async custom-element="amp-list" src="https://cdn.ampproject.org/v0/amp-list-0.1.js"></script>
	<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
	<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    
</head>

<body class="">
	<!-- analytics-->
	<?php echo ldk_load_analytics_comscore() ?>
	<?php echo ldk_load_analytics_gtm() ?>
	<?php echo ldk_load_analytics_adobe() ?>
 
	  <?php get_template_part('partials/amp','sidebar');?>
   
     
  
	<div class="wrapper">
        <div class="containerldk logo">
           <div class="title">
				<a class="a-width" href="<?php  echo ldk_get_current_medio_meta('url_localdigitalkit',true)?>">
				<?php if(ldk_get_current_medio_meta('logo_white',true)){ ?>
				<div class="empresas-logo-header-title"><amp-img src="<?php echo ldk_get_current_medio_meta('logo_white') ?>" alt="Logo" height="11" width="70" layout="responsive"></amp-img></div>
				<?php } ?>
				</a>
			</div>
			<div class="new-menu">
			<?php get_template_part('partials/menu');?>
			</div>
			<div class="head-phone"><span>LLámanos</span> <div class="phone-box"><i class="fa fa-phone"></i><a href="tel:<?php echo ldk_get_current_medio_meta('telefono',true)?>"><?php echo ldk_get_current_medio_meta('telefono',true)?></a></div><span>Asesoramiento gratuíto</span></div>
        </div>
    </div>
	
     <div class="wrapper wrapper-logo">
        <div class="containerldk ldk-logo">
          	
            <?php if(!is_single()){ 
					if(is_category()){ ?>
					 <h1 class="blog-title"><?php echo single_cat_title() ?></h1>
					<?php
					} 
					else
					{
						if(is_tag()){ ?>
							
							<h1 class="blog-title"><?php echo single_tag_title() ?></h1>
						<?php }
						else{ ?>
							
							<h1 class="blog-title"><?php echo ldk_get_current_medio_meta('home_title',true) ?></h1>
						<?php }
					
					
				 }
			} else{?>
             <div class="blog-title"><?php echo ldk_get_current_medio_meta('home_title',true) ?></div>
            <?php } ?>

         </div>
    </div>

    <?php get_template_part('partials/breadcumb'); ?>

   <div class="wrapper white">
          <div class="containerldk">
          




