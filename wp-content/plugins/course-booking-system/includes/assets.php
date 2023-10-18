<?php
function cbs_enqueue_assets() {
	wp_enqueue_style( 'course-booking-system-style', plugins_url( '../assets/css/style.css', __FILE__ ) );
	wp_enqueue_script( 'course-booking-system-script', plugins_url( '../assets/js/script.js', __FILE__ ), array( 'jquery' ), '', true );

	if ( get_option( 'course_booking_system_design' ) == 'modern' ) :
		wp_enqueue_style( 'course-booking-system-modern', plugins_url( '../assets/css/modern.css', __FILE__ ) );
	endif;

	if ( is_single() && get_post_type() == 'course' ) :
		wp_enqueue_style( 'slick-style', plugins_url( '../assets/js/slick/slick.min.css', __FILE__ ) );
		wp_enqueue_style( 'slick-theme-style', plugins_url( '../assets/js/slick/slick-theme.css', __FILE__ ) );
		wp_enqueue_script( 'slick-script', plugins_url( '../assets/js/slick/slick.min.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'single-course-script', plugins_url( '../assets/js/single-course.js', __FILE__ ), array( 'jquery', 'slick-script' ), '', true );
	endif;
}
add_action( 'wp_enqueue_scripts', 'cbs_enqueue_assets', 15 );

function cbs_enqueue_assets_admin() {
	wp_enqueue_style( 'admin-style', plugins_url( '../assets/css/admin.css', __FILE__ ) );
	wp_enqueue_script( 'admin-script', plugins_url( '../assets/js/admin.js', __FILE__ ), array( 'jquery' ), '', true );
}
add_action( 'admin_enqueue_scripts', 'cbs_enqueue_assets_admin', 20 );

function cbs_enqueue_assets_ajax() {
	wp_enqueue_script( 'course-booking-system-ajax', plugins_url( '../assets/js/ajax.js', __FILE__ ), array( 'jquery' ), '', true );

	wp_localize_script( 'course-booking-system-ajax', 'course_booking_system_ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'offset'  => get_option( 'course_booking_system_message_offset' )
		)
	);
}
add_action( 'wp_enqueue_scripts', 'cbs_enqueue_assets_ajax', 25 );
add_action( 'admin_enqueue_scripts', 'cbs_enqueue_assets_ajax', 25 );
add_action( 'cbs_after_archive_course', 'cbs_enqueue_assets_ajax', 25 );