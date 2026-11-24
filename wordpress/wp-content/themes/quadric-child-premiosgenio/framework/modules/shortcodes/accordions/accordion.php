<?php
namespace EdgefQuadricModules\Accordion;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;
/**
	* class Accordions
*/
class Accordion implements ShortcodeInterface{
	/**
	 * @var string
	 */
	private $base;

	function __construct() {
		$this->base = 'edgtf_accordion';
		add_action('vc_before_init', array($this, 'vcMap'));
	}

	public function getBase() {
		return	$this->base;
	}

	public function vcMap() {

		vc_map( array(
			'name' =>  esc_html__( 'Accordion', 'quadric' ),
			'base' => $this->base,
			'as_parent' => array('only' => 'edgtf_accordion_tab'),
			'content_element' => true,
			'category' => 'by EDGE',
			'icon' => 'icon-wpb-accordion extended-custom-icon',
			'show_settings_on_create' => true,
			'js_view' => 'VcColumnView',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'quadric' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'quadric' )
				),
				array(
					'type' => 'dropdown',
					'class' => '',
					'heading' => 'Style',
					'param_name' => 'style',
					'value' => array(
						'Accordion'   				=> 'accordion',
						'Accordion With Boxed Mark' => 'boxed_accordion',
						'Toggle'     				=> 'toggle',
						'Toggle With Boxed Mark'    => 'boxed_toggle'
					),
					'save_always' => true,
					'description' => ''
				),
				array(
					'type' => 'dropdown',
					'heading' => 'Predefined Color Style',
					'param_name' => 'predefined_color_style',
					'value' => array(
						'Default' => '',
						'White' => 'white'
					),
					'description' => '',
					'dependency' => array('element'=>'style', 'value'=> array('accordion', 'toggle'))
				)
			)
		) );
	}
	public function render($atts, $content = null) {
		$default_atts=(array(
			'title' => '',
			'style' => 'accordion',
			'predefined_color_style' => ''
		));
		$params = shortcode_atts($default_atts, $atts);
		extract($params);
		
 		$acc_class = $this->getAccordionClasses($params);
		$params['acc_class'] = $acc_class;
		$params['content'] = $content;
		
		$output = '';
		
		$output .= edgtf_quadric_get_shortcode_module_template_part('templates/accordion-holder-template','accordions', '', $params);

		return $output;
	}

	/**
	   * Generates accordion classes
	   *
	   * @param $params
	   *
	   * @return string
	*/
	private function getAccordionClasses($params){
		
		$acc_class = array();
		$style = $params['style'];
		switch($style) {
			case 'toggle':
				$acc_class[] = 'edgtf-toggle edgtf-initial';
				break;
			case 'boxed_toggle':
				$acc_class[] = 'edgtf-toggle edgtf-boxed';
				break;
			case 'boxed_accordion':
				$acc_class[] = 'edgtf-accordion edgtf-boxed';
				break;			
			default:
				$acc_class[] = 'edgtf-accordion edgtf-initial';
		}

		if($params['predefined_color_style'] != ''){
			$acc_class[] = 'edgtf-accordion-predefined-style-'. $params['predefined_color_style'];
		}

		return implode(' ', $acc_class);
	}
}
