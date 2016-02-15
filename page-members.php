<?php if ( is_user_logged_in() ) :
	while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/page', 'header'); ?>
		<?php get_template_part('templates/content', 'page'); ?>
		<?php
		global $wpdb;
		$payed_users = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT	user_id
				FROM	$wpdb->pmpro_memberships_users
				WHERE	status=%s",
				'active'
			)
		);
		$country_field = 'pmpro_bcountry';
		$countries=$wpdb->get_col(
			$wpdb->prepare(
				"SELECT	meta_value
				FROM	$wpdb->usermeta
				WHERE	meta_key=%s
				AND		user_id IN (".implode(',',$payed_users).")",
				$country_field
			)
		);
		$occupation_field = 'occupation_';
		$meta_query = array(
			array(
				'key' => 'role',
				'value' => 'student',
			),
		);
		$country = get_query_var( 'country', '' );
		if('' !== $country) {
			$meta_query[] = array(
				'key' => $country_field,
				'value' => $country,
			);
		}
		$occupation = get_query_var( 'occupation', '' );
		if('' !== $occupation) {
			$meta_query[] = array(
				'key' => $occupation_field . $occupation,
				'value' => '1',
			);
		}
		$ordering = get_query_var( 'order', '' );
		$order = 'ASC';
		if('DESC' === $ordering) {
			$order = 'DESC';
		}
	 ?>
	<select class="selectpicker" onchange="location = this.options[this.selectedIndex].value;">
		<option style="display: none" value="" selected disabled>Sort</option>
		<optgroup label="Order">
			<option <?php if('ASC' === $ordering ) : ?>selected<?php endif; ?> value="<?php echo get_the_permalink() .'?order=asc'; ?>">Newest</option>
			<option <?php if('DESC' === $ordering ) : ?>selected<?php endif; ?> value="<?php echo get_the_permalink() .'?order=desc'; ?>">Oldest</option>
		</optgroup>
		<optgroup label="Occupation">
			<option <?php if('designer' === $occupation ) : ?>selected disabled<?php endif; ?> value="<?php echo get_the_permalink() .'?occupation=designer'; ?>">Designer</option>
			<option <?php if('engineer' === $occupation ) : ?>selected disabled<?php endif; ?> value="<?php echo get_the_permalink() .'?occupation=engineer'; ?>">Engineer</option>
			<option <?php if('entrepreneur' === $occupation ) : ?>selected disabled<?php endif; ?> value="<?php echo get_the_permalink() .'?occupation=entrepreneur'; ?>">Entrepreneur</option>
		</optgroup>
		<optgroup label="Country">
			<?php foreach ($countries as $country_name) {
				$attr = '';
				if($country === $country_name) {
					$attr = 'selected disabled';
				}
				echo '<option ' . $attr . ' value="'. get_the_permalink() .'?country=' . $country_name . '">' . $country_name . '</option>';
			} ?>
		</optgroup>
	</select>
		<?php
			$user_query = array(
				'role' => 'subscriber',
				'meta_query' => $meta_query,
				'order' => $order,
				'fields' => array(
					'ID',
				),
			);
		?>
		<?php $users = get_users($user_query); ?>
		<?php foreach ($users as $key => $user) {
			if(in_array($user->ID, $payed_users)) {
				?>
				<div class="media">
					<div class="media-left">
						<?php echo get_avatar( $id_or_email = $user->ID, $size, $default, $alt, $args = array( 'class' => 'img-circle' )); ?>
					</div>
					<div class="media-body">
						<h4 class="media-heading"><a href="<?php echo home_url( 'profile/?userid='.$user->ID, 'relative' ); ?> "><?php echo get_user_meta( $user_id = $user->ID, $key = 'first_name', $single = true ) . ' ' . get_user_meta( $user_id = $user->ID, $key = 'last_name', $single = true ); ?></a></h4>
						<p><i><?php echo get_user_meta($user->ID, 'pmpro_bcity', true); ?>, <?php echo get_user_meta($user->ID, 'pmpro_bcountry', true); ?></i><?php echo ('1' === get_user_meta( $user_id = $user->ID, $key = $occupation_field . 'engineer', $single = true ) ? '<span class="label label-success">Engineer</span>' : ''); ?><?php echo ('1' === get_user_meta( $user_id = $user->ID, $key = $occupation_field . 'designer', $single = true ) ? '<span class="label label-danger">Designer</span>' : ''); ?><?php echo ('1' === get_user_meta( $user_id = $user->ID, $key = $occupation_field . 'entrepreneur', $single = true ) ? '<span class="label label-primary">Entrepreneur</span>' : ''); ?></p>
						<p><?php echo get_user_meta( $user_id = $user->ID, $key = 'description', $single = true ); ?></p>
					</div>
				</div>
				<?php echo '<hr>';
			}
		} ?>
	<?php endwhile;
	else : wp_safe_redirect( home_url('/register') );
	endif;
?>