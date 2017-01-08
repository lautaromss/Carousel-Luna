<?php
// Show user input errors if any
if ( ! empty( $GLOBALS['mlabs_carousel_errors'] ) ) {
	$mlabs_error_count = count( $GLOBALS['mlabs_carousel_errors'] );
	for ( $i = 0; $i < $mlabs_error_count; $i++ ) {?>
		<div class="notice notice-error is-dismissible" style="max-width:1185px;"> 
			<p><strong><?php echo $GLOBALS['mlabs_carousel_errors'][ $i ]; ?></strong></p><button type="button" class="notice-dismiss"></button>
		</div>
		<?php
	}
} elseif ( isset( $GLOBALS['mlabs_carousel_errors'] ) ) {
	?>
	<div class="updated notice is-dismissible" style="max-width:1185px;"> 
		<p><strong><?php echo __( 'Settings saved.' ) ?></strong></p><button type="button" class="notice-dismiss"></button>
	</div>
	<?php
}
?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" id="mlabs_ele_tabs" style="border-bottom:none;" role="tablist">
	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
<?php for ( $i = 1; $i <= (int) $carousel_options['count']; $i++ ) {  ?>
	<li role="presentation"><a href="#element<?php echo $i; ?>" aria-controls="mlabs_ele" role="tab" data-toggle="tab">Element <?php echo $i; ?></a><button class="close" type="button" data-identifier="<?php echo $i; ?>" title="Remove this slide">x</button></li>
<?php } ?>
</ul>

<div class="card" style="max-width:1200px; margin-top:0px">
	<form id="mlabs_carousel_form" action="<?php echo $save_link ?>" method="POST">
		<div class="tab-content" id="mlabs_ele_tab_content">
			<!-- Main carousel admin tab -->
			<div role="tabpanel" class="tab-pane fade in active" id="home">
				<?php
				// Hidden ID inputs
				if ( isset( $_GET['post_id'] ) ) { ?>
					<input type="hidden" id="slideridvalue" name="postid" value="<?php echo esc_attr( $_GET['post_id'] ); ?>" />
				<?php
				} else { ?>
					<input type="hidden"  id="slideridvalue" name="postid" value="" />
				<?php
				} ?>
				
				<?php
				// Display Shortcode
				if ( isset( $_GET['post_id'] ) ) { ?>
				<table class="form-table"><tbody>
					<tr>
						<th scope="row">
						Carousel shortcode:
						</th>
						<td>
							<input class="mlabs-carousel-shortcode" type="text" value="[mlabs_carousel id=<?php echo intval( $_GET['post_id'] ); ?>]" disabled />
						</td>
					</tr>
				</tbody></table>
				<?php
				} ?>
				<div style="font-size:14px;text-align: left;line-height: 1.3;font-weight: 600; margin-bottom:10px;"><?php echo __( 'Preview:' ); ?></div>
				<!-- Show the preview -->
				<link rel="stylesheet" href="<?php echo plugins_url( 'css/mlabs-carousel.css', dirname( dirname( __FILE__ ) ) );?>">
				<div id="mlabs_carousel_preview" data-cartype="<?php echo $carousel_options['type']; ?>">
					<?php
					mlabs_show_carousel( $mlabs_elements, $carousel_options, 0 );
					?>
				</div>
				<?php
				// Upload images row
				?>
				<table class="form-table mlabs_close_table"><tbody>
					<tr>
						<th scope="row">
							<label for="gallery-button"><?php echo __( 'Carousel items' ); ?></label>
							<span data-toggle="tooltip" title="Upload only the high-resolution version of the image. If necessary, WordPress will automatically create a lower resolution version to use as the non-zoomed version." class="dashicons dashicons-info mlabs-infobox"></span>
						</th>
						<td>
							<input type="hidden" id="attachids" name="attachids" value="<?php
								if ( ! empty( $mlabs_elements ) ) {
									echo '[';
									$num_items = count( $mlabs_elements );
									$i = 0;
									foreach ( $mlabs_elements as $mlabs_ele ) {
										echo $mlabs_ele['attachid'];
										if ( ++$i !== $num_items ) {
											echo ',';
										}
									}
									echo ']';
								}
								?>" />
							<input type="button" class="button" value="<?php echo __( 'Add images' ); ?>" id="gallery-button">
							<input type="button" class="button-danger" value="<?php echo __( 'Delete ALL items' ); ?>" id="delete-button">
							<p class="description"><?php echo __( 'Each image will be added as an individual item of the carousel.' ); ?></p>
						</td>
					</tr>

					<?php
					//** Display inputs options **//

					// Name of the carousel
					?>

					
					<tr>
						<th scope="row">
							<label for="mlabs_carousel_name"><?php echo __( 'Carousel Name' ); ?></label>
						</th>
						<td>
							<input id="mlabs_carousel_name" type="text" name="title" value="<?php echo $carousel_options['title'] ?>"/>
						</td>
					</tr>

					<script>
					jQuery(document).ready(function(){
					    jQuery('[data-toggle="tooltip"]').tooltip(); 
					});
					</script>

					<?php
					// Carousel Type
					$types = array( 'Images Carousel', 'Slider', 'Flexible Width', 'Testimonials', 'Meet our team', 'Services', 'Product / Content Card' );
					?>
					<tr>
						<th scope="row">
							<label for="mlabs_carousel_type"><?php echo __( 'Carousel Type/Layout' ); ?></label>
							<span data-toggle="tooltip" title="Use Images Carousel only for images with the same size proportions. Use flexible width type for images with different size proportions. Consult documentation for more info." class="dashicons dashicons-info mlabs-infobox"></span>
						</th>
						<td>
							<select id="mlabs_carousel_type" name="type">
								<?php
								$counted_types = count( $types );
								for ( $i = 0; $i < $counted_types; $i++ ) {
									if ( $i === $carousel_options['type'] ) {
										$mlabs_state = 'selected="selected"';
									}?>
									<option <?php echo $mlabs_state; ?> value="<?php echo $i; ?>"><?php echo $types[ $i ]; ?></option>
									<?php
									$mlabs_state = '';
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
							<label for="mlabs_carousel_navs"><?php echo __( 'Navigation arrows' ); ?></label>
						</th>
						<td>
							<label class="switch">
								<input id="mlabs_carousel_navs" type="checkbox" name="navs" <?php
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
						$mlabs_input_state = '';
					} else {
						$mlabs_input_state = 'class="disabled_input"';
					}

					// Dots pagination
					?>
					<tr id="mlabs_dots_pagination" <?php echo $mlabs_input_state; ?>>
						<th scope="row">
							<label for="mlabs_carousel_dots"><?php echo __( 'Dots pagination' ); ?></label>
						</th>
						<td>
							<label class="switch">
								<input type="checkbox" id="mlabs_carousel_dots" name="dots" <?php
								if ( true === $carousel_options['dots'] ) {
									echo 'checked';
								} ?>>
								<div class="slider round"></div>
							</label>
						</td>
					</tr>

					<?php
					if ( 6 !== $carousel_options['type'] ) {
						$mlabs_input_state = '';
					} else {
						$mlabs_input_state = 'class="disabled_input"';
					}

					// Colorpick the background overlay
					?>
					<tr id="mlabs_colorpick" <?php echo $mlabs_input_state; ?>>
						<th scope="row">
							<label for="mlabs_carousel_bgcolor"><?php echo __( 'Overlay Color' ); ?></label>
						</th>
						<td>
							<input class="my-input-class" id="mlabs_carousel_bgcolor" type="text" name="bgcolor"
								value="<?php echo $carousel_options['bgcolor'] ?>">
						</td>
					</tr>

					<script>

					</script>

					<?php
					// Autoplay
					?>
					<tr>
						<th scope="row">
							<label for="mlabs_carousel_autoplay"><?php echo __( 'Autoplay' ); ?></label>
						</th>
						<td>
							<label style="margin-right:20px;" class="switch">
								<input type="checkbox" id="mlabs_carousel_autoplay" name="autoplay" <?php
								if ( true === $carousel_options['autoplay'] ) {
									echo 'checked';
								} ?>>
								<div class="slider round"></div>
							</label>
							<input style="max-width:170px;" id="mlabs_carousel_autoplayms" type="number" min="200"
								name="autoplayms" value="<?php echo $carousel_options['autoplayms']; ?>" />
							<p class="description">
								<?php echo __( 'Time in miliseconds before changing to the next item' ); ?>	
							</p>
						</td>
					</tr>

					<?php
					// Show only the inputs that correspond to the current carousel type.
					if ( 1 === $carousel_options['type'] ) {
						$mlabs_input_state = 'disabled_input';
						$mlabs_input_height = 'disabled_input';
					} elseif ( 2 === $carousel_options['type'] ) {
						$mlabs_input_state = 'disabled_input';
						$mlabs_input_height = '';
					} else {
						$mlabs_input_state = '';
						$mlabs_input_height = 'disabled_input';
					}
					// Items (Desktop)
					?>
					<tr class="mlabs_items_class <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_carousel_items"><?php echo __( 'Items to show' ); ?></label>
							<span data-toggle="tooltip" title="This determines how many items will be shown in the carousel at once. The height of your carousel is directly dependant on this value." class="dashicons dashicons-info mlabs-infobox"></span>
						</th>
						<td>
							<input id="mlabs_carousel_items" type="number" name="items" value="<?php echo $carousel_options['items'] ?>" />
						</td>
					</tr>

					<?php
					// Items (Tablet)
					?>
					<tr class="mlabs_items_class <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_carousel_items_tablet"><?php echo __( 'Items (tablets)' ); ?></label>
							<span
								data-toggle="tooltip" 
								title="This determines how many items will be shown in the carousel at once (on tablets). 
									The height of your carousel is directly dependant on this value." 
								class="dashicons dashicons-info mlabs-infobox">
							</span>
						</th>
						<td>
							<input id="mlabs_carousel_items_tablet" type="number" name="items_tablet"
								value="<?php echo $carousel_options['items_tablet'] ?>" />
						</td>
					</tr>

					<?php
					// Items (Phone)
					?>
					<tr class="mlabs_items_class <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_carousel_items_phone"><?php echo __( 'Items (phone)' ); ?></label>
							<span
								data-toggle="tooltip"
								title="This determines how many items will be shown in the carousel at once (on phones).
								The height of your carousel is directly dependant on this value."
								class="dashicons dashicons-info mlabs-infobox">
							</span>
						</th>
						<td>
							<input id="mlabs_carousel_items_phone" type="number" name="items_phone" value="<?php echo $carousel_options['items_phone'] ?>" />
						</td>
					</tr>

					<?php
					// Height
					?>
					<tr class="mlabs_height_class <?php echo $mlabs_input_height; ?>">
						<th scope="row">
							<label for="mlabs_carousel_height"><?php echo __( 'Height (fixed)' ); ?></label>
						</th>
						<td>
							<input id="mlabs_carousel_height" type="number" min="100" name="height" value="<?php echo $carousel_options['height'] ?>" />
						</td>
					</tr>
				</tbody></table>
			</div>
			<?php
			// Iterate through each element to generate the admin panel tabs
			for ( $i = 0; $i < (int) $carousel_options['count']; $i++ ) {
				$mlabs_src = wp_get_attachment_image_src( $mlabs_elements[ $i ]['attachid'], $size = 'medium' );?>
				<div role="tabpanel" class="tab-pane fade" id="element<?php echo $i + 1; ?>">
				<table class="form-table mlabs_close_table"><tbody>
					<?php
					// Image
					?>
					<tr>
						<th scope="row">
							<label for="mlabs_element_image<?php echo $i; ?>"><?php echo __( 'Image' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_image<?php echo $i; ?>" type="hidden" name="attachid<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['attachid'] ?>" />
							<img id="ele_img_<?php echo $i; ?>" src="<?php echo $mlabs_src[0]; ?>" style="height:200px; width:auto;display:block;margin-bottom:15px;">
							<input type="button" value="<?php echo __( 'Change image' ); ?>" class="button ele_img_button" data-element="<?php echo $i; ?>">
						</td>
					</tr>

					<?php
					// Element title
					?>
					<tr>
						<th scope="row">
							<label for="mlabs_element_title<?php echo $i; ?>"><?php echo __( 'Title text' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_title<?php echo $i; ?>" class="mlabs_large_textbox" type="text" name="title<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['title'] ?>" />
							<p class="description"><?php echo __( 'Optional' ); ?></p>
						</td>
					</tr>

					<?php
					// Description
					?>
					<tr>
						<th scope="row">
							<label for="mlabs_element_desc<?php echo $i; ?>"><?php echo __( 'Description text' ); ?></label>
						</th>
						<td>
							<textarea id="mlabs_element_desc<?php echo $i; ?>" rows="5" cols="55" name="desc<?php echo $i; ?>"><?php echo esc_textarea( $mlabs_elements[ $i ]['desc'] ) ?></textarea>
							<p class="description"><?php echo __( 'Optional' ); ?></p>
						</td>
					</tr>

					<?php
					if ( 6 === $carousel_options['type'] ) {
						$mlabs_input_state = '';
					} else {
						$mlabs_input_state = 'disabled_input';
					}

					// Text for the button that takes you to the url
					?>
					<tr class="mlabs_buttontext <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_element_linktext<?php echo $i; ?>"><?php echo __( 'Button text' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_linktext<?php echo $i; ?>" class="mlabs_large_textbox" type="text" name="linktext<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['linktext'] ?>" />
						</td>
					</tr>

					<?php
					if (
						0 === $carousel_options['type'] ||
						2 === $carousel_options['type'] ||
						6 === $carousel_options['type']
					) {
						$mlabs_input_state = '';
					} else {
						$mlabs_input_state = 'disabled_input';
					}
					// Link URL
					?>
					<tr class="mlabs_linkurl <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_element_linkurl<?php echo $i; ?>"><?php echo __( 'Full URL' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_linkurl<?php echo $i; ?>" class="mlabs_large_textbox" type="text" name="linkurl<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['linkurl'] ?>" />
							<p class="description"><?php echo __( 'Optional' ); ?></p>
						</td>
					</tr>

					<?php
					if ( 4 === $carousel_options['type'] ) {
						$mlabs_input_state = '';
					} else {
						$mlabs_input_state = 'disabled_input';
					}

					// Facebook
					?>
					<tr class="mlabs_social_class <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_element_facebook<?php echo $i; ?>"><?php echo __( 'Facebook link' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_facebook<?php echo $i; ?>" type="text" name="facebook<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['facebook'] ?>" />
							<p class="description"><?php echo __( "Without '/' at the start" ); ?></p>
						</td>
					</tr>

					<?php
					// Twitter
					?>
					<tr class="mlabs_social_class <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_element_twitter<?php echo $i; ?>"><?php echo __( 'Twitter link' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_twitter<?php echo $i; ?>" type="text" name="twitter<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['twitter'] ?>" />
							<p class="description"><?php echo __( "Without '@' at the start" ); ?></p>
						</td>
					</tr>

					<?php
					// Google plus
					?>
					<tr class="mlabs_social_class <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_element_googleplus<?php echo $i; ?>"><?php echo __( 'Google+ link' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_googleplus<?php echo $i; ?>" type="text" name="googleplus<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['googleplus'] ?>" />
							<p class="description"><?php echo __( "Without '+' at the start" ); ?></p>
						</td>
					</tr>

					<?php
					// Email
					?>
					<tr class="mlabs_social_class <?php echo $mlabs_input_state; ?>">
						<th scope="row">
							<label for="mlabs_element_email<?php echo $i; ?>"><?php echo __( 'Email link' ); ?></label>
						</th>
						<td>
							<input id="mlabs_element_email<?php echo $i; ?>" type="email" name="email<?php echo $i; ?>" value="<?php echo $mlabs_elements[ $i ]['email'] ?>" />
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
		<?php if ( ! isset( $_GET['post_id'] ) ) : ?>
			<?php submit_button( 'Create Carousel', 'primary', 'btnsubmit' ); ?>
		<?php else : ?>
			<?php submit_button( 'Save all changes', 'primary', 'btnsubmit' ); ?>
			<p class="description"><?php echo __( 'Save changes to refresh the preview.' ); ?></p>
		<?php endif; ?>
		</p>
		<input type="hidden" value="<?php echo $mlabs_nonce; ?>" name="mlabs_nonce" />
	</form>
</div>
