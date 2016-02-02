<?php if ( is_user_logged_in() ) { ?>
	<?php
	$form_message_class = '';
	$form_message       = '';
	$occupation_designer = get_user_meta( $user_id = get_current_user_id(), $key = 'occupation_designer', $single = true );
	$occupation_engineer = get_user_meta( $user_id = get_current_user_id(), $key = 'occupation_engineer', $single = true );
	$occupation_entrepreneur = get_user_meta( $user_id = get_current_user_id(), $key = 'occupation_entrepreneur', $single = true );
	$description = get_user_meta( $user_id = get_current_user_id(), $key = 'description', $single = true );

	if ( isset( $_POST['student-settings-submit'] ) ) {

		if ( ! isset( $_POST['student_settings_nonce'] ) || ! wp_verify_nonce( $_POST['student_settings_nonce'], 'student_settings_save' )
		) {
			_e( "Changed can't be saved because nonce didn't verify.", 'cp' );
		} else {
			if($_POST['occupation_designer'] !== $occupation_designer) {
				update_usermeta( get_current_user_id(), 'occupation_designer', $_POST['occupation_designer'], $occupation_designer );
				$occupation_designer = $_POST['occupation_designer'];
			}
			if($_POST['occupation_engineer'] !== $occupation_engineer) {
				update_usermeta( get_current_user_id(), 'occupation_engineer', $_POST['occupation_engineer'], $occupation_engineer );
				$occupation_engineer = $_POST['occupation_engineer'];
			}
			if($_POST['occupation_entrepreneur'] !== $occupation_entrepreneur) {
				update_usermeta( get_current_user_id(), 'occupation_entrepreneur', $_POST['occupation_entrepreneur'], $occupation_entrepreneur );
				$occupation_entrepreneur = $_POST['occupation_entrepreneur'];
			}
			if($_POST['user-description'] !== $description) {
				update_usermeta( get_current_user_id(), 'description', $_POST['user-description'], $description );
				$description = $_POST['user-description'];
			}

			$student_data       = array();
			$student_data['ID'] = get_current_user_id();
			$form_errors        = 0;

			do_action( 'coursepress_before_settings_validation' );

			if ( $_POST['password'] != '' ) {
				if ( $_POST['password'] == $_POST['password_confirmation'] ) {
					$student_data['user_pass'] = $_POST['password'];
				} else {
					$form_message       = __( "Passwords don't match", 'cp' );
					$form_message_class = 'red';
					$form_errors ++;
				}
			}

			$student_data['user_email'] = $_POST['email'];
			$student_data['first_name'] = $_POST['first_name'];
			$student_data['last_name']  = $_POST['last_name'];

			if ( ! is_email( $_POST['email'] ) ) {
				$form_message       = __( 'E-mail address is not valid.', 'cp' );
				$form_message_class = 'red';
				$form_errors ++;
			}

			if ( $form_errors == 0 ) {
				$student = new Student( get_current_user_id() );
				if ( $student->update_student_data( $student_data ) ) {
					$form_message       = __( 'Profile has been updated successfully.', 'cp' );
					$form_message_class = 'regular';
				} else {
					$form_message       = __( 'An error occured while updating. Please check the form and try again.', 'cp' );
					$form_message_class = 'red';
				}
			}
		}
	}
	$student = new Student( get_current_user_id() );
	?>
	<h1>Rnaodmawm</h1>
	<p class="<?php echo esc_attr( 'form-info-' . $form_message_class ); ?>"><?php echo esc_html( $form_message ); ?></p>
	<?php do_action( 'coursepress_before_settings_form' ); ?>
	<form id="student-settings" name="student-settings" method="post" class="form-horizontal">
		<?php wp_nonce_field( 'student_settings_save', 'student_settings_nonce' ); ?>
		<div class="form-group">
			<label class="col-md-3 control-label">
				<?php _e( 'First Name', 'cp' ); ?>
			</label>
			<div class="col-md-5">
				<input type="text" class="form-control" name="first_name" value="<?php esc_attr_e( $student->user_firstname ); ?>"/>
			</div>
			<?php do_action( 'coursepress_after_settings_first_name' ); ?>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">
				<?php _e( 'Last Name', 'cp' ); ?>
			</label>
			<div class="col-md-5">
				<input type="text" class="form-control" name="last_name" value="<?php esc_attr_e( $student->user_lastname ); ?>"/>
			</div>
			<?php do_action( 'coursepress_after_settings_last_name' ); ?>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">
				<?php _e( 'E-mail', 'cp' ); ?>
			</label>
			<div class="col-md-5">
				<input type="text" class="form-control" name="email" value="<?php esc_attr_e( $student->user_email ); ?>"/>
			</div>
			<?php do_action( 'coursepress_after_settings_email' ); ?>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">
				<?php _e( 'Username', 'cp' ); ?>:
			</label>
			<div class="col-md-5">
				<input type="text" class="form-control" name="username" value="<?php esc_attr_e( $student->user_login ); ?>" disabled="disabled"/>
			</div>
			<?php do_action( 'coursepress_after_settings_username' ); ?>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Occupation</label>
			<div class="col-md-5">
				<div class="checkbox">
					<label>
						<input name="occupation_designer" type="checkbox" id="occupation-designer" value="1" <?php echo ( '1' === $occupation_designer ? 'checked' : ''); ?>/>Designer
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input name="occupation_engineer" type="checkbox" id="occupation-engineer" value="1" <?php echo ( '1' === $occupation_engineer ? 'checked' : ''); ?>/>Engineer
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input name="occupation_entrepreneur" type="checkbox" id="occupation-entrepreneur" value="1" <?php echo ( '1' === $occupation_entrepreneur ? 'checked' : ''); ?>/>Entrepreneur
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Description</label>
			<div class="col-md-5">
				<textarea name="user-description" class="form-control" rows="3"><?php echo $description; ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">
				<?php _e( 'Password', 'cp' ); ?>
			</label>
			<div class="col-md-5">
				<input type="password" class="form-control" name="password" value="" placeholder="<?php _e( "Won't change if empty.", 'cp' ); ?>"/>
			</div>
			<?php do_action( 'coursepress_after_settings_passwordon' ); ?>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">
				<?php _e( 'Confirm Password', 'cp' ); ?>:
			</label>
			<div class="col-md-5">
				<input type="password" class="form-control" name="password_confirmation" value=""/>
			</div>
			<?php do_action( 'coursepress_after_settings_pasword' ); ?>
		</div>
		<div class="form-group">
			<div class="col-md-5 col-md-offset-3">
				<input type="submit" name="student-settings-submit" class="btn btn-primary" value="<?php _e( 'Save Changes', 'cp' ); ?>"/>
			</div>
		</div>
	</form><?php do_action( 'coursepress_after_settings_form' ); ?>
<?php
} else {
	// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
	wp_redirect( get_option( 'use_custom_login_form', 1 ) ? CoursePress::instance()->get_signup_slug( true ) : wp_login_url() );
	exit;
}
?>