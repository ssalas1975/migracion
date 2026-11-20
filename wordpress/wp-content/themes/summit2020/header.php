<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>The Summit 2020</title>
    <!-- metas -->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google-site-verification" content="wiOCakNDQ5Ic6ZF6BYCgg6nW7A87_X4EH5jWh-GA6LY" />
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css" media="screen">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/add.css" media="screen">
    
    <!-- JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PSXK7CQ');
    </script>

    <?php $thepost = get_post( $post->ID ); set_script($thepost); ?>

</head>
<body>
    <!-- START: header -->
    <header class="header">
        <div class="container">
            <h1 class="header__brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" title="" ><img src="<?php echo get_template_directory_uri(); ?>/assets/img/brand.svg" alt="Women NOW - The Summit"/></a>
            </h1>
            <nav class="header__nav">
                
                 <ul class="nav">
                    <?php  $languages = icl_get_languages('skip_missing=0&orderby=code');  foreach($languages as $key => $lan): if($lan['native_name']!=''):?>
                        <li class="nav-item">
                            <a class="<?php if($lan['active']) echo 'current' ?>" href="<?php echo $lan['url'] ?>"><span><?php echo $lan['native_name'] ?></span></a>
                        </li>
                        <?php if($lan['native_name']=='ES'): ?>
                            <li class="nav-item"> / </li>
                        <?php endif; ?>
                    <?php endif; endforeach;  ?>
                </ul>

               <?php
                  $locations = get_nav_menu_locations(); $menu = wp_get_nav_menu_object( $locations[ 'menu-principal'] );
                  $menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) ); 
                ?>

                <ul class="nav">

                        <?php foreach( $menuitems as $item ):  $current=($item->object_id==$post->ID)?'current':''; ?>
                            <li class="nav-item">
                                <?php if($item->attr_title=='no-link'): ?>
                                      <a href="<?php echo $item->url; ?>" target="_blank"><span><?php echo $item->title; ?></span></a>
                                <?php elseif($post->post_name!='speakers'&&$post->post_name!='whos-who'&&$post->post_name!='aviso-legal'&&$post->post_name!='politica-de-privacidad'&&$post->post_name!='politica-de-cookies'): ?>
                                     <a data-type="nav" data-nav-scroll-to="section-<?php echo $item->attr_title;?>"><span><?php echo $item->title; ?></span></a>
                                 <?php else: ?>
                                    <a href="<?php echo $item->url; ?>"><span><?php echo $item->title; ?></span></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                 </ul>
               
                </ul> 


            </nav>
            <button class="header__nav__btn hamburger hamburger--boring" data-type="menu">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </button>
        </div>
    </header>
    <!-- END: header -->