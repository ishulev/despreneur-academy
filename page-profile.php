<?php if ( is_user_logged_in() ) : ?>
	<?php
		$student_courses = $student->get_enrolled_courses_ids();
		if( !empty($student_courses)) :
			$args = array(
				'post__in' 	=> $student_courses,
				'post_type' => 'course',
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) :
				$completed_courses = 0;
				$started_courses = 0;
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$progress = do_shortcode( '[course_progress course_id="' . get_the_ID() . '"]' );
					$completed = Student_Completion::is_course_complete( $user_id, get_the_ID() );
					if($completed || '100' == $progress) {
						$completed_courses ++;
					} else {
						$started_courses ++;
					}
				} ?>
				<?php if($started_courses > 0) : ?>
					<?php $count = 0; ?>
					<h4>Started courses</h4>
					<div class="row courses">
						<?php
						while ( $the_query->have_posts() ) {
							if($count !== 0 && $count % 3 == 0)
								echo '</div><div class="row courses">';
							$the_query->the_post();
							$completed = Student_Completion::is_course_complete( $user_id, get_the_ID() );
							$progress = do_shortcode( '[course_progress course_id="' . get_the_ID() . '"]' );
							if($completed || '100' == $progress) {
								continue;
							}
							$category_display_label = 'primary';
							$category_object = wp_get_post_terms( get_the_ID(), 'course_category' );
							$category_object = $category_object[0];
							if('design' === $category_object->slug)
								$category_display_label = 'danger';
							echo '<div class="col-md-4">';
								echo '<div class="thumbnail">';
									echo '<a href="' . esc_url(get_permalink()) . '"><img src="' . get_post_meta(get_the_ID(), 'featured_url', true) . '" class="img-responsive"></a>';
									echo '<span class="course-category label label-'. $category_display_label .'">' . $category_object->name . '</span>';
									echo '<a class="title" href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
									echo '<p><i>' . get_the_date() . ' / ' . trim(do_shortcode( '[course_time_estimation course_id="' . get_the_ID() . '"]' ) ) . '</i></p>';
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
				<?php endif; ?>
				<?php if($completed_courses > 0) : ?>
					<?php $count = 0; ?>
					<h4>Completed courses</h4>
					<div class="row courses">
						<?php
						while ( $the_query->have_posts() ) {
							if($count !== 0 && $count % 3 == 0)
								echo '</div><div class="row courses">';
							$the_query->the_post();
							$completed = Student_Completion::is_course_complete( $user_id, get_the_ID() );
							$progress = do_shortcode( '[course_progress course_id="' . get_the_ID() . '"]' );
							if(!$completed && '100' !== $progress)
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
									echo '<a class="title" href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
									echo '<p><i>' . get_the_date() . ' / ' . trim(do_shortcode( '[course_time_estimation course_id="' . get_the_ID() . '"]' ) ) . '</i></p>';
								echo '</div>';
							echo '</div>';
						}
						?>
					</div>
				<?php endif; ?>
				<?php wp_reset_postdata();
			endif;
		endif;
	else :
	wp_safe_redirect( home_url() );
	endif;
?>