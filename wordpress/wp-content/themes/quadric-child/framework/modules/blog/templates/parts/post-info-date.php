<div class="edgtf-post-info-date">
	<?php if(!edgtf_quadric_post_has_title()) { ?>
	<a href="<?php the_permalink() ?>">
		<?php } ?>

		<i class="edgtf-icon-simple-line-icon icon-calender"></i><?php the_time(get_option('date_format')); ?>

		<?php if(!edgtf_quadric_post_has_title()) { ?>
	</a>
<?php } ?>
</div>