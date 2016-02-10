<?php if ( is_user_logged_in() ) : ?>
	<?php

	global $coursepress;
	$user_id = get_current_user_id();
	$user_id_var = get_query_var( 'userid', '' );


	if('' !== $user_id_var && is_numeric($user_id_var)){
		$user = get_userdata( $user_id_var );
		if(false !== $user) {
			$user_id = $user_id_var;
		}
	}
	$student = new Student( $user_id);
	$avatar_url = get_avatar_url( $id_or_email = $user_id );
	$fname = get_user_meta( $user_id = $user_id, $key = 'first_name', $single = true );
	$lname = get_user_meta( $user_id = $user_id, $key = 'last_name', $single = true );
	$occupation_designer = get_user_meta( $user_id = $user_id, $key = 'occupation_designer', $single = true );
	$occupation_engineer = get_user_meta( $user_id = $user_id, $key = 'occupation_engineer', $single = true );
	$occupation_entrepreneur = get_user_meta( $user_id = $user_id, $key = 'occupation_entrepreneur', $single = true );
	$user_city = get_user_meta($user_id, 'pmpro_bcity', true);
	$user_country = get_user_meta($user_id, 'pmpro_bcountry', true);
	$user_description = get_user_meta( $user_id = $user_id, $key = 'description', $single = true );
?>
	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/page', 'header'); ?>
		<?php get_template_part('templates/content', 'page'); ?>
		<div class="row">
			<div class="col-md-6 col-md-offset-3 text-center">
				<?php if ($avatar_url) {
					echo '<img class="img-circle" src="' . $avatar_url . '" alt="Profile photo of ' . $fname . ' ' . $lname . '">';
				} ?>
				<h1><?php echo $fname . ' ' . $lname; ?></h1>
				<p><?php echo ('1' === $occupation_engineer ? '<a href="' . home_url( 'members/?occupation=engineer', 'relative' ) . '"><span class="label label-success">Engineer</span></a>' : ''); ?><?php echo ('1' === $occupation_designer ? '<a href="' . home_url( 'members/?occupation=designer', 'relative' ) . '"><span class="label label-danger">Designer</span></a>' : ''); ?><?php echo ('1' === $occupation_entrepreneur ? '<a href="' . home_url( 'members/?occupation=entrepreneur', 'relative' ) . '"><span class="label label-primary">Entrepreneur</span></a>' : ''); ?></p>
				<p>Member since <?php echo date('F, Y', strtotime($student->user_registered)); ?> &#8226; Location: <a href="<?php echo home_url( 'members/?country=' . $user_country, 'relative' ); ?> "><?php echo $user_city . ', ' . $user_country; ?></a></p>
				<p><?php echo $user_description; ?></p>
				<?php if($user_id === get_current_user_id()) : ?>
					<a href="<?php echo home_url( 'settings', 'relative' ); ?>">Edit</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile; ?>
<?php else :
	wp_safe_redirect( home_url() );
	endif;
?>