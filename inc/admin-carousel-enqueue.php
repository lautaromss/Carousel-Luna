<?php

/*
@package pixelmold theme

	=============================
		ADMIN ENQUEUE FUNCTIONS
	=============================
*/

/*
 * Load admin CSS styles and JS scripts
*/
function pixelmold_load_carousel_admin_scripts( $hook ) {

	// The hook variable represents our current page.
	if ( 'toplevel_page_pixelmold_carousels_page' !== $hook ) {
		return;
	}

	$LabelsArray = array(
		'image'   => __('Image'),
		'titleText'  => __('Title text'),
		'descText'  => __('Description text'),
		'buttonText' => __('Button text'),
		'fullURL'   => __('Full URL'),
		'currPrice'  => __('Current price'),
		'oldPrice'  => __('Old price'),
		'fbLink' => __('Facebook link'),
		'twitterLink'   => __('Twitter link'),
		'gplusLink'  => __('Google+ link'),
		'emailLink'  => __('Email link'),
		'optional' => __('Optional.'),
		'optionalSale' => __('Optional, to show a product on sale.'),
	);

	wp_register_style( 'pixelmold_carousel_admin_css', plugins_url( 'css/pixelmold-carousel-admin.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );
	wp_enqueue_style( 'pixelmold_carousel_admin_css' );

	wp_register_style( 'pixelmold_owl_carousel_css', plugins_url( 'css/owl.carousel.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );
	wp_enqueue_style( 'pixelmold_owl_carousel_css' );

	wp_register_style( 'pixelmold_carousel_styled', plugins_url( 'css/pixelmold-carousel.css', dirname( __FILE__ ) ), array( 'pixelmold_owl_carousel_css' ), '1.0.0', 'all' );
	wp_enqueue_style( 'pixelmold_carousel_styled' );

	wp_register_style( 'animate_css', plugins_url( 'css/animate.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );
	wp_enqueue_style( 'animate_css' );

	wp_register_style( 'select2_css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', array(), '1.0.0', 'all' );
	wp_enqueue_style( 'select2_css' );

	wp_register_script( 'select2_js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'select2_js' );

	wp_enqueue_style( 'open_sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,600,700', false );

	wp_register_script( 'pixelmold_admin_script', plugins_url( 'js/pixelmold-admin.js', dirname( __FILE__ ) ), array( 'jquery', 'wp-color-picker' ), '1.0.0', true );
	wp_enqueue_script( 'pixelmold_admin_script' );

	wp_localize_script(
					'pixelmold_admin_script',
					'pixelmoldLabelsArray',
					$LabelsArray
				);

	wp_register_script( 'pixelmold_owl_js', plugins_url( 'js/owl.carousel.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'pixelmold_owl_js' );

	wp_enqueue_style( 'wp-color-picker' );

	wp_register_script( 'lightbox_js', plugins_url( 'js/lightbox.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'lightbox_js' );

	wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'pixelmold_load_carousel_admin_scripts' );
