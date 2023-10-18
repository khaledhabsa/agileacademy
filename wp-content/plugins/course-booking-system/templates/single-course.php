<div id="ajax"></div>
<div id="ajax-loader" class="loader"><div></div><div></div><div></div></div>

<?php if ( isset( $_GET['message'] ) && $_GET['message'] == 'purchase' ) : ?>
	<div class="woocommerce-message">
		<?php _e( 'Thank you for your purchase. Your card was successfully redeemed. You can now book appointments.', 'course-booking-system' ); ?><a href="<?= get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>">» <?= get_the_title( get_option( 'woocommerce_myaccount_page_id' ) ) ?></a>
	</div>
<?php endif; ?>

<div id="course" class="entry-content course">

	<?php
	if ( file_exists( plugin_dir_path( __FILE__ ) . '../includes/ajax/single-course.php' ) )
		require plugin_dir_path( __FILE__ ) . '../includes/ajax/single-course.php';
	?>

</div>
<div id="course-loader" class="loader"><div></div><div></div><div></div></div>