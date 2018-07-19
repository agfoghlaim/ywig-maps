<?php 
/**
 * Adds Ywig_Map_Widget widget.
 */
class Ywig_Map_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'ywigmap_widget', // Base ID
			esc_html__( 'Ywig Maps', 'ywig_map_domain' ), // Name
			array( 'description' => esc_html__( 'Widget to display Youth Services Map', 'ywig_map_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		//output
		//echo esc_html__( 'Hello, World!', 'ywig_map_domain' );
		//echo 'Hiya ywig map widget';
		
		include( plugin_dir_path( __FILE__ ) . 'map/map.php' );

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Ywig Map', 'ywig_map_domain' );
		$longitude = ! empty( $instance['longitude'] ) ? $instance['longitude'] : esc_html__( 'default for longitude', 'ywig_map_domain' );
		$latitude = ! empty( $instance['latitude'] ) ? $instance['latitude'] : esc_html__( 'default for latitude', 'ywig_map_domain' );
		?>
		
		<!-- for title-->		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'ywig_map_domain' ); ?>
			</label> 
			<input 
				class="widefat" 
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<!-- for longitude-->	
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>">
				<?php esc_attr_e( 'Longitude:', 'ywig_map_domain' ); ?>
			</label> 
			<input 
				class="widefat" 
				id="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'longitude' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $longitude ); ?>">
		</p>

		<!-- for latitude-->	
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>">
				<?php esc_attr_e( 'Latitude:', 'ywig_map_domain' ); ?>
			</label> 
			<input 
				class="widefat" 
				id="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'latitude' ) ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $latitude ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['longitude'] = ( ! empty( $new_instance['longitude'] ) ) ? sanitize_text_field( $new_instance['longitude'] ) : '';
		$instance['latitude'] = ( ! empty( $new_instance['latitude'] ) ) ? sanitize_text_field( $new_instance['latitude'] ) : '';
		return $instance;
	}

} // class Foo_Widget