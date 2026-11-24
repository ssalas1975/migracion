<div class="edgtf-carousel-item-holder <?php echo esc_attr($carousel_classes); ?>">
	<?php if ($link !== '') { ?>
	<a href="<?php echo esc_url($link)?>" target="<?php echo esc_attr($target)?>">
	<?php } ?>
		<?php if ($image !== '') { ?>
			<span class="edgtf-carousel-first-image-holder">
				<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_html($title); ?>">
			</span>
		<?php } ?>
		<?php if ($hover_image !== '') { ?>
			<span class="edgtf-carousel-second-image-holder">
				<img src="<?php echo esc_url($hover_image); ?>" alt="<?php echo esc_html($title); ?>">
			</span>
		<?php } ?>
	<?php if ($link !== '') { ?>
	</a>
	<?php } ?>

	<?php if($show_title == 'yes') { ?>
		<?php if($link !== '') { ?>
			<h3 class="edgtf-carousel-item-title"><a href="<?php echo esc_url($link)?>" target="<?php echo esc_attr($target)?>"><?php the_title(); ?></a></h3>
		<?php } else { ?>
			<h3 class="edgtf-carousel-item-title"><?php the_title(); ?></h3>
		<?php } ?>
	<?php } ?>
</div>