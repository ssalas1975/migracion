<?php
	$month ='M';
	$day = 'd';
	$year = 'Y';
?>
<li class="edgtf-blog-list-item clearfix">
	<div class="edgtf-blog-list-item-inner">
		<?php if(has_post_thumbnail()){ ?>
			<div class="edgtf-item-image">
				<div class="edgtf-item-date">
					<span class="edgtf-date-month"><?php the_time($month) ?></span>
					<span class="edgtf-date-day"><?php the_time($day) ?></span>
					<span class="edgtf-date-year"><?php the_time($year) ?></span>
				</div>
				<a href="<?php echo esc_url(get_permalink()) ?>">
					<?php
						 echo get_the_post_thumbnail(get_the_ID(), $thumb_image_size);
					?>
				</a>
				<a href="<?php echo esc_url(get_permalink()) ?>" class="edgtf-image-hover">
					<span class="edgtf-image-hover-inner">
						<span><?php esc_html_e('Read More', 'quadric') ?><span class="arrow_carrot-right edgtf-hover-icon"></span></span>
					</span>
				</a>
			</div>
		<?php } ?>
		<div class="edgtf-item-text-holder">
			<<?php echo esc_html( $title_tag)?> class="edgtf-item-title">
				<a href="<?php echo esc_url(get_permalink()) ?>" >
					<?php echo esc_attr(get_the_title()) ?>
				</a>
			</<?php echo esc_html($title_tag) ?>>
			
			<div class="edgtf-item-info-section">
				<?php edgtf_quadric_post_info(array(
					'date' => 'no',
					'category' => 'yes',
					'author' => 'yes',
					'comments' => 'yes',
					'like' => 'no'
				)) ?>
			</div>
			
			<?php if ($text_length != '0') {
				$excerpt = ($text_length > 0) ? substr(get_the_excerpt(), 0, intval($text_length)) : get_the_excerpt(); ?>
				<p class="edgtf-excerpt"><?php echo esc_html($excerpt)?>...</p>
			<?php } ?>
		</div>
	</div>	
</li>