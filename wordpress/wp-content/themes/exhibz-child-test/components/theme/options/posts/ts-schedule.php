<?php if (!defined('FW')) {
    die('Forbidden');
}
require_once(EXHIBZ_COMPONENTS . '/theme/options/inc/speaker-custom-quary.php');

$options = array(
    array(
        'type'  => 'box',
        'title' => esc_html__('Day Title', 'exhibz'),
        'options' => array(
            'schedule_day' => array('type' => 'text')
        )
    ),
    'exhibz_schedule_pop_up' =>
    array(
        'type'            => 'addable-popup',
        'value'           => array(
            array(
                'schedule_title' => '',
                'schedule_time'  => '',
            ),
        ),
        'label'           => esc_html__('All Schedule', 'exhibz'),
        'desc'            => esc_html__('Add your schedule', 'exhibz'),
        'template'        => '{{- schedule_title }} {{- schedule_time }}',
        'popup-title'     => 'Schedule',
        'size'            => 'small', // small, medium, large
        'limit'           => 0, // limit the number of popups that can be added
        'add-button-text' => esc_html__('Add', 'exhibz'),
        'sortable'        => true,
        'popup-options'   => array(
            'schedule_time' => array(
                'type'  => 'text',
                'value' => '8:00 - 9:00',
                'label' => esc_html__('Time', 'exhibz'),
            ),
            'duration' => array(
                'type'  => 'text',
                'value' => '',
                'label' => esc_html__('Duration', 'exhibz'),
            ),
            'day' => array(
                'type'  => 'text',
                'value' => '',
                'label' => esc_html__('Day', 'exhibz'),
            ),
            'speakers'      => array(
                'type'        => 'select',
                'label'       => esc_html__('Speakers', 'exhibz'),
                'choices'     => $speaker_list,
                'attr'        => array('class' => 'single-speaker-sw', 'data-foo' => 'single'),
                'no-validate' => false,
            ),
            'multi_speaker_choose' => array(
                'type'         => 'multi-picker',
                'label'        => false,
                'desc'         => false,
                'attr'         => array('class' => 'multi-speaker-sw', 'data-foo' => 'multi'),
                'picker'       => array(
                    'style' => array(
                        'type'  => 'switch',
                        'label' => esc_html__('Multi speaker ', 'exhibz'),
                        'value'        => 'no',
                        'left-choice'  => [
                            'value' => 'yes',
                            'label' => esc_html__('Yes', 'exhibz'),
                        ],
                        'right-choice' => [
                            'value' => 'no',
                            'label' => esc_html__('No', 'exhibz'),
                        ],
                    )
                ),
                'choices'      => array(
                    'yes' => array(
                        'multi_speakers' => array(
                            'type'        => 'multi-select',
                            'label'       => esc_html__('Multi Speakers', 'exhibz'),
                            'choices'     => $multi_speaker_list,
                            'desc'        => esc_html__('Use elementor style multi tab circle', 'exhibz'),
                            'no-validate' => false,
                            'limit'       => 10,
                        ),
                    ),
                ),
                'show_borders' => false,
            ),
            'position' => array(
                'type'    => 'select',
                'label'   => esc_html__('Position', 'exhibz'),
                'choices' => array(
                    'default' => esc_html__('Default', 'exhibz'),
                    'bottom'  => esc_html__('Below', 'exhibz'),
                ),
                'value' => 'default',
            ),
            'schedule_vertical' => array(
                'type' => 'multi-picker',
                'label' => esc_html__('Schedule Vertical', 'exhibz'),
                'picker' => array(
                    'type' => array(
                        'type' => 'select',
                        'label' => esc_html__('Type', 'exhibz'),
                        'choices' => array(
                            'text' => esc_html__('Text', 'exhibz'),
                            'image' => esc_html__('Image', 'exhibz'),
                        ),
                        'value' => 'text',
                    ),
                ),
                'choices' => array(
                    'text' => array(
                        'text_value' => array(
                            'type'  => 'text',
                            'label' => esc_html__('Text', 'exhibz'),
                        ),
                    ),
                    'image' => array(
                        'image_value' => array(
                            'type'  => 'upload',
                            'label' => esc_html__('Image', 'exhibz'),
                        ),
                    ),
                ),
            ),

            'background_color' => array(
                'type'  => 'color-picker',
                'label' => esc_html__('Background Color', 'exhibz'),
                'value' => '',
            ),
            'column_position' => array(
                'type'    => 'select',
                'label'   => esc_html__('Column Position', 'exhibz'),
                'choices' => array(
                    'normal'  => esc_html__('Normal', 'exhibz'),
                    'reverse' => esc_html__('Reverse', 'exhibz'),
                ),
                'value' => 'normal',
            ),
            'schedule_title' => array(
                'type'  => 'text',
                'value' => '',
                'label' => esc_html__('Schedule Title', 'exhibz'),
            ),
            'schedule_note'  => array(
                'type'  => 'wp-editor',
                'value' => '',
                'label' => esc_html__('Short Note', 'exhibz'),
            ),
        ),
    )
);
