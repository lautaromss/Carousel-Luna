<?php

class MlabsthemeCarousel {


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
				'type' => 2, // 0: Images Carousel, 1: Slider, 2: Flexible Width, 3: Testimonials, 4: Meet our team, 5: Services, 6: Product/Content Card.
				'count' => 0,
				'dots' => false, // Dots navigation. 0: None, 1: Inside, 2: Outside.
				'navs' => true, // Navigation arrows. 0: None, 1: Inside, 2: Outside.
				'bgcolor' => '#000',
				'color' => '#fff',
				'items' => 5, // Amount of items displayed at once in the desktop size.
				'items_tablet' => 3,
				'items_phone' => 2,
				'autoplay' => false,
				'autoplayms' => 4000,
				'stop_on_hover' => true,
				'speed' => 300,
				'animation' => 0, // 0 is move, 1 is ease-in-out.
				'fixedheight' => false,
				'height' => 200,
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
			);

		return $element;
	}

	function register_post_type() {

		$labels = array(
				'name'               => _x( 'Carousels', 'post type general name', 'mlabstheme' ),
				'singular_name'      => _x( 'Carousel', 'post type singular name', 'mlabstheme' ),
				'menu_name'          => _x( 'Carousels', 'admin menu', 'mlabstheme' ),
				'name_admin_bar'     => _x( 'Carousel', 'add new on admin bar', 'mlabstheme' ),
				'add_new'            => _x( 'Add New', 'Carousel', 'mlabstheme' ),
				'add_new_item'       => __( 'Add New Carousel', 'mlabstheme' ),
				'new_item'           => __( 'New Carousel', 'mlabstheme' ),
				'edit_item'          => __( 'Edit Carousel', 'mlabstheme' ),
				'view_item'          => __( 'View Carousel', 'mlabstheme' ),
				'all_items'          => __( 'All Carousels', 'mlabstheme' ),
				'search_items'       => __( 'Search Carousels', 'mlabstheme' ),
				'parent_item_colon'  => __( 'Parent Carousels:', 'mlabstheme' ),
				'not_found'          => __( 'No Carousels found.', 'mlabstheme' ),
				'not_found_in_trash' => __( 'No Carousels found in Trash.', 'mlabstheme' ),
		);

		$args = array(
				'labels'             => $labels,
		        'description'        => __( 'Description.', 'mlabstheme' ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => false,
				'show_in_menu'       => false,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'mlabs_carousel' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title' ),
				'show_in_admin_bar'  => false,
		);

		register_post_type( 'mlabs_carousel', $args );
	}

	function admin_menu() {
		$page_title = __( 'MLabs Carousels', 'mlabsplugin' );
		$menu_title = __( 'Carousels', 'mlabsplugin' );
		// $menu_slug = 'edit.php?post_type=mlabs_carousel';.
		add_menu_page(
			$page_title,
			$menu_title,
			'manage_options',
		  	'mlabs_carousels_page',
			array( $this, 'carousels_page' )
		);
	}

	// Callback function from admin_menu().
	function carousels_page() {
		// If there is an action to edit or add a carousel.
		if ( isset( $_GET['action'] ) ) {
			// Prepare variables.
			$mlabs_nonce = wp_create_nonce( 'mlabs_settings_nonce' );
			$carousel_options = $this->default_carousel_options();
			$mlabs_elements = array();

			if ( 'edit_carousel' === $_GET['action'] ) {
				if ( ! isset( $_GET['post_id'] ) || get_post_type( (int) $_GET['post_id'] ) !== 'mlabs_carousel' ) {
					echo __( '<br>ERROR: An action to edit a carousel was passed but with an invalid ID.' );
					return;
				}

				// Prepare more variables.
				$post_id = (int) $_GET['post_id'];
				$save_link = admin_url( 'admin.php?page=mlabs_carousels_page&action=edit_carousel&post_id=' . $post_id );
				$carousel_options = array_merge( $carousel_options, get_post_meta( $post_id, 'mlabs_carousel_data', true ) );
				$carousel_options['title'] = get_the_title( $post_id );
				$mlabs_elements = get_post_meta( $post_id, 'mlabs_elements_data', true );
			} elseif ( 'new_post' === $_GET['action'] ) {
				$save_link = admin_url( 'admin.php?page=mlabs_carousels_page&action=new_post' );
			}

			// if ( isset( $_POST['submit'] )  )
				// TODO when (succesful?) submit
			// This is the Add/Edit Carousel page.
			require_once( plugin_dir_path( __FILE__ ) . 'templates/mlabs-admin-carousel-add.php' );

			// Else there is no action to edit or add a carousel.
			// So show the list of existing carousels.
		} else {
			$carousel_del_nonce = wp_create_nonce( 'mlabs_carousel_del_nonce' );

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
				'post_type'        => 'mlabs_carousel',
				'post_mime_type'   => '',
				'post_parent'      => '',
				'author'	       => '',
				'post_status'      => 'private',
				'suppress_filters' => true,
			);

			$carousels = get_posts( $args );

			// This is the carousel list page.
			require_once( plugin_dir_path( __FILE__ ) . 'templates/mlabs-admin-carousel.php' );
		}// End if().
	}

	function save_carousel_options() {

		// Check if we should delete a carousel.
		if ( isset( $_GET['mlabs_nonce_del'] ) && 'delete_carousel' === $_GET['action'] ) {
			if ( ! wp_verify_nonce( $_GET['mlabs_nonce_del'], 'mlabs_carousel_del_nonce' ) ) {
				die( 'Security Error' );
			}

			if ( get_post_type( (int) $_GET['post_id'] ) !== 'mlabs_carousel' ) {
				echo __( '<br>SECURITY ERROR: An action to delete a carousel was passed but with an invalid ID.' );
				return;
			}

			wp_delete_post( $_GET['post_id'], true );

			$location = admin_url( 'admin.php?page=mlabs_carousels_page' );
			wp_redirect( $location );
			return;
		}

		// Check that the submitted form is from the carousel, otherwise exit.
		if ( ! isset( $_POST['mlabs_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['mlabs_nonce'], 'mlabs_settings_nonce' ) ) {
			echo __( '<br>SECURITY ERROR: invalid nonce.' );
		}

		$mlabs_carousel_dumpdata = $_POST;

		// This variable is used to show errors (like the ones that can be caught during sanitization).
		global $mlabs_carousel_errors;
		$GLOBALS['mlabs_carousel_errors'] = array();

		// If the action is to create a new caruosel, create it and redirect to the edit page.
		if ( 'new_post' === $_GET['action'] ) {
			$postarr = array(
					'post_title' => sanitize_text_field( $mlabs_carousel_dumpdata['title'] ),
					'post_status' => 'private',
					'post_type' => 'mlabs_carousel',
					'post_author' => 1,
				);
			$post_id = wp_insert_post( $postarr );
			$this->update_carousel( $post_id, $mlabs_carousel_dumpdata );
			$location = admin_url( 'admin.php?page=mlabs_carousels_page&action=edit_carousel&post_id=' . $post_id );
			wp_redirect( $location );
			exit;

			// Else the action is to edit a carousel.
		} elseif ( isset( $_GET['post_id'] ) && 'edit_carousel' === $_GET['action'] ) {
			if ( get_post_type( (int) $_GET['post_id'] ) !== 'mlabs_carousel' ) {
				echo __( '<br>ERROR: An action to edit a carousel was passed but with an invalid ID.' );
				return;
			}

			// First update the title.
			$title = sanitize_text_field( $mlabs_carousel_dumpdata['title'] );
			$postarr = array(
					'ID' => $_GET['post_id'],
					'post_title' => $title,
				);
			wp_update_post( $postarr );

			// Sanitize and fill the carousel options.
			$this->update_carousel( $_GET['post_id'], $mlabs_carousel_dumpdata );
		}
	}

	function update_carousel( $post_id, $dumpdata ) {

		$meta_mlabs_carousel_data = array();
		$meta_mlabs_carousel_data = $this->mlabs_sanitation( $dumpdata );
		update_post_meta( $post_id, 'mlabs_carousel_data', $meta_mlabs_carousel_data );

		// Finally update the info of each individual element (slides).
		$attachmentids = json_decode( $dumpdata['attachids'] );
		if ( is_array( $attachmentids ) || is_object( $attachmentids ) ) {
			$meta_mlabs_elements_data = array();
			$i = 0;
			foreach ( $attachmentids as $c_element ) {
				$mlabs_ele_placeholder = $this->mlabs_ele_sanitation( $i, $c_element, $dumpdata );
				array_push( $meta_mlabs_elements_data, $mlabs_ele_placeholder );
				$i++;
			}
			update_post_meta( $post_id, 'mlabs_elements_data', $meta_mlabs_elements_data );
		}
	}

	function mlabs_ele_sanitation( $id, $c_element, $mlabs_carousel_dumpdata ) {

		$mlabs_ele_placeholder['id'] = intval( $id );
		$mlabs_ele_placeholder['attachid'] = intval( $c_element );
		$mlabs_ele_placeholder['title'] = sanitize_text_field( $mlabs_carousel_dumpdata[ 'title' . $id ] );

		// This next implode thing is just a fancy way of sanitizing textareas, keeping the newlines.
		// Credits to http://stackoverflow.com/questions/20444042/wordpress-how-to-sanitize-multi-line-text-from-a-textarea-without-losing-line for it.
		$mlabs_ele_placeholder['desc'] = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $mlabs_carousel_dumpdata[ 'desc' . $id ] ) ) );

		$mlabs_ele_placeholder['linkurl'] = esc_url_raw( $mlabs_carousel_dumpdata[ 'linkurl' . $id ] );
		$mlabs_ele_placeholder['linktext'] = sanitize_text_field( $mlabs_carousel_dumpdata[ 'linktext' . $id ] );

		$mlabs_ele_placeholder['facebook'] = sanitize_text_field( $mlabs_carousel_dumpdata[ 'facebook' . $id ] );
		if ( substr( $mlabs_ele_placeholder['facebook'], 0, 1 ) === '/' ) {
			$mlabs_ele_placeholder['facebook'] = substr( $mlabs_ele_placeholder['facebook'], 1 );
		}

		$mlabs_ele_placeholder['twitter'] = sanitize_text_field( $mlabs_carousel_dumpdata[ 'twitter' . $id ] );
		if ( substr( $mlabs_ele_placeholder['twitter'], 0, 1 ) === '@' ) {
			$mlabs_ele_placeholder['twitter'] = substr( $mlabs_ele_placeholder['twitter'], 1 );
		}
		$mlabs_ele_placeholder['googleplus'] = sanitize_text_field( $mlabs_carousel_dumpdata[ 'googleplus' . $id ] );
		if ( substr( $mlabs_ele_placeholder['googleplus'], 0, 1 ) === '+' ) {
			$mlabs_ele_placeholder['googleplus'] = substr( $mlabs_ele_placeholder['googleplus'], 1 );
		}

		if ( '' !== $mlabs_carousel_dumpdata[ 'email' . $id ] && sanitize_email( $mlabs_carousel_dumpdata[ 'email' . $id ] ) === '' ) {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'Email was invalid ' ) . 'on element ' . $id );
		}
		$mlabs_ele_placeholder['email'] = sanitize_email( $mlabs_carousel_dumpdata[ 'email' . $id ] );

		return $mlabs_ele_placeholder;
	}

	function mlabs_sanitation( $mlabs_carousel_dumpdata ) {

		// This will prevent errors if some value is missing.
		$required = $this->default_carousel_options();
		$missing = array_diff( array_keys( $required ), array_keys( $mlabs_carousel_dumpdata ) );
		foreach ( $missing as $m ) {
		    $mlabs_carousel_dumpdata[ $m ] = $required[ $m ];
		}

		$attachmentids = json_decode( $mlabs_carousel_dumpdata['attachids'] );
		$mlabs_sanitized['count'] = count( $attachmentids );

		if ( intval( $mlabs_carousel_dumpdata['type'] ) >= 0 && intval( $mlabs_carousel_dumpdata['type'] ) < 10 ) {
			$mlabs_sanitized['type'] = intval( $mlabs_carousel_dumpdata['type'] );
		} else {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'Error with the type of carousel selected, saved default instead.' ) );
			$mlabs_sanitized['type'] = $required['type'];
		}

		if ( intval( $mlabs_carousel_dumpdata['height'] ) >= 100 ) {
			$mlabs_sanitized['height'] = intval( $mlabs_carousel_dumpdata['height'] );
		} else {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'The height of the carousel was not a number higher than a 100, saved default instead.' ) );
			$mlabs_sanitized['height'] = $required['height'];
		}

		if ( intval( $mlabs_carousel_dumpdata['items'] ) > 0 ) {
			$mlabs_sanitized['items'] = intval( $mlabs_carousel_dumpdata['items'] );
		} else {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'Amount of items to show was not a positive number, saved default instead.' ) );
			$mlabs_sanitized['items'] = $required['items'];
		}

		if ( intval( $mlabs_carousel_dumpdata['items_tablet'] ) > 0 ) {
			$mlabs_sanitized['items_tablet'] = intval( $mlabs_carousel_dumpdata['items_tablet'] );
		} else {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'Amount of items to show on tablet was not a positive number, saved default instead.' ) );
			$mlabs_sanitized['items_tablet'] = $required['items_tablet'];
		}

		if ( intval( $mlabs_carousel_dumpdata['items_phone'] ) > 0 ) {
			$mlabs_sanitized['items_phone'] = intval( $mlabs_carousel_dumpdata['items_phone'] );
		} else {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'Amount of items to show on phone was not a positive number, saved default instead.' ) );
			$mlabs_sanitized['items_phone'] = $required['items_phone'];
		}

		if ( intval( $mlabs_carousel_dumpdata['autoplayms'] ) >= 200 ) {
			$mlabs_sanitized['autoplayms'] = intval( $mlabs_carousel_dumpdata['autoplayms'] );
		} else {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'Amount of autoplay miliseconds was not a number higher than 200, saved default instead.' ) );
			$mlabs_sanitized['autoplayms'] = $required['autoplayms'];
		}

		if ( preg_match( '/^(#[a-f0-9]{3}([a-f0-9]{3})?)$/i', $mlabs_carousel_dumpdata['bgcolor'] ) ) {
			$mlabs_sanitized['bgcolor'] = $mlabs_carousel_dumpdata['bgcolor'];
		} else {
			array_push( $GLOBALS['mlabs_carousel_errors'], __( 'The background color was invalid, saved default instead.' ) );
			$mlabs_sanitized['bgcolor'] = $required['bgcolor'];
		}

		if ( 'on' === $mlabs_carousel_dumpdata['navs'] ) {
			$mlabs_sanitized['navs'] = true;
		} else {
			$mlabs_sanitized['navs'] = false;
		}

		if ( 'on' === $mlabs_carousel_dumpdata['dots'] ) {
			$mlabs_sanitized['dots'] = true;
		} else {
			$mlabs_sanitized['dots'] = false;
		}

		if ( 'on' === $mlabs_carousel_dumpdata['autoplay'] ) {
			$mlabs_sanitized['autoplay'] = true;
		} else {
			$mlabs_sanitized['autoplay'] = false;
		}

		return $mlabs_sanitized;
	}
}
