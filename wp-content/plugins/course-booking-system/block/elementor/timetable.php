<?php
class Elementor_Timetable extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cbs_timetable';
	}

	public function get_title() {
		return esc_html__( 'Timetable', 'course-booking-system' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'course', 'booking', 'system', 'timetable', 'schedule' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Timetable Attributes', 'course-booking-system' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT
			]
		);

		$options = [];
		$terms = get_terms([
			'taxonomy' => 'course_category',
			'hide_empty' => false
		]);
		foreach ( $terms as $term ) :
			$options[$term->term_id] = $term->name;
		endforeach;

		$this->add_control(
			'category',
			[
				'label' => esc_html__( 'Course Category', 'course-booking-system' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'default' => '',
				'options' => $options
			]
		);

		$this->add_control(
			'design',
			[
				'label' => esc_html__( 'Timetable Design', 'course-booking-system' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'course-booking-system' ),
					'divided' => esc_html__( 'Divided', 'course-booking-system' ),
					'list' => esc_html__( 'List', 'course-booking-system' ),
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		echo cbs_shortcode_timetable( $atts );
	}
}