<?php 
	global $wpdb, $current_user, $pmpro_invoice, $pmpro_msg, $pmpro_msgt;
	
	if(empty($current_user->membership_level))
		$confirmation_message = "<p>" . __('Your payment has been submitted. Your membership will be activated shortly.', 'pmpro') . "</p>";
	else
		$confirmation_message = "<p>" . sprintf(__('Thank you for your membership to %s.</p><p>Your %s membership is now active.', 'pmpro'), get_bloginfo("name"), $current_user->membership_level->name) . "</p>";
?>	
			<?php echo $confirmation_message; ?>
		</section> <!-- CLOSING VERTICAL ALIGN SECTION -->
	</div> <!-- CLOSING VERTICAL ALIGN PARENT -->
</div> <!-- CLOSING FULL WIDTH TAG -->
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<?php if(!empty($pmpro_invoice) && !empty($pmpro_invoice->id)) {
				$pmpro_invoice->getUser();
				$pmpro_invoice->getMembershipLevel();			

				$confirmation_message = "<p>" . sprintf(__('Below are details about your membership account and a receipt for your initial membership invoice. A welcome email with a copy of your initial membership invoice has been sent to %s.', 'pmpro'), $pmpro_invoice->user->user_email) . "</p>";
				
				//check instructions		
				if($pmpro_invoice->gateway == "check" && !pmpro_isLevelFree($pmpro_invoice->membership_level))
					$confirmation_message .= wpautop(pmpro_getOption("instructions"));
				
				$confirmation_message = apply_filters("pmpro_confirmation_message", $confirmation_message, $pmpro_invoice);				
				
				?>

				<?php echo apply_filters("the_content", $confirmation_message);	?>	
				<?php if($pmpro_msg) : ?>
					<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
				<?php endif; ?>
				<h5>
					<?php printf(__('Invoice #%s on %s', 'pmpro'), $pmpro_invoice->code, date_i18n(get_option('date_format'), $pmpro_invoice->timestamp));?>		
				</h5>
				<a class="pmpro_a-print" href="javascript:window.print()"><?php _e('Print', 'pmpro');?></a>
				<?php echo $pmpro_invoice->billing->name; ?> (<?php echo $current_user->user_email; ?>)
				<div class="row">
					<div class="col-md-4">
						<h6>Billing Address</h6>
						<?php if(!empty($pmpro_invoice->billing->name)) { ?>
						<?php echo $pmpro_invoice->billing->name?><br />
						<?php echo $pmpro_invoice->billing->street?><br />						
						<?php if($pmpro_invoice->billing->city && $pmpro_invoice->billing->state) { ?>
						<?php echo $pmpro_invoice->billing->city?>, <?php echo $pmpro_invoice->billing->state?> <?php echo $pmpro_invoice->billing->zip?> <?php echo $pmpro_invoice->billing->country?><br />												
						<?php } ?>
						<?php echo formatPhone($pmpro_invoice->billing->phone)?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<h6>Payment method</h6>
						<?php if($pmpro_invoice->accountnumber) { ?>
						<?php echo $pmpro_invoice->cardtype?> <?php _e('ending in', 'pmpro');?> <?php echo last4($pmpro_invoice->accountnumber)?><br />
						<small><?php _e('Expiration', 'pmpro');?>: <?php echo $pmpro_invoice->expirationmonth?>/<?php echo $pmpro_invoice->expirationyear?></small>
						<?php } elseif($pmpro_invoice->payment_type) { ?>
						<?php echo $pmpro_invoice->payment_type?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<h6>Membership Level</h6>
						<?php if(!empty($current_user->membership_level)) echo $current_user->membership_level->name; else _ex("Pending", "User without membership is in {pending} status.", "pmpro");?>
						<?php if($pmpro_invoice->total) echo pmpro_formatPrice($pmpro_invoice->total); else echo "---";?>
					</div>
				</div>
				<?php 
			} 
			else 
			{
				$confirmation_message .= "<p>" . sprintf(__('Below are details about your membership account. A welcome email has been sent to %s.', 'pmpro'), $current_user->user_email) . "</p>";
				
				$confirmation_message = apply_filters("pmpro_confirmation_message", $confirmation_message, false);
				
				echo $confirmation_message;
				?>	
				<ul>
					<li><strong><?php _e('Account', 'pmpro');?>:</strong> <?php echo $current_user->display_name?> (<?php echo $current_user->user_email?>)</li>
					<li><strong><?php _e('Membership Level', 'pmpro');?>:</strong> <?php if(!empty($current_user->membership_level)) echo $current_user->membership_level->name; else _ex("Pending", "User without membership is in {pending} status.", "pmpro");?></li>
				</ul>	
				<?php 
			} 
			?>
			<?php if(!empty($current_user->membership_level)) { ?>
			<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/profile', 'relative' ) ); ?>"><?php _e('View your account', 'pmpro');?></a>
			<?php } else { ?>
			<?php _e('If your account is not activated within a few minutes, please contact the site owner.', 'pmpro');?>
			<?php } ?>
		</div>
	</div>
</div>
