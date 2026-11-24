<?php
namespace EdgefQuadricModules\TeamSliderHolder;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class TeamSliderHolder implements ShortcodeInterface{
	private $base;
	function __construct() {
		$this->base = 'edgtf_team_slider_holder';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name' => 'Team Slider Holder',
			'base' => $this->getBase(),
			'icon' => 'icon-wpb-team-slider-holder extended-custom-icon',
			'category' => 'by EDGE',
			'as_parent' => array('only' => 'edgtf_team_slider_item'),
			'content_element' => true,
			'show_settings_on_create' => false,
			'params' => array(),
			'js_view' => 'VcColumnView'
		));
	}

	public function render($atts, $content = null) {

		$params['content'] = $content;

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/team-slider-holder-template', 'team-slider', '', $params);

		return $html;

	}
}