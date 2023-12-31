<?php
/**
 * The template created for displaying footer styling options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'footer-styling' => array(
			'name'       => 'footer-styling',
			'title'      => esc_html__( 'Footer styling', 'xstore' ),
			'panel'      => 'footer',
			'icon'       => 'dashicons-admin-customizer',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/footer-styling' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $text_color_scheme2, $paddings_empty, $padding_labels, $padding_descriptions, $border_styles ) {
	$args = array();
	// Array of fields
	$args = array(
		
		'footer_color' => array(
			'name'        => 'footer_color',
			'type'        => 'select',
			'settings'    => 'footer_color',
			'label'       => esc_html__( 'Text color scheme', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the color scheme for the footer.', 'xstore' ),
			'section'     => 'footer-styling',
			'default'     => 'dark',
			'choices'     => $text_color_scheme2,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.footer',
					'function' => 'toggleClass',
					'class'    => 'text-color-dark',
					'value'    => 'dark'
				),
				array(
					'element'  => '.footer',
					'function' => 'toggleClass',
					'class'    => 'text-color-light',
					'value'    => 'light'
				),
			),
		),
		
		'footer-links' => array(
			'name'      => 'footer-links',
			'type'      => 'multicolor',
			'settings'  => 'footer-links',
			'label'     => esc_html__( 'Link colors', 'xstore' ),
			'tooltip' => esc_html__('This controls the colors of the links in the footer.', 'xstore'),
			'section'   => 'footer-styling',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
				'active'  => esc_html__( 'Active', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
				'active'  => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'choice'   => 'regular',
					'element'  => '.template-container .template-content .footer a, .template-container .template-content .footer .vc_wp_posts .widget_recent_entries li a',
					'property' => 'color',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'choice'   => 'hover',
					'element'  => '.template-container .template-content .footer a:hover, .template-container .template-content .footer .vc_wp_posts .widget_recent_entries li a:hover',
					'property' => 'color',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'choice'   => 'active',
					'element'  => '.template-container .template-content .footer a:active, .template-container .template-content .footer .vc_wp_posts .widget_recent_entries li a:active',
					'property' => 'color',
				),
			),
		),
		
		'footer_bg_color' => array(
			'name'        => 'footer_bg_color',
			'type'        => 'background',
			'settings'    => 'footer_bg_color',
			'label'       => esc_html__( 'Background', 'xstore' ),
			'tooltip' => esc_html__( 'This controls the style of the background in the footer area.', 'xstore' ),
			'section'     => 'footer-styling',
			'default'     => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => '',
				'background-position'   => '',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => 'footer.footer, [data-mode="dark"] .footer',
				),
			),
		),
		
		'footer_padding' => array(
			'name'        => 'footer_padding',
			'type'        => 'dimensions',
			'settings'    => 'footer_padding',
			'label'       => esc_html__( 'Padding', 'xstore' ),
			'tooltip' => esc_html__( 'Set the padding for the footer area. Leave it blank to use the default values.', 'xstore' ),
			'section'     => 'footer-styling',
			'default'     => $paddings_empty,
			'choices'     => array(
				'labels' => $padding_labels,
                'descriptions' => $padding_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.footer',
				),
				array(
					'choice'   => 'padding-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'footer.footer:after',
					'property' => 'top'
				)
			),
		),
		
		'footer_border_width' => array(
			'name'        => 'footer_border_width',
			'type'        => 'slider',
			'settings'    => 'footer_border_width',
			'label'       => esc_html__( 'Border bottom width', 'xstore' ),
			'tooltip' => esc_html__( 'This controls border bottom width of the footer area', 'xstore' ),
			'section'     => 'footer-styling',
			'default'     => 1,
			'choices'     => array(
				'min'  => 0,
				'max'  => 10,
				'step' => 1,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'footer.footer:after',
					'property' => 'border-bottom-width',
					'units'    => 'px'
				),
			),
		),
		
		'footer_border_style' => array(
			'name'        => 'footer_border_style',
			'type'        => 'select',
			'settings'    => 'footer_border_style',
			'label'       => esc_html__( 'Border bottom style', 'xstore' ),
			'tooltip' => esc_html__( 'This controls border bottom style of the footer area', 'xstore' ),
			'section'     => 'footer-styling',
			'default'     => 'solid',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'footer.footer:after',
					'property' => 'border-bottom-style'
				),
			),
		),
		
		'footer_border_color' => array(
			'name'        => 'footer_border_color',
			'type'        => 'color',
			'settings'    => 'footer_border_color',
			'label'       => esc_html__( 'Border bottom color', 'xstore' ),
			'tooltip' => esc_html__( 'This controls border bottom color of the footer area', 'xstore' ),
			'section'     => 'footer-styling',
			'default'     => '#e1e1e1',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'footer.footer:after',
					'property' => 'border-bottom-color'
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );