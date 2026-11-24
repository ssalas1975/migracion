<?php
namespace EdgefQuadricModules\Number;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class Number implements ShortcodeInterface{

	private $base;

	function __construct() {
		$this->base = 'edgtf_number';
		add_action('vc_before_init', array($this, 'vcMap'));
	}

	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {

		vc_map(
			array(
				'name' => 'Number',
				'base' => $this->base,
				'category' => 'by EDGE',
				'icon' => 'icon-wpb-number extended-custom-icon',
				'show_settings_on_create' => true,
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => 'Number',
						'param_name' => 'number',
						'value' => ''
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Position',
						'param_name' => 'position',
						'value' => array(
							'Left'			=> 'left',
							'Center'		=> 'center',
							'Right'			=> 'right'
						),
						'save_always' => true
					),
					array(
						'type' => 'colorpicker',
						'heading' => 'Color',
						'param_name' => 'color',
						'value' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => 'Font Size',
						'param_name' => 'font_size',
						'value' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => 'Font Family',
						'param_name' => 'font_family',
						'value' => '',
						'description' => ''
					),
					array(
						'type'        => 'dropdown',
						'heading'     => 'Font Weight',
						'param_name'  => 'font_weight',
						'value'       => edgtf_quadric_get_font_weight_array(true),
						'save_always' => true
					),
					array(
						'type'        => 'dropdown',
						'heading'     => 'Font Style',
						'param_name'  => 'font_style',
						'value'       => edgtf_quadric_get_font_style_array(true)
					),
					array(
						'type' => 'textfield',
						'heading' => 'Letter Spacing',
						'param_name' => 'letter_spacing',
						'value' => ''
					)
				)
			)
		);

	}

	public function render($atts, $content = null) {
		$args = array(
			'number'			=>	'',
			'position'			=>	'left',
			'color'				=>	'',
			'font_size'			=>	'',
			'font_family'		=>	'',
			'font_weight'		=>	'',
			'font_style'		=>	'',
			'letter_spacing'	=>	''
		);
		
		$params = shortcode_atts($args, $atts);
		extract($params);
		$params['number_class'] = $this->getNumberClass($params);
		$params['number_style'] = $this->getNumberStyle($params);


		$html = edgtf_quadric_get_shortcode_module_template_part('templates/number-template', 'number', '', $params);

		return $html;
	}


	/**
	 * Return Separator classes
	 *
	 * @param $params
	 * @return array
	 */
	private function getNumberClass($params) {

		$number_class = array();

		if ($params['position'] !== '') {
			$number_class[] = 'edgtf-number-'.$params['position'];
		}


		return implode(' ', $number_class);

	}

	/**
	 * Return Number style
	 *
	 * @param $params
	 * @return array
	 */
	private function getNumberStyle($params) {

		$number_style = array();

		if ($params['color'] !== '') {
			$number_style[] = 'color: ' . $params['color'];
		}
		if ($params['font_size'] !== '') {
			$number_style[] = 'font-size: ' . $params['font_size']. 'px';
		}
		if ($params['font_family'] !== '') {
			$number_style[] = 'font-family: ' . $params['font_family'];
		}
		if ($params['font_weight'] !== '') {
			$number_style[] = 'font-weight: ' . $params['font_weight'];
		}
		if ($params['font_style'] !== '') {
			$number_style[] = 'font-style: ' . $params['font_style'];
		}
		if ($params['letter_spacing'] !== '') {
			$number_style[] = 'letter-spacing: ' . $params['letter_spacing'] . 'px';
		}
		return implode(';', $number_style);

	}

}
