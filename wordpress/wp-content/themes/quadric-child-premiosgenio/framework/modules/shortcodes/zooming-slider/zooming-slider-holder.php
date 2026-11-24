<?php
namespace EdgefQuadricModules\ZoomingSliderHolder;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ZoomingSliderHolder implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_zooming_slider_holder';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name' => 'Zooming Slider Holder',
			'base' => $this->getBase(),
			'icon' => 'icon-wpb-zooming-slider-holder extended-custom-icon',
			'category' => 'by EDGE',
			'as_parent' => array('only' => 'edgtf_zooming_slider_item'),
			'content_element' => true,
			'show_settings_on_create' => false,
			'params' => array(),
			'js_view' => 'VcColumnView'
		));
	}

	public function render($atts, $content = null) {

		$params['content'] = $content;
	
		$html = edgtf_quadric_get_shortcode_module_template_part('templates/zooming-slider-holder-template', 'zooming-slider', '', $params);

		return $html;

	}

}