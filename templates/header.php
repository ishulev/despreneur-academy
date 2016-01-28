<header class="banner">
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<?php
					if (has_nav_menu('primary_navigation')) :
						wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav navbar-right', 'container' => '', 'walker' => new BootstrapNavMenuWalker]);
					endif;
					?>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
	</div>
</header>
