<?php
	if(isset($_POST['registration-submit'])) {
		$missing_fields = false;
		$password_missmatch = false;
		$reserved_email = false;
		$error = false;
		if(! isset($_POST['email']) || ! isset($_POST['password']) || ! isset($_POST['password-repeat']))  {
			$missing_fields = true;
		} else if ( isset( $_POST['register_wpnonce'] ) && wp_verify_nonce( $_POST['register_wpnonce'], 'register_nonce' )) {
			if($_POST['password'] !== $_POST['password-repeat']) {
				$password_missmatch = true;
			}
			if(false !== username_exists( $_POST['email'] ) || false !== email_exists( $_POST['email'])) {
				$reserved_email = true;
			} else if(!$password_missmatch) {
				$userdata = array(
					'user_login'	=>	$_POST['email'],
					'user_pass'		=>	$_POST['password'],
					'user_email'	=>	$_POST['email'],
					'user_role'
				);
				$new_user_id = wp_insert_user( $userdata );
				if ( ! is_wp_error( $new_user_id )) {
					$user = get_user_by( 'id', $new_user_id ); 
					if( $user ) {
						update_user_meta( $user_id = $new_user_id, $meta_key = 'role', $meta_value = 'student', $prev_value );
						wp_set_current_user( $new_user_id, $user->user_login );
						wp_set_auth_cookie( $new_user_id );
						do_action( 'wp_login', $user->user_login );
						wp_safe_redirect( home_url( '/' ) );
						exit;
					}
				}
				else {
					$error = $new_user->get_error_message();
				}
			}
		}
	}
?>
<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('templates/page', 'header'); ?>
	<?php get_template_part('templates/content', 'page'); ?>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<?php if($missing_fields) : ?>
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<p>Please fill in all the fields!</p>
				</div>
			<?php endif; ?>
			<?php if($error) : ?>
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<p>The following error occured:</p>
					<p><?php echo $error; ?></p>
				</div>
			<?php endif; ?>
			<?php if($password_missmatch) : ?>
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<p>Passwords do not match!</p>
				</div>
			<?php endif; ?>
			<?php if($reserved_email) : ?>
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<p>Email is already in use!</p>
				</div>
			<?php endif; ?>
			<form name="registration-form" id="registration-form" action="" method="post">
				<div class="form-group<?php if($reserved_email) : ?> has-warning<?php endif; ?>">
					<label class="control-label" for="email">Email</label>
					<input type="email" name="email" id="email" class="form-control" value="<?php if($reserved_email) : echo $_POST['email']; endif; ?>"/>
				</div>
				<div class="form-group">
					<label class="control-label" for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control"/>
				</div>
				<div class="form-group">
					<label class="control-label" for="password-repeat">Repeat Password</label>
					<input type="password" name="password-repeat" id="password-repeat" class="form-control"/>
				</div>
				<p class="registration-submit">
					<?php wp_nonce_field( $action = 'register_nonce', $name = 'register_wpnonce', $referer, $echo = true ); ?>
					<input type="submit" name="registration-submit" id="registration-submit" class="btn btn-primary" value="Register" />
				</p>
			</form>
		</div>
	</div>
<?php endwhile; ?>