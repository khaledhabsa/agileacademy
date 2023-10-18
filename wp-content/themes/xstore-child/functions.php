<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001 );
function theme_enqueue_styles() {
	etheme_child_styles();
}

add_action( 'wp_footer', function () {

	require_once "custom_html.php";
}, 100 );

add_action( 'woocommerce_thankyou', function( $order_id ){
	wp_redirect( home_url('thank-you'));
});
