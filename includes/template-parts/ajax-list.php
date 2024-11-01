<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    $product_query = new WC_Product_Query( array(
        'limit' => get_option('sprm_product_limit', 199),
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' => 'term_id',
                'terms'         => $term_id
            ),
        )
    ) );
    $products = $product_query->get_products();
    wp_reset_query();
    $hide_image = get_option('sprm_hide_image') == 'yes';
?>


<?php foreach($products as $_product): global $product; $product = $_product; ?>
    <li class="sprm-menu-item-wrap"> <!-- li.sprm-menu-item-wrap start -->
        <div class="sprm-menu-item">
            <div class="sprm-menu-left"> <!-- div.sprm-menu-left start -->
                <h4 class="sprm-menu-title"><?php echo esc_html($product->get_title()) ?></h4>
                <p class="sprm-menu-description"><?php echo esc_html(strip_tags($product->get_short_description())); ?></p>
                <div class="sprm-price-view">
                    <?php echo $product->get_price_html(); ?>
                </div>
            </div> <!-- div.sprm-menu-left end -->
            <div class="sprm-menu-right"> <!-- div.sprm-menu-right start -->
                <?php 
                    $defaults = array(
                        'quantity'   => 1,
                        'class'      => implode(
                            ' ',
                            array_filter(
                                array(
                                    'button',
                                    'product_type_' . $product->get_type(),
                                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                    $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                                )
                            )
                        ),
                        'attributes' => array(
                            'data-product_id'  => $product->get_id(),
                            'data-product_sku' => $product->get_sku(),
                            'aria-label'       => $product->add_to_cart_description(),
                            'rel'              => 'nofollow',
                        ),
                    );
        
                    $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( array(), $defaults ), $product );
        
                    if ( isset( $args['attributes']['aria-label'] ) ) {
                        $args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
                    }

                        echo apply_filters(
                        'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                        sprintf(
                            '<a href="%s" data-quantity="%s" class="%s" %s>+</a>',
                            esc_url( $product->add_to_cart_url() ),
                            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                            esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : ''
                        ),
                        $product,
                        $args
                    ); 
                ?>
                
                <?php 
                    if(!$hide_image) {
                        echo $product->get_image('thumbnail'); 
                    }
                ?>
            </div> <!-- div.sprm-menu-right end -->
            <div class="sprm-product-detail"> <!-- div.sprm-product-detail start -->
                <div class="sprm-product-detail-content" data-sprm-product="<?php echo $product->get_id() ?>"></div>
            </div> <!-- div.sprm-product-detail end -->
        </div>
    </li> <!-- li.sprm-menu-item-wrap end -->
<?php endforeach; ?>
