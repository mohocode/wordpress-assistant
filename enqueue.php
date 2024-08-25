<?php
function moho_inc_enqueue() {
    /*:::::::::::Styles:::::::::::*/
    wp_enqueue_style( 'moho.main.css', get_template_directory_uri() . '/moho-inc/assets/main.css', array() );
    /*:::::::::::Js::::::::::::*/
    wp_enqueue_script( 'moho.main.js', get_template_directory_uri() . '/moho-inc/assets/main.js', array(), false, true );
}
add_action( 'wp_enqueue_scripts', 'moho_inc_enqueue' );