<nav class="header-menu">
    <button  on="tap:sidebar.toggle"  class="ampstart-btn"><i class="fa fa-navicon"></i></button>
    <ul>

        <?php 
            $menu_items=ldk_load_menu_xml_ldk();
            if($menu_items && $menu_items['item']){
            foreach($menu_items['item'] as $item){
				$link=$item["link"];
				$link=str_replace("__URL__",ldk_get_current_medio_meta('url_localdigitalkit',true),$link);
                echo "<li><a href='".$link."'>".$item["name"]."</a></li>";
            }
            }
         ?>
      
</ul>
</nav>