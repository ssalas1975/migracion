<?php
namespace EdgefQuadricModules\FloatingSlidePost;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class FloatingSlidePost implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_floating_slide_post';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	public function vcMap() {
		$posts_array = get_posts( array(
			'posts_per_page'   => (int)wp_count_posts('post')->publish,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'post_status'      => 'publish',
		) );
		$posts_dropdown = array("" => "");
		foreach ($posts_array as $le_post) {
			$posts_dropdown[$le_post->post_title] = $le_post->ID; 
		}
		vc_map( array(
			"name" => "Floating Slide Post",
			"base" => "edgtf_floating_slide_post",
			"category" => 'by EDGE',
			"as_child" => array('only' => 'edgtf_floating_slide'),
			"icon" => "icon-wpb-floating-slide-post extended-custom-icon",
			"params" => array(
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"value" => $posts_dropdown,
					'save_always' => true,
					"heading" => "Post",
					"param_name" => "post",
					"description" => "Choose the post you would like to display. Make sure it has a featured image."
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
					"heading" => "Featured Image Size",
					"param_name" => "image_size",
					"description" => "Choose the appropriate size depending on the size of the container.",
					"dependency" => array('element' => 'post', 'not_empty' => true),
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
					"heading" => "Appearance of the Featured Image",
					"param_name" => "bgnd_size",
					"description" => "Relation between the image and the container below.",
					"dependency" => array('element' => 'post', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Container Position - Top (%)",
					"param_name" => "top",
					"description" => "Distance of the container's center from the top edge of the containing slide, e.g. 50 to be vertically centered.",
					"dependency" => array('element' => 'post', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Container Position - Left (%)",
					"param_name" => "left",
					"description" => "Distance of the container's center from the left edge of the containing slide, e.g. 50 to be horizontally centered.",
					"dependency" => array('element' => 'post', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Container Width (%)",
					"param_name" => "width",
					"description" => "How much of the containing slide's width the container should take.",
					"dependency" => array('element' => 'post', 'not_empty' => true),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Container Height (%)",
					"param_name" => "height",
					"description" => "How much of the containing slide's height the container should take.",
					"dependency" => array('element' => 'post', 'not_empty' => true),
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
					"description" => "Whether the post should open in the same or a new window.",
					"dependency" => array('element' => 'post', 'not_empty' => true),
				),
			)
		) );

	}

	public function render($atts, $content = null) {
		$args = array(
            'post' => '',
            'image_size' => '',
            'bgnd_size' => '',
            'target' => '_self',
            'width' => '',
            'height' => '',
            'top' => '',
            'left' => '',
        );

        extract(shortcode_atts($args, $atts));

        $the_post = get_post((int)$post);
        $the_post_title = $the_post->post_title;
        $the_post_url = get_permalink((int)$post);
        $featured_image_id = get_post_thumbnail_id((int)$post);
        $the_image = wp_get_attachment_image_src($featured_image_id,$image_size);
        list($month, $day, $year) = explode(' ', get_the_date('M j Y',(int)$post));

        $html = "";

        $html .=    
            (($bgnd_size == 'cover' || $bgnd_size == '100% 100%') ? '<div class="fs-container" data-width="'. esc_attr($width) .'" data-height="'. esc_attr($height) .'" data-top="'. esc_attr($top) .'" data-left="'. esc_attr($left) .'" style="background-image: url(' . $the_image[0] . ');'.(!empty($bgnd_size) ? ' background-size: '.$bgnd_size.';' : '').'">' : '') .
            (($bgnd_size == 'contain' || $bgnd_size == '') ? '<div class="fs-container" data-width="'. esc_attr($width) .'" data-top="'. esc_attr($top) .'" data-left="'. esc_attr($left) .'">' : '') .
            	(($bgnd_size == 'contain' || empty($bgnd_size)) ? wp_get_attachment_image($featured_image_id, $image_size) : '') .
            	'<div class="fs-cont-date">'. 
					'<span class="edgtf-date-month">'. $month .'</span>' .
					'<span class="edgtf-date-day">'. $day .'</span>' .
					'<span class="edgtf-date-year">'. $year .'</span>' .
				'</div>' .
				'<div class="fs-cont-title"><h2>'. $the_post_title .'</h2></div>' .
                '<a href="'. $the_post_url .'" target="'. esc_attr($target) .'"></a>' .
            '</div>' 
        ;

        return $html;
	}
	
}