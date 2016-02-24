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
					<?php
						$logo_option = get_theme_mod('da_logo_option', 'title');
						$to_display = '';
						if( 'title' === $logo_option ) :
							$to_display = get_theme_mod('da_logo_title', get_bloginfo( 'name' ));
						else :
							$logo_src = get_theme_mod('da_logo_image', '');
						$to_display = '<img class="img-responsive logo" src="' . $logo_src . '">';
						endif;
					?>
					<a class="navbar-brand" href="<?php echo esc_url(home_url()); ?>"><?php echo $to_display; ?></a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<?php
					if (has_nav_menu('primary_navigation')) :
						wp_nav_menu(
							array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav navbar-right', 'container' => '', 'walker' => new BootstrapNavMenuWalker)
						);
					endif;
					?>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
	</div>
</header>
