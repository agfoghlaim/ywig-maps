<?php
function ywig_map_add_custom_metabox(){ 

	add_meta_box(
		'ywig_map_meta',
		'Location Marker',
		'ywig_meta_callback',
		'marker'
	);

}
add_action('add_meta_boxes', 'ywig_map_add_custom_metabox');

function ywig_meta_callback( $post ){
	wp_nonce_field( basename(__FILE__), 'ywig_map_nonce');
	$ywig_stored_meta = get_post_meta( $post->ID );
	echo '<h1>';
	var_dump($ywig_stored_meta);
	echo '</h1>';
	?>

	<div>
	
		<div class="meta-row">
			<div class="meta-th">
				<label for="marker-id" class="ywig-row-title">Marker Id</label>
			</div>
			<div class="meta-td">
				<input type="text" name="marker_id" id="marker-id" value="<?php if( !empty ( $ywig_stored_meta['marker_id'] ) ) echo esc_attr( $ywig_stored_meta['marker_id'][0] ); ?>">
			</div>
		</div>

		<div class="meta-row">
			<div class="meta-th">
				<label for="latitude" class="ywig-row-title">Latitude</label>
			</div>
			<div class="meta-td">
				<input type="text" name="latitude" id="latitude" value="<?php if( !empty ( $ywig_stored_meta['latitude'] ) ) echo esc_attr( $ywig_stored_meta['latitude'][0] ); ?>">
			</div>
		</div>

		<div class="meta-row">
			<div class="meta-th">
				<label for="longitude" class="ywig-row-title">Longitude</label>
			</div>
			<div class="meta-td">
				<input type="text" name="longitude" id="longitude" value="<?php if( !empty ( $ywig_stored_meta['longitude'] ) ) echo esc_attr( $ywig_stored_meta['longitude'][0] ); ?>">
			</div>
		</div>
	</div>

	<div class="meta">
		<div class="meta-th">
			<span>Description</span>
		</div>
	</div>

	<div class="meta-editor">

		<?php 

		$content = get_post_meta( $post->ID, 'description', true );
		$editor = 'description';
		$settings = array(
					'textarea_rows' => 5,
					'media_buttons' => false,
					); 
		wp_editor( $content, $editor, $settings );
		?>
	</div>

	<?php
}

function ywig_meta_save( $post_id ){
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'ywig_map_nonce'] ) && wp_verify_nonce( $_POST[ 'ywig_map_nonce'], basename(__FILE__) ) ) ? 'true' : 'false';

	if( $is_autosave || $is_revision || !$is_valid_nonce ){
		return;
	}

	if( isset( $_POST['marker_id'] ) ){
		update_post_meta( $post_id, 'marker_id', sanitize_text_field( $_POST[ 'marker_id' ]) );
	}

	if( isset( $_POST['latitude'] ) ){
		update_post_meta( $post_id, 'latitude', sanitize_text_field( $_POST[ 'latitude' ]) );
	}

	if( isset( $_POST['longitude'] ) ){
		update_post_meta( $post_id, 'longitude', sanitize_text_field( $_POST[ 'longitude' ]) );
	}

	if( isset( $_POST['description'] ) ){
		update_post_meta( $post_id, 'description', sanitize_text_field( $_POST[ 'description' ]) );
	}


}
add_action('save_post', 'ywig_meta_save');