<?php
namespace EdgefQuadricModules\ContentSliderItem;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ContentSliderItem implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'edgtf_content_slider_item';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){
			vc_map( array(
				'name' => 'Content Slider Item',
				'base' => $this->getBase(),
				'as_parent' => array('only' => 'vc_column_text,vc_text_separator,edgtf_separator,edgtf_button,edgtf_zig_zag_separator'),
				'as_child' => array('only' => 'edgtf_content_slider_holder'),
				'category' => 'by EDGE',
				'icon' => "icon-wpb-content-slider-item extended-custom-icon",
				'show_settings_on_create' => true,
				'js_view' => 'VcColumnView',
				'params' =>array(
						array(
							'type' => 'attach_image',
					        'heading' => 'Slide Image',
					        'param_name' => 'slide_image',
					        'value' => '',
					        'description' =>esc_html__('Select image from media library.','quadric'),
						),
						array(
							'type' => 'attach_image',
					        'heading' => 'Content Slide Background Image',
					        'param_name' => 'background_image',
					        'value' => '',
					        'description' =>esc_html__('Select image from media library.','quadric'),
						),
					),
				)
			);
		}
	}

	public function render($atts, $content = null) {


		$args = array(
			'slide_image' => '',
			'background_image' => '',
		);		

		$params = shortcode_atts($args, $atts);

		$params['slide_image_src'] = $this->getIconSliderImageURL($params['slide_image']);
		$params['background_image'] = $this->getIconSliderBackgroundImage($params['background_image']);
		$params['content'] = $content;

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/content-slider-item-template', 'content-slider', '', $params);

		return $html;
	}


	/**
	 * Return Icon Slider Image URL
	 *
	 * @param $img
	 * @return var
	 */
	private function getIconSliderImageURL($img) {

		$slider_images_src = '';

		if (is_numeric($img)) {
	    	$slide_image_src = wp_get_attachment_url($img);
		} else {
		    $slide_image_src = $img;
		}

		return $slide_image_src;
	}

	private function getIconSliderBackgroundImage($img){
		$src = $this->getIconSliderImageURL($img);
		$bck = array();
		if($src != ''){
			$bck[] = 'background-image:url('.$src.')';
			$bck[] = 'background-size:cover';
			$bck[] = 'background-position:center';
		}
		return $bck;
	}	
}