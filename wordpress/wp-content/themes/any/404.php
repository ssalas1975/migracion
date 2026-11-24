<?php get_header(); ?>

	<div class="edgtf-container">
	<?php do_action('any_edge_after_container_open'); ?>
		<div class="edgtf-container-inner edgtf-404-page">
			<div class="edgtf-page-not-found-part">
				<span class="edgtf-404-text"><?php esc_html_e('404', 'any'); ?></span>
			</div>
			<div class="edgtf-page-not-found">
				<h1>
					<?php if(any_edge_options()->getOptionValue('404_title')){
						echo esc_html(any_edge_options()->getOptionValue('404_title'));
					}
					else{
						esc_html_e('Page cannot be found', 'any');
					} ?>
				</h1>
				<h4>
					<?php if(any_edge_options()->getOptionValue('404_text')){
						echo esc_html(any_edge_options()->getOptionValue('404_text'));
					}
					else{
						esc_html_e('The page requested couldn\'t be found. This could be a spelling error in the URL or a removed page.', 'any');
					} ?>
				</h4>
				<?php
					$params = array();
					if (any_edge_options()->getOptionValue('404_back_to_home')){
						$params['text'] = any_edge_options()->getOptionValue('404_back_to_home');
					}
					else{
						$params['text'] = "Go Back";
					}
					$params['link'] = esc_url(home_url('/'));
					$params['target'] = '_self';
					$params['icon_pack'] = "font_elegant";
					$params['fe_icon'] = "arrow_right";
				echo any_edge_execute_shortcode('edgtf_button',$params);?>
			</div>
		</div>
		<?php do_action('any_edge_before_container_close'); ?>
	</div>
<?php get_footer(); ?>