<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Dashboard.
 *
 * Add admin panel dashboard pages to admin menu.
 * Output dashboard pages.
 *
 * @since   5.0.0
 * @version 1.0.8
 */

class EthemeAdmin{
	/**
	 * Theme name
	 *
	 * @var string
	 */
	protected $theme_name;
	
	/**
	 * Panel page
	 *
	 * @var array
	 */
	protected $page = array();
	
	protected $settingJsConfig = array();
	
	protected static $instance = null;
	
	// ! Main construct/ add actions
	public function main_construct(){
		add_action( 'admin_menu', array( $this, 'et_add_menu_page' ) );
		add_action( 'admin_head', array( $this, 'et_add_menu_page_target') );
		add_action( 'wp_ajax_et_ajax_panel_popup', array($this, 'et_ajax_panel_popup') );
		
        // Enable svg support
		add_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'correct_svg_filetype' ), 10, 5 );
		
		if ( isset($_REQUEST['helper']) && $_REQUEST['helper']){
			$this->require_class($_REQUEST['helper']);
		}
		
		add_action( 'wp_ajax_et_panel_ajax', array($this, 'et_panel_ajax') );

		add_action('wp_ajax_et_close_installation_video', array($this, 'et_close_installation_video'));
		
		$current_theme         = wp_get_theme();
		$this->theme_name      = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
		
		add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
		add_action('admin_init',array($this,'add_page_admin_script'), 1140);
		
		if(!is_child_theme()){
			add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
		}
		
		if ( ! $this->set_page_data() ){
			return;
		}
		
		if (isset($this->page['class']) && ! empty($this->page['class'])){
			$this->require_class($this->page['class']);
		}
		
		// Stas
		$this->init_vars();
	}
	
	public static function add_svg_support( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	
	/**
	 * Correct SVG file uploads to make them pass the WP check.
	 *
	 * WP upload validation relies on the fileinfo PHP extension, which causes inconsistencies.
	 * E.g. json file type is application/json but is reported as text/plain.
	 * ref: https://core.trac.wordpress.org/ticket/45633
	 *
	 * @access public
	 * @since 4.3.4
	 * @param array       $data                      Values for the extension, mime type, and corrected filename.
	 * @param string      $file                      Full path to the file.
	 * @param string      $filename                  The name of the file (may differ from $file due to
	 *                                               $file being in a tmp directory).
	 * @param string[]    $mimes                     Array of mime types keyed by their file extension regex.
	 * @param string|bool $real_mime                 The actual mime type or false if the type cannot be determined.
	 *
	 * @return array
	 */
	public function correct_svg_filetype( $data, $file, $filename, $mimes, $real_mime = false ) {
		
		// If both ext and type are.
		if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
			return $data;
		}
		
		$wp_file_type = wp_check_filetype( $filename, $mimes );
		
		if ( 'svg' === $wp_file_type['ext'] ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		
		return $data;
	}
	
	public function init_vars() {
		$this->settingJsConfig = array(
			'ajaxurl'          => admin_url( 'admin-ajax.php' ),
			'resetOptions'     => __( 'All your settings will be reset to default values. Are you sure you want to do this ?', 'xstore' ),
			'pasteYourOptions' => __( 'Please, paste your options there.', 'xstore' ),
			'loadingOptions'   => __( 'Loading options', 'xstore' ) . '...',
			'ajaxError'        => __( 'Ajax error', 'xstore' ),
			'audioPlaceholder' => ETHEME_BASE_URI.'framework/panel/images/audio.png',
		);
		return $this->settingJsConfig;
	}
	
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * enqueue scripts for current panel page
	 *
	 * @version  1.0.2
	 * @since  7.0.0
	 */
	public function add_page_admin_script(){
		if ( isset($this->page['script']) && ! empty($this->page['script']) ){
			wp_enqueue_script('etheme_panel_global',ETHEME_BASE_URI.'framework/panel/js/global.min.js', array('jquery','etheme_admin_js'), false,true);
			wp_enqueue_script('etheme_panel_'.$this->page['script'],ETHEME_BASE_URI.'framework/panel/js/'.$this->page['script'].'.js', array('jquery','etheme_admin_js'), false,true);
            if ( $this->page['script'] == 'patcher.min' ) {
                wp_localize_script( 'etheme_panel_'.$this->page['script'], 'XStorePanelPatcherConfig', array(
                    'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                    'success' => esc_html__('Successfully applied', 'xstore'),
                    'applied_btn' => '<span class="patch-unavailable success">'.
                        '<svg width="1em" height="1em" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.5 0C2.01911 0 0 2.01911 0 4.5C0 6.98089 2.01911 9 4.5 9C6.98089 9 9 6.98089 9 4.5C9 2.01911 6.98089 0 4.5 0ZM4.5 8.2666C2.41751 8.2666 0.7334 6.5825 0.7334 4.5C0.7334 2.41751 2.41751 0.7334 4.5 0.7334C6.5825 0.7334 8.2666 2.41751 8.2666 4.5C8.2666 6.5825 6.5825 8.2666 4.5 8.2666ZM6.80885 2.85211C6.70926 2.84306 6.6006 2.87928 6.52817 2.95171L3.85714 5.54125L2.47183 4.11972C2.3994 4.04728 2.2998 4.01107 2.19115 4.01107C2.0825 4.01107 1.9829 4.05634 1.92857 4.14688C1.86519 4.22837 1.82897 4.33702 1.83803 4.43662C1.84708 4.51811 1.8833 4.5996 1.94668 4.64487L3.58551 6.33803C3.65795 6.41046 3.74849 6.44668 3.84809 6.44668C3.93863 6.44668 4.02918 6.41046 4.10161 6.33803L7.02616 3.48592C7.09859 3.41348 7.13481 3.31388 7.13481 3.20523C7.13481 3.11469 7.09859 3.02414 7.03521 2.96982C6.98089 2.89738 6.8994 2.86117 6.80885 2.85211Z" fill="currentColor"/>
                                    </svg>' . esc_html__('Applied', 'xstore').'</span>',
                    'error_btn' => '<span class="patch-unavailable error">'.
                        '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 9 9">
                            <path d="M4.5 0.009c-2.475 0-4.491 2.016-4.491 4.491s2.016 4.491 4.491 4.491 4.491-2.016 4.491-4.491-2.016-4.491-4.491-4.491zM4.5 8.271c-2.079 0-3.771-1.692-3.771-3.771s1.692-3.771 3.771-3.771 3.771 1.692 3.771 3.771-1.692 3.771-3.771 3.771zM4.59 3.492h-0.18c-0.18 0-0.315 0.099-0.315 0.234v3.294c0 0.126 0.135 0.234 0.315 0.234h0.18c0.18 0 0.315-0.099 0.315-0.234v-3.294c0-0.135-0.135-0.234-0.315-0.234zM4.59 1.737h-0.18c-0.171 0-0.315 0.144-0.315 0.315v0.54c0 0.171 0.144 0.315 0.315 0.315h0.18c0.171 0 0.315-0.144 0.315-0.315v-0.54c0-0.171-0.144-0.315-0.315-0.315z" fill="currentColor"></path>
                        </svg>' . esc_html__('Error', 'xstore').'</span>',
                    'apply_all' => esc_html__('Before proceeding, please confirm that you wish to apply all patches.', 'xstore'),
                    'backup_info' => esc_html__('We recommend that you make backups of your website before making any changes.', 'xstore'),
                    'question' => esc_html__('Before proceeding, please confirm that you wish to apply this patch.', 'xstore'),
                    'test_mode' => isset($_GET['xstore-patches-test-mode']),
                    'file_will_modify' => esc_html__('Please, note that the following file will be modified: {{file}}', 'xstore'),
                    'files_will_modify' => esc_html__('Please, note that the following files will be modified: {{files}}', 'xstore')
                ) );
            }
		}

		if (
			isset($this->page['template'])
			&& ! empty($this->page['template'])
            && etheme_is_activated()
            && get_option('et_documentation_beacon', false) !== 'off'
        ){
			wp_enqueue_script('etheme_panel_documentation',ETHEME_BASE_URI.'framework/panel/js/documentation.min.js', array('jquery','etheme_admin_js'), false,true);
		}

		wp_enqueue_script( 'jquery_lazyload', ETHEME_BASE_URI . '/js/libs/jquery.lazyload.js', array('jquery') );
	}
	
	public function add_page_admin_settings_scripts() {
		
		wp_enqueue_script( 'xstore_panel_settings_admin_js', ETHEME_BASE_URI.'framework/panel/js/settings/save_action.min.js', array('wp-color-picker') );
		
		wp_localize_script( 'xstore_panel_settings_admin_js', 'XStorePanelSettingsConfig', $this->settingJsConfig );
	}
	
	public function add_page_admin_settings_xstore_icons() {
		$dir_uri = get_template_directory_uri();
		$icons_type = ( etheme_get_option('bold_icons', 0) ) ? 'bold' : 'light';
		wp_register_style( 'xstore-icons-font', false );
		wp_enqueue_style( 'xstore-icons-font' );
		wp_add_inline_style( 'xstore-icons-font',
			"@font-face {
		  font-family: 'xstore-icons';
		  src:
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".ttf') format('truetype'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff2') format('woff2'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff') format('woff'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".svg#xstore-icons') format('svg');
		  font-weight: normal;
		  font-style: normal;
		}"
		);
		wp_enqueue_style( 'xstore-icons-font-style', $dir_uri . '/css/xstore-icons.css' );
	}
	
	/**
	 * Set panel page data
	 *
	 * @version  1.0.2
	 * @since  7.0.0
	 * @log added sales_booster actions
	 */
	public function set_page_data(){
		if (! isset($_REQUEST['page'])){
			return false;
		}
		switch ( $_REQUEST['page'] ) {
			case 'et-panel-system-requirements':
				$this->page['template'] = 'system-requirements';
				break;
			case 'et-panel-changelog':
				$this->page['template'] = 'changelog';
				break;
			case 'et-panel-support':
				$this->page['template'] = 'support';
				$this->page['class'] = 'youtube';
				$this->page['script'] = 'support.min';
				break;
			case 'et-panel-demos':
				$this->page['template'] = 'demos';
				$this->page['script'] = 'demos.min';
				break;
            case 'et-panel-patcher':
                $this->page['template'] = 'patcher';
                $this->page['script'] = 'patcher.min';
                break;
			case 'et-panel-custom-fonts':
				$this->page['template'] = 'custom-fonts';
				break;
			case 'et-panel-sales-booster':
				$this->page['script'] = 'sales_booster.min';
				$this->page['template'] = 'sales-booster';
				$this->page['class'] = 'sales_booster';
				break;
			case 'et-panel-maintenance-mode':
				$this->page['script'] = 'maintenance_mode.min';
				$this->page['template'] = 'maintenance-mode';
				$this->page['class'] = 'maintenance_mode';
				break;
			case 'et-panel-social':
				$this->page['script'] = 'instagram.min';
				$this->page['template'] = 'instagram';
				$this->page['class'] = 'instagram';
				break;
			case 'et-panel-ai':
				$this->page['script'] = 'ai.min';
				$this->page['template'] = 'ai';
//				$this->page['class'] = 'ai';
				break;
			case 'et-panel-plugins':
				$this->page['script'] = 'plugins.min';
				$this->page['template'] = 'plugins';
				$this->page['class'] = 'plugins';
				break;
			case 'et-panel-email-builder':
				$this->page['script'] = 'email_builder.min';
				$this->page['template'] = 'email-builder';
				$this->page['class'] = 'email_builder';
				break;
			default:
				$this->page['template'] = 'welcome';
				$this->page['script'] = 'welcome.min';
				break;
		}
		return true;
	}
	
	/**
	 * Require page classes
	 *
	 * require page classes when ajax process and return the callbacks for ajax requests
	 *
	 * @version  1.0.0
	 * @since  7.0.0
	 * @param string $class class filename
	 */
	public function require_class($class=''){
		if (! $class){
			return;
		}
		require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'panel/classes/'.$class.'.php') );
	}
	
	/**
	 * Global panel ajax
	 *
	 * require page classes when ajax process and return the callbacks for ajax requests
	 *
	 * @version  1.0.2
	 * @since  7.0.0
	 * @todo remove this
	 * @log added sales_booster actions
	 */
	public function et_panel_ajax(){
		if ( isset($_POST['action_type']) ){
			switch ( $_POST['action_type'] ) {
				case 'et_instagram_user_add':
					$this->require_class('instagram');
					$class = new Instagram();
					$class->et_instagram_user_add();
					break;
				case 'et_instagram_user_remove':
					$this->require_class('instagram');
					$class = new Instagram();
					$class->et_instagram_user_remove();
					break;
				case 'et_instagram_save_settings':
					$this->require_class('instagram');
					$class = new Instagram();
					$class->et_instagram_save_settings();
					break;
				case 'et_email_builder_switch_default':
					$this->require_class('email_builder');
					$class = new Email_builder();
					$class->et_email_builder_switch_default();
					break;
				case 'et_documentation_beacon':
					$this->require_class('youtube');
					$class = new YouTube();
					$class->et_documentation_beacon();
					break;
				case 'et_email_builder_switch_dev_mode_default':
					$this->require_class('email_builder');
					$class = new Email_builder();
					$class->et_email_builder_switch_dev_mode_default();
					break;
				case 'et_maintenance_mode_switch_default':
					$this->require_class('maintenance_mode');
					$class = new Maintenance_mode();
					$class->et_maintenance_mode_switch_default();
					break;
				case 'et_sales_booster_fake_sale_popup_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_fake_sale_popup_switch_default();
					break;
				case 'et_sales_booster_progress_bar_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_progress_bar_switch_default();
					break;
				case 'et_sales_booster_request_quote_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_request_quote_switch_default();
					break;
                case 'et_sales_booster_cart_checkout_countdown_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_cart_checkout_countdown_switch_default();
                    break;
                case 'et_sales_booster_cart_checkout_progress_bar_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_cart_checkout_progress_bar_switch_default();
                    break;
                case 'et_sales_booster_fake_live_viewing_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_fake_live_viewing_switch_default();
                    break;
				case 'et_sales_booster_fake_product_sales_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_fake_product_sales_switch_default();
					break;
                case 'et_sales_booster_quantity_discounts_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_quantity_discounts_switch_default();
                    break;
                case 'et_sales_booster_safe_checkout_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_safe_checkout_switch_default();
                    break;
                case 'et_sales_booster_account_loyalty_program_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_account_loyalty_program_switch_default();
                    break;
                case 'et_sales_booster_account_tabs_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_account_tabs_switch_default();
                    break;
				case 'et_sales_booster_floating_menu_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_floating_menu_switch_default();
					break;
				case 'et_sales_booster_estimated_delivery_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_estimated_delivery_switch_default();
					break;
				case 'et_sales_booster_customer_reviews_images_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_customer_reviews_images_switch_default();
					break;
                case 'et_sales_booster_customer_reviews_advanced_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_customer_reviews_advanced_switch_default();
                    break;
				default:
					break;
			}
		}
	}
	
	/**
	 * Add admin panel dashboard pages to admin menu.
	 *
	 * @since   5.0.0
	 * @version 1.0.3
	 */
	public function et_add_menu_page(){
		$system = new Etheme_System_Requirements();
		$system->system_test();
		$result = $system->result();
		
		$is_et_core = class_exists('ETC\App\Controllers\Admin\Import');
		$is_activated = etheme_is_activated();
		$is_wc = class_exists('WooCommerce');
		$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
		$update_info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_green-color); font-size: 22px;"></span></span>';
		
		$icon = ETHEME_CODE_IMAGES . 'wp-icon.svg';
		$label = 'XStore';
		$show_pages = array(
			'welcome',
			'system_requirements',
			'demos',
			'plugins',
            'patcher',
			'open_ai',
			'customize',
			'email_builder',
			'sales_booster',
			'custom_fonts',
			'maintenance_mode',
			'social',
			'support',
			'changelog',
			'sponsors'
		);
		
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		
		if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
			if ( $xstore_branding_settings['control_panel']['icon'] )
				$icon = $xstore_branding_settings['control_panel']['icon'];
			if ( $xstore_branding_settings['control_panel']['label'] )
				$label = $xstore_branding_settings['control_panel']['label'];
			
			$show_pages_parsed = array();
			foreach ( $show_pages as $show_page ) {
				if ( isset($xstore_branding_settings['control_panel']['page_'.$show_page]))
					$show_pages_parsed[] = $show_page;
			};
			$show_pages = $show_pages_parsed;
		}
		
		$is_update_support = 'active';

		$is_subscription = false;
		
		if (
		$is_activated
		){
			if (
				isset($xstore_branding_settings['control_panel'])
				&& isset($xstore_branding_settings['control_panel']['hide_updates'])
				&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
			){
				$is_update_support = 'active';
				$is_update_available = false;
			} else {
				$check_update = new ETheme_Version_Check();
				$is_update_available = $check_update->is_update_available();
				$is_update_support = 'active'; //$check_update->get_support_status();

				$is_subscription = $check_update->is_subscription;
			}
			
		} else {
			$is_update_available = false;
		}
		
		if ($is_activated && $is_update_support !='active' && $result){
			if ($is_update_support == 'expire-soon'){
				$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
			} else {
				$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_red-color); font-size: 22px;"></span></span>';
			}
		} else if ($is_activated && $is_update_available && $result ){
			$info = $update_info;
		} elseif(!$is_activated){
			$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
			//$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_red-color); font-size: 22px;"></span></span>';
		}
		
		add_menu_page(
			$label . ' ' . ( ( !$is_activated || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
			$label . ' ' . ( ( !$is_activated || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
			'manage_options',
			'et-panel-welcome',
			array( $this, 'etheme_panel_page' ),
			$icon,
			65
		);
		
		if ( in_array('welcome', $show_pages) ) {
			add_submenu_page(
				'et-panel-welcome',
				esc_html__( 'Dashboard', 'xstore' )  .' '. ( !$is_activated || ($is_update_support !='active' && $result) ? $info : ''),
				esc_html__( 'Dashboard', 'xstore' ) .' '. ( !$is_activated || ($is_update_support !='active' && $result) ? $info : ''),
				'manage_options',
				'et-panel-welcome',
				array( $this, 'etheme_panel_page' )
			);
		}
		
		if ( $is_activated ) {
			
			if ( in_array('demos', $show_pages) ) {
				add_submenu_page(
					'et-panel-welcome',
					esc_html__( 'Import Demos', 'xstore' ),
					esc_html__( 'Import Demos', 'xstore' ),
					'manage_options',
					'et-panel-demos',
					array( $this, 'etheme_panel_page' )
				);
			}

            if ( in_array('system_requirements', $show_pages) ) {


                $server_label = esc_html__( 'Server Requirements', 'xstore' );

                if (!$result && $is_activated){
                    $server_label = esc_html__( 'Server Reqs.', 'xstore' );
                    $server_label .= ' ' . $info;
                }

                add_submenu_page(
                    'et-panel-welcome',
                    $server_label,
                    $server_label,
                    'manage_options',
                    'et-panel-system-requirements',
                    array( $this, 'etheme_panel_page' )
                );
            }
			
			if ( in_array('plugins', $show_pages) ) {
				add_submenu_page(
					'et-panel-welcome',
					esc_html__( 'Plugins Installer', 'xstore' ),
					esc_html__( 'Plugins Installer', 'xstore' ),
					'manage_options',
					'et-panel-plugins',
					array( $this, 'etheme_panel_page' )
				);
			}

			// tweak this page link to make link available always
            // but is hidden with css if is disabled from XStore White Label Branding plugin
            $available_patches_affix = '';
//            if ( class_exists('Etheme_Patcher') ) {
//                $patcher = Etheme_Patcher::get_instance();
//                $available_patches = count($patcher->get_available_patches(ETHEME_THEME_VERSION));
//                if ( $available_patches ) {
//                    $available_patches_affix = ' <span class="awaiting-mod update-plugins patches-count count-'.$available_patches.'">'.
//                        $available_patches.
//                    '</span>';
//                }
//            }
            add_submenu_page(
                'et-panel-welcome',
                (in_array('patcher', $show_pages) ? esc_html__( 'Patcher', 'xstore' ) : ''),
                (in_array('patcher', $show_pages) ? esc_html__( 'Patcher', 'xstore' ) : '') . $available_patches_affix,
                'manage_options',
                'et-panel-patcher',
                array( $this, 'etheme_panel_page' )
            );
			
		}
		else {
            if ( in_array('system_requirements', $show_pages) ) {


                $server_label = esc_html__( 'Server Requirements', 'xstore' );

                if (!$result && $is_activated){
                    $server_label = esc_html__( 'Server Reqs.', 'xstore' );
                    $server_label .= ' ' . $info;
                }

                add_submenu_page(
                    'et-panel-welcome',
                    $server_label,
                    $server_label,
                    'manage_options',
                    'et-panel-system-requirements',
                    array( $this, 'etheme_panel_page' )
                );
            }
        }

//        if ( ! etheme_is_activated() && ! class_exists( 'Kirki' ) ) {
		// add_submenu_page(
		//     'et-panel-welcome',
		//     esc_html__( 'Setup Wizard', 'xstore' ),
		//     esc_html__( 'Setup Wizard', 'xstore' ),
		//     'manage_options',
		//     admin_url( 'themes.php?page=xstore-setup' ),
		//     ''
		// );
//        } elseif( ! etheme_is_activated() ){
//
//        } elseif( ! class_exists( 'Kirki' ) ){
//            add_submenu_page(
//                'et-panel-welcome',
//                esc_html__( 'Plugins installer', 'xstore' ),
//                esc_html__( 'Plugins installer', 'xstore' ),
//	            'manage_options',
//	            'et-panel-plugins',
//	            array( $this, 'etheme_panel_page' )
//            );
//        }
//        else {
//
//            add_submenu_page(
//                'et-panel-welcome',
//                esc_html__( 'Install Plugins', 'xstore' ),
//                esc_html__( 'Install Plugins', 'xstore' ),
//                'manage_options',
//                admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
//                ''
//            );
//        }
		
		if ( $is_activated && $is_et_core ) {
			
			if ( ! class_exists( 'Kirki' ) ) {
//                add_submenu_page(
//                    'et-panel-welcome',
//                    'Theme Options',
//                    'Theme Options',
//                    'manage_options',
//                    admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
//                    ''
//                );
			}
			else {
				if ( in_array('customize', $show_pages) ) {
					add_submenu_page(
						'et-panel-welcome',
						'Theme Options',
						'Theme Options',
						'manage_options',
						wp_customize_url(),
						''
					);
					add_submenu_page(
						'et-panel-welcome',
						'Header Builder',
						'Header Builder',
						'manage_options',
						admin_url( '/customize.php?autofocus[panel]=header-builder' ),
						''
					);
					add_submenu_page(
						'et-panel-welcome',
						'Single Product Builder',
						'Single Product Builder',
						'manage_options',
						( get_option( 'etheme_single_product_builder', false ) ? admin_url( '/customize.php?autofocus[panel]=single_product_builder' ) : admin_url( '/customize.php?autofocus[section]=single_product_builder' ) ),
						''
					);
				}
			}

            if ( $is_wc ) {
                if ( in_array( 'email_builder', $show_pages ) ) {
                    add_submenu_page(
                        'et-panel-welcome',
                        esc_html__( 'Email Builder', 'xstore' ),
                        esc_html__( 'Email Builder', 'xstore' ),
                        'manage_options',
                        'et-panel-email-builder',
                        array( $this, 'etheme_panel_page' )
                    );
                }

                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Built-in Wishlist', 'xstore' ),
                    esc_html__( 'Built-in Wishlist', 'xstore' ),
                    'manage_options',
                    admin_url( '/customize.php?autofocus[section]=xstore-wishlist' ),
                    ''
                );

                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Built-in Compare', 'xstore' ),
                    esc_html__( 'Built-in Compare', 'xstore' ),
                    'manage_options',
                    admin_url( '/customize.php?autofocus[section]=xstore-compare' ),
                    ''
                );

                if ( $is_et_core && in_array( 'sales_booster', $show_pages ) ) {
                    add_submenu_page(
                        'et-panel-welcome',
                        esc_html__( 'Sales Booster', 'xstore' ),
                        esc_html__( 'Sales Booster', 'xstore' ),
                        'manage_options',
                        'et-panel-sales-booster',
                        array( $this, 'etheme_panel_page' )
                    );
                }

            }

            if ( in_array('open_ai', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__('ChatGPT', 'xstore'),
                    esc_html__('ChatGPT', 'xstore'),
                    'manage_options',
                    'et-panel-ai',
                    array($this, 'etheme_panel_page')
                );
            }

            if ( in_array('social', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Authorization APIs', 'xstore' ),
                    esc_html__( 'Authorization APIs', 'xstore' ),
                    'manage_options',
                    'et-panel-social',
                    array( $this, 'etheme_panel_page' )
                );
            }

			if ( in_array('maintenance_mode', $show_pages) ) {
				add_submenu_page(
					'et-panel-welcome',
					esc_html__( 'Maintenance Mode', 'xstore' ),
					esc_html__( 'Maintenance Mode', 'xstore' ),
					'manage_options',
					'et-panel-maintenance-mode',
					array( $this, 'etheme_panel_page' )
				);
			}

            if ( in_array('custom_fonts', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Custom Fonts', 'xstore' ),
                    esc_html__( 'Custom Fonts', 'xstore' ),
                    'manage_options',
                    'et-panel-custom-fonts',
                    array( $this, 'etheme_panel_page' )
                );
            }

            if ( in_array('support', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Tutorials & Support', 'xstore' ),
                    esc_html__( 'Tutorials & Support', 'xstore' ),
                    'manage_options',
                    'et-panel-support',
                    array( $this, 'etheme_panel_page' )
                );
            }

            if ( in_array('changelog', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Changelog', 'xstore' ),
                    esc_html__( 'Changelog', 'xstore' ),
                    'manage_options',
                    'et-panel-changelog',
                    array( $this, 'etheme_panel_page' )
                );
            }
        }

        else {
            if ( in_array('customize', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    'Theme Options',
                    'Theme Options',
                    'manage_options',
                    admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
                    ''
                );
            }
        }
		
		if ( $is_activated && in_array('sponsors', $show_pages) ) {
			
			add_submenu_page(
				'et-panel-welcome',
				esc_html__( 'SEO Experts', 'xstore' ),
				esc_html__( 'SEO Experts', 'xstore' ),
				'manage_options',
				'https://overflowcafe.com/am/aff/go/8theme',
				''
			);
			
			//add_submenu_page(
			//	'et-panel-welcome',
			//	esc_html__( 'Customization Services', 'xstore' ),
			//	esc_html__( 'Customization Services', 'xstore' ),
			//	'manage_options',
			//	'https://wpkraken.io/?ref=8theme',
			//	''
			//);

//	        add_submenu_page(
//		        'et-panel-welcome',
//		        esc_html__( 'Woocommerce Hosting', 'xstore' ),
//		        esc_html__( 'Woocommerce Hosting', 'xstore' ),
//		        'manage_options',
//		        'http://www.bluehost.com/track/8theme',
//		        ''
//	        );
			
			add_submenu_page(
				'et-panel-welcome',
				esc_html__( 'Get WPML Plugin', 'xstore' ),
				esc_html__( 'Get WPML Plugin', 'xstore' ),
				'manage_options',
				'https://wpml.org/?aid=46060&affiliate_key=YI8njhBqLYnp&dr',
				''
			);
//			add_submenu_page(
//				'et-panel-welcome',
//				esc_html__( 'Hosting Service', 'xstore' ),
//				esc_html__( 'Hosting Service', 'xstore' ),
//				'manage_options',
//				'https://www.siteground.com/index.htm?afcode=37f764ca72ceea208481db0311041c62',
//				''
//			);
//            if (!$is_subscription){
//                add_submenu_page(
//                    'et-panel-welcome',
//                    esc_html__( 'Go Unlimited', 'xstore' ),
//                    esc_html__( 'Go Unlimited', 'xstore' ),
//                    'manage_options',
//                    'https://www.8theme.com/#price-section-anchor',
//                    ''
//                );
//            }


//	        add_submenu_page(
//		        'et-panel-welcome',
//		        esc_html__( 'WooCommerce Plugins', 'xstore' ),
//		        esc_html__( 'WooCommerce Plugins', 'xstore' ),
//		        'manage_options',
//		        'https://yithemes.com/product-category/plugins/?refer_id=1028760',
//		        ''
//	        );

//            if ( $is_et_core ) {
//		        add_submenu_page(
//			        'et-panel-welcome',
//			        esc_html__( 'Rate Theme', 'xstore' ),
//			        esc_html__( 'Rate Theme', 'xstore' ),
//			        'manage_options',
//			        'https://themeforest.net/item/xstore-responsive-woocommerce-theme/reviews/15780546',
//			        ''
//		        );
//	        }
		}
	}
	
	/**
	 * Add target blank to some dashboard pages.
	 *
	 * @since   6.2
	 * @version 1.0.0
	 */
	public function et_add_menu_page_target() {
		ob_start(); ?>
		<script type="text/javascript">
            jQuery(document).ready( function($) {
                $('#adminmenu .wp-submenu a[href*=themeforest]').attr('target','_blank');
            });
		</script>
		<?php echo ob_get_clean();
	}
	
	/**'
	 * Show Add admin panel dashboard pages.
	 *
	 * @since   5.0.0
	 * @version 1.0.4
	 */
	public function etheme_panel_page(){
		ob_start();
		get_template_part( 'framework/panel/templates/page', 'header' );
		get_template_part( 'framework/panel/templates/page', 'navigation' );
		echo '<div class="et-row etheme-page-content">';
		
		if (isset($this->page['template']) && ! empty($this->page['template'])){
			get_template_part( 'framework/panel/templates/page', $this->page['template'] );
		}
		echo '</div>';
		get_template_part( 'framework/panel/templates/page', 'footer' );
		echo ob_get_clean();
	}
	
	/**
	 * Load content for panel popups
	 *
	 * @since   6.0.0
	 * @version 1.0.1
	 * @log 1.0.2
	 * ADDED: et_ajax_panel_popup header param
	 */
	public function et_ajax_panel_popup(){
		$response = array();
		
		if ( isset( $_POST['type'] ) && $_POST['type'] == 'instagram' ) {
			ob_start();
			get_template_part( 'framework/panel/templates/popup-instagram', 'content' );
			$response['content'] = ob_get_clean();
		} elseif (isset( $_POST['type'] ) && $_POST['type'] == 'registration'){
			ob_start();
			get_template_part( 'framework/panel/templates/popup-theme', 'registration');
			$response['content'] = ob_get_clean();
        } elseif (isset( $_POST['type'] ) && $_POST['type'] == 'deregister'){
			ob_start();
			get_template_part( 'framework/panel/templates/popup-theme', 'deregister');
			$response['content'] = ob_get_clean();
		} else {
			
			if (! isset( $_POST['header'] ) || $_POST['header'] !== 'false'){
				ob_start();
				get_template_part( 'framework/panel/templates/popup-import', 'head' );
				$response['head'] = ob_get_clean();
			} else {
				$response['head'] = '';
			}
			
			ob_start();
			get_template_part( 'framework/panel/templates/popup-import', 'content');
			$response['content'] = ob_get_clean();
		}
		wp_send_json($response);
	}
	
	/**
	 * Redirect after theme was activated
	 *
	 * @since   6.0.0
	 * @version 1.0.0
	 */
	public function admin_redirects() {
		ob_start();
		if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'envato_setup_complete', false ) ) {
			return;
		}
		delete_transient( '_' . $this->theme_name . '_activation_redirect' );
		wp_safe_redirect( admin_url( 'admin.php?page=et-panel-welcome' ) );
		exit;
	}
	
	public function switch_theme() {
		set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );


//		if (
//			! get_theme_mod( 'header_top_elements', false )
//			&& ! get_theme_mod( 'header_main_elements', false )
//			&& ! get_theme_mod( 'header_bottom_elements', false )
//		){
//			$ooo = '{"header_top_elements":"{\"element-DWnDe\":{\"size\":\"12\",\"index\":\"1\",\"offset\":\"0\",\"element\":\"promo_text\"}}","header_main_elements":"{\"element-KRycs\":{\"size\":\"2\",\"index\":\"6\",\"offset\":\"0\",\"element\":\"logo\"},\"element-twJZy\":{\"size\":\"5\",\"index\":\"1\",\"offset\":\"0\",\"element\":\"main_menu\"},\"element-LtRTH\":{\"size\":\"5\",\"index\":\"8\",\"offset\":\"0\",\"element\":\"secondary_menu\"}}","header_bottom_elements":"{}","connect_block_package":"[]","options":{"logo_img_et-desktop":{"id":"","url":"","width":"","height":""},"retina_logo_img_et-desktop":{"id":"","url":"","width":"","height":""},"logo_align_et-desktop":"center","logo_width_et-desktop":"140","logo_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"logo_border_et-desktop":"solid","logo_border_color_custom_et-desktop":"","top_header_wide_et-desktop":true,"top_header_height_et-desktop":"30","top_header_fonts_et-desktop":{"text-transform":"none"},"top_header_zoom_et-desktop":"100","top_header_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"top_header_color_et-desktop":"#000000","top_header_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"top_header_border_et-desktop":"solid","top_header_border_color_custom_et-desktop":"#e1e1e1","main_header_wide_et-desktop":false,"main_header_height_et-desktop":"100","main_header_fonts_et-desktop":{"text-transform":"uppercase","font-backup":"","variant":"regular","font-weight":400,"font-style":"normal"},"main_header_zoom_et-desktop":"100","main_header_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"main_header_color_et-desktop":"#000000","main_header_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"main_header_border_et-desktop":"solid","main_header_border_color_custom_et-desktop":"#e1e1e1","bottom_header_wide_et-desktop":false,"bottom_header_height_et-desktop":"40","bottom_header_fonts_et-desktop":{"text-transform":"none"},"bottom_header_zoom_et-desktop":"100","bottom_header_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"bottom_header_color_et-desktop":"#000000","bottom_header_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"bottom_header_border_et-desktop":"solid","bottom_header_border_color_custom_et-desktop":"","top_header_sticky_et-desktop":false,"main_header_sticky_et-desktop":true,"bottom_header_sticky_et-desktop":false,"header_sticky_type_et-desktop":"smart","headers_sticky_animation_et-desktop":"toBottomFull","headers_sticky_animation_duration_et-desktop":0.70000000000000007,"headers_sticky_start_et-desktop":"80","headers_sticky_logo_img_et-desktop":{"id":"","url":"","width":"","height":""},"top_header_sticky_height_et-desktop":"60","top_header_sticky_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"top_header_sticky_color_et-desktop":"#000000","main_header_sticky_height_et-desktop":"75","main_header_sticky_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"main_header_sticky_color_et-desktop":"#000000","bottom_header_sticky_height_et-desktop":"60","bottom_header_sticky_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"bottom_header_sticky_color_et-desktop":"#000000","menu_item_style_et-desktop":"underline","main_menu_term":"53","menu_zoom_et-desktop":"100","menu_alignment_et-desktop":"flex-end","menu_item_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"inherit","font-weight":0,"font-style":""},"menu_item_border_radius_et-desktop":"30","menu_item_color_custom_et-desktop":"","menu_item_background_color_custom_et-desktop":"#c62828","menu_item_hover_color_custom_et-desktop":"#888888","menu_item_line_hover_color_custom_et-desktop":"#888888","menu_item_dots_color_custom_et-desktop":"#888888","menu_item_background_hover_color_custom_et-desktop":"","menu_item_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"menu_nice_space_et-desktop":false,"menu_item_border_color_custom_et-desktop":"","menu_item_border_hover_color_custom_et-desktop":"","menu_2_item_style_et-desktop":"underline","main_menu_2_term":"","menu_2_zoom_et-desktop":"100","menu_2_alignment_et-desktop":"flex-start","menu_2_item_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"inherit","font-weight":0,"font-style":""},"menu_2_item_border_radius_et-desktop":"30","menu_2_item_color_custom_et-desktop":"","menu_2_item_background_color_custom_et-desktop":"#c62828","menu_2_item_hover_color_custom_et-desktop":"#888888","menu_2_item_line_hover_color_custom_et-desktop":"#888888","menu_2_item_dots_color_custom_et-desktop":"#888888","menu_2_item_background_hover_color_custom_et-desktop":"","menu_2_item_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"menu_2_nice_space_et-desktop":false,"menu_2_item_border_color_custom_et-desktop":"","menu_2_item_border_hover_color_custom_et-desktop":"","menu_dropdown_zoom_et-desktop":"110","menu_dropdown_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"none","font-weight":0,"font-style":""},"menu_dropdown_background_custom_et-desktop":"#ffffff","menu_dropdown_color_et-desktop":"#000000","menu_dropdown_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"1em","padding-right":"2.14em","padding-bottom":"1em","padding-left":"2.14em"},"menu_dropdown_border_et-desktop":"solid","menu_dropdown_border_color_custom_et-desktop":"","secondary_menu_visibility":"on_hover","secondary_menu_home":true,"all_departments_text":"All departments","secondary_menu_term":"","secondary_title_fonts_et-desktop":{"font-family":"","variant":"","letter-spacing":"0px","text-transform":"inherit","font-weight":0,"font-style":""},"secondary_title_background_color_custom_et-desktop":"#c62826","secondary_title_color_et-desktop":"#ffffff","secondary_title_border_radius_et-desktop":"0","secondary_title_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"15px","padding-right":"10px","padding-bottom":"15px","padding-left":"10px"},"secondary_title_border_et-desktop":"solid","secondary_title_border_color_custom_et-desktop":"#e1e1e1","secondary_menu_content_zoom_et-desktop":"100","secondary_menu_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"15px","padding-right":"30px","padding-bottom":"15px","padding-left":"30px"},"secondary_menu_content_border_et-desktop":"solid","secondary_menu_content_border_color_custom_et-desktop":"#e1e1e1","mobile_menu_type_et-desktop":"off_canvas_left","mobile_menu_icon_et-desktop":"icon1","mobile_menu_icon_zoom_et-desktop":1.5,"mobile_menu_label_et-desktop":false,"mobile_menu_text":"Menu","mobile_menu_item_click_et-desktop":false,"mobile_menu_content":["logo","search","menu","wishlist","cart","account","header_socials"],"mobile_menu_2":"categories","mobile_menu_term":"","mobile_menu_logo_type_et-desktop":"simple","mobile_menu_logo_width_et-desktop":"120","mobile_menu_content_alignment_et-desktop":"start","mobile_menu_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"mobile_menu_border_et-desktop":"solid","mobile_menu_border_color_custom_et-desktop":"#e1e1e1","mobile_menu_content_fonts_et-desktop":{"text-transform":"capitalize","font-backup":"","variant":"regular","font-weight":400,"font-style":"normal"},"mobile_menu_zoom_dropdown_et-desktop":"100","mobile_menu_zoom_popup_et-desktop":"100","mobile_menu_overlay_et-desktop":"rgba(0,0,0,.3)","mobile_menu_color2_et-desktop":"#ffffff","mobile_menu_max_height_et-desktop":"500","mobile_menu_background_color_et-desktop":"#ffffff","mobile_menu_color_et-desktop":"#000000","mobile_menu_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"mobile_menu_content_border_et-desktop":"solid","mobile_menu_content_border_color_custom_et-desktop":"#e1e1e1","cart_style_et-desktop":"type1","cart_icon_et-desktop":"type2","cart_icon_zoom_et-desktop":1.3,"cart_label_et-desktop":false,"cart_label_custom":"Cart","cart_total_et-desktop":false,"cart_content_type_et-desktop":"dropdown","mini-cart-items-count":"3","cart_link_to":"cart_url","cart_custom_url":"#","cart_quantity_et-desktop":true,"cart_quantity_size_et-desktop":0.75,"cart_quantity_active_background_custom_et-desktop":"#ffffff","cart_quantity_active_color_et-desktop":"#000000","cart_content_alignment_et-desktop":"end","cart_background_et-desktop":"current","cart_background_custom_et-desktop":"#ffffff","cart_color_et-desktop":"#000000","cart_border_radius_et-desktop":"0","cart_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"cart_border_et-desktop":"solid","cart_border_color_custom_et-desktop":"#e1e1e1","cart_zoom_et-desktop":"100","cart_dropdown_position_et-desktop":"right","cart_dropdown_position_custom_et-desktop":"0","cart_dropdown_background_custom_et-desktop":"#ffffff","cart_dropdown_color_et-desktop":"#000000","cart_content_position_et-desktop":"right","cart_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"20px","padding-right":"20px","padding-bottom":"20px","padding-left":"20px"},"cart_content_border_et-desktop":"solid","cart_content_border_color_custom_et-desktop":"#e1e1e1","cart_footer_content_et-desktop":"Free shipping over 49$","cart_footer_background_custom_et-desktop":"#f5f5f5","cart_footer_color_et-desktop":"#555555","account_background_et-desktop":"","account_style_et-desktop":"type1","account_icon_et-desktop":"type1","account_icon_zoom_et-desktop":1.3,"account_content_type_et-desktop":"dropdown","account_label_et-desktop":true,"account_label_username":false,"account_text":"Log in \/ Sign in","account_logged_in_text":"My account","account_content_alignment_et-desktop":"start","account_background_custom_et-desktop":null,"account_color_et-desktop":"#ffffff","account_border_radius_et-desktop":"0","account_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"account_border_et-desktop":"solid","account_border_color_custom_et-desktop":"","account_zoom_et-desktop":"100","account_dropdown_position_et-desktop":"right","account_dropdown_position_custom_et-desktop":"0","account_dropdown_background_custom_et-desktop":"#ffffff","account_dropdown_color_et-desktop":"#000000","account_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"account_content_border_et-desktop":"solid","account_content_border_color_custom_et-desktop":"#e1e1e1","header_widget1":"","header_widget2":"","html_block1":"","html_block1_sections":false,"html_block1_section":"","html_block2":"","html_block2_sections":false,"html_block2_section":"","html_block3":"","html_block3_sections":false,"html_block3_section":"","promo_text_package":[{"text":"Take 30% off when you spend $120","icon":"et_icon-delivery","icon_position":"before","link_title":"Go shop","link":"#"},{"text":"Free 2-days standard shipping on orders $255+","icon":"et_icon-coupon","icon_position":"before","link_title":"Custom link","link":"#"}],"promo_text_autoplay_et-desktop":true,"promo_text_speed_et-desktop":"3","promo_text_delay_et-desktop":"4","promo_text_navigation_et-desktop":false,"promo_text_close_button_et-desktop":true,"promo_text_close_button_action_et-desktop":false,"promo_text_height_et-desktop":"38","promo_text_background_custom_et-desktop":"#000000","promo_text_color_et-desktop":"#ffffff","button_text_et-desktop":"Button","button_link_et-desktop":"","button_custom_link_et-desktop":"","button_fonts_et-desktop":{"text-transform":"none"},"button_zoom_et-desktop":1,"button_content_align_et-desktop":"start","button_background_custom_et-desktop":"#000000","button_border_radius_et-desktop":"0","button_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"button_border_et-desktop":"solid","button_border_color_custom_et-desktop":"","button_target_et-desktop":false,"button_no_follow_et-desktop":false,"newsletter_shown_on_et-desktop":"click","newsletter_delay_et-desktop":"300","newsletter_icon_et-desktop":"type1","newsletter_label_show_et-desktop":true,"newsletter_label_et-desktop":"Newsletter","newsletter_title_et-desktop":"Title","newsletter_content_et-desktop":"<p>You can add any HTML here (admin -&gt; Theme Options -&gt; E-Commerce -&gt; Promo Popup).<br \/> We suggest you create a static block and use it by turning on the settings below<\/p>","newsletter_sections_et-desktop":false,"newsletter_section_et-desktop":"","newsletter_content_alignment_et-desktop":"start","newsletter_background_et-desktop":{"background-color":"#ffffff","background-image":"","background-repeat":"no-repeat","background-position":"center center","background-size":"","background-attachment":""},"newsletter_background_et-desktop[background-color]":null,"newsletter_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"15px","padding-right":"15px","padding-bottom":"15px","padding-left":"15px"},"newsletter_border_et-desktop":"solid","newsletter_border_color_custom_et-desktop":"","contacts_icon_et-desktop":"left","contacts_direction_et-desktop":"hor","contacts_package_et-desktop":[{"contact_title":"Phone","contact_subtitle":"Call us any time","contact_icon":"et_icon-phone"},{"contact_title":"Hours","contact_subtitle":"Call us any time 24\/7","contact_icon":"et_icon-calendar"},{"contact_title":"Email","contact_subtitle":"Write us any time","contact_icon":"et_icon-chat"}],"contacts_alignment_et-desktop":"start","contact_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"},"contact_border_et-desktop":"solid","contact_border_color_custom_et-desktop":"#e1e1e1","header_socials_type_et-desktop":"type1","header_socials_package_et-desktop":[{"social_name":"Facebook","social_url":"#","social_icon":"et_icon-facebook"},{"social_name":"Twitter","social_url":"#","social_icon":"et_icon-twitter"},{"social_name":"Instagram","social_url":"#","social_icon":"et_icon-instagram"},{"social_name":"Google plus","social_url":"#","social_icon":"et_icon-google_plus"},{"social_name":"Youtube","social_url":"#","social_icon":"et_icon-youtube"},{"social_name":"Linkedin","social_url":"#","social_icon":"et_icon-linkedin"}],"header_socials_content_alignment_et-desktop":"start","header_socials_elements_zoom_et-desktop":"100","header_socials_elements_spacing_et-desktop":"10","header_socials_target_et-desktop":false,"header_socials_no_follow_et-desktop":false,"search_type_et-desktop":"input","search_ajax_et-desktop":true,"search_by_sku_et-desktop":true,"search_category_et-desktop":true,"search_all_categories_text_et-desktop":"All categories","search_placeholder_et-desktop":"Search for...","search_limit_results_et-desktop":"3","search_icon_zoom_et-desktop":1,"search_content_alignment_et-desktop":"center","search_width_et-desktop":"100","search_height_et-desktop":"40","search_border_radius_et-desktop":"0","search_color_et-desktop":"#888888","search_button_background_custom_et-desktop":"#000000","search_button_color_et-desktop":"#ffffff","search_input_box_model_et-desktop":{"margin-top":"0px","margin-right":"","margin-bottom":"0px","margin-left":"","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"10px"},"search_input_border_et-desktop":"solid","search_input_border_color_custom_et-desktop":"#e1e1e1","search_icon_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"10px","padding-right":"0px","padding-bottom":"10px","padding-left":"0px"},"search_icon_border_et-desktop":"solid","search_icon_border_color_custom_et-desktop":"#e1e1e1","search_zoom_et-desktop":"100","search_content_position_et-desktop":"right","search_content_position_custom_et-desktop":"0","search_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"20px","padding-right":"30px","padding-bottom":"30px","padding-left":"30px"},"search_content_border_et-desktop":"solid","search_content_border_color_custom_et-desktop":"#e1e1e1","wishlist_style_et-desktop":"type1","wishlist_icon_et-desktop":"type1","wishlist_icon_zoom_et-desktop":1.3,"wishlist_label_et-desktop":true,"wishlist_label_custom_et-desktop":"Wishlist","wishlist_content_type_et-desktop":"dropdown","wishlist_link_to":"wishlist_url","wishlist_custom_url":"#","mini-wishlist-items-count":null,"wishlist_quantity_et-desktop":true,"wishlist_quantity_size_et-desktop":1,"wishlist_quantity_active_background_custom_et-desktop":"#ffffff","wishlist_quantity_active_color_et-desktop":"#000000","wishlist_content_alignment_et-desktop":"start","wishlist_background_et-desktop":"current","wishlist_background_custom_et-desktop":"#ffffff","wishlist_color_et-desktop":"#000000","wishlist_border_radius_et-desktop":"0","wishlist_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-left-width":"0px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"},"wishlist_border_et-desktop":"solid","wishlist_border_color_custom_et-desktop":"#e1e1e1","wishlist_zoom_et-desktop":"100","wishlist_dropdown_position_et-desktop":"right","wishlist_dropdown_position_custom_et-desktop":"0","wishlist_dropdown_background_custom_et-desktop":"#ffffff","wishlist_content_position_et-desktop":"right","wishlist_content_box_model_et-desktop":{"margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-top-width":"1px","border-right-width":"1px","border-bottom-width":"1px","border-left-width":"1px","padding-top":"20px","padding-right":"20px","padding-bottom":"20px","padding-left":"20px"},"wishlist_content_border_et-desktop":"solid","wishlist_content_border_color_custom_et-desktop":"#e1e1e1"}}';
//			$ooo = json_decode($ooo, true);
//			foreach ( $ooo as $key => $val ) {
//				set_theme_mod( $key, $val );
//			}
//		}


	}


//	Stas fields
	
	public $xstore_panel_section_settings, $settings_name;
	
	protected function enqueue_settings_scripts($script) {
		wp_enqueue_script('etheme_panel_'.$script, ETHEME_BASE_URI.'framework/panel/js/settings/'.$script.'.js', array('jquery','etheme_admin_js'), false,true);
		wp_localize_script( 'xstore_panel_settings_'.$script, 'XStorePanelSettings'.ucfirst($script).'Config', $this->settingJsConfig );
	}
	
	// don't name setting with key of elements it will break saving for this field
	public function xstore_panel_settings_repeater_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $template = array(), $active_callbacks = array(), $custom_item_title = false ) {

        wp_enqueue_script( 'jquery-ui-sortable');
        wp_enqueue_script("jquery-ui-draggable");

		$this->enqueue_settings_scripts( 'sortable' );
		$this->enqueue_settings_scripts( 'repeater' );
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		$values_2_save = $selected_value;
		if ( is_array( $selected_value ) ) {
			$values_2_save = array();
			foreach ( $selected_value as $item_value => $item_name ) {
				$values_2_save[] = $item_value;
			}
			$values_2_save = implode( ',', $values_2_save );
		}
		
		$sorted_list_parsed = array();
		if ( ! empty( $values_2_save ) ) {
			$sorted_list_values = explode( ',', $values_2_save );
			foreach ( $sorted_list_values as $item ) {
				$sorted_list_parsed[ $item ] = array(
					'callbacks' => $template
				);
//			foreach ( $template as $template_item => $template_item_value) {
//			    $current_template = $template;
//				$current_template[$template_item]['args'][1] = $item.'_'.$template_item_value['args'][1];
//				$sorted_list_parsed[$item] = array(
//                    'callbacks' => $template
//                );
//		    }
			}
		}
//		foreach ($sorted_list_values as $item) {
//			$sorted_list_parsed[$item] = $default[$item];
//		}
		if ( count($sorted_list_parsed))
			$sorted_list_parsed = array_merge($sorted_list_parsed, $default);

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

		ob_start();
		?>
		<div class="xstore-panel-option xstore-panel-repeater<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-sortable-items">
				<?php
				$i=0;
				foreach ( $sorted_list_parsed as $item_value => $item_name) { $i++;?>
					<div class="sortable-item" data-name="<?php echo esc_attr($item_value); ?>">
						<h4 class="sortable-item-title">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                                <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
                            </svg>
							<?php if ( $custom_item_title ) echo esc_html($custom_item_title) . ' ' . $i; else echo esc_html__('Item', 'xstore') . ' ' . $i; ?>
						</h4>
						<div class="settings">
							<div class="settings-inner">
								<?php
								if ( isset($item_name['callbacks'])) {
									foreach ( $item_name['callbacks'] as $callback ) {
										$callback['args'][1] = $item_value.'_'.$callback['args'][1];
										call_user_func_array( $callback['callback'], $callback['args'] );
									}
								}
								?>
							</div>
						</div>
					</div>
				<?php }
				?>
			</div>
			<div class="sortable-item-template hidden">
				<div class="sortable-item" data-name="{{name}}">
					<h4 class="sortable-item-title">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                            <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
                        </svg>
                        <?php if ( $custom_item_title ) echo esc_html($custom_item_title) . ' {{item_number}}' ; else echo esc_html__('Item', 'xstore') . ' {{item_number}}'; ?>
					</h4>
					<div class="settings">
						<div class="settings-inner">
							<?php
							foreach ( $template as $template_callback ) {
								$template_callback['args'][1] = '{{name}}_'.$template_callback['args'][1];
								call_user_func_array( $template_callback['callback'], $template_callback['args'] );
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<input type="button" class="add-item et-button no-loader" value="<?php echo esc_attr('Add new item', 'xstore'); ?>">
			<input type="button" class="remove-item et-button et-button-semiactive no-loader" value="<?php echo esc_attr('Remove last item', 'xstore'); ?>">
			<input type="hidden" class="option-val" name="<?php echo esc_attr($setting); ?>" value="<?php echo esc_attr($values_2_save); ?>">
		</div>
		<?php
		echo ob_get_clean();
	}
	
	public function xstore_panel_settings_sortable_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $active_callbacks = array() ) {
		
		$this->enqueue_settings_scripts('sortable');
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		$values_2_save = $selected_value;
		if ( is_array($selected_value)) {
			$values_2_save = array();
			foreach ( $selected_value as $item_value => $item_name ) {
				$values_2_save[] = $item_value;
			}
			$values_2_save = implode(',', $values_2_save);
		}
		
		$sorted_list_parsed = array();
		$sorted_list_values = explode(',', $values_2_save);
		foreach ($sorted_list_values as $item) {
			if ( !isset($default[$item])) continue;
			$sorted_list_parsed[$item] = $default[$item];
		}
		$sorted_list_parsed = array_merge($sorted_list_parsed, $default);
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
				elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start();
		?>
		<div class="xstore-panel-option xstore-panel-sortable<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<?php if ( $setting_title || $setting_descr) { ?>
				<div class="xstore-panel-option-title">
					
					<?php if ( $setting_title ) { ?>
						<h4><?php echo esc_html( $setting_title ); ?>:</h4>
					<?php } ?>
					
					<?php if ( $setting_descr ) : ?>
						<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
					<?php endif; ?>
				
				</div>
			<?php } ?>
			<div class="xstore-panel-sortable-items">
				<?php
				foreach ( $sorted_list_parsed as $item_value => $item_name) {
					if ( !$item_name['name'] ) continue;
					$with_options = isset($item_name['callbacks']);
					$visibility_setting_name = $item_value . '_visibility';
					if ( isset($settings[ $section ]) ) {
						if ( isset( $settings[ $section ][ $visibility_setting_name ] ) && $settings[ $section ][ $visibility_setting_name ] ) {
							$visibility_setting_value = true;
						}
						else {
							$visibility_setting_value = false;
						}
					}
					else {
						$visibility_setting_value = isset($item_name['visible']) ? $item_name['visible'] : true;
					}
					?>
					<div class="sortable-item<?php if(!$visibility_setting_value) {echo ' disabled'; }?><?php if (!$with_options) {?> no-settings<?php } ?>" data-name="<?php echo esc_attr($item_value); ?>">
						<h4 class="sortable-item-title">
							<?php if ( $with_options) : ?>
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
									<path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
								</svg>
							<?php endif;
							echo esc_html( $item_name['name'] ); ?>
							<span class="item-visibility">
                                <input class="screen-reader-text" type="checkbox" id="<?php echo esc_attr($visibility_setting_name); ?>" name="<?php echo esc_attr($visibility_setting_name); ?>"
                                <?php if($visibility_setting_value) echo 'checked'; ?>>
                                <label for="<?php echo esc_attr($visibility_setting_name); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.14em" viewBox="0 0 24 24" width="1.14em" class="show-item"><path d="M0 0h24v24H0V0z" fill="none"/>
                                        <path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.14em" viewBox="0 0 24 24" width="1.14em" class="hide-item">
                <path d="M0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                <path d="M12 6c3.79 0 7.17 2.13 8.82 5.5-.59 1.22-1.42 2.27-2.41 3.12l1.41 1.41c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l1.65 1.65C10.66 6.09 11.32 6 12 6zm-1.07 1.14L13 9.21c.57.25 1.03.71 1.28 1.28l2.07 2.07c.08-.34.14-.7.14-1.07C16.5 9.01 14.48 7 12 7c-.37 0-.72.05-1.07.14zM2.01 3.87l2.68 2.68C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.98-.29 4.32-.82l3.42 3.42 1.41-1.41L3.42 2.45 2.01 3.87zm7.5 7.5l2.61 2.61c-.04.01-.08.02-.12.02-1.38 0-2.5-1.12-2.5-2.5 0-.05.01-.08.01-.13zm-3.4-3.4l1.75 1.75c-.23.55-.36 1.15-.36 1.78 0 2.48 2.02 4.5 4.5 4.5.63 0 1.23-.13 1.77-.36l.98.98c-.88.24-1.8.38-2.75.38-3.79 0-7.17-2.13-8.82-5.5.7-1.43 1.72-2.61 2.93-3.53z"/>
                </svg>
                                </label>
                            </span>
						</h4>
						<div class="settings">
							<div class="settings-inner">
								<?php
								if ( $with_options ) {
									foreach ( $item_name['callbacks'] as $callback ) {
										call_user_func_array( $callback['callback'], $callback['args'] );
									}
								}
								//                                    if ( isset($item_name['callback']) )
								//                                        call_user_func_array( $item_name['callback'], $item_name['args'] );
								?>
							</div>
						</div>
					</div>
				<?php }
				?>
			</div>
			<input type="hidden" class="option-val" name="<?php echo esc_attr($setting); ?>" value="<?php echo esc_html($values_2_save); ?>">
		</div>
		<?php
		echo ob_get_clean();
	}
	
	public function xstore_panel_settings_colorpicker_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $config_var = '' ) {
		
		$this->enqueue_settings_scripts('colorpicker');
		
		$settings = $this->xstore_panel_section_settings;
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-color">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<input type="text" data-alpha="true" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
				       class="color-field color-picker"
				       value="<?php echo ( isset( $settings[ $section ][ $setting ] ) ) ? esc_attr( $settings[ $section ][ $setting ] ) : ''; ?>"
				       <?php if ( $default ) : ?>data-default="<?php echo esc_attr($default); ?>"<?php endif; ?>
				       data-css-var="<?php echo esc_attr( $config_var ); ?>"/>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_upload_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $type = 'image', $save_as = 'url', $js_selector = '', $js_img_var = '' ) {
		
		wp_enqueue_media();
		
		$this->enqueue_settings_scripts('media');
		
		$settings = $this->xstore_panel_section_settings;
		
		ob_start(); ?>
  
		<div class="xstore-panel-option xstore-panel-option-upload">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<div class="<?php echo esc_attr( $setting ); ?>_preview xstore-panel-option-file-preview">
					<?php
					if ( ! empty( $settings[ $section ][ $setting ] ) ) {
						$url = $settings[ $section ][ $setting ];
						if ( $type == 'audio' ) {
							$url = ETHEME_BASE_URI.'framework/panel/images/audio.png';
						}
						echo '<img src="' . esc_url( $url ) . '" />';
					}
					?>
				</div>
				<div class="file-upload-container">
					<div class="upload-field-input">
						<input type="text" id="<?php echo esc_html( $setting ); ?>"
						       name="<?php echo esc_html( $setting ); ?>"
						       value="<?php echo ( isset( $settings[ $section ][ $setting ] ) ) ? esc_html( $settings[ $section ][ $setting ] ) : ''; ?>"
						       <?php if ( $js_selector ) : ?>data-js-selector="<?php echo esc_attr( $js_selector ); ?>"<?php endif; ?>
							<?php if ( $js_img_var ) : ?> data-js-img-var="<?php echo esc_attr( $js_img_var ); ?>" <?php endif; ?>/>
					</div>
					<div class="upload-field-buttons">
						<input type="button"
						       data-title="<?php esc_html_e( 'Login Screen Background Image', 'xstore' ); ?>"
						       data-button-title="<?php esc_html_e( 'Use File', 'xstore' ); ?>"
						       data-option-name="<?php echo esc_html( $setting ); ?>"
						       class="et-button et-button-dark-grey no-loader button-upload-file button-default"
						       value="<?php esc_html_e( 'Upload', 'xstore' ); ?>"
						       data-file-type="<?php echo esc_attr( $type ); ?>"
						       data-save-as="<?php echo esc_attr($save_as); ?>"/>
						<input type="button"
						       data-option-name="<?php echo esc_html( $setting ); ?>"
						       class="et-button et-button-semiactive no-loader button-remove-file button-default <?php echo ( ! isset( $settings[ $section ][ $setting ] ) || '' === $settings[ $section ][ $setting ] ) ? 'hidden' : ''; ?>"
						       value="<?php esc_html_e( 'Remove', 'xstore' ); ?> "/>
					</div>
				</div>
			</div>
		</div>
		<?php echo ob_get_clean();
	}
	
	/**
	 * Description of the function.
	 *
	 * @param string $section
	 * @param string $setting
	 * @param string $setting_title
	 * @param string $setting_descr
	 * @param false  $default
	 * @return void
	 *
	 * @since 1.0.0
	 *
	 */
	public function xstore_panel_settings_switcher_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = false, $active_callbacks = array() ) {
	 
		$this->enqueue_settings_scripts('switch');
		
		$settings = $this->xstore_panel_section_settings;

//		$value = $settings[ $section ][ $setting ] ?? $default;
//		$value = $value === 'no' ? false : $value;
		
		if ( isset($settings[ $section ]) ) {
			if ( isset( $settings[ $section ][ $setting ] ) && $settings[ $section ][ $setting ] == 'on' ) {
				$value = true;
			}
			else {
				$value = false;
			}
		}
		else {
			$value = $default;
		}

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-switcher<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">
                <h4><?php echo esc_html( $setting_title ); ?>:</h4>
                <?php if ( $setting_descr ) :
                    echo '<p class="description">'. $setting_descr . '</p>';
                endif; ?>
            </div>
			<div class="xstore-panel-option-input">
                <label for="<?php echo esc_attr($setting); ?>">
                    <input class="screen-reader-text" id="<?php echo esc_attr($setting); ?>"
                           name="<?php echo esc_attr($setting); ?>"
                           type="checkbox"
                           value="<?php if($value) echo 'on'; ?>"
                        <?php if($value) echo 'checked'; ?>>
                    <span class="switch"></span>
                </label>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_select_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $default = '', $active_callbacks = array() ) {
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-select<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) :
                    echo '<p class="description">' . $setting_descr . '</p>';
				endif; ?>
			
			</div>
			<div class="xstore-panel-option-select">
				<select name="<?php echo esc_attr($setting); ?>" id="<?php echo esc_attr($setting); ?>">
					<?php foreach ( $options as $key => $value ) { ?>
						<option value="<?php echo esc_attr($key); ?>"
							<?php if($key == $selected_value) echo 'selected'; ?>>
							<?php echo esc_attr($value); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_icons_select( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $default = '' ) {
		
		$this->enqueue_settings_scripts('icons_select');
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-icons-select">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-select">
				<select name="<?php echo esc_attr($setting); ?>" id="<?php echo esc_attr($setting); ?>">
					<?php foreach ( $options as $key => $value ) { ?>
						<option value="<?php echo esc_attr($key); ?>"
							<?php if($key == $selected_value) echo 'selected'; ?>>
							<?php echo esc_attr($value); ?></option>
					<?php } ?>
				</select>
				<div class="<?php echo esc_attr( $setting ); ?>_preview xstore-panel-option-icon-preview">
					<i class="et-icon <?php echo str_replace( 'et_icon', 'et', $selected_value ); ?>"></i>
				</div>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_slider_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $min = 0, $max = 50, $default = 12, $step = 1, $postfix = '', $active_callbacks = array(), $super_default = '', $enforce_super_defaults = false ) {
		
		$this->enqueue_settings_scripts('slider');
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-slider<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<input type="range" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
				       min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" value="<?php echo esc_attr( $value ); ?>"
				       step="<?php echo esc_attr($step); ?>">
				<span class="value"
				      <?php if ( $postfix ) { ?>data-postfix="<?php echo esc_html($postfix); ?>" <?php } ?>><?php echo esc_attr( $value ); ?></span>

                <span class="reset dashicons dashicons-image-rotate" data-default="<?php echo esc_attr($default); ?>" data-text="<?php echo esc_attr('Reset', 'xstore'); ?>"></span>

                <?php if($super_default || $enforce_super_defaults) : ?>
                    <input type="hidden" class="super-default" value="<?php echo esc_attr($super_default);?>">
			    <?php endif; ?>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	/**
	 * Description of the function.
	 *
	 * @param       $title
	 * @param array $active_callbacks - multiple array with must-have values as
	 *                                'name' => name of option to compare,
	 *                                'value' => value of option to compare,
	 *                                'section' => section of option to compare,
	 *                                'default' => default value of option for backward compatibility then
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 *
	 */
    public function xstore_panel_settings_tab_field_start($title, $active_callbacks = array()) {

        $this->enqueue_settings_scripts('tab');

        $class = '';
        $to_hide = false;
        $attr = array();
        if ( count($active_callbacks) ) {

            $this->enqueue_settings_scripts('callbacks');

            $attr['data-callbacks'] = array();
            foreach ( $active_callbacks as $key) {
                if ( isset($settings[ $key['section'] ]) ) {
                    if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
                    }
                    else {
                        $to_hide = true;
                    }
                }
                elseif ( $key['value'] != $key['default'] ) {
                    $to_hide = true;
                }
                $attr['data-callbacks'][] = $key['name'].':'.$key['value'];
            }
            $attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
            unset($attr['data-callbacks']);
        }

        if ( $to_hide ) {
            $class .= ' hidden';
        }

        ?>
        <div class="xstore-panel-option xstore-panel-option-tab <?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <?php echo '<h4 class="tab-title">' . $title; ?>
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
            </svg>
            <?php echo '</h4>'; ?>
            <div class="tab-content">
            <?php
            }

    public function xstore_panel_settings_tab_field_end() {
                ?>
            </div>
        </div>
        <?php
    }
	
	public function xstore_panel_settings_input_number_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $min = 0, $max = 100, $default = '', $step = 1, $active_callbacks = array() ) {

		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-input<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                <input type="number" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                       min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" step="<?php echo esc_attr($step); ?>"
                       value="<?php echo esc_attr($value); ?>">
            </div>
        </div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_input_text_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $placeholder = '', $default = '', $active_callbacks = array() ) {
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-input<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) :
					echo '<p class="description">' . $setting_descr . '</p>';
				endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<input type="text" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
				       placeholder="<?php echo esc_attr( $placeholder ); ?>"
				       value="<?php echo esc_attr($value); ?>">
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	// @todo not used
	public function xstore_panel_settings_button_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $active_callbacks = array() ) {
		
		$settings = $this->xstore_panel_section_settings;
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
				elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-button <?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<?php if ( $setting_title || $setting_descr ) : ?>
				<div class="xstore-panel-option-title">
					
					<?php if ( $setting_title ) : ?>
						<h4><?php echo esc_html( $setting_title ); ?>:</h4>
					<?php endif; ?>
					
					<?php if ( $setting_descr ) : ?>
						<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
					<?php endif; ?>
				
				</div>
			<?php endif; ?>
			<div class="xstore-panel-option-input">
				<a class="et-button no-loader"
				   href="<?php echo esc_url($options['url']); ?>"
				   target="<?php echo esc_attr($options['target']); ?>">
					<?php echo esc_html($options['text']); ?>
				</a>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_textarea_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $super_default =  '', $enforce_super_defaults = false ) {
		global $allowedposttags;
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-code-editor">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) :
					echo '<p class="description">' . $setting_descr . '</p>';
				endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
                    <textarea id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                              style="width: 100%; height: 120px;"
                              class="regular-textarea"><?php echo wp_kses( $value, $allowedposttags ); ?></textarea>
                    <?php if($super_default || $enforce_super_defaults) : ?>
                        <textarea class="super-default hidden" value="<?php echo wp_kses($super_default, $allowedposttags);?>"></textarea>
                    <?php endif; ?>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_save() {
        $settings_name = isset( $_POST['settings_name'] ) ? $_POST['settings_name'] : $this->settings_name;
		$all_settings            = (array)get_option( $settings_name, array() );
		
		$local_settings          = isset( $_POST['settings'] ) ? $_POST['settings'] : array();
		if ( isset( $_POST['type'] ) ) {
			$local_settings_key = $_POST['type'];
		}
		else {
			switch ( $settings_name ) {
				case 'xstore_sales_booster_settings':
					$local_settings_key = 'fake_sale_popup';
					break;
				default:
					$local_settings_key = 'general';
			}
		}
		$updated                 = false;
		$local_settings_parsed   = array();
		
		foreach ( $local_settings as $setting ) {
//			$local_settings_parsed[ $local_settings_key ][ $setting['name'] ] = $setting['value'];
            // if ( $this->settings_name == 'xstore_sales_booster_settings' )
			$local_settings_parsed[ $local_settings_key ][ $setting['name'] ] = stripslashes( $setting['value'] );
		}
		
		$all_settings = array_merge( $all_settings, $local_settings_parsed );

		update_option( $settings_name, $all_settings );
		$updated = true;

		switch ($local_settings_key) {
            case 'fake_sale_popup':
                delete_transient('etheme_'.$local_settings_key.'_products_rendered');
                delete_transient('etheme_'.$local_settings_key.'_orders_rendered');
                break;
            case 'fake_live_viewing':
            case 'fake_product_sales':
                $product_ids = (array)get_transient('etheme_'.$local_settings_key.'_ids', array());
                if ( count($product_ids) ) {
                    foreach ($product_ids as $product_id) {
                        if ( $product_id )
                            delete_transient('etheme_'.$local_settings_key.'_' . $product_id);
                    }
                }
                break;
        }
		
		$this_response['response'] = array(
			'msg'  => '<h4 style="margin-bottom: 15px;">' . ( ( $updated ) ? esc_html__( 'Settings successfully saved!', 'xstore' ) : esc_html__( 'Settings saving error!', 'xstore' ) ) . '</h4>',
			'icon' => ( $updated ) ? '<img src="' . ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/success-icon.png" alt="installed icon" style="margin-top: 15px;"><br/><br/>' : '',
		);
		
		wp_send_json( $this_response );
	}

	public function et_close_installation_video(){
		add_option('et_close_installation_video', true, '', false);
        wp_send_json(array('result'=> 'success'));
    }
}
$EtAdmin = new EthemeAdmin();
$EtAdmin->main_construct();