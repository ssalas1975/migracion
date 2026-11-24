<?php 

namespace EdgefQuadricModules\AccordionTab;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;
/**
	* class Accordions
*/
class AccordionTab implements ShortcodeInterface{
	/**
	 * @var string
	 */
	private $base;
	function __construct() {
		$this->base = 'edgtf_accordion_tab';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){


			vc_map( array(
				"name" =>  esc_html__( 'Accordion Tab', 'quadric' ),
				"base" => $this->base,
				"as_parent" => array('except' => 'vc_row'),
				"as_child" => array('only' => 'edgtf_accordion'),
				'is_container' => true,
				"category" => 'by EDGE',
				"icon" => "icon-wpb-accordion-section extended-custom-icon",
				"show_settings_on_create" => true,
				"js_view" => 'VcColumnView',
				"params" => array_merge(array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'quadric' ),
						'param_name' => 'title',
						'admin_label' => true,
						'value' => esc_html__( 'Section', 'quadric' ),
						'description' => esc_html__( 'Enter accordion section title.', 'quadric' )
					),
					array(
						'type' => 'el_id',
						'heading' => esc_html__( 'Section ID', 'quadric' ),
						'param_name' => 'el_id',
						'description' => sprintf( esc_html__( 'Enter optional row ID. Make sure it is unique, and it is valid as w3c specification: %s (Must not have spaces)', 'quadric' ), '<a target="_blank" href="http://www.w3schools.com/tags/att_global_id.asp">' . esc_html__( 'link', 'quadric' ) . '</a>' ),
					),
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => "Title Tag",
						"param_name" => "title_tag",
						"value" => array(
							""   => "",
							"p"  => "p",
							"h2" => "h2",
							"h3" => "h3",
							"h4" => "h4",
							"h5" => "h5",
							"h6" => "h6",
						),
						"description" => ""
					)
					),
					edgtf_quadric_icon_collections()->getVCParamsArray(array(),'',true , 'This is only for "Accordion" & "Toggle" type')
				)
				)

			);
		}
	}	
	public function render($atts, $content = null) {

		$default_atts = (array(
			'title'	=> "Accordion Title",
			'title_tag' => 'h3',
			'el_id' => ''
		));

		$default_atts = array_merge($default_atts, edgtf_quadric_icon_collections()->getShortcodeParams());

		$params = shortcode_atts($default_atts, $atts);
		extract($params);
		$params['icon'] = '';
		$params['accordion_class'] = '';
		if($params['icon_pack'] !== '') {
			$iconPackName = edgtf_quadric_icon_collections()->getIconCollectionParamNameByKey($params['icon_pack']);
			$params['icon'] = $params[$iconPackName];
			if($params['icon'] != ''){
				$params['accordion_class'] = 'edgtf-accordion-with-icon';
			}
		}

		$params['content'] = $content;



		$output = '';

		$output .= edgtf_quadric_get_shortcode_module_template_part('templates/accordion-template','accordions', '',$params);

		return $output;
		
	}

}