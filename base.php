<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
<!--[if IE]>
<div class="alert alert-warning">
<?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
</div>
<![endif]-->
<?php
do_action('get_header'); ?>
<?php if(is_front_page()): ?>
<div class="front-page full-width-background">
<?php elseif(is_page( 'profile' )) : ?>
	<div class="profile-page full-width-background">
	<?php endif; ?>
	<?php
	get_template_part('templates/header');
	?>
	<?php if(!is_front_page() && !is_page( 'profile' )): ?>
		<div class="container">
		<?php endif; ?>
		<?php include Wrapper\template_path(); ?>
		<?php if(!is_front_page() && !is_page( 'profile' )): ?>
		</div>
	<?php endif; ?>
	<?php if(!is_user_logged_in()) : ?>
		<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="ModalLogin">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Login</h4>
					</div>
					<div class="modal-body">
						<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ) ?>" method="post">
							<div class="status"></div>
							<div class="form-group">
								<label for="login-email">Email</label>
								<input type="text" name="login-email" id="login-email" class="form-control" value=""/>
							</div>
							<div class="form-group">
								<label for="login-password">Password</label>
								<input type="password" name="login-password" id="login-password" class="form-control" value=""/>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>
										<input name="rememberme" type="checkbox" id="rememberme"/>Remember me
									</label>
									<a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
								</div>
							</div>
							<p class="login-submit">
								<button id="login-submit" class="btn btn-primary">Log in</button>
								<p>Don't have an account? <button class="btn btn-link" id="toggle-modal-register" href="#">Register</button></p>
							</p>
							<?php wp_nonce_field( 'ajax-login-nonce', 'ajax-login' ); ?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="ModalRegister">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Register</h4>
					</div>
					<div class="modal-body">
						<form name="registerform" id="registerform" action="register" method="post">
							<div class="status"></div>
							<div class="form-group">
								<label for="register-email">Email</label>
								<input type="email" name="register-email" id="register-email" class="form-control" value=""/>
							</div>
							<div class="form-group">
								<label for="register-password">Password</label>
								<input type="password" name="register-password" id="register-password" class="form-control" value=""/>
							</div>
							<p class="login-submit">
								<button id="register-submit" class="btn btn-primary" value="Register" />
								<p>Already a member? <button class="btn btn-link" id="toggle-modal-login" href="#">Login here</button></p>
							</p>
							<?php wp_nonce_field( 'ajax-register-nonce', 'ajax-register' ); ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
	do_action('get_footer');
	get_template_part('templates/footer');
	wp_footer();
	?>
</body>
</html>
