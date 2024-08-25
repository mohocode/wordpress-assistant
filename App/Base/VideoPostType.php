<?php 
namespace App\Base;

class VideoPostType
{

    public function __construct() {

        add_action('init', array($this, 'register_video_post_type'));
        add_action('init', array($this, 'register_video_taxonomy'));
    }

    public function register_video_post_type(){

        $labels = array(
            'name'                  => _x('Videos', 'Post type general name', 'ziba'),
            'singular_name'         => _x('Video', 'Post type singular name', 'ziba'),
            'menu_name'             => _x('Videos', 'Admin Menu text', 'ziba'),
            'name_admin_bar'        => _x('Video', 'Add New on Toolbar', 'ziba'),
            'add_new'               => __('Add New', 'ziba'),
            'add_new_item'          => __('Add New Video', 'ziba'),
            'new_item'              => __('New Video', 'ziba'),
            'edit_item'             => __('Edit Video', 'ziba'),
            'view_item'             => __('View Video', 'ziba'),
            'all_items'             => __('All Videos', 'ziba'),
            'search_items'          => __('Search Videos', 'ziba'),
            'parent_item_colon'     => __('Parent Videos:', 'ziba'),
            'not_found'             => __('No videos found.', 'ziba'),
            'not_found_in_trash'    => __('No videos found in Trash.', 'ziba'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'video'),
            'capability_type'    => 'post',
            'menu_icon'             => 'dashicons-format-video',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
            'show_in_rest'       => true,
        );

        register_post_type('video', $args);
    }

    public function register_video_taxonomy(){

        $labels = array(
            'name'              => _x('Video Categories', 'taxonomy general name', 'ziba'),
            'singular_name'     => _x('Video Category', 'taxonomy singular name', 'ziba'),
            'search_items'      => __('Search Video Categories', 'ziba'),
            'all_items'         => __('All Video Categories', 'ziba'),
            'parent_item'       => __('Parent Video Category', 'ziba'),
            'parent_item_colon' => __('Parent Video Category:', 'ziba'),
            'edit_item'         => __('Edit Video Category', 'ziba'),
            'update_item'       => __('Update Video Category', 'ziba'),
            'add_new_item'      => __('Add New Video Category', 'ziba'),
            'new_item_name'     => __('New Video Category Name', 'ziba'),
            'menu_name'         => __('Video Category', 'ziba'),
        );

        $args = array(
            'hierarchical'      => true, // Set to true to allow category-style taxonomy
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'show_in_rest'      => true, // Needed for Gutenberg to show taxonomy in the editor
            'query_var'         => true,
            'rewrite'           => array('slug' => 'video-category'),
        );

        register_taxonomy('video_category', array('video'), $args);
    }
}

