<div class="col-c-module  tags">
    <div class="title">Etiquetas destacadas</div>
    <div class="tags-content">
        <?php
        foreach ($tags_list = ldk_custom_tag_cloud() as $tag_term) {
            echo '<a href="'.ldk_get_current_medio_meta('akamai_blog_path', $single).'tag/'.$tag_term->slug.'/" class="tag-cloud-link">'.$tag_term->name.'</a>';
        }
        //wp_tag_cloud(array('number'=>10,'orderby' => 'count', 'order'=> 'DESC'));
        ?>
    </div>
</div>