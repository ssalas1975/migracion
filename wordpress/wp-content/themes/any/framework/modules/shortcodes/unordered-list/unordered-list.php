<?php
namespace AnyEdge\Modules\Shortcodes\UnorderedList;

use AnyEdge\Modules\Shortcodes\Lib\ShortcodeInterface;

/**
 * Class unordered List
 */
class UnorderedList implements ShortcodeInterface{

	private $base;

	function __construct() {
		$this->base='edgtf_unordered_list';
		
		add_action('vc_before_init', array($this, 'vcMap'));
	}

	/**\
	 * Returns base for shortcode
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	public function vcMap() {

		vc_map( array(
			'name' => esc_html__('List - Unordered', 'any'),
			'base' => $this->base,
			'icon' => 'icon-wpb-unordered-list extended-custom-icon',
			'category' => 'by EDGE',
			'allowed_container_element' => 'vc_row',
			'params' => array(
				array(
					'type'			=> 'dropdown',
					'admin_label'	=> true,
					'heading'		=> 'Style',
					'param_name'	=> 'style',
					'value'			=> array(
						'Circle' => 'circle',
						'Line'	 => 'line',
						'Arrow'  => 'arrow'
					)
				),
				array(
					'type'			=> 'dropdown',
					'admin_label'	=> true,
					'heading'		=> 'Animate List',
					'param_name'	=> 'animate',
					'value'			=> array(
						'No' => 'no',
						'Yes' => 'yes'
					)
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> 'Hover Effect',
					'param_name'	=> 'hover',
					'value'			=> array(
						'No' => 'no',
						'Yes' => 'yes'
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => 'Font Weight',
					'param_name' => 'font_weight',
					'value' => array(
						'Default' => '',
						'Light' => 'light',
						'Normal' => 'normal',
						'Bold' => 'bold'
					),
					'description' => ''
				),
				array(
					'type' => 'textfield',
					'heading' => 'Padding left (px)',
					'param_name' => 'padding_left',
					'value' => ''
				),
				array(
					'type' => 'textarea_html',
					'heading' => 'Content',
					'param_name' => 'content',
					'value' => '<ul><li>Lorem Ipsum</li><li>Lorem Ipsum</li><li>Lorem Ipsum</li></ul>',
					'description' => ''
				)
			)
		) );
	}

	public function render($atts, $content = null) {
		$args = array(
            'style' => '',
            'animate' => '',
            'hover' => '',
            'font_weight' => '',
            'padding_left' => ''
        );
		$params = shortcode_atts($args, $atts);
		
		//Extract params for use in method
		extract($params);
		
		$list_item_classes = "";

        if ($style != '') {
			$list_item_classes .= ' edgtf-'.$style;         
        }

		if ($animate == 'yes') {
			$list_item_classes .= ' edgtf-animate-list';
		}

		if ($hover == 'yes') {
			$list_item_classes .= ' edgtf-hover-list';
		}
		
		$list_style = '';
		if($padding_left != '') {
			$list_style .= 'padding-left: ' . $padding_left .'px;';
		}
		$content = preg_replace('#^<\/p>|<p>$#', '', $content);
        $html = '<div class="edgtf-unordered-list '.$list_item_classes.'" '.  any_edge_get_inline_style($list_style).'>'.do_shortcode($content).'</div>';
        return $html;
	}
}