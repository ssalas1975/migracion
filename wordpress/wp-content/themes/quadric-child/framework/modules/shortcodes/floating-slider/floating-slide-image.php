<?php
namespace EdgefQuadricModules\FloatingSlideImage;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class FloatingSlideImage implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_floating_slide_image';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	public function vcMap() {

		vc_map( array(
			"name" => "Floating Slide Image",
			"base" => "edgtf_floating_slide_image",
			"category" => 'by EDGE',
			"as_child" => array('only' => 'edgtf_floating_slide'),
			"icon" => "icon-wpb-floating-slide-image extended-custom-icon",
			"params" => array(
				array(
					"type" => "attach_image",
					"holder" => "div",
					"class" => "",
					"heading" => "Image",
					"param_name" => "image",
					"description" => "Image will be cropped to best fit its container defined below."
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"value" => array(
						"Thumbnail" => "thumbnail",
						"Medium"	=>	"medium",
						"Large"	=>	"large",
						"Full"	=>	"full",
					),
					'save_always' => true,
					"heading" => "Image Size",
					"param_name" => "image_size",
					"description" => "Choose the appropriate size depending on the size of the container.",
					"dependency" => array('element' => 'image', 'not_empty' => true),
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"value" => array(
						"" => "",
						"Show the entire image"	=>	"contain",
						"Crop the image to best fit the container"	=>	"cover",
						"Stretch the image to fill the container"	=>	"100% 100%",
					),
					'save_always' => true,
					"heading" => "Appearance of the Image",
					"param_name" => "bgnd_size",
					"description" => "Relation between the image and the container below.",
					"dependency" => array('element' => 'image', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Image Container Position - Top (%)",
					"param_name" => "top",
					"description" => "Distance of the container's center from the top edge of the containing slide, e.g. 50 to be vertically centered.",
					"dependency" => array('element' => 'image', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Image Container Position - Left (%)",
					"param_name" => "left",
					"description" => "Distance of the container's center from the left edge of the containing slide, e.g. 50 to be horizontally centered.",
					"dependency" => array('element' => 'image', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Image Container Width (%)",
					"param_name" => "width",
					"description" => "How much of the containing slide's width the container should take.",
					"dependency" => array('element' => 'image', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Image Container Height (%)",
					"param_name" => "height",
					"description" => "How much of the containing slide's height the container should take.",
					"dependency" => array('element' => 'bgnd_size', 'value' => array('cover','100% 100%')),
				),
				/*
				array(
					"type" => "textarea_html",
					"holder" => "div",
					"class" => "",
					"heading" => "Text Over Image",
					"param_name" => "content",
				),
				*/
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Link",
					"param_name" => "link",
					"description" => "Click on the image will lead to this link.",
					"dependency" => array('element' => 'image', 'not_empty' => true),
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"value" => array(
						"Self"	=>	"_self",
						"Blank"	=>	"_blank",
					),
					'save_always' => true,
					"heading" => "Target",
					"param_name" => "target",
					"description" => "Whether the link should open in the same or a new window.",
					"dependency" => array('element' => 'link', 'not_empty' => true),
				),
			)
		) );

	}

	public function render($atts, $content = null) {
		$args = array(
            'image' => '',
            'image_size' => '',
            'bgnd_size' => '',
            'link' => '',
            'target' => '_self',
            'width' => '',
            'height' => '',
            'top' => '',
            'left' => '',
        );

        extract(shortcode_atts($args, $atts));

        $the_image = wp_get_attachment_image_src($image,$image_size);

        $html = "";

        $html .=    
            (($bgnd_size == 'cover' || $bgnd_size == '100% 100%') ? '<div class="fs-container" data-width="'. esc_attr($width) .'" data-height="'. esc_attr($height) .'" data-top="'. esc_attr($top) .'" data-left="'. esc_attr($left) .'" style="background-image: url(' . $the_image[0] . ');'.(!empty($bgnd_size) ? ' background-size: '.$bgnd_size.';' : '').'">' : '') .
        	(($bgnd_size == 'contain' || $bgnd_size == '') ? '<div class="fs-container" data-width="'. esc_attr($width) .'" data-top="'. esc_attr($top) .'" data-left="'. esc_attr($left) .'">' : '') .
                //'<div class="fs-cont-title">'. $content .'</div>' .
            	(($bgnd_size == 'contain' || empty($bgnd_size)) ? wp_get_attachment_image($image, $image_size) : '') .
                '<a href="'. esc_url($link) .'" target="'. esc_attr($target) .'"></a>' .
            '</div>' 
        ;

        return $html;
	}
	
}