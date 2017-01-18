<?php

class pixelmoldthemeCarousel {


	function __construct() {

		// Register post type.
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Check if we have to update a carousel, then save.
		add_action( 'init', array( $this, 'save_carousel_options' ) );

		// Add admin menu.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	function default_carousel_options() {

		$default_options = array(
			'title' => '',
			'type' => 2, // 0: Images Carousel, 1: Slider, 2: Flexible Width, 3: Testimonials, 4: Meet our eam, 5: Services/logo, 6: Product, 7: Content card.
			'style' => 0,
			'count' => 0,
			'dots' => false, // Dots navigation. 0: None, 1: Inside, 2: Outside.
			'navs' => true, // Navigation arrows. 0: None, 1: Inside, 2: Outside.
			'bgcolor' => '#000',
			'items' => 5, // Amount of items displayed at once in the desktop size.
			'items_tablet' => 3,
			'items_phone' => 2,
			'autoplay' => false,
			'autoplayms' => 4000,
			'stop_on_hover' => true, // Not yet implemented
			'speed' => 300,
			'fixedheight' => false, // Not yet implemented
			'height' => 200, // In pixels
			'primary_font' => array( 'g', 'Raleway', '900', 'Ultra-Bold 900' ),
			'primary_size' => 24, // In pixels
			'primary_color' => '#fff',
			'primary_lineheight' => 24,
			'secondary_font' => array( 'g', 'Open Sans', '400', 'Normal 400' ),
			'secondary_size' => 16, // In pixels
			'secondary_color' => '#fff',
			'secondary_lineheight' => 16,
			'animation' => 'fadeIn',
			);

		return $default_options;
	}

	function default_element_options() {

		$element = array(
				'id' => 0,
				'type' => 'image',
				'title' => '',// Also serves as author in the testimonials type carousel.
				'desc' => '', // Also serves as testimonial text/quote.
				'icon' => '',
				'icon_color' => '',
				'linkurl' => '#',
				'linktext' => 'See more',
				'attachid' => '',
				'videotype' => 'html5',
				'videourl' => '',
				'facebook' => '',
				'twitter' => '',
				'googleplus' => '',
				'email' => '',
				'price' => '',
				'old_price' => '',
			);

		return $element;
	}

	function register_post_type() {

		$labels = array(
				'name'               => _x( 'Carousels', 'post type general name', 'pixelmoldtheme' ),
				'singular_name'      => _x( 'Carousel', 'post type singular name', 'pixelmoldtheme' ),
				'menu_name'          => _x( 'Carousels', 'admin menu', 'pixelmoldtheme' ),
				'name_admin_bar'     => _x( 'Carousel', 'add new on admin bar', 'pixelmoldtheme' ),
				'add_new'            => _x( 'Add New', 'Carousel', 'pixelmoldtheme' ),
				'add_new_item'       => __( 'Add New Carousel', 'pixelmoldtheme' ),
				'new_item'           => __( 'New Carousel', 'pixelmoldtheme' ),
				'edit_item'          => __( 'Edit Carousel', 'pixelmoldtheme' ),
				'view_item'          => __( 'View Carousel', 'pixelmoldtheme' ),
				'all_items'          => __( 'All Carousels', 'pixelmoldtheme' ),
				'search_items'       => __( 'Search Carousels', 'pixelmoldtheme' ),
				'parent_item_colon'  => __( 'Parent Carousels:', 'pixelmoldtheme' ),
				'not_found'          => __( 'No Carousels found.', 'pixelmoldtheme' ),
				'not_found_in_trash' => __( 'No Carousels found in Trash.', 'pixelmoldtheme' ),
		);

		$args = array(
				'labels'             => $labels,
		        'description'        => __( 'Description.', 'pixelmoldtheme' ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => false,
				'show_in_menu'       => false,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'pixelmold_carousel' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title' ),
				'show_in_admin_bar'  => false,
		);

		register_post_type( 'pixelmold_carousel', $args );
	}

	function admin_menu() {
		$page_title = __( 'pixelmold Carousels', 'pixelmoldplugin' );
		$menu_title = __( 'Carousels', 'pixelmoldplugin' );
		// $menu_slug = 'edit.php?post_type=pixelmold_carousel';.
		add_menu_page(
			$page_title,
			$menu_title,
			'manage_options',
		  	'pixelmold_carousels_page',
			array( $this, 'carousels_page' )
		);
	}

	// Callback function from admin_menu().
	function carousels_page() {
		// If there is an action to edit or add a carousel.
		if ( isset( $_GET['action'] ) ) {
			// Prepare variables.
			$pixelmold_nonce = wp_create_nonce( 'pixelmold_settings_nonce' );
			$carousel_options = $this->default_carousel_options();
			$pixelmold_elements = array();

			if ( 'edit_carousel' === $_GET['action'] ) {
				// Check post_id for security.
				if ( ! isset( $_GET['post_id'] ) || get_post_type( (int) $_GET['post_id'] ) !== 'pixelmold_carousel' ) {
					echo __( '<br>ERROR: An action to edit a carousel was passed but with an invalid ID.' );
					return;
				}

				// Prepare more variables.
				$pixelmold_post_id = (int) $_GET['post_id'];
				$save_link = admin_url( 'admin.php?page=pixelmold_carousels_page&action=edit_carousel&post_id=' . $pixelmold_post_id );
				$carousel_options = array_merge( $carousel_options, get_post_meta( $pixelmold_post_id, 'pixelmold_carousel_data', true ) );
				$carousel_options['title'] = get_the_title( $pixelmold_post_id );
				$pixelmold_elements = get_post_meta( $pixelmold_post_id, 'pixelmold_elements_data', true );
			} elseif ( 'new_post' === $_GET['action'] ) {
				$save_link = admin_url( 'admin.php?page=pixelmold_carousels_page&action=new_post' );
			}

			// if ( isset( $_POST['submit'] )  )
				// TODO when (succesful?) submit
			// This is the Add/Edit Carousel page.
			require_once( plugin_dir_path( __FILE__ ) . 'templates/pixelmold-admin-carousel-add.php' );

			// Else there is no action to edit or add a carousel.
			// So show the list of existing carousels.
		} else {
			$carousel_del_nonce = wp_create_nonce( 'pixelmold_carousel_del_nonce' );

			$args = array(
				'posts_per_page'   => -1,
				'offset'           => 0,
				'category'         => '',
				'category_name'    => '',
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => '',
				'exclude'          => '',
				'meta_key'         => '',
				'meta_value'       => '',
				'post_type'        => 'pixelmold_carousel',
				'post_mime_type'   => '',
				'post_parent'      => '',
				'author'	       => '',
				'post_status'      => 'private',
				'suppress_filters' => true,
			);

			$carousels = get_posts( $args );

			// This is the carousel list page.
			require_once( plugin_dir_path( __FILE__ ) . 'templates/pixelmold-admin-carousel.php' );
		}// End if().
	}

	function save_carousel_options() {

		// Check if we should delete a carousel.
		if ( isset( $_GET['pixelmold_nonce_del'] ) && 'delete_carousel' === $_GET['action'] ) {
			if ( ! wp_verify_nonce( $_GET['pixelmold_nonce_del'], 'pixelmold_carousel_del_nonce' ) ) {
				die( 'Security Error' );
			}

			if ( get_post_type( (int) $_GET['post_id'] ) !== 'pixelmold_carousel' ) {
				echo __( '<br>SECURITY ERROR: An action to delete a carousel was passed but with an invalid ID.' );
				return;
			}

			wp_delete_post( $_GET['post_id'], true );

			$location = admin_url( 'admin.php?page=pixelmold_carousels_page' );
			wp_redirect( $location );
			return;
		}

		// Check that the submitted form is from the carousel, otherwise exit.
		if ( ! isset( $_POST['pixelmold_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['pixelmold_nonce'], 'pixelmold_settings_nonce' ) ) {
			echo __( '<br>SECURITY ERROR: invalid nonce.' );
		}

		$pixelmold_carousel_dumpdata = $_POST;

		// This variable is used to show errors (like the ones that can be caught during sanitization).
		global $pixelmold_carousel_errors;
		$GLOBALS['pixelmold_carousel_errors'] = array();

		// If the action is to create a new caruosel, create it and redirect to the edit page.
		if ( 'new_post' === $_GET['action'] ) {
			$postarr = array(
					'post_title' => sanitize_text_field( $pixelmold_carousel_dumpdata['title'] ),
					'post_status' => 'private',
					'post_type' => 'pixelmold_carousel',
					'post_author' => 1,
				);
			$pixelmold_post_id = wp_insert_post( $postarr );
			$this->update_carousel( $pixelmold_post_id, $pixelmold_carousel_dumpdata );
			$location = admin_url( 'admin.php?page=pixelmold_carousels_page&action=edit_carousel&post_id=' . $pixelmold_post_id );
			wp_redirect( $location );
			exit;

			// Else the action is to edit a carousel.
		} elseif ( isset( $_GET['post_id'] ) && 'edit_carousel' === $_GET['action'] ) {
			if ( get_post_type( (int) $_GET['post_id'] ) !== 'pixelmold_carousel' ) {
				echo __( '<br>ERROR: An action to edit a carousel was passed but with an invalid ID.' );
				return;
			}

			// First update the title.
			$title = sanitize_text_field( $pixelmold_carousel_dumpdata['title'] );
			$postarr = array(
					'ID' => $_GET['post_id'],
					'post_title' => $title,
				);
			wp_update_post( $postarr );

			// Sanitize and fill the carousel options.
			$this->update_carousel( $_GET['post_id'], $pixelmold_carousel_dumpdata );
		}
	}

	function update_carousel( $pixelmold_post_id, $dumpdata ) {

		$meta_pixelmold_carousel_data = array();
		$meta_pixelmold_carousel_data = $this->pixelmold_sanitation( $dumpdata );
		update_post_meta( $pixelmold_post_id, 'pixelmold_carousel_data', $meta_pixelmold_carousel_data );

		// Finally update the info of each individual element (slides).
		$attachmentids = json_decode( $dumpdata['attachids'] );
		if ( is_array( $attachmentids ) || is_object( $attachmentids ) ) {
			$meta_pixelmold_elements_data = array();
			$i = 0;
			foreach ( $attachmentids as $c_element ) {
				$pixelmold_ele_placeholder = $this->pixelmold_ele_sanitation( $i, $c_element, $dumpdata );
				array_push( $meta_pixelmold_elements_data, $pixelmold_ele_placeholder );
				$i++;
			}
			update_post_meta( $pixelmold_post_id, 'pixelmold_elements_data', $meta_pixelmold_elements_data );
		}
	}

	function pixelmold_ele_sanitation( $id, $c_element, $pixelmold_carousel_dumpdata ) {

		$pixelmold_ele_placeholder['id'] = intval( $id );
		$pixelmold_ele_placeholder['attachid'] = intval( $c_element );
		$pixelmold_ele_placeholder['title'] = sanitize_text_field( $pixelmold_carousel_dumpdata[ 'title' . $id ] );

		// This next implode thing is just a fancy way of sanitizing textareas, keeping the newlines.
		$pixelmold_ele_placeholder['desc'] = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $pixelmold_carousel_dumpdata[ 'desc' . $id ] ) ) );

		$pixelmold_ele_placeholder['linkurl'] = esc_url_raw( $pixelmold_carousel_dumpdata[ 'linkurl' . $id ] );
		$pixelmold_ele_placeholder['linktext'] = sanitize_text_field( $pixelmold_carousel_dumpdata[ 'linktext' . $id ] );

		$pixelmold_ele_placeholder['price'] = intval( $pixelmold_carousel_dumpdata[ 'price' . $id ] );
		$pixelmold_ele_placeholder['old_price'] = intval( $pixelmold_carousel_dumpdata[ 'old_price' . $id ] );

		$pixelmold_ele_placeholder['facebook'] = sanitize_text_field( $pixelmold_carousel_dumpdata[ 'facebook' . $id ] );
		if ( substr( $pixelmold_ele_placeholder['facebook'], 0, 1 ) === '/' ) {
			$pixelmold_ele_placeholder['facebook'] = substr( $pixelmold_ele_placeholder['facebook'], 1 );
		}

		$pixelmold_ele_placeholder['twitter'] = sanitize_text_field( $pixelmold_carousel_dumpdata[ 'twitter' . $id ] );
		if ( substr( $pixelmold_ele_placeholder['twitter'], 0, 1 ) === '@' ) {
			$pixelmold_ele_placeholder['twitter'] = substr( $pixelmold_ele_placeholder['twitter'], 1 );
		}
		$pixelmold_ele_placeholder['googleplus'] = sanitize_text_field( $pixelmold_carousel_dumpdata[ 'googleplus' . $id ] );
		if ( substr( $pixelmold_ele_placeholder['googleplus'], 0, 1 ) === '+' ) {
			$pixelmold_ele_placeholder['googleplus'] = substr( $pixelmold_ele_placeholder['googleplus'], 1 );
		}

		if ( '' !== $pixelmold_carousel_dumpdata[ 'email' . $id ] && sanitize_email( $pixelmold_carousel_dumpdata[ 'email' . $id ] ) === '' ) {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'Email was invalid ' ) . 'on element ' . $id );
		}
		$pixelmold_ele_placeholder['email'] = sanitize_email( $pixelmold_carousel_dumpdata[ 'email' . $id ] );

		return $pixelmold_ele_placeholder;
	}

	function pixelmold_sanitation( $pixelmold_carousel_dumpdata ) {

		// This will prevent errors if some value is missing.
		$required = $this->default_carousel_options();
		$missing = array_diff( array_keys( $required ), array_keys( $pixelmold_carousel_dumpdata ) );
		foreach ( $missing as $m ) {
		    $pixelmold_carousel_dumpdata[ $m ] = $required[ $m ];
		}

		$attachmentids = json_decode( $pixelmold_carousel_dumpdata['attachids'] );
		$pixelmold_sanitized['count'] = count( $attachmentids );

		if ( intval( $pixelmold_carousel_dumpdata['type'] ) >= 0 && intval( $pixelmold_carousel_dumpdata['type'] ) < 10 ) {
			$pixelmold_sanitized['type'] = intval( $pixelmold_carousel_dumpdata['type'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'Error with the type of carousel selected, saved default instead.' ) );
			$pixelmold_sanitized['type'] = $required['type'];
		}

		if ( '' === $pixelmold_carousel_dumpdata['style'] ) {
			$pixelmold_sanitized['style'] = 'content_and_button';
		} else {
			$pixelmold_sanitized['style'] = sanitize_text_field( $pixelmold_carousel_dumpdata['style'] );
		}

		if ( intval( $pixelmold_carousel_dumpdata['height'] ) >= 50 ) {
			$pixelmold_sanitized['height'] = intval( $pixelmold_carousel_dumpdata['height'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'Height of the carousel was not a number higher than a 50, saved default instead.' ) );
			$pixelmold_sanitized['height'] = $required['height'];
		}

		if ( intval( $pixelmold_carousel_dumpdata['items'] ) > 0 ) {
			$pixelmold_sanitized['items'] = intval( $pixelmold_carousel_dumpdata['items'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'Amount of items to show was not a positive number, saved default instead.' ) );
			$pixelmold_sanitized['items'] = $required['items'];
		}

		if ( intval( $pixelmold_carousel_dumpdata['items_tablet'] ) > 0 ) {
			$pixelmold_sanitized['items_tablet'] = intval( $pixelmold_carousel_dumpdata['items_tablet'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'Amount of items to show on tablet was not a positive number, saved default instead.' ) );
			$pixelmold_sanitized['items_tablet'] = $required['items_tablet'];
		}

		if ( intval( $pixelmold_carousel_dumpdata['items_phone'] ) > 0 ) {
			$pixelmold_sanitized['items_phone'] = intval( $pixelmold_carousel_dumpdata['items_phone'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'Amount of items to show on phone was not a positive number, saved default instead.' ) );
			$pixelmold_sanitized['items_phone'] = $required['items_phone'];
		}

		if ( intval( $pixelmold_carousel_dumpdata['autoplayms'] ) >= 200 ) {
			$pixelmold_sanitized['autoplayms'] = intval( $pixelmold_carousel_dumpdata['autoplayms'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'Amount of autoplay miliseconds was not a number higher than 200, saved default instead.' ) );
			$pixelmold_sanitized['autoplayms'] = $required['autoplayms'];
		}

		if ( preg_match( '/^(#[a-f0-9]{3}([a-f0-9]{3})?)$/i', $pixelmold_carousel_dumpdata['bgcolor'] ) ) {
			$pixelmold_sanitized['bgcolor'] = $pixelmold_carousel_dumpdata['bgcolor'];
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'The background color was invalid, saved default instead.' ) );
			$pixelmold_sanitized['bgcolor'] = $required['bgcolor'];
		}

		if ( 'on' === $pixelmold_carousel_dumpdata['navs'] ) {
			$pixelmold_sanitized['navs'] = true;
		} else {
			$pixelmold_sanitized['navs'] = false;
		}

		if ( 'on' === $pixelmold_carousel_dumpdata['dots'] ) {
			$pixelmold_sanitized['dots'] = true;
		} else {
			$pixelmold_sanitized['dots'] = false;
		}

		if ( 'on' === $pixelmold_carousel_dumpdata['autoplay'] ) {
			$pixelmold_sanitized['autoplay'] = true;
		} else {
			$pixelmold_sanitized['autoplay'] = false;
		}

		if ( '' === $pixelmold_carousel_dumpdata['animation'] ) {
			$pixelmold_sanitized['animation'] = 'fadeIn';
		} else {
			$pixelmold_sanitized['animation'] = sanitize_text_field( $pixelmold_carousel_dumpdata['animation'] );
		}

		// The rest of the function is typographys sanitation.
		$pixelmold_variants = array(
			'100' => 'Ultra-Light 100',
			'200' => 'Light 200',
			'300' => 'Book 300',
			'400' => 'Normal 400',
			'500' => 'Medium 500',
			'600' => 'Semi-Bold 600',
			'700' => 'Bold 700',
			'800' => 'Extra-Bold 800',
			'900' => 'Ultra-Bold 900',
			'100i' => 'Ultra-Light 100 Italic',
			'200i' => 'Light 200 Italic',
			'300i' => 'Book 300 Italic',
			'400i' => 'Normal 400 Italic',
			'500i' => 'Medium 500 Italic',
			'600i' => 'Semi-Bold 600 Italic',
			'700i' => 'Bold 700 Italic',
			'800i' => 'Extra-Bold 800 Italic',
			'900i' => 'Ultra-Bold 900 Italic',
			);

		// Primary font
		$pixelmold_font_family = substr( $pixelmold_carousel_dumpdata['primary_font'], 2 );
		$pixelmold_variant = $pixelmold_carousel_dumpdata['primary_variant'];

		if ( substr( $pixelmold_carousel_dumpdata['primary_font'], 0, 1 ) === 'g' ) {
			$pixelmold_font_type = 'g';
			$pixelmold_sanitized['primary_font'] = array( $pixelmold_font_type, $pixelmold_font_family, $pixelmold_variant, $pixelmold_variants[ $pixelmold_variant ] );
		} elseif ( substr( $pixelmold_carousel_dumpdata['primary_font'], 0, 1 ) === 's' ) {
			$pixelmold_font_type = 's';
			$pixelmold_sanitized['primary_font'] = array( $pixelmold_font_type, $pixelmold_font_family, $pixelmold_variant, $pixelmold_variants[ $pixelmold_variant ] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], esc_html__( 'The primary font was invalid.' ) );
			$pixelmold_sanitized['primary_font'] = $required['primary_font'];
		}

		// Secondary font
		$pixelmold_font_family = substr( $pixelmold_carousel_dumpdata['secondary_font'], 2 );
		$pixelmold_variant = $pixelmold_carousel_dumpdata['secondary_variant'];

		if ( substr( $pixelmold_carousel_dumpdata['secondary_font'], 0, 1 ) === 'g' ) {
			$pixelmold_font_type = 'g';
			$pixelmold_sanitized['secondary_font'] = array( $pixelmold_font_type, $pixelmold_font_family, $pixelmold_variant, $pixelmold_variants[ $pixelmold_variant ] );
		} elseif ( substr( $pixelmold_carousel_dumpdata['secondary_font'], 0, 1 ) === 's' ) {
			$pixelmold_font_type = 's';
			$pixelmold_sanitized['secondary_font'] = array( $pixelmold_font_type, $pixelmold_font_family, $pixelmold_variant, $pixelmold_variants[ $pixelmold_variant ] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], esc_html__( 'The secondary font was invalid.' ) );
			$pixelmold_sanitized['secondary_font'] = $required['secondary_font'];
		}

		// Font colors
		if ( preg_match( '/^(#[a-f0-9]{3}([a-f0-9]{3})?)$/i', $pixelmold_carousel_dumpdata['primary_color'] ) ) {
			$pixelmold_sanitized['primary_color'] = $pixelmold_carousel_dumpdata['primary_color'];
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'The primary font color was invalid, saved default instead.' ) );
			$pixelmold_sanitized['primary_color'] = $required['primary_color'];
		}

		if ( preg_match( '/^(#[a-f0-9]{3}([a-f0-9]{3})?)$/i', $pixelmold_carousel_dumpdata['secondary_color'] ) ) {
			$pixelmold_sanitized['secondary_color'] = $pixelmold_carousel_dumpdata['secondary_color'];
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'The secondary font color was invalid, saved default instead.' ) );
			$pixelmold_sanitized['secondary_color'] = $required['secondary_color'];
		}

		// Font line heights
		if ( intval( $pixelmold_carousel_dumpdata['primary_lineheight'] ) > 0 ) {
			$pixelmold_sanitized['primary_lineheight'] = intval( $pixelmold_carousel_dumpdata['primary_lineheight'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'The primary line height was not a number higher than 0, saved default instead.' ) );
			$pixelmold_sanitized['primary_lineheight'] = $required['primary_lineheight'];
		}

		if ( intval( $pixelmold_carousel_dumpdata['secondary_lineheight'] ) > 0 ) {
			$pixelmold_sanitized['secondary_lineheight'] = intval( $pixelmold_carousel_dumpdata['secondary_lineheight'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'The secondary line height of the carousel was not a number higher than 0, saved default instead.' ) );
			$pixelmold_sanitized['secondary_lineheight'] = $required['secondary_lineheight'];
		}

		// Font sizes
		if ( intval( $pixelmold_carousel_dumpdata['primary_size'] ) > 0 ) {
			$pixelmold_sanitized['primary_size'] = intval( $pixelmold_carousel_dumpdata['primary_size'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'The primary font size was not a number higher 0, saved default instead.' ) );
			$pixelmold_sanitized['primary_size'] = $required['primary_size'];
		}
		if ( intval( $pixelmold_carousel_dumpdata['secondary_size'] ) > 0 ) {
			$pixelmold_sanitized['secondary_size'] = intval( $pixelmold_carousel_dumpdata['secondary_size'] );
		} else {
			array_push( $GLOBALS['pixelmold_carousel_errors'], __( 'The secondary font size was not a number higher 0, saved default instead.' ) );
			$pixelmold_sanitized['secondary_size'] = $required['secondary_size'];
		}

		return $pixelmold_sanitized;
	}
}
