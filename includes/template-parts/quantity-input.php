<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="sprm-cart-parameters">
    <?php if($cart_item_key): ?>
        <input type="hidden" name="sprm_cart_item_key" value="<?php echo $cart_item_key ?>" />
    <?php endif; ?>
    <?php if(isset($cart_item['product_id'])): ?>
        <input type="hidden" name="sprm_product_id" value="<?php echo $cart_item['product_id'] ?>" />
    <?php endif; ?>
</div>