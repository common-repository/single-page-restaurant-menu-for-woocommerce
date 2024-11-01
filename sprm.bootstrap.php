<?php

/**
 * Plugin Name: Single Page Restaurant Menu for WooCommerce
 * Plugin URI: http://sprm.intelvue.com/
 * Description: This plugin is developed to list all woocommerce products/menus in a single page with category and editable cart information.
 * Author: Intelvue
 * Text Domain: sprm
 * Domain Path: /languages
 * Version: 1.0.6
 * Author URI: https://www.intelvue.com/
 */


define('SPRM_VIEW_PATH', __DIR__ . '/includes/template-parts/');

class SPRM_Bootstrap {
    
    public static function instance()
    {
        return new SPRM_Bootstrap;
    }

    public function __construct()
    {
        require_once __DIR__ . '/includes/sprm-functions.php';

        $this->initialize_admin();
        $this->initialize_public();

        register_activation_hook( __FILE__, array($this, 'plugin_activate') );
    }

    /**
     * check woocommerce dependency on plugin activated
     *
     * @return void
     */
    public function plugin_activate()
    {
        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            
            // Deactivate the plugin
            deactivate_plugins( plugin_basename( __FILE__ ) );
            
            wp_die(sprintf('<div class="notice notice-error"><p>%s</p></div>', __('This plugin is written for <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> plugin and cannot be used without it.', 'sprm')));
        }
    }

    /**
     * Initialize all public code
     *
     * @return void
     */
    public function initialize_public()
    {
        require_once __DIR__ . '/includes/sprm-public-init.php';
    }
    
    /**
     * Initialize all required code for admin only
     *
     * @return void
     */
    public function initialize_admin()
    {
        if(!is_admin()) {
            return;
        }

        require_once __DIR__ . '/includes/lib/UltimateForm.php';
        require_once __DIR__ . '/includes/sprm-admin-init.php';
    }
}

SPRM_Bootstrap::instance();