<form role="search" action="<?php echo esc_url(home_url('/')); ?>" class="edgtf-search-cover" method="get">
	<?php if ( $search_in_grid ) { ?>
	<div class="edgtf-container">
		<div class="edgtf-container-inner clearfix">
			<?php } ?>
			<div class="edgtf-form-holder-outer">
				<div class="edgtf-form-holder">
					<div class="edgtf-form-holder-inner">
						<input type="text" placeholder="<?php esc_html_e('Search', 'quadric'); ?>" name="s" class="edgt_search_field" autocomplete="off" />
						<div class="edgtf-search-close">
							<a href="#">
								<span class="close">
									<span><?php esc_html_e('Close', 'quadric'); ?></span>
									<span class="lines">
										<span class="line-1"></span>
										<span class="line-2"></span>
									</span>
								</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php if ( $search_in_grid ) { ?>
		</div>
	</div>
	<?php } ?>
</form>