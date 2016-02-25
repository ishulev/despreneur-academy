<?php
	$user_id = get_current_user_id();
	$user_id_var = get_query_var( 'userid', '' );

	if('' !== $user_id_var && is_numeric($user_id_var)){
		$test_user = get_userdata( $user_id_var );
		if(false !== $test_user) {
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

	global $coursepress;
	$student = new Student( (int)$user_id);

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
		<?php if((int)$user_id === get_current_user_id() && 'active' !== $payment_status) { ?>
			<div class="container">
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<p>Your profile is still not visible to others. Please select a <a href="<?php echo get_page_link( $post = $pmpro_pages['levels'], $leavename, $sample ); ?>">payment plan</a>.</p>
				</div>
			</div>
		<?php } ?>
		<div class="vertical-center">
			<div class="container">
				<div class="row profile-view">
					<div class="col-md-6 col-md-offset-3 text-center">
						<?php if( !is_page( 'settings' ) && (int)$user_id === get_current_user_id()) : ?>
							<p class="edit-button"><a href="<?php echo home_url( 'settings', 'relative' ); ?>">Edit profile</a></p>
						<?php endif; ?>
						<?php echo get_avatar( $id_or_email = $user_id, $size, $default, $alt, $args = array( 'class' => 'img-circle' )); ?>
						<h1><?php echo $fname . ' ' . $lname; ?></h1>
						<div class="row"><?php echo ('1' === $occupation_engineer ? '<a href="' . home_url( 'members/?occupation=engineer', 'relative' ) . '"><span class="label label-success">Engineer</span></a>' : ''); ?><?php echo ('1' === $occupation_designer ? '<a href="' . home_url( 'members/?occupation=designer', 'relative' ) . '"><span class="label label-danger">Designer</span></a>' : ''); ?><?php echo ('1' === $occupation_entrepreneur ? '<a href="' . home_url( 'members/?occupation=entrepreneur', 'relative' ) . '"><span class="label label-primary">Entrepreneur</span></a>' : ''); ?></div>
						<p class="text">Member since <?php echo date('F, Y', strtotime($student->user_registered)); ?> <?php if( '' !== $user_city && '' !== $user_country) : ?>&#8226; Location: <a href="<?php echo home_url( 'members/?country=' . $user_country, 'relative' ); ?> "><?php echo $user_city . ', ' . $user_country; ?></a><?php endif; ?></p>
						<p class="text description"><?php echo $user_description; ?></p>
						<div class="row social">
							<?php if('' !== $smediaurl_facebook) : ?><a href="<?php echo $smediaurl_facebook; ?>"><span class="fa fa-facebook"></span></a><?php endif; ?>
							<?php if('' !== $smediaurl_twitter) : ?><a href="<?php echo $smediaurl_twitter; ?>"><span class="fa fa-twitter"></span></a><?php endif; ?>
							<?php if('' !== $smediaurl_googleplus) : ?><a href="<?php echo $smediaurl_googleplus; ?>"><span class="fa fa-google-plus"></span></a><?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
</div> <!-- This is ending the full width background div -->