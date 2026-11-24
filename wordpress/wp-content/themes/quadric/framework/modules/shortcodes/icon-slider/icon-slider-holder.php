<?php
namespace EdgefQuadricModules\IconSliderHolder;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class IconSliderHolder implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_icon_slider_holder';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name' => 'Icon Slider Holder',
			'base' => $this->getBase(),
			'icon' => 'icon-wpb-icon-slider-holder extended-custom-icon',
			'category' => 'by EDGE',
			'as_parent' => array('only' => 'edgtf_icon_slider_item'),
			'content_element' => true,
			'show_settings_on_create' => false,
			'params' => array(
				array(
					'type' => 'colorpicker',
					'heading' => 'Icons Holder Background Color',
					'param_name' => 'nav_bgnd_color',
					'value' => '',
					'description' => ''
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Icons Color',
					'param_name' => 'icon_color',
					'value' => '',
					'description' => ''
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Icons Active Color',
					'param_name' => 'icon_active_color',
					'value' => '',
					'description' => ''
				),
			),
			'js_view' => 'VcColumnView'
		));
	}

	public function render($atts, $content = null) {

		$args = array(
			'nav_bgnd_color' => '',
			'icon_color' => '',
			'icon_active_color' => '',
		);		

		$params = shortcode_atts($args, $atts);

		$params['content'] = $content;
		$params['nav_style'] = $this->getIconSliderNavStyle($params);
		$params['nav_data'] =  $this->getIconSliderNavData($params);
		$params['icon_style'] =  $this->getIconSliderIconStyle($params);
	
		$html = edgtf_quadric_get_shortcode_module_template_part('templates/icon-slider-holder-template', 'icon-slider', '', $params);

		return $html;

	}

	/**
	 * Return Icon Slider Navigation Style
	 *
	 * @param $params
	 * @return array
	 */
	private function getIconSliderNavStyle($params) {
		
		$style = array();		

		if(!empty($params['nav_bgnd_color'])){
			$style[] = 'background-color:'.$params['nav_bgnd_color'].';';
		}		

		return $style;
	}


	/**
	 * Return Icon Slider Icon Style
	 *
	 * @param $params
	 * @return array
	 */
	private function getIconSliderIconStyle($params) {
		
		$icon_style = array();		

		if(!empty($params['icon_color'])){
			$icon_style[] = 'color:'.$params['icon_color'].';';
		}

		return $icon_style;
	}


	/**
	 * Return Icon Slider Navigation Data
	 *
	 * @param $params
	 * @return array
	 */

	private function getIconSliderNavData($params) {
		
		$data = array();

		if(!empty($params['icon_active_color'])){
			$data['data-icon-active-color'] = $params['icon_active_color'];
		}		

		return $data;
	}

}
