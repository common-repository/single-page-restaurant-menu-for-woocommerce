<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Get template part
 * If template exists in themes/THEME_NAME/sprm folder then it wil be picked from there
 *
 * @param string $file_path
 * @param array $data
 * @return void
 */
function sprm_get_template_part( $file_path, $data = array() ) {
    
    if('' !== locate_template('sprm/' . $file_path . '.php')) {
        $file_path = locate_template('sprm/' . $file_path . '.php');
    } else {
        $file_path = SPRM_VIEW_PATH . $file_path . '.php';
    }
    
    if( is_file($file_path) && file_exists($file_path) ) {
        extract($data);
        include($file_path);
    } else {
        echo __('Unable to find view file.', 'sprm');
    }
}

/**
 * Get Mini Cart Content HTML
 *
 * @return string
 */
function sprm_get_mini_cart_contents() {
    ob_start();
    sprm_get_template_part('mini-cart-contents');
    $cart_fragment = ob_get_clean();
    return $cart_fragment;
}

/**
 * Add to cart variable product
 *
 * @param int $product_id
 * @param int $variation_id
 * @param int $quantity
 * @return void
 */
function sprm_add_to_cart_handler_variable( $product_id, $variation_id, $quantity ) {
    try {
        
        $missing_attributes = array();
        $variations         = array();
        $product     = wc_get_product( $product_id );

        if ( ! $product ) {
            return false;
        }

        if ( $product->is_type( 'variation' ) ) {
            $variation_id   = $product_id;
            $product_id     = $product->get_parent_id();
            $product = wc_get_product( $product_id );

            if ( ! $product ) {
                return false;
            }
        }

        $posted_attributes = array();

        foreach ( $product->get_attributes() as $attribute ) {
            if ( ! $attribute['is_variation'] ) {
                // ignore if not a variation attribute
                continue;
            }
            
            $attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );

            if ( isset( $_REQUEST[ $attribute_key ] ) ) {
                if ( $attribute['is_taxonomy'] ) {
                    $value = sanitize_title( wp_unslash( $_REQUEST[ $attribute_key ] ) );
                } else {
                    $value = html_entity_decode( wc_clean( wp_unslash( $_REQUEST[ $attribute_key ] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
                }

                $posted_attributes[ $attribute_key ] = $value;
            }
        }

        if ( empty( $variation_id ) ) {
            $data_store   = WC_Data_Store::load( 'product' );
            $variation_id = $data_store->find_matching_product_variation( $product, $posted_attributes );
        }

        if ( empty( $variation_id ) ) {
            throw new Exception( __( 'Please choose product options&hellip;', 'sprm' ) );
        }

        $variation_data = wc_get_product_variation_attributes( $variation_id );

        foreach ( $product->get_attributes() as $attribute ) {
            if ( ! $attribute['is_variation'] ) {
                continue;
            }

            $attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );
            $valid_value   = isset( $variation_data[ $attribute_key ] ) ? $variation_data[ $attribute_key ] : '';

            if ( isset( $posted_attributes[ $attribute_key ] ) ) {
                $value = $posted_attributes[ $attribute_key ];

                if ( $valid_value === $value ) {
                    $variations[ $attribute_key ] = $value;
                } elseif ( '' === $valid_value && in_array( $value, $attribute->get_slugs(), true ) ) {
                    $variations[ $attribute_key ] = $value;
                } else {
                    throw new Exception( sprintf( __( 'Invalid value posted for %s', 'sprm' ), wc_attribute_label( $attribute['name'] ) ) );
                }
            } elseif ( '' === $valid_value ) {
                $missing_attributes[] = wc_attribute_label( $attribute['name'] );
            }
        }
        if ( ! empty( $missing_attributes ) ) {
            throw new Exception( sprintf( _n( '%s is a required field', '%s are required fields', count( $missing_attributes ), 'sprm' ), wc_format_list_of_items( $missing_attributes ) ) );
        }
    } catch ( Exception $e ) {
        return false;
    }

    // passed data in woocommerce validation filter
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

    if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) ) {
        wc_add_to_cart_message( array( $product_id => $quantity ), true );
        return true;
    }
}