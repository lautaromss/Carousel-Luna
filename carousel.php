<?php
/**
 * @package pixelmoldCarousel
 * Plugin Name: pixelmold Carousel
 * Description: Responsive, customizable, fast, easy. Manage and display different carousels for your blog, product or service.
 * Plugin URI: http://www.pixelmoldcarousel.com
 * Author: pixelmold
 * Author URI: http://www.pixelmoldcarousel.com/
 * Version: 1.0
 */

// Load and register shortcode.
// This global variable holds how many carousels had been added to the current page.
global $pixelmold_carousels_counter;
$pixelmold_carousels_counter = -1;
require plugin_dir_path( __FILE__ ) . '/shortcodes/pixelmold-carousel.php';
add_shortcode( 'pixelmoldcarousel', 'pixelmold_carousel_load' );


// Load Carousel BackEnd.
require plugin_dir_path( __FILE__ ) . '/inc/carousel-admin.php';
$pixelmoldCarouselObject = new pixelmoldthemeCarousel();

// Our BackEnd CSS and JS enqueues.
require plugin_dir_path( __FILE__ ) . '/inc/admin-carousel-enqueue.php';

// Register FrontEnd CSS and JS so it can be loaded by the shortcode when present.
require plugin_dir_path( __FILE__ ) . '/inc/carousel-enqueue.php';


// Handle ajax request
add_action( 'wp_ajax_pixelmold_refresh_carousel', 'my_action_callback' );
function my_action_callback() {

	$sanitized_elements = array();

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( __( 'Security error, user does not have enough privilages' ) );
	}

	if ( ! isset($_POST['count']) ) {
		return wp_send_json_error( __( 'Missing data' ) );
	}

	for ( $i = 0; $i < $_POST['count']; $i++ ) {
		$_POST[ 'desc' . $i ] = str_replace( "\'", "'", $_POST[ 'desc' . $i ] );
		$_POST[ 'linktext' . $i ] = str_replace( "\'", "'", $_POST[ 'linktext' . $i ] );
		$_POST[ 'title' . $i ] = str_replace( "\'", "'", $_POST[ 'title' . $i ] );
		$ele_placeholder = pixelmoldthemeCarousel::pixelmold_ele_sanitation( $i, $_POST[ 'attachid' . $i ], $_POST );
		array_push( $sanitized_elements, $ele_placeholder );
	}

	$sanitized_carousel_properties = pixelmoldthemeCarousel::pixelmold_sanitation( $_POST );

	pixelmold_show_carousel( $sanitized_elements, $sanitized_carousel_properties, 0, true );
	wp_die();
}

function test_delete_pls() {
	echo 'well this gets called';
}