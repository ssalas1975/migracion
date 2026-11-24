<?php
namespace AnyEdge\Modules\Shortcodes\DevicePresentation;

use AnyEdge\Modules\Shortcodes\Lib\ShortcodeInterface;

class DevicePresentation implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'degtf_device_presentation';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){
			vc_map( 
				array(
					'name' => esc_html__('Device Presentation', 'any'),
					'base' => $this->base,
					'content_element' => true,
					'category' => 'by EDGE',
					'icon' => 'icon-wpb-device-presentation extended-custom-icon',
					'show_settings_on_create' => true,
					'params' => array(
						array(
							'type' => 'attach_image',
							'class' => '',
							'heading' => 'Hero Image',
							'param_name' => 'hero_image',
							'value' => '',
							'group'	=> 'Hero Image'
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => 'Link',
							'param_name' => 'hero_image_link',
							'value' => '',
							'description' => 'Set an external URL to link to.',
						    'dependency' => array('element' => 'hero_image', 'not_empty' => true),
							'group'	=> 'Hero Image'
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Target',
							'param_name' => 'hero_image_target',
							'value' => array(
								'Same Window'    => '_self',
								'New Window'    => '_blank',
							),
						    'dependency' => array('element' => 'hero_image_link', 'not_empty' => true),
							'save_always' => true,
							'group'	=> 'Hero Image'
						),
						array(
							'type' => 'attach_image',
							'class' => '',
							'heading' => 'Left Tablet Image',
							'param_name' => 'left_tablet_image',
							'value' => '',
							'group'	=> 'Tablet Images'
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => 'Link',
							'param_name' => 'left_tablet_image_link',
							'value' => '',
							'description' => 'Set an external URL to link to.',
						    'dependency' => array('element' => 'left_tablet_image', 'not_empty' => true),
							'group'	=> 'Tablet Images'
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Target',
							'param_name' => 'left_tablet_image_target',
							'value' => array(
								'Same Window'    => '_self',
								'New Window'    => '_blank',
							),
						    'dependency' => array('element' => 'left_tablet_image_link', 'not_empty' => true),
							'save_always' => true,
							'group'	=> 'Tablet Images'
						),
						array(
							'type' => 'attach_image',
							'class' => '',
							'heading' => 'Right Tablet Image',
							'param_name' => 'right_tablet_image',
							'value' => '',
							'group'	=> 'Tablet Images'
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => 'Link',
							'param_name' => 'right_tablet_image_link',
							'value' => '',
							'description' => 'Set an external URL to link to.',
						    'dependency' => array('element' => 'right_tablet_image', 'not_empty' => true),
							'group'	=> 'Tablet Images'
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Target',
							'param_name' => 'right_tablet_image_target',
							'value' => array(
								'Same Window'    => '_self',
								'New Window'    => '_blank',
							),
						    'dependency' => array('element' => 'right_tablet_image_link', 'not_empty' => true),
							'save_always' => true,
							'group'	=> 'Tablet Images'
						),
						array(
							'type' => 'attach_image',
							'class' => '',
							'heading' => 'Left Phone Image',
							'param_name' => 'left_phone_image',
							'value' => '',
							'group'	=> 'Phone Images'
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => 'Link',
							'param_name' => 'left_phone_image_link',
							'value' => '',
							'description' => 'Set an external URL to link to.',
						    'dependency' => array('element' => 'left_phone_image', 'not_empty' => true),
							'group'	=> 'Phone Images'
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Target',
							'param_name' => 'left_phone_image_target',
							'value' => array(
								'Same Window'    => '_self',
								'New Window'    => '_blank',
							),
						    'dependency' => array('element' => 'left_phone_image_link', 'not_empty' => true),
							'save_always' => true,
							'group'	=> 'Phone Images'
						),
						array(
							'type' => 'attach_image',
							'class' => '',
							'heading' => 'Right Phone Image',
							'param_name' => 'right_phone_image',
							'value' => '',
							'group'	=> 'Phone Images'
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => 'Link',
							'param_name' => 'right_phone_image_link',
							'value' => '',
							'description' => 'Set an external URL to link to.',
						    'dependency' => array('element' => 'right_phone_image', 'not_empty' => true),
							'group'	=> 'Phone Images'
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Target',
							'param_name' => 'right_phone_image_target',
							'value' => array(
								'Same Window'    => '_self',
								'New Window'    => '_blank',
							),
						    'dependency' => array('element' => 'right_phone_image_link', 'not_empty' => true),
							'save_always' => true,
							'group'	=> 'Phone Images'
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Animate on Appear',
							'param_name' => 'animate_on_appear',
							'value' => array(
								'Yes'    => 'yes',
								'No'    => 'no',
							),
							'save_always' => true,
							'group'	=> 'Behavior'
						),
					)
				)
			);			
		}
	}

	public function render($atts, $content = null) {
		$args = array(
			'hero_image' => '',
			'hero_image_link' => '',
			'hero_image_target' => '_self',
			'left_tablet_image' => '',
			'left_tablet_image_link' => '',
			'left_tablet_image_target' => '_self',
			'right_tablet_image' => '',
			'right_tablet_image_link' => '',
			'right_tablet_image_target' => '_self',
			'left_phone_image' => '',
			'left_phone_image_link' => '',
			'left_phone_image_target' => '_self',
			'right_phone_image' => '',
			'right_phone_image_link' => '',
			'right_phone_image_target' => '_self',
			'animate_on_appear' => 'yes',
		);
		
		$params = shortcode_atts($args, $atts);
		extract($params);

		$params['shortcode_classes'] = $this->getShortcodeClasses($params);

		$html = any_edge_get_shortcode_module_template_part('templates/device-presentation-template' , 'device-presentation', '', $params);

		return $html;
	}

	/**
	 * Return Device Presentation classes
	 *
	 * @param $params
	 * @return array
	 */
	private function getShortcodeClasses($params) {

		$shortcode_classes = array();

		$shortcode_classes[] = 'edgtf-device-presentation';

		if ($params['animate_on_appear'] == 'yes') {
			$shortcode_classes[] = 'edgtf-animate-on-appear';
		}

		return implode(' ', $shortcode_classes);

	}

}
