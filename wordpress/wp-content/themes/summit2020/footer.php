

	<footer class="footer">
        <div class="container">
            <a class="footer__modal" data-type="modal" data-modal-content="contact"><span><?php _e('¿Hablamos?','summit2020_wpml'); ?></span></a>
            <div class="group-1">
                <?php
                  $locations = get_nav_menu_locations(); $menu = wp_get_nav_menu_object( $locations[ 'menu-footer'] );
                  $menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) ); 
                ?>
                <nav class="footer__nav">
                    <?php foreach( $menuitems as $item ):  $current=($item->object_id==$post->ID)?'current':''; ?>
                        <a class="footer__nav-item"href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a>
                    <?php endforeach; ?>
                 </nav> 
				  <nav class="footer__rrss">
                    <a class="btn btn-primary btn-icon" target="_blank" rel="nofollow noopener" href="https://www.facebook.com/WomenNowSP/">
                        <svg class="svg-icon" viewBox="0 0 20 20" style="transform:scale(1.05)">
                            <path fill="currentColor" d="M11.344,5.71c0-0.73,0.074-1.122,1.199-1.122h1.502V1.871h-2.404c-2.886,0-3.903,1.36-3.903,3.646v1.765h-1.8V10h1.8v8.128h3.601V10h2.403l0.32-2.718h-2.724L11.344,5.71z"></path>
                        </svg>
                        <span>Facebook</span>
                    </a>
                    <a class="btn btn-primary btn-icon" target="_blank" rel="nofollow noopener" href="https://www.instagram.com/womennowsp/?hl=es">
                        <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                        </svg>
                        <span>Instagram</span>
                    </a>
                    <a class="btn btn-primary btn-icon" target="_blank" rel="nofollow noopener" href="https://twitter.com/WomenNOWSP?lang=es">
                         <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                        </svg>
                        <span>Twitter</span>
                    </a>
                </nav>
            </div>
            <div class="group-2">
                  <nav class="footer__logos">
                    <a href="http://www.vocento.com/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/vocento.svg"  width="150" height="23"></a>
                    <a href="http://www.tallerdeeditores.com/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/taller-editores.svg"  width="180" height="20"></a>
                  </nav>
                  <p class="footer__copy">&copy; <?php _e('WOMEN NOW SUMMIT. Todos los derechos reservados','summit2020_wpml'); ?></p>
            </div>
            <div class="group-3">
                <a href="https://www.womennow.es" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/women-now.svg" width="97" height="62"></a>
            </div>
        </div>
    </footer>
   
<?php wp_reset_postdata(); get_sidebar('sidebar'); ?>

<!-- JS -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/libs.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/add.js"></script>

</body>
</html>

<?php wp_footer(); ?>

</body>
</html>
