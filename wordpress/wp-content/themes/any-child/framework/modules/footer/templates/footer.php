<?php
/**
 * Footer template part
 */

any_edge_get_content_bottom_area(); ?>
</div> <!-- close div.content_inner -->
</div>  <!-- close div.content -->

<?php if (!isset($_REQUEST["ajax_req"]) || $_REQUEST["ajax_req"] != 'yes') { ?>
<footer <?php any_edge_class_attribute($footer_classes); ?>>
	<div class="edgtf-footer-inner clearfix">

		<?php

        if ($display_footer_top) {
            any_edge_get_footer_top();
        }
        if ($display_footer_bottom) {
            any_edge_get_footer_bottom();
        }
        ?>

	</div>
</footer>
<?php } ?>

</div> <!-- close div.edgtf-wrapper-inner  -->
</div> <!-- close div.edgtf-wrapper -->
<?php wp_footer(); ?>

<!-- <div class="voc-cookie-message voc-cookie-message--hidden animated" data-voc-cookies-advice>
<div class="voc-cookie-message__container">
<span class="voc-cookie-message__notice">
Utilizamos “cookies” propias y de terceros para elaborar información estadística y mostrarle publicidad, contenidos y servicios personalizados a través del análisis de su navegación. Si continúa navegando acepta su uso. <a title="Si desea más información pinche aquí" href="https://www.vocento.com/politica-cookies/" target="_blank">Más información y cambio de configuración.</a>
</span>
<button type="button" class="voc-cookie-message__button" data-voc-cookies-advice-close>Aceptar</button>
</div>
</div> -->


</body>
</html>