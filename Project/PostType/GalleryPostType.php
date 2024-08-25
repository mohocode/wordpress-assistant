<?php

namespace Project\PostType;

class GalleryPostType
{

    public function __construct()
    {
        add_action('init', array($this, 'register_gallery_post_type'));
        add_action('init', array($this, 'register_gallery_taxonomy'));
    }

    public function register_gallery_post_type()
    {
        $labels = array(
            'name'                  => _x('Galleries', 'Post type general name', 'ziba'),
            'singular_name'         => _x('Gallery', 'Post type singular name', 'ziba'),
            'menu_name'             => _x('Galleries', 'Admin Menu text', 'ziba'),
            'name_admin_bar'        => _x('Gallery', 'Add New on Toolbar', 'ziba'),
            'add_new'               => __('Add New', 'ziba'),
            'add_new_item'          => __('Add New Gallery', 'ziba'),
            'new_item'              => __('New Gallery', 'ziba'),
            'edit_item'             => __('Edit Gallery', 'ziba'),
            'view_item'             => __('View Gallery', 'ziba'),
            'all_items'             => __('All Galleries', 'ziba'),
            'search_items'          => __('Search Galleries', 'ziba'),
            'parent_item_colon'     => __('Parent Galleries:', 'ziba'),
            'not_found'             => __('No galleries found.', 'ziba'),
            'not_found_in_trash'    => __('No galleries found in Trash.', 'ziba')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-format-gallery',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'gallery'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
            'show_in_rest'       => true  // Enables the Gutenberg editor for this post type
        );

        register_post_type('gallery', $args);
    }

    public function register_gallery_taxonomy()
    {
        $labels = array(
            'name'                       => _x('Gallery Categories', 'taxonomy general name', 'ziba'),
            'singular_name'              => _x('Gallery Category', 'taxonomy singular name', 'ziba'),
            'search_items'               => __('Search Gallery Categories', 'ziba'),
            'popular_items'              => __('Popular Gallery Categories', 'ziba'),
            'all_items'                  => __('All Gallery Categories', 'ziba'),
            'parent_item'                => __('Parent Gallery Category', 'ziba'),
            'parent_item_colon'          => __('Parent Gallery Category:', 'ziba'),
            'edit_item'                  => __('Edit Gallery Category', 'ziba'),
            'update_item'                => __('Update Gallery Category', 'ziba'),
            'add_new_item'               => __('Add New Gallery Category', 'ziba'),
            'new_item_name'              => __('New Gallery Category Name', 'ziba'),
            'separate_items_with_commas' => __('Separate gallery categories with commas', 'ziba'),
            'add_or_remove_items'        => __('Add or remove gallery categories', 'ziba'),
            'choose_from_most_used'      => __('Choose from the most used gallery categories', 'ziba'),
            'not_found'                  => __('No gallery categories found.', 'ziba'),
            'menu_name'                  => __('Gallery Categories', 'ziba'),
        );

        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'show_admin_column'     => true,
            'show_in_rest'      => true, // Needed for Gutenberg to show taxonomy in the editor
            'query_var'             => true,
            'rewrite'               => array('slug' => 'gallery-category'),
        );

        register_taxonomy('gallery_category',array('gallery'), $args);
    }
}