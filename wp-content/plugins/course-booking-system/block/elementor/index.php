<?php
function cbs_elementor_widgets( $widgets_manager ) {
	require_once( __DIR__ . '/timetable.php' );

	$widgets_manager->register( new \Elementor_Timetable() );
}
add_action( 'elementor/widgets/register', 'cbs_elementor_widgets' );