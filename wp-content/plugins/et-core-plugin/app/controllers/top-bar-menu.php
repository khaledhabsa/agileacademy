<?php

namespace ETC\App\Controllers;
/**
 * Top Bar Menu.
 *
 * @since      4.0.12
 * @version    1.0.0
 * @package    ETC
 * @subpackage ETC/Models
 */
class Top_Bar_Menu extends Base_Controller{
    public $is_theme_active = false;
    public $is_admin = false;
	public $is_woocommerce = false;
    public $is_elementor = false;
    public $is_system_requirements = true;
    public $is_update_available = false;
    private $settings;
    private $notices;

	public $is_subscription = false;

    public function hooks(){
        add_action( 'admin_bar_menu', array( $this, 'top_bar_menu' ), 110 );
    }

    public function top_bar_menu( $wp_admin_bar ){
        if ( ! defined( 'ETHEME_CODE_IMAGES' ) || ! current_user_can('manage_options') ) {
            return;
        }

        $this->is_theme_active = $this->is_theme_active();
        $this->is_admin = is_admin();
        $this->is_woocommerce = class_exists('WooCommerce');
        $this->is_elementor = defined( 'ELEMENTOR_VERSION' );
        $this->is_system_requirements = $this->is_system_requirements();
        $this->is_update_available = $this->is_update_available();
        $this->set_settings();
        $this->get_notices();

        $args = array(
            'id'    => 'et-top-bar-general-menu',
            'title' => '<span class="ab-label"><img class="et-logo" style="vertical-align: -4px; margin-inline-end: 5px; max-width: 18px;" src="' . $this->settings->title_logo . '" alt="xstore"><span>' . $this->settings->title_text . '</span>' . $this->notices->main,
            'href'  => admin_url( 'admin.php?page=et-panel-welcome' ),
            'meta' => array(
                'html' => $this->style(),
            )
        );

        $wp_admin_bar->add_node( $args );

        if ( in_array('welcome', $this->settings->show_pages) ) {
            $wp_admin_bar->add_node( array(
                'parent' => 'et-top-bar-general-menu',
                'id'     => 'et-panel-welcome',
                'title'  => esc_html__( 'Dashboard', 'xstore-core' ). $this->notices->main,
                'href'   => admin_url( 'admin.php?page=et-panel-welcome' ),
            ) );
        }

        if ( $this->is_theme_active ) {
            if ( in_array('customize', $this->settings->show_pages) ) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-theme-settings',
                    'title' => esc_html__('Theme Options', 'xstore-core'),
                    'href' => wp_customize_url(),
                ));

                //Child pages of Theme Settings (et-top-bar-general-menu)
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-general',
                    'title' => '<span class="dashicons dashicons-before dashicons-schedule"></span>' . __('General', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=general'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-breadcrumbs',
                    'title' => '<span class="dashicons dashicons-before dashicons-carrot"></span>' . __('Breadcrumbs', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=breadcrumbs'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-footer',
                    'title' => '<span class="dashicons dashicons-before dashicons-arrow-down-alt"></span>' . __('Footer', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=footer'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-mobile_panel',
                    'title' => '<span class="dashicons dashicons-before dashicons-download"></span>' . __('Mobile panel', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=mobile_panel'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-style',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-customizer"></span>' . __('Styling/Colors', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=style'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-typography-content',
                    'title' => '<span class="dashicons dashicons-before dashicons-media-document"></span>' . __('Typography', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=typography-content'),
                ));
                if (in_array('custom_fonts', $this->settings->show_pages)) {
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-panel-custom-fonts',
                        'title' => '<span class="dashicons dashicons-before dashicons-editor-spellcheck"></span>' . esc_html__('Custom Fonts', 'xstore-core'),
                        'href' => admin_url('admin.php?page=et-panel-custom-fonts'),
                    ));
                }
                if ($this->is_woocommerce) {
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-cart',
                        'title' => '<span class="dashicons dashicons-before dashicons-cart"></span>' . __('WooCommerce(Shop)', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[panel]=woocommerce'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-panel-built-in-wishlist',
                        'title' => '<span class="dashicons dashicons-before dashicons-heart"></span>' . esc_html__('Wishlist', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=xstore-wishlist'),
                    ));

                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-panel-built-in-compare',
                        'title' => '<span class="dashicons dashicons-before dashicons-image-rotate"></span>' . esc_html__('Compare', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=xstore-compare'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-elements',
                        'title' => '<span class="dashicons dashicons-before dashicons-forms"></span>' . __('Shop Elements', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[panel]=shop-elements'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-cart-page',
                        'title' => '<span class="dashicons dashicons-before dashicons-cart"></span>' . __('Cart Page', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[panel]=cart-page'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-woocommerce_checkout',
                        'title' => '<span class="dashicons dashicons-before dashicons-clipboard"></span>' . __('Checkout', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=woocommerce_checkout'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-cart-checkout-layout',
                        'title' => '<span class="dashicons dashicons-before dashicons-schedule"></span>' . __('Advanced Cart/Checkout', 'xstore-core') . $this->label()->new,
                        'href' => admin_url('/customize.php?autofocus[section]=cart-checkout-layout'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-color-swatches',
                        'title' => '<span class="dashicons dashicons-before dashicons-image-filter"></span>' . __('Variation Swatches', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-color-swatches'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-quick-view',
                        'title' => '<span class="dashicons dashicons-before dashicons-external"></span>' . __('Quick View', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-quick-view'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-section-shop-brands',
                        'title' => '<span class="dashicons dashicons-before dashicons-tickets"></span>' . __('Brands', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-brands'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-catalog-mode',
                        'title' => '<span class="dashicons dashicons-before dashicons-hidden"></span>' . __('Catalog Mode', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=catalog-mode'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-page-filters',
                        'title' => '<span class="dashicons dashicons-before dashicons-filter"></span>' . __('Shop Page Filters', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-page-filters'),
                    ));
                }
                if (in_array('social', $this->settings->show_pages)) {
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-panel-social',
                        'title' => '<span class="dashicons dashicons-before dashicons-admin-users"></span>' . esc_html__('Authorization APIs', 'xstore-core'),
                        'href' => admin_url('admin.php?page=et-panel-social'),
                    ));
                }
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-blog',
                    'title' => '<span class="dashicons dashicons-before dashicons-editor-table"></span>' . __('Blog', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=blog'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-portfolio',
                    'title' => '<span class="dashicons dashicons-before dashicons-images-alt2"></span>' . __('Portfolio', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=portfolio'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-social-sharing',
                    'title' => '<span class="dashicons dashicons-before dashicons-share-alt"></span>' . __('Social Sharing', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=social-sharing'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-general-page-not-found',
                    'title' => '<span class="dashicons dashicons-before dashicons-warning"></span>' . __('404 Page', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=general-page-not-found'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-style-custom_css',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-customizer"></span>' . __('Theme Custom CSS', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=style-custom_css'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-title_tagline',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-settings"></span>' . __('Site Identity', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=title_tagline'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-general-optimization',
                    'title' => '<span class="dashicons dashicons-before dashicons-dashboard"></span>' . __('Speed Optimization', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=general-optimization'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-nav_menus',
                    'title' => '<span class="dashicons dashicons-before dashicons-menu"></span>' . __('Menus', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=nav_menus'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-widgets',
                    'title' => '<span class="dashicons dashicons-before dashicons-wordpress-alt"></span>' . __('Widgets', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=widgets'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-static_front_page',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-home"></span>' . __('Home Settings', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=static_front_page'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-custom_css',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-customizer"></span>' . __('Additional CSS', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=custom_css'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-cei-section',
                    'title' => '<span class="dashicons dashicons-before dashicons-controls-repeat"></span>' . __('Export/Import', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=cei-section'),
                ));
                // End of Child pages of Theme Settings (et-top-bar-general-menu)

            }
            if ( in_array('demos', $this->settings->show_pages) ) {
                $wp_admin_bar->add_node( array(
                    'parent' => 'et-top-bar-general-menu',
                    'id'     => 'et-panel-demos',
                    'title'  => esc_html__( 'Import Demos 130+', 'xstore-core' ),
                    'href'   => admin_url( 'admin.php?page=et-panel-demos' ),
                ) );
            }

            if ( !$this->is_elementor && in_array('customize', $this->settings->show_pages) ) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-panel-header-builder',
                    'title' => esc_html__('Header Builder', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=header-builder'),
                ));
            }

            if( get_theme_mod( 'static_blocks', true ) ) {
                $wp_admin_bar->add_node( array(
                    'parent' => 'et-top-bar-general-menu',
                    'id'     => 'et-panel-staticblocks',
                    'title'  => esc_html__( 'Static Blocks', 'xstore-core' ),
                    'href'   => admin_url( 'edit.php?post_type=staticblocks' ),
                ) );
            }

            $wp_admin_bar->add_node( array(
                'parent' => 'et-top-bar-general-menu',
                'id'     => 'et-panel-global-widgets',
                'title'  => esc_html__( 'Widgets', 'xstore-core' ),
                'href'   => admin_url( 'widgets.php' ),
            ) );

            if ( in_array('patcher', $this->settings->show_pages) ) {
                $wp_admin_bar->add_node( array(
                    'parent' => 'et-top-bar-general-menu',
                    'id'     => 'et-panel-patcher',
                    'title'  => esc_html__( 'Patcher', 'xstore-core' ),
                    'href'   => admin_url( 'admin.php?page=et-panel-patcher' ),
                ) );
            }

            if ($this->is_woocommerce && in_array('email_builder', $this->settings->show_pages)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-panel-email-builder',
                    'title' => esc_html__('Email Builder', 'xstore-core'),
                    'href' => admin_url('admin.php?page=et-panel-email-builder'),
                ));
            }
        }

        if ( in_array('system_requirements', $this->settings->show_pages) ) {
            $wp_admin_bar->add_node( array(
                'parent' => 'et-top-bar-general-menu',
                'id'     => 'et-panel-system-requirements',
                'title'  => esc_html__( 'System Status', 'xstore-core' ),
                'href'   => admin_url( 'admin.php?page=et-panel-system-requirements' ),
            ) );
        }

        if ( ! $this->is_theme_active ){
            return;
        }

        $wp_admin_bar->add_node( array(
            'parent' => 'et-top-bar-general-menu',
            'id'     => 'et-panel-more',
            'title'  => esc_html__( 'More', 'xstore-core' ),
            'href'   => admin_url( 'admin.php?page=et-panel-welcome' ),
        ) );
//			if (!$this->is_subscription){
//				$wp_admin_bar->add_node( array(
//					'parent' => 'et-top-bar-general-menu',
//					'id'     => 'et-panel-unlimited',
//					'title'  => esc_html__( 'Go Unlimited', 'xstore-core' ),
//					'href'   => 'https://www.8theme.com/#price-section-anchor',
//				) );
//			}

        if ( in_array('plugins', $this->settings->show_pages) ) {
            $wp_admin_bar->add_node( array(
                'parent' => 'et-panel-more',
                'id'     => 'et-panel-plugins',
                'title'  => esc_html__( 'Plugin Installer', 'xstore-core' ),
                'href'   => admin_url( 'admin.php?page=et-panel-plugins' ),
            ) );
        }

        if ( in_array('open_ai', $this->settings->show_pages) ) {
            $wp_admin_bar->add_node(array(
                'parent' => 'et-panel-more',
                'id' => 'et-panel-ai',
                'title' => esc_html__('ChatGPT (OpenAI)', 'xstore-core') . $this->label()->new,
                'href' => admin_url('admin.php?page=et-panel-ai'),
            ));
        }

        if ( !class_exists('XStore_AMP') ) {
            $amp_url = admin_url( 'admin.php?page=et-panel-plugins&plugin=xstore-amp' );
        } else {
            $amp_url = admin_url('admin.php?page=et-panel-xstore-amp');
        }

        $wp_admin_bar->add_node( array(
            'parent' => 'et-panel-more',
            'id'     => 'et-panel-amp',
            'title'  => esc_html__( 'AMP XStore', 'xstore-core' ),
            'href'   => ($this->is_theme_active ? $amp_url : admin_url( 'admin.php?page=et-panel-welcome' )),
        ) );

        $wp_admin_bar->add_node( array(
            'parent' => 'et-panel-more',
            'id'     => 'et-xstore-documentation',
            'title'  => esc_html__( 'Documentation', 'xstore-core' ),
            'href'   => etheme_documentation_url(false, false),
            'meta' => array(
                'target' => '_blank'
            )
        ) );

        if ( in_array('support', $this->settings->show_pages) ) {
            $wp_admin_bar->add_node( array(
                'parent' => 'et-panel-more',
                'id'     => 'et-panel-support',
                'title'  => esc_html__( 'Support & Tutorials', 'xstore-core' ),
                'href'   => admin_url( 'admin.php?page=et-panel-support' ),
            ) );
        }

        // theme builder
        if ( $this->is_elementor )
            $this->add_theme_builders_tab($wp_admin_bar, $this->is_woocommerce);

        if ( $this->is_theme_active && $this->is_woocommerce ) {
            $wp_admin_bar->add_node(array(
                'id' => 'et-top-bar-xstore-sales-booster',
                'title' => '<span class="ab-label"><span class="dashicons-before dashicons-chart-bar" style="vertical-align: -5px;margin-inline-end: 5px;width: 18px;" aria-hidden="true"></span><span>' . esc_html__('Sales Booster', 'xstore-core') . '</span>',
                'href' => admin_url('admin.php?page=et-panel-sales-booster'),
            ));
        }
    }

    public function add_theme_builders_tab($wp_admin_bar, $is_woocommerce) {
        $builder_url = admin_url( 'admin.php?page=et-panel-theme-builders' );
        $args = array(
            'id'    => 'et-top-bar-theme-builders-menu',
//            'title' => '<span class="ab-label"><img class="et-logo" style="vertical-align: -4px; margin-inline-end: 5px; max-width: 18px;" src="' . $this->settings->title_logo . '" alt="xstore"><span>' . esc_html__('Theme Builders', 'xstore-core') . '</span>',
            'title' => '<span class="ab-label"><span class="dashicons-before dashicons-schedule" style="vertical-align: -5px;margin-inline-end: 5px;width: 18px;" aria-hidden="true"></span><span>' . sprintf(esc_html__('%s Builders', 'xstore-core'), $this->settings->title_text) . '</span>',
            'href'  => $builder_url,
            'meta' => array(
                'html' => $this->style(),
            )
        );

        $wp_admin_bar->add_node( $args );

        $has_pro = defined( 'ELEMENTOR_PRO_VERSION' );

        if ( $has_pro ) {

            $wp_admin_bar->add_node(array(
                'parent' => 'et-top-bar-theme-builders-menu',
                'id' => 'et-panel-theme-builders',
                'title' => esc_html__('Builders Panel', 'xstore-core'),
                'href' => $builder_url,
            ));

            $elementor_pro_theme_builder_link = \Elementor\Plugin::$instance->app->get_settings('menu_url');

//            $theme_builder_key = 'et-elementor'. ($has_pro ? '-pro' : '') . '-theme-builder';
            $theme_builder_key = !$has_pro ? 'et-elementor-pro-theme-builder' : 'et-panel-theme-builders';

            $theme_builders = array(
                'header' => esc_html__('Header', 'xstore-core'),
                'footer' => esc_html__('Footer', 'xstore-core'),
            );
            if ( $is_woocommerce ) {
                $theme_builders = array_merge($theme_builders,
                    array(
                        'product' => esc_html__('Single Product', 'xstore-core'),
                        'product-archive' => esc_html__('Products Archive', 'xstore-core'),
                        'cart' => esc_html__('Cart Page', 'xstore-core'),
                        'checkout' => esc_html__('Checkout Page', 'xstore-core'),
                    ));
            }

            $theme_builders = array_merge($theme_builders, array(
                'error-404' => esc_html__('Error-404', 'xstore-core')
            ));
            foreach ($theme_builders as $theme_builder_unique_key => $theme_builder_unique_name) {

                $builder_url = $elementor_pro_theme_builder_link . '/templates/'.$theme_builder_unique_key;
                switch ($theme_builder_unique_key) {
                    case 'cart':
                    case 'checkout':
                        $builder_url = admin_url( 'admin.php?page=et-panel-theme-builders' );
                        break;
                    case 'header':
                        $builder_url = admin_url('/customize.php?autofocus[panel]=header-builder');
                        break;
                }
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-theme-builders-menu',
                    'id' => 'et-panel-theme-builder-'.$theme_builder_unique_key,
                    'title' => $theme_builder_unique_name,
                    'href' => $builder_url,
                ));
            }
        }
    }

    public function is_theme_active(){
        return function_exists('etheme_is_activated') && etheme_is_activated();
    }

    public function is_system_requirements(){
        if (
            ! defined('ETHEME_CODE')
            || ! is_user_logged_in()
            || ! current_user_can('administrator')
        ){
            return true;
        }

        if( ! class_exists('Etheme_System_Requirements') ) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'system-requirements.php') );
        }
        $system = new \Etheme_System_Requirements();
        $system->system_test();
        return $system->result();
    }

    public function is_update_available(){
        if (! class_exists('ETheme_Version_Check') && defined('ETHEME_CODE') && is_user_logged_in() ){
            require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'version-check.php') );
        }
        $check_update = new \ETheme_Version_Check(false);

	    $this->is_subscription = $check_update->is_subscription;

        return $check_update->is_update_available();
    }

    private function set_settings(){
        $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

        $settings = array(
            'title_logo' => ETHEME_CODE_IMAGES . 'wp-icon.svg',
            'title_text' => 'XStore',
            'show_pages' => array(
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
                'social',
                'support',
                'changelog',
                'sponsors'
            )
        );

        if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
            if ( $xstore_branding_settings['control_panel']['icon'] ) {
                $settings['title_logo'] = $xstore_branding_settings['control_panel']['icon'];
            }
            if ( $xstore_branding_settings['control_panel']['label'] ) {
                $settings['title_text'] = $xstore_branding_settings['control_panel']['label'];
            }

            $show_pages_parsed = array();
            foreach ( $settings['show_pages'] as $show_page ) {
                if ( isset($xstore_branding_settings['control_panel']['page_'.$show_page])){
                    $show_pages_parsed[] = $show_page;
                }
            }

            $settings['show_pages'] = $show_pages_parsed;
        }
        $this->settings = (object) $settings;
    }

    public function get_notices(){

        $this->notices = (object) array(
            'main' => false,
            'system_requirements' => false,
            'theme_update' => false,
            'theme_activate' => false,
        );

        // temporary disable setter for notices
        // @todo remove when alerts will be fully redesigned
        return;

        if (! $this->is_system_requirements){
            $this->notices->main = $this->notices->system_requirements = $this->notice('warning', __( 'Upgrade Your System Requirements', 'xstore-core' ) );
        }

        if ($this->is_update_available){
            $this->notices->main = $this->notices->theme_update = $this->notice('warning-light', __( 'Update Available', 'xstore-core' ) );
        }

        if (!$this->is_theme_active ){
            $this->notices->main = $this->notices->theme_activate = $this->notice('warning', __( 'Theme Isn\'t Registered', 'xstore-core' ) );
        }

    }

    public function notice($type = 'warning', $tooltip = false){
        if (! $this->is_admin){
            $tooltip = false;
        }
        return $this->icon($type, $tooltip );
    }

    public function icon($type = 'warning', $tooltip = false){
        $class = ($tooltip) ? 'mtips mtips-right': '';

        if ($type == 'warning'){
            $color = 'var(--et_admin_orange-color, #f57f17)';
        } else {
            $color = 'var(--et_admin_green-color, #f57f17)';
        }

        $text = '<span class="awaiting-mod '.$class.'" style="position: relative;min-width: 16px;height: 16px;margin-inline-start: 7px;background: #fff;line-height: 1;display: inline-block;width: 10px;height: 10px;min-width: unset;">';
        $text .= '<span class="dashicons dashicons-warning" style="width: auto;height: auto;font-size: 20px;font-family: dashicons;line-height: 1;border-radius: 50%;color: '.$color.';position: absolute;top: -5px;left: -5px;"></span>';
        if ( $tooltip ) {
            $text .= $this->tooltip($tooltip);
        }
        $text .= '</span>';

        return $text;
    }

    public function tooltip($text = 'Warning: Empty tooltip!'){
        return'<span class="mt-mes" style="line-height: 1; margin-top: -13px; border-radius: 3px;">' . $text . '</span>';
    }

    public function label(){
        return (object) [
            'new' => '<span class="et-tbm-label et-tbm-label-new">'.esc_html__('new', 'xstore-core').'</span>',
            'hot' => '<span class="et-tbm-label et-tbm-label-hot">'.esc_html__('hot', 'xstore-core').'</span>',
            'beta' => '<span class="et-tbm-label et-tbm-label-beta">'.esc_html__('beta', 'xstore-core').'</span>',
        ];
    }

    private function style(){
        return '<style id="et-tbm-styles">
        #wp-admin-bar-et-top-bar-general-menu li .ab-sub-wrapper .ab-submenu, .js #adminmenu #toplevel_page_et-panel-theme-options .et_top-bar-mega-menu-copy {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            width: 840px;
            align-content: space-between;
            height: 220px;
        }
         #wp-admin-bar-et-top-bar-general-menu li#wp-admin-bar-et-panel-more .ab-sub-wrapper .ab-submenu{
            width: auto;
            height: auto;
         }
        #wp-admin-bar-et-top-bar-general-menu li .ab-sub-wrapper .ab-submenu{
            padding: 10px;
        }
        .et_adm-mega-menu-holder{
            position: relative;
        }
        
        .et_adm-mega-menu-holder > a:after {
            content: "\f139";
            font: normal 20px/1 dashicons;
            position: absolute;
            top: 0;
            right: 0;
            speak: never;
            padding: 5px 12px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-image: none!important;
        }
        
        #wp-admin-bar-et-top-bar-general-menu li .ab-sub-wrapper .ab-submenu .dashicons:before {
            font-size: 14px;
            line-height: 27px;
        }
        .js #adminmenu #toplevel_page_et-panel-theme-options .et_top-bar-mega-menu-copy .dashicons:before {
            font-size: 14px;
            line-height: 18px;
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label,
        #toplevel_page_et-panel-theme-options .et-tbm-label {
            margin-inline-start: 3px;
            letter-spacing: 1px;
            display: inline-block;
            border-radius: 3px;
            color: #fff;
            padding: 3px 2px 2px 3px;
            text-transform: uppercase;
            font-size: 8px;
            line-height: 1;
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label-beta,
        #toplevel_page_et-panel-theme-options .et-tbm-label-beta {
            background: var(--et_admin_orange-color, #f57f17);
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label-new,
        #toplevel_page_et-panel-theme-options .et-tbm-label-new {
            background: var(--et_admin_green-color, #489c33);
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label-hot,
        #toplevel_page_et-panel-theme-options .et-tbm-label-hot {
            background: var(--et_admin_main-color, #A4004F);
        }
        </style>';
    }
}