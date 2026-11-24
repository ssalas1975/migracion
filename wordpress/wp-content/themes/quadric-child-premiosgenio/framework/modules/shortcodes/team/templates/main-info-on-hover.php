<?php
/**
 * Team info on hover shortcode template
 */

global $edgtfQuadricIconCollections;
$number_of_social_icons = 5;
?>

<div class="edgtf-team <?php echo esc_attr( $team_type )?>">
	<div class="edgtf-team-inner">
		<?php if ( $team_image !== '' ) { ?>
		<div class="edgtf-team-image">
			<img src="<?php echo esc_url($team_image_src); ?>" alt="team-image" />
			<div class="edgtf-team-social-holder">
				<div class="edgtf-team-social">
					<div class="edgtf-team-social-inner">
						<?php if ( $team_name !== '' || $team_position !== '') { ?>
							<div class="edgtf-team-title-holder">
								<?php if ( $team_name !== '' ) { ?>
								<<?php echo esc_attr($team_name_tag); ?> class="edgtf-team-name">
									<?php echo esc_attr( $team_name ); ?>
								</<?php echo esc_attr($team_name_tag); ?>>
								<?php echo edgtf_quadric_get_zig_zag_html(array('size'=>'small', 'position'=>'center', 'animated'=>'no', 'top_margin'=>'23', 'bottom_margin'=>'0', '')); ?>
								<?php }
								if ( $team_position !== '' ) { ?>
								<h6 class="edgtf-team-position">
									<?php echo esc_attr( $team_position ); ?>
								</h6>
								<?php } ?>
							</div>
						<?php } ?>
						<div class="edgtf-team-social-wrapp">

							<?php foreach( $team_social_icons as $team_social_icon ) {
								print $team_social_icon;
							} ?>

						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }

		if ($team_description !== '') { ?>
		<div class="edgtf-team-text">
			<div class="edgtf-team-text-inner">
				<div class="edgtf-team-description">
					<p><?php echo esc_attr( $team_description ); ?></p>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>