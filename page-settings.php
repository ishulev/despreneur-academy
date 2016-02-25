<?php if ( is_user_logged_in() ) { ?>
	<?php
	//TODO: Add social media specific checks: E.g. If the url for facebook starts with http:facebook.com, etc.
	global $wpdb;
	$form_message_class = '';
	$form_message       = '';
	$occupation_designer = get_user_meta( $user_id = get_current_user_id(), $key = 'occupation_designer', $single = true );
	$occupation_engineer = get_user_meta( $user_id = get_current_user_id(), $key = 'occupation_engineer', $single = true );
	$occupation_entrepreneur = get_user_meta( $user_id = get_current_user_id(), $key = 'occupation_entrepreneur', $single = true );
	$description = get_user_meta( $user_id = get_current_user_id(), $key = 'description', $single = true );

	$smediaurl_facebook = get_user_meta( $user_id = get_current_user_id(), $key = 'smediaurl_facebook', $single = true );
	$smediaurl_twitter = get_user_meta( $user_id = get_current_user_id(), $key = 'smediaurl_twitter', $single = true );
	$smediaurl_googleplus = get_user_meta( $user_id = get_current_user_id(), $key = 'smediaurl_googleplus', $single = true );

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

			if($_POST['smediaurl_facebook'] !== $smediaurl_facebook && filter_var($_POST['smediaurl_facebook'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
				update_usermeta( get_current_user_id(), 'smediaurl_facebook', $_POST['smediaurl_facebook'], $smediaurl_facebook );
				$smediaurl_facebook = $_POST['smediaurl_facebook'];
			}
			if($_POST['smediaurl_twitter'] !== $smediaurl_twitter && filter_var($_POST['smediaurl_twitter'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
				update_usermeta( get_current_user_id(), 'smediaurl_twitter', $_POST['smediaurl_twitter'], $smediaurl_twitter );
				$smediaurl_twitter = $_POST['smediaurl_twitter'];
			}
			if($_POST['smediaurl_googleplus'] !== $smediaurl_googleplus && filter_var($_POST['smediaurl_googleplus'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
				update_usermeta( get_current_user_id(), 'smediaurl_googleplus', $_POST['smediaurl_googleplus'], $smediaurl_googleplus );
				$smediaurl_googleplus = $_POST['smediaurl_googleplus'];
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

	$user_id = get_current_user_id();
	$payment_status = $wpdb->get_var( $wpdb->prepare( 
		"SELECT status 
		FROM $wpdb->pmpro_memberships_users 
		WHERE user_id = %s", 
		$user_id
		)
	);
	global $pmpro_pages;

	//PMPRO
	global $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $levels;
	ob_start();
	if(pmpro_hasMembershipLevel())
	{
		$ssorder = new MemberOrder();
		$ssorder->getLastMemberOrder();
		$invoices = $wpdb->get_results("SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' ORDER BY timestamp DESC LIMIT 6");		
		?>	
		<div id="pmpro_account">	
			<h5><?php _e("Membership", "pmpro");?></h5>
			<div class="row">
				<div class="col-md-4">
					<h6>Level</h6>
					<?php echo $current_user->membership_level->name?>
					<div class="pmpro_actionlinks">
						<?php do_action("pmpro_member_action_links_before"); ?>
						
						<?php if( pmpro_isLevelExpiringSoon( $current_user->membership_level) ) { ?>
							<a href="<?php echo pmpro_url("checkout", "?level=" . $current_user->membership_level->id, "https")?>"><?php _e("Renew", "pmpro");?></a>
						<?php } ?>

						<?php if((isset($ssorder->status) && $ssorder->status == "success") && (isset($ssorder->gateway) && in_array($ssorder->gateway, array("authorizenet", "paypal", "stripe", "braintree", "payflow", "cybersource")))) { ?>
							<a href="<?php echo pmpro_url("billing", "", "https")?>"><?php _e("Update Billing Info", "pmpro"); ?></a>
						<?php } ?>
						
						<?php 
							//To do: Only show CHANGE link if this level is in a group that has upgrade/downgrade rules
							if(count($pmpro_levels) > 1 && !defined("PMPRO_DEFAULT_LEVEL")) { ?>
							<a href="<?php echo pmpro_url("levels")?>"><?php _e("Change", "pmpro");?></a>
						<?php } ?>
						<a href="<?php echo pmpro_url("cancel", "?level=" . $current_user->membership_level->id)?>"><?php _e("Cancel", "pmpro");?></a>
						<?php do_action("pmpro_member_action_links_after"); ?>
					</div>
					<div class="pmpro_actionlinks">
						<a href="<?php echo pmpro_url("levels")?>"><?php _e("View all Membership Options", "pmpro");?></a>
					</div>
				</div>
				<div class="col-md-4">
					<h6>Billing</h6>
					<?php $level = $current_user->membership_level; ?>
					<p><?php echo pmpro_getLevelCost($level, true, true);?></p>
					<a href="<?php echo pmpro_url("billing", "")?>"><?php _e("Update Billing Info", "pmpro"); ?></a>
				</div>
				<div class="col-md-4">
					<h6>Expiration</h6>
					<?php
						if($current_user->membership_level->enddate) 
							echo date(get_option('date_format'), $current_user->membership_level->enddate);
						else
							echo "---";
					?>
				</div>
			</div>
			<hr>
			<h5>Invoices</h5>
			<div class="row">
				<div class="col-md-4"><h6>Date</h6></div>
				<div class="col-md-4"><h6>Level</h6></div>
				<div class="col-md-4"><h6>Amount</h6></div>
			</div>
			<?php 
				$count = 0;
				foreach($invoices as $invoice) 
				{ ?>
					<div class="row">
						<?php

						//get an member order object
						$invoice_id = $invoice->id;
						$invoice = new MemberOrder;
						$invoice->getMemberOrderByID($invoice_id);
						$invoice->getMembershipLevel();						
						?>
						<div class="col-md-4">
							<a href="<?php echo pmpro_url("invoice", "?invoice=" . $invoice->code)?>"><?php echo date(get_option("date_format"), $invoice->timestamp); ?></a>
						</div>
						<div class="col-md-4">
							<?php echo $invoice->membership_level->name; ?>
						</div>
						<div class="col-md-4">
							<?php echo pmpro_formatPrice($invoice->total);?>
						</div>
					</div>
				<?php 
				}
			?>
		</div>
	<?php
	}
	
	$content = ob_get_contents();
	ob_end_clean();
	
	?>
	<?php require_once(trailingslashit( get_template_directory() ) . 'templates/profile-view.php'); ?>
	</div>
	<div class="container">
		<div class="col-md-6 col-md-offset-3">
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#account" aria-controls="account" role="tab" data-toggle="tab">Account</a></li>
				<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
				<li role="presentation"><a href="#payment" aria-controls="payment" role="tab" data-toggle="tab">Payment</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="account">
					<form id="student-settings" name="student-settings" method="post" class="form-horizontal">
						<?php wp_nonce_field( 'student_settings_save', 'student_settings_nonce' ); ?>
						<div class="row">
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'First Name', 'cp' ); ?>
								</label>
								<input type="text" class="form-control" name="first_name" value="<?php esc_attr_e( $student->user_firstname ); ?>"/>
								<?php do_action( 'coursepress_after_settings_first_name' ); ?>
							</div>
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'Last Name', 'cp' ); ?>
								</label>
								<input type="text" class="form-control" name="last_name" value="<?php esc_attr_e( $student->user_lastname ); ?>"/>
								<?php do_action( 'coursepress_after_settings_last_name' ); ?>
							</div>
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'E-mail', 'cp' ); ?>
								</label>
								<input type="text" class="form-control" name="email" value="<?php esc_attr_e( $student->user_email ); ?>"/>
								<?php do_action( 'coursepress_after_settings_email' ); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'Username', 'cp' ); ?>:
								</label>
								<input type="text" class="form-control" name="username" value="<?php esc_attr_e( $student->user_login ); ?>" disabled="disabled"/>
								<?php do_action( 'coursepress_after_settings_username' ); ?>
							</div>
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'Password', 'cp' ); ?>
								</label>
								<input type="password" class="form-control" name="password" value="" placeholder="<?php _e( "Won't change if empty.", 'cp' ); ?>"/>
								<?php do_action( 'coursepress_after_settings_passwordon' ); ?>
							</div>
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'Confirm Password', 'cp' ); ?>:
								</label>
								<input type="password" class="form-control" name="password_confirmation" value=""/>
								<?php do_action( 'coursepress_after_settings_pasword' ); ?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-5">
								<input type="submit" name="student-settings-submit" class="btn btn-primary" value="<?php _e( 'Save Changes', 'cp' ); ?>"/>
							</div>
						</div>
					</form><?php do_action( 'coursepress_after_settings_form' ); ?>
				</div>
				<div role="tabpanel" class="tab-pane" id="profile">
					<form id="student-settings" name="student-settings" method="post" class="form-horizontal">
						<?php wp_nonce_field( 'student_settings_save', 'student_settings_nonce' ); ?>
						<div>
							<label class="control-label">Occupation</label>
							<div class="checkboxes">
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
							<div class="clearfix"></div>
						</div>
						<div class="">
							<label class="control-label">Biographical info</label>
							<textarea name="user-description" class="form-control" rows="3"><?php echo $description; ?></textarea>
						</div>
						<div class="row">
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'Facebook URL', 'cp' ); ?>
								</label>
								<input type="url" class="form-control" name="smediaurl_facebook" value="<?php echo $smediaurl_facebook; ?>" placeholder="Facebook URL"/>
							</div>
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'Twitter URL', 'cp' ); ?>
								</label>
								<input type="url" class="form-control" name="smediaurl_twitter" value="<?php echo $smediaurl_twitter; ?>" placeholder="Twitter URL"/>
							</div>
							<div class="col-md-4">
								<label class="control-label">
									<?php _e( 'Google Plus URL', 'cp' ); ?>
								</label>
								<input type="url" class="form-control" name="smediaurl_googleplus" value="<?php echo $smediaurl_googleplus; ?>" placeholder="Google Plus URL"/>
							</div>
						</div>
						<input type="submit" name="student-settings-submit" class="btn btn-primary" value="<?php _e( 'Save Changes', 'cp' ); ?>"/>
					</form><?php do_action( 'coursepress_after_settings_form' ); ?>
					<hr>
					<div class="row">
						<div class="col-md-4">
							<?php $background_id = get_user_meta( $user_id = get_current_user_id(), $key = 'profile_background', $single = true );
								if('' !== $background_id) {
									echo wp_get_attachment_image( $attachment_id = $background_id, $size = 'thumbnail', $icon, $attr );
								}
							?>
							<?php echo do_shortcode( '[fu-upload-form title=""][input type="file" name="photo"][input type="submit" class="btn btn-default" value="Upload"][/fu-upload-form]' ); ?>
						</div>
						<div class="col-md-4 bua">	
							<?php echo do_shortcode( '[basic-user-avatars]' ); ?>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="payment">
					<?php
						if($payment_status) {
							// include(PMPRO_DIR . "/pages/account.php");
							// echo pmpro_shortcode_account(array( 'sections' => 'membership,invoices',));
							echo $content;
						} else {
							echo '<h2>It seems like your account hasn\'t been setup yet</h2>';
							echo '<p>Please visit <a href="' . get_page_link( $post = $pmpro_pages['levels'], $leavename, $sample ) . '">this link</a> for more.</p>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
<?php
} else {
	// if( defined('DOING_AJAX') && DOING_AJAX ) { cp_write_log('doing ajax'); }
	// wp_redirect( get_option( 'use_custom_login_form', 1 ) ? CoursePress::instance()->get_signup_slug( true ) : wp_login_url() );
	exit;
}
?>