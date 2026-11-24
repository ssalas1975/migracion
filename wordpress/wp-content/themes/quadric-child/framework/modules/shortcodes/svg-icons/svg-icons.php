<?php
namespace EdgefQuadricModules\SvgIcons;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class SvgIcons implements ShortcodeInterface{

	private $base;

	function __construct() {
		$this->base = 'edgtf_svg_icons';
		add_action('vc_before_init', array($this, 'vcMap'));
	}

	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {

		vc_map(
			array(
				'name' => 'SVG Icons',
				'base' => $this->base,
				'category' => 'by EDGE',
				'icon' => 'icon-wpb-svg-icons extended-custom-icon',
				'show_settings_on_create' => true,
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => 'Icon',
						'admin_label' => true,
						'param_name' => 'icon',
						'value' => array(
							'Device' 	=>	'icon-device',
							'File'		=>	'icon-file',
							'Home'		=>	'icon-home',
							'Lightbulb'	=>	'icon-lightbulb',
							'Screen'	=>	'icon-screen',
							'Search'	=>	'icon-search',
						),
						'save_always' => true,
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
						'type' => 'textfield',
						'heading' => 'Animation Delay (ms)',
						'param_name' => 'animation_delay',
						'value' => '',
						'dependency' => array('element' => 'animated', 'value' => array('yes'))
					),
					array(
						'type' => 'colorpicker',
						'heading' => 'Color',
						'param_name' => 'color',
						'value' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => 'Text',
						'param_name' => 'text',
						'value' => ''
					),
					array(
						'type' => 'dropdown',
						'param_name' => 'title_tag',
						'heading' => 'Title Tag',
						'value' => array(
							'Default'   => '',
							'p'  => 'p',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'h6' => 'h6',
						),
						'description' => ''
					),
					array(
						'type' => 'colorpicker',
						'heading' => 'Text Color',
						'param_name' => 'text_color',
						'value' => ''
					)
				)
			)
		);

	}

	public function render($atts, $content = null) {
		$args = array(
			'class_name'		=>	'',
			'icon'				=>	'',
			'custom_icon'		=>	'',
			'animated'			=>	'yes',
			'animation_delay'	=>	'',
			'color'				=>	'',
			'text'				=>	'',
			'title_tag'			=>	'p',
			'text_color'		=>	''
		);
		
		$params = shortcode_atts($args, $atts);
		extract($params);

		$headings_array = array('p','h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];


		$params['icons_data'] = $this->getIconsData($params);
		$params['text_style'] = $this->getTextStyle($params);
		$params['icons_class'] = $this->getIconClass($params);

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/svg-icons-template', 'svg-icons', '', $params);

		return $html;
	}

	/**
	 * Return Icon data
	 *
	 * @param $params
	 * @return array
	 */
	private function getIconsData($params) {

		$icons_data = array();

		$icons_data['data-svg-frames'] = '30';

		if ($params['animated'] !== '') {
			$icons_data['data-svg-drawing'] = $params['animated'];
		}
		if ($params['animation_delay'] !== '') {
			$icons_data['data-animation-delay'] = $params['animation_delay'];
		}


		return $icons_data;

	}

	/**
	 * Return text  style
	 *
	 * @param $params
	 * @return array
	 */
	private function getTextStyle($params) {

		$text_style = array();


		if ($params['text_color'] !== '') {
			$text_style[] = 'color:' . $params['text_color'];
		}

		return $text_style;

	}


	/**
	 * Return Icon class
	 *
	 * @param $params
	 * @return array
	 */
	private function getIconClass($params) {

		$icons_class = array();

		if ($params['text'] !== '') {
			$icons_class[] = 'edgtf-upward-animation';
		}

		return implode(' ', $icons_class);

	}

}
