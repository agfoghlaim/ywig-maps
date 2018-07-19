<?php

	function ywig_maps_add_scripts(){

		//css
		//wp_enqueue_style('ywig-maps-style', plugins_url(). '/ywig-maps/css/style.css');

		//js
		//wp_enqueue_script('ywig-maps-js', plugins_url(). '/ywig-maps/js/main.js');

		// //load google maps api example
		// wp_register_script('google', 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js');
		// wp_enqueue_script('google');


	}
	//add_action('wp_enqueue_scripts', 'ywig_maps_add_scripts');


	//load admin css and js on edit post page only
	function ywig_admin_enqueue_scripts(){
		global $pagenow, $typenow;
		
		if( ($pagenow == 'post.php' || $pagenow == 'post-new.php' ) && $typenow == 'marker' ){

			wp_enqueue_style( 'ywig_admin_css', plugins_url( '../css/admin.css', __FILE__) );
			wp_enqueue_script('ywig_admin_js', plugins_url( '../js/admin.js', __FILE__), array( 'jquery'), '1.0.0', true );

		}

		if( $pagenow ==='post.php' ){
			wp_enqueue_script( 'mainjs' , plugins_url('../js/main.js', __FILE__ ), array('jquery'), '1.0.0', true );
			
			//param1: $handle the file to which we want to make localized scripts available, match 'mainjs' above
			//param2: $object_name, a custom variable
			//param3: array of variables to include in $object_name
			//$ywig_stored_meta = get_post_meta( $post->ID );

		
			wp_localize_script( 'mainjs', 'WP_MARKER_DETS', 
				array(
					'security' => wp_create_nonce( 'wp_marker_nonce' ),
					'success' => 'Ajax says ok',
					'failure' => 'Ajax not happy',
					//'test' => $new_arr

				) 
			);
		}

	}
	add_action( 'admin_enqueue_scripts', 'ywig_admin_enqueue_scripts' );


	/*

	Front end

	*/

	function ywig_enqueue_map_scripts(  ) {
		
		if( is_page( 'blog' ) != true ) return;

		//main frontend js file
	    wp_enqueue_script( 'ajax-script',
	        plugins_url( '../js/myjquery.js', __FILE__ ),
	        array( 'jquery' )
	    );

	   //load google maps api example for cluster
		wp_register_script('google', 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js');
		wp_enqueue_script('google');

	    //enqueue google maps script
	    wp_enqueue_script('google-maps',
	    'https://maps.googleapis.com/maps/api/js?key=AIzaSyB7mIJUr340av2q7O3HXe7E75ttFrbCFdQ&libraries=places',
	    '');

	    //frontend  map css
		wp_enqueue_style('ywig-map-style', plugins_url(). '/ywig-maps/css/map.css');

	    $map_nonce = wp_create_nonce( 'map_example' );

	    //localise map js
	    wp_localize_script( 'ajax-script', 'my_ajax_obj', array(
	       'ajax_url' => admin_url( 'admin-ajax.php' ),
	       'nonce'    => $map_nonce,
	    ) );
	}
	add_action( 'wp_enqueue_scripts', 'ywig_enqueue_map_scripts' );

	function handle_ajax_send_markers(){
		check_ajax_referer( 'map_example' );
		//query for markers here
		//wp_send_json_success('hiya');
		$args = array(
		'post_type' => 'marker',
		'order_by' => 'menu_order',
		'order' => 'ASC',
		'no_found_rows' => true,
		'update_post_term_cache' => false,
		'post_status' => 'publish',
		'post_per_post' => 50
	);

		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			$arr = array();
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				/*
					$lng = get_post_meta( get_the_ID(), 'longitude', true );
		         	$lat = get_post_meta( get_the_ID(), 'latitude', true );
		         	$desc = get_post_meta( get_the_ID(), 'description', true );
		         	$marker = get_post_meta( get_the_ID(), 'marker_id', true );
		         	$resp[] = array(
		         		'lng' => $lng,
		         		'lat' => $lat, 
		         		'desc' => $desc,
		         		'marker' => $marker
		         	);
		   		*/


		        	/*
		        	refactor for Advanced Custom Fields - acf_marker, marker_text, marker_title
		        	*/
		        	$location = get_field('acf_marker');
		        	$desc = get_field('marker_text');
		        	//$marker = get_post_meta( get_the_ID(), 'marker_id', true );
		        	$marker = get_the_title();
		        	$resp[] = array(
		        		'lng' => $location['lng'],
		        		'lat' => $location['lat'], 
		        		'address' => $location['address'],
		        		'desc' => $desc,
		        		'marker' => $marker
		        	);
		        	
			}
			wp_send_json_success( $resp );
			
			/* Restore original Post Data */
			wp_reset_postdata();
		} else {
			wp_send_json_error( 'no markers found' );
			// no posts found
		}
	}
	add_action( 'wp_ajax_nopriv_map_action', 'handle_ajax_send_markers' );
	add_action( 'wp_ajax_map_action', 'handle_ajax_send_markers' );


	//API Key for ACF
	function my_acf_google_map_api( $api ){
		$api['key'] = 'AIzaSyD8pEH8w5TPoAqLSHBphIKy46RQTt-rd7s';
		//$api['key'] = 'AIzaSyB7mIJUr340av2q7O3HXe7E75ttFrbCFdQ';
		
		return $api;
		
	}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');