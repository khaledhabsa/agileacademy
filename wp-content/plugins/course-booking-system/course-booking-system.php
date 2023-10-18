<?php
/**
 * Course Booking System
 *
 * @package           CBS
 * @author            ComMotion
 * @copyright         2023 ComMotion
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Course Booking System
 * Plugin URI: https://commotion.online/individuelles-kurs-online-buchungssystem/
 * Description: Individual course booking system for specific needs. Works perfectly with WooCommerce.
 * Network: true
 * Version: 5.1.4
 * Requires at least: 5.5
 * Requires PHP: 7.0
 * WC requires at least: 5.7.0
 * WC tested up to: 8.2.1
 * Author: ComMotion
 * Author URI: https://commotion.online/
 * Text Domain: course-booking-system
 */

defined( 'ABSPATH' ) || exit;

global $cbs_db_version;
$cbs_db_version = '5.0';

$plugin_start = new course_booking_system();
class course_booking_system {
	public function __construct() {
		$this->include_all();

		add_action( 'init', array( $this, 'register_course_taxonomy' ) );
		add_action( 'init', array( $this, 'register_course_post_type' ) );
		add_filter( 'the_content', array( $this, 'append_course_post_meta' ) );
		add_filter( 'get_the_archive_title', array( $this, 'course_category_title' ) );
		add_filter( 'get_the_archive_description', array( $this, 'remove_course_archive_description' ) );
		add_action( 'loop_start', array( $this, 'prepend_course_archive' ) );
		add_action( 'loop_end', array( $this, 'append_course_archive' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts_no_limit' ) );
		add_action( 'wp_footer', array( $this, 'livesearch' ) );

		// register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		// register_deactivation_hook( __FILE__, array( $this, 'on_deactivation' ) );
		// register_uninstall_hook( __FILE__, array( $this, 'on_uninstall' ) );
		// add_action( 'wpmu_new_blog', array( $this, 'on_create_blog' ), 10, 6 );
		// add_filter( 'wpmu_drop_tables', array( $this, 'on_delete_blog' ) );
	}

	public static function include_all() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/assets.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/ajax.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/cron.php';

		require_once plugin_dir_path( __FILE__ ) . 'includes/admin/user.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/admin/single.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/admin/settings.php';

		require_once plugin_dir_path( __FILE__ ) . 'includes/db/update.php';

		require_once plugin_dir_path( __FILE__ ) . 'block/timetable/index.php';

		if ( in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
			require_once plugin_dir_path( __FILE__ ) . 'block/elementor/index.php';

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'includes/woocommerce/woocommerce.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/woocommerce/product-type.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/woocommerce/payment-gateway.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/woocommerce/myaccount/dashboard.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/woocommerce/myaccount/delete.php';
		}
	}

	public function register_course_taxonomy() {
		$labels = array(
			'name'              => _x( 'Course Categories', 'taxonomy general name', 'course-booking-system' ),
			'singular_name'     => _x( 'Course Category', 'taxonomy singular name', 'course-booking-system' ),
			'search_items'      => __( 'Search Course Categories', 'course-booking-system' ),
			'all_items'         => __( 'All Course Categories', 'course-booking-system' ),
			'parent_item'       => __( 'Parent Course Category', 'course-booking-system' ),
			'parent_item_colon' => __( 'Parent Course Category:', 'course-booking-system' ),
			'edit_item'         => __( 'Edit', 'course-booking-system' ), 
			'update_item'       => __( 'Update', 'course-booking-system' ),
			'add_new_item'      => __( 'Add New', 'course-booking-system' ),
			'new_item_name'     => __( 'New Course Category Name', 'course-booking-system' ),
			'menu_name'         => __( 'Course Categories', 'course-booking-system' ),
		);

		register_taxonomy( 'course_category', array( 'course' ), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'course_category' ),
		));
	}

	public function register_course_post_type() {
		$labels = array(
			'name'               => _x( 'Courses', 'post type general name', 'course-booking-system' ),
			'singular_name'      => _x( 'Course', 'post type singular name', 'course-booking-system' ),
			'add_new'            => __( 'Add New', 'course-booking-system' ),
			'add_new_item'       => __( 'Add New', 'course-booking-system' ),
			'edit_item'          => __( 'Edit', 'course-booking-system' ),
			'new_item'           => __( 'Add New', 'course-booking-system' ),
			'all_items'          => __( 'All courses', 'course-booking-system' ),
			'view_item'          => __( 'View', 'course-booking-system' ),
			'search_items'       => __( 'Search Courses', 'course-booking-system' ),
			'not_found'          => __( 'No courses found.', 'course-booking-system' ),
			'not_found_in_trash' => __( 'No courses found found in Trash.', 'course-booking-system' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Courses', 'course-booking-system' ),
		);

		$args = array(
			/* 'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-editor-table',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'capability_type'     => 'post',
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions' ),
			'has_archive'         => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'course' ),
			'show_in_rest'        => true, */

			'label'               => __( 'Course', 'course-booking-system' ),
			'description'         => __( 'Courses', 'course-booking-system' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			'taxonomies'          => array( 'course_category' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-editor-table',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);

		register_post_type( 'course', $args );
	}

	public function append_course_post_meta( $content ) {
		if ( is_admin() || !is_singular() || !in_the_loop() || !is_main_query() )
			return $content;

		global $post;
		remove_filter( 'the_content', array( $this, 'append_course_post_meta' ) ); // run only once

		switch ( $post->post_type ) {
			case 'course':
				if ( file_exists( plugin_dir_path(  __FILE__  ).'templates/single-course.php' ) ) :
					echo $content;
					require plugin_dir_path( __FILE__ ).'templates/single-course.php';
				endif;
				return;
			break;
		}

		return $content;
	}

	public function course_category_title( $title ) {
		if ( is_post_type_archive( 'course' ) ) :
			$date_format = get_option( 'date_format' );

			if ( isset( $_GET['weekday'] ) )
				$day = intval( $_GET['weekday'] );

			if ( isset( $_GET['date'] ) ) :
				$date = htmlspecialchars( $_REQUEST['date'] );
			else :
				$date = date( 'Y-m-d', strtotime( 'Sunday +'.$day.' days' ) );
			endif;

			if ( isset( $day ) && $day <= 7 ) :
				$title = date_i18n( 'l', strtotime( 'Sunday +'.$day.' days' ) ).', '.date_i18n( $date_format, strtotime( $date ) );
			elseif ( isset( $date ) ) :
				$title = date_i18n( $date_format, strtotime( $date ) );
			endif;
		endif;

		return $title;
	}

	public function remove_course_archive_description( $description ) {
		global $post;
		remove_filter( 'get_the_archive_description', array( $this, 'append_course_archive' ) ); // run only once

		switch ( $post->post_type ) {
			case 'course':
				return;
			break;
		}

		return $description;
	}

	public function prepend_course_archive( $query ) {
		if ( array_key_exists( 'post_type', $query->query ) && $query->query['post_type'] == 'course' && isset( $_GET['weekday'] ) && file_exists( plugin_dir_path( __FILE__ ).'templates/single-weekday.php' ) ) :
			require plugin_dir_path( __FILE__ ).'templates/single-weekday.php';

			echo '<div style="display: none;">'; // Container to hide normal archive posts
		endif;
	}

	public function append_course_archive( $query ) {
		if ( array_key_exists( 'post_type', $query->query ) && $query->query['post_type'] == 'course' && isset( $_GET['weekday'] ) && file_exists( plugin_dir_path( __FILE__ ).'templates/single-weekday.php' ) )
			echo '</div>';
	}

	public function pre_get_posts_no_limit( $query ) {
		if ( !is_admin() && $query->get( 'post_type' ) == 'course' ) {
			$query->set( 'posts_per_page', -1 );
		}
	}

	public function livesearch() {
		if ( ( is_singular( 'course' ) || is_post_type_archive( 'course' ) ) && ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) :
			// Variables
			$course_id = !empty( $_REQUEST['course_id'] ) ? intval( $_REQUEST['course_id'] ) : 0;
			if ( is_singular( 'course' ) ) {
				$courses = cbs_get_courses( array(
					'id' => $course_id
				) );
				$post_id = !empty( $post->ID ) ? $post->ID : reset( $courses )->post_id;

				$free        = get_post_meta( $post_id, 'free', true );
				$price_level = get_post_meta( $post_id, 'price_level', true );
			}

			// Options
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );

			$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );

			$users = get_users();
			?>
			<ul id="livesearch" class="livesearch">
				<?php
				foreach ( $users AS $user ) :
					if ( is_singular( 'course' ) ) {
						if ( $price_level == 5 ) {
							$card_name = 'card_5';
							$expire_name = 'expire_5';

							$flat_name = 'flat_5';
							$flat_expire_name = 'flat_expire_5';

							$card = get_the_author_meta( $card_name, $user->ID );
							$expire = get_the_author_meta( $expire_name, $user->ID );
						} else if ( $price_level == 4 ) {
							$card_name = 'card_4';
							$expire_name = 'expire_4';

							$flat_name = 'flat_4';
							$flat_expire_name = 'flat_expire_4';

							$card = get_the_author_meta( $card_name, $user->ID );
							$expire = get_the_author_meta( $expire_name, $user->ID );

							if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_5';
								$expire_name = 'expire_5';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							}
						} else if ( $price_level == 3 ) {
							$card_name = 'card_3';
							$expire_name = 'expire_3';

							$flat_name = 'flat_3';
							$flat_expire_name = 'flat_expire_3';

							$card = get_the_author_meta( $card_name, $user->ID );
							$expire = get_the_author_meta( $expire_name, $user->ID );

							if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_4';
								$expire_name = 'expire_4';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							} if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_5';
								$expire_name = 'expire_5';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							}
						} else if ( $price_level == 2 ) {
							$card_name = 'card_2';
							$expire_name = 'expire_2';

							$flat_name = 'flat_2';
							$flat_expire_name = 'flat_expire_2';

							$card = get_the_author_meta( $card_name, $user->ID );
							$expire = get_the_author_meta( $expire_name, $user->ID );

							if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_3';
								$expire_name = 'expire_3';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							} if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_4';
								$expire_name = 'expire_4';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							} if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_5';
								$expire_name = 'expire_5';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							}
						} else {
							$card_name = 'card';
							$expire_name = 'expire';

							$flat_name = 'flat';
							$flat_expire_name = 'flat_expire';

							$card = get_the_author_meta( $card_name, $user->ID );
							$expire = get_the_author_meta( $expire_name, $user->ID );

							if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_2';
								$expire_name = 'expire_2';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							} if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_3';
								$expire_name = 'expire_3';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							} if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_4';
								$expire_name = 'expire_4';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							} if ( $price_level_for_lower_course && ( $card <= 0 /* || date( 'Y-m-d', strtotime( $expire ) ) < $date */ ) ) {
								$card_name = 'card_5';
								$expire_name = 'expire_5';

								$card = get_the_author_meta( $card_name, $user->ID );
								$expire = get_the_author_meta( $expire_name, $user->ID );
							}
						}

						$flat = get_the_author_meta( $flat_name, $user->ID );
						$flat_expire = get_the_author_meta( $flat_expire_name, $user->ID );
					}

					echo '<li class="user-'.$user->ID.'" data-user="'.$user->ID.'">';
						if ( is_post_type_archive( 'course' ) ) {
							echo '<a href="#" class="action-booking" data-id="'.$course_id.'" data-date="" data-user="'.$user->ID.'">'.$user->first_name.' '.$user->last_name.'</a>';
						} else if ( $free ) {
							echo '<a href="#" class="action-booking" data-id="'.$course_id.'" data-date="" data-user="'.$user->ID.'">'.esc_html( $user->first_name ).' '.esc_html( $user->last_name ).'</a>';
						} else if ( $flat /* && $flat_expire >= $date */ ) {
							echo '<a href="#" class="action-booking" data-id="'.$course_id.'" data-date="" data-user="'.$user->ID.'" data-email="'.esc_html( $user->user_email ).'">'.esc_html( $user->first_name ).' '.esc_html( $user->last_name ).'<br>(<strong>'.__( 'Flatrate', 'course-booking-system' ).'</strong><span class="expiry"> '.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire ) ).'</span>)</a>';
						} else if ( $card > 0 /* && $expire >= $date */ ) {
							echo '<a href="#" class="action-booking" data-id="'.$course_id.'" data-date="" data-user="'.$user->ID.'" data-email="'.esc_html( $user->user_email ).'">'.esc_html( $user->first_name ).' '.esc_html( $user->last_name ).'<br>(<strong>'.$card.'</strong><span class="expiry"> '.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire ) ).'</span>)</a>';
						} else {
							echo '<a href="#">'.esc_html( $user->first_name ).' '.esc_html( $user->last_name ).' - '.__( 'No valid card available.', 'course-booking-system' ).'</a>';
						}
					echo '</li>';
					?>
				<?php endforeach; ?>

				<li class="user-new" data-user="new"><a href="<?= admin_url( 'user-new.php' ) ?>" target="_blank">+ <?php _e( 'Add new customer', 'course-booking-system' ); ?></a></li>
			</ul>

			<ul id="livesearch-waitlist" class="livesearch">
				<?php
				foreach ( $users AS $user ) :
					echo '<li class="user-'.$user->ID.'" data-user="'.$user->ID.'"><a href="#" class="action-waitlist" data-id="'.$course_id.'" data-date="" data-user="'.$user->ID.'">'.esc_html( $user->first_name ).' '.esc_html( $user->last_name ).'</a></li>';
				endforeach; ?>

				<li class="user-new" data-user="new"><a href="<?= admin_url( 'user-new.php' ) ?>" target="_blank">+ <?php _e( 'Add new customer', 'course-booking-system' ); ?></a></li>
			</ul>
		<?php
		endif;
	}

	/* public static function on_activation() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/db/create.php';
		flush_rewrite_rules();
	}

	public static function on_deactivation() {
		flush_rewrite_rules();
	}

	public static function on_uninstall() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/db/drop.php';
	}

	public static function on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( is_plugin_active_for_network( dirname( plugin_basename( __FILE__ ) ) . '/' . dirname( plugin_basename( __FILE__ ) ) . '.php' ) ) {
			switch_to_blog($blog_id);

			require_once plugin_dir_path( __FILE__ ) . 'includes/db/create.php';

			restore_current_blog();
		}
	}

	public static function on_delete_blog($tables) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/db/drop.php';
	} */
}

function cbs_on_activation() {
	if ( is_multisite() ) {
		$sites = get_sites();
		if ( is_plugin_active_for_network( __FILE__ ) ) {
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );

				require plugin_dir_path( __FILE__ ) . 'includes/db/create.php';

				restore_current_blog();
			}
		} else {
			require plugin_dir_path( __FILE__ ) . 'includes/db/create.php';
		}
	} else {
		require plugin_dir_path( __FILE__ ) . 'includes/db/create.php';
	}

	// Set important WooCommerce settings automatically
	update_option( 'woocommerce_file_download_method', 'xsendfile' );
	update_option( 'woocommerce_downloads_require_login', 'yes' );
	update_option( 'woocommerce_enable_guest_checkout', 'no' );
	update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' );

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cbs_on_activation' );

function cbs_on_deactivation() {
	// Remove templates directory
	$directory = get_template_directory().'/mp-timetable/';
	if ( is_dir( $directory ) ) {
		$it = new RecursiveDirectoryIterator( $directory, RecursiveDirectoryIterator::SKIP_DOTS );
		$files = new RecursiveIteratorIterator( $it, RecursiveIteratorIterator::CHILD_FIRST );
		foreach ( $files as $file ) {
		    if ( $file->isDir() ){
		        rmdir( $file->getRealPath() );
		    } else {
		        unlink( $file->getRealPath() );
		    }
		}
		rmdir( $directory );
	}

	flush_rewrite_rules();
	wp_cache_flush();
}
register_deactivation_hook( __FILE__, 'cbs_on_deactivation' );

function cbs_on_uninstall() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/db/drop.php';

	global $wpdb;
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."options` WHERE `option_name` LIKE ('course_booking_system_%')" );

	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_2'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_3'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_start'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_expire'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_course'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_course_2'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_course_3'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_alternate'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_2'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_2'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_3'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_3'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_4'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_4'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_5'" );
	$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_5'" );
}
register_uninstall_hook( __FILE__, 'cbs_on_uninstall' );

function cbs_on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	if ( is_plugin_active_for_network( dirname( plugin_basename( __FILE__ ) ) . '/' . dirname( plugin_basename( __FILE__ ) ) . '.php' ) ) {
		switch_to_blog( $blog_id );

		require_once plugin_dir_path( __FILE__ ) . 'includes/db/create.php';

		restore_current_blog();
	}
}
add_action( 'wpmu_new_blog', 'cbs_on_create_blog', 10, 6 );

function cbs_on_delete_blog( $tables ) {
	include_once plugin_dir_path( __FILE__ ) . 'includes/db/drop.php';
}
add_filter( 'wpmu_drop_tables', 'cbs_on_delete_blog' );

// Check if required plugins are activated
function cbs_plugin_check() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) && !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		add_action( 'admin_notices', 'cbs_recommended_plugin_notice' );
	}
}
add_action( 'admin_init', 'cbs_plugin_check' );

function cbs_recommended_plugin_notice() {
	$action = 'install-plugin';
	$slug = 'woocommerce';
	$url = wp_nonce_url(
		add_query_arg(
			array(
				'action' => $action,
				'plugin' => $slug
			),
			admin_url( 'update.php' )
		),
		$action.'_'.$slug
	);
	?>
	<div class="notice">
		<p><?php _e( 'We recommend the "WooCommerce" plugin for the full scope of the "Course Booking System" plugin. Please install and activate the plugin.', 'course-booking-system' ); ?></p>
		<p><a href="<?= $url ?>" class="button button-primary"><?php _e( 'Install "WooCommerce"', 'course-booking-system' ); ?></a></p>
	</div>
	<?php
}