<?php

namespace App\Service;

class GetWpQuery
{

    public function __construct()
    {
    }

    public function getQuery(array $filter = [])
    {

        $query = new \WP_Query($filter);

        return $query;
    }


    public function getPosts(int $perPage = 10)
    {

        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        $query = new \WP_Query(
            [
                'posts_per_page' => $perPage,
                'post_type' => 'post',
                'paged' => $paged
            ]
        );

        return $query;
    }

    public function getProductBySearch(string $search)
    {

        $args = array(
            'posts_per_page' => 15,
            'post_type' => 'product',
            's' => $search,
            'post_status' => array("publish"),
        );

        $query = new \WP_Query($args);

        return $query;
    }

    public function getProductBySku(string $sku)
    {

        $args =  array(

            'posts_per_page' => 15,
            'post_type' => 'product',
            'post_status' => array("publish"),

            'meta_query' => array(
                array(
                    'key'  => '_sku',
                    'value' => esc_attr($sku),
                    'compare' => 'LIKE'
                ),

            )
        );

        $query = new \WP_Query($args);

        return $query;
    }

    public function getProductByIds(array $ids)
    {

        $args =  array(
            'post_type'              => 'product',
            'posts_per_page'         => 20,
            'order' => 'DESC',
            'tax_query'              => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    =>  $ids
                )
            ),

            'update_post_meta_cache' => false,
            // to do not run query for post meta
            'update_post_term_cache' => false,
            // to do not run query for terms, remove if terms required
            'ignore_sticky_posts'    => true,
            // to ignore sticky posts
            'no_found_rows'          => true
            // to do not count posts – remove if pagination required
        );

        $query = new \WP_Query($args);
        return $query;
    }

    public function getCountByID($tableName)
    {
        global $wpdb;
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM {$tableName}"));
        return $count;
    }

    public function getRowByQuery($Query)
    {
        global $wpdb;
        $results = $wpdb->get_results($Query);
        return $results;
    }

    public function getDataByID($table, $id)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$table} WHERE id = {$id}";
        $results = $wpdb->get_results($sql);
        return $results;
    }

    public function getPostBySlug($slug) {

        global $wpdb;


        $query = $wpdb->prepare( 
            "SELECT * FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'block-html' LIMIT 1", 
            $slug 
        );
        
        // Execute the query
        $post = $wpdb->get_row($query);
        
        // Check if the post was found
        if (null !== $post) {
            // Do something with the $post object
            return $post; // For example, print the post title
        } else {
            return 'Post not found.';
        }

    }

    public function showPaginateLinks($page = 1, $totalPage = 100, $keyFormat = "page_num")
    {
        echo '<nav class="store-pages">';
        echo paginate_links(array(
            'base' => $this->cleanPageArgs($keyFormat),
            'format' => "&{$keyFormat}=%#%",
            'current' => $page,
            'total' => $totalPage,
            'type' => 'list',
        ));
        echo '</nav>';
    }

    public function customPaginate($query)
    {

        // Get the total number of pages from the custom query
        $total_pages = $query->max_num_pages;
        // Check if there is more than one page
        if ($total_pages > 1) {
            // Get the current page number from the URL or default to 1
            $current_page = max(1, get_query_var('paged'));
            // Generate the pagination links using paginate_links()
            echo '<nav class="woocommerce-pagination">';
            echo paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%', // Base URL for each link
                'format' => '/page/%#%', // Format for each link
                'current' => $current_page, // Current page number
                'total' => $total_pages, // Total number of pages
                'prev_text' => __('Previous'), // Text for previous link
                'next_text' => __('Next'), // Text for next link
                // Add more arguments as needed
            ));
            echo '</nav>';
        }
    }

    /**
     * @param string $key
     * @return string
     */
    protected function cleanPageArgs($key)
    {
        $result = remove_query_arg($key);

        return add_query_arg(array($key => '%#%'), $result);
    }
}
