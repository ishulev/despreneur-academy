<?php if ( is_user_logged_in() ) { ?>
	<?php

	global $coursepress;
	$student         = new Student( get_current_user_id() );
	$student_courses = $student->get_enrolled_courses_ids();
	$args = array(
		'post__in' 	=> $student_courses,
		'post_type' => 'course',
	);
	// The Query
	$completed_count = 0;
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) : ?>
		<h3>Started courses</h3>
		<div class="row">
			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$completed = Student_Completion::is_course_complete( get_current_user_id(), get_the_ID() );
				if($completed) {
					$completed_count ++;
					continue;
				}
				$progress = do_shortcode( '[course_progress course_id="' . get_the_ID() . '"]' );
				$category_display_label = 'primary';
				$category_object = wp_get_post_terms( get_the_ID(), 'course_category' );
				$category_object = $category_object[0];
				if('design' === $category_object->slug)
					$category_display_label = 'danger';
				echo '<div class="col-md-4">';
					echo '<div class="thumbnail">';
						echo '<a href="' . esc_url(get_permalink()) . '"><img src="' . get_post_meta(get_the_ID(), 'featured_url', true) . '" class="img-responsive"></a>';
						echo '<span class="course-category label label-'. $category_display_label .'">' . $category_object->name . '</span>';
						echo '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
						echo '<p>' . trim(do_shortcode( '[course_end label="" label_tag="" course_id="' . get_the_ID() . '"]') ) . ' / ' . trim(do_shortcode( '[course_time_estimation course_id="' . get_the_ID() . '"]' ) ) . '</p>';
						echo '<div class="progress">';
							echo '<div class="progress-bar" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: ' . $progress . '%;">';
								echo $progress . '%';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
			?>
		</div>
		<?php if($completed_count > 0) : ?>
			<h3>Completed courses</h3>
			<div class="row">
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$completed = Student_Completion::is_course_complete( get_current_user_id(), get_the_ID() );
					if(!$completed)
						continue;
					$progress = do_shortcode( '[course_progress course_id="' . get_the_ID() . '"]' );
					$category_display_label = 'primary';
					$category_object = wp_get_post_terms( get_the_ID(), 'course_category' );
					$category_object = $category_object[0];
					if('design' === $category_object->slug)
						$category_display_label = 'danger';
					echo '<div class="col-md-4">';
						echo '<div class="thumbnail">';
							echo '<a href="' . esc_url(get_permalink()) . '"><img src="' . get_post_meta(get_the_ID(), 'featured_url', true) . '" class="img-responsive"></a>';
							echo '<span class="course-category label label-'. $category_display_label .'">' . $category_object->name . '</span>';
							echo '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
							echo '<p>' . trim(do_shortcode( '[course_end label="" label_tag="" course_id="' . get_the_ID() . '"]') ) . ' / ' . trim(do_shortcode( '[course_time_estimation course_id="' . get_the_ID() . '"]' ) ) . '</p>';
						echo '</div>';
					echo '</div>';
				}
				?>
			</div>
		<?php endif; ?>
	<?php endif;
	wp_reset_postdata();
} else {
	//ob_start();
	// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
	wp_redirect( get_option( 'use_custom_login_form', 1 ) ? CoursePress::instance()->get_signup_slug( true ) : wp_login_url() );
	exit;
}