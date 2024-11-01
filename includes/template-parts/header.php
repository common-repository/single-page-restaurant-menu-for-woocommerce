<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$sprm_banner = get_option('sprm_top_banner');
$sprm_logo = get_option('sprm_logo');

?>
<div class="sprm-header <?php echo $sprm_banner ? 'sprm-has-banner' : '' ?>">
    <?php do_action('sprm_header_before') ?>
    <?php if($sprm_banner): ?>
        <div class="sprm-banner-wrap">
            <img src="<?php echo $sprm_banner ?>" alt="" class="sprm-banner" />
        </div>
    <?php endif; ?>

    <?php if($sprm_logo): ?>
        <div class="sprm-logo-wrap">
            <img src="<?php echo $sprm_logo ?>" alt="" class="sprm-logo" />
        </div>
    <?php endif; ?>

    <?php do_action('sprm_header_header') ?>
</div>