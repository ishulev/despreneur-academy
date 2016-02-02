<?php
	$category_display_label = 'primary';
	$category_object = wp_get_post_terms( get_the_ID(), 'course_category' )[0];
	$instructor_id = get_post_meta(get_the_ID(), 'instructors', true)[1];
	if('design' === $category_object->slug)
		$category_display_label = 'danger';
	echo do_shortcode( '[course_title class="course-single-title" title_tag="h1"]' );
	echo '<span class="label label-'. $category_display_label .'">' . $category_object->name . '</span>';
	echo do_shortcode( '[course_media course_id="'. get_the_ID() .'" class="course-video-holder"]' );
?>
<nav>
	<ul class="pager">
		<li class="previous"><?php previous_post_link('%link', '&#171; Previous lesson'); ?></li>
		<li class="next"><?php next_post_link('%link', 'Next lesson &#187;'); ?></li>
	</ul>
</nav>

<?php
	if(is_user_logged_in())
		echo do_shortcode( '[course_join_button course_id="'. get_the_ID() .'"]' );
	else
		echo '<a href="'.get_home_url( $blog_id = null, $path = 'membership-account' ).'">Sign up!</a>';
?>
<div class="row">
	<div class="col-md-8">
		<h2>In this lesson</h2>
		<?php print_r(do_shortcode('[course_description]')); ?>
		<?php echo do_shortcode( '[course_structure course_id="'. get_the_ID() .'"]' ); ?>
	</div>
	<div class="col-md-4">
		<h2>Author</h2>
		<?php echo do_shortcode('[course_instructor_avatar]');?>
		<h3><?php echo get_the_author_meta('display_name', $instructor_id); ?></h3>
		<div class="row"><p class="col-xs-12"><?php echo get_the_author_meta('description', $instructor_id); ?></p></div>
		<hr>
		<h2>Course details</h2>
		<?php echo do_shortcode('[course_start label="Date" label_tag="span" label_delimeter=":"]');?>
		<?php echo do_shortcode('[course_time_estimation]'); ?>
		<p>Category: <?php echo $category_object->name; ?></p>
	</div>
</div>