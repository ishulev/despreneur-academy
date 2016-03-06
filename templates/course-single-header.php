<?php
	$category_display_label = 'primary';
	$category_object = wp_get_post_terms( get_the_ID(), 'course_category' );
	$category_object = $category_object[0]; ?>
<div class="full-width-color">
	<?php get_template_part('templates/header'); ?>
	<div class="container">
		<?php if('design' === $category_object->slug)
				$category_display_label = 'danger';
			echo '<div class="course-single-header">';
				echo do_shortcode( '[course_title class="course-single-title" title_tag="h1"]' );
				echo '<span class="label label-'. $category_display_label .'">' . $category_object->name . '</span>';
			echo '</div>';
			echo do_shortcode( '[course_media course_id="'. get_the_ID() .'" class="course-video-holder"]' );
		?>
		<nav>
			<ul class="pager">
				<li class="previous"><?php previous_post_link('%link', '&#171; Previous lesson'); ?></li>
				<li class="next"><?php next_post_link('%link', 'Next lesson &#187;'); ?></li>
			</ul>
		</nav>
		<div id="course-signup">
			<?php if(is_user_logged_in()) : ?>
				<?php echo do_shortcode( '[course_join_button course_id="'. get_the_ID() .'"]' ); ?>
			<?php else : ?>
				<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/register' ) ); ?>">Signup!</a>
			<?php endif; ?>
		</div>
	</div>
</div>