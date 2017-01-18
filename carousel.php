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

// Load shortcodes.
global $pixelmold_carousels_counter;
$pixelmold_carousels_counter = -1;
require plugin_dir_path( __FILE__ ) . '/shortcodes/pixelmold-carousel.php';
add_shortcode( 'pixelmoldcarousel', 'pixelmold_carousel_load' );

// Load Carousel BackEnd.
require plugin_dir_path( __FILE__ ) . '/inc/carousel-admin.php';
new pixelmoldthemeCarousel();

// Our BackEnd CSS and JS enqueues.
require plugin_dir_path( __FILE__ ) . '/inc/admin-carousel-enqueue.php';

// Register FrontEnd CSS and JS so it can be loaded by the shortcode when present.
require plugin_dir_path( __FILE__ ) . '/inc/carousel-enqueue.php';
