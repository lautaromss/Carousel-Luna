<div id="pixelmold-heading_div" class="pixelmold-heading_div">
<h1 id="pixelmold-heading" class="pixelmold-heading">Carousels</h1>
<input id="" class="pixelmold-add-new-button" type="button" value="Add New" onclick="window.location = '<?php echo admin_url( 'admin.php?page=pixelmold_carousels_page&action=new_post' ) ?>'">
</div>
<table border="0" id="pixelmold-carousels-table">
	
	<tr>
		<th>Name</th>
		<th>Amount of elements</th>
		<th>Shortcode</th>
		<th>Created</th>
		<th>Options</th>
	</tr>

	<?php $even_odd = 'even'; ?>
		
	<?php
	// Iterate through all carousels to populate the table with them.
	foreach ( $carousels as $carousel ) {
		$car_data = get_post_meta( $carousel->ID, 'pixelmold_carousel_data', true );
		$carousel_url = admin_url( 'admin.php?page=pixelmold_carousels_page&action=edit_carousel&post_id=' . $carousel->ID );
		?>
		<tr class='<?php echo $even_odd?>'>
			
			<td>
				<a class="carouseltitle_link" href="<?php echo $carousel_url ?>">
					<strong><?php echo get_the_title( $carousel->ID ) ?></strong>
				</a>
			</td>
			
			<td class="pixelmold_amount_width"><?php echo $car_data['count']; ?></td>
			<td id="pixelmoldshortcodecopy">[pixelmoldcarousel id=<?php echo $carousel->ID ?>]</td>
			<td><?php echo get_the_date( 'l, F j, Y', $carousel->ID ); ?></td>
			<td class="pixelmold_options_width">
			<a href="<?php echo $carousel_url ?>">
				<span class="dashicons dashicons-edit"></span>
				Edit
			</a>
			<a href="#" class="pixelmold_delete_car" data-id="<?php echo intval( $carousel->ID ) ?>">
				<span class="dashicons dashicons-trash"></span>
				Delete
			</a>
			
			</td>
		</tr>

		<?php
		$even_odd = 'even' === $even_odd ? 'odd' : 'even';
	} // End foreach().?>

	<?php if ( ! $carousels ) : ?>

		<div style="text-align: center">
			<h3>
				No carousels. Click the + <a href="<?php echo admin_url( 'admin.php?page=pixelmold_carousels_page&action=new_post' ) ?>">Add New</a> to create your first carousel.
			</h3>
		</div>

	<?php endif; ?>

</table>

<script>
jQuery('.pixelmold_delete_car').click(function() {

		id = jQuery(this).data('id');

		url = '<?php echo admin_url() ?>' + 'admin.php?page=pixelmold_carousels_page&action=delete_carousel&pixelmold_nonce_del=<?php echo $carousel_del_nonce;?>&post_id=' + id;

		if ( confirm( 'Are you sure you want to permanently remove this carousel?' ) ) {
			window.location = url;
		}
	});
</script>
