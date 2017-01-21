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

	pixelmold_carousel_enqueues();

	pixelmold_show_carousel( $elements, $pixelmold_c_data, $pixelmold_carousels_counter );
}

function pixelmold_show_carousel( $elements, $carousel_data, $carousels_counter ) {
	if ( ! is_array( $elements ) ) {
		return;
	}

	$custom_css = '';

	$custom_css .= '
		.pixelmold_carousel_' . (int) $carousels_counter . '_items:hover .pixelmold-c-overlay,
		.pixelmold_carousel_' . (int) $carousels_counter . '_items:focus .pixelmold-c-overlay {
			animation: ' . '0.3s ' . esc_html( $carousel_data['animation'] ) . ';
		}
		.pixelmold-cart-btn:hover,
		.pixelmold-cart-btn:focus {
			transition: all 0.3s;
			background-color: ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			color: #fff
		}
		.pixelmold-card:hover, .pixelmold-card:focus {
			border: 1px solid ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			-webkit-box-shadow: 0 0 4px 1px ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			-moz-box-shadow: 0 0 4px 1px ' . esc_attr( $carousel_data['bgcolor'] ) . ';
			box-shadow: 0 0 4px 1px ' . esc_attr( $carousel_data['bgcolor'] ) . ';
		}
		';

	// wp_add_inline_style( 'pixelmold_carousel_style', $custom_css );

	echo '<style scoped>' . $custom_css . '</style>';

	//If the font is from Google Fonts, request it.
	if ( 'g' === $carousel_data['primary_font'][0] ) {
		pixelmold_enqueue_google_font( $carousel_data['primary_font'][1], $carousel_data['primary_font'][2] );
	}

	//If the font is from Google Fonts, request it.
	if (
		'g' === $carousel_data['secondary_font'][0] &&
		$carousel_data['secondary_font'][1] !== $carousel_data['primary_font'][1]
	) {
		pixelmold_enqueue_google_font( $carousel_data['secondary_font'][1], $carousel_data['secondary_font'][2], 'secondary' );
	}
?>
<div class="pixelmold-outer-carousel">
	<div id="owl_pixelmold_carousel" class="owl-carousel pixelmold_images_carousel<?php echo $carousels_counter; ?>">
		<?php
		// LIST ALL CAROUSEL ITEMS
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
				?>
					<img
						src="<?php echo esc_url( $elem_img_full[0] );?>"
						style="width:100%; height:auto;">
					</img>
					<div class="hero">
						<div class="container">
							<h1 class="animated fadeInDown">MAKE BEAUTIFUL AND RESPONSIVE</br> CAROUSELS</h1>
							<div class="separator"></div>
							<!--<button class="btn btn-hero btn-md animated fadeInUp">Learn More</button>-->
							<div class="pixelmold-info">
								<div class="">
									<a href="http://www.google.com/" class="pixelmold-doublebutton" style="
										border-top-left-radius: 100px;
										border-bottom-left-radius: 100px;
										border-right-width: 1px;
									">View Demos
									</a><span class="button-or">or</span><a href="#our-themes" class="pixelmold-doublebutton" style="
										border-top-right-radius: 100px;
										border-bottom-right-radius: 100px;
										border-left-width: 1px;
									">Buy Now</a>
								</div>
							</div>
						</div>
					</div>
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
// The only piece of javascript that we can't enqueue.
?>
<script>
	jQuery(document).ready(function() {
		"use strict"
		var owl = jQuery('.pixelmold_images_carousel<?php echo $carousels_counter; ?>');
		owl.owlCarousel({
			animateOut: 'slideOutDown',
			animateIn: 'flipInX',
			margin:0,
			loop:true,
			dots:<?php echo $carousel_data['dots'] ? 'true' : 'false'; ?>,
			autoplay:<?php echo $carousel_data['autoplay'] ? 'true' : 'false'; ?>,
			autoplayTimeout:<?php echo esc_js( $carousel_data['autoplayms'] ); ?>,

			//autowidth
			<?php if ( 2 === $carousel_data['type'] ) { ?>
				autoWidth:true,
			<?php } else { ?>
				autoWidth:false,
			<?php } ?>

			// items
				
			<?php if ( 1 === $carousel_data['type'] ) { ?>
				items:1,
			<?php } elseif ( 2 === $carousel_data['type'] ) { ?>
				items:<?php echo esc_js( ( (int) $carousel_data['count'] * 5 ) );?>
			<?php } else { ?>
				responsiveClass:true,
				responsive:{
					0:{
						items:<?php echo esc_js( $carousel_data['items_phone'] );?>,
					},
					768:{
						items:<?php echo esc_js( $carousel_data['items_tablet'] );?>,
					},
					992:{
						items:<?php echo esc_js( $carousel_data['items'] );?>,
					}
				}
			<?php } ?>
		});


		<?php if ( true === $carousel_data['navs'] ) { ?>
		// Go to the next item
		jQuery('.<?php echo 'pixelmold_next_identifier_' . $carousels_counter; ?>').click(function() {
			owl.trigger('next.owl.carousel');
		})
		// Go to the previous item
		jQuery('.<?php echo 'pixelmold_prev_identifier_' . $carousels_counter; ?>').click(function() {
			owl.trigger('prev.owl.carousel');
		})
		<?php } ?>
	});
</script>

<?php
}  // End of function pixelmold_show_carousel

function pixelmold_images_carousel( $carousel_data, $element, $elem_img, $elem_img_full ) {
	?>
	<img class="pixelmold-zommable pixelmold-carousel-image" src="<?php echo $elem_img[0]; ?>">
	<div class="pixelmold-c-overlay" style="
		background: <?php echo esc_attr( pixelmold_hex_to_rgba( $carousel_data['bgcolor'] ) ); ?>;
		">
		<div class="pixelmold-overlay-content">
			<?php
			if ( '' !== $element['title'] ) { ?>
				<div class="pixelmold-caption-title" style="
					color:<?php echo esc_attr( $carousel_data['primary_color'] ); ?>; 
					font-size: <?php echo esc_attr( $carousel_data['primary_size'] ); ?>px;
					line-height: <?php echo esc_attr( $carousel_data['primary_lineheight'] ); ?>px;
					font-family: <?php echo esc_attr( $carousel_data['primary_font'][1] ); ?>;
					font-weight: <?php echo esc_attr( $carousel_data['primary_font'][2] ); ?>;
				">
					<?php echo esc_html( $element['title'] ); ?>
				</div>
				<div class="double-separator"></div>
			<?php
			}
			if ( '' !== $element['desc'] ) { ?>
				<div class="pixelmold-caption-name" style="
					color:<?php echo esc_attr( $carousel_data['secondary_color'] ); ?>;
					font-size: <?php echo esc_attr( $carousel_data['secondary_size'] ); ?>px;
					line-height: <?php echo esc_attr( $carousel_data['secondary_lineheight'] ); ?>px;
					font-family: <?php echo esc_attr( $carousel_data['secondary_font'][1] ); ?>;
					font-weight: <?php echo esc_attr( $carousel_data['secondary_font'][2] ); ?>;
				">
					<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
				</div>
			<?php
			}
			if ( '' !== $element['linkurl'] && '#' !== $element['linkurl'] ) { ?>
				<a class="pixelmold-ov pixelmold-ov-link"
					style="color:<?php echo esc_attr( $carousel_data['secondary_color'] ); ?>;"
					href="<?php echo esc_url( $element['linkurl'] ); ?>">
				</a>
			<?php
			} ?>
			<a class="pixelmold-ov pixelmold-ov-lightbox"
				style="color:<?php echo esc_attr( $carousel_data['secondary_color'] ); ?>;"
				data-lightbox="image-1" 
				href="<?php echo esc_url( $elem_img_full[0] ); ?>">
			</a>
		</div>
	</div>
	<?php
}

function pixelmold_flexible_width_carousel( $carousel_data, $element, $elem_img, $elem_img_full ) {
	// Get the proportional width
	$elem_img_width = round( $carousel_data['height'] * $elem_img[1] / $elem_img[2] );
	?>
	<img class="pixelmold-zommable" style="
		height: <?php echo esc_attr( $carousel_data['height'] ); ?>px;
		width:<?php echo esc_attr( $elem_img_width ) ?>px;
	" src="<?php echo $elem_img[0]; ?>">
	<div class="pixelmold-c-overlay" style="background:<?php echo esc_attr( pixelmold_hex_to_rgba( $carousel_data['bgcolor'] ) ); ?>">
		<div class="pixelmold-overlay-content">
			<?php if ( '' !== $element['title']  || '' !== $element['desc'] ) { ?>
				<div class="pixelmold-caption-title" style="
					color:<?php echo esc_attr( $carousel_data['primary_color'] ); ?>; 
					font-size: <?php echo esc_attr( $carousel_data['primary_size'] ); ?>px;
					line-height: <?php echo esc_attr( $carousel_data['primary_lineheight'] ); ?>px;
					font-family: <?php echo esc_attr( $carousel_data['primary_font'][1] ); ?>;
					font-weight: <?php echo esc_attr( $carousel_data['primary_font'][2] ); ?>;
					">
					<?php echo esc_html( $element['title'] ); ?>
				</div>
				<div class="double-separator"></div>
				<div class="pixelmold-caption-name" style="
					color:<?php echo esc_attr( $carousel_data['secondary_color'] ); ?>; 
					font-size: <?php echo esc_attr( $carousel_data['secondary_size'] ); ?>px;
					line-height: <?php echo esc_attr( $carousel_data['secondary_lineheight'] ); ?>px;
					font-family: <?php echo esc_attr( $carousel_data['secondary_font'][1] ); ?>;
					font-weight: <?php echo esc_attr( $carousel_data['secondary_font'][2] ); ?>;
				">
					<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
				</div>
			<?php } ?>
			<?php if ( '' !== $element['linkurl'] && '#' !== $element['linkurl'] ) { ?>
				<a class="pixelmold-ov pixelmold-ov-link"
					style="color:<?php echo esc_attr( $carousel_data['secondary_color'] ); ?>;"
					href="<?php echo esc_url( $element['linkurl'] ); ?>">
				</a>
			<?php } ?>
			<a class="pixelmold-ov pixelmold-ov-lightbox"
				style="color:<?php echo esc_attr( $carousel_data['secondary_color'] ); ?>;"
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
			<div class="pixelmold-caption-title" style="
				color:<?php echo esc_attr( $carousel_data['primary_color'] ); ?>; 
				font-size: <?php echo esc_attr( $carousel_data['primary_size'] ); ?>px;
				line-height: <?php echo esc_attr( $carousel_data['primary_lineheight'] ); ?>px;
				font-family: <?php echo esc_attr( $carousel_data['primary_font'][1] ); ?>;
				font-weight: <?php echo esc_attr( $carousel_data['primary_font'][2] ); ?>;
			">
				<?php echo esc_html( $element['title'] ); ?>
			</div>
			<div class="double-separator"></div>
			<div class="pixelmold-caption-name" style="
					color:<?php echo esc_attr( $carousel_data['secondary_color'] ); ?>; 
					font-size: <?php echo esc_attr( $carousel_data['secondary_size'] ); ?>px;
					line-height: <?php echo esc_attr( $carousel_data['secondary_lineheight'] ); ?>px;
					font-family: <?php echo esc_attr( $carousel_data['secondary_font'][1] ); ?>;
					font-weight: <?php echo esc_attr( $carousel_data['secondary_font'][2] ); ?>;
			">
				<?php echo nl2br( esc_textarea( $element['desc'] ) ); ?>
			</div>
			<?php
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
					<a style="
						color:<?php echo esc_attr( $carousel_data['primary_color'] ); ?>; 
						font-size: <?php echo esc_attr( $carousel_data['primary_size'] ); ?>px;
						line-height: <?php echo esc_attr( $carousel_data['primary_lineheight'] ); ?>px;
						font-family: <?php echo esc_attr( $carousel_data['primary_font'][1] ); ?>;
						font-weight: <?php echo esc_attr( $carousel_data['primary_font'][2] ); ?>;
						" <?php echo pixelmold_escape_href_url( $element['linkurl'] ); ?>" class="pixelmold-card-title">
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
					<a style="
						color:<?php echo esc_attr( $carousel_data['primary_color'] ); ?>; 
						font-size: <?php echo esc_attr( $carousel_data['primary_size'] ); ?>px;
						line-height: <?php echo esc_attr( $carousel_data['primary_lineheight'] ); ?>px;
						font-family: <?php echo esc_attr( $carousel_data['primary_font'][1] ); ?>;
						font-weight: <?php echo esc_attr( $carousel_data['primary_font'][2] ); ?>;
						" <?php echo pixelmold_escape_href_url( $element['linkurl'] ); ?>" class="pixelmold-card-title">
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
