<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('templates/page', 'header'); ?>
	<?php get_template_part('templates/content', 'page'); ?>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<?php
				$form = '<form name="loginform" id="loginform" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post">
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
						<p>Don\'t have an account?<a class="btn btn-link" href=" ' . esc_url( home_url( '/register' ) ) . ' ">Register</a></p>
					</p>
				</form>';
				echo $form;
			?>
		</div>
	</div>
<?php endwhile; ?>