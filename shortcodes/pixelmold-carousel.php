<?php
function pixelmold_carousel_enqueues() {
	wp_enqueue_script( 'pixelmold_owl_js' );
	wp_enqueue_script( 'lightbox_js' );
	wp_enqueue_style( 'pixelmold_owl_carousel_css' );
	wp_enqueue_style( 'pixelmold_carousel_style' );
	wp_enqueue_style( 'animate_css' );
}

function pixelmold_carousel_load( $atts ) {

	if ( get_post_type( (int) $atts['id'] ) !== 'pixelmold_carousel' ) {
		echo __( 'ERROR: Invalid carousel ID '  . (int) $_GET['post_id'] );
		return;
	}

	$elements = get_post_meta( $atts['id'], 'pixelmold_elements_data', true );
	$pixelmold_c_data = get_post_meta( $atts['id'], 'pixelmold_carousel_data', true );

	global $pixelmold_carousels_counter;
	$pixelmold_carousels_counter++;

	// Fallback function in case styles and scripts haven't been enqueued yet.
	pixelmold_carousel_enqueues();

	ob_start();
	pixelmold_show_carousel( $elements, $pixelmold_c_data, $pixelmold_carousels_counter );
	$output = ob_get_clean();
	return $output;
}

function pixelmold_show_carousel( $elements, $carousel_data, $carousels_counter, $isAjaxRequest = false ) {
	if ( ! is_array( $elements ) ) {
		return;
	}

	// Send carousel info to JS so it can be initialized.
	if ( ! $isAjaxRequest ){
		foreach ( $carousel_data as $carkey => $carvalue ) {
			$car_data[ $carkey . $carousels_counter ] = $carvalue;
		}
		wp_localize_script( 'pixelmold_owl_js', 'pixelmold_carousel', $car_data );
	}

	// Generate custom CSS
	if ( strlen( $carousel_data['primary_font'][2] ) === 4 ) {
		$primary_font_style = 'italic';
	} else {
		$primary_font_style = 'normal';
	}
	$custom_css = '
		.pixelmold-caption-title,
		.pixelmold-testimonial-quote,
		.pixelmold-service-title,
		.pixelmold-card-title,
		.lunacarousel-hero h1 {
			color: ' . esc_attr( $carousel_data['primary_color'] ) . '; 
			font-size: ' . esc_attr( $carousel_data['primary_size'] ) . 'px;
			line-height: ' . esc_attr( $carousel_data['primary_lineheight'] ) . 'px;
			font-family: ' . esc_attr( $carousel_data['primary_font'][1] ) . ';
			font-weight: ' . esc_attr( substr($carousel_data['primary_font'][2], 0, 3 ) ) . ';
			font-style: ' . esc_attr( $primary_font_style ) . ';
		}';

	if ( strlen( $carousel_data['secondary_font'][2] ) === 4 ) {
		$secondary_font_style = 'italic';
	} else {
		$secondary_font_style = 'normal';
	}
	$custom_css .= '
		.pixelmold-caption-name,
		.pixelmold-testimonial-author,
		.pixelmold-card-text,
		.pixelmold-ov-link,
		.pixelmold-ov-lightbox,
		.lunacarousel-hero h3 {
			color: ' . esc_attr( $carousel_data['secondary_color'] ) . '; 
			font-size: ' . esc_attr( $carousel_data['secondary_size'] ) . 'px;
			line-height: ' . esc_attr( $carousel_data['secondary_lineheight'] ) . 'px;
			font-family: ' . esc_attr( $carousel_data['secondary_font'][1] ) . ';
			font-weight: ' . esc_attr( substr($carousel_data['secondary_font'][2], 0, 3 ) ) . ';
			font-style: ' . esc_attr( $secondary_font_style ) . ';
		}

		.pixelmold-c-overlay {
			background: ' . esc_attr( pixelmold_hex_to_rgba( $carousel_data['bgcolor'] ) ) . ';
		}
		.pixelmold_carousel_' . (int) $carousels_counter . '_items:hover .pixelmold-c-overlay,
		.pixelmold_carousel_' . (int) $carousels_counter . '_items:focus .pixelmold-c-overlay {
			animation: ' . '0.3s ' . esc_html( $carousel_data['animation'] ) . ';
		}
		.pixelmold-cart-btn:hover,
		.pixelmold-cart-btn:focus {
			transition: all 0.3s;
			background-color: ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			color: #fff;
		}
		.pixelmold-card:hover, .pixelmold-card:focus {
			border: 1px solid ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			-webkit-box-shadow: 0 0 4px 1px ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			-moz-box-shadow: 0 0 4px 1px ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			box-shadow: 0 0 4px 1px ' . esc_attr( $carousel_data['bgcolor'] ) . ';
		}
		';

	// CSS files were already enqueued in the header,
	// so it's not possible to use wp_add_inline_style here, just echo it.
	echo '<style>' . $custom_css . '</style>';

	if ( ! $isAjaxRequest ) {
		// If the primary font is from Google Fonts, request it.
		if ( 'g' === $carousel_data['primary_font'][0] ) {
			pixelmold_enqueue_google_font( $carousel_data['primary_font'][1], $carousel_data['primary_font'][2] );
		}

		// If the secondary font is from Google Fonts, and it's different from the first one, request it.
		if (
			'g' === $carousel_data['secondary_font'][0] &&
			! ( $carousel_data['secondary_font'][1] == $carousel_data['primary_font'][1] &&
				$carousel_data['secondary_font'][2] == $carousel_data['primary_font'][2] )
		) {
			pixelmold_enqueue_google_font( $carousel_data['secondary_font'][1], $carousel_data['secondary_font'][2], 'secondary' );
		}
	}

	//Print the carousel HTML.
	?>
	<div class="pixelmold-outer-carousel">
		<div id="owl_pixelmold_carousel" class="owl-carousel pixelmold_images_carousel<?php echo $carousels_counter; ?>">
			<?php
			// List all carousel items.
			foreach ( $elements as $element ) {
				$elem_img = wp_get_attachment_image_src( $element['attachid'], $size = 'large' );
				$elem_img_full = wp_get_attachment_image_src( $element['attachid'], $size = 'original' );
				?>

				<div class="children-owl-item pixelmold_carousel_<?php echo (int) $carousels_counter; ?>_items">
				<?php
				switch ( $carousel_data['type'] ) {
					// 0: Images Carousel
					case 0:
						pixelmold_images_carousel( $carousel_data, $element, $elem_img, $elem_img_full );
						break;
					// 1: Slider
					case 1:
					pixelmold_slider_carousel( $carousel_data, $element, $elem_img_full );
					?>
						
						<?php
						break;

					// 2: Flexible Width
					case 2:
						pixelmold_flexible_width_carousel( $carousel_data, $element, $elem_img, $elem_img_full );
						break;

					// 3: Testimonials
					case 3:
					?>
						<div class="pixelmold-testimonials">
							<div class="pixelmold-testminial-image" style="background-image:url('<?php
							echo esc_url( $elem_img[0] ); ?>');"></div>
							<div class="blockquote">
								<div class="pixelmold-testimonial-quote"><?php echo esc_html( $element['desc'] ); ?></div>
								<div class="pixelmold-testimonial-author"><?php echo esc_html( $element['title'] ); ?></div>
							</div>
						</div>
						<?php
						break;

					// 4: Meet our team
					case 4:
						pixelmold_team_carousel( $carousel_data, $element, $elem_img );
						break;

					// 5: Services
					case 5:
						?>
						<div class="pixelmold-service">
							<!--Icons <span class="dashicons-heart pixelmold-ico"></span>-->
							<img class="pixelmold-carousel-image" src="<?php echo esc_url( $elem_img[0] ); ?>">
							<div class="pixelmold-service-title"><?php echo esc_attr( $element['title'] ); ?></div>
							<div class="pixelmold-card-text"><?php echo esc_attr( $element['desc'] ); ?></div>
						</div>	

						<?php
						break;

					// 6: Product
					case 6:
						pixelmold_product_carousel( $carousel_data, $element, $elem_img );
						break;

					// 7: Content Card
					case 7:
						pixelmold_content_carousel( $carousel_data, $element, $elem_img );
						break;
				} // End switch(). ?>
				</div>
			<?php
			} // End foreach(). ?>
		</div>
		<?php if ( true === $carousel_data['navs'] ) { ?>
		<div class="pixelmold-prev-btn pixelmold-carousel-btn <?php echo 'pixelmold_prev_identifier_' . $carousels_counter; ?>"></div>
		<div class="pixelmold-next-btn pixelmold-carousel-btn <?php echo 'pixelmold_next_identifier_' . $carousels_counter; ?>"></div>
		<?php } ?>
	</div>

	<?php
} // End pixelmold_show_carousel().

function pixelmold_images_carousel( $carousel_data, $element, $elem_img, $elem_img_full ) {
	?>
	<img class="pixelmold-zommable pixelmold-carousel-image" src="<?php echo $elem_img[0]; ?>">
	<div class="pixelmold-c-overlay">
		<div class="pixelmold-overlay-content">
			<?php
			if ( '' !== $element['title'] ) { ?>
				<div class="pixelmold-caption-title">
					<?php echo esc_html( $element['title'] ); ?>
				</div>
				<div class="double-separator"></div>
			<?php
			}
			if ( '' !== $element['desc'] ) { ?>
				<div class="pixelmold-caption-name">
					<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
				</div>
			<?php
			}
			if ( '' !== $element['linkurl'] && '#' !== $element['linkurl'] ) { ?>
				<a class="pixelmold-ov pixelmold-ov-link"
					href="<?php echo esc_url( $element['linkurl'] ); ?>">
				</a>
			<?php
			} ?>
			<a class="pixelmold-ov pixelmold-ov-lightbox"
				data-lightbox="image-1" 
				href="<?php echo esc_url( $elem_img_full[0] ); ?>">
			</a>
		</div>
	</div>
	<?php
}

function pixelmold_slider_carousel( $carousel_data, $element, $elem_img_full ) {
	switch ( $carousel_data['style'] ) {
		case 'text_and_button':
			?>
			<img
				src="<?php echo esc_url( $elem_img_full[0] );?>"
				style="width:100%; height:auto;">
			</img>
			<div class="lunacarousel-hero">
				<div class="container">
					<h1 class="animated fadeInDown">
						<?php echo esc_html( $element['title'] ); ?>
					</h1>
					<div class="lunacarousel-separator"></div>
					<h3 class="animated fadeInDown">
						<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
					</h3>
					<a class="lunacarousel-btn lunacarousel-btn-hero animated fadeInUp">
						<?php echo esc_attr( $element['linktext'] ); ?>
					</a>
				</div>
			</div>
			<?php
			break;
		case 'just_images':
			?>
			
			<?php
			break;
	} // End switch().
}

function pixelmold_flexible_width_carousel( $carousel_data, $element, $elem_img, $elem_img_full ) {
	// Get the proportional width
	$elem_img_width = round( $carousel_data['height'] * $elem_img[1] / $elem_img[2] );
	?>
	<img class="pixelmold-zommable" style="
		height: <?php echo esc_attr( $carousel_data['height'] ); ?>px;
		width:<?php echo esc_attr( $elem_img_width ) ?>px;
	" src="<?php echo $elem_img[0]; ?>">
	<div class="pixelmold-c-overlay">
		<div class="pixelmold-overlay-content">
			<?php if ( '' !== $element['title'] ) { ?>
				<div class="pixelmold-caption-title">
					<?php echo esc_html( $element['title'] ); ?>
				</div>
				<div class="double-separator"></div>
			<?php } 
			if ( '' !== $element['desc'] ) { ?>
				<div class="pixelmold-caption-name">
					<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
				</div>
			<?php } ?>
			<?php if ( '' !== $element['linkurl'] && '#' !== $element['linkurl'] ) { ?>
				<a class="pixelmold-ov pixelmold-ov-link"
					href="<?php echo esc_url( $element['linkurl'] ); ?>">
				</a>
			<?php } ?>
			<a class="pixelmold-ov pixelmold-ov-lightbox"
				data-lightbox="image-2"
				href="<?php echo esc_url( $elem_img_full[0] ); ?>">
			</a>
		</div>
	</div>
	<?php
}

function pixelmold_team_carousel( $carousel_data, $element, $elem_img ) {
	?>
	<img class="pixelmold-zommable" src="<?php echo esc_url( $elem_img[0] ); ?>"></img>
	<div class="pixelmold-black-overlay">
		<div class="pixelmold-overlay-caption">
			<?php if ( '' !== $element['title'] ) { ?>
				<div class="pixelmold-caption-title">
					<?php echo esc_html( $element['title'] ); ?>
				</div>
				<div class="double-separator"></div>
			<?php } 
			if ( '' !== $element['desc'] ) { ?>
				<div class="pixelmold-caption-name">
					<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
				</div>
			<?php
			}
			if ( '' !== $element['facebook'] ) { ?>
				<a class="pixelmold-socialov dashicons-facebook" href="<?php echo esc_attr( $element['facebook'] ); ?>"></a>
			<?php
			}
			if ( '' !== $element['twitter'] ) {
			?>
				<a class="pixelmold-socialov dashicons-twitter" href="<?php echo esc_attr( $element['twitter'] ); ?>"></a>
			<?php
			}
			if ( '' !== $element['googleplus'] ) { ?>
				<a class="pixelmold-socialov dashicons-googleplus" href="<?php echo esc_attr( $element['googleplus'] ); ?>"></a>
			<?php
			}
			if ( '' !== $element['email'] ) { ?>
				<a class="pixelmold-socialov dashicons-email" href="<?php echo esc_attr( $element['email'] ); ?>"></a>
			<?php
			} ?>
		</div>
	</div>
	<?php
}

function pixelmold_product_carousel( $carousel_data, $element, $elem_img ) {
	switch ( $carousel_data['style'] ) {
		case 'products_cart':
			?><div class="pixelmold-card">
				<div class="pixelmold-card-block">
					<a <?php echo pixelmold_escape_href_url( $element['linkurl'] ); ?>" class="pixelmold-card-title">
							<?php echo esc_attr( $element['title'] ); ?>
					</a>
					<img class="pixelmold-product-img-top" src="<?php echo esc_url( $elem_img[0] ); ?>">
					<div class="pixelmold-card-text">
						<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
					</div>
					<div class="pixelmold-product-footer">
						<div class="pixelmold-old-price">
							$<?php echo esc_attr( $element['old_price'] ); ?>
						</div>
						<div class="pixelmold-new-price">
							$<?php echo esc_attr( $element['price'] ); ?>
						</div>
						<?php if ( esc_url( $element['linkurl'] ) !== '' ) {?>
							<a href="<?php echo esc_url( $element['linkurl'] ); ?>" class="pixelmold-cart-btn">
								<span class="dashicons dashicons-cart"></span>
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
			break;
		case 'products_button':
			?><div class="pixelmold-card">
				<div class="pixelmold-card-block">
					<a class="pixelmold-card-title">
							<?php echo esc_attr( $element['title'] ); ?>
					</a>
					<img class="pixelmold-product-img-top" src="<?php echo esc_url( $elem_img[0] ); ?>">
					<div class="pixelmold-card-text">
						<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
					</div>
					<div class="pixelmold-product-footer">
						<div class="pixelmold-old-price">
							$<?php echo esc_attr( $element['old_price'] ); ?>
						</div>
						<div class="pixelmold-new-price">
							$<?php echo esc_attr( $element['price'] ); ?>
						</div>
						<?php if ( esc_url( $element['linkurl'] ) !== '' ) {?>
							<a href="<?php echo esc_url( $element['linkurl'] ); ?>" class="pixelmold-btn-card pixelmold-pull-right">
								<?php echo esc_attr( $element['linktext'] ); ?>
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
			break;
	} // End switch().
} // End pixelmold_content_carousel().

function pixelmold_content_carousel( $carousel_data, $element, $elem_img ) {
	?>
	<div class="pixelmold-card">
		<img class="pixelmold-card-img-top" src="<?php echo esc_url( $elem_img[0] ); ?>">
		<div class="pixelmold-card-block">
			<a <?php echo pixelmold_escape_href_url( $element['linkurl'] ); ?>" class="pixelmold-card-title">
				<?php echo esc_attr( $element['title'] ); ?>
			</a>
			<div class="pixelmold-card-text">
				<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
			</div>

			<?php if ( esc_url( $element['linkurl'] ) !== '' ) {?>
				<a href="<?php echo esc_url( $element['linkurl'] ); ?>" class="pixelmold-btn-card">
					<?php echo esc_attr( $element['linktext'] ); ?>
				</a>
			<?php } ?>
		</div>
	</div>
	<?php
} // End pixelmold_content_carousel().

function pixelmold_hex_to_rgba( $hex ) {
	$hex = str_replace( '#', '', $hex );

	if ( 3 === strlen( $hex ) ) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}

	$rgba = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . '0.7)';

	return $rgba;
}

function pixelmold_escape_href_url( $url ) {
	$url = esc_url( $url );
	if ( '' === $url ) {
		$href = '';
	} else {
		$href = 'href="' . $url . '"';
	}
	return $href;
}

function pixelmold_enqueue_google_font( $font, $weight, $instance = 'primary' ) {

	$font = esc_attr( $font );
	$weight = esc_attr( $weight );
	wp_register_style(
		'pixelmold_font_' . $instance,
		'https://fonts.googleapis.com/css?family=' . $font . ':' . $weight,
		array(),
		'1.0.0',
		'all'
	);
	wp_enqueue_style( 'pixelmold_font_' . $instance );

}
