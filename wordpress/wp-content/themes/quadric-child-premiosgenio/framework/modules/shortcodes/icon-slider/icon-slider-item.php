<?php
namespace EdgefQuadricModules\IconSliderItem;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class IconSliderItem implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'edgtf_icon_slider_item';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){

			$icon_slider_icons_array = array();
			$icon_slider_IconCollections = edgtf_quadric_icon_collections()->iconCollections;
			foreach($icon_slider_IconCollections as $collection_key => $collection) {

			    $icon_slider_icons_array[] = array(
			        'type' => 'dropdown',
			        'heading' => 'Slide Navigation Icon',
			        'param_name' => 'icon_'.$collection->param,
			        'value' => $collection->getIconsArray(),
			        'save_always' => true,
			        'dependency' => Array('element' => 'icon_family', 'value' => array($collection_key))
			    );

			}

			vc_map( array(
				'name' => 'Icon Slider Item',
				'base' => $this->getBase(),
				'as_parent' => array('only' => 'vc_column_text,vc_text_separator,edgtf_separator,edgtf_button,edgtf_zig_zag_separator'),
				'as_child' => array('only' => 'edgtf_icon_slider_holder'),
				'category' => 'by EDGE',
				'icon' => "icon-wpb-icon-slider-item extended-custom-icon",
				'show_settings_on_create' => true,
				'js_view' => 'VcColumnView',
				'params' =>array_merge(
						array(
							array(
								'type' => 'dropdown',
								'heading' => 'Icon Pack',
								'param_name' => 'icon_family',
								'value' =>  array_merge(array('No Icon' => ''),edgtf_quadric_icon_collections()->getIconCollectionsVC()),
			                    'save_always' => true
							),
						),	
						$icon_slider_icons_array,
						array(
							array(
								'type' => 'attach_image',
						        'heading' => 'Slide Image',
						        'param_name' => 'slide_image',
						        'value' => '',
						        'description' =>esc_html__('Select image from media library.','quadric'),
							),
							array(
								'type' => 'attach_image',
						        'heading' => 'Content Background Image',
						        'param_name' => 'content_background_image',
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
			'slide_image' => '',
			'content_background_image' => '',
		);		

		$params = shortcode_atts($args, $atts);

		$params['slide_image_src'] = $this->getIconSliderImageURL($params['slide_image']);
		$params['slide_image_responsive'] = $this->getIconSliderBackgroundImage($params['slide_image']);
		$params['style'] = $this->getIconSliderBackgroundImage($params['content_background_image']);		
		$params['content'] = $content;

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/icon-slider-item-template', 'icon-slider', '', $params);

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

	/**
	 * Return Icon Slider Content Slide Style
	 *
	 * @param $img
	 * @return array
	 */
	private function getIconSliderBackgroundImage($img) {

		
		$style = array();

		$content_background_image = $this->getIconSliderImageURL($img);

		if(!empty($content_background_image)){
			$style[] = 'background-image:url('.esc_url($content_background_image).')';
			$style[] = 'background-size:cover';
			$style[] = 'background-repeat:no-repeat';
		}

		return $style;
	}
}
