<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart;

use ETC\App\Classes\Elementor;

/**
 * Cart Totals widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart_Table extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-cart-etheme_table';
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
		return __( 'Cart Table', 'xstore-core' );
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
        return [ 'woocommerce', 'cart' ];
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
        $scripts = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'etheme_elementor_cart_table';
        }
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
            'design_type',
            [
                'label' => esc_html__( 'Design type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Classic', 'xstore-core' ),
                    'separated' => esc_html__( 'Separated', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'design_type_separated_description',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf( __( 'Use this type for making next column filled with <a href="%s" target="_blank">full-height background</a>, we recommend you to add aside Cart Totals widget', 'xstore-core' ), 'https://prnt.sc/jS61G4_OQnK3' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'design_type' => 'separated',
                ],
            ]
        );

        $this->add_control(
            'design_separated_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => '--et_ccsl-2d-color: {{VALUE}};',
                ],
                'condition' => [
                    'design_type' => 'separated',
                ],
            ]
        );

        $this->add_control(
            'design_separated_direction',
            [
                'label' => esc_html__( 'Separated Direction', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ltr',
                'options' => array(
                    'ltr' => __( 'LTR', 'xstore-core' ),
                    'rtl' => __( 'RTL', 'xstore-core' ),
                ),
                'prefix_class' => 'direction-',
                'condition' => [
                    'design_type' => 'separated',
                ],
            ]
        );

        $this->add_responsive_control(
            'design_separated_direction_offset',
            [
                'label' => __( 'Offset', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'design_type' => 'separated',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--design-element-overlay-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_fields',
            [
                'label' => esc_html__( 'Table Fields', 'xstore-core' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'status',
            [
                'label' => esc_html__('Active Status', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'label',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'status!' => ''
                ]
            ]
        );

        $repeater->add_control(
            'show_mobile',
            [
                'label' => esc_html__('Show on mobile', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'status!' => ''
                ]
            ]
        );

        $this->add_control(
            'table_fields',
            [
                'label' => esc_html__( 'Fields', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'item_actions' => [
                    'add' => false,
                    'duplicate' => false,
                    'remove' => false,
                    'sort' => true,
                ],
                'default' => $this->get_table_field_defaults(),
                'title_field' => '{{{ label }}}',
            ]
        );
//
//        $this->add_control(
//            'update_cart_automatically',
//            [
//                'label' => esc_html__( 'Update Cart Automatically', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SWITCHER,
//                'selectors' => [
//                    '{{WRAPPER}}' => '{{VALUE}};',
//                ],
//                'selectors_dictionary' => [
//                    'yes' => '--update-cart-automatically-display: none;',
//                ],
//                'frontend_available' => true,
//                'render_type' => 'template',
//            ]
//        );
//
//        $this->add_control(
//            'update_cart_automatically_description',
//            [
//                'raw' => esc_html__( 'Changes to the cart will update automatically.', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::RAW_HTML,
//                'content_classes' => 'elementor-descriptor',
//            ]
//        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional',
            [
                'label' => esc_html__( 'Additional Options', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'additional_empty_cart_template_switch',
            [
                'label' => esc_html__( 'Customize empty cart', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'additional_template_description',
            [
                'raw' => sprintf(
                /* translators: 1: Saved templates link opening tag, 2: Link closing tag. */
                    esc_html__( 'Replaces the default WooCommerce Empty Cart screen with a custom template. (Don’t have one? Head over to %1$sSaved Templates%2$s)', 'xstore-core' ),
                    sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=elementor_library&tabs_group=library#add_new' ) ),
                    '</a>'
                ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor elementor-descriptor-subtle',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_cart_template_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Choose template', 'xstore-core' ),
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_cart_content_type',
            [
                'label' 		=>	__( 'Content Type', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_saved_content_list(false),
                'default'	=> 'custom',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_cart_save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf( __( 'Create template in Templates -> <a href="%s" target="_blank">Saved Templates</a> -> Choose ready to use template or go to Saved Templates and create new one.', 'xstore-core' ), admin_url('edit.php?post_type=elementor_library&tabs_group=library') ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'saved_template'
                ]
            ]
        );

        $this->add_control(
            'additional_empty_cart_template_content',
            [
                'type'        => \Elementor\Controls_Manager::WYSIWYG,
                'label'       => __( 'Content', 'xstore-core' ),
                'condition'   => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'custom',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'additional_empty_cart_saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_saved_content(),
                'default' => 'select',
                'condition' => [
                    'additional_empty_cart_template_switch!' => '',
                    'additional_empty_cart_content_type' => 'saved_template'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_cart_table_style',
            [
                'label' => esc_html__( 'Table', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'cart_table_typography',
                'selector' => '{{WRAPPER}} .woocommerce-cart-form table',
            ]
        );

        $this->add_control(
            'cart_table_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form table' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_table_price_color',
            [
                'label' => __( 'Price Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form table .amount' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_table_space',
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
                    '{{WRAPPER}} .woocommerce-cart-form table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_actions_style',
            [
                'label' => esc_html__( 'Actions', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'actions_space',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
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
                    '{{WRAPPER}} .actions' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_clear_cart_button_style',
            [
                'label' => __( 'Clear Cart Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'clear_cart_button_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'clear_cart_button_icon',
                'label_block' => false,
                'default' => [
                    'value' => 'et-icon et-trash',
                    'library' => 'xstore-icons',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'clear_cart_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 7
                ],
                'selectors' => [
                    '{{WRAPPER}} .clear-cart .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .clear-cart .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'clear_cart_button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'clear_cart_button_typography',
                'selector' => '{{WRAPPER}} .clear-cart',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'clear_cart_button_text_shadow',
                'selector' => '{{WRAPPER}} .clear-cart',
            ]
        );

        $this->start_controls_tabs( 'tabs_clear_cart_button_style' );

        $this->start_controls_tab(
            'tab_clear_cart_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'clear_cart_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_clear_cart_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'clear_cart_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-cart:hover, {{WRAPPER}} .clear-cart:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .clear-cart:hover svg, {{WRAPPER}} .clear-cart:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-cart:hover, {{WRAPPER}} .clear-cart:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'clear_cart_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'clear_cart_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .clear-cart:hover, {{WRAPPER}} .clear-cart:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'clear_cart_button_border',
                'selector' => '{{WRAPPER}} .clear-cart, {{WRAPPER}} .clear-cart.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'clear_cart_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'clear_cart_button_box_shadow',
                'selector' => '{{WRAPPER}} .clear-cart',
            ]
        );

        $this->add_responsive_control(
            'clear_cart_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clear-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        if ( !class_exists('WooCommerce') ) {
            echo esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core');
            return;
        }

        if ( ! is_object( WC()->cart ) ) {
            return;
        }

        // enqueue script for refreshing elements loaded in empty cart template
        wp_enqueue_script('etheme_elementor_cart_table');

        $empty_cart = WC()->cart->is_empty();
        if ( $empty_cart )
            $this->add_render_attribute( '_wrapper', 'class', 'full-width' );

        $settings = $this->get_settings_for_display();
        $fallback_text = '<h1 style="text-align: center;">'.esc_html__('YOUR SHOPPING CART IS EMPTY', 'xstore-core').'</h1><p style="text-align: center;">'.esc_html__('We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!', 'xstore-core').'</p>';
        if ( wc_get_page_id( 'shop' ) > 0 ) :
            $fallback_text .= '<p class="text-center"><a class="btn black" href="' . get_permalink(wc_get_page_id('shop')) .'"><span>' . esc_html__('Return To Shop', 'xstore') . '</span></a></p>';
        endif;
        if ( $empty_cart )
            echo '<div class="woocommerce"><div class="wc-empty-cart-message">';
            if ( !!$settings['additional_empty_cart_template_switch'] ) {
                switch ($settings['additional_empty_cart_content_type']) {
                    case 'custom':
                        if ( $empty_cart ) {
                            if (!empty($settings['additional_empty_cart_template_content']))
                                $this->print_unescaped_setting('additional_empty_cart_template_content');
                            else
                                echo $fallback_text;
                        }
                        break;
                    case 'global_widget':
                    case 'saved_template':
                        $prefix = 'additional_empty_cart_';
                        if (!empty($settings[$prefix.$settings['additional_empty_cart_content_type']])):
                            //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $settings[$settings['content_type']], true );
                            $posts = get_posts(
                                [
                                    'name' => $settings[$prefix.$settings['additional_empty_cart_content_type']],
                                    'post_type'      => 'elementor_library',
                                    'posts_per_page' => '1',
                                    'tax_query'      => [
                                        [
                                            'taxonomy' => 'elementor_library_type',
                                            'field'    => 'slug',
                                            'terms'    => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $settings['additional_empty_cart_content_type']),
                                        ],
                                    ],
                                    'fields' => 'ids'
                                ]
                            );

                            if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) { // @todo maybe try to enchance TRUE value with on ajax only
                                if ( $empty_cart )
                                    echo esc_html__('We have imported popup template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
                            } else {
                                // enqueue script for refreshing elements loaded in empty cart template
//                                wp_enqueue_script('etheme_elementor_cart_table');
                                if ( $empty_cart )
                                    echo $content;
                            }
                            elseif($empty_cart) :
                                echo $fallback_text;
                            endif;
                        break;
                }
            }
            elseif ($empty_cart) {
                echo $fallback_text;
            }

        if ( $empty_cart ) {
            echo '</div></div>';
            return;
        }

        $woo_new_7_0_1_version = etheme_woo_version_check();
        $button_class = '';
        if ( $woo_new_7_0_1_version ) {
            $button_class = wc_wp_theme_get_element_class_name( 'button' );
        }

        $this->add_render_attribute( 'button_text',
            [
                'class' => 'button-text',
            ]
        );

        if ( defined('ETHEME_THEME_VERSION') ) {
            remove_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            remove_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
            add_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            add_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
        }

        add_filter('et_sales_booster_cart_checkout_progress_bar_enabled', '__return_false');
        ?>
        <div class="woocommerce<?php if ( $settings['design_type'] == 'separated' ) echo ' design-styled-part'; ?>">
            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

                <?php do_action( 'woocommerce_before_cart_table' ); ?>
                <div class="table-responsive">
                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                        <thead>
                        <tr>
                            <?php
                                foreach ( $settings['table_fields'] as $repeater_field ) {
                                    if ( !!!$repeater_field['status'] ) continue;
                                    $field_attr = array(
                                        'class="product-'.esc_attr($repeater_field['field_key']).'"'
                                    );
                                    if ( in_array($repeater_field['field_key'], array('details')) )
                                        $field_attr[] = 'colspan="2"'
                                    ?>
                                    <th <?php echo implode(' ', $field_attr) ?>><?php echo esc_html($repeater_field['label'] ); ?></th>
                                    <?php
                                }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            /**
                             * Filter the product name.
                             *
                             * @since 7.8.0
                             * @param string $product_name Name of the product in the cart.
                             */
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                    <?php
                                    foreach ( $settings['table_fields'] as $repeater_field ) {
                                        if ( !!!$repeater_field['status'] ) continue;
                                        switch ($repeater_field['field_key']) {
                                            case 'details':
                                                ?>
                                                <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'xstore' ); ?>">
                                                    <div class="product-thumbnail">
                                                        <?php
                                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                                        if ( ! $_product->is_visible() || ! $product_permalink){
                                                            echo wp_kses_post( $thumbnail );
                                                        } else {
                                                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td class="product-details">
                                                    <div class="cart-item-details">
                                                        <?php
                                                        if ( ! $_product->is_visible() || ! $product_permalink  ){
                                                            /**
                                                             * Filter the product name.
                                                             *
                                                             * @since 7.8.0
                                                             * @param string $product_name Name of the product in the cart.
                                                             * @param array $cart_item The product in the cart.
                                                             * @param string $cart_item_key Key for the product in the cart.
                                                             */
                                                            echo wp_kses_post( $product_name );
                                                        } else {
                                                            /**
                                                             * Filter the product name.
                                                             *
                                                             * @since 7.8.0
                                                             * @param string $product_url URL the product in the cart.
                                                             */
                                                            echo wp_kses_post( sprintf( '<a href="%s" class="product-title">%s</a>', esc_url( $product_permalink ), $product_name ) );
                                                        }

                                                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                                        echo wc_get_formatted_cart_item_data( $cart_item );

                                                        // Backorder notification
                                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'xstore-core' ) . '</p>', $product_id ) );
                                                        ?>
                                                        <div class="product-remove">
                                                            <?php
                                                            echo apply_filters( 'woocommerce_cart_item_remove_link',
                                                                sprintf(
                                                                    '<a href="%s" class="remove-item text-underline" title="%s">%s</a>',
                                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                                    /* translators: %s is the product name */
                                                                    esc_attr( sprintf( __( 'Remove %s from cart', 'xstore-core' ), wp_strip_all_tags( $product_name ) ) ),
                                                                    esc_html__('Remove', 'xstore-core')
                                                                ),
                                                                $cart_item_key );
                                                            ?>
                                                        </div>
                                                        <span class="mobile-price">
                                            <?php
                                            echo (int) $cart_item['quantity'] . ' x ' . apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                            ?>
                                        </span>
                                                    </div>
                                                </td>
                                                <?php
                                                break;
                                            case 'price':
                                                ?>
                                                <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'xstore-core' ); ?>">
                                                    <?php
                                                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                            case 'sku':
                                                ?>
                                                <td class="product-sku" data-title="<?php esc_attr_e( 'SKU', 'xstore-core' ); ?>">
                                                    <?php
                                                        echo esc_html( ( $sku = $_product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ) );
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                            case 'quantity':
                                                ?>
                                                <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'xstore-core' ); ?>">
                                                    <?php
                                                    if ( $_product->is_sold_individually() ) {
                                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                    } else {
                                                        $product_quantity = woocommerce_quantity_input( array(
                                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                                            'input_value' => $cart_item['quantity'],
                                                            'max_value'   => $_product->get_max_purchase_quantity(),
                                                            'min_value'   => '0',
                                                            'product_name'  => $product_name,
                                                        ), $_product, false );
                                                    }

                                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                            case 'subtotal':
                                                ?>
                                                <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'xstore-core' ); ?>">
                                                    <?php
                                                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                                    ?>
                                                </td>
                                                <?php
                                                break;
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        }

                        do_action( 'woocommerce_cart_contents' );
                        ?>

                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                        </tbody>
                    </table>
                </div>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>

                <div class="actions">
            <?php if ( wc_coupons_enabled() ) : ?>
                <div class="text-left mob-center">
                    <form class="checkout_coupon" method="post">
                        <div class="coupon">

                            <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'xstore-core' ); ?></label>
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Coupon code', 'xstore-core' ); ?>" />
                            <input type="submit" class="btn<?php echo esc_attr( $button_class ? ' ' . $button_class : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e('OK', 'xstore-core'); ?>" />

                            <?php do_action('woocommerce_cart_coupon'); ?>

                        </div>
                    </form>
                </div>
            <?php endif; ?>
            <div class="mob-center">
                <a class="clear-cart btn bordered flex-inline align-items-center">
                <?php
                    if ( $settings['clear_cart_button_icon_align'] == 'left')
                        $this->render_icon( $settings, 'clear_cart_button_' );
                    ?>
                    <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                        <?php esc_html_e('Clear shopping cart', 'xstore-core'); ?>
                    </span>
                    <?php
                    if ( $settings['clear_cart_button_icon_align'] == 'right')
                        $this->render_icon( $settings, 'clear_cart_button_' );
                    ?>
                </a>
                <button type="submit" class="btn gray medium bordered hidden<?php echo esc_attr( $button_class ? ' ' . $button_class : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'xstore-core' ); ?>"><?php esc_html_e( 'Update cart', 'xstore-core' ); ?></button>
                <?php wp_nonce_field( 'woocommerce-cart' ); ?>
                <?php do_action( 'woocommerce_cart_actions' ); ?>
            </div>
        </div>
            </form>
        </div>
        <?php
        remove_filter('et_sales_booster_cart_checkout_progress_bar_enabled', '__return_false');
	}

    /**
     * Get Billing Field Defaults
     *
     * Get defaults used for the billing details repeater control.
     *
     * @since 5.2
     *
     * @return array
     */
    private function get_table_field_defaults() {
        $fields = [
            'details' => [
                'label' => esc_html__( 'Product', 'xstore-core' ),
            ],
            'price' => [
                'label' => esc_html__( 'Price', 'xstore-core' ),
                'show_mobile' => ''
            ],
            'sku' => [
                'label' => esc_html__( 'SKU', 'xstore-core' ),
                'show_mobile' => ''
            ],
            'quantity' => [
                'label' => esc_html__( 'Quantity', 'xstore-core' ),
            ],
            'subtotal' => [
                'label' => esc_html__( 'Subtotal', 'xstore-core' ),
                'show_mobile' => ''
            ],
        ];

        return $this->reformat_field_defaults( $fields );
    }

    /**
     * Reformat Table Field Defaults
     *
     * Used with the `get_..._field_defaults()` methods.
     * Takes the fields array and converts it into the format expected by the repeater controls.
     *
     * @since 5.2
     *
     * @param $fields
     * @return array
     */
    private function reformat_field_defaults( $fields ) {
        $defaults = [];
        foreach ( $fields as $key => $value ) {
            $defaults[] = [
                'field_key' => $key,
                'field_label' => $value['label'],
                'label' => $value['label'],
                'status' => 'yes',
                'show_mobile' => isset($value['show_mobile']) ? $value['show_mobile'] : 'yes',
            ];
        }

        return $defaults;
    }


    protected function render_icon($settings, $prefix = '') {
        $migrated = isset( $settings['__fa4_migrated'][$prefix.'selected_icon'] );
        $is_new = empty( $settings[$prefix.'icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings[$prefix.'icon'] ) || ! empty( $settings[$prefix.'selected_icon']['value'] ) ) : ?>
            <?php if ( $is_new || $migrated ) :
                \Elementor\Icons_Manager::render_icon( $settings[$prefix.'selected_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings[$prefix.'icon'] ); ?>" aria-hidden="true"></i>
            <?php endif;
        endif;
    }
}
