<?php
namespace EdgefQuadricModules\ContentSliderHolder;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ContentSliderHolder implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_content_slider_holder';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name' => 'Content Slider Holder',
			'base' => $this->getBase(),
			'icon' => 'icon-wpb-content-slider-holder extended-custom-icon',
			'category' => 'by EDGE',
			'as_parent' => array('only' => 'edgtf_content_slider_item'),
			'content_element' => true,
			'show_settings_on_create' => false,
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => 'Overlapping Slide Image',
					'param_name' => 'overlap',
					'value' => array(
						'No'   => 'not-overlapping',
						'Yes'  => 'overlapping',
					),
					'save_always' => true
				),
				array(
					'type' => 'dropdown',
					'heading' => 'Slides Content Alignment',
					'param_name' => 'alignment',
					'value' => array(
						'Outer Edges'   => 'outer',
						'Center'  => 'center',
					),
					'save_always' => true
				),
				array(
					'type' => 'dropdown',
					'heading' => 'Pagination Skin',
					'param_name' => 'pagination',
					'value' => array(
						'Dark'   => 'dark',
						'Light'  => 'light',
					),
					'save_always' => true
				)
				),
			'js_view' => 'VcColumnView'
		));
	}

	public function render($atts, $content = null) {

		$default_atts = (array(
			'alignment'	=> '',
			'overlap'	=> '',
			'pagination'	=> '',
		));

		$params = shortcode_atts($default_atts, $atts);
		extract($params);

		$params['content'] = $content;
		$params['classes']      = $this->getHolderClasses($params);
		$html = edgtf_quadric_get_shortcode_module_template_part('templates/content-slider-holder-template', 'content-slider', '', $params);

		return $html;

	}

	 /**
     * Returns array of HTML classes for holder
     *
     * @param $params
     *
     * @return array
     */
    private function getHolderClasses($params) {
        $classes = array('edgtf-content-slider-container');

        if($params['alignment'] == 'center') {
            $classes[] = 'edgtf-center';
        }

        if($params['overlap'] == 'overlapping') {
            $classes[] = 'overlapping';
        }else{
        	$classes[] = 'not-overlapping';
        }


        if($params['pagination'] == 'dark') {
            $classes[] = 'dark-skin';
        }else{
        	$classes[] = 'light-skin';
        }

        return $classes;
    }
}