<div class="edgtf-two-columns-66-33 clearfix">
	<div class="edgtf-column1">
		<div class="edgtf-column-inner">
			<?php //get portfolio share and like section
				edgtf_quadric_portfolio_get_info_part('share-like');
			?>
			<?php
			$media = edgtf_quadric_get_portfolio_single_media();

			if(is_array($media) && count($media)) : ?>
				<div class="edgtf-portfolio-media edgtf-owl-slider">
					<?php foreach($media as $single_media) : ?>
						<div class="edgtf-portfolio-single-media">
							<?php edgtf_quadric_portfolio_get_media_html($single_media); ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="edgtf-column2">
		<div class="edgtf-column-inner">
			<div class="edgtf-portfolio-info-holder">
				<?php
				//get portfolio content section
				edgtf_quadric_portfolio_get_info_part('content');

				//get portfolio custom fields section
				edgtf_quadric_portfolio_get_info_part('custom-fields');

				//get portfolio date section
				edgtf_quadric_portfolio_get_info_part('date');

				//get portfolio categories section
				edgtf_quadric_portfolio_get_info_part('categories');

				//get portfolio tags section
				edgtf_quadric_portfolio_get_info_part('tags');

			?>
			</div>
		</div>
	</div>
</div>