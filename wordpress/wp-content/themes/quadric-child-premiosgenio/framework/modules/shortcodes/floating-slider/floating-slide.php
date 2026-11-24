<?php
namespace EdgefQuadricModules\FloatingSlide;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class FloatingSlide implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_floating_slide';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	public function vcMap() {

		vc_map( array(
			"name" => "Floating Slide",
			"base" => $this->base,
			"category" => 'by EDGE',
			"as_parent" => array('only' => 'edgtf_floating_slide_image,edgtf_floating_slide_post,edgtf_floating_slide_element'),
			"as_child" => array('only' => 'edgtf_floating_slider'),
			"icon" => "icon-wpb-floating-slide extended-custom-icon",
			"description" => "Slide containing floating images or posts.",
			"js_view" => 'VcColumnView',
			"params" => array(
				array(
					"type" => "attach_image",
					"holder" => "div",
					"class" => "",
					"heading" => "Background Floating Image",
					"param_name" => "floating_image",
					"description" => "For better effect, choose a smaller image, as its multiple instances will float around.",
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"value" => array(
						"Top -> Bottom"		=>	"top-bottom",
						"Bottom -> Top"		=>	"bottom-top",
						"Left -> Right"		=>	"left-right",
						"Right -> Left"		=>	"right-left",
						"Top Left -> Bottom Right"		=>	"top-left-bottom-right",
						"Top Right -> Bottom Left"		=>	"top-right-bottom-left",
						"Bottom Left -> Top Right"		=>	"bottom-left-top-right",
						"Bottom Right -> Top Left"		=>	"bottom-right-top-left",
					),
					'save_always' => true,
					"heading" => "Floating Direction",
					"param_name" => "direction",
					"description" => "Direction in which background images float.",
					"dependency" => array('element' => 'floating_image', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"value" => "3",
					"heading" => "Floating Speed",
					"param_name" => "speed",
					"description" => "How many pixels per second background floating images move.",
					"dependency" => array('element' => 'floating_image', 'not_empty' => true),
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"value" => array(
						"Yes"	=>	"yes",
						"No"	=>	"no",
					),
					'save_always' => true,
					"heading" => "Zig-Zag Floating",
					"param_name" => "zigzag",
					"description" => "\"Yes\" will cause half of the images to float in opposite direction.",
					"dependency" => array('element' => 'floating_image', 'not_empty' => true),
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"value" => array(
						"No"	=>	"no",
						"Yes"	=>	"yes"
					),
					'save_always' => true,
					"heading" => "Random Sized Floating Images",
					"param_name" => "randsize",
					"description" => "\"Yes\" renders the floating image in random sizes.",
					"dependency" => array('element' => 'floating_image', 'not_empty' => true),
				),
			)
		) );

	}

	public function render($atts, $content = null) {
		$args = array(
            'floating_image' => '',
            'direction' => '',
            'zigzag' => '',
            'randsize' => '',
            'speed' => '3',
        );

        extract(shortcode_atts($args, $atts));

        $the_floating_image = wp_get_attachment_image_src($floating_image,'full');

        $html = "";

        $html .=    
            '<div class="edgtf-fs-slide"'.(!empty($floating_image) ? ' data-dir="'. $direction .'" data-zigzag="'. $zigzag .'" data-randsize="'. $randsize .'" data-floaterspeed="'. $speed .'" data-floaterwidth="'. $the_floating_image[1] .'" data-floaterheight="'. $the_floating_image[2] .'" data-floatersrc="'. $the_floating_image[0] .'"' : '').'>'.
                do_shortcode($content) .
            '</div>'
        ;

        return $html;
	}
	
}