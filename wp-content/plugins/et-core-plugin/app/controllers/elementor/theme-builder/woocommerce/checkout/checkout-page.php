<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Checkout Page widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Checkout_Page extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-checkout-etheme_page';
	}

	/**
	 * Get widget title.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Checkout Page', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-sales-booster';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
        return [ 'woocommerce', 'checkout' ];
	}

    /**
     * Get widget categories.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['woocommerce-elements'];
    }
	
	/**
	 * Get widget dependency.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
        $styles = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $styles = [ 'etheme-cart-page', 'etheme-no-products-found', 'etheme-checkout-page' ];
        }
		return $styles;
	}

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        $scripts = [ 'wc-checkout' ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
            $scripts[] = 'cart_checkout_advanced_labels';
        return $scripts;
    }
	
	/**
	 * Help link.
	 *
	 * @since 5.2
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return 'https://xstore.helpscoutdocs.com/article/110-sales-booster';
	}

	/**
	 * Register widget controls.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function register_controls() {

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'cols',
            [
                'label' => __( 'Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                ],
                'default' => '2',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}}' => '--cols: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'heading_type',
            [
                'label' => esc_html__( 'Design Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => array(
                    'classic' => __( 'Classic', 'xstore-core' ),
                    'line-aside' => __( 'Line aside', 'xstore-core' ),
                    'square-aside' => __( 'Square aside', 'xstore-core' ),
                    'circle-aside' => __( 'Circle aside', 'xstore-core' ),
                    'underline' => __( 'With Underline', 'xstore-core' ),
                    'colored-underline' => __( 'With Colored Underline', 'xstore-core' ),
                ),
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'advanced_labels',
            [
                'label' => esc_html__('Advanced Labels', 'xstore-core'),
                'description' => esc_html__( 'Enable this option to have aesthetically pleasing animated labels when filling out forms on the checkout page.', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'email_field_first',
            [
                'label'           => esc_html__( 'Email field prioritized', 'xstore-core' ),
                'description' => esc_html__('Enable this option to move the email field to the first position of the billing details form so that it will become the highest priority for filling in among the other fields.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_col_one_elements',
            [
                'label' => esc_html__( 'Content', 'xstore-core' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'element',
            [
                'label'   => __('Element', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_elements_list(),
                'default' => 'billing_details',
            ]
        );

        $this->add_control(
            'col_one_elements',
            [
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'element'  => 'billing_details',
                    ],
                    [
                        'element'  => 'shipping_details',
                    ],
                ],
                'title_field' => '{{{ element }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_col_two_elements',
            [
                'label' => esc_html__( 'Content of 2nd column', 'xstore-core' ),
                'condition' => [
                    'cols' => '2'
                ]
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'element',
            [
                'label'   => __('Element', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_elements_list(),
                'default' => 'billing_details',
            ]
        );

        $this->add_control(
            'col_two_elements',
            [
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'element'  => 'order_review',
                    ],
                    [
                        'element'  => 'payment_methods',
                    ],
                ],
                'title_field' => '{{{ element }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'cols_gap',
            [
                'label' => __( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_heading!' => ''
                ]
            ]
        );

        $this->add_control(
            'heading_html_tag',
            [
                'label' => esc_html__( 'HTML Tag', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'heading_align',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'xstore-core' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .step-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .step-title',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .step-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_border_width',
            [
                'label' => esc_html__( 'Border Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 5,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_control(
            'heading_border_color',
            [
                'label'     => __( 'Border Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_inner_spacing',
            [
                'label' => esc_html__( 'Inner Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-inner-space-bottom: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-space-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_element_heading',
            [
                'label' => __( 'Design element', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

//        $this->add_responsive_control(
//            'heading_element_width',
//            [
//                'label' => esc_html__( 'Element Width', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px', 'rem' ],
//                'range' => [
//                    'px' => [
//                        'min'  => 1,
//                        'max'  => 20,
//                        'step' => 1
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}}' => '--widget-title-element-width: {{SIZE}}{{UNIT}}',
//                ],
//                'condition' => [
//                    'heading_type' => ['line-aside']
//                ]
//            ]
//        );

        $this->add_control(
            'heading_element_color',
            [
                'label'     => __( 'Color Active', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-element-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_fields_style',
            [
                'label' => esc_html__( 'Fields', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'fields_cols_gap',
            [
                'label' => __( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--fields-h-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'fields_rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--fields-v-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_label_style',
            [
                'label'                 => __( 'Labels', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce-billing-fields label, {{WRAPPER}} .woocommerce-shipping-fields label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'label_typography',
                'selector'              => '{{WRAPPER}} .woocommerce-billing-fields label, {{WRAPPER}} .woocommerce-shipping-fields label',
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields label, {{WRAPPER}} .woocommerce-shipping-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'advanced_labels' => ''
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_input_field_style',
            [
                'label' => esc_html__('Input/Textarea Fields', 'xstore-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_input_field_style');

        $this->start_controls_tab(
            'tab_input_field_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'input_field_text_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper text-area, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_background_color',
            [
                'label'     => esc_html__('Background Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper text-area, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'        => 'input_field_border',
                'label'       => esc_html__('Border', 'xstore-core'),
                'selector'    => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper text-area, {{WRAPPER}} .woocommerce-input-wrapper select',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'input_field_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper text-area, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_field_box_shadow',
                'selector' => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper text-area, {{WRAPPER}} .woocommerce-input-wrapper select',
            ]
        );

        $this->add_responsive_control(
            'input_field_padding',
            [
                'label'      => esc_html__('Padding', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper text-area, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'      => 'input_field_typography',
                'label'     => esc_html__('Typography', 'xstore-core'),
                'selector'  => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper text-area, {{WRAPPER}} .woocommerce-input-wrapper select',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_field_focus',
            [
                'label' => esc_html__('Focus', 'xstore-core'),
            ]
        );

        $this->add_control(
            'input_field_focus_background',
            [
                'label'     => esc_html__('Background', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input:focus, {{WRAPPER}} .woocommerce-input-wrapper textarea:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_focus_border_color',
            [
                'label'     => esc_html__('Border Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input:focus, {{WRAPPER}} .woocommerce-input-wrapper textarea:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'input_field_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_radio_checkbox_style',
            [
                'label' => esc_html__( 'Radio & Checkbox', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'radio_checkbox_size',
            [
                'label'                 => __( 'Size', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}}' => '--et_inputs-radio-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_order_total_style',
            [
                'label' => esc_html__( 'Order Total', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'order_total_table_typography',
                'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table',
            ]
        );

        $this->add_control(
            'order_total_table_space',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-checkout-review-order-table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_payments_style',
            [
                'label'                 => __( 'Payments', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_payment_label',
            [
                'label' => esc_html__('Label', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'payment_label_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} #payment .payment_methods label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'payment_label_typography',
                'selector'              => '{{WRAPPER}} #payment .payment_methods label',
            ]
        );

        $this->add_control(
            'payment_label_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} #payment .payment_methods label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_payment_box',
            [
                'label' => esc_html__('Content', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'box_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .payment_box' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'box_typography',
                'selector'              => '{{WRAPPER}} .payment_box',
            ]
        );

        $this->add_control(
            'box_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .payment_box' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_terms_style',
            [
                'label'                 => __( 'Terms & Conditions', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'terms_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_terms_links_colors');

        $this->start_controls_tab(
            'tab_terms_link_color_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'terms_link_color',
            [
                'label' => esc_html__( 'Link Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_terms_link_color_color_hover',
            [
                'label' => esc_html__('Links Hover', 'xstore-core'),
            ]
        );

        $this->add_control(
            'terms_link_color_hover',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'terms_typography',
                'selector'              => '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper',
            ]
        );

        $this->add_control(
            'terms_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_place_order_button_style',
            [
                'label' => __( 'Place Order Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'place_order_button_typography',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'place_order_button_text_shadow',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->start_controls_tabs( 'tabs_place_order_button_style' );

        $this->start_controls_tab(
            'tab_place_order_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'place_order_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_place_order_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'place_order_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} #place_order:hover svg, {{WRAPPER}} #place_order:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'place_order_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'place_order_button_border',
                'selector' => '{{WRAPPER}} #place_order, {{WRAPPER}} #place_order.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'place_order_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'place_order_button_payment_box_shadow',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->add_responsive_control(
            'place_order_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function render() {

        if ( ! is_object( WC()->cart ) || 0 === WC()->cart->get_cart_contents_count() ) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        $two_columns = $settings['cols'] == '2';
        if ( !count($settings['col_one_elements']) || ($two_columns && !count($settings['col_one_elements']) && !count($settings['col_two_elements']))) return;

        if ( !!$settings['advanced_labels'] )
            wp_enqueue_script( 'cart_checkout_advanced_labels' );
        ?>
        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
            <div class="etheme-grid align-items-start">
                <?php if ( $two_columns ) : ?>
                    <div class="etheme-grid">
                    <?php endif; ?>
                        <?php $this->print_elements($settings, $settings['col_one_elements']); ?>
                    <?php if ( $two_columns ) : ?>
                    </div>
                    <div class="etheme-grid">
                    <?php endif; ?>
                        <?php $this->print_elements($settings, $settings['col_two_elements']); ?>
                    <?php if ( $two_columns ) : ?>
                    </div>
                    <?php endif; ?>
            </div>
        </form>
        <?php
        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $(document).find( 'div.shipping_address' ).hide();
                    $(document).find('#ship-to-different-address input').on('change', function () {
                        $(document).find( 'div.shipping_address' ).hide();
                        if ( $( this ).is( ':checked' ) ) {
                            $(document).find( 'div.shipping_address' ).slideDown();
                        }
                    })
                    if ( etTheme.cart_checkout_advanced_labels !== undefined )
                        etTheme.cart_checkout_advanced_labels();
                });
            </script>
            <style>
                /* On real frontend there will be select2 by WooCommerce script */
                [data-id="<?php echo $this->get_id(); ?>"] .woocommerce-input-wrapper select {
                    width: 100%;
                }
            </style>
            <?php
        }
	}

    public function print_elements($settings, $elements) {
        foreach ($elements as $checkout_element) {
            switch ($checkout_element['element']) {
                case 'billing_details':
                    $this->print_billing_details($settings);
                    break;
                case 'shipping_details':
                    $this->print_shipping_details($settings);
                    break;
                case 'payment_methods':
                    $this->print_payment_methods();
                    break;
                case 'order_review':
                    woocommerce_order_review();
                    break;
            }
        }
    }

    public function print_billing_details($settings) {
        if ( !!$settings['advanced_labels'] ) {
            add_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'), 999);
            add_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }

        if ( !!$settings['show_heading'] ) {
            add_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            add_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            add_filter('etheme_form_billing_title', '__return_false');
        }

        if ( !!$settings['email_field_first'] ) {
            add_filter('woocommerce_billing_fields', array($this, 'prioritize_email_field'));
            // compatibility with Brazilian Market on WooCommerce plugin
            add_filter('wcbcf_billing_fields', array($this, 'wcbcf_prioritize_email_field'));
        }

        WC()->checkout()->checkout_form_billing();

        if ( !!$settings['email_field_first'] ) {
            remove_filter('woocommerce_billing_fields', array($this, 'prioritize_email_field'));
            // compatibility with Brazilian Market on WooCommerce plugin
            remove_filter('wcbcf_billing_fields', array($this, 'wcbcf_prioritize_email_field'));
        }

        if ( !!$settings['show_heading'] ) {
            remove_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'), 999);
            remove_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
        }
        else {
            remove_filter('etheme_form_billing_title', '__return_false');
        }

        if ( !!$settings['advanced_labels'] ) {
            remove_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            remove_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }
    }

    public function print_shipping_details($settings) {
        if ( !!$settings['advanced_labels'] ) {
            add_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            add_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }

        if ( !!$settings['show_heading'] ) {
            add_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            add_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            add_filter('etheme_form_shipping_title', '__return_false');
        }

        WC()->checkout()->checkout_form_shipping();

        if ( !!$settings['show_heading'] ) {
            remove_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
            remove_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
        }
        else {
            remove_filter('etheme_form_shipping_title', '__return_false');
        }

        if ( !!$settings['advanced_labels'] ) {
            remove_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            remove_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }
    }

    public function print_payment_methods() {
        WC()->cart->calculate_fees();
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();

        woocommerce_checkout_payment();
    }

    public function title_tag($html_tag) {
        $settings = $this->get_settings_for_display();
        return $settings['heading_html_tag'];
    }

    public function title_class($class) {
        $settings = $this->get_settings_for_display();
        return $class . ' style-' . $settings['heading_type'];
    }

    public function filter_form_placeholders($fields) {
        $new_fields = array();
        foreach ($fields as $field_key => $field) {
            if ( isset($field['label']) && $field['label'] != '' ) {
                if ( isset($field['label_class']) ) {
                    if ( !in_array( 'screen-reader-text', $field['label_class'] ) )
                        $field['placeholder'] = '';
                }
                elseif ( isset($field['placeholder']) ) {
                    $field['placeholder'] = '';
                }
            }
            $new_fields[$field_key] = $field;
        }
        return $new_fields;
    }

    public function filter_form_fields ( $args ) {
        if ( $args['label'] != '' && ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
            $args['class'][] = 'et-advanced-label';
            $args['placeholder'] = '';
        }

        if ( $args['type'] == 'textarea' ) {
            $args['label_class'][] = 'textarea-label';
        }

        if ( ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
            $args['class'][] = 'et-validated';
        }

        return $args;
    }

    public function prioritize_email_field($fields) {
        if ( isset($fields['billing_email'])) {
            $fields['billing_email']['priority'] = 5;
        }
        // Fix autofocus - maybe be useful in future updates
    //                if ( isset( $fields['billing'] ) ) $fields['billing']['billing_first_name']['autofocus'] = false;
    //                if ( isset( $fields['shipping'] ) ) $fields['shipping']['shipping_first_name']['autofocus'] = false;
        return $fields;
    }

    public function wcbcf_prioritize_email_field($fields){
        if ( isset($fields['billing_first_name'])) {
            $fields['billing_first_name']['priority'] = 15;
        }
        if ( isset($fields['billing_last_name'])) {
            $fields['billing_last_name']['priority'] = 15;
        }
        return $fields;
    }

    public function get_elements_list() {
        return array(
            'billing_details' => esc_html__('Billing details', 'xstore-core'),
            'shipping_details' => esc_html__('Shipping details', 'xstore-core'),
            'payment_methods' => esc_html__('Payment methods', 'xstore-core'),
            'order_review' => esc_html__('Order review', 'xstore-core')
        );
    }

}
