<?php
namespace Elementor;

use \Elementor\ElementsKit_Widget_Lottie_Handler as Handler;
use \ElementsKit_Lite\Modules\Controls\Controls_Manager as ElementsKit_Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;


class ElementsKit_Widget_Lottie extends Widget_Base {
    use \ElementsKit_Lite\Widgets\Widget_Notice;

    public $base;

    public function get_script_depends() {
        return ['lottie', 'lottie-init'];
    }

    public function get_name() {
        return Handler::get_name();
    }

    public function get_title() {
        return Handler::get_title();
    }

    public function get_icon() {
        return Handler::get_icon();
    }

    public function get_categories() {
        return Handler::get_categories();
    }

    public function get_keywords() {
        return Handler::get_keywords();
    }

    public function get_help_url() {
        return 'https://wpmet.com/doc/lottie-animation/';
    }
    protected function is_dynamic_content(): bool {
        return false;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'ekit_lottie',
            [
                'label' => esc_html__( 'Lottie', 'elementskit-lite' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'ekit_lottie_type',
                [
                    'label'         => esc_html__( 'Select JSON', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::CHOOSE,
                    'default'       => 'file',
                    'options'       => $this->get_source_options(),
                ]
            );

            $this->add_control(
                'ekit_lottie_json',
                [
                    'show_label'    => false,
                    'description'   => sprintf('%1$s <a href="https://lottiefiles.com/featured" target="_blank"> %2$s</a> %3$s',
						esc_html__('Discover thousands of' , 'elementskit-lite'),
						esc_html__('Lottie animations' , 'elementskit-lite'),
						esc_html__('ready to use.' , 'elementskit-lite'),
                    ),
                    'type'          => \Elementor\Controls_Manager::MEDIA,
                    'media_type'    => 'application/json',
                    'condition'    => [
                        'ekit_lottie_type'  => 'file',
                    ],
                ]
            );

            $this->add_control(
                'ekit_lottie_url',
                [
                    'show_label'    => false,
                    'label_block'   => true,
                    'description'   => sprintf('%1$s <a href="https://lottiefiles.com/featured" target="_blank"> %2$s</a> %3$s',
						esc_html__('Discover thousands of' , 'elementskit-lite'),
						esc_html__('Lottie animations' , 'elementskit-lite'),
						esc_html__('ready to use.' , 'elementskit-lite'),
					),
                    // Same as Elementor Pro: only users who can publish pages may set a JSON URL.
                    'type'          => $this->current_user_can_use_external_source() ? \Elementor\Controls_Manager::TEXT : \Elementor\Controls_Manager::HIDDEN,
					'dynamic'       => [
						'active' => true,
					],
                    'placeholder'   => esc_html( 'https://example.com/file.json'),
                    'show_external' => false,
                    'condition'     => [
                        'ekit_lottie_type'  => 'url'
                    ],
                ]
            );

            // Media URLs follow the WordPress Address. When it is http:// and the site is
            // opened over https://, browsers block the JSON (mixed content) and nothing shows.
            // No condition: the notice sits under both the file picker and the URL field.
            if ( 'https' !== wp_parse_url( site_url(), PHP_URL_SCHEME ) ) {
                $this->add_control(
                    'ekit_lottie_https_notice',
                    [
                        'type'            => \Elementor\Controls_Manager::RAW_HTML,
                        'raw'             => sprintf(
                            '<strong>%1$s</strong> %2$s',
                            esc_html__( 'HTTPS required:', 'elementskit-lite' ),
                            esc_html__( 'Your site URL starts with http://. When the page is opened over https://, the browser blocks http:// JSON files, both uploaded files and JSON URLs, and the animation will not show. Change the WordPress Address and Site Address to https:// in Settings → General, and use https:// JSON URLs.', 'elementskit-lite' )
                        ),
                        'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                        'render_type'     => 'ui',
                    ]
                );
            }

            $this->add_control(
                'ekit_lottie_link_check',
                [
                    'label'         => esc_html__( 'Link', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'ekit_lottie_link',
                [
                    'show_label'    => false,
                    'type'          => \Elementor\Controls_Manager::URL,
					'dynamic'       => [
						'active' => true,
					],
                    'condition'     => [
                        'ekit_lottie_link_check'    => 'yes'
                    ],
                ]
            );

            $this->add_control(
                'ekit_lottie_options',
                [
                    'label'         => esc_html__( 'Animation Options', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::HEADING,
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'ekit_lottie_reverse',
                [
                    'label'         => esc_html__( 'Reverse', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'ekit_lottie_autoplay',
                [
                    'label'         => esc_html__( 'Autoplay', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SWITCHER,
                    'return_value'  => 'true',
                    'default'       => 'true',
                ]
            );

            $this->add_control(
                'ekit_lottie_on_scroll',
                [
                    'label'         => esc_html__( 'Start when visible', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SWITCHER,
                    'condition'     => [
                        'ekit_lottie_autoplay'  => ''
                    ],
                ]
            );

            $this->add_control(
                'ekit_lottie_loop',
                [
                    'label'         => esc_html__( 'Loop', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SWITCHER,
                    'return_value'  => 'true',
                    'default'       => 'true',
                ]
            );

            $this->add_control(
                'ekit_lottie_loop_count',
                [
                    'label'         => esc_html__( 'Loop Count', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SLIDER,
                    'range'         => [
                        'px'    => [
                            'max'   => 10,
                        ]
                    ],
                    'condition'     => [
                        'ekit_lottie_loop'  => 'true'
                    ],
                ]
            );

            $this->add_control(
                'ekit_lottie_speed',
                [
                    'label'         => esc_html__( 'Speed', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SLIDER,
                    'range'         => [
                        'px'    => [
                            'max'   => 10,
                            'step'  => 0.2,
                        ]
                    ],
                    'default'       => [
                        'size'  => 1,
                    ],
                ]
            );

            $this->add_control(
                'ekit_lottie_renderer',
                [
                    'label'         => esc_html__( 'Render Type', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::CHOOSE,
                    'default'       => 'svg',
                    'options'       => [
                        'svg'           => [
                            'title' => esc_html__( 'SVG', 'elementskit-lite' ),
                            'icon'  => 'fa fa-magic',
                        ],
                        'canvas'        => [
                            'title' => esc_html__( 'Canvas', 'elementskit-lite' ),
                            'icon'  => 'fa fa-chalkboard',
                        ],
                    ],
                ]
            );

            $this->add_control(
                'ekit_lottie_action',
                [
                    'label'         => esc_html__( 'On Hover', 'elementskit-lite' ),
                    'type'          => \Elementor\Controls_Manager::SELECT,
                    'options'       => [
                        ''          => esc_html__( 'None', 'elementskit-lite' ),
                        'play'      => esc_html__( 'Play', 'elementskit-lite' ),
                        'pause'     => esc_html__( 'Pause', 'elementskit-lite' ),
                        'reverse'   => esc_html__( 'Reverse', 'elementskit-lite' ),
                    ],
                ]
            );
        $this->end_controls_section();

        $this->start_controls_section(
            'ekit_lottie_styles',
            [
                'label' => esc_html__( 'Lottie', 'elementskit-lite' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs(
                'ekit_lottie_state'
            );
                $this->start_controls_tab(
                    'ekit_lottie_normal',
                    [
                        'label' => esc_html__( 'Normal', 'elementskit-lite' ),
                    ]
                );
                    $this->add_control(
                        'ekit_lottie_opacity',
                        [
                            'label'         => esc_html__( 'Opacity', 'elementskit-lite' ),
                            'type'          => \Elementor\Controls_Manager::SLIDER,
                            'range'         => [
                                'px'    => [
                                    'min'   => 0,
                                    'max'   => 1,
                                    'step'  => 0.1,
                                ]
                            ],
                            'selectors'     => [
                                '{{WRAPPER}}'   => 'opacity: {{SIZE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Css_Filter::get_type(),
                        [
                            'name'      => 'ekit_lottie_filter',
                            'selector'  => '{{WRAPPER}}',
                        ]
                    );
                $this->end_controls_tab();

                $this->start_controls_tab(
                    'ekit_lottie_hover',
                    [
                        'label' => esc_html__( 'Hover', 'elementskit-lite' ),
                    ]
                );
                    $this->add_control(
                        'ekit_lottie_opacity_hover',
                        [
                            'label'         => esc_html__( 'Opacity', 'elementskit-lite' ),
                            'type'          => \Elementor\Controls_Manager::SLIDER,
                            'range'         => [
                                'px'    => [
                                    'min'   => 0,
                                    'max'   => 1,
                                    'step'  => 0.1,
                                ]
                            ],
                            'selectors'     => [
                                '{{WRAPPER}}:hover'   => 'opacity: {{SIZE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Css_Filter::get_type(),
                        [
                            'name'      => 'ekit_lottie_filter_hover',
                            'selector'  => '{{WRAPPER}}',
                        ]
                    );

                    $this->add_control(
                        'ekit_lottie_transition',
                        [
                            'label' => esc_html__( 'Transition', 'elementskit-lite' ),
                            'type'  => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px'    => [
                                    'max'   => 10,
                                    'step'  => 0.1,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}}'   => 'transition: all {{SIZE}}s ease;',
                            ],
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();

        $this->insert_pro_message();
    }

    /**
     * Same rule as Elementor Pro: the JSON URL source is only offered to users
     * who can publish pages. Saves are checked server-side as well, see
     * ElementsKit_Json_Handler::restrict_lottie_sources().
     */
    protected function current_user_can_use_external_source() {
        return \ElementsKit_Lite\ElementsKit_Json_Handler::current_user_can_manage_json();
    }

    protected function get_source_options() {
        $options = [
            'file' => [
                'title' => esc_html__( 'JSON File', 'elementskit-lite' ),
                'icon'  => 'eicon-folder-o',
            ],
        ];

        if ( $this->current_user_can_use_external_source() ) {
            $options['url'] = [
                'title' => esc_html__( 'JSON URL', 'elementskit-lite' ),
                'icon'  => 'eicon-link',
            ];
        }

        return $options;
    }

    /**
     * Resolve the animation JSON URL for the selected source.
     *
     * Media uploads are resolved from the attachment ID so a hand-edited `url`
     * cannot point anywhere else. Returns '' when nothing valid is set.
     */
    protected function get_lottie_path( $settings ) {
        return self::match_request_scheme( $this->get_raw_lottie_path( $settings ) );
    }

    /**
     * On an https request, serve this site's own JSON over https too.
     *
     * wp_get_attachment_url() upgrades to https on the frontend but not in
     * wp-admin, where the editor renders the widget. With an http:// WordPress
     * Address and the editor opened over https://, the editor preview got an
     * http:// JSON URL, the browser blocked it as mixed content and the
     * animation did not show, while the frontend worked.
     */
    protected static function match_request_scheme( $url ) {
        if ( '' === $url || ! is_ssl() || 0 !== strpos( $url, 'http://' ) ) {
            return $url;
        }

        $host      = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
        $own_hosts = array_map( 'strtolower', array_filter( [
            wp_parse_url( home_url(), PHP_URL_HOST ),
            wp_parse_url( site_url(), PHP_URL_HOST ),
        ] ) );

        return in_array( $host, $own_hosts, true ) ? set_url_scheme( $url, 'https' ) : $url;
    }

    protected function get_raw_lottie_path( $settings ) {
        if ( isset( $settings['ekit_lottie_type'] ) && 'url' === $settings['ekit_lottie_type'] ) {
            return ! empty( $settings['ekit_lottie_url'] ) ? self::sanitize_lottie_url( $settings['ekit_lottie_url'] ) : '';
        }

        $media = isset( $settings['ekit_lottie_json'] ) && is_array( $settings['ekit_lottie_json'] ) ? $settings['ekit_lottie_json'] : [];

        if ( ! empty( $media['id'] ) ) {
            $file_url = \ElementsKit_Lite\ElementsKit_Json_Handler::get_json_attachment_url( $media['id'] );

            if ( $file_url ) {
                return self::sanitize_lottie_url( $file_url );
            }
        }

        // No usable attachment (e.g. a template imported from another site).
        if ( ! empty( $media['url'] ) ) {
            return self::sanitize_lottie_url( $media['url'] );
        }

        return '';
    }

    /**
     * Return the URL when it is an http(s) URL or a site-relative path, otherwise ''.
     */
    public static function sanitize_lottie_url( $url ) {
        if ( ! is_string( $url ) ) {
            return '';
        }

        $url = trim( $url );

        if ( '' === $url ) {
            return '';
        }

        // Site-relative paths ("/wp-content/uploads/…") are left for the browser to resolve.
        if ( '/' === $url[0] && ( ! isset( $url[1] ) || '/' !== $url[1] ) ) {
            return esc_url_raw( $url );
        }

        if ( 0 === strpos( $url, '//' ) ) {
            $url = ( is_ssl() ? 'https:' : 'http:' ) . $url;
        }

        $scheme = wp_parse_url( $url, PHP_URL_SCHEME );

        if ( ! $scheme || ! in_array( strtolower( $scheme ), [ 'http', 'https' ], true ) ) {
            return '';
        }

        return esc_url_raw( $url, [ 'http', 'https' ] );
    }

    protected function render() {
        echo '<div class="ekit-wid-con" >';
            $this->render_raw();
        echo '</div>';
    }

    protected function render_raw() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute(
            'wrapper',
            [
                'id'                    => 'ekit_lottie_' . $this->get_id(),
                'class'                 => 'ekit_lottie',
                'data-autoplay'         => $settings['ekit_lottie_autoplay'],
                'data-on-scroll'        => $settings['ekit_lottie_on_scroll'],
                'data-speed'            => $settings['ekit_lottie_speed']['size'],
                'data-direction'        => $settings['ekit_lottie_reverse'],
                'data-action'           => $settings['ekit_lottie_action'],
                'data-renderer'         => $settings['ekit_lottie_renderer'],
            ]
        );


        $lottie_path = $this->get_lottie_path( $settings );

        if ( '' !== $lottie_path ):
            $this->add_render_attribute( 'wrapper', 'data-path', $lottie_path );
        endif;

		if ( isset($settings['ekit_lottie_loop_count']['size']) && is_numeric($settings['ekit_lottie_loop_count']['size']) ):
			$this->add_render_attribute( 'wrapper', 'data-loop', max(0, $settings['ekit_lottie_loop_count']['size'] - 1) );
		else:
			$this->add_render_attribute( 'wrapper', 'data-loop', $settings['ekit_lottie_loop'] );
		endif;


        if ( !empty($settings['ekit_lottie_link']['url']) && $settings['ekit_lottie_link']['url'] ):
            $this->add_render_attribute( 'wrapper', 'class', 'met_d--block' );
            $this->add_link_attributes( 'link', $settings['ekit_lottie_link'] );
        
            echo '<a '. $this->get_render_attribute_string( 'link' ) .' '. $this->get_render_attribute_string( 'wrapper' ) .'>&nbsp;</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_render_attribute_string Already escaped by elementor 
        else:
            echo '<div '. $this->get_render_attribute_string( 'wrapper' ) .'>&nbsp;</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_render_attribute_string Already escaped by elementor
        endif;
    }
}
