<?php
/**
 * bookmark Widget
 *
 */
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || die();

class ESBMRK_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve rsgallery widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */

    public function get_name() {
        return 'easy_bookmark';
    }   


    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Easy Bookmark', 'easy-bookmark' );
    }

    /**
     * Get widget esbmrk-icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget esbmrk-icon.
     */
    public function get_icon() {
        return 'easy-bookmark-icon';
    }

   
    public function get_categories() {
        return [ 'easy_bookmark_category' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content Settings', 'easy-bookmark' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'easy-bookmark' ),
				'type' => Controls_Manager::TEXT,
				'default' => '6',
				'separator' => 'before',
			]
		);

        $this->add_control(
            'post_meta_show_hide',
            [
                'label' => esc_html__( 'Meta Show / Hide', 'easy-bookmark' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__( 'Yes', 'easy-bookmark' ),
                    'none' => esc_html__( 'No', 'easy-bookmark' ),
                ],                
                'separator' => 'before',
                'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article-metas' => 'display: {{VALUE}};',
				],    
            ]
        );
        $this->add_control(
			'counter_box_esbmrk_icon',
			[
				'label' => esc_html__( 'Bookmark Icon', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-bookmark',
					'library' => 'fa-regular',
				],
			]
		);

        $this->add_control(
            'count_position',
            [
                'label'     => esc_html__('Count Position', 'easy-bookmark'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'top',
                'options'   => [
                    'top'      => esc_html__('Top', 'easy-bookmark'),
                    'right'      => esc_html__('Right', 'easy-bookmark')
                ]
            ]
        );

        $this->add_control(
            'esbm_icon_text',
            [
                'label' => esc_html__( 'Bookmark Label', 'easy-bookmark' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
           'enable_count',
            [
               'label' => esc_html__('Enable Count', 'easy-bookmark'),
               'type' => Controls_Manager::SWITCHER,
               'label_on' => esc_html__('Yes', 'easy-bookmark'),
               'label_off' => esc_html__('No', 'easy-bookmark'),
               'return_value' => 'yes',
               'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();



        $this->start_controls_section(
			'counter_box_styles',
			[
				'label' => esc_html__( 'Counter Box', 'easy-bookmark' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_responsive_control(
            'counter_box_esbmrk_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter .esbmrk-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'counter_box_height',
            [
                'label' => esc_html__( 'Box Height', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'counter_box_width',
            [
                'label' => esc_html__( 'Box Width', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'counter_box_background',
                'label' => esc_html__( 'Box Background', 'easy-bookmark' ),
                'types' => [ 'classic', 'gradient', 'easy-bookmark' ],
                'selector' => '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter',
                
            ]
        );
        $this->add_control(
			'counter_box_counter_styles',
			[
				'label' => esc_html__( 'Counter', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_responsive_control(
            'counter_box_counter_text_size',
            [
                'label' => esc_html__( 'Number Font Size', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter .esbmrk-total-marked' => 'font-size: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'counter_box_counter_height',
            [
                'label' => esc_html__( 'Box Height', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter .esbmrk-total-marked' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'counter_box_counter_width',
            [
                'label' => esc_html__( 'Box Width', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter .esbmrk-total-marked' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'counter_box_counter_position_top',
            [
                'label' => esc_html__( 'Position Top', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
                        'step' => 1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter .esbmrk-total-marked' => 'top: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'counter_box_counter_position_right',
            [
                'label' => esc_html__( 'Position Right', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
                        'step' => 1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter .esbmrk-total-marked' => 'right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'counter_box_counter_background',
                'label' => esc_html__( 'Box Background', 'easy-bookmark' ),
                'types' => [ 'classic', 'gradient', 'easy-bookmark' ],
                'selector' => '{{WRAPPER}} .esbmrk-counter-box .esbmrk-counter .esbmrk-total-marked',
                
            ]
        );
        $this->end_controls_section();



        $this->start_controls_section(
			'modal_styles',
			[
				'label' => esc_html__( 'Modal', 'easy-bookmark' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'modal_container',
			[
				'label' => esc_html__( 'Modal Container', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_responsive_control(
			'bookmark_height',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'easy-bookmark' ),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
				'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'bookmark_width',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'easy-bookmark' ),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1600,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'modal_background',
                'label' => esc_html__( 'Modal Background', 'easy-bookmark' ),
                'types' => [ 'classic', 'gradient', 'easy-bookmark' ],
                'selector' => '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content',
                
            ]
        );
        $this->add_control(
            'modal_body_padding',
            [
                'label' => esc_html__( 'Body Padding', 'easy-bookmark' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
            ]
        );
        $this->add_control(
			'modal_header',
			[
				'label' => esc_html__( 'Modal Header', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'modal_header_background',
                'label' => esc_html__( 'Modal Header Background', 'easy-bookmark' ),
                'types' => [ 'classic', 'easy-bookmark' ],
                'selector' => '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header',
                
            ]
        );
        $this->add_control(
            'modal_header_padding',
            [
                'label' => esc_html__( 'Title Padding', 'easy-bookmark' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
            ]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'modal_title_typography',
				'label' => esc_html__( 'Title Typography', 'easy-bookmark' ),
				'selector' => '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header .esbmrk-modal-title',
			]
		);
        $this->add_control(
            'modal_header_icon_color',
            [
                'label' => esc_html__( 'Close Icon Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header .close' => 'color: {{VALUE}};',
                ],                
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'modal_header_icon_bg_color',
            [
                'label' => esc_html__( 'Close Icon Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header .close' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->add_responsive_control(
            'modal_header_icon_size',
            [
                'label' => esc_html__( 'Close Icon Size', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header .close' => 'font-size: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'modal_header_icon_box_height',
            [
                'label' => esc_html__( 'Close Icon Box Height', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header .close' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'modal_header_icon_box_width',
            [
                'label' => esc_html__( 'Close Icon Box Width', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-header .close' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
			'action_bar_styles',
			[
				'label' => esc_html__( 'Action Bar', 'easy-bookmark' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
            'action_bar_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-bookmark' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
            ]
        );
        $this->add_control(
            'action_bar_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-bookmark' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
            ]
        );
        $this->add_control(
            'action_bar_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
			'action_bar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'action_buttons',
			[
				'label' => esc_html__( 'Buttons', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'action_button_typography',
				'label' => esc_html__( 'Button Typography', 'easy-bookmark' ),
				'selector' => 
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn',
			]
		);
        $this->add_responsive_control(
            'action_button_width',
            [
                'label' => esc_html__( 'Button Width', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
						'max' => 500,
						'step' => 2,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
			'action_button_align',
			[
				'label' => esc_html__( 'Alignment', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-bookmark' ),
						'icon' => 'e-icon-justify-start-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-bookmark' ),
						'icon' => 'e-icon-justify-center-h',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-bookmark' ),
						'icon' => 'e-icon-justify-end-h',
					],
                    'space-between' => [
						'title' => esc_html__( 'Space Between', 'easy-bookmark' ),
						'icon' => 'e-icon-justify-space-between-h',
					],
				],
				'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn' => 'justify-content: {{VALUE}};',
                ],
			]
		);
        $this->add_responsive_control(
            'action_button_space_between_text_icon',
            [
                'label' => esc_html__( 'Button Space Between', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
						'max' => 500,
						'step' => 2,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn' => 'gap: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
			'action_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'action_button_view_all',
			[
				'label' => esc_html__( 'View All Button', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->start_controls_tabs(
            'action_button_view_all_tabs'
        );
        
        $this->start_controls_tab(
            'action_button_view_all_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-bookmark' ),
            ]
        );
        $this->add_control(
            'action_button_view_all_color',
            [
                'label' => esc_html__( 'Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_view_all_trigger' => 'color: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
            'action_button_view_all_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_view_all_trigger' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'action_button_view_all_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-bookmark' ),
            ]
        );
        $this->add_control(
            'action_button_view_all_color_hover',
            [
                'label' => esc_html__( 'Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_view_all_trigger:hover' => 'color: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
            'action_button_view_all_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_view_all_trigger:hover' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
			'action_button_delete_all',
			[
				'label' => esc_html__( 'Delete All Button', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->start_controls_tabs(
            'action_button_tabs'
        );
        
        $this->start_controls_tab(
            'action_button_delete_all_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-bookmark' ),
            ]
        );
        $this->add_control(
            'action_button_delete_all_color',
            [
                'label' => esc_html__( 'Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_delete_all_trigger' => 'color: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
            'action_button_delete_all_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_delete_all_trigger' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'action_button_delet_all_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-bookmark' ),
            ]
        );
        $this->add_control(
            'action_button_delete_all_color_hover',
            [
                'label' => esc_html__( 'Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_delete_all_trigger:hover' => 'color: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
            'action_button_delete_all_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-action-bar .esbmrk-action-btn.esbmrk_delete_all_trigger:hover' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();




        
        


        $this->start_controls_section(
			'item_styles',
			[
				'label' => esc_html__( 'Item', 'easy-bookmark' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => esc_html__( 'Box Shadow', 'easy-bookmark' ),
                'selector' => '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'easy-bookmark' ),
                'types' => [ 'classic', 'gradient', 'easy-bookmark' ],
                'selector' => '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article',
                
            ]
        );

        $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name' => 'post_border',
		        'selector' => '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article',      
		    ]
		);

        $this->add_control(
            'post_padding',
            [
                'label' => esc_html__( 'Item Padding', 'easy-bookmark' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
            ]
        );

        $this->add_control(
            'post_border_radius',
            [
                'label' => esc_html__( 'Item Border radius', 'easy-bookmark' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
					'{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
            ]
        );

      

        $this->add_responsive_control(
            'post_bottom_spacing',
            [
                'label' => esc_html__( 'Item Spacing', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-posts' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'post_spacing_image_contents',
            [
                'label' => esc_html__( 'Item Space Between Image & Contents', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
			'post_remove_button_styles',
			[
				'label' => esc_html__( 'Remove Button', 'easy-bookmark' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->start_controls_tabs(
            'modal_action_post_remove_button_tabs'
        );
        
        $this->start_controls_tab(
            'action_post_remove_button_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-bookmark' ),
            ]
        );
        $this->add_control(
            'post_remove_button_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article label.esbmrk-form-control.esbmrk-remove-btn' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
            'post_remove_button_color',
            [
                'label' => esc_html__( 'Icon Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article label.esbmrk-form-control.esbmrk-remove-btn' => 'color: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'post_remove_button_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-bookmark' ),
            ]
        );
        $this->add_control(
            'post_remove_button_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article label.esbmrk-form-control.esbmrk-remove-btn:hover' => 'background: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
            'post_remove_button_color_hover',
            [
                'label' => esc_html__( 'Icon Color Hover', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article label.esbmrk-form-control.esbmrk-remove-btn:hover' => 'color: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'post_meta_style',
            [
                'label' => esc_html__( 'Meta', 'easy-bookmark' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'post_meta_color',
            [
                'label' => esc_html__( 'Meta Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-metas .esbmrk-meta' => 'color: {{VALUE}};',
                ],                
            ]
        );

        $this->add_control(
            'post_meta_icon_color',
            [
                'label' => esc_html__( 'Meta Icon Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-metas .esbmrk-meta i' => 'color: {{VALUE}};',
                ],                
            ]
        );

        $this->add_responsive_control(
            'post_meta_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-bookmark' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
               
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-metas' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'post_meta_spacing',
            [
                'label' => esc_html__( 'Item Space Between', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-metas' => 'gap: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => esc_html__( 'Typography', 'easy-bookmark' ),
				'selector' => 
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-metas .esbmrk-meta',
			]
		);
        $this->add_responsive_control(
            'post_meta_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-bookmark' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-metas .esbmrk-meta .esbmrk-meta-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'post_title_style',
            [
                'label' => esc_html__( 'Title', 'easy-bookmark' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-title' => 'color: {{VALUE}};',
                ],                
            ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Title Hover Color', 'easy-bookmark' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-title:hover' => 'color: {{VALUE}};',
                ],                
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-bookmark' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-bookmark' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'easy-bookmark' ),
				'selector' => 
                    '{{WRAPPER}} .esbmrk-counter-box #esbmrk_popup_modal .esbmrk-modal-content .esbmrk-modal-body .esbmrk-posts .esbmrk-article .esbmrk-article-inner .esbmrk-col-right .esbmrk-article-title',
			]
		);
        $this->end_controls_section();


    }
  
    protected function render() {

        $settings = $this->get_settings_for_display();   
        $counter_box_esbmrk_icon = $settings['counter_box_esbmrk_icon'];
        $esbm_icon_text = $settings['esbm_icon_text'];
        $posts_per_page = $settings['posts_per_page'];
        $enable_count = $settings['enable_count'];
        $count_position = $settings['count_position'];
       
        $args = array(
            'posts_per_page' => $posts_per_page,
            'counter_box_esbmrk_icon' => $counter_box_esbmrk_icon,
            'esbm_icon_text' => $esbm_icon_text,
            'enable_count' => $enable_count,
            'count_position' => $count_position,
        );

        $allowed_html = array(
            'div' => array(
                'class' => array(),
            ),
            'span' => array(
                'class' => array(),
            ),
        );
        $ESBMRK_View = new ESBMRK_View();
        echo esc_html($ESBMRK_View->esbmrk_get_easy_bookmark_counter($args));
        
    }
}
