<?php
//eliminando el canonical de wordpress
remove_action('wp_head', 'rel_canonical');
//contando visitas...
setPostViews(get_the_ID());


$metadatacss = get_post_meta(get_the_ID(), 'css_opciones', true);
$pieces = explode("@@", $metadatacss);

$url_medio = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
if ($url_medio !== "http://content-factory.sevilla.abc.es") {
    $url_medio = str_replace("http://content-factory", "https://www", $url_medio);
} else {
    $url_medio = str_replace("http://content-factory.", "https://", $url_medio);
}



?>
<?php if (have_posts()):
    while (have_posts()):
        the_post(); ?>
        <article class="noticia <?php echo $pieces[1] . ' ' . $url_medio; ?>">
            <?php
            $content = get_the_content();
            $content = do_shortcode($content);
            $content = str_replace("/contentfactory/post/wp-content/uploads/2024/", "/contentfactory/post/wp-content/uploads/sites/4/2024/", $content);
            echo $content;
            ?>

            <!-- /.post -->
        <?php endwhile; ?>

    <?php else: ?>
        <h2>Whoops! Looks like we can't find this post.</h2>

    <?php endif; ?>

</article>