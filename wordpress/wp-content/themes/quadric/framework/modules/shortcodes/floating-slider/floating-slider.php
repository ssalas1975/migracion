<?php
namespace EdgefQuadricModules\FloatingSlider;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class FloatingSlider implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_floating_slider';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}

	public function vcMap() {
		vc_map( array(
			"name" => "Floating Slider",
			"base" => $this->base,
			"icon" => "icon-wpb-floating-slider extended-custom-icon",
			"category" => 'by EDGE',
			"as_parent" => array('only' => 'edgtf_floating_slide'),
			"description" => "Container for slides with floating content.",
			"js_view" => 'VcColumnView',
			"params" => array(
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"value" => "1000",
					"heading" => "Sliding Duration (ms)",
					"param_name" => "change_duration",
					"description" => "Duration of switch between two slides in milliseconds.",
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"value" => "1",
					"save_always" => true,
					"heading" => "Fly-Out Zooming Factor (0-1)",
					"param_name" => "zoomout",
					"description" => "How much images shrink as they fly out of screen.",
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"value" => "",
					"heading" => "Slides with Hidden Navigation",
					"param_name" => "nav_hide",
					"description" => "Enter comma-separated list of slide numbers on which to hide menu and navigation.",
				),
			)
		) );

	}

	public function render($atts, $content = null) {
		$args = array(
            'change_duration' => '1000', // in ms
            'zoomout' => '1',
            'nav_hide' => ''
        );

        extract(shortcode_atts($args, $atts));

        $regex = "/(\\[edgtf_floating_slide]|\\[edgtf_floating_slide\\s)/"; 
        preg_match_all($regex, $content, $matches);
        $no_of_slides = count($matches[1]);

        $html = "";

        $html .= 
            '<div class="edgtf-floating-slider" data-changeduration="' . $change_duration . '" data-zoomout="' . $zoomout . '" data-nav-hide="' . esc_attr($nav_hide) . '">' .
                '<div class="edgtf-fs-background-elements">' .
                    '<div class="edgtf-fs-background-elements-inner fs-bei-1"></div>' .
                    '<div class="edgtf-fs-background-elements-inner fs-bei-2"></div>' .
                '</div>' .
                '<div class="edgtf-fs-front-content">' .
                    '<div class="edgtf-fs-front-content-inner">' .
                        do_shortcode($content) .
                    '</div>' .
                '</div>' .
            '</div>'
        ;

        $html .=
            '<div class="edgtf-fs-nav">'
        ;
        for ($i = 1; $i <= $no_of_slides; $i++) {
            $html .= '<div class="nav_but"><span>'.$i.'</span></div>';
        }
        $html .=
            '</div>'
        ;

        return $html;
	}
	
}
