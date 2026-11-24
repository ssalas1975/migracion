
<div class='edgtf-image-gallery-carousel-wrapper'>
	<div class="edgtf-image-gallery carousel">
		<div class="edgtf-image-gallery-carousel" <?php echo edgtf_quadric_get_inline_attrs($carousel_data); ?>>
			<?php foreach ($images as $image) {
				if ($pretty_photo) { ?>
					<a href="<?php echo esc_url($image['url'])?>" data-rel="prettyPhoto[single_pretty_photo]" title="<?php echo esc_attr($image['title']); ?>">
				<?php } ?>
					<div class='edgtf-image-gallery-wrapper'><img src="<?php echo esc_url($image['url'])?>" alt="<?php echo esc_attr($image['title']); ?>" width="<?php echo esc_attr($image['width']); ?>" height="<?php echo esc_attr($image['height']); ?>"></div>
				<?php if ($pretty_photo) { ?>
					</a>
				<?php }
			} ?>
		</div>
	</div>
	<?php if($navigation == 'yes') {?>
	<div class='edgtf-image-gallery-nav'>
		<span class="edgtf-prev-icon"><i class="icon-arrows-left"></i></span>
		<span class="edgtf-next-icon"><i class="icon-arrows-right"></i></span>
	</div>
	<?php } ?>
</div>
