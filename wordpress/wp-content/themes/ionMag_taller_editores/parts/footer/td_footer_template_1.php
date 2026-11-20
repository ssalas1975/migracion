<?php $page_title = $wp_query->post->post_title;
if($page_title == 'Home'){ ?>
<div class="td-pb-row td-pb-span1 top-footer-new">
    <?php
    locate_template('parts/footer/td_footer_extra_home.php', true); ?>
</div>
<div class="td-pb-row td-pb-span1 top-footer-new2">
<?php
    } else {  ?>
    <div class="td-pb-row td-pb-span1 top-footer-new">
<?php
}
    locate_template('parts/footer/td_footer_extra_bottom.php', true); ?>
</div>
<div class="td-footer-wrapper">
    <div class="td-container">

        <div class="td-pb-row">
            <div class="td-pb-span12 td-footer-menu-new">
                <?php
                td_global::vc_set_custom_column_number(1);
                dynamic_sidebar('Footer 1');
                ?>
            </div>
        </div>

    </div>
</div>

<div class="footer-vocento-logo"><p>vocento</p></div>
<div class="footer-links-2">
    <ul>
        <li><a href="//www.elcorreo.com" target="_blank">Elcorreo.com</a></li>
        <li><a href="//www.elcomerciodigital.com/" target="_blank">Elcomerciodigital.com</a></li>
        <li><a href="//www.eldiariomontanes.es" target="_blank">Eldiariomontañes.es</a></li>
        <li><a href="//www.hoycinema.com" target="_blank">Hoy Cinema</a></li>
        <li><a href="//www.finanzas.com/" target="_blank">Finanzas.com</a></li>
        <li><a href="//www.hoy.es/" target="_blank">Hoydigital</a></li>
        <li><a href="//www.grada360.com" target="_blank">Grada 360</a></li>
        <li><a href="http://www.ideal.es/" target="_blank">Idealdigital</a></li>
        <li><a href="http://www.lavozdigital.es/" target="_blank">La Voz Digital</a></li>
        <li><a href="http://www.abc.es" target="_blank">ABC.es</a></li>
        <li><a href="http://www.que.es/" target="_blank">Qué.es</a></li>
        <li><a href="http://www.larioja.com/" target="_blank">Larioja.com</a></li>
        <li><a href="http://www.hoymujer.com/" target="_blank">Mujer Hoy</a></li>
        <li><a href="http://www.abc.es/radio/" target="_blank">Puntoradio</a></li>
        <li><a href="http://www.laverdad.es/" target="_blank">Laverdad.es</a></li>
        <li><a href="http://www.diariovasco.com/" target="_blank">Diario Vasco</a></li>
        <li><a href="http://www.hoymotor.com" target="_blank">Hoy Motor</a></li>
        <li><a href="http://www.diariosur.es/" target="_blank">Sur Digital</a></li>
        <li><a href="http://www.nortecastilla.es/" target="_blank">NorteCastilla.es</a></li>
        <li><a href="http://www.lasprovincias.es/" target="_blank">Las Provincias Digital</a></li>
    </ul>
</div>

<div class="copyright footer-copyright">© Taller de editores 2012 - C/ Juan Ignacio Luca de Tena, 7 · 1ª planta - 28027 Madrid. 91 327 83 00</div>