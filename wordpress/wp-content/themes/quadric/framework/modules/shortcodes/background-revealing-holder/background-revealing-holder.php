<?php
namespace EdgefQuadricModules\BackgroundRevealingHolder;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class BackgroundRevealingHolder implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_background_revealing_holder';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name' => 'Background-Revealing Holder',
			'base' => $this->base,
			'category' => 'by EDGE',
			'as_parent' => array('except' => 'vc_row'),
			'content_element' => true,
			'icon' => 'icon-wpb-hover-revealing-background extended-custom-icon',
			'description' => 'Background reveals or conceals on hover.',
			'js_view' => 'VcColumnView',
			'params' => array(
				array(
					'type' => 'attach_image',
					'heading' => 'Background Image',
					'param_name' => 'background_image',
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Background Color',
					'param_name' => 'background_color',
					'value' => '',
					'description' => 'The color that interactively covers the background image.'
				),
				array(
					'type' => 'textfield',
					'heading' => 'Padding',
					'param_name' => 'padding',
					'value' => '',
					'description' => 'Please insert padding in format (top right bottom left) i.e. 5px 5px 5px 5px'
				),
				array(
					'type' => 'dropdown',
					'value' => array(
						'Reveal image'		=>	'reveal',
						'Conceal image'		=>	'conceal',
					),
					'save_always' => true,
					'admin_label' => true,
					'heading' => 'Hover Action',
					'param_name' => 'action',
					'description' => 'Choose what happens when the mouse is over the holder.',
				)
			)
		));
	}

	public function render($atts, $content = null) {
	
		$args = array(
			'background_image'			=> '',
			'background_color'			=> '',
			'padding'				=> '',
			'action'				=> '',
		);
		$params = shortcode_atts($args, $atts);
		extract($params);

		$params['content'] = $content;
		$params['curtain_style'] = $this->getCurtainStyle($params);
		$params['curtain_class'] = $this->getCurtainClass($params);
		$params['holder_class'] = $this->getHolderClass($params);
		$params['holder_style'] = $this->getHolderStyle($params);
		$params['content_style'] = $this->getContentStyle($params);

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/background-revealing-holder-template', 'background-revealing-holder', '', $params);

		return $html;

	}

	/**
	 * Return Holder Curtain style
	 *
	 * @param $params
	 * @return array
	 */
	private function getCurtainStyle($params) {

		$curtain_style = array();

		if ($params['background_color'] !== '') {
			$curtain_style[] = 'background-color: ' . $params['background_color'];
		}

		return implode(';', $curtain_style);

	}

	/**
	 * Return Holder style
	 *
	 * @param $params
	 * @return array
	 */
	private function getHolderStyle($params) {

		$holder_style = array();

		if ($params['background_image'] !== '') {
			$image = wp_get_attachment_image_src($params['background_image'],'full');
			$holder_style[] = 'background-image:url(' . $image[0] . ');';
		}

		return implode(';', $holder_style);

	}

	/**
	 * Return Content style
	 *
	 * @param $params
	 * @return array
	 */
	private function getContentStyle($params) {

		$content_style = array();

		if ($params['padding'] !== '') {
			$content_style[] = 'padding:' . $params['padding'];
		}

		return implode(';', $content_style);

	}


	/**
	 * Return Holder Curtain class
	 *
	 * @param $params
	 * @return array
	 */
	private function getCurtainClass($params) {

		$curtain_class = array();

		if ($params['action'] != 'conceal') {
			$curtain_class[] = 'edgtf-pulled';
		}

		return implode(' ', $curtain_class);

	}

	/**
	* Return Hover class
	*
	* @param $params
	* @return array
	*/
	private function getHolderClass($params) {

		$hover_class = array();

		if ($params['action'] == 'conceal') {
			$hover_class[] = 'edgtf-default-image';
		} else{
			$hover_class[] = 'edgtf-default-color';
		}
		return implode(' ', $hover_class);

	}
}
