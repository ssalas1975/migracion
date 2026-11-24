<amp-sidebar id="sidebar" layout="nodisplay" side="right">
    <div class="menu-amp-logo">
         <amp-img src="<?php echo ldk_get_current_medio_meta('logo') ?>" alt="Logo" class="logomedio" height="60" width="460" layout="responsive"></amp-img>    
    </div>
    <ul>

        <?php 
        $menu_items=ldk_load_menu_xml_ldk();
        if($menu_items && $menu_items['item']){
            foreach($menu_items['item'] as $item){
                echo "<li><a href='".$item["link"]."'>".$item["name"]."</a></li>";
            }
        }
        ?>
      
</ul>
</amp-sidebar>

 