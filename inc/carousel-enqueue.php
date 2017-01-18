<?php

/*
@package pixelmold theme

	=================================
		CAROUSEL FRONT-END ENQUEUE
	=================================
*/

// FRONT-END ENQUEUES
function pixelmold_carousel_front_enqueue() {

	wp_enqueue_script( 'jquery' );

	wp_register_script( 'pixelmold_owl_js', plugins_url( 'js/owl.carousel.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.1.0' );

	wp_register_script( 'lightbox_js', plugins_url( 'js/lightbox.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.1.0' );

	wp_register_style( 'pixelmold_owl_carousel_css', plugins_url( 'css/owl.carousel.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );

	wp_register_style( 'pixelmold_carousel_style', plugins_url( 'css/pixelmold-carousel.css', dirname( __FILE__ ) ), array('pixelmold_owl_carousel_css'), '1.0.0', 'all' );

	wp_register_style( 'animate_css', plugins_url( 'css/animate.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'pixelmold_carousel_front_enqueue' );
