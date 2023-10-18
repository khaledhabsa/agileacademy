<?php
/**
 * BLOCK: Timetable
 *
 * Gutenberg Timetable Block assets.
 *
 * @since   5.0.4
 * @package CBS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the block's assets for the editor.
 *
 * `wp-blocks`: Includes block type registration and related functions.
 * `wp-element`: Includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function cbs_timetable_block() {
    wp_register_script( 'cbs-timetable-block-script', plugins_url( 'block.js', __FILE__ ), array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-block-editor', 'wp-components', 'wp-i18n' ), filemtime( plugin_dir_path( __FILE__ ) . 'block.js' ) );
	wp_register_style( 'cbs-timetable-block-style', plugins_url( 'style.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ) );

    register_block_type( 'course-booking-system/timetable', array(
        'api_version'     => 3,
		'supports'        => array( 'align' => true, 'alignWide' => true ),
        'editor_script'   => 'cbs-timetable-block-script',
		'style'           => 'cbs-timetable-block-style',
        'render_callback' => 'cbs_shortcode_timetable',
        'attributes'      => array(
			'category'    => array(
				'type'    => 'array',
				'default' => array()
			),
			'design'      => array(
				'type'    => 'string',
				'default' => 'default'
			)
		)
    ) );
}
add_action( 'init', 'cbs_timetable_block' );


/* if ( function_exists('register_block_type') )
	new CBS_Timetable_Block();

class CBS_Timetable_Block {
	public function __construct() {
		add_action( 'init', [ $this, 'gutenberg_block_register_block' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'gutenberg_block_editor_scripts' ] );
	}

	public function gutenberg_block_register_block() {
		register_block_type( 'course-booking-system/timtetable', [
			'editor_script'   => 'cbs-timetable-block-script',
			'editor_style'    => 'cbs-timetable-block-editor-style',
			'style'           => 'cbs-timetable-block-style',
			'render_callback' => [ $this, 'cbs_shortcode_timetable' ],
			'attributes'      => [
				'category'    => [
					'type'    => 'array'
				],
				'design'      => [
					'type'    => 'string',
					'default' => 'default'
				]
			]
		] );
	}

	public function gutenberg_block_editor_scripts() {
		wp_enqueue_script( 'cbs-timetable-block-script', plugins_url( 'block.js', __FILE__ ), array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), filemtime( plugin_dir_path( __FILE__ ) . 'block.js' ), true );
		wp_register_style( 'cbs-timetable-block-style', plugins_url( 'style.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ) );
		wp_register_style( 'cbs-timetable-block-editor-style', plugins_url( 'editor.css', __FILE__ ), array( 'wp-edit-blocks' ), filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' ) );
	}
} */
