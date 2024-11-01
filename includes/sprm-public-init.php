<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * SPRM Public
 * Description: This class is entry point for all frontend code
 */
class SPRM_Public {
    
    public function __construct()
    {
        require_once __DIR__ . '/sprm-ajax-actions.php';

        // all hooks and filters    
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'head'));
        add_filter( 'body_class', array($this, 'body_class'));
        add_filter('page_template', array($this, 'page_template'));
        add_action('woocommerce_before_quantity_input_field', array($this, 'before_quantity_input_field'));
        add_action('woocommerce_after_quantity_input_field', array($this, 'after_quantity_input_field'));
        add_filter( 'sprm_widget_cart_item_quantity', array($this, 'widget_cart_item_quantity'), 10, 3 );
        
    }
    
    /**
     * wp action: wp_head callback
     *
     * @return void
     */
    public function head()
    {
        sprm_get_template_part('head');
    }

    /**
     * wp filter: body_class callbale
     *
     * @param array $classes
     * @return array
     */
    public function body_class($classes)
    {
        global $post;
        $sprm_page_id = intval(get_option('sprm_page_id'));
        if($post && $sprm_page_id === $post->ID) {
            $classes[] = 'sprm-body';
            $classes[] = get_option('sprm_skin');
        }

        return $classes;
    }

    /**
     * wp action: wp_enqueue_scripts
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script( 'sprm', plugins_url( 'assets/js/script.js', __DIR__ ), array('jquery'), '1.0.1', true );
        wp_register_style( 'sprm',    plugins_url( 'assets/css/style.css', __DIR__ ), false, '1.0.1' );
        wp_enqueue_style ( 'sprm' );

        wp_register_style( 'sprm-font', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap', false );        
        wp_enqueue_style ( 'sprm-font' );
    }

    /**
     * wp action: page_template
     *
     * @param string $page_template
     * @return string
     */
    public function page_template($page_template)
    {  
        global $post;
        $sprm_page_id = intval(get_option('sprm_page_id'));
        
        if($sprm_page_id === $post->ID) {
            
            if(locate_template('sprm/sprm.php') !== '') {
                return locate_template('sprm/sprm.php');
            }

            return SPRM_VIEW_PATH . 'sprm.php';
        }

        return $page_template;
    }

    /**
     * wp action: woocommerce_before_quantity_input_field
     *
     * @return void
     */
    public function before_quantity_input_field()
    {
        echo '<button type="button" class="sprm-qty-decrement">-</button>';
    }

    /**
     * wp action: woocommerce_after_quantity_input_field
     *
     * @return void
     */
    public function after_quantity_input_field()
    {
        echo '<button type="button" class="sprm-qty-increment">+</button>';
    }

    /**
     * wp action: woocommerce_widget_cart_item_quantity
     *
     * @param string $html
     * @param array $cart_item
     * @param string $cart_item_key
     * @return string
     */
    public function widget_cart_item_quantity( $html, $cart_item, $cart_item_key ) 
    {
        $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $cart_item['data'] ), $cart_item, $cart_item_key );
        ob_start();
        sprm_get_template_part('quantity-input', array('cart_item_key' => $cart_item_key, 'cart_item' => $cart_item));
        $quantity_prepend = ob_get_clean();

        return woocommerce_quantity_input( array('input_value' => $cart_item['quantity']), $cart_item['data'], false ) . $quantity_prepend;
    }
    
}

new SPRM_Public;