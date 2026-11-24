<?php
namespace EdgefQuadricModules\ZoomingSliderItem;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ZoomingSliderItem implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'edgtf_zooming_slider_item';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){

			vc_map( array(
				'name' => 'Zooming Slider Item',
				'base' => $this->getBase(),
				'as_parent' => array('only' => 'vc_column_text,vc_text_separator,edgtf_separator,edgtf_button,edgtf_zig_zag_separator'),
				'as_child' => array('only' => 'edgtf_icon_slider_holder'),
				'category' => 'by EDGE',
				'icon' => "icon-wpb-icon-slider-item extended-custom-icon",
				'show_settings_on_create' => true,
				'js_view' => 'VcColumnView',
				'params' =>array_merge(							
						\EdgeQuadricIconCollections::get_instance()->getVCParamsArray(),
						array(
							array(
								'type' => 'colorpicker',
						        'heading' => 'Icon Color',
						        'param_name' => 'icon_color',
						        'value' => '',
							),
							array(
								'type' => 'colorpicker',
						        'heading' => 'Background Color',
						        'param_name' => 'background_color',
						        'value' => '',
							),
							array(
								'type' => 'attach_image',
						        'heading' => 'Background Image',
						        'param_name' => 'background_image',
						        'value' => '',
							),							
						)
					),
				)
			);
		}
	}

	public function render($atts, $content = null) {


		$args = array(
			'background_image' => '',
			'background_color' => '',
			'icon_color' => '',
		);		

		$args = array_merge($args, edgtf_quadric_icon_collections()->getShortcodeParams());
		
        $params = shortcode_atts($args, $atts);
		
		//Extract params for use in method
		extract($params);

		$params['background_image'] = $this->getIconSliderBackgroundImage($params['background_image']);
		$params['background_color'] = $this->getSlideBackgroundColor($params);
		$params['content'] = $content;

		$iconPackName = edgtf_quadric_icon_collections()->getIconCollectionParamNameByKey($params['icon_pack']);
		$iconClasses = '';
		
		//generate icon holder classes
		$iconClasses .= 'edgtf-zooming-slider-icon ';
		$iconClasses .= $params['icon_pack'];
		
		$params['icon_classes'] = $iconClasses;
		$params['icon'] = $params[$iconPackName];		
		$params['icon_attributes']['style'] =  $this->getIconStyle($params);	

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/zooming-slider-item-template', 'zooming-slider', '', $params);

		return $html;
	}


	 /**
     * Generates icon styles
     *
     * @param $params
     *
     * @return array
     */
	private function getIconStyle($params){
		$iconStylesArray = array();
		
		if(!empty($params['icon_color'])) {
			$iconStylesArray[] = 'color:' . $params['icon_color'];
		}
		
		return implode(';', $iconStylesArray);
	}


	/**
	*	Get CSS for Background Color
	*
	*/


	private function getSlideBackgroundColor($params){
		$bckg_color = '';
		
		if($params['background_color'] != ''){
			$bckg_color = 'background-color:'.$params['background_color'];
		}

		return $bckg_color;
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

	/**
	 * Return Icon Slider Background Image
	 *
	 */
	private function getIconSliderBackgroundImage($img) {

		
		$background_img = array();		

		$content_background_image = $this->getIconSliderImageURL($img);

		if(!empty($content_background_image)){
			$background_img[] = 'background-image:url('.esc_url($content_background_image).')';
			$background_img[] = 'background-size:cover';
			$background_img[] = 'background-repeat:no-repeat';
		}

		return $background_img;
	}
}
