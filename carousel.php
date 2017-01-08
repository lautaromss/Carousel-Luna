<?php
/**
 * @package MlabsCarousel
 * Plugin Name: Mlabs Carousel
 * Description: Responsive, customizable, fast, easy. Manage and display different carousels for your blog, product or service.
 * Plugin URI: http://www.Mlabscarousel.com
 * Author: MLabs
 * Author URI: http://www.Mlabscarousel.com/
 * Version: 1.0
 */

// Load shortcodes.
global $mlabs_carousels_counter;
$mlabs_carousels_counter = -1;
require plugin_dir_path( __FILE__ ) . '/shortcodes/mlabs_carousel.php';
add_shortcode( 'mlabscarousel', 'mlabs_carousel_load' );
echo 'shortcodes loaded';

// Load Carousel BackEnd.
require plugin_dir_path( __FILE__ ) . '/inc/carousel-admin.php';
new MlabsthemeCarousel();

// Our BackEnd CSS and JS enqueues.
require plugin_dir_path( __FILE__ ) . '/inc/admin-carousel-enqueue.php';

// Carousel front-end enqueues.
require plugin_dir_path( __FILE__ ) . '/inc/carousel-enqueue.php';
