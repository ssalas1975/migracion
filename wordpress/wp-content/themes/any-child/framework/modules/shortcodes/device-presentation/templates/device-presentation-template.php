<div <?php any_edge_class_attribute($shortcode_classes);?>>
	<div class="edgtf-device-presentation-inner">
		<div class="edgtf-hero-image-holder">
			<img class="edgtf-hero-image-frame" src="<?php echo EDGE_ASSETS_ROOT ?>/css/img/device-presentation-monitor.png" alt="device presentation desktop frame" />
			<div class="edgtf-hero-image-holder-inner">
				<?php if ($hero_image_link != '') { ?>
					<a href="<?php echo esc_url($hero_image_link) ?>" target="<?php echo esc_attr($hero_image_target) ?>"></a>
				<?php } ?>
				<div class="edgtf-device-presentation-image " style="background-image: url(<?php echo wp_get_attachment_url($hero_image) ?>);"></div>
			</div>
		</div>
		<?php if ($left_tablet_image != '') { ?>
		<div class="edgtf-left-tablet-image-holder">
			<img class="edgtf-tablet-image-frame" src="<?php echo EDGE_ASSETS_ROOT ?>/css/img/device-presentation-tablet.png" alt="device presentation tablet frame" />
			<div class="edgtf-tablet-image-holder-inner">
				<?php if ($left_tablet_image_link != '') { ?>
					<a href="<?php echo esc_url($left_tablet_image_link) ?>" target="<?php echo esc_attr($left_tablet_image_target) ?>"></a>
				<?php } ?>
				<div class="edgtf-device-presentation-image " style="background-image: url(<?php echo wp_get_attachment_url($left_tablet_image) ?>);"></div>
			</div>
		</div>
		<?php } if ($right_tablet_image != '') { ?>
		<div class="edgtf-right-tablet-image-holder">
			<img class="edgtf-tablet-image-frame" src="<?php echo EDGE_ASSETS_ROOT ?>/css/img/device-presentation-tablet.png" alt="device presentation tablet frame" />
			<div class="edgtf-tablet-image-holder-inner">
				<?php if ($right_tablet_image_link != '') { ?>
					<a href="<?php echo esc_url($right_tablet_image_link) ?>" target="<?php echo esc_attr($right_tablet_image_target) ?>"></a>
				<?php } ?>
				<div class="edgtf-device-presentation-image " style="background-image: url(<?php echo wp_get_attachment_url($right_tablet_image) ?>);"></div>
			</div>
		</div>
		<?php } if ($left_phone_image != '') { ?>
		<div class="edgtf-phone-image-holder edgtf-x-flip">
			<?php if ($left_phone_image_link != '') { ?>
				<a class="edgtf-phone-link" href="<?php echo esc_url($left_phone_image_link) ?>" target="<?php echo esc_attr($left_phone_image_target) ?>">
			<?php } ?>
			<img class="edgtf-phone-image-frame" src="<?php echo EDGE_ASSETS_ROOT ?>/css/img/device-presentation-phone.png" alt="device presentation phone frame" />
			<div class="edgtf-phone-image-holder-inner">
				<div class="edgtf-device-presentation-image " style="background-image: url(<?php echo wp_get_attachment_url($left_phone_image) ?>);"></div>
			</div>
			<?php if ($left_phone_image_link != '') { ?>
				</a>
			<?php } ?>
		</div>
		<?php } if ($right_phone_image != '') { ?>
		<div class="edgtf-phone-image-holder">
			<?php if ($right_phone_image_link != '') { ?>
				<a class="edgtf-phone-link" href="<?php echo esc_url($left_phone_image_link) ?>" target="<?php echo esc_attr($left_phone_image_target) ?>">
			<?php } ?>
			<img class="edgtf-phone-image-frame" src="<?php echo EDGE_ASSETS_ROOT ?>/css/img/device-presentation-phone.png" alt="device presentation phone frame" />
			<div class="edgtf-phone-image-holder-inner">
				<div class="edgtf-device-presentation-image " style="background-image: url(<?php echo wp_get_attachment_url($right_phone_image) ?>);"></div>
			</div>
			<?php if ($right_phone_image_link != '') { ?>
				</a>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>
