
<br>
<div align="center">
<a href="https://oferplan.laverdad.es/ofertas-especiales/murcia-gastronomica-2019/" target="_blank">
<img alt="Región de Murcia Gastronómica" src="https://proyectos.laverdad.es/comun/img/oferplan-mg.jpg" border="0">
</a>
</div>
<br>
<br>
		<?php if( of_get_option('show_contact_map') == true ) { ?>
		<div class="google-map">
			<a href="#map" class="map-toggle expand"><span class="show"><?php echo __('Ver mapa', 'site5framework'); ?></span><span class="hide"><?php echo __('Esconder mapa', 'site5framework'); ?></span></a>
			<div id="map">
				<?php echo of_get_option('contact_map') ?>
			</div>
		</div>
		<?php } ?>

		<!-- begin footer -->
		<footer>

		<div class="footer_widgets block grid4 clearfix">

			<?php if ( !function_exists('dynamic_sidebar') || dynamic_sidebar('sidebar-footer') ) { ?><?php } else { ?>
		    <?php }?>

		</div>

		</footer>
        <!-- end footer -->

		<!-- begin .copyright -->
		<div class="copyright" align="left">

			<?php if(of_get_option('footer_copyright') == '') { ?>
				
				© LA VERDAD MULTIMEDIA, S.A. Camino Viejo de Monteagudo s/n. 30160. Murcia.
			<?php } else { ?>
				<?php echo of_get_option('footer_copyright')  ?>
			<?php } ?>

		</div>
		<!-- end .copyright -->


	</div>
	<!-- end container -->

	<?php wp_footer(); ?> 
<script>
	Voonto.ready.then(function(app) {
		window.googletag.pubads().setRequestNonPersonalizedAds(1);
		app.when('consent:advertising').then(function(result) {
			window.googletag.pubads().setRequestNonPersonalizedAds(result ? 0 : 1);
		});
		app.on('consent:updated:advertising', function(event, result) {
			window.googletag.pubads().setRequestNonPersonalizedAds(result ? 0 : 1);
		});
	});
</script>
	</body>
</html>