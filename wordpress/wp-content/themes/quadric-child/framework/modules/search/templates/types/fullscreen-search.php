<div class="edgtf-fullscreen-search-holder">
	<div class="edgtf-fullscreen-search-close-container">
		<div class="edgtf-search-close-holder">
			<a class="edgtf-fullscreen-search-close" href="javascript:void(0)">
				<span class="close">
					<span class="lines">
						<span class="line-1"></span>
						<span class="line-2"></span>
					</span>
				</span>
			</a>
		</div>
	</div>
	<div class="edgtf-fullscreen-search-table">
		<div class="edgtf-fullscreen-search-cell">
			<div class="edgtf-fullscreen-search-inner">
				<form action="<?php echo esc_url(home_url('/')); ?>" class="edgtf-fullscreen-search-form" method="get">
					<div class="edgtf-form-holder">
						<div class="edgtf-field-holder">
							<?php print $zig_zag; ?>
							<input type="text" name="s" placeholder="<?php esc_html_e('Search', 'quadric'); ?>" class="edgtf-search-field" autocomplete="off" />
							<input type="submit" class="edgtf-search-submit" value="&#xf002;" />
							<div class="edgtf-line"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>