<?php
//register ywig map post type
function ywig_map_register_post_type(){
	$singular = 'Marker';

	$plural = 'Markers';

	$labels = array(
		'name' => $plural,
		'singular_name' => $singular,
		'add_name' => 'Add New',
		'add_new_item' => 'Add New '.$singular,
		'edit' => 'Edit',
		'edit_item' => 'Edit '.$singular,
		'new_item' => 'New '.$singular,
		'view' => 'View '.$singular,
		'view_item' => 'View '.$singular,
		'search_item' => 'Search '.$plural,
		'parent' => 'Parent '.$singular,
		'not_found' => 'No '.$plural,
		'not_found_in_trash' => 'No '.$plural

		);
	  $args = array(
    'labels'              =>$labels,
    'public'              =>true,
    'publicly_queryable'  =>true, 
    'exclude_from_search' =>false,
    'show_in_nav_menus'   =>true,
    'show_ui'             =>true,
    'show_in_menu'         =>true,
    'show_in_admin_bar'   =>true,
    'menu_icon'           =>'dashicons-wordpress-alt',
    'delete_with_user'    =>false,
    'hierarchical'        =>false,
    'has_archive'         =>true,
    'query_var'           =>true,
    'show_in_rest'       => true,
      'rest_base'          => 'markers-api',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
    'rewrite'             =>array(
      'slug'              =>'markers',
      'with_front'        =>true,
      'pages'             =>true,
      'feeds'             =>false
     ),
    'supports'=>array(
      'title',
      'editor',
      //'author',
      //'custom-fields',
      //'thumbnail'
      )
    );

	register_post_type('Marker', $args);

}
add_action('init', 'ywig_map_register_post_type');