<?php global $current_user; ?>
<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('templates/page', 'header'); ?>
	<?php if(is_singular( 'unit' )) : ?>
		<?php if( !empty($current_user->membership_level) ) : ?>
			<?php get_template_part('templates/content', 'page'); ?>
		<?php else : ?>
			<?php global $pmpro_pages; ?>
			<div class="jumbotron">
				<h2>This area is for paying members only.</h2>
				<p>Follow <a href="<?php echo get_page_link( $post = $pmpro_pages['levels'], $leavename, $sample ); ?>">this link</a> to set up your account.</p>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<?php get_template_part('templates/content', 'page'); ?>
	<?php endif; ?>
<?php endwhile; ?>
