<div class="edgtf-interactive-banner-holder">
	<div class="edgtf-interactive-banner-image">
		<img src="<?php echo esc_url($image);  ?>" alt="image"/>
	</div>
	<div class="edgtf-interactive-banner-shader"></div>
	<div class="edgtf-interactive-banner-text">
		<div class="edgtf-interactive-banner-text-inner">
			<div class="edgtf-interactive-banner-initial">
				<div class="edgtf-interactive-banner-initial-content">
					<span class="edgtf-interactive-banner-initial-title"><?php echo esc_attr($title); ?></span>
					<?php echo edgtf_quadric_get_zig_zag_html(array('size'=>'medium', 'position'=>'center', 'animated'=>'no', 'top_margin'=>'23', 'bottom_margin'=>'21', '')); ?>
				</div>
			</div>
			<div class="edgtf-interactive-banner-hover">
				<div class="edgtf-interactive-banner-hover-content">
					<span class="edgtf-interactive-banner-hover-title"><?php echo esc_attr($title); ?></span>
					<p class="edgtf-interactive-banner-hover-text"><?php echo esc_attr($hover_text); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>