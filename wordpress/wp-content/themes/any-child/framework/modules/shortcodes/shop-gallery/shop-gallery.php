<?php
namespace AnyEdge\Modules\Shortcodes\ShopGallery;

use AnyEdge\Modules\Shortcodes\Lib\ShortcodeInterface;

class ShopGallery implements ShortcodeInterface
{
    private $base;

    public function __construct()
    {
        $this->base = 'edgtf_shop_gallery';
        add_action('vc_before_init', array($this, 'vcMap'));
    }

    public function getBase()
    {
        return $this->base;
    }

    public function vcMap()
    {
        vc_map(array(
            'name' => 'Shop Gallery',
            'base' => $this->base,
            'icon' => 'icon-wpb-shop-gallery extended-custom-icon',
            'category' => 'by EDGE',
            'allowed_container_element' => 'vc_row',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => 'Order by',
                    'param_name' => 'orderby',
                    'value' => array(
                        'Date' => 'date',
                        'Title' => 'title',
                    ),
                    'std' => 'title',
                    'save_always' => true,
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => 'Sort order',
                    'param_name' => 'order',
                    'value' => array(
                        'Descending' => 'DESC',
                        'Ascending' => 'ASC',
                    )
                ),
                array(
                    'type' => 'textfield',
                    'admin_label' => true,
                    'heading' => 'Category',
                    'param_name' => 'category',
                    'value' => '',
                    'description' => 'Category Slug (leave empty for all)'
                ),
				array(
					'type' => 'dropdown',
					'heading' => 'Image Size',
					'param_name' => 'image_size',
					'value' => array(
						'Original' => 'original',
						'Landscape' => 'landscape',
						'Portrait' => 'portrait',
						'Square' => 'square'
					)
				),
            )
        ));
    }

    public function render($atts, $content = null)
    {

        $params = shortcode_atts(array(
            'orderby' => 'date',
            'category' => '',
            'order' => 'DESC',
            'image_size' => 'original'
        ), $atts);


        extract($params);

        $query_args = $this->getQueryArgs($params);

        $products = new \WP_Query($query_args, $atts);
        $columns  = 4;

        $html = '';

        $html  .= '<div class="edgtf-shop-gallery columns-' . $columns .'">';


        if ( $products->have_posts() ) :

            do_action( "woocommerce_shortcode_before_products_loop" );

            $html .= '<div class="products edgtf-shop-list-gallery">';

            while ( $products->have_posts() ) : $products->the_post();

                $id = get_the_ID();
                $params['thumb_size'] = $this->getImageSize($params);
                $params['class'] = $this->getItemClasses($id);
                $html .= any_edge_get_shortcode_module_template_part('templates/shop-gallery-template','shop-gallery','',$params);

            endwhile; // end of the loop.

            $html .= '</div>';

            do_action( "woocommerce_shortcode_after_products_loop" );

        endif;

        woocommerce_reset_loop();
        wp_reset_postdata();

        $html .= '</div>';

        return $html;
    }

    /**
     * Gets product's categories based on $id
     *
     * @param $id
     * @return string
     */
    private function getItemClasses($id){
        global $product;

    	$classes = array();
    	$classes[] = 'edgtf-shop-product';

        $terms = get_the_terms( $id, 'product_cat');
        $cats = '';

		if (is_array($terms) && count($terms)){
			foreach($terms as $term){
				$cats .= 'product_cat-'.$term->term_id.' ';
			}
		}

		$classes[] = $cats;

        if (!$product->is_in_stock()) {
            $classes[] = 'edgtf-out-of-stock';
        }

        if ( $product->is_on_sale() ) {
            $classes[] = 'edgtf-on-sale';
        }

        return implode(' ', $classes);
    }


    /**
     * Creates an array of args for loop
     *
     * @param $params
     * @return array
     */
    private function getQueryArgs($params){

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => $params['orderby'],
            'order'               => $params['order'],
            'posts_per_page'      => -1,
            'meta_query'          => WC()->query->get_meta_query()
        );

        if($params['category'] != ''){
            $args['product_cat'] = $params['category'];
        }

        return $args;
    }


    /**
     * Gets required size for product thumb
     *
     * @param $params
     * @return string
     */
    private function getImageSize($params){

        $size = $params['image_size'];

        switch($size):
	        case 'original' :
                $thumb_size = 'full';
                break;
	        case 'square' :
                $thumb_size = 'any_edge_square';
                break;
            case 'landscape' :
                $thumb_size = 'any_edge_landscape';
                break;
            case 'portrait' :
                $thumb_size = 'any_edge_portrait';
                break;
	        default:
                $thumb_size = 'full';
        endswitch;

        return $thumb_size;
    }
}