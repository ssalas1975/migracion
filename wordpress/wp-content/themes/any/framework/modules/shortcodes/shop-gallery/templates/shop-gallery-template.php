
<div <?php any_edge_class_attribute($class);?>>

    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>


    <div class="edgtf-gallery-product-image-holder">
    <?php

    echo woocommerce_get_product_thumbnail($thumb_size);

    ?>
    </div>
    <?php
    /**
     * any_edge_woocommerce_after_title hook
     *
     * @hooked woocommerce_show_product_loop_sale_flash - 10
     * @hooked any_edge_woocommerce_out_of_stock_flash - 10 
     * @hooked woocommerce_template_loop_product_link_close - 15
     */
    do_action('any_edge_woocommerce_after_title');
    ?>
    <div class="edgtf-gallery-product-meta-wrapper">

        <div class="edgtf-gallery-product-overlay-outer">
	        <div class="edgtf-gallery-product-overlay-inner">
	            <div class="edgtf-gallery-product-info">
					<?php
					/**
					* woocommerce_after_shop_loop_item_title hook
					*
					* @hooked any_edge_woocommerce_product_categories - 10
					*/

						do_action( 'woocommerce_after_shop_loop_item_title' );
					?>
	                <a href="<?php the_permalink(); ?>">
	                    <?php

	                    the_title('<h3 class="edgtf-product-list-product-title">', '</h3>');

	                    ?>
	                </a>
	                <?php 

	                /**
	                 * any_edge_woocommerce_product_price hook
	                 *
	                 * @hooked woocommerce_template_single_price - 1
	                 */
	                do_action('any_edge_woocommerce_product_price');
	                ?>
	            </div>
	            <div class="edgtf-gallery-product-button">
	                <?php
	                /**
	                 * woocommerce_after_shop_loop_item hook
	                 *
	                 * @hooked woocommerce_template_loop_add_to_cart - 10
	                 */
	                do_action( 'woocommerce_after_shop_loop_item' );

	                ?>
	            </div>
	        </div>
        </div>
    </div>
</div>
