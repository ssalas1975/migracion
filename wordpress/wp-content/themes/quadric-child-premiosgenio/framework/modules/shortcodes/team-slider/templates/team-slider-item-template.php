<?php
/**
 * Team slider item template
 */
global $edgtfQuadricIconCollections;
?>

<div class="edgtf-team-slide">
	<?php if ($team_image !== '') { ?>
	<div class="edgtf-slide-image">
		<div class='edgtf-image-wrapper'>
			<img src='<?php print $team_image_src; ?>' alt='img'/>
		</div>	
	</div>
	<?php } ?>
	<?php if ($team_name !== '' || $team_position !== '' || $team_description != "" || $show_skills == 'yes') { ?>
	<div class="edgtf-slide-content">
		<?php if ($team_name !== '' || $team_position !== '') { ?>
				<div class="edgtf-team-title-holder <?php echo esc_attr($team_social_icon_type) ?>">
					<?php if ($team_name !== '') { ?>
						<<?php echo esc_attr($team_name_tag); ?> class="edgtf-team-name">
							<?php echo esc_attr($team_name); ?>
						</<?php echo esc_attr($team_name_tag); ?>>
					<?php } ?>
					<?php if ($team_position !== "") { ?>
						<h6 class="edgtf-team-position"><?php echo esc_attr($team_position) ?></h6>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ($team_description != "") { ?>
				<div class='edgtf-team-text'>
					<div class='edgtf-team-text-inner'>
						<div class='edgtf-team-description'>
							<p><?php echo esc_attr($team_description) ?></p>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="edgtf-team-social-holder-between">
				<div class="edgtf-team-social <?php echo esc_attr($team_social_icon_type) ?>">
					<div class="edgtf-team-social-inner">
						<div class="edgtf-team-social-wrapp">

							<?php foreach( $team_social_icons as $team_social_icon ) {
								print $team_social_icon;
							} ?>

						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>	
</div>