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
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Modal title</h4>
					</div>
					<div class="modal-body">
						<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ) ?>" method="post">
							<div class="form-group">
								<label for="user_login">Username</label>
								<input type="text" name="log" id="user_login" class="form-control" value=""/>
							</div>
							<div class="form-group">
								<label for="user_pass">Password</label>
								<input type="password" name="pwd" id="user_pass" class="form-control" value=""/>
							</div>
							<div class="checkbox">
								<label>
									<input name="rememberme" type="checkbox" id="rememberme" value="forever"/>Remember me
								</label>
							</div>
							<p class="login-submit">
								<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary" value="Log in" />
								<p>Don\'t have an account?<a class="btn btn-link" href="#">Register</a></p>
							</p>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
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
