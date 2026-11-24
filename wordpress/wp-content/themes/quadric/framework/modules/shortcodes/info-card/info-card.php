<?php
namespace EdgefQuadricModules\InfoCard;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;
/**
 * Class InfoCard
 */
class InfoCard implements ShortcodeInterface {

	/**
	 * @var string
	 */
	private $base;

	public function __construct() {
		$this->base = 'edgtf_infocard';

		add_action('vc_before_init', array($this, 'vcMap'));
	}

	/**
	 * Returns base for shortcode
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	/**
	 * Maps shortcode to Visual Composer. Hooked on vc_before_init
	 *
	 * @see edgtf_core_get_carousel_slider_array_vc()
	 */
	public function vcMap() {

		vc_map( array(
				'name' => 'Info Card',
				'base' => $this->getBase(),
				'category' => 'by EDGE',
				'icon' => 'icon-wpb-info-card extended-custom-icon',
				'allowed_container_element' => 'vc_row',
				'params' => array_merge(
	                edgtf_quadric_icon_collections()->getVCParamsArray(),
	                array(
						array(
							"type" => "textarea",
							"heading" => "Title",
							"param_name" => "title",
						),
						array(
							"type" => "textarea",
							"heading" => "Text",
							"param_name" => "text",
						),
						array(
							"type" => "textarea",
							"heading" => "Link",
							"param_name" => "link",
							"value" => "http://",
							"save_always" => true,
							"description" => "Enter the URL to link to."
						),
						array(
							"type" => "attach_image",
							"heading" => "Initial image",
							"param_name" => "initial_image",
						),
						array(
							"type" => "colorpicker",
							"heading" => "Hover Background Color Overlay",
							"param_name" => "hover_background_color",
							"value" => "",
						),
						array(
							"type" => "colorpicker",
							"heading" => "Title Color",
							"param_name" => "title_color",
							"value" => "",
						),
						array(
							"type" => "colorpicker",
							"heading" => "Text Color",
							"param_name" => "text_color",
							"value" => "",
						),
						array(
							"type" => "colorpicker",
							"heading" => "Icon Color",
							"param_name" => "icon_color",
							"value" => "",
						)
					) 
				)
		) );

	}

	/**
	 * Renders shortcodes HTML
	 *
	 * @param $atts array of shortcode params
	 * @return string
	 */
	public function render($atts, $content = null) {

		$default_atts = array(
			'title' => '',
			'text' => '',
			'link' => '',
			'initial_image' => '',
			'hover_background_color' => '',
			'title_color' => '',
			'text_color' => '',
			'icon_color' => ''
		);

		$default_atts = array_merge($default_atts, edgtf_quadric_icon_collections()->getShortcodeParams());
		$params       = shortcode_atts($default_atts, $atts);

		$params['icon'] = $this->getInfoCardIconParams($params);
		$params['infocard_initial_image'] = $this->getInfoCardInitialImage($params);
		$params['hover'] = $this->getInfoCardHoverBackgroundColor($params);
		$params['title_style'] = $this->getInfoCardTitleStyle($params);
		$params['text_style'] = $this->getInfoCardTextStyle($params);

		//Get HTML from template
		$html = edgtf_quadric_get_shortcode_module_template_part('templates/info-card-template', 'info-card', '', $params);

		return $html;

	}

	/**
	 * Return Style for Info Card - ICON
	 *
	 * @param $params
	 * @return string
	 */
	private function getInfoCardIconParams($params) {

	$params_array = array();

	$icon = '';

	$iconPackName = edgtf_quadric_icon_collections()->getIconCollectionParamNameByKey($params['icon_pack']);

	if (($iconPackName) != '') {

		$params_array['icon_pack']   = $params['icon_pack'];
		$params_array[$iconPackName] = $params[$iconPackName];

	}
	if ($params['icon_color'] !== '') {
		$params_array['icon_color'] = $params['icon_color'];
	}
	$icon = edgtf_quadric_execute_shortcode('edgtf_icon', $params_array);

	return $icon;

	}

	/**
	 * Return Style for Info Card - INITIAL IMAGE
	 *
	 * @param $params
	 * @return string
	 */
	private function getInfoCardInitialImage($params) {
		$infocard_initial_image = array();

		$image_url = '';

		if (is_numeric($params['initial_image'])) {
			$image_url = wp_get_attachment_url($params['initial_image']);
		} else {
			$image_url = $params['initial_image'];
		}

		if ($params['initial_image'] !== '') {
			$infocard_initial_image[] = 'background-image: url('.$image_url.')';
		}

		return implode(';', $infocard_initial_image);

	}

	/**
	 * Return Style for Info Card - HOVER COLOR
	 *
	 * @param $params
	 * @return string
	 */
	private function getInfoCardHoverBackgroundColor($params) {
		$hover = '';

		if ($params['hover_background_color'] !== '') {
			$hover = 'background-color: '.($params['hover_background_color']).' ';
		}

		return $hover;
	}


	/**
	 * Return Style for Info Card - Title
	 *
	 * @param $params
	 * @return string
	 */
	private function getInfoCardTitleStyle($params) {
		$style = '';

		if ($params['title_color'] !== '') {
			$style = 'color: '.($params['title_color']);
		}

		return $style;
	}

	/**
	 * Return Style for Info Card - Text
	 *
	 * @param $params
	 * @return string
	 */
	private function getInfoCardTextStyle($params) {
		$style = '';

		if ($params['text_color'] !== '') {
			$style = 'color: '.($params['text_color']);
		}

		return $style;
	}
}