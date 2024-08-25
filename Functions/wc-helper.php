<?php

function theme___get_percent_rate_number($rate)
{

    global $product;
    if ($average = $product->get_average_rating()) {

        return $product->get_rating_count($rate) / $product->get_review_count() * 100;
    } else {
        return "0";
    }
}

function theme__wc_attr_permalink($product, $attr)
{
    $attributes = $product->get_attributes();
    foreach ($attributes as $attribute) {
        if ($attribute->get_name() == "pa_product_brand") {
            $terms = wp_get_post_terms($product->get_id(), "pa_product_brand", 'all');
            foreach ($terms as $term) {
                return get_term_link($term);
            }
        }
    }
    foreach ($terms as $term) {
    }
}

function theme__wc_product_cats()
{

    $output = array();

    $categories = get_terms(array(
        'orderby' => 'name',
        'pad_counts' => false,
        'hierarchical' => 1,
        'hide_empty' => true,
        'child_of' => false, //can be 0, '0', '' too
        'childless' => false,
        'cache_domain' => 'core',
        'update_term_meta_cache' => true, //can be 1, '1' too
    ));

    foreach ($categories as $category) {
        if ($category->taxonomy == 'product_cat') {
            $output[$category->term_id] = $category->name;
        }
    }

    return $output;
}

function theme__get_count_orders($status)
{

    $customer_orders = get_posts(

        apply_filters(
            'woocommerce_my_account_my_orders_query',
            array(
                'meta_key' => '_customer_user',
                'meta_value' => get_current_user_id(),
                'post_type' => wc_get_order_types('view-orders'),
                'post_status' => array_keys(wc_get_order_statuses()),
            )
        )

    );
    foreach ($customer_orders as $order) {

        if ($order->post_status == "wc-" . $status) {

            $orderCount[] = $order->post_status;
        }
    }

    if (isset($orderCount)) {
        return count($orderCount);
    } else {
        return 0;
    }

    wp_reset_query();
}


function theme__product_variation_attributes()
{
    global $product;

    // test if product is variable
    if ($product->is_type('variable')) {
        // Loop through available product variation data
        foreach ($product->get_available_variations() as $key => $variation) {
            // Loop through the product attributes for this variation
            foreach ($variation['attributes'] as $attribute => $term_slug) {
                // Get the taxonomy slug
                $taxonmomy = str_replace('attribute_', '', $attribute);

                // Get the attribute label name
                $attr_label_name = wc_attribute_label($taxonmomy);

                // Display attribute labe name
                $term_name = get_term_by('slug', $term_slug, $taxonmomy)->name;

                // Testing output
                echo '<p>' . $attr_label_name . ': ' . $term_name . '</p>';
            }
        }
    }
}

function theme__custom_attr_values($attr)
{
    global $product;
    return $product->get_attribute($attr);
}

function theme__is_added_remember_stock()
{

    global $wpdb;
    global $product;

    $table = $wpdb->prefix . 'moho_wc_stock';
    $product_id = $product->get_id();
    $user_id = get_current_user_id();

    $result = $wpdb->get_var("SELECT * FROM $table WHERE product_id = '$product_id' and user_id = '$user_id'");

    if ($result == NULL) {
        return false;
    }

    return true;
}

function get__paginations()
{
    $total = isset($total) ? $total : wc_get_loop_prop('total_pages');
    $current = isset($current) ? $current : wc_get_loop_prop('current_page');
    $base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg(
        'add-to-cart',
        get_pagenum_link(999999999, false)
    )));
    $format = isset($format) ? $format : '';

    if ($total <= 1) {
        return;
    } ?>
    <?php
    $pages = paginate_links(
        apply_filters(
            'woocommerce_pagination_args',
            array( // WPCS: XSS ok.
                'base'      => $base,
                'format'    => $format,
                'add_args'  => false,
                'prev_text' => '<i class="bi bi-chevron-right"></i>',
                'next_text' => '<i class="bi bi-chevron-left"></i>',
                'current'   => max(1, $current),
                'total'     => $total,
                'type'      => 'array',
                'end_size'  => 1,
                'mid_size'  => 3,

            )
        )
    );
    // var_dump($pages);
    if (is_array($pages)) {
        $paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');
        echo '<ul  class="pagination">';

        foreach ($pages as $page) {
            echo "<li class='page-item'>$page</li>";
        }
        echo '</ul>';
    } ?>
    <?php
}

function custom__paginate($query)
{
    $big = 999999999; // need an unlikely integer

    $pages = paginate_links(array(
        'base'         => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'       => '?paged=%#%',
        'current'      => max(1, get_query_var('paged')),
        'total'        => $query->max_num_pages,
        'prev_text' => '<i class="bi bi-chevron-right"></i>',
        'next_text' => '<i class="bi bi-chevron-left"></i>',
        'type'         => 'array',
        'end_size'  => 1,
        'mid_size'  => 3,
    ));

    if (is_array($pages)) {
        $paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');
        echo '<ul  class="pagination">';

        foreach ($pages as $page) {
            echo "<li class='page-item'>$page</li>";
        }
        echo '</ul>';
    }
}


function active_tab($tab, $tabs) {
    echo $tab == "description" ?"active show": "";
}

function wc__count_product()
{
    // Get current query object
    global $wp_query;

    // Get the total number of products available
    $total_products = $wp_query->found_posts;

    // Determine how many products are being displayed per page
    $products_per_page = get_option('posts_per_page');

    // Calculate the start and end range of products being displayed
    $start_index = $wp_query->query['paged'] ? ($wp_query->query['paged'] - 1) * $products_per_page + 1 : 1;
    $end_index = min($start_index + $products_per_page - 1, $total_products);

    // Output the message
    echo "نمایش  " . $start_index . "  تا  " . $end_index . "  از  " . $total_products . " کالا های موجود";
}
    ?>