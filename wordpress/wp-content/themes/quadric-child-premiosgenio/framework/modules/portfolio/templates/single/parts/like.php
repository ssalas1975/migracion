<?php if(edgtf_quadric_options()->getOptionValue('portfolio_single_hide_like') !== 'yes') : ?>
	<?php if( function_exists('edgtf_quadric_get_like') ) edgtf_quadric_get_like(); ?>
<?php endif; ?>
