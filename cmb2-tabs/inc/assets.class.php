<?php

namespace cmb2_tabs\inc;

class Assets {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
	}


	public function admin_assets() {
		// Css
		wp_enqueue_style( 'dtheme-cmb2-tabs', get_template_directory_uri() . '/inc/core/cmb2-tabs/assets/css/cmb2-tabs.css', array(), '1.0.1' );

		// Js
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'dtheme-cmb2-tabs', get_template_directory_uri() . '/inc/core/cmb2-tabs/assets/js/cmb2-tabs.js', array( 'jquery-ui-tabs' ) );
	}

}