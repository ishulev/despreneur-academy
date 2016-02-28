<?php 
global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels = pmpro_getAllLevels(false, true);
$pmpro_level_order = pmpro_getOption('level_order');

if(!empty($pmpro_level_order))
{
	$order = explode(',',$pmpro_level_order);

	//reorder array
	$reordered_levels = array();
	foreach($order as $level_id) {
		foreach($pmpro_levels as $key=>$level) {
			if($level_id == $level->id)
				$reordered_levels[] = $pmpro_levels[$key];
		}
	}

	$pmpro_levels = $reordered_levels;
}

$pmpro_levels = apply_filters("pmpro_levels_array", $pmpro_levels); ?>

		</section> <!-- CLOSING VERTICAL ALIGN SECTION -->
	</div> <!-- CLOSING VERTICAL ALIGN PARENT -->
</div> <!-- CLOSING FULL WIDTH TAG -->
<div class="container">
	<?php if($pmpro_msg) { ?>
	<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
	<?php }	?>
	<div class="row">
		<?php $count = 0;
		foreach($pmpro_levels as $level)
		{ ?>
			<div class="col-md-4">
				<?php if(isset($current_user->membership_level->ID))
				$current_level = ($current_user->membership_level->ID == $level->id);
				else
					$current_level = false; ?>
				<div class="<?php if($count++ % 2 == 0) { ?>odd<?php } ?><?php if($current_level == $level) { ?> active<?php } ?>">
					<div><?php echo $current_level ? "<strong>{$level->name}</strong>" : $level->name?></div>
					<div>
						<?php 
						if(pmpro_isLevelFree($level))
							$cost_text = "<strong>" . __("Free", "pmpro") . "</strong>";
						else
							$cost_text = pmpro_getLevelCost($level, true, true); 
						$expiration_text = pmpro_getLevelExpiration($level);
						if(!empty($cost_text) && !empty($expiration_text))
							echo $cost_text . "<br />" . $expiration_text;
						elseif(!empty($cost_text))
							echo $cost_text;
						elseif(!empty($expiration_text))
							echo $expiration_text;
						?>
					</div>
					<div>
						<?php if(empty($current_user->membership_level->ID)) { ?>
						<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'pmpro');?></a>
						<?php } elseif ( !$current_level ) { ?>                	
						<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'pmpro');?></a>
						<?php } elseif($current_level) { ?>      

							<?php			
							if( pmpro_isLevelExpiringSoon( $current_user->membership_level) ) {
								?>
								<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Renew', 'pmpro');?></a>
							<?php } else { ?>
								<a class="pmpro_btn disabled" href="<?php echo pmpro_url("account")?>"><?php _e('Your&nbsp;Level', 'pmpro');?></a>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>