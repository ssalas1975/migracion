<?php

if(!function_exists('any_edge_passepartout_opening_tag')) {
	/**
	 * Prints opening HTML tags for passepartou
	 * Hooks to any_edge_after_wrapper_open
	 */
	function any_edge_passepartout_opening_tag() {

		if(any_edge_passepartout_enabled()) : ?>
			<div class="edgtf-passepartout-top"></div>
			<div class="edgtf-passepartout-left"></div>
			<div class="edgtf-passepartout-right"></div>
			<div class="edgtf-passepartout-bottom"></div>
		<?php endif;
	}

	add_action('any_edge_after_wrapper_open', 'any_edge_passepartout_opening_tag');
}