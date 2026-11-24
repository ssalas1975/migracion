<?php 
$items=wp_get_nav_menu_items('principal'); 
?>

      <div class="collapse" id="menu-principal">
                    <div class="menu-principal" id="menuprin">
                        <!--<a href="" data-toggle="collapse"><span id="aspa" data-toggle="collapse"></span></a>-->
                     <nav >
                     <ul>
                         <!-- <li class="active"><a href="index.html">HOME</a></li> -->
						 <?php foreach($items as $item){
									
									if(strtolower($item->title) =="blog" && !moka_exists_posts())
										continue;
									
									if(strtolower($item->title) =="projects" && !moka_exists_projects())
										continue;
							 
                                   $active="";
                                   $actual_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
								
                                   if(strpos($actual_link, $item->url)){
                                         $active="active-page";
                                   }

                                   if($post->post_type=="post" && is_single() && (strpos($item->url, 'blog'))){
                                       $active="active-page";
                                   }

                                   if(($post->post_type=="proyecto" && is_single() && (strpos($item->url, 'project'))) || (strpos($actual_link,'project') && strpos($item->url, 'project'))){
                                       $active="active-page";
                                   }
                         ?>
                          <li  id="color-white-phone"><h3><a class="<?php echo $active?>" href="<?php echo $item->url?>"><?php echo $item->title?></a></h3></li>
						 <?php }?>
                     </ul>
                 </nav>
                 </div>
                <div class="navigation-overlay"></div>
              </div>