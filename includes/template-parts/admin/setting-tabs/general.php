<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }
    
    $pages = get_posts(array('post_type' => 'page', 'posts_per_page' => -1));
    $pages_options = array('' => __('Select Page'));

    foreach ($pages as $key => $page) {
        $pages_options[$page->ID] = $page->post_title;
    }

    $skins = array('' => __('Default', 'sprm'), 'sprm-skin-2' => __('Skin 2'), 'sprm-skin-3' => __('Skin 3'));
    $yesno = array('yes' => 'Yes', 'no' => 'No');
?>

<h2><?php _e('General') ?></h2>

<div class="form-item">
    <label for="sprm_page_id"><?php _e('SRPM Page', 'sprm') ?></label>
    <?php echo UltimateForm::select('sprm_page_id', get_option('sprm_page_id'), $pages_options, ['class' => 'ultimate-input', 'id' => 'sprm_page_id']); ?>
</div>


<div class="form-item">
    <label for="sprm_top_banner"><?php _e('Top Banner', 'sprm') ?></label>
    <?php echo UltimateForm::media('sprm_top_banner', get_option('sprm_top_banner'), ['class' => 'ultimate-input', 'id' => 'sprm_top_banner']); ?>
    <span class="ultimate-input-desc"><?php _e('Should be 4:1, Ideal size is 1920x400') ?></span>
</div>

<div class="form-item">
    <label for="sprm_logo"><?php _e('Top Logo', 'sprm') ?></label>
    <?php echo UltimateForm::media('sprm_logo', get_option('sprm_logo'), ['class' => 'ultimate-input', 'id' => 'sprm_logo']); ?>
    <span class="ultimate-input-desc"><?php _e('Should be 1x1. Ideal size is 180x180') ?></span>
</div>

<div class="form-item">
    <label for="sprm_primary_color"><?php _e('Primary Color', 'sprm') ?></label>
    <?php echo UltimateForm::text('sprm_primary_color', get_option('sprm_primary_color', '#1574f5'), array('class' => 'ultimate-input color-field', 'id' => 'sprm_primary_color')); ?>
</div>

<div class="form-item">
    <label for="sprm_skin"><?php _e('Skin', 'sprm') ?></label>
    <?php echo UltimateForm::select('sprm_skin', get_option('sprm_skin'), $skins, array('class' => 'ultimate-input', 'id' => 'sprm_skin')); ?>
</div>

<div class="form-item">
    <label for="sprm_hide_image"><?php _e('Hide Product Image', 'sprm') ?></label>
    <?php echo UltimateForm::select('sprm_hide_image', get_option('sprm_hide_image', 'yes'), $yesno, array('class' => 'ultimate-input', 'id' => 'sprm_hide_image')); ?>
</div>

<div class="form-item">
    <label for="sprm_product_limit"><?php _e('Menu Limit', 'sprm') ?></label>
    <?php echo UltimateForm::text('sprm_product_limit', get_option('sprm_product_limit', '199'), array('class' => 'ultimate-input', 'id' => 'sprm_product_limit')); ?>
    <div class="ultimate-input-desc"><?php echo __('Product limit under each category. Add -1 if you want to show all.') ?></div>
</div>