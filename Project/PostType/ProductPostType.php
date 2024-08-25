<?php


namespace Project\PostType;

class ProductPostType
{
  
    public function __construct() {
        // add_action( 'init', array( $this, 'register_product_post_type' ) );
    }

    public function register_product_post_type() {
        $labels = array(
            'name'                  => _x( 'Products', 'post type general name', 'ziba' ),
            'singular_name'         => _x( 'Product', 'post type singular name', 'ziba' ),
            'menu_name'             => _x( 'Products', 'admin menu', 'ziba' ),
            'name_admin_bar'        => _x( 'Product', 'add new on admin bar', 'ziba' ),
            'add_new'               => _x( 'Add New', 'product', 'ziba' ),
            'add_new_item'          => __( 'Add New Product', 'ziba' ),
            'new_item'              => __( 'New Product', 'ziba' ),
            'edit_item'             => __( 'Edit Product', 'ziba' ),
            'view_item'             => __( 'View Product', 'ziba' ),
            'all_items'             => __( 'All Products', 'ziba' ),
            'search_items'          => __( 'Search Products', 'ziba' ),
            'parent_item_colon'     => __( 'Parent Products:', 'ziba' ),
            'not_found'             => __( 'No products found.', 'ziba' ),
            'not_found_in_trash'    => __( 'No products found in Trash.', 'ziba' )
        );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'menu_icon'          => 'dashicons-cart',
            'rewrite'               => array( 'slug' => 'products' ),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => true, // Non-hierarchical like posts
            'menu_position'         => null,
            'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
            'show_in_rest'          => true, // This enables Gutenberg support
        );

        register_post_type( 'product', $args );
    }
}
