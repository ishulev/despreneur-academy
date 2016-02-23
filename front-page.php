	<div class="vertical-center">
		<section class="text-center container">
			<h1>Become a design entrepreneur. Build a business & meet amazing people.</h1>
			<h2>Learn game-changing skills, build location independent business and meet amazing designers and entrepreneurs.</h2>
			<a href="<?php echo esc_url( home_url( '/membership-account' ) ); ?>" class="btn btn-primary">Start learning</a>
		</section>
	</div>
</div> <!-- This is ending the full width background div -->
<section class="container text-center">
	<div class="row">
		<div class="col-md-4">
			<span class="fa fa-globe"></span>
			<h3>Invest in yourself</h3>
			<p>Lorem ipsum</p>
			<a href="#">Some link</a>
		</div>
		<div class="col-md-4">
			<span class="fa fa-briefcase"></span>
			<h3>Invest in yourself</h3>
			<p>Lorem ipsum</p>
			<a href="#">Some link</a>
		</div>
		<div class="col-md-4">
			<h3>Invest in yourself</h3>
			<p>Lorem ipsum</p>
			<a href="#">Some link</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<h3>Simple pricing</h3>
		<p>Despreneur academy membership is only $19</p>
		<p><i>One month trial is just $1</i></p>
		<a href="<?php echo esc_url( home_url( '/membership-account' ) ); ?>" class="btn btn-primary">Sign up now</a>
	</div>
</section>

<?php $args = array(
	'post_type'				=> 'course',
	'posts_per_page'		=> 3,
	'post_status'			=> 'publish',
	'orderby'				=> 'date',
	'order'					=> 'DESC',
	);
	// The Query
$the_query = new WP_Query( $args );

	// The Loop
if ( $the_query->have_posts() ) : ?>
<section class="full-width-color">
	<div class="container">
		<h3>Latest courses</h3>
		<div class="row courses">
			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
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
	</div>
</section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
<section class="container text-center">
	<h3>What people are saying</h3>
	<div class="row">
		<div class="col-md-4 h-card vcard">
			<img src="https://media.licdn.com/mpr/mpr/shrink_100_100/p/3/005/06c/210/2b30920.jpg" alt="Test image" class="u-photo photo img img-circle">
			<h4 class="p-name fn">Ivan Shulev</h4>
			<a class="u-uid url uid" href="twitter.com/user1">User1</a>
			<p><i>Loved every video course I've taken. Very easy to follow.</i></p>
		</div>
		<div class="col-md-4 h-card vcard">
			<img src="https://media.licdn.com/mpr/mpr/shrink_100_100/p/3/005/06c/210/2b30920.jpg" alt="Test image" class="u-photo photo img img-circle">
			<h4 class="p-name fn">Ivan Shulev</h4>
			<a class="u-uid url uid" href="twitter.com/user1">User1</a>
			<p><i>Loved every video course I've taken. Very easy to follow.</i></p>
		</div>
		<div class="col-md-4 h-card vcard">
			<img src="https://media.licdn.com/mpr/mpr/shrink_100_100/p/3/005/06c/210/2b30920.jpg" alt="Test image" class="u-photo photo img img-circle">
			<h4 class="p-name fn">Ivan Shulev</h4>
			<a class="u-uid url uid" href="twitter.com/user1">User1</a>
			<p><i>Loved every video course I've taken. Very easy to follow.</i></p>
		</div>
	</div>
	<a href="#">Read more success stories</a>
	<div class="row">
		<h3>Exclusive partner deals to kickstart your business</h3>
		<img src="<?php echo get_template_directory_uri();?>/dist/images/partners-front_page.png" alt="">
	</div>
	<hr>
	<div class="row">
		<h3>Start learning today</h3>
		<p><i>Join an amazing community and start learning life-changing skills</i></p>
		<a href="<?php echo esc_url( home_url( '/membership-account' ) ); ?>" class="btn btn-primary">Sign up now</a>
	</div>
</section>