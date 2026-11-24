<?php
namespace EdgefQuadricModules\FloatingSlideElement;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class FloatingSlideElement implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_floating_slide_element';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	public function vcMap() {
		vc_map( array(
			"name" => "Floating Slide Element",
			"base" => $this->base,
			"category" => 'by EDGE',
			"as_child" => array('only' => 'edgtf_floating_slide'),
			"as_parent" => array('only' => 'edgtf_elements_holder'),
			"icon" => "icon-wpb-floating-slide-element extended-custom-icon",
			"js_view" => 'VcColumnView',
			"params" => array(
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Container Position - Top (%)",
					"param_name" => "top",
					"description" => "Distance of the container's center from the top edge of the containing slide, e.g. 50 to be vertically centered.",
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Container Position - Left (%)",
					"param_name" => "left",
					"description" => "Distance of the container's center from the left edge of the containing slide, e.g. 50 to be horizontally centered.",
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Container Width (%)",
					"param_name" => "width",
					"description" => "How much of the containing slide's width the container should take.",
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Maximum Container Height (%)",
					"param_name" => "max_height",
					"description" => "How much of the containing slide's height the container should take at most.",
				)
			)
		) );

	}

	public function render($atts, $content = null) {
		$args = array(
            'width' => '',
            'max_height' => '',
            'top' => '',
            'left' => '',
        );

        extract(shortcode_atts($args, $atts));

        $html = "";

        $html .=    
            '<div class="fs-container" data-width="'. esc_attr($width) .'" data-max-height="'. esc_attr($max_height) .'" data-top="'. esc_attr($top) .'" data-left="'. esc_attr($left) .'">' .
                do_shortcode($content) .
            '</div>' 
        ;

        return $html;
	}
	
}