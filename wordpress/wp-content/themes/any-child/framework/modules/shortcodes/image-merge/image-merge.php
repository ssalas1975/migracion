<?php
namespace AnyEdge\Modules\ImageMerge;

use AnyEdge\Modules\Shortcodes\Lib\ShortcodeInterface;

class ImageMerge implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'edgtf_image_merge';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){
			vc_map( 
				array(
					'name' => 'Image Merge',
					'base' => $this->base,
					'category' => 'by EDGE',
					'icon' => 'icon-wpb-image-merge extended-custom-icon',
					'allowed_container_element' => 'vc_row',
					"params" => array(
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => "Scroll Amount (px)",
							"param_name" => "height",
							"description" => "How many pixels of scrolling the animation lasts. The default value adapts to smaller screens."
						),
						array(
							"type" => "attach_image",
							"holder" => "div",
							"class" => "",
							"heading" => "Quicker Top Image",
							"param_name" => "ver_img_1",
							"description" => "This image enters the screen from top moving faster."
						),
						array(
							"type" => "attach_image",
							"holder" => "div",
							"class" => "",
							"heading" => "Quicker Bottom Image",
							"param_name" => "ver_img_2",
							"description" => "This image enters the screen from bottom moving faster."
						),
						array(
							"type" => "attach_image",
							"holder" => "div",
							"class" => "",
							"heading" => "Slower Top Image",
							"param_name" => "ver_img_3",
							"description" => "This image enters the screen from top moving slower."
						),
						array(
							"type" => "attach_image",
							"holder" => "div",
							"class" => "",
							"heading" => "Slower Bottom Image",
							"param_name" => "ver_img_4",
							"description" => "This image enters the screen from bottom moving slower."
						),
						array(
							"type" => "attach_image",
							"holder" => "div",
							"class" => "",
							"heading" => "Image Entering From the Left",
							"param_name" => "hor_img_1",
							"description" => "This image enters the screen from the left."
						),
						array(
							"type" => "attach_image",
							"holder" => "div",
							"class" => "",
							"heading" => "Image Entering From the Right",
							"param_name" => "hor_img_2",
							"description" => "This image enters the screen from the right."
						)
					)				
				)
			);			
		}
	}

	public function render($atts, $content = null) {
		$args = array(
			'height'     	=> '',
			'ver_img_1'     => '',
			'ver_img_2'     => '',
			'ver_img_3'     => '',
			'ver_img_4'     => '',
			'hor_img_1'     => '',
			'hor_img_2'     => '',
		);
		
		$params = 	shortcode_atts($args, $atts);
		extract($params);

		$params['shortcode_styles'] =  $this->getShortcodeStyles($params);

		$html = any_edge_get_shortcode_module_template_part('templates/image-merge-template', 'image-merge', '', $params);

		return $html;
	}


	/**
	 * Return shortcode styles
	 *
	 * @param $params
	 * @return array
	 */
	private function getShortcodeStyles($params) {

		$shortcode_styles = array();

		if ($params['height'] !== '') {
			$shortcode_styles[] = 'height: ' . any_edge_filter_px($params['height']);
		}

		return implode(';', $shortcode_styles);

	}

}
