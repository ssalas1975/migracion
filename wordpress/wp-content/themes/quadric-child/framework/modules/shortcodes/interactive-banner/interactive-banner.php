<?php
namespace EdgefQuadricModules\InteractiveBanner;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class InteractiveBanner implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'edgtf_interactive_banner';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){
			vc_map( 
				array(
					'name' => 'Interactive Banner', 'quadric',
					'base' => $this->base,
					'category' => 'by EDGE',
					'icon' => 'icon-wpb-interactive-banner extended-custom-icon',
					'show_settings_on_create' => true,
					'params' => array(
						array(
							'type' => 'attach_image',
							'class' => '',
							'heading' => 'Background Image',
							'param_name' => 'background_image',
							'value' => '',
							'description' => ''
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => 'Title',
							'param_name' => 'title',
							'value' => ''
						),
						array(
							'type' => 'textarea',
							'class' => '',
							'heading' => 'Hover Text',
							'param_name' => 'hover_text',
							'value' => ''
						)

					)
				)
			);			
		}
	}

	public function render($atts, $content = null) {
		$args = array(
			'background_image' => '',
			'title' => '',
			'hover_text' => ''

		);
		
		$params = shortcode_atts($args, $atts);
		extract($params);

		$params['image'] = wp_get_attachment_url($params['background_image']);

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/interactive-banner-template', 'interactive-banner', '', $params);

		return $html;
	}

}
