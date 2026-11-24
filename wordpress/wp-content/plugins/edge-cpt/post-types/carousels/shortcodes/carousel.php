<?php
namespace EdgeCore\CPT\Carousels\Shortcodes;

use EdgeCore\Lib;

/**
 * Class Carousel
 * @package EdgeCore\CPT\Carousels\Shortcodes
 */
class Carousel implements Lib\ShortcodeInterface {
    /**
     * @var string
     */
    private $base;

    public function __construct() {
        $this->base = 'edgtf_carousel';

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
            'name' => 'Edge Carousel',
            'base' => $this->base,
            'category' => 'by EDGE',
            'icon' => 'icon-wpb-carousel-slider extended-custom-icon',
            'allowed_container_element' => 'vc_row',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => 'Carousel Slider',
                    'param_name' => 'carousel',
                    'value' => edgtf_core_get_carousel_slider_array_vc(),
                    'description' => '',
                    'admin_label' => true
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => 'Order By',
                    'param_name' => 'orderby',
                    'value' => array(
                        '' => '',
                        'Title' => 'title',
                        'Date' => 'date'
                    ),
                    'description' => ''
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => 'Order',
                    'param_name' => 'order',
                    'value' => array(
                        '' => '',
                        'ASC' => 'ASC',
                        'DESC' => 'DESC',
                    ),
                    'description' => ''
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => 'Number of items showing',
                    'param_name' => 'number_of_items',
                    'value' => array(
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7'
                    ),
                    'save_always' => true,
                    'admin_label' => true,
                    'description' => ''
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => 'Image Animation',
                    'param_name' => 'image_animation',
                    'value' => array(
						'None' => '',
						'Images Roll Over' => 'roll-over',
						'Images Fade' => 'fade'
                    ),
                    'admin_label' => true,
                    'description' => 'Note: Only on "Image Change" zoom image will be used'
                ),
				array(
					'type' => 'dropdown',
					'heading' => 'Show title',
					'param_name' => 'show_title',
					'value' => array(
						'No' => 'no',
						'Yes' => 'yes',

					),
					'save_always' => true,
					'admin_label' => true,
					'description' => ''
				),
                array(
                    'type' => 'dropdown',
                    'heading' => 'Show navigation?',
                    'param_name' => 'show_navigation',
                    'value' => array(
						'No' => 'no',
                        'Yes' => 'yes',

                    ),
                    'save_always' => true,
                    'admin_label' => true,
                    'description' => ''
                ),
				array(
					'type' => 'dropdown',
					'heading' => 'Show bullets navigation?',
					'param_name' => 'show_bullets_navigation',
					'value' => array(
						'No' => 'no',
						'Yes' => 'yes',

					),
					'save_always' => true,
					'admin_label' => true,
					'description' => ''
				)
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
            'carousel' => '',
            'orderby' => 'date',
            'order' => 'ASC',
            'number_of_items' => '5',
            'image_animation' => '',
            'show_title' => 'no',
            'show_navigation' => '',
            'show_bullets_navigation' => 'no'
        );

        $params = shortcode_atts($args, $atts);
		$params['carousel_data_attributes'] = $this->getCarouselDataAttributes($params);

		extract($params);

        $html = '';

        if ($carousel !== '') {

            $html .= '<div class="edgtf-carousel-holder clearfix">';
            $html .= '<div class="edgtf-carousel" ' .  edgtf_quadric_get_inline_attrs($carousel_data_attributes) . '>';

            $args = array(
                'post_type' => 'carousels',
                'carousels_category' => $params['carousel'],
                'orderby' => $params['orderby'],
                'order' => $params['order'],
                'posts_per_page' => '-1'
            );

            $query = new \WP_Query($args);

            if ($query->have_posts()) {
                while($query->have_posts()) {
                    $query->the_post();
                    $carousel_item = $this->getCarouselItemData(get_the_ID(), $params);
                    $html .= edgtf_core_get_shortcode_module_template_part('carousels', 'carousel-template', '', $carousel_item);
                }
            }

            wp_reset_postdata();


            $html .= '</div>';
            $html .= '</div>';

        }

        return $html;
    }

    /**
     * Return all data that carousel needs, images, titles, links, etc
     *
     * @param $params
     * @return array
     */
    private function getCarouselItemData($item_id, $params) {

        $carousel_item = array();

        if (get_post_meta($item_id, 'edgtf_carousel_image', true) !== '') {
            $carousel_item['image'] = get_post_meta($item_id, 'edgtf_carousel_image', true);
        } else {
            $carousel_item['image'] = '';
        }

		if ($params['image_animation'] != '' && get_post_meta($item_id, 'edgtf_carousel_hover_image', true) !== '') {
            $carousel_item['hover_image'] = get_post_meta($item_id, 'edgtf_carousel_hover_image', true);
        } else {
            $carousel_item['hover_image'] = '';
        }


        if (get_post_meta($item_id, 'edgtf_carousel_item_link', true) != '') {
            $carousel_item['link'] = get_post_meta($item_id, 'edgtf_carousel_item_link', true);
        } else {
            $carousel_item['link'] = '';
        }

        if (get_post_meta($item_id, 'edgtf_carousel_item_target', true) != '') {
            $carousel_item['target'] = get_post_meta($item_id, 'edgtf_carousel_item_target', true);
        } else {
            $carousel_item['target'] = '_self';
        }

        $carousel_item['title'] = get_the_title();

		$carousel_item['class'] = '';
		if($params['image_animation'] !== '') {
			$carousel_item['class'] = 'edgtf-carousel-' . $params['image_animation'];
		}

		if($params['show_title'] !== '') {
			$carousel_item['show_title'] = $params['show_title'];
		}

        $carousel_item['carousel_classes'] = $carousel_item['class'];

        return $carousel_item;

    }


	/**
	 * Return data attributes for carousel
	 *
	 * @param $params
	 * @return array
	 */
	private function getCarouselDataAttributes($params) {

		$carousel_data = array();

		if ($params['number_of_items'] !== '') {
			$carousel_data['data-items'] = $params['number_of_items'];
		}
		if ($params['show_navigation'] !== '') {
			$carousel_data['data-navigation'] = $params['show_navigation'];
		}
		if ($params['show_bullets_navigation'] !== '') {
			$carousel_data['data-bullets-navigation'] = $params['show_bullets_navigation'];
		}

		return $carousel_data;

	}

}