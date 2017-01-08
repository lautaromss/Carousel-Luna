<?php

/*
@package mlabs theme

	=============================
		ADMIN ENQUEUE FUNCTIONS
	=============================
*/

/* 
 * Load admin CSS styles and JS scripts
*/
function mlabs_load_carousel_admin_scripts( $hook ) {

	// The hook variable represents our current page.
	if ( 'toplevel_page_mlabs_carousels_page' !== $hook ) {
		return;
	}

	wp_register_style( 'mlabs_carousel_admin_css', plugins_url( 'css/mlabs-carousel-admin.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );
	wp_enqueue_style( 'mlabs_carousel_admin_css' );

	wp_register_style( 'mlabs_owl_carousel_css', plugins_url( 'css/owl.carousel.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );
	wp_enqueue_style( 'mlabs_owl_carousel_css' );

	wp_enqueue_style( 'open_sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,600,700', false );

	wp_register_script( 'mlabs-admin-script', plugins_url( 'js/mlabs-admin.js', dirname( __FILE__ ) ), array( 'jquery', 'wp-color-picker' ), '1.0.0', true );
	wp_enqueue_script( 'mlabs-admin-script' );

	wp_register_script( 'mlabs-owl-script', plugins_url( 'js/owl.carousel.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.0.0', false );
	wp_enqueue_script( 'mlabs-owl-script' );

	wp_enqueue_style( 'wp-color-picker' );

	wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'mlabs_load_carousel_admin_scripts' );
