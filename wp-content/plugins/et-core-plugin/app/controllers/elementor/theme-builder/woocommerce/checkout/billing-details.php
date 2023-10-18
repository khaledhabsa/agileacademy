<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Cart Totals widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Billing_Details extends \Elementor\Widget_Base {
    public static $checkout_login_reminder_feature_status = null;
    public static $shipping_feature_status = null;
    public static $coupons_feature_status = null;
    public static $signup_and_login_from_checkout_status = null;
    public static $ship_to_billing_address_only_feature_status = null;
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-checkout-etheme_billing_details';
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
		return __( 'Billing Details', 'xstore-core' );
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
            'design_type',
            [
                'label' => esc_html__( 'Design type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Classic', 'xstore-core' ),
//                    'multistep' => esc_html__( 'Multistep', 'xstore-core' ),
                    'separated' => esc_html__( 'Separated', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'design_type_separated_description',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf( __( 'Use this type for making next column filled with <a href="%s" target="_blank">full-height background</a>, we recommend you to add aside Order Total widget', 'xstore-core' ), 'https://prnt.sc/jS61G4_OQnK3' ),
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

        $this->add_control(
            'advanced_labels',
            [
                'label' => esc_html__('Advanced Labels', 'xstore-core'),
                'description' => esc_html__( 'Enable this option to have aesthetically pleasing animated labels when filling out forms on the checkout page.', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'billing_details_section',
            [
                'label' => $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ? esc_html__( 'Billing and Shipping Details', 'xstore-core' ) : esc_html__( 'Billing Details', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'billing_details_section_title',
            [
                'label' => esc_html__( 'Section Title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ? esc_html__( 'Billing and Shipping Details', 'xstore-core' ) : esc_html__( 'Billing Details', 'xstore-core' ),
                'default' => $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ? esc_html__( 'Billing and Shipping Details', 'xstore-core' ) : esc_html__( 'Billing Details', 'xstore-core' ),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_responsive_control(
            'billing_details_alignment',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--billing-details-title-alignment: {{VALUE}};',
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->start_controls_tabs( 'tabs');

        $repeater->start_controls_tab( 'content_tab', [
            'label' => esc_html__( 'Content', 'xstore-core' ),
        ] );

        $repeater->add_control(
            'label',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'repeater_state' => '',
                ],
            ]
        );

        $repeater->add_control(
            'placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'repeater_state' => '',
                ],
            ]
        );

        $repeater->add_control(
            'stretched',
            [
                'label' => esc_html__('Full-width row', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'advanced_tab', [
            'label' => esc_html__( 'Advanced', 'xstore-core' ),
        ] );

        $repeater->add_control(
            'default',
            [
                'label' => esc_html__( 'Default Value', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'repeater_state' => '',
                ],
            ]
        );


        $repeater->add_control(
            'locale_notice',
            [
                'raw' => __( 'Note: This content cannot be changed due to local regulations.', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    'repeater_state' => 'locale',
                ],
            ]
        );

        $repeater->add_control(
            'from_billing_notice',
            [
                'raw' => __( 'Note: This label and placeholder are taken from the Billing section. You can change it there.', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    'repeater_state' => 'from_billing',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $repeater->add_control(
            'repeater_state',
            [
                'label' => esc_html__( 'Repeater State - hidden', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
            ]
        );

        $this->add_control(
            'billing_details_form_fields',
            [
                'label' => esc_html__( 'Form Items', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'item_actions' => [
                    'add' => false,
                    'duplicate' => false,
                    'remove' => false,
                    'sort' => true,
                ],
                'default' => $this->get_billing_field_defaults(),
                'title_field' => '{{{ label }}}',
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
            'section_label_style',
            [
                'label'                 => __( 'Labels', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_label_colors' );

        $this->start_controls_tab(
            'tab_label_color_normal',
            [
                'label'                 => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce-billing-fields label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_label_color_active',
            [
                'label'                 => __( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_color_active',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} #payment .payment_methods input[type=radio]:checked+label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'label_typography',
                'selector'              => '{{WRAPPER}} .woocommerce-billing-fields label',
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
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

        if ( !!$settings['advanced_labels'] ) {
            wp_enqueue_script( 'cart_checkout_advanced_labels' );
            add_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            add_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }

        add_filter( 'woocommerce_form_field_args', [ $this, 'modify_form_field' ], 70, 3 );
        add_filter('woocommerce_billing_fields', [$this, 'sorting_address_fields'], 90, 1);
        add_filter('woocommerce_billing_fields', [$this, 'modify_address_fields_classes'], 110, 1);
//        add_filter('woocommerce_shipping_fields', [$this, 'sorting_address_fields'], 90, 1);

        ?>
        <div class="woocommerce<?php if ( $settings['design_type'] == 'separated' ) echo ' design-styled-part'; ?>">
                <?php
                    WC()->checkout()->checkout_form_billing();
                ?>
        </div>
        <?php

//        remove_filter('woocommerce_shipping_fields', [$this, 'sorting_address_fields'], 90, 1);
        remove_filter('woocommerce_billing_fields', [$this, 'modify_address_fields_classes'], 110, 1);
        remove_filter('woocommerce_billing_fields', [$this, 'sorting_address_fields'], 90, 1);
        remove_filter( 'woocommerce_form_field_args', [ $this, 'modify_form_field' ], 70, 3 );

        if ( !!$settings['advanced_labels'] ) {
            remove_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            remove_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }

        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <style>
                .elementor-element-<?php echo $this->get_id(); ?> select {
                    width: 100%;
                }
            </style>
            <script>
                jQuery(document).ready(function ($) {
                    if ( etTheme.cart_checkout_advanced_labels !== undefined )
                        etTheme.cart_checkout_advanced_labels();
                });
            </script>
            <?php
        }
	}

    public function modify_address_fields_classes($address_fields) {
        $sorted_fields = $this->get_reformatted_form_fields();
        // Sort each of the fields based on priority.
        uasort( $address_fields, 'wc_checkout_fields_uasort_comparison' );
        foreach ($address_fields as $address_field_key => $address_field) {
            if (isset($sorted_fields[$address_field_key])) {
                if ( !!$address_fields[$address_field_key]['et_stretched'] ) {
                    $last_row = array_search('form-row-last', $address_fields[$address_field_key]['class']);
                    $first_row = array_search('form-row-first', $address_fields[$address_field_key]['class']);
                    if ( $last_row !== false )
                        unset($address_fields[$address_field_key]['class'][$last_row]);
                    if ( $first_row !== false )
                        unset($address_fields[$address_field_key]['class'][$first_row]);
                    $address_fields[$address_field_key]['class'][] = 'form-row-wide';
                }
                else {
                    $previous_address_field_key = $this->get_previous_address_field($address_fields, $address_field_key);
                        $last_row = array_search('form-row-last', $address_fields[$address_field_key]['class']);
                        $first_row = array_search('form-row-first', $address_fields[$address_field_key]['class']);
                        $wide_row = array_search('form-row-wide', $address_fields[$address_field_key]['class']);
                        if ( $last_row !== false )
                            unset($address_fields[$address_field_key]['class'][$last_row]);
                        if ( $first_row !== false )
                            unset($address_fields[$address_field_key]['class'][$first_row]);
                        if ( $wide_row !== false )
                            unset($address_fields[$address_field_key]['class'][$wide_row]);

                    if ( $previous_address_field_key > -1 ) {
                        // $last_previous_row = array_search('form-row-last', $address_fields[$previous_address_field_key]['class']);
                        $first_previous_row = array_search('form-row-first', $address_fields[$previous_address_field_key]['class']);
                        if ($first_previous_row !== false)
                            $address_fields[$address_field_key]['class'][] = 'form-row-last';
                        else
                            $address_fields[$address_field_key]['class'][] = 'form-row-first';
                    }
                    else {
                        $address_fields[$address_field_key]['class'][] = 'form-row-first';
                    }
                }
            }
        }
        return $address_fields;
    }
    public function sorting_address_fields($address_fields)
    {
        $sorted_fields = $this->get_reformatted_form_fields();
        foreach ($address_fields as $address_field_key => $address_field) {
            if (isset($sorted_fields[$address_field_key])) {
                if (isset($sorted_fields[$address_field_key]['priority'])) {
                    $address_fields[$address_field_key]['priority'] = $sorted_fields[$address_field_key]['priority'];
                }
                $address_fields[$address_field_key]['et_stretched'] = $sorted_fields[$address_field_key]['stretched'];
            }
        }
        return $address_fields;
    }

    private function get_previous_address_field($address_fields, $current) {
        $keys = array_keys($address_fields);
        $keyPos = array_flip($keys);
        // $values = array_values($address_fields);

        $prevKeyPos = $keyPos[$current]-1;
        // echo $values[$keyPos[$current]-1]; // returns previous element's value: array('c','d')
        return $prevKeyPos > -1 ? $keys[$keyPos[$current]-1] : -1; // returns previous element's key: 34
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

    /**
     * Modify Form Field.
     *
     * WooCommerce filter is used to apply widget settings to the Checkout forms address fields
     * from the Billing and Shipping Details widget sections, e.g. label, placeholder, default.
     *
     * @since 5.2.0
     *
     * @param array $args
     * @param string $key
     * @param string $value
     * @return array
     */
    public function modify_form_field( $args, $key, $value ) {
        $reformatted_form_fields = $this->get_reformatted_form_fields();
        // Check if we need to modify the args of this form field.
        if ( isset( $reformatted_form_fields[ $key ] ) ) {
            $apply_fields = [
                'label',
                'placeholder',
                'default',
                'priority'
            ];

            foreach ( $apply_fields as $field ) {
                if ( ! empty( $reformatted_form_fields[ $key ][ $field ] ) ) {
                    $args[ $field ] = $reformatted_form_fields[ $key ][ $field ];
                }
            }
        }

        return $args;
    }

    /**
     * Get Reformatted Form Fields.
     *
     * Combines the 3 relevant repeater settings arrays into a one level deep associative array
     * with the keys that match those that WooCommerce uses for its form fields.
     *
     * The result is cached so the conversion only ever happens once.
     *
     * @since 5.2.0
     *
     * @return array
     */
    private function get_reformatted_form_fields() {
        if ( ! isset( $this->reformatted_form_fields ) ) {
            $instance = $this->get_settings_for_display();

            // Reformat form repeater field into one usable array.
            $repeater_fields = [
                'billing_details_form_fields',
                'shipping_details_form_fields',
                'additional_information_form_fields',
            ];

            $this->reformatted_form_fields = [];

            // Apply other modifications to inputs.
            foreach ( $repeater_fields as $repeater_field ) {
                if ( isset( $instance[ $repeater_field ] ) ) {
                    foreach ( $instance[ $repeater_field ] as $item_index => $item ) {
                        if ( ! isset( $item['field_key'] ) ) {
                            continue;
                        }
                        $item['priority'] = ($item_index*10);
                        $this->reformatted_form_fields[ $item['field_key'] ] = $item;
                    }
                }
            }
        }

        return $this->reformatted_form_fields;
    }

    /**
     * Get Billing Field Defaults
     *
     * Get defaults used for the billing details repeater control.
     *
     * @since 5.2.0
     *
     * @return array
     */
    private function get_billing_field_defaults() {
        $fields = [
            'billing_first_name' => [
                'label' => esc_html__( 'First Name', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => false,
            ],
            'billing_last_name' => [
                'label' => esc_html__( 'Last Name', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => false,
            ],
            'billing_company' => [
                'label' => esc_html__( 'Company Name', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => 'yes',
            ],
            'billing_country' => [
                'label' => esc_html__( 'Country / Region', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'billing_address_1' => [
                'label' => esc_html__( 'Street Address', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'billing_postcode' => [
                'label' => esc_html__( 'Post Code', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'billing_city' => [
                'label' => esc_html__( 'Town / City', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'billing_state' => [
                'label' => esc_html__( 'State', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'billing_phone' => [
                'label' => esc_html__( 'Phone', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => 'yes',
            ],
            'billing_email' => [
                'label' => esc_html__( 'Email Address', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => 'yes',
            ],
        ];
        
        $fields = apply_filters('etheme_billing_details_billing_fields', $fields);

        return $this->reformat_address_field_defaults( $fields );
    }

    /**
     * Get Shipping Field Defaults
     *
     * Get defaults used for the shipping details repeater control.
     *
     * @since 5.2.0
     *
     * @return array
     */
    private function get_shipping_field_defaults() {
        $fields = [
            'shipping_first_name' => [
                'label' => esc_html__( 'First Name', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => false,
            ],
            'shipping_last_name' => [
                'label' => esc_html__( 'Last Name', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => false,
            ],
            'shipping_company' => [
                'label' => esc_html__( 'Company Name', 'xstore-core' ),
                'repeater_state' => '',
                'stretched' => 'yes',
            ],
            'shipping_country' => [
                'label' => esc_html__( 'Country / Region', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'shipping_address_1' => [
                'label' => esc_html__( 'Street Address', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'shipping_postcode' => [
                'label' => esc_html__( 'Post Code', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'shipping_city' => [
                'label' => esc_html__( 'Town / City', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
            'shipping_state' => [
                'label' => esc_html__( 'State', 'xstore-core' ),
                'repeater_state' => 'locale',
                'stretched' => 'yes',
            ],
        ];

        $fields = apply_filters('etheme_billing_details_shipping_fields', $fields);

        return $this->reformat_address_field_defaults( $fields );
    }

    /**
     * Reformat Address Field Defaults
     *
     * Used with the `get_..._field_defaults()` methods.
     * Takes the address array and converts it into the format expected by the repeater controls.
     *
     * @since 5.2.0
     *
     * @param $address
     * @return array
     */
    private function reformat_address_field_defaults( $address ) {
        $defaults = [];
        foreach ( $address as $key => $value ) {
            $defaults[] = [
                'field_key' => $key,
                'field_label' => $value['label'],
                'label' => $value['label'],
                'placeholder' => $value['label'],
                'repeater_state' => $value['repeater_state'],
                'stretched' => $value['stretched'],
            ];
        }

        return $defaults;
    }

    /**
     * Is WooCommerce Feature Active.
     *
     * Checks whether a specific WooCommerce feature is active. These checks can sometimes look at multiple WooCommerce
     * settings at once so this simplifies and centralizes the checking.
     *
     * @since 5.2.0
     *
     * @param string $feature
     * @return bool
     */
    protected function is_wc_feature_active( $feature ) {
        switch ( $feature ) {
            case 'checkout_login_reminder':
                if (self::$checkout_login_reminder_feature_status != null)
                    return self::$checkout_login_reminder_feature_status;
                self::$checkout_login_reminder_feature_status = 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' );
                return self::$checkout_login_reminder_feature_status;
            case 'shipping':
                if (self::$shipping_feature_status != null)
                    return self::$shipping_feature_status;
                if ( class_exists( 'WC_Shipping_Zones' ) ) {
                    $all_zones = \WC_Shipping_Zones::get_zones();
                    self::$shipping_feature_status = count( $all_zones ) > 0;
                }
                return self::$shipping_feature_status;
                break;
            case 'coupons':
                if (self::$coupons_feature_status != null)
                    return self::$coupons_feature_status;
                self::$coupons_feature_status = function_exists( 'wc_coupons_enabled' ) && wc_coupons_enabled();
                return self::$coupons_feature_status;
            case 'signup_and_login_from_checkout':
                if (self::$signup_and_login_from_checkout_status != null)
                    return self::$signup_and_login_from_checkout_status;
                self::$signup_and_login_from_checkout_status = 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' );
                return self::$signup_and_login_from_checkout_status;
            case 'ship_to_billing_address_only':
                if (self::$ship_to_billing_address_only_feature_status != null)
                    return self::$ship_to_billing_address_only_feature_status;
                self::$ship_to_billing_address_only_feature_status = wc_ship_to_billing_address_only();
                return self::$ship_to_billing_address_only_feature_status;
        }

        return false;
    }

}
