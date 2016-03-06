<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
function is_pmpro_page() {
	global $pmpro_pages;
	if(is_array($pmpro_pages)) {
		foreach ($pmpro_pages as $value) {
			if(is_page( $value )) {
				return true;
			}
		}
	}
	return false;
}
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
		do_action('get_header');
		$section_classes = array();
		$custom_heading = '';
		$custom_subheading = '';
		$header_cta = array();
		if(is_pmpro_page() || is_post_type_archive( 'course' )) {
			$section_classes[] = 'partial-height';
			$section_classes[] = 'full-width-background';
			if(is_pmpro_page()) {
				global $current_user;
				if(empty($current_user->membership_level)) {
					$confirmation_message = "<p>" . __('Your payment has been submitted. Your membership will be activated shortly.', 'pmpro') . "</p>";
				}
				else {
					$confirmation_message = "<p>" . sprintf(__('Thank you for your membership to %s.</p><p>Your %s membership is now active.', 'pmpro'), get_bloginfo("name"), $current_user->membership_level->name) . "</p>";
				}
				$custom_subheading = $confirmation_message;
			}
		}
		else if(is_page() && !is_singular( $post_types = 'course' )) {
			$the_id = get_the_ID();
			$header_section = get_post_meta( $post_id = $the_id, $key = 'full_width_top_section', $single = true );
			$partial_height = get_post_meta( $post_id = $the_id, $key = 'partial_height', $single = true );
			if($partial_height) {
				$section_classes[] = 'partial-height';
			}
			if($header_section) {
				$section_classes[] = 'full-width-background';
				$custom_heading = get_post_meta( $post_id = $the_id, $key = 'heading', $single = true );
				$custom_subheading = get_post_meta( $post_id = $the_id, $key = 'subheading', $single = true );
				$cta = get_post_meta( $post_id = $the_id, $key = 'cta_button', $single = true );
				if($cta) {
					$header_cta['title'] = get_post_meta( $post_id = $the_id, $key = 'cta_title', $single = true );
					$header_cta['action'] = get_permalink(get_post_meta( $post_id = $the_id, $key = 'cta_action', $single = true ));
				}
			}
		}
		?>
		<?php if(!empty($section_classes)) : ?>
			<div class="<?php echo implode(' ', $section_classes); ?>">
				<?php get_template_part('templates/header'); ?>
				<div class="vertical-center">
					<section class="text-center container">
						<?php if(is_page( 'settings' ) || is_page( 'profile' )) :
							require_once(trailingslashit( get_template_directory() ) . 'templates/profile-view.php');
						else :?>
							<h1><?php if( '' !== $custom_heading ) : echo $custom_heading; else: the_title(); endif;?></h1>
							<?php if( '' !== $custom_subheading ) : ?>
								<h2><?php echo $custom_subheading; ?></h2>
							<?php endif; ?>
							<?php if(!empty($header_cta)) : ?>
								<a href="<?php echo $header_cta['action']; ?>" class="btn btn-primary"><?php echo $header_cta['title']; ?></a>
							<?php endif; ?>
						<?php endif; ?>
					</section>
				</div>
			</div>
			<div class="container">
				<?php include Wrapper\template_path(); ?>
			</div>
		<?php elseif(is_singular( $post_types = 'course' )) : ?>
			<?php get_template_part('templates/course-single-header'); ?>
			<div class="container">
				<?php include Wrapper\template_path(); ?>
			</div>
		<?php else : ?>
			<?php get_template_part('templates/header'); ?>
			<div class="container">
				<?php if(!is_single()) : ?><h1><?php the_title(); ?></h1>
				<?php endif; ?>
				<?php include Wrapper\template_path(); ?>
			</div>
		<?php endif; ?>
		<?php
		do_action('get_footer');
		if(!is_user_logged_in()) : ?>
			<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="ModalLogin">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h5 class="modal-title" id="myModalLabel">Login</h5>
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
											<input name="rememberme" type="checkbox" id="rememberme"/><span class="rememberme">Remember me</span>
										</label>
										<a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
									</div>
								</div>
								<p class="login-submit">
									<button id="login-submit" class="btn btn-primary btn-block">Log in</button>
								</p>
								<p class="direct-other-modal">If you are not a member, <button class="btn btn-link" id="toggle-modal-register" href="#">register here</button>.</p>
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
							<h5 class="modal-title" id="myModalLabel">Register</h5>
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
									<button id="register-submit" class="btn btn-primary btn-block">Register</button>
								</p>
								<p class="direct-other-modal">If you already have an account, <button class="btn btn-link" id="toggle-modal-login" href="#">log in now</button>.</p>
								<?php wp_nonce_field( 'ajax-register-nonce', 'ajax-register' ); ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php endif;
		get_template_part('templates/footer');
		wp_footer();
	?>
	</body>
</html>
