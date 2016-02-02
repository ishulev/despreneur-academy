<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('templates/page', 'header'); ?>
	<?php get_template_part('templates/content', 'page'); ?>
	<?php
	global $wpdb;
	$country_field = 'pmpro_bcountry';
	$countries=$wpdb->get_col(
		$wpdb->prepare(
			"SELECT	meta_value
			FROM	$wpdb->usermeta
			WHERE	meta_key=%s",
			$country_field
		)
	);
	$occupation_field = 'occupation_';
 ?>
	<div class="btn-group" role="group" aria-label="...">
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Occupation
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="<?php echo get_the_permalink() .'?occupation=designer'; ?>">Designer</a></li>
				<li><a href="<?php echo get_the_permalink() .'?occupation=engineer'; ?>">Engineer</a></li>
				<li><a href="<?php echo get_the_permalink() .'?occupation=entrepreneur'; ?>">Entrepreneur</a></li>
			</ul>
		</div>
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Country
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<?php foreach ($countries as $country) {
					echo '<li><a href="'. get_the_permalink() .'?country=' . $country . '">' . $country . '</a></li>';
				} ?>
			</ul>
		</div>
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Sort by
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="<?php echo get_the_permalink() .'?order=asc'; ?>">Newest</a></li>
				<li><a href="<?php echo get_the_permalink() .'?order=desc'; ?>">Oldest</a></li>
			</ul>
		</div>
	</div>
	<?php 
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
		$order_query = get_query_var( 'order', '' );
		$order = 'ASC';
		if('DESC' === $order_query) {
			$order = 'DESC';
		}
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
		?>
		<div class="media">
			<div class="media-left">
				<img class="media-object img-circle" src="<?php echo get_avatar_url($user->id) ?>" alt="...">
			</div>
			<div class="media-body">
				<h4 class="media-heading"><?php echo get_user_meta( $user_id = $user->ID, $key = 'first_name', $single = true ) . ' ' . get_user_meta( $user_id = $user->ID, $key = 'last_name', $single = true ); ?></h4>
				<p><i><?php echo get_user_meta($user->ID, 'pmpro_bcity', true); ?>, <?php echo get_user_meta($user->ID, 'pmpro_bcountry', true); ?></i><?php echo ('1' === get_user_meta( $user_id = $user->ID, $key = $occupation_field . 'engineer', $single = true ) ? '<span class="label label-success">Engineer</span>' : ''); ?><?php echo ('1' === get_user_meta( $user_id = $user->ID, $key = $occupation_field . 'designer', $single = true ) ? '<span class="label label-danger">Designer</span>' : ''); ?><?php echo ('1' === get_user_meta( $user_id = $user->ID, $key = $occupation_field . 'entrepreneur', $single = true ) ? '<span class="label label-primary">Entrepreneur</span>' : ''); ?></p>
				<p><?php echo get_user_meta( $user_id = $user->ID, $key = 'description', $single = true ); ?></p>
			</div>
		</div>
		<?php echo '<hr>';
	} ?>
<?php endwhile; ?>
