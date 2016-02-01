<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('templates/page', 'header'); ?>
	<?php get_template_part('templates/content', 'page'); ?>
	<?php
	global $wpdb;
	$country_field = 'pmpro_bcountry';
	$occupations_field = 'occupationtags';
	$countries=$wpdb->get_col(
		$wpdb->prepare(
			"SELECT	meta_value
			FROM	$wpdb->usermeta
			WHERE	meta_key=%s",
			$country_field
		)
	);
	$occupationtags_dirty=$wpdb->get_col(
		$wpdb->prepare(
			"SELECT	meta_value
			FROM	$wpdb->usermeta
			WHERE	meta_key=%s",
			$occupations_field
		)
	);
	$occupationtags = array();
	foreach ($occupationtags_dirty as $tag) {
		$occupationtags = array_merge( maybe_unserialize( $tag ), $occupationtags);
		echo '<hr>';
	}
	print_r($occupationtags);
 ?>
	<div class="btn-group" role="group" aria-label="...">
		<button type="button" class="btn btn-default">1</button>
		<button type="button" class="btn btn-default">2</button>
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Country
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<?php foreach ($countries as $country) {
					echo '<li><a href="'. get_the_permalink() .'?filter=country&country=' . $country . '">' . $country . '</a></li>';
				} ?>
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
		print_r($meta_query);
		$user_query = array(
			'role' => 'subscriber',
			'meta_query' => $meta_query,
			// 'orderby' => array(
			// 	'meta_key' => 'pmpro_bcountry',
			// 	'meta_value' => get_query_var( 'country', '' ),
			// ),
			'order' => 'DESC',
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
				<h4 class="media-heading"><?php echo $user->display_name; ?></h4>
				<p><i><?php echo get_user_meta($user->id, 'pmpro_bcity', true); ?>, <?php echo get_user_meta($user->id, 'pmpro_bcountry', true); ?></i></p>
				<p><?php echo $user->description; ?></p>
			</div>
		</div>
		<?php echo '<hr>';
		echo get_query_var( 'filter', 'country' );
	} ?>
<?php endwhile; ?>
