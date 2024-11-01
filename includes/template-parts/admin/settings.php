<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(function_exists( 'wp_enqueue_media' )) {
    wp_enqueue_media();
} else {
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}
wp_enqueue_style( 'wp-color-picker' ); 
?>

<div class="ultimate-wrapper">
    <?php 
        echo UltimateForm::open(array(
            'method' => 'post', 
            'action' => admin_url('admin-ajax.php'), 
            'class' => 'ultimate-ajax-form ultimate-admin-form'
        )); 
    ?>
	
    <div class="ultimate-form-submit-box">
        <h1 style="float: left;"><?php _e('SPRM Options', 'sprm') ?></h1>
        <input type="submit" class="button button-primary button-large" value="Save" style="float: right; margin-top: 15px;" />
    </div>

    <div class="ultimate-content">
        <div class="ultimate-tabs">
            <ul class="col-3 ultimate-tab-nav">
                <li><a href="#" rel="#general"><?php _e('General', 'sprm') ?></a></li>
                <li><a href="https://www.intelvue.com?utm_ref=woo-single-page-restaurant-menu" target="_blank"><?php _e('About Us', 'sprm') ?></a></li>
                <li><a href="http://sprm.intelvue.com?utm_ref=woo-single-page-restaurant-menu" target="_blank"><?php _e('Demos', 'sprm') ?></a></li>
                <li><a href="mailto:ali.kazim@intelvue.com?subject=[Woo Single Page Restaurant Menu] Custom Request" target="_blank"><?php _e('Want Customization', 'sprm') ?></a></li>
            </ul>
            <div class="col-9 ultimate-tab-content">

                <div class="ultimate-tab" id="general">
                    <?php sprm_get_template_part('admin/setting-tabs/general') ?>
                </div>
            </div>
        </div>
    </div>
	<?php echo UltimateForm::hidden('action', 'sprm_save_settings'); ?>
	<div class="loader-admin"></div>

    <div class="ultimate-form-submit-box">
        <input type="submit" class="button button-primary button-large" value="Save">
    </div>

    <?php echo UltimateForm::close(); ?>
</div>