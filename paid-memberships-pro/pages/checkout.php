<?php
	global $gateway, $pmpro_review, $skip_account_fields, $pmpro_paypal_token, $wpdb, $current_user, $pmpro_msg, $pmpro_msgt, $pmpro_requirebilling, $pmpro_level, $pmpro_levels, $tospage, $pmpro_show_discount_code, $pmpro_error_fields;
	global $discount_code, $username, $password, $password2, $bfirstname, $blastname, $baddress1, $baddress2, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail, $bconfirmemail, $CardType, $AccountNumber, $ExpirationMonth,$ExpirationYear;

	/**
	 * Filter to set if PMPro uses email or text as the type for email field inputs.
	 *
	 * @since 1.8.4.5
	 *
	 * @param bool $use_email_type, true to use email type, false to use text type
	 */
	$pmpro_email_field_type = apply_filters('pmpro_email_field_type', true);
	$skip_account_fields = true;
?>
		</section> <!-- CLOSING VERTICAL ALIGN SECTION -->
	</div> <!-- CLOSING VERTICAL ALIGN PARENT -->
</div> <!-- CLOSING FULL WIDTH TAG -->
<div class="container">
	<div class="row">
		<div class="col-md-offset-2 col-md-8">
			<?php if($pmpro_msg)
				{
			?>
				<div id="pmpro_message" class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
			<?php
				}
				else
				{
			?>
				<div id="pmpro_message" class="pmpro_message" style="display: none;"></div>
			<?php
				}
			?>
			<p><?php printf(__('You have selected the <strong>%s</strong> membership level.', 'pmpro'), $pmpro_level->name);?></p>
			<?php if($current_user->ID && !$pmpro_review) { ?>
				<p id="pmpro_account_loggedin">
					<?php printf(__('You are logged in as <strong>%s</strong>. If you would like to use a different account for this membership, <a href="%s">log out now</a>.', 'pmpro'), $current_user->user_login, wp_logout_url($_SERVER['REQUEST_URI'])); ?>
				</p>
			<?php } ?>
			<?php if($discount_code && pmpro_checkDiscountCode($discount_code)) { ?>
				<?php printf(__('<p class="pmpro_level_discount_applied">The <strong>%s</strong> code has been applied to your order.</p>', 'pmpro'), $discount_code);?>
			<?php } ?>
			<?php echo wpautop(pmpro_getLevelCost($pmpro_level)); ?>
			<hr>
			<h4>Billing Address</h4>
			<p>All fields are required</p>
			<form id="pmpro_form" class="membership-checkout" action="<?php echo pmpro_url("checkout", "?level=" . $pmpro_level->id); ?>" method="post">
				<div class="row">
					<div class="col-md-4">
						<label for="bfirstname"><?php _e('First Name', 'pmpro');?></label>
						<input id="bfirstname" name="bfirstname" type="text" class="input <?php echo pmpro_getClassForField("bfirstname");?>" size="30" value="<?php echo esc_attr($bfirstname)?>" />
					</div>
					<div class="col-md-4">
						<label for="blastname"><?php _e('Last Name', 'pmpro');?></label>
						<input id="blastname" name="blastname" type="text" class="input <?php echo pmpro_getClassForField("blastname");?>" size="30" value="<?php echo esc_attr($blastname)?>" />
					</div>
					<div class="col-md-4">
						<label for="baddress1"><?php _e('Address 1', 'pmpro');?></label>
						<input id="baddress1" name="baddress1" type="text" class="input <?php echo pmpro_getClassForField("baddress1");?>" size="30" value="<?php echo esc_attr($baddress1)?>" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="baddress2"><?php _e('Address 2', 'pmpro');?></label>
						<input id="baddress2" name="baddress2" type="text" class="input <?php echo pmpro_getClassForField("baddress2");?>" size="30" value="<?php echo esc_attr($baddress2)?>" />
					</div>
					<?php
					$longform_address = apply_filters("pmpro_longform_address", true);
					if($longform_address)
					{
					?>
					<div class="col-md-4">
						<label for="bcity"><?php _e('City', 'pmpro');?></label>
						<input id="bcity" name="bcity" type="text" class="input <?php echo pmpro_getClassForField("bcity");?>" size="30" value="<?php echo esc_attr($bcity)?>" />
					</div>
					<div class="col-md-4">
						<label for="bstate"><?php _e('State', 'pmpro');?></label>
						<input id="bstate" name="bstate" type="text" class="input <?php echo pmpro_getClassForField("bstate");?>" size="30" value="<?php echo esc_attr($bstate)?>" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="bzipcode"><?php _e('Postal Code', 'pmpro');?></label>
						<input id="bzipcode" name="bzipcode" type="text" class="input <?php echo pmpro_getClassForField("bzipcode");?>" size="30" value="<?php echo esc_attr($bzipcode)?>" />
					</div>
					<?php
					}
					else
					{ ?>
						<div  class="col-md-4">
							<label for="bcity_state_zip"><?php _e('City, State Zip', 'pmpro');?></label>
							<input id="bcity" name="bcity" type="text" class="input <?php echo pmpro_getClassForField("bcity");?>" size="14" value="<?php echo esc_attr($bcity)?>" />,
							<?php
							$state_dropdowns = apply_filters("pmpro_state_dropdowns", false);
							if($state_dropdowns === true || $state_dropdowns == "names")
							{
								global $pmpro_states;
								?>
								<select name="bstate" class=" <?php echo pmpro_getClassForField("bstate");?>">
									<option value="">--</option>
									<?php
									foreach($pmpro_states as $ab => $st)
									{
										?>
										<option value="<?php echo esc_attr($ab);?>" <?php if($ab == $bstate) { ?>selected="selected"<?php } ?>><?php echo $st;?></option>
										<?php } ?>
									</select>
									<?php
								}
								elseif($state_dropdowns == "abbreviations")
								{
									global $pmpro_states_abbreviations;
									?>
									<select name="bstate" class=" <?php echo pmpro_getClassForField("bstate");?>">
										<option value="">--</option>
										<?php
										foreach($pmpro_states_abbreviations as $ab)
										{
											?>
											<option value="<?php echo esc_attr($ab);?>" <?php if($ab == $bstate) { ?>selected="selected"<?php } ?>><?php echo $ab;?></option>
											<?php } ?>
										</select>
										<?php
									}
									else
									{
										?>
										<input id="bstate" name="bstate" type="text" class="input <?php echo pmpro_getClassForField("bstate");?>" size="2" value="<?php echo esc_attr($bstate)?>" />
										<?php
									}
									?>
									<input id="bzipcode" name="bzipcode" type="text" class="input <?php echo pmpro_getClassForField("bzipcode");?>" size="5" value="<?php echo esc_attr($bzipcode)?>" />
								</div>
								<?php
							}
							?>

							<?php
							$show_country = apply_filters("pmpro_international_addresses", true);
							if($show_country)
							{
								?>
								<div class="col-md-4">
									<label for="bcountry"><?php _e('Country', 'pmpro');?></label>
									<select name="bcountry" class=" <?php echo pmpro_getClassForField("bcountry");?>">
										<?php
										global $pmpro_countries, $pmpro_default_country;
										if(!$bcountry)
											$bcountry = $pmpro_default_country;
										foreach($pmpro_countries as $abbr => $country)
										{
											?>
											<option value="<?php echo $abbr?>" <?php if($abbr == $bcountry) { ?>selected="selected"<?php } ?>><?php echo $country?></option>
											<?php
										}
										?>
									</select>
								</div>
								<?php
							}
							else
							{
								?>
								<input type="hidden" name="bcountry" value="US" />
								<?php
							}
							?>
					<div class="col-md-4">
						<label for="bphone"><?php _e('Phone', 'pmpro');?></label>
						<input id="bphone" name="bphone" type="text" class="input <?php echo pmpro_getClassForField("bphone");?>" size="30" value="<?php echo esc_attr(formatPhone($bphone))?>" />
					</div>
				</div>
				<div class="row">
					<?php if($skip_account_fields) { ?>
					<?php
					if($current_user->ID)
					{
						$bemail = $current_user->user_email;
						$bconfirmemail = $current_user->user_email;
					}
					?>
					<div>
						<input id="bemail" name="bemail" type="hidden" class="input <?php echo pmpro_getClassForField("bemail");?>" size="30" value="<?php echo esc_attr($bemail)?>" />
					</div>
					<?php
					$pmpro_checkout_confirm_email = apply_filters("pmpro_checkout_confirm_email", true);
					if($pmpro_checkout_confirm_email)
					{
						?>
						<div>
							<input id="bconfirmemail" name="bconfirmemail" type="hidden" class="input <?php echo pmpro_getClassForField("bconfirmemail");?>" size="30" value="<?php echo esc_attr($bconfirmemail)?>" />

						</div>
						<?php
					}
					else
					{
						?>
						<input type="hidden" name="bconfirmemail_copy" value="1" />
						<?php
					}
					?>
					<?php } ?>
				</div>
				<hr>
				<?php
				$pmpro_accepted_credit_cards = pmpro_getOption("accepted_credit_cards");
				$pmpro_accepted_credit_cards = explode(",", $pmpro_accepted_credit_cards);
				$pmpro_accepted_credit_cards_string = pmpro_implodeToEnglish($pmpro_accepted_credit_cards);
			?>
				<h4><?php _e('Payment Information', 'pmpro');?></h4>
				<span><?php printf(__('We Accept PayPal, %s', 'pmpro'), $pmpro_accepted_credit_cards_string);?></span>
				<?php $sslseal = pmpro_getOption("sslseal");
				if($sslseal)
				{
				?>
					<div class="pmpro_sslseal"><?php echo stripslashes($sslseal)?></div>
				<?php
				} ?>
				<h5><?php _e('Payment Method', 'pmpro');?></h5>
				<div class="payment-methods">
					<div class="radio">
						<label>
							<input type="radio" name="gateway" value="paypal" <?php if(!$gateway || $gateway == "paypal") { ?>checked="checked"<?php } ?> />
							<?php _e('Check Out with a Credit Card', 'pmpro');?>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="gateway" value="paypalexpress" <?php if($gateway == "paypalexpress") { ?>checked="checked"<?php } ?> />
							<?php _e('Check Out with PayPal', 'pmpro');?>
						</label>
					</div>
				</div>
				<div class="<?php if($gateway == "paypalexpress") { echo 'hidden'; } ?> row card-fields">
					<?php
						$pmpro_include_cardtype_field = apply_filters('pmpro_include_cardtype_field', false);
						if($pmpro_include_cardtype_field)
						{
							?>
							<div class="col-md-4 pmpro_payment-card-type">
								<label for="CardType"><?php _e('Card Type', 'pmpro');?></label>
								<select id="CardType" name="CardType" class=" <?php echo pmpro_getClassForField("CardType");?>">
									<?php foreach($pmpro_accepted_credit_cards as $cc) { ?>
										<option value="<?php echo $cc?>" <?php if($CardType == $cc) { ?>selected="selected"<?php } ?>><?php echo $cc?></option>
									<?php } ?>
								</select>
							</div>
							<?php
						}
						else
						{
							?>
							<input type="hidden" id="CardType" name="CardType" value="<?php echo esc_attr($CardType);?>" />
							<script>
								<!--
								jQuery(document).ready(function() {
										jQuery('#AccountNumber').validateCreditCard(function(result) {
											var cardtypenames = {
												"amex"                      : "American Express",
												"diners_club_carte_blanche" : "Diners Club Carte Blanche",
												"diners_club_international" : "Diners Club International",
												"discover"                  : "Discover",
												"jcb"                       : "JCB",
												"laser"                     : "Laser",
												"maestro"                   : "Maestro",
												"mastercard"                : "Mastercard",
												"visa"                      : "Visa",
												"visa_electron"             : "Visa Electron"
											}

											if(result.card_type)
												jQuery('#CardType').val(cardtypenames[result.card_type.name]);
											else
												jQuery('#CardType').val('Unknown Card Type');
										});
								});
								-->
							</script>
							<?php
						}
					?>

					<div class="col-md-4 pmpro_payment-account-number">
						<label for="AccountNumber"><?php _e('Card Number', 'pmpro');?></label>
						<input id="AccountNumber" name="AccountNumber" class="input <?php echo pmpro_getClassForField("AccountNumber");?>" type="text" size="25" value="<?php echo esc_attr($AccountNumber)?>" data-encrypted-name="number" autocomplete="off" />
					</div>
					<div class="col-md-4">
						<label for="ExpirationMonth"><?php _e('Expiration Date', 'pmpro');?></label>
						<div class="pmpro_payment-expiration">
							<select id="ExpirationMonth" name="ExpirationMonth" class=" <?php echo pmpro_getClassForField("ExpirationMonth");?>">
								<option value="01" <?php if($ExpirationMonth == "01") { ?>selected="selected"<?php } ?>>01</option>
								<option value="02" <?php if($ExpirationMonth == "02") { ?>selected="selected"<?php } ?>>02</option>
								<option value="03" <?php if($ExpirationMonth == "03") { ?>selected="selected"<?php } ?>>03</option>
								<option value="04" <?php if($ExpirationMonth == "04") { ?>selected="selected"<?php } ?>>04</option>
								<option value="05" <?php if($ExpirationMonth == "05") { ?>selected="selected"<?php } ?>>05</option>
								<option value="06" <?php if($ExpirationMonth == "06") { ?>selected="selected"<?php } ?>>06</option>
								<option value="07" <?php if($ExpirationMonth == "07") { ?>selected="selected"<?php } ?>>07</option>
								<option value="08" <?php if($ExpirationMonth == "08") { ?>selected="selected"<?php } ?>>08</option>
								<option value="09" <?php if($ExpirationMonth == "09") { ?>selected="selected"<?php } ?>>09</option>
								<option value="10" <?php if($ExpirationMonth == "10") { ?>selected="selected"<?php } ?>>10</option>
								<option value="11" <?php if($ExpirationMonth == "11") { ?>selected="selected"<?php } ?>>11</option>
								<option value="12" <?php if($ExpirationMonth == "12") { ?>selected="selected"<?php } ?>>12</option>
							</select>/<select id="ExpirationYear" name="ExpirationYear" class=" <?php echo pmpro_getClassForField("ExpirationYear");?>">
								<?php
									for($i = date("Y"); $i < date("Y") + 10; $i++)
									{
								?>
									<option value="<?php echo $i?>" <?php if($ExpirationYear == $i) { ?>selected="selected"<?php } ?>><?php echo $i?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
					<?php
					$pmpro_show_cvv = apply_filters("pmpro_show_cvv", true);
					if($pmpro_show_cvv)
					{ ?>
						<div class="pmpro_payment-cvv">
							<label for="CVV"><?php _e('CVV', 'pmpro');?></label>
							<div class="row">
								<div class="col-md-4">
									<input class="input" id="CVV" name="CVV" type="text" size="4" value="<?php if(!empty($_REQUEST['CVV'])) { echo esc_attr($_REQUEST['CVV']); }?>" class=" <?php echo pmpro_getClassForField("CVV");?>" />
								</div>
								<div class="col-md-8">
									<small>(<a href="javascript:void(0);" onclick="javascript:window.open('<?php echo pmpro_https_filter(PMPRO_URL)?>/pages/popup-cvv.html','cvv','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=600, height=475');"><?php _e("what's this?", 'pmpro');?></a>)</small>
								</div>
							</div>
						</div>
						<?php
					} ?>
					</div>
				</div>
				<hr>
				<div class="pmpro_submit">
					<?php if($pmpro_review) { ?>
						<span id="pmpro_submit_span">
							<input type="hidden" name="confirm" value="1" />
							<input type="hidden" name="token" value="<?php echo esc_attr($pmpro_paypal_token)?>" />
							<input type="hidden" name="gateway" value="<?php echo esc_attr($gateway); ?>" />
							<input type="submit" class="pmpro_btn pmpro_btn-submit-checkout btn btn-primary" value="<?php _e('Complete Payment', 'pmpro');?> &raquo;" />
						</span>
					<?php } else { ?>
						<?php
							$pmpro_checkout_default_submit_button = apply_filters('pmpro_checkout_default_submit_button', true);
							if($pmpro_checkout_default_submit_button)
							{
							?>
							<span id="pmpro_submit_span">
								<input type="hidden" name="submit-checkout" value="1" />
								<input type="submit" class="pmpro_btn pmpro_btn-submit-checkout btn btn-primary" value="<?php if($pmpro_requirebilling) { _e('Submit and Check Out', 'pmpro'); } else { _e('Submit and Confirm', 'pmpro');}?> &raquo;" />
							</span>
							<?php
							}
						?>
					<?php } ?>
					<span id="pmpro_processing_message" style="visibility: hidden;">
						<?php
							$processing_message = apply_filters("pmpro_processing_message", __("Processing...", "pmpro"));
							echo $processing_message;
						?>
					</span>
				</div>
			</form>
		</div>
	</div>
</div>	
<script>
<!--
	var cardFields = jQuery('.card-fields');
	var cardSubmit = jQuery('#pmpro_submit_span');
	var payPalSubmit = jQuery('#pmpro_paypalexpress_checkout');
	jQuery('.payment-methods input').on('change', function(){
		if(jQuery(this).val() == 'paypalexpress') {
			cardFields.addClass('hidden');
			cardSubmit.hide();
			payPalSubmit.show();
		} else {
			payPalSubmit.hide();
			cardFields.removeClass('hidden');
			cardSubmit.show();
		}
	});
	// Find ALL <form> tags on your page
	jQuery('form').submit(function(){
		// On submit disable its submit button
		jQuery('input[type=submit]', this).attr('disabled', 'disabled');
		jQuery('input[type=image]', this).attr('disabled', 'disabled');
		jQuery('#pmpro_processing_message').css('visibility', 'visible');
	});

	//iOS Safari fix (see: http://stackoverflow.com/questions/20210093/stop-safari-on-ios7-prompting-to-save-card-data)
	var userAgent = window.navigator.userAgent;
	if(userAgent.match(/iPad/i) || userAgent.match(/iPhone/i)) {
		jQuery('input[type=submit]').click(function() {
			try{
				jQuery("input[type=password]").attr("type", "hidden");
			} catch(ex){
				try {
					jQuery("input[type=password]").prop("type", "hidden");
				} catch(ex) {}
			}
		});
	}

	//unhighlight error fields when the user edits them
	jQuery('.pmpro_error').bind("change keyup input", function() {
		jQuery(this).removeClass('pmpro_error');
	});

	//click apply button on enter in discount code box
	jQuery('#discount_code').keydown(function (e){
	    if(e.keyCode == 13){
		   e.preventDefault();
		   jQuery('#discount_code_button').click();
	    }
	});

	//hide apply button if a discount code was passed in
	<?php if(!empty($_REQUEST['discount_code'])) {?>
		jQuery('#discount_code_button').hide();
		jQuery('#discount_code').bind('change keyup', function() {
			jQuery('#discount_code_button').show();
		});
	<?php } ?>

	//click apply button on enter in *other* discount code box
	jQuery('#other_discount_code').keydown(function (e){
	    if(e.keyCode == 13){
		   e.preventDefault();
		   jQuery('#other_discount_code_button').click();
	    }
	});
-->
</script>
<script>
<!--
//add javascriptok hidden field to checkout
jQuery("input[name=submit-checkout]").after('<input type="hidden" name="javascriptok" value="1" />');
-->
</script>
