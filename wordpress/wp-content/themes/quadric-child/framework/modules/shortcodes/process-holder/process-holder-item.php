<?php
namespace EdgefQuadricModules\ProcessHolderItem;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ProcessHolderItem implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'edgtf_process_holder_item';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){
			vc_map( 
				array(
					'name' => 'Process Holder Item', 'quadric',
					'base' => $this->base,
					'as_child' => array('only' => 'edgtf_process_holder'),
					'content_element' => true,
					'category' => 'by EDGE',
					'icon' => 'icon-wpb-process-holder-item extended-custom-icon',
					'show_settings_on_create' => true,
					'params' => array(						
						array(
							'type' => 'attach_image',
							'class' => '',
							'heading' => 'Image',
							'param_name' => 'item_image',
							'value' => '',
							'description' => ''
						),
						array(
							'type' => 'checkbox',
							'class' => '',
							'heading' => 'Image Shadow?',
							'param_name' => 'image_shadow',
							'value' => array('Image Shadow'=>'yes'),
							'description' => 'It is recommended to upload 145 x 145 px image.'
						),
						array(
							'type' => 'textfield',
							'heading' => 'Number',
							'param_name' => 'item_number',
							'value' => '',
							'description' => ''
						),
						array(
							'type' => 'textfield',
							'heading' => 'Title',
							'param_name' => 'item_title',
							'value' => '',
							'description' => ''
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Title Tag',
							'param_name' => 'title_tag',
							'value' => array(
								''		=> '',
								'p'		=> 'p',
								'h1'	=> 'h1',
								'h2'	=> 'h2',
								'h3'	=> 'h3',
								'h4'	=> 'h4',
								'h5'	=> 'h5',
								'h6'	=> 'h6',
							),
							'description' => '',
							'dependency' => Array('element'=>'item_title', 'not_empty'=>true)
						),
						array(
							'type' => 'textfield',
							'heading' => 'Text',
							'param_name' => 'item_text',
							'value' => '',
							'description' => ''
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => 'Content Alignment',
							'param_name' => 'horizontal_aligment',
							'value' => array(
								'Center'    => 'center',
								'Left'    	=> 'left',
								'Right'     => 'right'
							),
							'description' => ''
						),
					)
				)
			);			
		}
	}

	public function render($atts, $content = null) {
		$args = array(
			'item_image' => '',
			'image_shadow' => '',
			'item_number' => '',
			'item_text' => '',
			'item_title' => '',
			'title_tag' => '',
			'horizontal_aligment' => ''
		);
		
		$params = shortcode_atts($args, $atts);
		extract($params);

		$params['image_shadow'] = $this->getProcessHolderImageShadow($params);
		$params['image'] = $this->getProcessHolderImage($params);
		$params['item_style'] = $this->getProcessHolderStyle($params);

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/process-holder-item-template', 'process-holder', '', $params);

		return $html;
	}

	/**
	 * Return Process Holder Image
	 *
	 * @param $params
	 * @return $background_image_src
	 */

	private function getProcessHolderImage($params){
		
		$background_image_src = '';

		if($params['item_image'] != ''){

			if (is_numeric($params['item_image'])) {
                $background_image_src = wp_get_attachment_url($params['item_image']);
            } else {
                $background_image_src = $params['item_image'];
            }
		}

		return $background_image_src;
	}

	/**
	 * Return Process Holder Image
	 *
	 * @param $params
	 * @return $shadow
	 */

	private function getProcessHolderImageShadow($params){
		
		$shadow= 'no';

		if($params['image_shadow'] != ''){

			$shadow = 'yes';
		}

		return $shadow;
	}

	/**
	 * Return Process Holder Style
	 *
	 * @param $params
	 * @return array
	 */

	private function getProcessHolderStyle($params){
		
		$style= array();

		if($params['horizontal_aligment'] != ''){

			$style[] = 'text-align:'.$params['horizontal_aligment'];
		}

		return $style;
	}


}
