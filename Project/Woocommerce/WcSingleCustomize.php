<?php

namespace Project\Woocommerce;

class WcSingleCustomize
{

    private $product;
    public function __construct()
    {

        // Remove Actions 
        remove_action("woocommerce_before_single_product", 'woocommerce_output_all_notices', 10);
        remove_action("woocommerce_single_product_summary", 'woocommerce_template_single_add_to_cart', 30);

        add_action("custom_form_add_to_cart", 'woocommerce_template_single_add_to_cart', 30);
        add_action('wp_ajax_woocommerce_ajax_single_add_to_cart', array($this, 'woocommerce_ajax_single_add_to_cart'));
        add_action('wp_ajax_nopriv_woocommerce_ajax_single_add_to_cart', array($this, 'woocommerce_ajax_single_add_to_cart'));
        
        add_filter('woocommerce_product_tabs', array($this, 'woo_remove_product_tabs'), 98);

        // add_filter( 'woocommerce_product_tabs', array($this, 'woo_add_tab_single_product') , 98 );

        // add_filter( 'woocommerce_product_tabs', array($this , 'woo_rename_tab_single_product'), 98  );

        // add_filter( 'woocommerce_product_tabs', array($this , 'woo_hide_tab_single_product'), 99 );


    }
    public function woocommerce_ajax_single_add_to_cart()
    {

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $variation_id = absint($_POST['variation_id']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);

        if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            \WC_AJAX::get_refreshed_fragments();
        } else {

            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
            );

            echo wp_send_json($data);
        }

        wp_die();
    }


    public function woo_remove_product_tabs($tabs) {

        unset($tabs['additional_information']);  // Remove the additional information tab
        $tabs['description']['title'] = "توضیحات محصول";
        $tabs['reviews']['title'] = "دیدگاه مشتریان";
        return $tabs;
    }


    public function woo_add_tab_single_product($tabs) {

        //==> Adds the new tab

        $tabs['custom_tab'] = array(
            'title'     => __('Custom Tab', 'woocommerce'),
            'priority'     => 50,
            'callback'     => array($this , 'woo_content_added_single_product')
        );

        return $tabs;
    }

    private function woo_content_added_single_product() {
        //==> The new tab content
        echo '<h2>Custom Tab</h2>';
        echo '<p>This is a custom tab.</p>';
    }

    public function woo_rename_tab_single_product($tabs) {

        // Rename the reviews tab
        $tabs['reviews']['title'] = __('Ratings');
        return $tabs;
    }

    public function woo_hide_tab_single_product($tabs) {

        global $product;

        // Get the product ID
        $product_id = $product->get_id();

        // Hide the additional information tab for product ID 123
        if ($product_id == 123) {
            unset($tabs['additional_information']);
        }

        return $tabs;
    }

    public function woo_get_attr_product(int $limit) {

        global $product;

        $recoreds = [] ;

        // Get the product attributes as an array
		$attributes = $product->get_attributes();

		// Limit the number of attributes to show, e.g. 3
		$attributes = array_slice($attributes, 0, $limit);

		// Loop through the attributes and display them
		foreach ($attributes as $attribute) {

			// Get the attribute name, slug or taxonomy
			$attr_taxonomy  =  $attribute->get_name();

			if(taxonomy_exists($attr_taxonomy)) {

				$term_name = get_taxonomy($attr_taxonomy)->labels
                                                         ->singular_name;

			} else {
				$term_name = $attr_taxonomy;
			}
			
			// Get the attribute value or term name
			$value = $product->get_attribute($attr_taxonomy);

			$recoreds[] = [
                'key' => $term_name ,
                'value' => $value
            ];
		}

        return $recoreds ;
    }
}
