<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="sprm-mini-cart">
    <div class="sprm-mini-cart-mobile-wrap">
        <div class="sprm-mini-cart-total-wrap"><?php echo __('Review', 'sprm') ?> <span><?php echo wc()->cart->get_cart_total() ?></span></div>
    </div>
    <div class="sprm-mini-cart-content-wrap">
        <div class="mini-cart-title"><?php echo __('Cart', 'sprm') ?></div> 
        <div class="sprm-mini-cart-content">
            <?php echo sprm_get_mini_cart_contents(); ?>
        </div>
    </div>
</div>