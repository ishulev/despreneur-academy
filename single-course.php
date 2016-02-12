<?php
	$category_display_label = 'primary';
	$category_object = wp_get_post_terms( get_the_ID(), 'course_category' );
	$category_object = $category_object[0];
	$instructor_id = get_post_meta(get_the_ID(), 'instructors', true);
	$instructor_id = $instructor_id[1];
	if('design' === $category_object->slug)
		$category_display_label = 'danger';
	echo do_shortcode( '[course_title class="course-single-title" title_tag="h1"]' );
	echo '<span class="label label-'. $category_display_label .'">' . $category_object->name . '</span>';
	echo do_shortcode( '[course_media course_id="'. get_the_ID() .'" class="course-video-holder"]' );

	$avatar_url = '';
	if ( has_wp_user_avatar($instructor_id) ) {
		$avatar_url = get_wp_user_avatar_src($instructor_id, 'thumbnail');
	} else {
		$avatar_url = get_avatar_url( $id_or_email = $instructor_id );
	}
?>
<nav>
	<ul class="pager">
		<li class="previous"><?php previous_post_link('%link', '&#171; Previous lesson'); ?></li>
		<li class="next"><?php next_post_link('%link', 'Next lesson &#187;'); ?></li>
	</ul>
</nav>

<?php
	echo do_shortcode( '[course_join_button course_id="'. get_the_ID() .'"]' );
?>
<div class="row">
	<div class="col-md-8">
		<h2>In this lesson</h2>
		<?php print_r(do_shortcode('[course_description]')); ?>
		<?php echo do_shortcode( '[course_structure course_id="'. get_the_ID() .'"]' ); ?>
	</div>
	<div class="col-md-4">
		<h2>Author</h2>
		<img src="<?php echo $avatar_url;?>" class="img-circle img-responsive" alt="Instructor photo">
		<h3><?php echo get_the_author_meta('display_name', $instructor_id); ?></h3>
		<div class="row"><p class="col-xs-12"><?php echo get_the_author_meta('description', $instructor_id); ?></p></div>
		<hr>
		<h2>Course details</h2>
		<?php echo do_shortcode('[course_start label="Date" label_tag="span" label_delimeter=":"]');?>
		<?php echo do_shortcode('[course_time_estimation]'); ?>
		<p>Category: <?php echo $category_object->name; ?></p>
	</div>
</div>