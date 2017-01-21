<?php
// Declare necessary variables
$fonts['standard'] = array(
	"Arial, Helvetica, sans-serif"                         => "Arial, Helvetica, sans-serif",
	"'Arial Black', Gadget, sans-serif"                    => "'Arial Black', Gadget, sans-serif",
	"'Bookman Old Style', serif"                           => "'Bookman Old Style', serif",
	"'Comic Sans MS', cursive"                             => "'Comic Sans MS', cursive",
	"Courier, monospace"                                   => "Courier, monospace",
	"Garamond, serif"                                      => "Garamond, serif",
	"Georgia, serif"                                       => "Georgia, serif",
	"Impact, Charcoal, sans-serif"                         => "Impact, Charcoal, sans-serif",
	"'Lucida Console', Monaco, monospace"                  => "'Lucida Console', Monaco, monospace",
	"'Lucida Sans Unicode', 'Lucida Grande', sans-serif"   => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
	"'MS Sans Serif', Geneva, sans-serif"                  => "'MS Sans Serif', Geneva, sans-serif",
	"'MS Serif', 'New York', sans-serif"                   => "'MS Serif', 'New York', sans-serif",
	"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
	"Tahoma,Geneva, sans-serif"                            => "Tahoma, Geneva, sans-serif",
	"'Times New Roman', Times,serif"                       => "'Times New Roman', Times, serif",
	"'Trebuchet MS', Helvetica, sans-serif"                => "'Trebuchet MS', Helvetica, sans-serif",
	"Verdana, Geneva, sans-serif"                          => "Verdana, Geneva, sans-serif",
);

$g_file = '/../googlefonts.php';
$fonts['google'] = include $g_file;?>
<script>var pixelmold_typography = <?php echo wp_json_encode( $fonts ); ?></script>
<?php

// Show user input errors if any
if ( ! empty( $GLOBALS['pixelmold_carousel_errors'] ) ) {
	$pixelmold_error_count = count( $GLOBALS['pixelmold_carousel_errors'] );
	for ( $i = 0; $i < $pixelmold_error_count; $i++ ) {?>
		<div class="notice notice-error is-dismissible" style="max-width:1185px;"> 
			<p><strong><?php echo $GLOBALS['pixelmold_carousel_errors'][ $i ]; ?></strong></p><button type="button" class="notice-dismiss"></button>
		</div>
		<?php
	}
} elseif ( isset( $GLOBALS['pixelmold_carousel_errors'] ) ) {
	?>
	<div class="updated notice is-dismissible" style="max-width:1185px;"> 
		<p><strong><?php echo __( 'Settings saved.' ) ?></strong></p><button type="button" class="notice-dismiss"></button>
	</div>
	<?php
}
?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" id="pixelmold_ele_tabs" role="tablist">
	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
<?php
// Add each tab
for ( $i = 1; $i <= (int) $carousel_options['count']; $i++ ) {  ?>
	<li role="presentation"><a href="#element<?php echo $i; ?>" aria-controls="pixelmold_ele" role="tab" data-toggle="tab">Element <?php echo $i; ?></a><button class="close" type="button" data-identifier="<?php echo $i; ?>" title="Remove this slide">x</button></li>
<?php }
?>
</ul>
<ul class="nav nav-tabs add-tab" role="tablist">
<li><a id="add-pixelmold-tab">Add Element</a></li>
</ul>

<div class="card" style="max-width:1200px; margin-top:0px">
	<form id="pixelmold_carousel_form" action="<?php echo $save_link ?>" method="POST">
		<div class="tab-content" id="pixelmold_ele_tab_content">
			<!-- Main carousel admin tab -->
			<div role="tabpanel" class="tab-pane fade in active" id="home">
				<?php
				// Hidden ID inputs
				if ( 'edit_carousel' === $_GET['action'] ) { ?>
					<input
						type="hidden"
						id="slideridvalue"
						name="postid"
						value="<?php echo intval( $pixelmold_post_id ); ?>"
					>
				<?php
				} else { ?>
					<input
						type="hidden"
						id="slideridvalue"
						name="postid"
						value=""
					>
				<?php
				} ?>
				
				<?php
				// Display Shortcode
				if ( 'edit_carousel' === $_GET['action'] ) { ?>
				<table class="form-table"><tbody>
					<tr>
						<th scope="row">
						Carousel shortcode:
						</th>
						<td>
							<input
								class="pixelmold-carousel-shortcode"
								type="text"
								value="[pixelmoldcarousel id=<?php echo intval( $pixelmold_post_id ); ?>]"
								disabled
							>
						</td>
					</tr>
				</tbody></table>
				<?php
				} ?>
				<div style="font-size:14px;text-align: left;line-height: 1.3;font-weight: 600; margin-bottom:10px;"><?php echo __( 'Preview:' ); ?></div>
				<!-- Show the preview -->
				<div id="pixelmold-carousel-preview" data-cartype="<?php echo $carousel_options['type']; ?>">
					<?php
					pixelmold_show_carousel( $pixelmold_elements, $carousel_options, 0 );
					?>
				</div>
				<p class="description">
					<?php echo __( 'Save changes to refresh the preview.' ); ?>
				</p>
				<?php
				// Upload images row
				?>
				<table class="form-table pixelmold-close-table"><tbody>
					<tr>
						<th scope="row">
							<label for="gallery-button"><?php echo __( 'Carousel items' ); ?></label>
							<span data-toggle="tooltip" title="Upload only the high-resolution version of the image. If necessary, WordPress will automatically create a lower resolution version to use as the non-zoomed version." class="dashicons dashicons-info pixelmold-infobox"></span>
						</th>
						<td>
							<input type="hidden" id="attachids" name="attachids" value="<?php
							if ( ! empty( $pixelmold_elements ) ) {
								echo '[';
								$num_items = count( $pixelmold_elements );
								$i = 0;
								foreach ( $pixelmold_elements as $pixelmold_ele ) {
									echo $pixelmold_ele['attachid'];
									if ( ++$i !== $num_items ) {
										echo ',';
									}
								}
								echo ']';
							}
							?>">
							<input
								type="button"
								class="button"
								value="<?php echo __( 'Add images' ); ?>"
								id="gallery-button"
							>
							<input
								type="button"
								class="button-danger"
								value="<?php echo __( 'Delete ALL items' ); ?>"
								id="delete-button"
							>
							<p class="description">
								<?php echo __( 'Each image will be added as an individual item of the carousel.' ); ?>
							</p>
						</td>
					</tr>

					<?php
					//** Display inputs options **//

					// Name of the carousel
					?>

					
					<tr>
						<th scope="row">
							<label for="pixelmold_carousel_name">
								<?php echo __( 'Carousel Name' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_carousel_name"
								type="text"
								name="title"
								value="<?php echo $carousel_options['title'] ?>"
							>
						</td>
					</tr>

					<script>
					jQuery(document).ready(function(){
						jQuery('[data-toggle="tooltip"]').tooltip(); 
					});
					</script>

					<?php
					// Carousel Type
					$types = array( 'Images Carousel', 'Slider', 'Flexible Width', 'Testimonials', 'Meet our team', 'Services', 'Product', 'Content Card' );
					?>
					<tr>
						<th scope="row">
							<label for="pixelmold_carousel_type"><?php echo __( 'Carousel Type/Layout' ); ?></label>
							<span data-toggle="tooltip" title="Use Images Carousel only for images with the same size proportions. Use flexible width type for images with different size proportions. Consult documentation for more info." class="dashicons dashicons-info pixelmold-infobox"></span>
						</th>
						<td>
							<select id="pixelmold_carousel_type" name="type">
								<?php
								$counted_types = count( $types );
								for ( $i = 0; $i < $counted_types; $i++ ) {
									if ( $i === $carousel_options['type'] ) {
										$pixelmold_state = 'selected="selected"';
									}?>
									<option <?php echo $pixelmold_state; ?> value="<?php echo $i; ?>"><?php echo $types[ $i ]; ?></option>
									<?php
									$pixelmold_state = '';
								}
								?>
							</select>
						</td>
					</tr>

					<?php
					// Carousel Style
					$pixelmold_style = array( 'Cart Icon', 'Text button' );
					$pixelmold_style_ids = array( 'products_cart', 'products_button' );
					

					if ( 6 === $carousel_options['type'] ) {
						$pixelmold_input_state = '';
					} else {
						$pixelmold_input_state = 'class="disabled_input"';
					}?>
					<tr id="pixelmold_carousel_style" <?php echo $pixelmold_input_state; ?>>
						<th scope="row">
							<label for="pixelmold_carousel_style"><?php echo __( 'Carousel style' ); ?></label>
						</th>
						<td>
							<select id="pixelmold_carousel_style_select" name="style">
								<?php
								$counted_style = count( $pixelmold_style );
								for ( $i = 0; $i < $counted_style; $i++ ) {
									if ( $pixelmold_style_ids[ $i ] === $carousel_options['style'] ) {
										$pixelmold_state = 'selected="selected"';
									}?>
									<option <?php echo $pixelmold_state; ?> value="<?php echo $pixelmold_style_ids[ $i ]; ?>"><?php echo $pixelmold_style[ $i ]; ?></option>
									<?php
									$pixelmold_state = '';
								}
								?>
							</select>
						</td>
					</tr>


					<?php
					// Navigation arrows
					?>

					<tr>
						<th scope="row">
							<label for="pixelmold_carousel_navs"><?php echo __( 'Navigation arrows' ); ?></label>
						</th>
						<td>
							<label class="switch">
								<input id="pixelmold_carousel_navs" type="checkbox" name="navs" <?php
								if ( true === $carousel_options['navs'] ) {
									echo 'checked';
								} ?>
								>
								<div class="slider round"></div>
							</label>
						</td>
					</tr>

					<?php
					if ( 2 !== $carousel_options['type'] ) {
						$pixelmold_input_state = '';
					} else {
						$pixelmold_input_state = 'class="disabled_input"';
					}

					// Dots pagination
					?>
					<tr id="pixelmold_dots_pagination" <?php echo $pixelmold_input_state; ?>>
						<th scope="row">
							<label for="pixelmold_carousel_dots"><?php echo __( 'Dots pagination' ); ?></label>
						</th>
						<td>
							<label class="switch">
								<input type="checkbox" id="pixelmold_carousel_dots" name="dots" <?php
								if ( true === $carousel_options['dots'] ) {
									echo 'checked';
								} ?>>
								<div class="slider round"></div>
							</label>
						</td>
					</tr>

					<?php
					if (
						3 !== $carousel_options['type'] &&
						4 !== $carousel_options['type'] &&
						5 !== $carousel_options['type']
					) {
						$pixelmold_input_state = '';
					} else {
						$pixelmold_input_state = 'class="disabled_input"';
					}

					// Colorpick the background overlay
					?>
					<tr id="pixelmold_colorpick" <?php echo $pixelmold_input_state; ?>>
						<th scope="row">
							<label for="pixelmold_carousel_bgcolor"><?php echo __( 'Hover/Overlay Color' ); ?></label>
						</th>
						<td>
							<input class="pixelmold-bgcolor" id="pixelmold_carousel_bgcolor" type="text" name="bgcolor"
								value="<?php echo $carousel_options['bgcolor'] ?>">
						</td>
					</tr>

					<?php
					// Animation
					?>
					<tr id="pixelmold_animation" <?php echo $pixelmold_input_state; ?>>
						<th scope="row">
							<label for="pixelmold_element_anim"><?php echo __( 'Animation' ); ?></label>
						</th>
						<td>
							<div class="anim-field-container">
								<select
									id="pixelmold_element_anim"
									class="pixelmold-anim-field"
									name="animation"
									style="width:100%"
								>
									<optgroup label="Selected">
										<option value="<?php echo $carousel_options['animation']?>">
											<?php echo $carousel_options['animation']?>
										</option>
									<optgroup label="Attention Seekers">
										<option value="bounce">bounce</option>
										<option value="flash">flash</option>
										<option value="pulse">pulse</option>
										<option value="rubberBand">rubberBand</option>
										<option value="shake">shake</option>
										<option value="swing">swing</option>
										<option value="tada">tada</option>
										<option value="wobble">wobble</option>
										<option value="jello">jello</option>
									</optgroup>

									<optgroup label="Bouncing Entrances">
										<option value="bounceIn">bounceIn</option>
										<option value="bounceInDown">bounceInDown</option>
										<option value="bounceInLeft">bounceInLeft</option>
										<option value="bounceInRight">bounceInRight</option>
										<option value="bounceInUp">bounceInUp</option>
									</optgroup>

									<optgroup label="Fading Entrances">
										<option value="fadeIn">fadeIn</option>
										<option value="fadeInDown">fadeInDown</option>
										<option value="fadeInDownBig">fadeInDownBig</option>
										<option value="fadeInLeft">fadeInLeft</option>
										<option value="fadeInLeftBig">fadeInLeftBig</option>
										<option value="fadeInRight">fadeInRight</option>
										<option value="fadeInRightBig">fadeInRightBig</option>
										<option value="fadeInUp">fadeInUp</option>
										<option value="fadeInUpBig">fadeInUpBig</option>
									</optgroup>

									<optgroup label="Flippers">
										<option value="flip">flip</option>
										<option value="flipInX">flipInX</option>
										<option value="flipInY">flipInY</option>
										<option value="flipOutX">flipOutX</option>
										<option value="flipOutY">flipOutY</option>
									</optgroup>

									<optgroup label="Lightspeed">
										<option value="lightSpeedIn">lightSpeedIn</option>
										<option value="lightSpeedOut">lightSpeedOut</option>
									</optgroup>

									<optgroup label="Rotating Entrances">
										<option value="rotateIn">rotateIn</option>
										<option value="rotateInDownLeft">rotateInDownLeft</option>
										<option value="rotateInDownRight">rotateInDownRight</option>
										<option value="rotateInUpLeft">rotateInUpLeft</option>
										<option value="rotateInUpRight">rotateInUpRight</option>
									</optgroup>

									<optgroup label="Sliding Entrances">
										<option value="slideInUp">slideInUp</option>
										<option value="slideInDown">slideInDown</option>
										<option value="slideInLeft">slideInLeft</option>
										<option value="slideInRight">slideInRight</option>
									</optgroup>
									
									<optgroup label="Zoom Entrances">
										<option value="zoomIn">zoomIn</option>
										<option value="zoomInDown">zoomInDown</option>
										<option value="zoomInLeft">zoomInLeft</option>
										<option value="zoomInRight">zoomInRight</option>
										<option value="zoomInUp">zoomInUp</option>
									</optgroup>

									<optgroup label="Specials">
										<option value="rollIn">rollIn</option>
									</optgroup>
								</select>
							</div>
						</td>
					</tr>

					<?php
					// Autoplay
					?>
					<tr>
						<th scope="row">
							<label for="pixelmold_carousel_autoplay"><?php echo __( 'Autoplay' ); ?></label>
						</th>
						<td>
							<label style="margin-right:20px;" class="switch">
								<input type="checkbox" id="pixelmold_carousel_autoplay" name="autoplay" <?php
								if ( true === $carousel_options['autoplay'] ) {
									echo 'checked';
								} ?>>
								<div class="slider round"></div>
							</label>
							<input
								style="max-width:170px;"
								id="pixelmold_carousel_autoplayms"
								type="number"
								min="200"
								name="autoplayms"
								value="<?php echo $carousel_options['autoplayms']; ?>"
							>
							<p class="description">
								<?php echo __( 'Time in miliseconds before changing to the next item' ); ?>	
							</p>
						</td>
					</tr>

					<?php
					// Show only the inputs that correspond to the current carousel type.
					if ( 1 === $carousel_options['type'] ) {
						$pixelmold_input_state = 'disabled_input';
						$pixelmold_input_height = 'disabled_input';
					} elseif ( 2 === $carousel_options['type'] ) {
						$pixelmold_input_state = 'disabled_input';
						$pixelmold_input_height = '';
					} else {
						$pixelmold_input_state = '';
						$pixelmold_input_height = 'disabled_input';
					}
					// Items (Desktop)
					?>
					<tr class="pixelmold_items_class <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_carousel_items"><?php echo __( 'Items to show' ); ?></label>
							<span data-toggle="tooltip" title="This determines how many items will be shown in the carousel at once. The height of your carousel is directly dependant on this value." class="dashicons dashicons-info pixelmold-infobox"></span>
						</th>
						<td>
							<input
								id="pixelmold_carousel_items"
								type="number"
								name="items"
								value="<?php echo $carousel_options['items'] ?>"
							>
						</td>
					</tr>

					<?php
					// Items (Tablet)
					?>
					<tr class="pixelmold_items_class <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_carousel_items_tablet"><?php echo __( 'Items (tablets)' ); ?></label>
							<span
								data-toggle="tooltip" 
								title="This determines how many items will be shown in the carousel at once (on tablets). 
									The height of your carousel is directly dependant on this value." 
								class="dashicons dashicons-info pixelmold-infobox">
							</span>
						</th>
						<td>
							<input
								id="pixelmold_carousel_items_tablet"
								type="number"
								name="items_tablet"
								value="<?php echo $carousel_options['items_tablet'] ?>"
							>
						</td>
					</tr>

					<?php
					// Items (Phone)
					?>
					<tr class="pixelmold_items_class <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_carousel_items_phone"><?php echo __( 'Items (phone)' ); ?></label>
							<span
								data-toggle="tooltip"
								title="This determines how many items will be shown in the carousel at once (on phones).
								The height of your carousel is directly dependant on this value."
								class="dashicons dashicons-info pixelmold-infobox">
							</span>
						</th>
						<td>
							<input
								id="pixelmold_carousel_items_phone"
								type="number"
								name="items_phone"
								value="<?php echo $carousel_options['items_phone'] ?>"
							>
						</td>
					</tr>

					<?php
					// Height
					?>
					<tr class="pixelmold_height_class <?php echo $pixelmold_input_height; ?>">
						<th scope="row">
							<label for="pixelmold_carousel_height"><?php echo __( 'Height (fixed)' ); ?></label>
						</th>
						<td>
							<input
								id="pixelmold_carousel_height"
								type="number"
								min="100"
								name="height"
								value="<?php echo $carousel_options['height'] ?>"
							>
						</td>
					</tr>

					<?php
					// Typographys
					?>

					<tr>
						<th scope="row">
							<label for="pixelmold_carousel_primaryfont"><?php echo __( 'Primary Typography' ); ?></label>
						</th>
						<td>
							<div><div class="pixelmold-typography-container">
								<p class="label-subtitle">
									<?php echo __( 'Font family' ); ?>	
								</p>

								<select
									id="pixelmold_carousel_primaryfont"
									name="primary_font"
									class="pixelmold-font-field"
									style="width: 100%; min-width:220px"
								>
									<?php
									$pixelmold_font_value = $carousel_options['primary_font'][0] . ':' . $carousel_options['primary_font'][1];
									echo '<option value="' . $pixelmold_font_value . '" selected>' . $carousel_options['primary_font'][1] . '</option>';?>
									<optgroup label="Standard Fonts">
										<?php
										foreach ( $fonts['standard'] as $key => $value ) {
											echo '<option value="' . 's:' . $key . '">' . $key . '</option>';
										} ?>
									</optgroup>
									<?php
									if (
										isset( $fonts['google'] ) &&
										! empty( $fonts['google'] ) &&
										is_array( $fonts['google'] ) &&
										false !== $fonts['google']
									) { ?>
										<optgroup label="Google Fonts">
											<?php
											foreach ( $fonts['google'] as $pixelmold_key => $pixelmold_value ) {
												echo '<option value="' . 'g:' . $pixelmold_key .
													'">' . $pixelmold_key . '</option>';
											} ?>
										</optgroup>
									<?php
									} ?>
								</select>
							</div>
							<div class="pixelmold-typography-container">
								<p class="label-subtitle">
									<?php echo __( 'Font Weight/Style' ); ?>	
								</p>
								<select
									id="primary_variant"
									class="pixelmold-variant-field"
									name="primary_variant"
									style="width: 100%; min-width:220px"
								>
									<?php
									if ( 'google' === $carousel_options['primary_font'][0] ) {
										echo '<option value="' . $carousel_options['primary_font'][2] . '" selected>'
											. $carousel_options['primary_font'][3] . '</option>';

										foreach ( $fonts['google'][ $carousel_options['primary_font'][1] ]['variants'] as $pixelmold_key => $pixelmold_variant ) {
											echo '<option value="' . $pixelmold_variant['id'] . '">' . $pixelmold_variant['text'] . '</option>';
										}
									} else {
										echo '<option value="' . $carousel_options['primary_font'][2] . '" selected>'
											. $carousel_options['primary_font'][3] . '</option>';?>
										<option value="400">Normal 400</option>
										<option value="700">Bold 700</option>
										<option value="400i">Normal 400 Italic</option>
										<option value="700i">Bold 700 Italic</option>
									<?php
									} ?>
								</select>
							</div></div>
							<div class="pixelmold-typography-little">
								<p class="label-subtitle">
									<?php echo __( 'Font size' ); ?>	
								</p>
								<input
									id="pixelmold_typography_size"
									type="number"
									min="1"
									name="primary_size"
									value="<?php echo $carousel_options['primary_size'] ?>"
								>
							</div>
							<div class="pixelmold-typography-little">
								<p class="label-subtitle">
									<?php echo __( 'Line Height' ); ?>	
								</p>
								<input
									id="pixelmold_typography_size"
									type="number"
									min="1"
									name="primary_lineheight"
									value="<?php echo $carousel_options['primary_lineheight'] ?>"
								>
							</div>
							<p class="label-subtitle">
								<?php echo __( 'Font color' ); ?>	
							</p>
							<input
								class="pixelmold-bgcolor"
								id="pixelmold_primary_color"
								type="text"
								name="primary_color"
								value="<?php echo $carousel_options['primary_color'] ?>"
							>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="pixelmold_carousel_secondaryfont">
								<?php echo __( 'Secondary Typography' ); ?>
							</label>
						</th>
						<td>
							<div><div class="pixelmold-typography-container">
								<p class="label-subtitle">
									<?php echo __( 'Font family' ); ?>	
								</p>
								<select
									id="pixelmold_carousel_secondaryfont"
									name="secondary_font"
									class="pixelmold-font-field"
									style="width: 100%; min-width:220px"
								>
									<?php
									$pixelmold_font_value = $carousel_options['secondary_font'][0] . ':' .
										$carousel_options['secondary_font'][1];
									echo '<option value="' . $pixelmold_font_value . '" selected>' .
										$carousel_options['secondary_font'][1] . '</option>';?>
									?>
									<optgroup label="Standard Fonts">
										<?php
										foreach ( $fonts['standard'] as $key => $value ) {
											echo '<option value="' . 's:' . $key . '">' . $key . '</option>';
										} ?>
									</optgroup>
									<?php
									if (
										isset( $fonts['google'] ) &&
										! empty( $fonts['google'] ) &&
										is_array( $fonts['google'] ) &&
										false !== $fonts['google']
									) { ?>
										<optgroup label="Google Fonts">
											<?php
											foreach ( $fonts['google'] as $pixelmold_key => $pixelmold_value ) {
												echo '<option value="' . 'g:' . $pixelmold_key . '">' . $pixelmold_key . '</option>';
											} ?>
										</optgroup>
									<?php
									} ?>
								</select>
							</div>
							<div class="pixelmold-typography-container">
								<p class="label-subtitle">
									<?php echo __( 'Font Weight/Style' ); ?>	
								</p>
								<select
									id="secondary_variant"
									class="pixelmold-variant-field"
									name="secondary_variant" style="width: 100%; min-width:220px"
								>
									<?php
									if ( 'google' === $carousel_options['secondary_font'][0] ) {
										echo '<option value="' . $carousel_options['secondary_font'][2] .
											'" selected>' . $carousel_options['secondary_font'][3] . '</option>';
										
										foreach ( $fonts['google'][ $carousel_options['secondary_font'][1] ]['variants'] as $pixelmold_key => $pixelmold_variant ) {
											echo '<option value="' . $pixelmold_variant['id'] . '">' .
												$pixelmold_variant['text'] . '</option>';
										}
									} else {
										echo '<option value="' . $carousel_options['secondary_font'][2] .
											'" selected>' . $carousel_options['secondary_font'][3] . '</option>';?>
										<option value="400">Normal 400</option>
										<option value="700">Bold 700</option>
										<option value="400i">Normal 400 Italic</option>
										<option value="700i">Bold 700 Italic</option>
									<?php
									} ?>
								</select>
							</div></div>
							<div class="pixelmold-typography-little">
								<p class="label-subtitle">
									<?php echo __( 'Font size' ); ?>	
								</p>
								<input id="pixelmold_typography_size" type="number" min="1" name="secondary_size" value="<?php echo $carousel_options['secondary_size'] ?>" />
							</div>
							<div class="pixelmold-typography-little">
								<p class="label-subtitle">
									<?php echo __( 'Line Height' ); ?>	
								</p>
								<input id="pixelmold_typography_size" type="number" min="1" name="secondary_lineheight" value="<?php echo $carousel_options['secondary_lineheight'] ?>" />
							</div>
							<p class="label-subtitle">
								<?php echo __( 'Font color' ); ?>	
							</p>
							<input class="pixelmold-bgcolor" id="pixelmold_secondary_color" type="text" name="secondary_color"
							value="<?php echo $carousel_options['secondary_color'] ?>">
						</td>
					</tr>
				</tbody></table>
			</div>
			<?php
			// Iterate through each element to generate the admin panel tabs
			for ( $i = 0; $i < (int) $carousel_options['count']; $i++ ) {
				$pixelmold_src = wp_get_attachment_image_src( $pixelmold_elements[ $i ]['attachid'], $size = 'medium' );?>
				<div role="tabpanel" class="tab-pane fade" id="element<?php echo $i + 1; ?>">
				<table class="form-table pixelmold-close-table"><tbody>
					<?php
					// Image
					?>
					<tr>
						<th scope="row">
							<label for="pixelmold_element_image<?php echo $i; ?>"><?php echo __( 'Image' ); ?></label>
						</th>
						<td>
							<input
								id="pixelmold_element_image<?php echo $i; ?>"
								type="hidden"
								name="attachid<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['attachid'] ?>"
							>
							<img
								id="ele_img_<?php echo $i; ?>"
								src="<?php echo $pixelmold_src[0]; ?>"
								style="height:200px; width:auto;display:block;margin-bottom:15px;"
							>
							<input
								type="button"
								value="<?php echo __( 'Change image' ); ?>"
								class="button ele_img_button"
								data-element="<?php echo $i; ?>"
							>
						</td>
					</tr>

					<?php
					// Element title
					?>
					<tr>
						<th scope="row">
							<label for="pixelmold_element_title<?php echo $i; ?>">
								<?php echo __( 'Title/Author text' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_title<?php echo $i; ?>"
								class="pixelmold-large-textbox"
								type="text"
								name="title<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['title'] ?>"
							>
							<p class="description"><?php echo __( 'Optional' ); ?></p>
						</td>
					</tr>

					<?php
					// Description
					?>
					<tr>
						<th scope="row">
							<label for="pixelmold_element_desc<?php echo $i; ?>">
								<?php echo __( 'Description/Quote text' ); ?>
							</label>
						</th>
						<td>
							<textarea
								id="pixelmold_element_desc<?php echo $i; ?>"
								rows="5"
								cols="55"
								name="desc<?php echo $i; ?>"
							><?php
								echo esc_textarea( $pixelmold_elements[ $i ]['desc'] );
							?></textarea>
							<p class="description"><?php echo __( 'Optional' ); ?></p>
						</td>
					</tr>

					<?php
					if (
						7 === $carousel_options['type'] ||
						( 6 === $carousel_options['type'] && 'products_button' === $carousel_options['style'] )
						) {
						$pixelmold_input_state = '';
					} else {
						$pixelmold_input_state = 'disabled_input';
					}

					// Text for the button that takes you to the url
					?>
					<tr class="pixelmold_buttontext <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_linktext<?php echo $i; ?>">
								<?php echo __( 'Button text' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_linktext<?php echo $i; ?>"
								class="pixelmold-large-textbox"
								type="text"
								name="linktext<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['linktext'] ?>"
							>
						</td>
					</tr>

					<?php
					if (
						0 === $carousel_options['type'] ||
						1 === $carousel_options['type'] ||
						2 === $carousel_options['type'] ||
						6 === $carousel_options['type'] ||
						7 === $carousel_options['type']
					) {
						$pixelmold_input_state = '';
					} else {
						$pixelmold_input_state = 'disabled_input';
					}
					// Link URL
					?>
					<tr class="pixelmold_linkurl <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_linkurl<?php echo $i; ?>">
								<?php echo __( 'Full URL' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_linkurl<?php echo $i; ?>"
								class="pixelmold-large-textbox"
								type="text"
								name="linkurl<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['linkurl'] ?>"
							>
							<p class="description"><?php echo __( 'Optional' ); ?></p>
						</td>
					</tr>

					<?php
					if ( 6 === $carousel_options['type'] ) {
						$pixelmold_input_state = '';
					} else {
						$pixelmold_input_state = 'disabled_input';
					}
					// Current Price
					?>
					<tr class="pixelmold_price <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_price<?php echo $i; ?>">
								<?php echo __( 'Current price' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_price<?php echo $i; ?>"
								class="pixelmold-large-textbox"
								type="number"
								min="1"
								name="price<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['price'] ?>"
							>
							<p class="description"><?php echo __( 'Optional.' ); ?></p>
						</td>
					</tr>

					<?php
					// Old Price
					?>
					<tr class="pixelmold_price <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_old_price<?php echo $i; ?>">
								<?php echo __( 'Old price' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_old_price<?php echo $i; ?>"
								class="pixelmold-large-textbox"
								type="number"
								min="1"
								name="old_price<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['old_price'] ?>"
							>
							<p class="description"><?php echo __( 'Optional, to show a product on sale.' ); ?></p>
						</td>
					</tr>

					<?php
					if ( 4 === $carousel_options['type'] ) {
						$pixelmold_input_state = '';
					} else {
						$pixelmold_input_state = 'disabled_input';
					}

					// Facebook
					?>
					<tr class="pixelmold_social_class <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_facebook<?php echo $i; ?>">
								<?php echo __( 'Facebook link' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_facebook<?php echo $i; ?>"
								type="text"
								name="facebook<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['facebook'] ?>"
							>
							<p class="description"><?php echo __( "Without '/' at the start" ); ?></p>
						</td>
					</tr>

					<?php
					// Twitter
					?>
					<tr class="pixelmold_social_class <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_twitter<?php echo $i; ?>">
								<?php echo __( 'Twitter link' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_twitter<?php echo $i; ?>"
								type="text"
								name="twitter<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['twitter'] ?>"
							>
							<p class="description"><?php echo __( "Without '@' at the start" ); ?></p>
						</td>
					</tr>

					<?php
					// Google plus
					?>
					<tr class="pixelmold_social_class <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_googleplus<?php echo $i; ?>">
								<?php echo __( 'Google+ link' ); ?>
							</label>
						</th>
						<td>
							<input
								id="pixelmold_element_googleplus<?php echo $i; ?>"
								type="text"
								name="googleplus<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['googleplus'] ?>"
							>
							<p class="description"><?php echo __( "Without '+' at the start" ); ?></p>
						</td>
					</tr>

					<?php
					// Email
					?>
					<tr class="pixelmold_social_class <?php echo $pixelmold_input_state; ?>">
						<th scope="row">
							<label for="pixelmold_element_email<?php echo $i; ?>"><?php echo __( 'Email link' ); ?></label>
						</th>
						<td>
							<input
								id="pixelmold_element_email<?php echo $i; ?>"
								type="email"
								name="email<?php echo $i; ?>"
								value="<?php echo $pixelmold_elements[ $i ]['email'] ?>"
							>
						</td>
					</tr>
				</tbody></table>
				</div>
			<?php
			} // End for().
?>
		</div> <!-- End tab-content div -->
		<?php
		// Submit
			?>
		<p clas="btnsubmit">
		<?php if ( 'edit_carousel' === $_GET['action'] ) : ?>
			<?php submit_button( 'Save all changes', 'primary', 'btnsubmit' ); ?>
		<?php else : ?>
			<?php submit_button( 'Create Carousel', 'primary', 'btnsubmit' ); ?>
			<p class="description"><?php echo __( 'Save changes to refresh the preview.' ); ?></p>
		<?php endif; ?>
		</p>
		<input type="hidden" value="<?php echo $pixelmold_nonce; ?>" name="pixelmold_nonce" />
	</form>
</div>
