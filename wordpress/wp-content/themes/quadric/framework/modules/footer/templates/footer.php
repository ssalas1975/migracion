<?php
/**
 * Footer template part
 */

edgtf_quadric_get_content_bottom_area(); ?>
</div> <!-- close div.content_inner -->
</div>  <!-- close div.content -->

<footer <?php edgtf_quadric_class_attribute($footer_classes); ?>>
	<div class="edgtf-footer-inner clearfix">

		<?php

		if($display_footer_top) {
			edgtf_quadric_get_footer_top();
		}
		if($display_footer_bottom) {
			edgtf_quadric_get_footer_bottom();
		}
		?>

	</div>
</footer>

</div> <!-- close div.edgtf-wrapper-inner  -->
</div> <!-- close div.edgtf-wrapper -->
<?php wp_footer(); ?>

<script>
	Voonto.ready.then(function(app) {
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