<?php
namespace AnyEdge\Modules\Shortcodes\Counter;

use AnyEdge\Modules\Shortcodes\Lib\ShortcodeInterface;
/**
 * Class Counter
 */
class Counter implements ShortcodeInterface {

	/**
	 * @var string
	 */
	private $base;

	public function __construct() {
		$this->base = 'edgtf_counter';

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
	 * @see edgt_core_get_carousel_slider_array_vc()
	 */
	public function vcMap() {

		vc_map( array(
			'name' => esc_html__('Counter', 'any'),
			'base' => $this->getBase(),
			'category' => 'by EDGE',
			'admin_enqueue_css' => array(any_edge_get_skin_uri().'/assets/css/edgtf-vc-extend.css'),
			'icon' => 'icon-wpb-counter extended-custom-icon',
			'allowed_container_element' => 'vc_row',
			'params' => array_merge(
                array(
					array(
						'type' => 'dropdown',
						'admin_label' => true,
						'heading' => 'Type',
						'param_name' => 'type',
						'value' => array(
							'Zero Counter' => 'zero',
							'Random Counter' => 'random'
						),
						'save_always' => true,
						'description' => ''
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Position',
						'param_name' => 'position',
						'value' => array(
							'Center' => 'center',
							'Left' => 'left',
							'Right' => 'right'
						),
						'save_always' => true,
						'description' => ''
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => 'Digit',
						'param_name' => 'digit',
						'description' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => 'Digit Font Size (px)',
						'param_name' => 'font_size',
						'description' => '',
						'group' => 'Design Options',
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => 'Digit Color',
						'param_name'  => 'digit_color',
						'admin_label' => true,
						'group'       => 'Design Options'
					),
					array(
						'type' => 'textfield',
						'heading' => 'Title',
						'param_name' => 'title',
						'admin_label' => true,
						'description' => ''
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => 'Title Color',
						'param_name'  => 'title_color',
						'admin_label' => true,
						'group'       => 'Design Options'
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
						'description' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => 'Text',
						'param_name' => 'text',
						'admin_label' => true,
						'description' => ''
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => 'Text Color',
						'param_name'  => 'text_color',
						'admin_label' => true,
						'group'       => 'Design Options'
					),
					array(
						'type' => 'textfield',
						'heading' => 'Padding Bottom(px)',
						'param_name' => 'padding_bottom',
						'description' => '',
						'group' => 'Design Options',
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Show Icon Above',
						'param_name' => 'show_icon_above',
						'value' => array(
							'No' => 'no',
							'Yes' => 'yes'
						)
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => 'Icon Color',
						'param_name'  => 'icon_color',
						'dependency'  => array('element' => 'show_icon_above', 'value' => array('yes')),
						'group'       => 'Design Options'
					),
					array(
						'type'        => 'textfield',
						'heading'     => 'Icon Size',
						'param_name'  => 'icon_size',
						'dependency'  => array('element' => 'show_icon_above', 'value' => array('yes')),
						'group'       => 'Design Options'
					)
				),
                any_edge_icon_collections()->getVCParamsArray(array('element' => 'show_icon_above', 'value' => array('yes')), '', true)
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
			'type' => '',
			'position' => '',
			'digit' => '',
			'digit_color' => '',
			'underline_digit' => '',
			'title' => '',
			'title_tag' => 'h5',
			'title_color' => '',
			'font_size' => '',
			'text' => '',
			'text_color' => '',
			'padding_bottom' => '',
			'show_icon_above' => 'no',
			'icon_color' => '',
			'icon_size' => ''
		);

        $args = array_merge($args, any_edge_icon_collections()->getShortcodeParams());
		$params = shortcode_atts($args, $atts);

		//get correct heading value. If provided heading isn't valid get the default one
		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');
		$params['title_tag'] = (in_array($params['title_tag'], $headings_array)) ? $params['title_tag'] : $args['title_tag'];

		$params['counter_holder_styles'] = $this->getCounterHolderStyle($params);
		$params['counter_styles'] = $this->getCounterStyle($params);
		$params['title_styles'] = $this->getTitleStyle($params);
		$params['text_styles'] = $this->getTextStyle($params);		
        $params['icon_parameters'] = $this->getIconParameters($params);

		//Get HTML from template
		$html = any_edge_get_shortcode_module_template_part('templates/counter-template', 'counter', '', $params);

		return $html;

	}

	/**
	 * Return Counter holder styles
	 *
	 * @param $params
	 * @return string
	 */
	private function getCounterHolderStyle($params) {
		$counterHolderStyle = array();

		if ($params['padding_bottom'] !== '') {

			$counterHolderStyle[] = 'padding-bottom: ' . $params['padding_bottom'] . 'px';

		}

		return implode(';', $counterHolderStyle);
	}

	/**
	 * Return Counter styles
	 *
	 * @param $params
	 * @return string
	 */
	private function getCounterStyle($params) {
		$counterStyle = array();

		if ($params['font_size'] !== '') {
			$counterStyle[] = 'font-size: ' . $params['font_size'] . 'px';
		}
		if ($params['digit_color'] !== '') {
			$counterStyle[] = 'color: ' . $params['digit_color'];
		}

		return implode(';', $counterStyle);
	}

	/**
	 * Return Text styles
	 *
	 * @param $params
	 * @return string
	 */
	private function getTextStyle($params) {
		$text_style = array();

		if ($params['text_color'] !== '') {
			$text_style[] = 'color: ' . $params['text_color'];
		}

		return implode(';', $text_style);
	}

	/**
	 * Return Title styles
	 *
	 * @param $params
	 * @return string
	 */
	private function getTitleStyle($params) {
		$title_style = array();

		if ($params['title_color'] !== '') {
			$title_style[] = 'color: ' . $params['title_color'];
		}

		return implode(';', $title_style);
	}

    /**
     * Returns parameters for icon shortcode as a string
     *
     * @param $params
     *
     * @return array
     */
    private function getIconParameters($params) {
        $params_array = array();

		if ($params['icon_pack'] !== '') {

			$iconPackName = any_edge_icon_collections()->getIconCollectionParamNameByKey($params['icon_pack']);

			$params_array['icon_pack']   = $params['icon_pack'];
			$params_array[$iconPackName] = $params[$iconPackName];

			if($params['icon_size'] !== '') {
				$params_array['custom_size'] = $params['icon_size'];
			}

			if($params['icon_color'] !== '') {
				$params_array['icon_color'] = $params['icon_color'];
			}
		}

        return $params_array;
    }
}