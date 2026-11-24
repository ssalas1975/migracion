<?php
namespace EdgefQuadricModules\ProcessHolder;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class ProcessHolder implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_process_holder';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name' => 'Process Holder',
			'base' => $this->base,
			'icon' => 'icon-wpb-process-holder extended-custom-icon',
			'category' => 'by EDGE',
			'content_element' => true,
			'as_parent' => array('only' => 'edgtf_process_holder_item'),
			'js_view' => 'VcColumnView',
			'params' => array(
				array(
					'type' => 'dropdown',
					'class' => '',
					'heading' => 'Columns',
					'admin_label' => true,
					'param_name' => 'number_of_columns',
					'value' => array(
						''	=> '',
						'3 Columns'     => 'three-columns',
						'4 Columns'     => 'four-columns',
						'5 Columns'     => 'five-columns',
					),
					'description' => 'Default value is 5 columns.'
				),				
			)
		));
	}

	public function render($atts, $content = null) {
	
		$args = array(
			'number_of_columns' 		=> 'five-columns',
		);
		$params = shortcode_atts($args, $atts);
		extract($params);

		$html = '';

		$process_holder_classes = array();
		$process_holder_classes[] = 'edgtf-process-holder';
		$process_holder_classes[] = 'edgtf-'.$number_of_columns;

		$process_holder_class = implode(' ', $process_holder_classes);

		$html .= '<div ' . edgtf_quadric_get_class_attribute($process_holder_class) . '>';
			$html .= do_shortcode($content);
		$html .= '</div>';

		return $html;

	}

}
