<?php
global $wpdb;
$user_id = get_current_user_id();

$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );

$abo_start = get_the_author_meta( 'abo_start', $user_id );
$abo_expire = get_the_author_meta( 'abo_expire', $user_id );
$abo_course = get_the_author_meta( 'abo_course', $user_id );
$abo_course_2 = get_the_author_meta( 'abo_course_2', $user_id );
$abo_course_3 = get_the_author_meta( 'abo_course_3', $user_id );
$abo_alternate = get_the_author_meta( 'abo_alternate', $user_id );
$abo_alternate = explode( ',', $abo_alternate );

$flat = get_the_author_meta( 'flat', $user_id );
$flat_expire = get_the_author_meta( 'flat_expire', $user_id );
$flat_2 = get_the_author_meta( 'flat_2', $user_id );
$flat_expire_2 = get_the_author_meta( 'flat_expire_2', $user_id );
$flat_3 = get_the_author_meta( 'flat_3', $user_id );
$flat_expire_3 = get_the_author_meta( 'flat_expire_3', $user_id );
$flat_4 = get_the_author_meta( 'flat_4', $user_id );
$flat_expire_4 = get_the_author_meta( 'flat_expire_4', $user_id );
$flat_5 = get_the_author_meta( 'flat_5', $user_id );
$flat_expire_5 = get_the_author_meta( 'flat_expire_5', $user_id );

$card = get_the_author_meta( 'card', $user_id );
$expire = get_the_author_meta( 'expire', $user_id );
$card_2 = get_the_author_meta( 'card_2', $user_id );
$expire_2 = get_the_author_meta( 'expire_2', $user_id );
$card_3 = get_the_author_meta( 'card_3', $user_id );
$expire_3 = get_the_author_meta( 'expire_3', $user_id );
$card_4 = get_the_author_meta( 'card_4', $user_id );
$expire_4 = get_the_author_meta( 'expire_4', $user_id );
$card_5 = get_the_author_meta( 'card_5', $user_id );
$expire_5 = get_the_author_meta( 'expire_5', $user_id );

$status = '';
$status_visual = '';
$status_visual_2 = '';
$status_visual_3 = '';
$status_visual_4 = '';
$status_visual_5 = '';

if ( !empty( $abo ) || $abo_expire > date( 'Y-m-d' ) ) {
	if ( !empty( $abo_course ) ) {
		$courses = cbs_get_courses( array(
			'id' => $abo_course
		) );
		foreach ( $courses as $course ) {
			$post_title = $course->post_title;
			$post_id    = $course->post_id;
			$day        = $course->day;
			$start      = $course->start;
			$end        = $course->end;
		}

		$status .= '<p>'.__( 'You have a subscription to the course', 'course-booking-system' ).' "<strong>'.$post_title.', '.cbs_get_weekday( $day ).', '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</strong>"';

		if ( !empty( $abo_course_2 ) ) {
			$courses_2 = cbs_get_courses( array(
				'id' => $abo_course_2
			) );
			foreach ( $courses_2 as $course_2 ) {
				$post_title_2 = $course_2->post_title;
				$post_id_2    = $course_2->post_id;
				$day_2        = $course_2->day;
				$start_2      = $course_2->start;
				$end_2        = $course_2->end;
			}

			$status .= ' '.__( 'and a subscription to the course', 'course-booking-system' ).' "<strong>'.$post_title_2.', '.cbs_get_weekday( $day_2 ).', '.date( $time_format, strtotime( $start_2 ) ).' - '.date( $time_format, strtotime( $end_2 ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</strong>"';
		} if ( !empty( $abo_course_3 ) ) {
			$courses_3 = cbs_get_courses( array(
				'id' => $abo_course_3
			) );
			foreach ( $courses_3 as $course_3 ) {
				$post_title_3 = $course_3->post_title;
				$post_id_3    = $course_3->post_id;
				$day_3        = $course_3->day;
				$start_3      = $course_3->start;
				$end_3        = $course_3->end;
			}

			$status .= ' '.__( 'and a subscription to the course', 'course-booking-system' ).' "<strong>'.$post_title_3.', '.cbs_get_weekday( $day_3 ).', '.date( $time_format, strtotime( $start_3 ) ).' - '.date( $time_format, strtotime( $end_3 ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</strong>"';
		}

		$status .= ', '.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $abo_expire ) ).'.</p>';
	} else {
		$status .= '<p>'.__( 'Thank you. You have a valid subscription. Please select the course of your choice so that this place is reserved for you.', 'course-booking-system' ).'</p>';
	}

	if ( !empty( $flat ) && strtotime( $flat_expire ) > current_time( 'timestamp' ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire ) ).')</span>.</p>';
	} if ( !empty( $flat_2 ) && strtotime( $flat_expire_2 ) > current_time( 'timestamp' ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_2' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_2 ) ).')</span>.</p>';
	} if ( !empty( $flat_3 ) && strtotime( $flat_expire_3 ) > current_time( 'timestamp' ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_3' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_3 ) ).')</span>.</p>';
	} if ( !empty( $flat_4 ) && strtotime( $flat_expire_4 ) > current_time( 'timestamp' ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_4' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_4 ) ).')</span>.</p>';
	} if ( !empty( $flat_5 ) && strtotime( $flat_expire_5 ) > current_time( 'timestamp' ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_5' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_5 ) ).')</span>.</p>';
	}

	if ( !empty( $card ) ) {
		$status_visual = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card.'" max="10"></progress></div>';
	} if ( !empty( $card_2 ) ) {
		$status_visual_2 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_2.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_2' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_2 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_2.'" max="10"></progress></div>';
	} if ( !empty( $card_3 ) ) {
		$status_visual_3 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_3.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_3' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_3 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_3.'" max="10"></progress></div>';
	} if ( !empty( $card_4 ) ) {
		$status_visual_4 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_4.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_4' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_4 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_4.'" max="10"></progress></div>';
	} if ( !empty( $card_5 ) ) {
		$status_visual_5 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_5.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_5' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_5 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_5.'" max="10"></progress></div>';
	}
} else if ( !empty( $flat ) || !empty( $flat_2 ) || !empty( $flat_3 ) || !empty( $flat_4 ) || !empty( $flat_5 ) ) {
	if ( !empty( $flat ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire ) ).')</span>.</p>';
	} if ( !empty( $flat_2 ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_2' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_2 ) ).')</span>.</p>';
	} if ( !empty( $flat_3 ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_3' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_3 ) ).')</span>.</p>';
	} if ( !empty( $flat_4 ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_4' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_4 ) ).')</span>.</p>';
	} if ( !empty( $flat_5 ) ) {
		$status .= '<p>'.__( 'You have', 'course-booking-system' ).' '.__( 'a <strong>flatrate</strong> for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_5' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire_5 ) ).')</span>.</p>';
	}

	if ( !empty( $card ) ) {
		$status_visual = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card.'" max="10"></progress></div>';
	} if ( !empty( $card_2 ) ) {
		$status_visual_2 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_2.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_2' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_2 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_2.'" max="10"></progress></div>';
	} if ( !empty( $card_3 ) ) {
		$status_visual_3 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_3.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_3' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_3 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_3.'" max="10"></progress></div>';
	} if ( !empty( $card_4 ) ) {
		$status_visual_4 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_4.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_4' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_4 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_4.'" max="10"></progress></div>';
	} if ( !empty( $card_5 ) ) {
		$status_visual_5 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_5.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_5' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_5 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_5.'" max="10"></progress></div>';
	}
} else if ( !empty( $card ) || !empty( $card_2 ) || !empty( $card_3 ) || !empty( $card_4 ) || !empty( $card_5 ) ) {
	if ( !empty( $card ) ) {
		$status_visual = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card.'" max="10"></progress></div>';
	} if ( !empty( $card_2 ) ) {
		$status_visual_2 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_2.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_2' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_2 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_2.'" max="10"></progress></div>';
	} if ( !empty( $card_3 ) ) {
		$status_visual_3 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_3.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_3' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_3 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_3.'" max="10"></progress></div>';
	} if ( !empty( $card_4 ) ) {
		$status_visual_4 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_4.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_4' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_4 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_4.'" max="10"></progress></div>';
	} if ( !empty( $card_5 ) ) {
		$status_visual_5 = '<p>'.__( 'You have', 'course-booking-system' ).' <strong>'.$card_5.'</strong> '.__( 'courses left for', 'course-booking-system' ).' '.get_option( 'course_booking_system_price_level_title_5' ).'<span class="expiry"> ('.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire_5 ) ).')</span>.</p><div class="progress-wrapper"><progress value="'.$card_5.'" max="10"></progress></div>';
	}
} else {
	$status = '<p>'.sprintf( __( 'You don\'t have a ticket or a subscription. Please buy a new card in our <a href="%s">Online Shop</a>.', 'course-booking-system' ), get_permalink( wc_get_page_id( 'shop' ) ) ).'</p>';
}

echo $status.$status_visual.$status_visual_2.$status_visual_3.$status_visual_4.$status_visual_5;
