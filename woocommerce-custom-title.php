<?php
/*
Plugin Name: WooCommerce Custom Product Title
Description: Replaces product title with a custom text field in single product view.
Version: 1.1b X
Author: 32372ok@gmail.com
*/

// Replace product title with custom text field on single product view
function custom_title_field($title, $product_id) {
    if (is_singular('product')) {
        $custom_title = get_post_meta($product_id, 'custom_title_field', true);
        if (!empty($custom_title)) {
            $title = $custom_title;
        }
    }
    return $title;
}
add_filter('the_title', 'custom_title_field', 10, 2);

// Add custom text field to product data meta box
function custom_title_field_input() {
    global $post;
    $custom_title = get_post_meta($post->ID, 'custom_title_field', true);
    ?>
    <div class="options_group">
        <?php woocommerce_wp_text_input(
            array(
                'id' => 'custom_title_field',
                'label' => __('Custom Title', 'woocommerce'),
                'placeholder' => '',
                'description' => __('Enter a custom title for this product.', 'woocommerce'),
                'value' => $custom_title,
            )
        ); ?>
    </div>
    <?php
}
add_action('woocommerce_product_options_general_product_data', 'custom_title_field_input');

// Save custom text field value
function save_custom_title_field($post_id) {
    $custom_title = $_POST['custom_title_field'];
    if (!empty($custom_title)) {
        update_post_meta($post_id, 'custom_title_field', sanitize_text_field($custom_title));
    } else {
        delete_post_meta($post_id, 'custom_title_field');
    }
}
add_action('woocommerce_process_product_meta', 'save_custom_title_field');

// Display original title on product catalog
function display_original_title_on_catalog($title, $product) {
    if (!is_singular('product')) {
        return $product->get_name();
    }
    return $title;
}
add_filter('woocommerce_product_title', 'display_original_title_on_catalog', 10, 2);
?>
