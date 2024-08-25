<?php

namespace Project\Woocommerce;

class WcArchiveCustomize
{
    public function __construct()
    {

        // Remove Actions 
        remove_action('woocommerce_before_shop_loop_item', 
                      'woocommerce_template_loop_product_link_open', 10);
        remove_action("woocommerce_before_shop_loop_item_title", 
                      'woocommerce_show_product_loop_sale_flash', 10);
        remove_action("woocommerce_before_shop_loop_item_title", 
                      'woocommerce_template_loop_product_thumbnail', 10);
        remove_action("woocommerce_shop_loop_item_title", 
                      "woocommerce_template_loop_product_title", 10);
        remove_action("woocommerce_shop_loop_item_title", 
                      "woocommerce_template_loop_product_title", 10);
        remove_action("woocommerce_after_shop_loop_item_title", 
                      "woocommerce_template_loop_rating", 5);
        remove_action("woocommerce_after_shop_loop_item_title", 
                      "woocommerce_template_loop_price", 10);
        remove_action('woocommerce_after_shop_loop_item', 
                      'woocommerce_template_loop_add_to_cart', 10);

        remove_action('woocommerce_before_shop_loop' ,'woocommerce_output_all_notices' ,10);
        remove_action('woocommerce_before_shop_loop' ,'woocommerce_result_count', 20);
        remove_action('woocommerce_before_shop_loop' ,'woocommerce_catalog_ordering', 30);

        #|==========|Add Actions 

        add_action("woocommerce_before_shop_loop_item_title", 
                   array($this, "woocommerce_show_product_thumbnail"));

        add_action("woocommerce_shop_loop_item_title", 
                   array($this, "woocommerce_show_title_product"));

        add_action('theme__woo_breadcrumb', 
                   'woocommerce_breadcrumb', 20);
    }

    /**
     * Create file for Action `theme__wc_thumbnail` in woocommerce 
     * Directory wordpress project theme
     */
    public function woocommerce_show_product_thumbnail() {

        do_action('theme__wc_thumbnail');

    }


    /**
     * Create file for Action `theme__wc_title` in woocommerce 
     * Directory wordpress project theme
     */
    public function woocommerce_show_title_product() {

        global $product;  

        $ratingLoop = $this->ratingLoop($product->get_average_rating());

        do_action('theme__wc_title' , $ratingLoop);
    }


    public function ratingLoop($rating, $count = 0)
    {
        $class_is_single = (!is_single()) ? 'style="margin:auto"' : ' ';
        $html = "<div class='star-ratings'>";
        if (0 < $rating) {

            $label = sprintf(__('Rated %s out of 5', 'woocommerce'), $rating);
            $html .= $this->starRatingHtml($rating, $count);
        } else {
            $html .= '<div class="fill-ratings" style="width:' . (($rating / 5) * 100) . '%"><span>★★★★★</span></div>';
            $html .= '<div class="empty-ratings"><span>★★★★★</span></div>';
        }
        $html .= "</div>";
        return apply_filters('woocommerce_product_get_rating_html', $html, $rating, $count);
    }

    
    private  function starRatingHtml($rating, $count = 0)
    {
        $html = '<div class="fill-ratings" style="width:' . (($rating / 5) * 100) . '%"><span>★★★★★</span></div>';
        $html .= '<div class="empty-ratings"><span>★★★★★</span></div>';
        return apply_filters('woocommerce_get_star_rating_html', $html, $rating, $count);
    }


    private function priceHtml()
    {
        global $product;
        if (!$product->is_in_stock()) {
            echo "<p>در انبار موجود نیست.</p>";
        } else {
            if ($price_html = $product->get_price_html()) :
                echo '<span class="price">' . $price_html . '</span>';
            endif;
        }
    }
}
