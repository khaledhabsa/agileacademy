<?php
namespace ETC\App\Controllers\Elementor\Dynamic_Tags;

use ETC\App\Classes\Elementor;

class Product_Brands_Images extends \Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'etheme_product_brands_images-tag';
    }

    public function get_title() {
        return __( 'Product Brands Images', 'xstore-core' );
    }

    public function get_group() {
        return \ElementorPro\Modules\Woocommerce\Module::WOOCOMMERCE_GROUP;// 'woocommerce'; // group key is taken from Elementor Pro code
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY ];
    }

    public function get_value( array $options = [] ) {
        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return [];
        }

        $value = [];

        $brands = get_terms( 'brand' );

        foreach ( $brands as $brand ) {
            $attachment_id = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) );
            if ( !$attachment_id ) continue;

            $value[] = [
                'id' => $attachment_id,
            ];
        }

        return (array)$value;

    }

}