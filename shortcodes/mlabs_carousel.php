<?php

function mlabs_carousel_load( $atts ) {

	if ( get_post_type( (int)$atts['id'] ) != 'mlabs_carousel' ) {
		echo __( "ERROR: Invalid carousel ID" );
		echo ('post id was ' . (int)$_GET['post_id']);
		return;
	}

	wp_enqueue_style( 'mlabs_carousel_style' );

	$elements = get_post_meta( $atts['id'], 'mlabs_elements_data', true );
	$mlabs_c_data = get_post_meta( $atts['id'], 'mlabs_carousel_data', true );

	global $mlabs_carousels_counter;
	$mlabs_carousels_counter++;

	echo $mlabs_carousels_counter;

	mlabs_show_carousel( $elements, $mlabs_c_data, $mlabs_carousels_counter );
}

function mlabs_show_carousel( $elements, $mlabs_c_data, $mlabs_carousels_counter ) {

	if ( !is_array( $elements ) ) {
		if ( $mlabs_carousels_counter == 0 ) { ?>
		<script src="<?php echo plugins_url( 'js/lightbox.js', dirname( __FILE__ ) ); ?>"></script>
		<script>
		    lightbox.option({
		      'resizeDuration': 400,
		      'fitImagesInViewport': true
		    })
		</script>
		<?php
		}
		return;
	}
?>
<div class="mlabs-outer-carousel">
	<div id="owl_mlabs_carousel" class="owl-carousel mlabs_images_carousel<?php echo $mlabs_carousels_counter; ?>">
		<?php
		// LIST ALL CAROUSEL ITEMS
		foreach ( $elements as $element ) {
			$mlabs_img = wp_get_attachment_image_src( $element['attachid'], $size = 'large' );
			$mlabs_img_full = wp_get_attachment_image_src( $element['attachid'], $size = 'original' );
			?>

			<div class="children-owl-item">
			<?php
			switch ( $mlabs_c_data['type'] ) {
				// 0: Images Carousel
				case 0:
					?>
					<img class="mlabs-zommable mlabs-carousel-image" src="<?php echo $mlabs_img[0]; ?>">
					<div class="mlabs_c_overlay" style="background:
					<?php echo esc_attr( hex_to_rgba( $mlabs_c_data['bgcolor'] ) ); ?>
					">
						<div class="mlabs-overlay-content">
							<?php if ( $element['title'] !== '' || $element['desc'] !== '' ) { ?>
								<div class="mlabs-caption-title"><?php echo esc_html( $element['title'] ); ?></div>
								<div class="double-separator"></div>
								<div class="mlabs-caption-name"><?php echo esc_html( $element['desc'] ); ?></div>
							<?php } ?>
							<?php if ( $element['linkurl'] != '' && $element['linkurl'] != '#' ) { ?>
								<a class="mlabs-ov mlabs-ov-link" href="<?php echo esc_url( $element['linkurl'] ); ?>"></a>
							<?php } ?>
							<a class="mlabs-ov mlabs-ov-lightbox" data-lightbox="image-1" href="<?php echo esc_url( $mlabs_img_full[0] ); ?>"></a>
						</div>
					</div>
					<?php
					break;
				// 1: Slider
				case 1:
				?>
					<img class="mlabs-carousel-image" src="<?php
					echo esc_url( $mlabs_img[0] ); ?>">
					<?php
					break;

				// 2: Flexible Width
				case 2:
					// Get the proportional width
					$mlabs_img_width = round( $mlabs_c_data['height'] * $mlabs_img[1] / $mlabs_img[2] );
					?>
					<img class="mlabs-zommable" style="
						height: <?php echo esc_attr( $mlabs_c_data['height'] ); ?>px;
						width:<?php echo esc_attr( $mlabs_img_width ) ?>px;
					" src="<?php echo $mlabs_img[0]; ?>">
					<div class="mlabs_c_overlay" style="background:<?php echo esc_attr( hex_to_rgba( $mlabs_c_data['bgcolor'] ) ); ?>">
						<div class="mlabs-overlay-content">
							<?php if ( $element['title'] != '' || $element['desc'] != '' ) { ?>
								<div class="mlabs-caption-title"><?php echo esc_html( $element['title'] ); ?></div>
								<div class="double-separator"></div>
								<div class="mlabs-caption-name"><?php echo nl2br( esc_textarea( $element['desc'] ) ); ?></div>
							<?php } ?>
							<?php if ( $element['linkurl'] != '' && $element['linkurl'] != '#' ) { ?>
								<a class="mlabs-ov mlabs-ov-link" href="<?php echo esc_url( $element['linkurl'] ); ?>"></a>
							<?php } ?>
							<a class="mlabs-ov mlabs-ov-lightbox" data-lightbox="image-2" href="<?php echo esc_url( $mlabs_img_full[0] ); ?>"></a>
						</div>
					</div>
					<?php
					break;

				// 3: Testimonials
				case 3:
				?>
					<div class="mlabs-testimonials">
						<div class="mlabs-testminial-image" style="background-image:url('<?php
						echo esc_url( $mlabs_img[0] ); ?>');"></div>
						<div class="blockquote">
							<div class="mlabs-testimonial-quote"><?php echo esc_html( $element['desc'] ); ?></div>
							<div class="mlabs-testimonial-author"><?php echo esc_html( $element['title'] ); ?></div>
						</div>
					</div>
					<?php
					break;

				// 4: Meet our team
				case 4:
				?>
					<link href="https://fonts.googleapis.com/css?family=Raleway:400,900" rel="stylesheet">
					<img class="mlabs-zommable" src="<?php echo esc_url( $mlabs_img[0] ); ?>"></img>
					<div class="mlabs-black-overlay">
						<div class="mlabs-overlay-caption">
							<div class="mlabs-caption-title"><?php echo esc_html( $element['title'] ); ?></div>
							<div class="double-separator"></div>
							<div class="mlabs-caption-name"><?php echo esc_html( $element['desc'] ); ?></div>
							<?php if ( $element['facebook'] != '' ) { ?>
								<a class="mlabs-socialov dashicons-facebook" href="<?php echo esc_attr( $element['facebook'] ); ?>"></a>
							<?php }
							if ( $element['twitter'] != '' ) {
							?>
								<a class="mlabs-socialov dashicons-twitter" href="<?php echo esc_attr( $element['twitter'] ); ?>"></a>
							<?php
							}
							if ( $element['googleplus'] != '' ) { ?>
								<a class="mlabs-socialov dashicons-googleplus" href="<?php echo esc_attr( $element['googleplus'] ); ?>"></a>
							<?php }
							if ( $element['email'] != '' ) { ?>
								<a class="mlabs-socialov dashicons-email" href="<?php echo esc_attr( $element['email'] ); ?>"></a>
							<?php } ?>
						</div>
					</div>
					<?php
					break;

				// 5: Services
				case 5:
					?>
					<div class="mlabs-service">
						<!--Icons <span class="dashicons-heart mlabs-ico"></span>-->
						<img class="mlabs-carousel-image" src="<?php echo esc_url( $mlabs_img[0] ); ?>">
						<div class="mlabs-service-title"><?php echo esc_attr( $element['title'] ); ?></div>
						<div class="mlabs-card-text"><?php echo esc_attr( $element['desc'] ); ?></div>
					</div>	

					<?php
					break;

				// 6: Product/Content Card
				case 6:
					?>
					<div class="mlabs-card">
						<img class="mlabs-card-img-top" src="<?php echo esc_url( $mlabs_img[0] ); ?>">
						<div class="mlabs-card-block">
							<a <?php echo escape_href_url( $element['linkurl'] ); ?>" class="mlabs-card-title"><?php echo esc_attr( $element['title'] ); ?></a>
							<div class="mlabs-card-text"><?php echo esc_attr( $element['desc'] ); ?></div>
							<?php if ( esc_url( $element['linkurl'] ) !== '' ) {?>
							<a href="<?php echo esc_url( $element['linkurl'] ); ?>" class="mlabs-btn-card"><?php echo esc_attr( $element['linktext'] ); ?></a>
							<?php } ?>
						</div>
					</div>	

					<?php
					break;
			} // End of Switch that checks which carousel to print ?>
			</div>
		<?php } // End of ForEach that prints each element of the carousel ?>
	</div>
	<?php if ( $mlabs_c_data['navs'] == true ) { ?>
	<div class="mlabs-prev-btn mlabs-carousel-btn <?php echo 'mlabs_prev_identifier_'.$mlabs_carousels_counter; ?>"></div>
	<div class="mlabs-next-btn mlabs-carousel-btn <?php echo 'mlabs_next_identifier_'.$mlabs_carousels_counter; ?>"></div>
	<?php } ?>
</div>

<?php if ( $mlabs_carousels_counter == 0 ) { ?>
<script src="<?php echo plugins_url( 'js/lightbox.js', dirname( __FILE__ ) ); ?>"></script>
<script>
	lightbox.option({
		'resizeDuration': 400,
		'fitImagesInViewport': true
	})
</script>
<?php } ?>

<script>

	jQuery(document).ready(function(){
		var owl = jQuery('.mlabs_images_carousel<?php echo $mlabs_carousels_counter; ?>');
		owl.owlCarousel({
			margin:0,
			loop:true,
			dots:<?php echo $mlabs_c_data['dots'] ? 'true' : 'false'; ?>,
			autoplay:<?php echo $mlabs_c_data['autoplay'] ? 'true' : 'false'; ?>,
			autoplayTimeout:<?php echo esc_js( $mlabs_c_data['autoplayms'] ); ?>,

			//autowidth
			<?php if ( $mlabs_c_data['type'] == 2 ) { ?>
				autoWidth:true,
			<?php } else { ?>
				autoWidth:false,
			<?php } ?>

			// items
				
			<?php if ( $mlabs_c_data['type'] == 1 ) { ?>
				items:1,
			<?php } elseif ( $mlabs_c_data['type'] == 2 ) { ?>
				items:<?php echo esc_js( ((int)$mlabs_c_data['count'] * 5) );?>
			<?php } else { ?>
				responsiveClass:true,
				responsive:{
				    0:{
				        items:<?php echo esc_js( $mlabs_c_data['items_phone'] );?>,
				    },
				    768:{
				        items:<?php echo esc_js( $mlabs_c_data['items_tablet'] );?>,
				    },
				    992:{
				        items:<?php echo esc_js( $mlabs_c_data['items'] );?>,
				    }
				}
			<?php } ?>
		});


		<?php if ( $mlabs_c_data['navs'] === true ) { ?>
		// Go to the next item
		jQuery('.<?php echo 'mlabs_next_identifier_'.$mlabs_carousels_counter; ?>').click(function() {
			owl.trigger('next.owl.carousel');
		})
		// Go to the previous item
		jQuery('.<?php echo 'mlabs_prev_identifier_'.$mlabs_carousels_counter; ?>').click(function() {
			owl.trigger('prev.owl.carousel');
		})
		<?php } ?>
	});
</script>

<?php
}  // End of function

function hex_to_rgba( $hex ) {
	$hex = str_replace( "#", "", $hex );

	if ( strlen( $hex ) == 3 ) {
		$r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}

	$rgba = "rgba(".$r.",".$g.",".$b.","."0.7)";

	return $rgba;
}

function escape_href_url( $url ) {
	$url = esc_url( $url );
	if ( $url === '' ) {
		$href = '';
	} else {
		$href = 'href="'.$url.'"';
	}
	return $href;
}