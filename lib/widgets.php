<?php
class Stats_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'stats_widget', // Base ID
			__( 'Stats Widget', 'da' ), // Name
			array( 'description' => __( 'Academy stats', 'da' ),
				'title' => 'Academy stats',
			) // Args
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
		$query_args = array(
			'post_type'				=> 'course',
			'posts_per_page'		=> -1,
			'post_status'			=> 'publish',
			'fields'				=> 'ids',
		);
		// The Query
		$courseIds = get_posts( $query_args );

		// The Loop
		$totalSeconds = 0;
		$totalMinutes = 0;
		foreach ($courseIds as $courseId) {
			$timeForCourse = (int)strtotime(Course::get_course_time_estimation($courseId));
			$totalMinutes += date("i", $timeForCourse);
		}

		global $wpdb;
		$payed_users = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT	COUNT(user_id)
				FROM	$wpdb->pmpro_memberships_users
				WHERE	status=%s",
				'active'
			)
		);
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		} ?>
		<ul>
			<li><?php echo count($courseIds); ?> Courses</li>
			<li><?php echo $totalMinutes ?> Minutes of video || CHANGE TO HOUR!</li>
			<li>25 Tutorials || STATIC!!</li>
			<li><?php echo $payed_users; ?> Members</li>
		</ul>
		<?php echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'da' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
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
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Stats_Widget

class Credentials_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'credentials_widget', // Base ID
			__( 'Credentials Widget', 'da' ), // Name
			array( 'description' => __( 'Home link and copyright information', 'da' ),
			) // Args
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
		$logo_option = get_theme_mod('da_logo_option', 'title');
		$to_display = '';
		if( 'title' === $logo_option ) :
			$to_display = get_theme_mod('da_logo_title', get_bloginfo( 'name' ));
		else :
			$logo_src = get_theme_mod('da_logo_image', '');
			$to_display = '<img class="img-responsive logo" src="' . $logo_src . '">';
		endif;
		$body = $instance['body'];
		echo $args['before_widget']; ?>
		<a href="<?php echo esc_url(home_url()); ?>"><?php echo $to_display; ?></a>
		<p><?php echo esc_attr($body); ?></p>
		<?php echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$body = ! empty( $instance['body'] ) ? $instance['body'] : __( '&#169; 2013-2016 Despreneur - Magazine for Design Entrepreneurs. All Rights Reserved.', 'da' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'body' ); ?>"><?php _e( 'body:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'body' ); ?>" name="<?php echo $this->get_field_name( 'body' ); ?>" type="text" value="<?php echo esc_attr( $body ); ?>">
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
		$instance['body'] = ( ! empty( $new_instance['body'] ) ) ? strip_tags( $new_instance['body'] ) : '';

		return $instance;
	}

} // class Credentials_Widget

class Front_Top_CTA_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'front_top_cta', // Base ID
			__( 'Front Top CTA', 'da' ), // Name
			array( 'description' => __( 'The top CTA CPTs', 'da' ),
				'title' => 'Top CTA',
			) // Args
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
		$query_args = array(
			'post_type'				=> 'ctatop',
			'posts_per_page'		=> 3,
			'post_status'			=> 'publish',
		);
		$has_hr = $instance['has-hr'];
		// The Query
		$ctas = get_posts( $query_args );
		echo $args['before_widget'];
		echo '<div class="row">';
			foreach ( $ctas as $cta ) : setup_postdata( $cta ); ?>
				<?php $meta = get_post_meta($cta->ID); ?>
				<div class="col-md-4 text-center">
					<?php if(has_post_thumbnail()) : ?>
						<?php echo get_the_post_thumbnail( $post_id = $cta->ID, $size = 'thumbnail', $attr = array() ); ?>
					<?php endif; ?>
					<h3><?php echo get_the_title( $post = $cta->ID ); ?></h3>
					<p><?php the_content(); ?></p>
					<?php if($meta['has_cta'][0]) : ?>
						<a class="btn btn-link" href="<?php echo get_permalink($meta['cta_action'][0]); ?>"><?php echo $meta['cta_title'][0]; ?></a>
					<?php endif; ?>
				</div>
			<?php endforeach; 
		echo '</div>';
		wp_reset_postdata();
		if($has_hr) {
			echo '<hr>';
		}
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) { ?>
		<p><input class="checkbox" type="checkbox" <?php checked( $instance[ 'has-hr' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'has-hr' ); ?>" name="<?php echo $this->get_field_name( 'has-hr' ); ?>" /> 
	        <label for="<?php echo $this->get_field_id( 'has-hr' ); ?>">Show hr element at the bottom of this widget.</label></p>
	<?php }

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
		$instance = $old_instance;
		$instance[ 'has-hr' ] = $new_instance[ 'has-hr' ];
		return $instance;
	}

} // class Front_Top_CTA_Widget

class Signup_CTA_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'signup_cta', // Base ID
			__( 'Signup CTA', 'da' ), // Name
			array( 'description' => __( 'Signup CTA CPT', 'da' ),
				'title' => 'Signup CTA',
			) // Args
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
		$post;
		if(isset($instance['post-id'])) {
			$post = get_post( $post = $instance['post-id'], $output = ARRAY_A, $filter = 'display' );
		} else {
			$posts = wp_get_recent_posts( $args = array('numberposts' => 1, 'post_type' => 'signup_cta' ), $output = ARRAY_A );
			foreach ($posts as $value) {
				$post = $value;
			}
		}
		$has_hr = $instance['has-hr'];
		$big_title = $instance['big-title'];
		// The Query
		echo $args['before_widget'];
		echo '<div class="row text-center">';
			if($big_title) {
				echo '<h2>' . $post['post_title'] . '</h2>';
			} else {
				echo '<h3>' . $post['post_title'] . '</h3>';		
			}
			echo $post['post_content'];
			echo '<a class="btn btn-primary" href="' . get_permalink(get_post_meta( $post_id = $post['ID'], $key = 'cta_action', $single = true )) . '">' . get_post_meta( $post_id = $post['ID'], $key = 'cta_title', $single = true ) . '</a>';
		echo '</div>';
		if($has_hr) {
			echo '<hr>';
		}
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
		$signup_ctas = get_posts( $args = array('post_type' => 'signup_cta', 'posts_per_page' => -1, 'post_status' => 'publish') );
		if(!empty($signup_ctas)) {
			echo '<select class="widefat" id="' . $this->get_field_id( 'post-id' ) . '" name="' . $this->get_field_name( 'post-id' ) . '">';
			foreach ($signup_ctas as $cta) {
				echo '<option value="' . $cta->ID . '">' . $cta->post_title . '</option>';
			}
			echo '</select>';
		}
		?>
		<p><input class="checkbox" type="checkbox" <?php checked( $instance[ 'big-title' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'big-title' ); ?>" name="<?php echo $this->get_field_name( 'big-title' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'big-title' ); ?>">Use big title.</label></p>
		<p><input class="checkbox" type="checkbox" <?php checked( $instance[ 'has-hr' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'has-hr' ); ?>" name="<?php echo $this->get_field_name( 'has-hr' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'has-hr' ); ?>">Show hr element at the bottom of this widget.</label></p>
	<?php }

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
		$instance = $old_instance;
		$instance[ 'has-hr' ] = $new_instance[ 'has-hr' ];
		$instance[ 'big-title' ] = $new_instance[ 'big-title' ];
		$instance[ 'post-id' ] = $new_instance[ 'post-id' ];
		return $instance;
	}

} // class Signup_CTA_Widget

class Display_Latest_Courses_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'courses_widget', // Base ID
			__( 'Courses Display', 'da' ), // Name
			array( 'description' => __( 'Display the most recent courses', 'da' ),
				'title' => 'Courses Display',
			) // Args
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
		$courses = get_posts( $args = array('post_type' => 'course', 'posts_per_page' => 3, 'post_status' => 'publish') );
		$title = $instance['title'];
		if(!empty($courses)) {

			// Closing the opened .container div
			echo '</div>';

			echo $args['before_widget'];
			echo '<div class="full-width-color">';
				echo '<div class="container">';
					echo '<h3>' . $title . '</h3>';
					echo '<div class="row courses">';
						foreach ($courses as $course) {
							$category_display_label = 'primary';
							$category_object = wp_get_post_terms( $course->ID, 'course_category' );
							$category_object = $category_object[0];
							if('design' === $category_object->slug)
								$category_display_label = 'danger';
							echo '<div class="col-md-4">';
								echo '<div class="thumbnail">';
									echo '<a href="' . esc_url(get_permalink()) . '"><img src="' . get_post_meta($course->ID, 'featured_url', true) . '" class="img-responsive"></a>';
									echo '<span class="course-category label label-'. $category_display_label .'">' . $category_object->name . '</span>';
									echo '<a class="title" href="' . esc_url(get_permalink()) . '">' . get_the_title($course->ID) . '</a>';
									echo '<p><i>' . get_the_date($format, $course->ID) . ' / ' . trim(do_shortcode( '[course_time_estimation course_id="' . $course->ID . '"]' ) ) . '</i></p>';
								echo '</div>';
							echo '</div>';
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';	
			echo $args['after_widget'];
			echo '<div class="container">';
		}
		wp_reset_postdata();
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Latest courses', 'da' );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
	<?php }

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
		$instance = $old_instance;
		$instance[ 'title' ] = $new_instance[ 'title' ];
		return $instance;
	}

} // class Display_Latest_Courses_Widget

// register custom widgets
function register_custom_widgets() {
	register_widget( 'Stats_Widget' );
	register_widget( 'Credentials_Widget' );
	register_widget( 'Front_Top_CTA_Widget' );
	register_widget( 'Signup_CTA_Widget' );
	register_widget( 'Display_Latest_Courses_Widget' );
}
add_action( 'widgets_init', 'register_custom_widgets' );
