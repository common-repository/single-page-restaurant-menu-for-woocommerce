<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$args = array(
    'taxonomy'   => "product_cat",
    'hide_empty' => true
);

$product_categories = get_terms($args);
?>
<div class="sprm">
    
    <?php sprm_get_template_part('header'); ?>

    <div class="sprm-page-content">
        <?php while(have_posts()): the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    </div>

    <div class="sprm-content-wrap">
        <div class="sprm-content">
            <div class="sprm-content-top">
                <div class="sprm-content-top-inner">
                    <button type="button" class="sprm-button sprm-button-show-search">&#128269;</button>
                    <div class="sprm-search-wrap">
                        <form charset="utf-8">
                            <input class="sprm-search-input" type="text" id="sprm-search-q" />
                            <button type="button" class="sprm-button sprm-button-hide-search">&times;</button>
                        </form>
                    </div>
                    <ul class="sprm-categories">
                        <?php foreach($product_categories as $product_category): ?>
                            <li>
                                <a href="#sprm-cat-<?php echo esc_html($product_category->slug) ?>"><?php echo esc_html($product_category->name) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="sprm-content-main">
                <?php foreach($product_categories as $category): ?>
                    <?php 
                        $category_thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true ); 
                        $category_thumbnail_url = wp_get_attachment_url( $category_thumbnail_id );     
                    ?>
                    <div class="sprm-category-menus">
                        <?php if($category_thumbnail_url): ?>
                            <div class="sprm-category-thumb-wrap">
                                <img class="sprm-category-thumb" src="<?php echo $category_thumbnail_url ?>" alt="" />
                            </div>
                        <?php endif; ?>
                        <h3 id="sprm-cat-<?php echo esc_html($category->slug) ?>"><?php echo esc_html($category->name) ?></h3>
                        <ul class="sprm-category-menus-main sprm-menu-loading" data-sprm_category="<?php echo $category->term_id ?>">
                            <li>
                                <span class="sprm-loading-item"></span>
                                <span class="sprm-loading-item"></span>
                            </li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php sprm_get_template_part('mini-cart'); ?>
    </div>
</div>

<?php wp_enqueue_script( 'wc-add-to-cart-variation' ); ?>

