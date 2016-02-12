<?php if ( is_user_logged_in() ) : ?>
	<?php

	$user_id = get_current_user_id();
	$user_id_var = get_query_var( 'userid', '' );

	if('' !== $user_id_var && is_numeric($user_id_var)){
		$user = get_userdata( $user_id_var );
		if(false !== $user) {
			$payment_status = $wpdb->get_var( $wpdb->prepare( 
				"SELECT status 
				FROM $wpdb->pmpro_memberships_users 
				WHERE user_id = %s", 
				$user_id_var
				)
			);
			if('active' == $payment_status) {
				$user_id = $user_id_var;
			}
		}
	}

	$avatar_url = '';
	if ( has_wp_user_avatar($user_id) ) {
		$avatar_url = get_wp_user_avatar_src($user_id, 'thumbnail');
	} else {
		$avatar_url = get_avatar_url( $id_or_email = $user_id );
	}

	$fname = get_user_meta( $user_id = $user_id, $key = 'first_name', $single = true );
	$lname = get_user_meta( $user_id = $user_id, $key = 'last_name', $single = true );
	$occupation_designer = get_user_meta( $user_id = $user_id, $key = 'occupation_designer', $single = true );
	$occupation_engineer = get_user_meta( $user_id = $user_id, $key = 'occupation_engineer', $single = true );
	$occupation_entrepreneur = get_user_meta( $user_id = $user_id, $key = 'occupation_entrepreneur', $single = true );
	$user_city = get_user_meta($user_id, 'pmpro_bcity', true);
	$user_country = get_user_meta($user_id, 'pmpro_bcountry', true);
	$user_description = get_user_meta( $user_id = $user_id, $key = 'description', $single = true );

	$smediaurl_facebook = get_user_meta( $user_id = $user_id, $key = 'smediaurl_facebook', $single = true );
	$smediaurl_twitter = get_user_meta( $user_id = $user_id, $key = 'smediaurl_twitter', $single = true );
	$smediaurl_googleplus = get_user_meta( $user_id = $user_id, $key = 'smediaurl_googleplus', $single = true );

	if((int)$user_id === get_current_user_id()) {
		global $pmpro_pages;
		$payment_status = $wpdb->get_var( $wpdb->prepare( 
			"SELECT status 
			FROM $wpdb->pmpro_memberships_users 
			WHERE user_id = %s", 
			get_current_user_id()
			)
		);
	}
?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="vertical-center">
			<div class="container">
				<?php if((int)$user_id === get_current_user_id() && 'active' !== $payment_status) { ?>
					<div class="alert alert-warning alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<p>Your profile is still not visible to others. Please select a <a href="<?php echo get_page_link( $post = $pmpro_pages['levels'], $leavename, $sample ); ?>">payment plan</a>.</p>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-md-6 col-md-offset-3 text-center">
						<?php if ($avatar_url) {
							echo '<img class="img-circle" src="' . esc_url( $url = $avatar_url, $protocols, $_context ) . '" alt="Profile photo of ' . $fname . ' ' . $lname . '">';
						} ?>
						<h1><?php echo $fname . ' ' . $lname; ?></h1>
						<p><?php echo ('1' === $occupation_engineer ? '<a href="' . home_url( 'members/?occupation=engineer', 'relative' ) . '"><span class="label label-success">Engineer</span></a>' : ''); ?><?php echo ('1' === $occupation_designer ? '<a href="' . home_url( 'members/?occupation=designer', 'relative' ) . '"><span class="label label-danger">Designer</span></a>' : ''); ?><?php echo ('1' === $occupation_entrepreneur ? '<a href="' . home_url( 'members/?occupation=entrepreneur', 'relative' ) . '"><span class="label label-primary">Entrepreneur</span></a>' : ''); ?></p>
						<p>Member since <?php echo date('F, Y', strtotime($student->user_registered)); ?> &#8226; Location: <a href="<?php echo home_url( 'members/?country=' . $user_country, 'relative' ); ?> "><?php echo $user_city . ', ' . $user_country; ?></a></p>
						<p><?php echo $user_description; ?></p>
						<div class="row">
							<?php if('' !== $smediaurl_facebook) : ?><a href="<?php echo $smediaurl_facebook; ?>"><span class="fa fa-facebook"></span></a><?php endif; ?>
							<?php if('' !== $smediaurl_twitter) : ?><a href="<?php echo $smediaurl_twitter; ?>"><span class="fa fa-twitter"></span></a><?php endif; ?>
							<?php if('' !== $smediaurl_googleplus) : ?><a href="<?php echo $smediaurl_googleplus; ?>"><span class="fa fa-google-plus"></span></a><?php endif; ?>
						</div>
						<?php if((int)$user_id === get_current_user_id()) : ?>
							<a href="<?php echo home_url( 'settings', 'relative' ); ?>">Edit</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
</div> <!-- This is ending the full width background div -->
	<?php
		global $coursepress;
		$student = new Student( $user_id);
		$student_courses = $student->get_enrolled_courses_ids();
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
			<div class="container">
				<?php if($started_courses > 0) : ?>
					<h3>Started courses</h3>
					<div class="row">
						<?php
						while ( $the_query->have_posts() ) {
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
				<?php endif; ?>
				<?php if($completed_courses > 0) : ?>
					<h3>Completed courses</h3>
					<div class="row">
						<?php
						while ( $the_query->have_posts() ) {
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
									echo '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
									echo '<p>' . trim(do_shortcode( '[course_end label="" label_tag="" course_id="' . get_the_ID() . '"]') ) . ' / ' . trim(do_shortcode( '[course_time_estimation course_id="' . get_the_ID() . '"]' ) ) . '</p>';
								echo '</div>';
							echo '</div>';
						}
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php wp_reset_postdata();
		endif;
	else :
	wp_safe_redirect( home_url() );
	endif;
?>