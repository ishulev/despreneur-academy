<div class="container">	
	<?php
		$category_display_label = 'primary';
		$category_object = wp_get_post_terms( get_the_ID(), 'course_category' )[0];
		if('design' === $category_object->slug)
			$category_display_label = 'danger';
		echo do_shortcode( '[course_title class="course-single-title" title_tag="h1"]' );
		echo '<span class="label label-'. $category_display_label .'">' . $category_object->name . '</span>';
		echo do_shortcode( '[course_media course_id="'. get_the_ID() .'"]' );
	?>
	<nav>
		<ul class="pager">
			<li class="previous"><?php previous_post_link('%link', '&#171; Previous lesson'); ?></li>
			<li class="next"><?php next_post_link('%link', 'Next lesson &#187;'); ?></li>
		</ul>
	</nav>
	<div class="row">
		<div class="col-md-8">
			<h2>In this lesson</h2>
			<?php print_r(do_shortcode('[course_description]')); ?>
		</div>
		<div class="col-md-4">
			<h2>Author</h2>
			<?php echo do_shortcode('[course_instructor_avatar]');?>
			<?php $instructor_id = get_post_meta(get_the_ID(), 'instructors', true)[1]; ?>
			<?php echo get_the_author_meta('description', $instructor_id); ?>
		</div>
	</div>
</div>