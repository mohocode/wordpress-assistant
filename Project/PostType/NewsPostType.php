<?php 
namespace Project\PostType;

class NewsPostType {

    public function __construct() {
        add_action('init', array($this, 'register_news_post_type'));
    }

    public function register_news_post_type() {
        $labels = array(
            'name'                  => _x('News', 'Post type general name', 'ziba'),
            'singular_name'         => _x('Gallery', 'Post type singular name', 'ziba'),
            'menu_name'             => _x('News', 'Admin Menu text', 'ziba'),
            'name_admin_bar'        => _x('Gallery', 'Add New on Toolbar', 'ziba'),
            'add_new'               => __('Add New', 'ziba'),
            'add_new_item'          => __('Add New Gallery', 'ziba'),
            'new_item'              => __('New Gallery', 'ziba'),
            'edit_item'             => __('Edit Gallery', 'ziba'),
            'view_item'             => __('View Gallery', 'ziba'),
            'all_items'             => __('All News', 'ziba'),
            'search_items'          => __('Search News', 'ziba'),
            'parent_item_colon'     => __('Parent News:', 'ziba'),
            'not_found'             => __('No News found.', 'ziba'),
            'not_found_in_trash'    => __('No News found in Trash.', 'ziba')
        );
        $args = array(
            'public' => true,
            'labels'             => $labels,
            'supports' => array('title', 'editor','thumbnail'), // Enables only title and description (editor)
            'has_archive' => true,
        );
        register_post_type('news', $args);
    }

 

}

// Instantiate the class to activate the custom post type and script enqueue.
