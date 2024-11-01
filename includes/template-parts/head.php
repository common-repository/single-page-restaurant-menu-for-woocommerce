<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<script type="text/javascript">
    var SPRM_AJAX_URL = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

<?php if($sprm_primary_color = get_option('sprm_primary_color')): ?>
    <style type="text/css">
        .sprm a,
        .sprm .quantity .sprm-qty-decrement,
        .sprm .quantity .sprm-qty-increment {
            color: <?php echo $sprm_primary_color ?>;
        }

        .sprm .single_add_to_cart_button,
        .sprm .single_add_to_cart_button:hover,
        .sprm-menu-right .add_to_cart_button,
        .sprm-mini-cart-content .woocommerce-mini-cart__buttons .checkout,
        .sprm-mini-cart-mobile-wrap {
            background-color: <?php echo $sprm_primary_color ?>;
        }
    </style>
<?php endif; ?>