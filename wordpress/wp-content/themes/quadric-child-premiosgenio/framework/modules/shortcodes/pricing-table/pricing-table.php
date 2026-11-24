<?php
namespace EdgefQuadricModules\PricingTable;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class PricingTable implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_pricing_table';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name' => 'Pricing Table',
			'base' => $this->base,
			'icon' => 'icon-wpb-pricing-table extended-custom-icon',
			'category' => 'by EDGE',
			'allowed_container_element' => 'vc_row',
			'as_child' => array('only' => 'edgtf_pricing_tables'),
			'params' => array(
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Title',
					'param_name' => 'title',
					'value' => 'Basic Plan',
					'description' => ''
				),
				array(
					'type' => 'attach_image',
					'heading' => 'Title Background Image',
					'param_name' => 'title_background_image',
					'value' => ''
				),
				array(
					"type" => "colorpicker",
					"heading" => "Title Color",
					"param_name" => "title_color",
					"description" => ""
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Price',
					'param_name' => 'price',
					'description' => 'Default value is 100'
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Currency',
					'param_name' => 'currency',
					'description' => 'Default mark is $'
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Price Period',
					'param_name' => 'price_period',
					'description' => 'Default label is monthly'
				),
				array(
					'type' => 'attach_image',
					'heading' => 'Content Background Image',
					'param_name' => 'content_background_image',
					'value' => ''
				),
				array(
					'type' => 'dropdown',
					'admin_label' => true,
					'heading' => 'Show Button',
					'param_name' => 'show_button',
					'value' => array(
						'Default' => '',
						'Yes' => 'yes',
						'No' => 'no'
					),
					'description' => ''
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Button Text',
					'param_name' => 'button_text',
					'dependency' => array('element' => 'show_button',  'value' => 'yes') 
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Button Link',
					'param_name' => 'link',
					'dependency' => array('element' => 'show_button',  'value' => 'yes')
				),
//				array(
//					'type' => 'dropdown',
//					'admin_label' => true,
//					'heading' => 'Active',
//					'param_name' => 'active',
//					'value' => array(
//						'No' => 'no',
//						'Yes' => 'yes'
//					),
//					'save_always' => true,
//					'description' => ''
//				),
//				array(
//					'type' => 'textfield',
//					'admin_label' => true,
//					'heading' => 'Active text',
//					'param_name' => 'active_text',
//					'description' => 'Best choice',
//					'dependency' => array('element' => 'active', 'value' => 'yes')
//				),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Content',
					'param_name' => 'content',
					'value' => '<li>content content content</li><li>content content content</li><li>content content content</li>',
					'description' => ''
				)
			)
		));
	}

	public function render($atts, $content = null) {
	
		$args = array(
			'title'         			   => 'Basic Plan',
			'title_background_image'       => '',
			'content_background_image'       => '',
			'title_color'       			=> '',
			'price'         			   => '100',
			'currency'      			   => '$',
			'price_period'  			   => 'Monthly',
			'active'        			   => 'no',
			'active_text'   			   => 'Best choice',
			'show_button'				   => 'yes',
			'link'          			   => '',
			'button_text'   			   => 'button'
		);
		$params = shortcode_atts($args, $atts);
		extract($params);

		$html						= '';
		$pricing_table_clasess		= array();
		
		if($active == 'yes') {
			$pricing_table_clasess[] = 'edgtf-active';
		}
		
		$params['title_style'] = array();
		if($params['title_color']){
			$params['title_style'] = 'color:' . $params['title_color'];
		}

		$params['title_holder_style'] = array();
		if($params['title_background_image']){
			$params['title_holder_style'] = 'background-image:url(' . wp_get_attachment_url($params['title_background_image']) .')';
			$pricing_table_clasess[] = 'edgtf-with-title-background';
		}

		$params['content_holder_style'] = array();
		if($params['content_background_image']){
			$params['content_holder_style'] = 'background-image:url(' . wp_get_attachment_url($params['content_background_image']) .')';
			$pricing_table_clasess[] = 'edgtf-with-content-background';
		}
		$params['pricing_table_classes'] = $pricing_table_clasess;
		$params['content']= $content;
		
		$html .= edgtf_quadric_get_shortcode_module_template_part('templates/pricing-table-template','pricing-table', '', $params);
		return $html;

	}

}
