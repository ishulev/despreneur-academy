<?php 
	$instructor_id = get_post_meta(get_the_ID(), 'instructors', true);
	$instructor_id = $instructor_id[1];
	$category_object = wp_get_post_terms( get_the_ID(), 'course_category' );
	$category_object = $category_object[0];
?>
<div class="row">
	<div class="col-md-8">
		<h3>In this lesson</h3>
		<?php echo do_shortcode('[course_description]'); ?>
		<?php echo do_shortcode( '[course_structure course_id="'. get_the_ID() .'"]' ); ?>
	</div>
	<div class="col-md-4 added-margin">
		<h3>Author</h3>
		<div class="row author-visuals">
			<div class="col-md-4">
				<?php echo get_avatar( $id_or_email = $instructor_id, $size, $default, $alt, $args = array( 'class' => 'img-circle' )); ?>
			</div>
			<div class="col-md-8">
				<h4 class="admin-name"><?php echo get_the_author_meta('display_name', $instructor_id); ?></h4>
			</div>
		</div>
		<p class="serif"><?php echo get_the_author_meta('description', $instructor_id); ?></p>
		<hr>
		<h3>Course details</h3>
		<div class="course-details"><strong>Date: </strong><?php echo do_shortcode('[course_start label="" label_tag="span" label_delimeter=""]');?></div>
		<div class="course-details"><strong>Category: </strong><em><?php echo $category_object->name; ?></em></div>
	</div>
</div>