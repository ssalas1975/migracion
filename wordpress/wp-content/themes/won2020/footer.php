 <!-- START: c-footer-->
 <footer class="c-footer">
        <div class="o-container">
            <div class="u-grid u-grid-columns-2-xs u-grid-columns-4">
                <div class="c-footer__brand u-grid-start-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 163.25 66.68">
                    <g fill="currentColor">
                        <path d="M27.73,21.84,31.59.51h7.25l-7,30.74H24.08L19.42,9.58,14.8,31.25H7L0,.51H7.34l4.19,21.24L15.69.51h7.5Z"/>
                        <path d="M53.26,31.8c-9.46,0-15.95-6.32-15.95-15.9S43.8,0,53.26,0,69.2,6.32,69.2,15.9,62.67,31.8,53.26,31.8Zm0-6c4.53,0,8.26-3.39,8.26-9.92S57.79,6,53.26,6,45,9.37,45,15.9,48.72,25.82,53.26,25.82Z"/>
                        <path d="M84.76,31.25,77.85,6.66V31.25h-7V.51H82.26l5.89,22,6-22h11.32V31.25h-7V6.61L91.59,31.25Z"/>
                        <path d="M108.93.51H132.5V6.87H116.18v5.38h15.43v6.41H116.18v6.19H132.5v6.4H108.93Z"/>
                        <path d="M154.25,31.25l-12.34-21.2v21.2h-7V.51h8.82l12.34,21.2V.51h7V31.25Z"/>
                        <path d="M83.66,66.12,71.33,44.92v21.2h-7V35.38h8.82l12.34,21.2V35.38h7V66.12Z"/>
                        <path d="M110,66.68c-9.45,0-15.94-6.32-15.94-15.9S100.54,34.87,110,34.87s16,6.32,16,15.91S119.41,66.68,110,66.68Zm0-6c4.54,0,8.27-3.4,8.27-9.92s-3.73-9.93-8.27-9.93-8.26,3.4-8.26,9.93S105.46,60.7,110,60.7Z"/>
                        <path d="M152.14,56.71,156,35.38h7.25L156.3,66.12h-7.81l-4.66-21.66-4.62,21.66h-7.76l-7-30.74h7.34l4.19,21.25,4.16-21.25h7.5Z"/>
                    </g>
                </svg>
            </div>
                <p class="c-footer__txt u-grid-start-2">MADRID</p>
                <address class="c-footer__where u-grid-start-3">
                    <p class="c-footer__txt ">VOCENTO C/ Josefa Valcárcel, 40 BIS</p>
                    <p class="c-footer__txt ">28027 Madrid</p>
                </address>
                <nav class="c-footer__newsletter u-grid-row-xs-start-1 u-grid-col-xs-start-2 u-grid-start-4">
                    <a href="mailto:won@womennow.es" title="Enviar correo de WomenNow" aria-label="Enviar correo de WomenNow">won@womennow.es</a>
                    <?php $newsletter=get_page_by_title('Newsletter'); ?>
                    <a rel="nofollow" href="<?php echo get_page_link($newsletter->ID) ?>" title="Ir a Prospect Newsletter" aria-label="Ir a Prospect Newsletter">Newsletter</a>
                </nav>
                <p class="c-footer__txt u-grid-start-2">SÍGUENOS</p>
                <nav class="c-footer__share u-grid-start-3">
                    <?php
                        $locations = get_nav_menu_locations(); $menu = wp_get_nav_menu_object( $locations[ 'menu-rrss'] );
                        $menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
                    ?>

                    <?php foreach( $menuitems as $item ):  ?>

                        <?php if($item->title=='Facebook'): ?>
                            <a href="<?php echo $item->url ?>" title="Ir a Facebook de WomenNow" aria-label="Ir a Facebook de WomenNow" class="btn btn-primary btn-icon" target="_blank" rel="nofollow noopener" >
                                <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                    <path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path>
                                </svg>
                                <span><?php echo $item->title ?></span>
                            </a>
                        <?php elseif($item->title=='Instagram'): ?>
                            <a href="<?php echo $item->url ?>" title="Ir a Instagram de WomenNow" aria-label="Ir a Instagram de WomenNow" class="btn btn-primary btn-icon" target="_blank" rel="nofollow noopener" >
                                <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                                </svg>
                                <span><?php echo $item->title ?></span>
                            </a>
                        <?php elseif($item->title=='Twitter'): ?>
                            <a href="<?php echo $item->url ?>" title="Ir a Twitter de WomenNow" aria-label="Ir a Twitter de WomenNow" class="btn btn-primary btn-icon" target="_blank" rel="nofollow noopener" >
                                <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                                </svg>
                                <span><?php echo $item->title ?></span>
                            </a>
                        <?php elseif($item->title=='LinkedIn'): ?>
                            <a href="<?php echo $item->url ?>" title="Ir a Linkedin de WomenNow" aria-label="Ir a Linkedin de WomenNow" class="btn btn-primary btn-icon" target="_blank" rel="nofollow noopener" >
                                <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="currentColor" d="M100.3 480H7.4V180.9h92.9V480zM53.8 140.1C24.1 140.1 0 115.5 0 85.8 0 56.1 24.1 32 53.8 32c29.7 0 53.8 24.1 53.8 53.8 0 29.7-24.1 54.3-53.8 54.3zM448 480h-92.7V334.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V480h-92.8V180.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V480z"></path>
                                </svg>
                                <span><?php echo $item->title ?></span>
                            </a>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </nav>

            </div>
            <div class="c-footer__bottom">
                <p class="policy-add">En lo posible, para la resolución de litigios en línea en materia de consumo conforme Reglamento (UE) 524/2013, se buscará la posibilidad que la Comisión Europea facilita como plataforma de resolución de litigios en línea y que se encuentra disponible en el enlace <a class="link-legal-platform" href='http://ec.europa.eu/consumers/odr'>http://ec.europa.eu/consumers/odr.</a></p>
                <nav class="u-flex u-flex-justify-end">
                    <style>.c-footer__bottom li{ list-style:none; } .c-footer__bottom li a{ margin-left:30px }</style>
                    <?php wp_nav_menu( array( 'container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'menu-legales' ) ); ?>
                </nav>
                <p class="u-flex u-flex-justify-end">WomenNOW. Mujeres extraordinarias que escriben el futuro de la igualdad. Copyright Vocento interactive 2020</p>
            </div>

        </div>
    </footer>
    <!-- END: c-footer-->

    <!-- START: footer -->
        <!-- <footer class="footer">
            <div class="container">
                <section class="footer-top">
                    <p class="title">HELLO!</p>
                    <address class="item">
                        <p class="c1">MADRID</p>
                        <p class="c2">
                            <span>VOCENTO</span>
                            <span>c/ Josefa Valcárcel, 40 BIS</span>
                            <span>28027 Madrid</span>
                        </p>
                        <p class="c3">
                            <a href="mailto:won@womennow.es">won@womennow.es</a>
                            <a href="newsletter">NEWSLETTER</a>
                        </p>
                    </address>
                    <div class="item">
                        <p class="c1">SÍGUENOS</p>
                        <nav class="c2">
                            <?php wp_nav_menu(array( 'theme_location' => 'menu-rrss')); ?>
                        </nav>
                    </div>
                </section>
                <section class="footer-bottom">
                    <nav>
                        <a href="aviso-legal">Aviso Legal</a>
                        <a href="politica-de-privacidad">Política de Privacidad</a>
                        <a href="politica-de-cookies">Política de Cookies</a>
                    </nav>
                    <p>Copyright Vocento Interactive 2020.</p>
                </section>
            </div>
        </footer> -->
    <!-- END: footer -->

<!-- JS -->
<script  type="vocento/script" src="<?php echo get_template_directory_uri(); ?>/assets/js/libs.js"></script>
<script  type="vocento/script" src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>
<script  type="vocento/script" src="<?php echo get_template_directory_uri(); ?>/assets/js/add.js"></script>

<?php if($post->post_type=='videocpt' || $post->post_name=='home'){?>
<script type="vocento/script" >
    var ruta="https://www.womennow.es/wp-content/themes/won2020";
    Voonto.ready.then(function (app) { app.load('//static.vocstatic.com/video-player/1.3.4/video-player.js'); });
</script>
<?php } ?>

<?php wp_footer(); ?>
<script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-experiment="vocento/script"></script>
</body>
</html>
