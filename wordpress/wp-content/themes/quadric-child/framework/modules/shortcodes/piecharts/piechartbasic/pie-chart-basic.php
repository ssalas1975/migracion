<?php
namespace EdgefQuadricModules\PieCharts\PieChartBasic;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;
/**
 * Class PieChartBasic
 */
class PieChartBasic implements ShortcodeInterface {

	/**
	 * @var string
	 */
	private $base;

	public function __construct() {
		$this->base = 'edgtf_pie_chart';

		add_action('vc_before_init', array($this, 'vcMap'));
	}

	/**
	 * Returns base for shortcode
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	/**
	 * Maps shortcode to Visual Composer. Hooked on vc_before_init
	 *
	 * @see edgtf_core_get_carousel_slider_array_vc()
	 */
	public function vcMap() {

		vc_map( array(
			'name' => 'Pie Chart',
			'base' => $this->getBase(),
			'icon' => 'icon-wpb-pie-chart extended-custom-icon',
			'category' => 'by EDGE',
			'allowed_container_element' => 'vc_row',
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => 'Type of Central text',
					'param_name' => 'type_of_central_text',
					'value' => array(
						'Percent'  => 'percent',
						'Title' => 'title'
					),
					'save_always' => true,
					'admin_label' => true
				),
				array(
					'type' => 'textfield',
					'heading' => 'Size(px)',
					'param_name' => 'size',
					'description' => '',
					'admin_label' => true,
					'group' => 'Design Options',
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Percentage Color',
					'param_name' => 'percentage_color',
					'description' => '',
					'group' => 'Design Options',
				),				
				array(
					'type' => 'colorpicker',
					'heading' => 'Bar Active Color',
					'param_name' => 'bar_color',
					'description' => '',
					'group' => 'Design Options',
				),				
				array(
					'type' => 'colorpicker',
					'heading' => 'Bar Inactive Color',
					'param_name' => 'track_color',
					'description' => '',
					'group' => 'Design Options',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Percentage',
					'param_name' => 'percent',
					'description' => '',
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => 'Title',
					'param_name' => 'title',
					'description' => '',
					'admin_label' => true
				),
				array(
					'type' => 'dropdown',
					'heading' => 'Title Tag',
					'param_name' => 'title_tag',
					'value' => array(
						''   => '',
						'h2' => 'h2',
						'h3' => 'h3',
						'h4' => 'h4',
						'h5' => 'h5',
						'h6' => 'h6',
					),
					'description' => '',
					'dependency' => array('element' => 'title', 'not_empty' => true)
				),
				array(
					'type' => 'textfield',
					'heading' => 'Title Font Size (px)',
					'param_name' => 'title_font_size',
					'value' => array(),
					'description' => '',
					'dependency' => array('element' => 'title', 'not_empty' => true),
					'group' => 'Design Options',
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Title Color',
					'param_name' => 'title_color',
					'value' => array(),
					'description' => '',
					'dependency' => array('element' => 'title', 'not_empty' => true),
					'group' => 'Design Options',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Text',
					'param_name' => 'text',
					'description' => '',
					'admin_label' => true
				),
				array(
					'type' => 'textfield',
					'heading' => 'Text Font Size (px)',
					'param_name' => 'text_font_size',
					'value' => array(),
					'description' => '',
					'dependency' => array('element' => 'text', 'not_empty' => true),
					'group' => 'Design Options',
				),
				array(
					'type' => 'colorpicker',
					'heading' => 'Text Color',
					'param_name' => 'text_color',
					'value' => array(),
					'description' => '',
					'dependency' => array('element' => 'text', 'not_empty' => true),
					'group' => 'Design Options',
				),
				array(
					'type' => 'textfield',
					'heading' => 'Margin below chart (px)',
					'param_name' => 'margin_below_chart',
					'description' => '',
					'group' => 'Design Options',
				),
			)
		) );

	}

	/**
	 * Renders shortcodes HTML
	 *
	 * @param $atts array of shortcode params
	 * @param $content string shortcode content
	 * @return string
	 */
	public function render($atts, $content = null) {

		$args = array(
			'size' => '',
			'type_of_central_text' => 'percent',
			'title' => '',
			'percentage_color' => '',
			'track_color' => '',
			'bar_color' => '',
			'title_color' => '',
			'title_font_size' => '',
			'title_tag' => 'h4',
			'percent' => '',
			'text' => '',
			'text_color' => '',
			'text_font_size' => '',
			'margin_below_chart' => ''
		);

		$params = shortcode_atts($args, $atts);

		$params['title_tag'] = $this->getValidTitleTag($params, $args);
		$params['title_styles'] = $this->getTitleStyles($params);
		$params['text_styles'] = $this->getTextStyles($params);
		$params['pie_chart_data'] = $this->getPieChartData($params);
		$params['pie_chart_style'] = $this->getPieChartStyle($params);
		$params['pie_chart_percentage_style'] = $this->getPieChartPercentageStyle($params);
		$params['pie_chart_canvas_style'] = $this->getPieChartCanvasStyle($params);

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/pie-chart-basic', 'piecharts/piechartbasic', '', $params);

		return $html;


	}

	/**
	 * Return correct heading value. If provided heading isn't valid get the default one
	 *
	 * @param $params
	 * @param $args
	 */
	private function getValidTitleTag($params, $args) {

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');
		return (in_array($params['title_tag'], $headings_array)) ? $params['title_tag'] : $args['title_tag'];

	}

	/**
	 * Return title styles such as color and font size
	 *
	 * @param $params
	 * 
	 */
	private function getTextStyles($params) {

		$text_styles = array();

		if($params['text_color'] != ''){
			$text_styles[] = 'color:'.$params['text_color'];
		}

		if($params['text_font_size'] != ''){
			$text_styles[] = 'font-size:'.$params['text_font_size'].'px';
		}

		return $text_styles;

	}

	/**
	 * Return text styles such as color and font size
	 *
	 * @param $params
	 * 
	 */
	private function getTitleStyles($params) {

		$title_styles = array();

		if($params['title_color'] != ''){
			$title_styles[] = 'color:'.$params['title_color'];
		}

		if($params['title_font_size'] != ''){
			$title_styles[] = 'font-size:'.$params['title_font_size'].'px';
		}

		return $title_styles;

	}

	/**
	 * Return data attributes for Pie Chart
	 *
	 * @param $params
	 * @return array
	 */
	private function getPieChartData($params) {

		$pieChartData = array();		

		if( $params['size'] !== '' ) {
			$pieChartData['data-size'] = $params['size'];

		}
		if( $params['percent'] !== '' ) {
			$pieChartData['data-percent'] = $params['percent'];
		}
		if( $params['bar_color'] !== '' ) {
			$pieChartData['data-bar-color'] = $params['bar_color'];
		}
		if( $params['track_color'] !== '' ) {
			$pieChartData['data-track-color'] = $params['track_color'];
		}		

		return $pieChartData;

	}

	private function getPieChartCanvasStyle($params) {

		$PieChartCanvasStyle = array();		

		if( $params['size'] !== '' ) {
			$PieChartCanvasStyle[] = 'height: ' . $params['size'] . 'px';
			$PieChartCanvasStyle[] = 'line-height: ' . $params['size'] . 'px';
			$PieChartCanvasStyle[] = 'width: ' . $params['size'] . 'px';
		}		

		return $PieChartCanvasStyle;

	}

	private function getPieChartStyle($params) {

		$pieChartStyle = array();

		if ($params['margin_below_chart'] !== '') {
			$pieChartStyle[] = 'margin-top: ' . $params['margin_below_chart'] . 'px';
		}

		return $pieChartStyle;

	}

	private function getPieChartPercentageStyle($params) {

		$pieChartStyle = array();

		if ($params['percentage_color'] !== '') {
			$pieChartStyle[] = 'color: ' . $params['percentage_color'];
		}

		return $pieChartStyle;

	}

}