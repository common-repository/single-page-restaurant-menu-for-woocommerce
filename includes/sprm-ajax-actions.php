<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// registering ajax action for fetching product list by term/category id  
add_action('wp_ajax_sprm_product_list', 'sprm_ajax_action_product_list');
add_action('wp_ajax_nopriv_sprm_product_list', 'sprm_ajax_action_product_list');

// registering ajax action for fetching product options for variable product
add_action('wp_ajax_sprm_quick_view', 'sprm_ajax_action_quick_view');
add_action('wp_ajax_nopriv_sprm_quick_view', 'sprm_ajax_action_quick_view');

// registering ajax action for add to cart of variable product from sprm page only
add_action('wp_ajax_sprm_add_to_cart_variation', 'sprm_ajax_action_add_to_cart_variation');
add_action('wp_ajax_nopriv_sprm_add_to_cart_variation', 'sprm_ajax_action_add_to_cart_variation');

// registering ajax action for updating cart item qty by using +/- buttons from mini cart
add_action('wp_ajax_sprm_update_cart_item', 'sprm_ajax_action_update_cart_item');
add_action('wp_ajax_nopriv_sprm_update_cart_item', 'sprm_ajax_action_update_cart_item');

// registering ajax action for fetching sprm mini cart
add_action('wp_ajax_sprm_mini_cart', 'sprm_ajax_action_mini_cart');
add_action('wp_ajax_nopriv_sprm_mini_cart', 'sprm_ajax_action_mini_cart');

/**
 * Ajax Action: For fetching product list by term/category id
 *
 * @return void
 */
function sprm_ajax_action_product_list() {
    $term_id = intval($_GET['term_id']);
    sprm_get_template_part('ajax-list', ['term_id' => $term_id]);
    wp_die();
}

/**
 * Ajax Action: Quick view by product ID
 *
 * @return void
 */
function sprm_ajax_action_quick_view() {
    global $post, $product;
    $product_id = intval($_GET['product_id']);
    $post = get_post($product_id);
    if(!$post || $post->post_type !== 'product') {
        return;
    }
    setup_postdata($post);
    $product = wc_get_product($product_id);
    woocommerce_template_single_add_to_cart();
    wp_die();
}

/**
 * Ajax Action: Add to cart variable products
 *
 * @return void
 */
function sprm_ajax_action_add_to_cart_variation() {

    $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( wp_unslash( $_REQUEST['product_id'] ) ) );
    $variation_id      = empty( $_REQUEST['variation_id'] ) ? '' : absint( wp_unslash( $_REQUEST['variation_id'] ) );
    $quantity          = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_REQUEST['quantity'] ) );
        
    $was_added_to_cart = false;
    $product    = wc_get_product( $product_id );

    if ( ! $product ) {
        wp_send_json(array(
            'status' => false,
            'message' => __('Invalid Product ID', 'sprm')
        ));
    } else {
        $add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->get_type(), $product );

        if ( 'variable' === $add_to_cart_handler || 'variation' === $add_to_cart_handler ) {
            $was_added_to_cart = sprm_add_to_cart_handler_variable( $product_id, $variation_id, $quantity );
        }

        if($was_added_to_cart) {
            wp_send_json(array(
                'status' => true,
                'message' => __('Product has been added to cart successfully.', 'sprm'),
                'cart_fragment' => sprm_get_mini_cart_contents(),
                'count' => wc()->cart->cart_contents_count,
                'total' => wc()->cart->get_cart_total(),
            ));
        }
    }

    wp_send_json(array(
        'status' => false,
        'message' => __('Unable to add item.', 'sprm')
    ));

    wp_die();
}

/**
 * Ajax Action: Update Cart Item Qty
 *
 * @return void
 */
function sprm_ajax_action_update_cart_item() {

    $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( wp_unslash( $_REQUEST['product_id'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $cart_item_key     = sanitize_text_field( $_REQUEST['cart_item_key'] );
    $quantity          = absint( wp_unslash( $_REQUEST['qty'] ) );
    $product           = wc_get_product( $product_id );

    if(!$product) {
        wp_send_json(array(
            'status' => false,
            'message' => __( 'Product doesn\'t.exist', 'sprm' )
        ));
    }

    if ( $product->is_sold_individually() && $quantity > 1 ) {
        wp_send_json(array(
            'status' => false,
            'message' => sprintf(__( 'You can only have 1 %s in your cart.', 'woocommerce' ), $product->get_name() )
        ));
    }
    
    // check if item is validated using woocommerce validation filter
    $passed_validation = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $product, $quantity );

    if ( $passed_validation ) {
        
        WC()->cart->set_quantity( $cart_item_key, $quantity, false );
        WC()->cart->calculate_totals();

        wp_send_json(array(
            'status' => true,
            'message' => __('Product has been updated to cart successfully.', 'sprm'),
            'cart_fragment' => sprm_get_mini_cart_contents(),
            'count' => wc()->cart->cart_contents_count,
            'total' => wc()->cart->get_cart_total(),
        ));

    } else {
        wp_send_json(array(
            'status' => false,
            'message' => __( 'Unable to update this item.', 'sprm' )
        ));
    }
}

/**
 * Ajax Action: Mini Cart HTML
 *
 * @return void
 */
function sprm_ajax_action_mini_cart() {
    wp_send_json([
        'status' => true,
        'cart_fragment' => sprm_get_mini_cart_contents(),
        'count' => wc()->cart->cart_contents_count,
        'total' => wc()->cart->get_cart_total(),
    ]);
}