<?php
namespace EdgefQuadricModules\ZigZagSeparator;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ZigZagSeparator implements ShortcodeInterface{

	private $base;

	function __construct() {
		$this->base = 'edgtf_zig_zag_separator';
		add_action('vc_before_init', array($this, 'vcMap'));
	}

	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {

		vc_map(
			array(
				'name' => 'Zig Zag Separator',
				'base' => $this->base,
				'category' => 'by EDGE',
				'icon' => 'icon-wpb-zig-zag-separator extended-custom-icon',
				'show_settings_on_create' => true,
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => 'Extra class name',
						'param_name' => 'class_name',
						'value' => '',
						'description' => 'Style particular content element differently - add a class name and refer to it in custom CSS.'
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Size',
						'param_name' => 'size',
						'value' => array(
							'Small'		=>	'small',
							'Medium'	=>	'medium',
							'Large'		=>	'large',
							'Extra Large'	=>	'extra-large'
						),
						'save_always' => true,
						'description' => ''
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Position',
						'param_name' => 'position',
						'value' => array(
							'Center'		=> 'center',
							'Left'			=> 'left',
							'Right'			=> 'right'
						),
						'save_always' => true
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Animated',
						'param_name' => 'animated',
						'value' => array(
							'Yes'		=> 'yes',
							'No'		=> 'no'
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
						'heading' => 'Top Margin',
						'param_name' => 'top_margin',
						'value' => '',
						'description' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => 'Bottom Margin',
						'param_name' => 'bottom_margin',
						'value' => '',
					)
				)
			)
		);

	}

	public function render($atts, $content = null) {
		$args = array(
			'class_name'	=>	'',
			'size'			=>	'',
			'position'		=>	'center',
			'animated'		=>	'yes',
			'color'			=>	'',
			'top_margin'	=>	'',
			'bottom_margin'	=>	''
		);
		
		$params = shortcode_atts($args, $atts);
		extract($params);
		$params['separator_class'] = $this->getSeparatorClass($params);
		$params['separator_holder_style'] = $this->getHolderSeparatorStyle($params);
		$params['separator_style'] = $this->getSeparatorStyle($params);
		$params['separator_data'] = $this->getSeparatorData($params);


		$html = edgtf_quadric_get_shortcode_module_template_part('templates/zig-zag-separator-template', 'zig-zag-separator', '', $params);

		return $html;
	}


	/**
	 * Return Separator classes
	 *
	 * @param $params
	 * @return array
	 */
	private function getSeparatorClass($params) {

		$separator_class = array();

		if ($params['class_name'] !== '') {
			$separator_class[] = $params['class_name'];
		}
		if ($params['position'] !== '') {
			$separator_class[] = 'edgtf-zig-zag-separator-'.$params['position'];
		}
		if ($params['size'] !== '') {
			$separator_class[] = 'edgtf-zig-zag-separator-'.$params['size'];
		}
		if ($params['animated'] == 'no') {
			$separator_class[] = 'edgtf-zig-zag-separator-on';
		}

		return implode(' ', $separator_class);

	}

	/**
	 * Return Separator Holder style
	 *
	 * @param $params
	 * @return array
	 */
	private function getHolderSeparatorStyle($params) {

		$separator_style = array();

		if ($params['top_margin'] !== '') {
			if(edgtf_quadric_string_ends_with($params['top_margin'], '%') || edgtf_quadric_string_ends_with($params['top_margin'], 'px')) {
				$separator_style[] = 'margin-top: ' . $params['top_margin'];
			}else{
				$separator_style[] = 'margin-top: ' . $params['top_margin'] . 'px';
			}
		}
		if ($params['bottom_margin'] !== '') {
			if(edgtf_quadric_string_ends_with($params['bottom_margin'], '%') || edgtf_quadric_string_ends_with($params['bottom_margin'], 'px')) {
				$separator_style[] = 'margin-bottom: ' . $params['bottom_margin'];
			}else{
				$separator_style[] = 'margin-bottom: ' . $params['bottom_margin'] . 'px';
			}
		}
		return implode(';', $separator_style);

	}

	/**
	 * Return Separator style
	 *
	 * @param $params
	 * @return array
	 */
	private function getSeparatorStyle($params) {

		$separator_style = array();

		if ($params['color'] !== '') {
			$separator_style[] = 'stroke: ' . $params['color'];
		}

		return implode(';', $separator_style);

	}

	/**
	 * Return Separator data
	 *
	 * @param $params
	 * @return array
	 */
	private function getSeparatorData($params) {

		$separator_data = array();

		if ($params['size'] == 'large') {
			$separator_data['height'] = '24';
			$separator_data['width'] = '118';
		} elseif ($params['size'] == 'extra-large'){
			$separator_data['height'] = '38';
			$separator_data['width'] = '185';
		} elseif ($params['size'] == 'small'){
			$separator_data['height'] = '12';
			$separator_data['width'] = '57';
		} else {
			$separator_data['height'] = '14';
			$separator_data['width'] = '68';
		}

		return $separator_data;

	}
}
