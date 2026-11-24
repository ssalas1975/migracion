<?php
namespace EdgefQuadricModules\TeamSliderItem;

use EdgefQuadricModules\Shortcodes\Lib\ShortcodeInterface;

class TeamSliderItem implements ShortcodeInterface{
	private $base;

	function __construct() {
		$this->base = 'edgtf_team_slider_item';
		add_action('vc_before_init', array($this, 'vcMap'));
	}
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if(function_exists('vc_map')){

			$team_social_icons_array = array();
			for ($x = 1; $x<7; $x++) {
				$teamIconCollections = edgtf_quadric_icon_collections()->getCollectionsWithSocialIcons();
				foreach($teamIconCollections as $collection_key => $collection) {

					$team_social_icons_array[] = array(
						'type' => 'dropdown',
						'heading' => 'Social Icon '.$x,
						'param_name' => 'team_social_'.$collection->param.'_'.$x,
						'value' => $collection->getSocialIconsArrayVC(),
						'dependency' => Array('element' => 'team_social_icon_pack', 'value' => array($collection_key))
					);

				}

				$team_social_icons_array[] = array(
					'type' => 'textfield',
					'heading' => 'Social Icon '.$x.' Link',
					'param_name' => 'team_social_icon_'.$x.'_link',
					'dependency' => array('element' => 'team_social_icon_pack', 'value' => edgtf_quadric_icon_collections()->getIconCollectionsKeys())
				);

				$team_social_icons_array[] = array(
					'type' => 'dropdown',
					'heading' => 'Social Icon '.$x.' Target',
					'param_name' => 'team_social_icon_'.$x.'_target',
					'value' => array(
						'' => '',
						'Self' => '_self',
						'Blank' => '_blank'
					),
					'dependency' => Array('element' => 'team_social_icon_'.$x.'_link', 'not_empty' => true)
				);

			}
			vc_map( array(
				'name' => 'Team Slider Item',
				'base' => $this->getBase(),
				'as_child' => array('only' => 'edgtf_content_slider_holder'),
				'category' => 'by EDGE',
				'icon' => "icon-wpb-team-slider-item extended-custom-icon",
				'show_settings_on_create' => true,
				'params' => array_merge(
						array(							
							array(
								'type' => 'attach_image',
								'heading' => 'Image',
								'param_name' => 'team_image'
							),
							array(
								'type' => 'textfield',
								'heading' => 'Name',
								'admin_label' => true,
								'param_name' => 'team_name'
							),
							array(
								'type' => 'dropdown',
								'heading' => 'Name Tag',
								'param_name' => 'team_name_tag',
								'value' => array(
									''   => '',
									'h2' => 'h2',
									'h3' => 'h3',
									'h4' => 'h4',
									'h5' => 'h5',
									'h6' => 'h6',
								),
								'dependency' => array('element' => 'team_name', 'not_empty' => true)
							),
							array(
								'type' => 'textfield',
								'heading' => 'Position',
								'admin_label' => true,
								'param_name' => 'team_position'
							),
							array(
								'type' => 'textarea',
								'heading' => 'Description',
								'admin_label' => true,
								'param_name' => 'team_description'
							),
							array(
								'type' => 'dropdown',
								'heading' => 'Social Icon Pack',
								'param_name' => 'team_social_icon_pack',
								'admin_label' => true,
								'value' => array_merge(array('' => ''),edgtf_quadric_icon_collections()->getIconCollectionsVCExclude('linea_icons')),
								'save_always' => true
							),
							array(
								'type' => 'dropdown',
								'heading' => 'Social Icons Type',
								'param_name' => 'team_social_icon_type',
								'value' => array(
									'Normal' => 'normal',
									'Circle' => 'circle',
									'Square' => 'square'
								),
								'save_always' => true,
								'dependency' => array('element' => 'team_social_icon_pack', 'value' => edgtf_quadric_icon_collections()->getIconCollectionsKeys())
							),
						),
						$team_social_icons_array
					)
				)
			);
		}
	}

	public function render($atts, $content = null) {

		$args = array(
			'team_image' => '',
			'team_name' => '',
			'team_name_tag' => 'h4',
			'team_position' => '',
			'team_description' => '',
			'team_social_icon_pack' => '',
			'team_social_icon_type' => 'normal_social'
		);

		$team_social_icons_form_fields = array();
		$number_of_social_icons = 6;

		for ($x = 1; $x <= $number_of_social_icons; $x++) {

			foreach (edgtf_quadric_icon_collections()->iconCollections as $collection_key => $collection) {
				$team_social_icons_form_fields['team_social_' . $collection->param . '_' . $x] = '';
			}

			$team_social_icons_form_fields['team_social_icon_'.$x.'_link'] = '';
			$team_social_icons_form_fields['team_social_icon_'.$x.'_target'] = '';

		}

		$args = array_merge($args, $team_social_icons_form_fields);

		$params = shortcode_atts($args, $atts);

		$params['number_of_social_icons'] = $number_of_social_icons;
		$params['team_name_tag'] = $this->getTeamNameTag($params, $args);
		$params['team_image_src'] = $this->getTeamImage($params);
		$params['team_social_icons'] = $this->getTeamSocialIcons($params);

		$html = edgtf_quadric_get_shortcode_module_template_part('templates/team-slider-item-template', 'team-slider', '', $params);

		return $html;
	}


	/**
	 * Return correct heading value. If provided heading isn't valid get the default one
	 *
	 * @param $params
	 * @return mixed
	 */
	private function getTeamNameTag($params, $args) {

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');
		return (in_array($params['team_name_tag'], $headings_array)) ? $params['team_name_tag'] : $args['team_name_tag'];

	}

	/**
	 * Return Team image
	 *
	 * @param $params
	 * @return false|string
	 */
	private function getTeamImage($params) {

		if (is_numeric($params['team_image'])) {
			$team_image_src = wp_get_attachment_url($params['team_image']);
		} else {
			$team_image_src = $params['team_image'];
		}

		return $team_image_src;

	}

	private function getTeamSocialIcons($params) {

		extract($params);
		$social_icons = array();

		if ($team_social_icon_pack !== '') {

			$icon_pack = edgtf_quadric_icon_collections()->getIconCollection($team_social_icon_pack);
			$team_social_icon_type_label = 'team_social_' . $icon_pack->param;
			$team_social_icon_param_label = $icon_pack->param;

			for ( $i = 1; $i <= $number_of_social_icons; $i++ ) {

				$team_social_icon = ${$team_social_icon_type_label . '_' . $i};
				$team_social_link = ${'team_social_icon_' . $i . '_link'};
				$team_social_target = ${'team_social_icon_' . $i . '_target'};

				if ($team_social_icon !== '') {

					$team_icon_params = array();
					$team_icon_params['icon_pack'] = $team_social_icon_pack;
					$team_icon_params[$team_social_icon_param_label] =   $team_social_icon;
					$team_icon_params['link'] = ($team_social_link !== '') ? $team_social_link : '';
					$team_icon_params['target'] = ($team_social_target !== '') ? $team_social_target : '';
					$team_icon_params['type'] = ($team_social_icon_type !== '') ? $team_social_icon_type : '';

					$social_icons[] = edgtf_quadric_execute_shortcode('edgtf_icon', $team_icon_params);
				}

			}

		}

		return $social_icons;

	}	
}