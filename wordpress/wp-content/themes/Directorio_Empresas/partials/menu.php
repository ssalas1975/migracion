<nav class="header-menu">
    <button  on="tap:sidebar.toggle"  class="ampstart-btn"><i class="fa fa-navicon"></i></button>
    <ul>

        <?php 
            $menu_items=ldk_load_menu_xml();
            if($menu_items && $menu_items['item']){
            foreach($menu_items['item'] as $item){
                echo "<li><a href='".$item["link"]."'>".$item["name"]."</a></li>";
            }
            }
         ?>
      
</ul>
</nav>