<?php


function wp_google_scripts() {
	$API_KEY = "AIzaSyB7mIJUr340av2q7O3HXe7E75ttFrbCFdQ";
	wp_enqueue_script( 'google-maps-native', "http://maps.googleapis.com/maps/api/js?key=".$API_KEY);
}
add_action( 'admin_enqueue_scripts', 'wp_google_scripts' );
// 2. Create Metabox
function add_embed_gmaps_meta_box() {
    add_meta_box(
        'gmaps_embed_meta_box', // $id
        'Post Embed Google Maps', // $title
        'show_embed_gmaps_meta_box', // $callback
        'post', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_embed_gmaps_meta_box');
// 3. Show Metabox Contents
function show_embed_gmaps_meta_box() {
    global $post;  
	$lat = get_post_meta($post->ID, 'lat', true);  
	$lng = get_post_meta($post->ID, 'lng', true); 
	$nonce = wp_create_nonce(basename(__FILE__));
?>
<div class="maparea" id="map-canvas"></div>
<input type="hidden" name="glat" id="latitude" value="<?php echo $lat; ?>">
<input type="hidden" name="glng" id="longitude" value="<?php echo $lng; ?>">
<input type="hidden" name="custom_meta_box_nonce" value="<?php echo $nonce; ?>">  
<?php
}
// 4. Add Javascript Logic + custom style
add_action('admin_print_styles-post.php', 'custom_js_css');
add_action('admin_print_styles-post-new.php', 'custom_js_css');
function custom_js_css() {
	global $post;
    wp_enqueue_style('gmaps-meta-box', plugins_url(). '/ywig-maps/css/admin-map.css');
    wp_enqueue_script('gmaps-meta-box', plugins_url(). '/ywig-maps/js/admin-map.js');
    $helper = array(
    	'lat' => get_post_meta($post->ID,'lat',true),
    	'lng' => get_post_meta($post->ID,'lng',true)
    );
    wp_localize_script('gmaps-meta-box','helper',$helper);
}
// 5. Save Metaboxes.
function save_embed_gmap($post_id) {   
    // verify nonce
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
        
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
        
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }  
    
    $oldlat = get_post_meta($post_id, "lat", true);
    
    $newlat = $_POST["glat"]; 
    if ($newlat != $oldlat) {
        update_post_meta($post_id, "lat", $newlat);
    } 
    $oldlng = get_post_meta($post_id, "lng", true);
    
    $newlng = $_POST["glng"]; 
    if ($newlng != $oldlng) {
        update_post_meta($post_id, "lng", $newlng);
    } 
}
add_action('save_post', 'save_embed_gmap');