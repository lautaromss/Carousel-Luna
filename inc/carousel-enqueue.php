<?php

/*
@package mlabs theme

	=================================
		CAROUSEL FRONT-END ENQUEUE
	=================================
*/

// FRONT-END ENQUEUES
function mlabs_carousel_front_enqueue() {
	wp_register_script( 'mlabs-owl-js', plugins_url( 'js/owl.carousel.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.1.0', false );

	wp_register_style( 'open_sans', '//fonts.googleapis.com/css?family=Open+Sans:400,700', array(), '1.0.0', 'all' );

	wp_register_style( 'mlabs_owl_carousel_css', plugins_url( 'css/owl.carousel.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );

	wp_register_style( 'mlabs_carousel_style', plugins_url( 'css/mlabs-carousel.css', dirname( __FILE__ ) ), array(), '1.0.0', 'all' );
}

add_action( 'wp_enqueue_scripts', 'mlabs_carousel_front_enqueue' );
