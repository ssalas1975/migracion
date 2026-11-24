<?php
namespace EdgefQuadricModules\ProgressBar;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ProgressBar implements ShortcodeInterface{
	private $base;
	
	function __construct() {
		$this->base = 'edgtf_progress_bar';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {

		vc_map( array(
			'name' => 'Progress Bar',
			'base' => $this->base,
			'icon' => 'icon-wpb-progress-bar extended-custom-icon',
			'category' => 'by EDGE',
			'allowed_container_element' => 'vc_row',
			'params' => array(
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Title',
					'param_name' => 'title',
					'description' => ''
				),
				array(
					'type' => 'dropdown',
					'admin_label' => true,
					'heading' => 'Title Tag',
					'param_name' => 'title_tag',
					'value' => array(
						''   => '',
						'p'   => 'p',
						'h2' => 'h2',
						'h3' => 'h3',
						'h4' => 'h4',	
						'h5' => 'h5',	
						'h6' => 'h6',	
					),
					'description' => ''
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => 'Percentage',
					'param_name' => 'percent',
					'description' => ''
				),	
				array(
					'type' => 'dropdown',
					'admin_label' => true,
					'heading' => 'Percentage Type',
					'param_name' => 'percentage_type',
					'value' => array(
						'Floating'  => 'floating',
						'Static' => 'static'
					),
					'dependency' => Array('element' => 'percent', 'not_empty' => true)
				),	
				array(
					'type' => 'dropdown',
					'admin_label' => true,
					'heading' => 'Floating Type',
					'param_name' => 'floating_type',
					'value' => array(
						'Outside Floating'  => 'floating_outside',
						'Inside Floating' => 'floating_inside'
					),
					'dependency' => array('element' => 'percentage_type', 'value' => array('floating'))
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Bar Background Color',
					'param_name' => 'inactive_color',
					'description' => '',
					'group' => 'Design Options'
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Bar Border Color',
					'param_name' => 'inactive_border_color',
					'description' => '',
					'group' => 'Design Options'
				),
				array(
					'type' => 'attach_image',
					'heading' => 'Bar Active Background Pattern',
					'param_name' => 'background_pattern',
					'description' => '',
					'group' => 'Design Options'
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Bar Background Active Color',
					'param_name' => 'active_color',
					'description' => '',
					'group' => 'Design Options'
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Percentage Color',
					'param_name' => 'percent_color',
					'description' => '',
					'group' => 'Design Options',
					'dependency' => Array('element' => 'percent', 'not_empty' => true)
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Title Color',
					'param_name' => 'title_color',
					'description' => '',
					'group' => 'Design Options',
					'dependency' => Array('element' => 'title', 'not_empty' => true)
				),
			)
		) );

	}

	public function render($atts, $content = null) {
		$args = array(
            'title' => '',
            'background_pattern' => '',
            'inactive_color' => '',
            'inactive_border_color' => '',
            'active_color' => '',
            'percent_color' => '',
            'title_color' => '',
            'title_tag' => 'h4',
            'percent' => '100',
            'percentage_type' => 'floating',
            'floating_type' => 'floating_outside'
        );
		$params = shortcode_atts($args, $atts);
		
		//Extract params for use in method
		extract($params);
		$headings_array = array('p','h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];
		
		$params['percentage_classes'] = $this->getPercentageClasses($params);
		$params['title_style'] = $this->getTitleStyle($params);
		$params['percent_style'] = $this->getPercentageStyle($params);
		$params['bar_style'] = $this->getBarStyle($params);
		$params['active_background'] = $this->getActiveBarStyle($params);

        //init variables
		$html = edgtf_quadric_get_shortcode_module_template_part('templates/progress-bar-template', 'progress-bar', '', $params);
		
        return $html;
		
	}
	/**
    * Generates css classes for progress bar
    *
    * @param $params
    *
    * @return array
    */
	private function getPercentageClasses($params){
		
		$percentClassesArray = array();
		
		if(!empty($params['percentage_type']) !=''){
			
			if($params['percentage_type'] == 'floating'){
				
				$percentClassesArray[]= 'edgtf-floating';
				
				if($params['floating_type'] == 'floating_outside'){
					
					$percentClassesArray[] = 'edgtf-floating-outside';
					
				}
				
				elseif($params['floating_type'] == 'floating_inside'){
					
					$percentClassesArray[] = 'edgtf-floating-inside';
				}

			}
			elseif($params['percentage_type'] == 'static'){
				
				$percentClassesArray[] = 'edgtf-static';
				
			}
		}
		return implode(' ', $percentClassesArray);
	}


	private function getActiveBarStyle($params){
		$activeBarStyle = array();
		if($params['active_color'] != ''){
			$activeBarStyle[] = 'background-color:'.$params['active_color'];
		}

		$background_image_src = '';

		if($params['background_pattern'] != ''){

			if (is_numeric($params['background_pattern'])) {
                $background_image_src = wp_get_attachment_url($params['background_pattern']);
            } else {
                $background_image_src = $params['background_pattern'];
            }
			$activeBarStyle[] = 'background-image:url('.$background_image_src.')';
			$activeBarStyle[] = 'background-repeat:repeat';
		}

		return $activeBarStyle;
	}


	private function getTitleStyle($params){
		
		$titleStyles = array();
		
		if($params['title_color'] != ''){
			$titleStyles[] = 'color:'.$params['title_color'];
		}

		return $titleStyles;

	}

	private function getBarStyle($params){

		$barStyles = '';		

		if($params['inactive_color'] != ''){
			$barStyles = 'background-color:'.$params['inactive_color'];
		}
		if($params['inactive_border_color'] != ''){
			$barStyles = 'border-color:'.$params['inactive_border_color'];
		}

		return $barStyles;

	}

	private function getPercentageStyle($params){

		$percentageStyles = array();
		
		if($params['percent_color'] != ''){
			$percentageStyles[] = 'color:'.$params['percent_color'];
		}

		return $percentageStyles;

	}
}