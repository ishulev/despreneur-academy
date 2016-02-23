<?php
	$args = array(
		'post_type'				=> 'course',
		'posts_per_page'		=> -1,
		'post_status'			=> 'publish',
		'fields'				=> 'ids',
	);
	// The Query
	$courseIds = get_posts( $args );

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
?>
<footer>
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar('sidebar-footer'); ?>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a>
				<p>&#169; 2013-2016 Despreneur - Magazine for Design Entrepreneurs. All Rights Reserved.</p>
			</div>
			<div class="col-md-2">
				<h6>Academy stats</h6>
				<ul>
					<li><?php echo count($courseIds); ?> Courses</li>
					<li><?php echo $totalMinutes ?> Minutes of video || CHANGE TO HOUR!</li>
					<li>25 Tutorials || STATIC!!</li>
					<li><?php echo $payed_users; ?> Members</li>
				</ul>
			</div>
			<div class="col-md-2">
				<h6>Courses</h6>
				<ul>
					
				</ul>
			</div>
			<div class="col-md-2">
				<h6>Academy stats</h6>
				<ul>
					<li>25 Tutorials</li>
					<li>25 Tutorials</li>
					<li>25 Tutorials</li>
					<li>25 Tutorials</li>
				</ul>
			</div>
			<div class="col-md-2">
				<h6>Academy stats</h6>
				<ul>
					<li>25 Tutorials</li>
					<li>25 Tutorials</li>
					<li>25 Tutorials</li>
					<li>25 Tutorials</li>
				</ul>
			</div>
		</div>
	</div>
</footer>
