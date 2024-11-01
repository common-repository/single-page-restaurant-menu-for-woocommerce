<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class SPRM_Admin {
   
    public function __construct()
    {
        add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'wp_ajax_sprm_save_settings', array($this, 'save_settings') );
    }

    /**
     * wp action: admin_menu
     *
     * @return void
     */
    public function admin_menu()
    {
        add_menu_page( 
            __('SRPM', 'sprm'), 
            __('SRPM Settings', 'sprm'), 
            'manage_options', 
            'sprm-settings', 
            array($this, 'settings_page'), 
            plugins_url( 'assets/intelvue-icon.png', __DIR__ )
        );
    }

    /**
     * Settings Page
     *
     * @return string
     */
    public function settings_page()
    {
        sprm_get_template_part('admin/settings');
    }
    
    /**
     * wp action: admin_enqueue_scripts
     *
     * @return void
     */
    public function admin_enqueue_scripts()
    {            
        wp_enqueue_script( 
            'sprm-admin', 
            plugins_url( 'assets/js/admin/script.js', __DIR__ ), 
            array( 'jquery', 'wp-color-picker' ), 
            '20180909'
        );
        
        wp_enqueue_style( 
            'sprm-admin', 
            plugins_url( 'assets/css/admin/style.css', __DIR__ ) 
        );
    }

    /**
     * Ajax Action: Save Admin Settings
     *
     * @return void
     */
    public function save_settings()
    {
        try {
        
            foreach ($_POST as $key => $value) {
                if(strpos($key, 'sprm_') === 0) {
                    update_option($key, sanitize_text_field( $value ));
                }
            }

            wp_send_json(array(
                'status' => true,
                'message' => __('Data has been saved successfully!', 'sprm')
            ));
            
        } catch(Exception $e) {
            wp_send_json(array(
                'status' => false,
                'message' => $e->getMessage()
            ));        
        }
    }
    
}

new SPRM_Admin;