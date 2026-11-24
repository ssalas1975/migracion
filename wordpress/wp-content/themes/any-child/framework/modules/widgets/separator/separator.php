<?php

/**
 * Widget that adds separator boxes type
 *
 * Class Separator_Widget
 */
class AnyEdgeSeparatorWidget extends AnyEdgeWidget {
    /**
     * Set basic widget options and call parent class construct
     */
    public function __construct() {
        parent::__construct(
            'edgt_separator_widget', // Base ID
            esc_html__('Edge Separator Widget', 'any') // Name
        );

        $this->setParams();
    }

    /**
     * Sets widget options
     */
    protected function setParams() {
        $this->params = array(
            array(
                'type' => 'dropdown',
                'title' => esc_html__('Type', 'any'),
                'name' => 'type',
                'options' => array(
                    'normal' => esc_html__('Normal', 'any'),
                    'full-width' => esc_html__('Full Width', 'any')
                )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__('Position', 'any'),
                'name' => 'position',
                'options' => array(
                    'center' => esc_html__('Center', 'any'),
                    'left' => esc_html__('Left', 'any'),
                    'right' => esc_html__('Right', 'any')
                )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__('Style', 'any'),
                'name' => 'border_style',
                'options' => array(
                    'solid' => esc_html__('Solid', 'any'),
                    'dashed' => esc_html__('Dashed', 'any'),
                    'dotted' => esc_html__('Dotted', 'any')
                )
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__('Color', 'any'),
                'name' => 'color'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__('Width', 'any'),
                'name' => 'width',
                'description' => ''
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__('Thickness (px)', 'any'),
                'name' => 'thickness',
                'description' => ''
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__('Top Margin', 'any'),
                'name' => 'top_margin',
                'description' => ''
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__('Bottom Margin', 'any'),
                'name' => 'bottom_margin',
                'description' => ''
            )
        );
    }

    /**
     * Generates widget's HTML
     *
     * @param array $args args from widget area
     * @param array $instance widget's options
     */
    public function widget($args, $instance) {

        extract($args);

        //prepare variables
        $params = '';

        //is instance empty?
        if(is_array($instance) && count($instance)) {
            //generate shortcode params
            foreach($instance as $key => $value) {
                $params .= " $key='$value' ";
            }
        }

        echo '<div class="widget edgtf-separator-widget">';

        //finally call the shortcode
        echo do_shortcode("[edgtf_separator $params]"); // XSS OK

        echo '</div>'; //close div.edgtf-separator-widget
    }
}