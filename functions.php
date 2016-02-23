<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = array(
	'lib/assets.php',    // Scripts and stylesheets
	'lib/extras.php',    // Custom functions
	'lib/setup.php',     // Theme setup
	'lib/titles.php',    // Page titles
	'lib/wrapper.php',   // Theme wrapper class
	'lib/customizer.php', // Theme customizer
	);

	foreach ($sage_includes as $file) {
		if (!$filepath = locate_template($file)) {
			trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
		}

		require_once $filepath;
	}
	unset($file, $filepath);

/**
 * Extended Walker class for use with the
 * Twitter Bootstrap toolkit Dropdown menus in Wordpress.
 * Edited to support n-levels submenu.
 * @author johnmegahan https://gist.github.com/1597994, Emanuele 'Tex' Tessore https://gist.github.com/3765640
 * @license CC BY 4.0 https://creativecommons.org/licenses/by/4.0/
 */
class BootstrapNavMenuWalker extends Walker_Nav_Menu {


	function start_lvl( &$output, $depth ) {

		$indent = str_repeat( "\t", $depth );
		$submenu = ($depth > 0) ? ' sub-menu' : '';
		$output    .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";

	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {


		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$li_attributes = '';
		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

    // managing divider: add divider class to an element to get a divider before it.
		$divider_class_position = array_search('divider', $classes);
		if($divider_class_position !== false){
			$output .= "<li class=\"divider\"></li>\n";
			unset($classes[$divider_class_position]);
		}

		$classes[] = ($args->has_children) ? 'dropdown' : '';
		$classes[] = ($item->current || $item->current_item_ancestor || 'Courses' === $item->title && is_post_type_archive( 'course' ) || 'Courses' === $item->title && is_singular( 'course' ) ) ? 'active' : '';
		$classes[] = 'menu-item-' . $item->ID;
		if($depth && $args->has_children){
			$classes[] = 'dropdown-submenu';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ($args->has_children)      ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= ($depth == 0 && $args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
		$item_output .= $args->after;


		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}


	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
    //v($element);
		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];

    //display this element
		if ( is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		else if ( is_object( $args[0] ) )
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'start_el'), $cb_args);

		$id = $element->$id_field;

    // descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach( $children_elements[ $id ] as $child ){

				if ( !isset($newlevel) ) {
					$newlevel = true;
          //start the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset($newlevel) && $newlevel ){
      //end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}

    //end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);

	}

}

function courses_latest() {
	register_sidebar( array(
		'name'          => 'Latest courses',
		'id'            => 'courses-latest',
		'before_widget' => '<div>' . do_shortcode( '[course_instructor_avatar]' ),
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
		) );

}
add_action( 'widgets_init', 'courses_latest' );

function da_add_members_query_var( $vars ){
	$vars[] = "country";
	$vars[] = "occupation";
	$vars[] = "order";
	return $vars;
}
add_filter( 'query_vars', 'da_add_members_query_var' );

function da_add_profile_query_var( $vars ){
	$vars[] = "userid";
	return $vars;
}
add_filter( 'query_vars', 'da_add_profile_query_var' );

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>
<table class="form-table">
	<tbody>
		<tr class="user-capabilities-wrap">
			<th scope="row">Occupation tags</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span>Occupation</span></legend>
					<label for="occupation-designer">
						<input name="occupation_designer" type="checkbox" id="occupation-designer" value="1" <?php echo ( '1' === get_user_meta( $user_id = $user->ID, $key = 'occupation_designer', $single = true ) ? 'checked' : ''); ?>/>
						<span><?php esc_attr_e( 'Designer', '' ); ?></span>
					</label>
					<label for="occupation-engineer">
						<input name="occupation_engineer" type="checkbox" id="occupation-engineer" value="1" <?php echo ( '1' === get_user_meta( $user_id = $user->ID, $key = 'occupation_engineer', $single = true ) ? 'checked' : ''); ?>/>
						<span><?php esc_attr_e( 'Engineer', '' ); ?></span>
					</label>
					<label for="occupation-entrepreneur">
						<input name="occupation_entrepreneur" type="checkbox" id="occupation-entrepreneur" value="1" <?php echo ( '1' === get_user_meta( $user_id = $user->ID, $key = 'occupation_entrepreneur', $single = true ) ? 'checked' : ''); ?>/>
						<span><?php esc_attr_e( 'Entrepreneur', '' ); ?></span>
					</label>
				</fieldset>
			</td>
		</tr>
	</tbody>
</table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_usermeta( $user_id, 'occupation_designer', $_POST['occupation_designer'] );
	update_usermeta( $user_id, 'occupation_engineer', $_POST['occupation_engineer'] );
	update_usermeta( $user_id, 'occupation_entrepreneur', $_POST['occupation_entrepreneur'] );
}

add_action( 'fu_after_upload', 'my_fu_after_upload', 10, 3 );

function my_fu_after_upload( $attachment_ids, $success, $post_id ) {
	if($success)
		update_usermeta( get_current_user_id(), 'profile_background', $attachment_ids[0] );
}

function da_custom_styles() {
	wp_enqueue_style( $handle = 'font-montserrat', $src = '//fonts.googleapis.com/css?family=Montserrat:400,700', $deps, $ver, $media );
	wp_enqueue_style( $handle = 'font-s-sans-pro', $src = '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,700', $deps, $ver, $media );
	wp_enqueue_style( $handle = 'font-s-serif-pro', $src = '//fonts.googleapis.com/css?family=Source+Serif+Pro:400,600', $deps, $ver, $media );
	wp_enqueue_style( $handle = 'fontawesome', $src = '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', $deps, $ver, $media );
	if(is_page( 'profile' )) {
		
		global $wpdb;
		$user_id = get_current_user_id();
		$user_id_var = get_query_var( 'userid', '' );

		if('' !== $user_id_var && is_numeric($user_id_var)){
			$user = get_userdata( $user_id_var );
			if(false !== $user) {
				$payment_status = $wpdb->get_var( $wpdb->prepare( 
					"SELECT status 
					FROM $wpdb->pmpro_memberships_users 
					WHERE user_id = %s", 
					$user_id_var
					)
				);
				if('active' == $payment_status) {
					$user_id = $user_id_var;
				}
			}
		}
		$background_id = get_user_meta( $user_id = $user_id, $key = 'profile_background', $single = true );
		if('' !== $background_id) {
			$custom_css = ".profile-page { background-image: url('" . wp_get_attachment_url($background_id) . "'); }";
			wp_add_inline_style( 'sage/css', $custom_css );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'da_custom_styles', 101 );

function da_avatar_filter() {
  // Remove from show_user_profile hook
	remove_action('show_user_profile', array('wp_user_avatar', 'wpua_action_show_user_profile'));
	remove_action('show_user_profile', array('wp_user_avatar', 'wpua_media_upload_scripts'));

  // Remove from edit_user_profile hook
	remove_action('edit_user_profile', array('wp_user_avatar', 'wpua_action_show_user_profile'));
	remove_action('edit_user_profile', array('wp_user_avatar', 'wpua_media_upload_scripts'));
	remove_action('wpua_before_avatar', 'wpua_do_before_avatar');
	remove_action('wpua_after_avatar', 'wpua_do_after_avatar');

  // Add to edit_user_avatar hook
	add_action('edit_user_avatar', array('wp_user_avatar', 'wpua_action_show_user_profile'));
	add_action('edit_user_avatar', array('wp_user_avatar', 'wpua_media_upload_scripts'));
}

// Loads only outside of administration panel
if(!is_admin()) {
	add_action('init','da_avatar_filter');
}

add_action( 'init', 'add_ppro_heading' );

function add_ppro_heading() {
	if( is_page( 'settings' ) ) {
		include(PMPRO_DIR . "/preheaders/levels.php");
	}
}

function da_add_logout_link($sorted_menu_items) {
	if(is_user_logged_in()) {
		$new_menu_items = $sorted_menu_items;
		foreach ($sorted_menu_items as $menu_item) {
			if('Settings' === $menu_item->title) {
				$link = array (
					'title'				=> 'Logout',
					'menu_item_parent'	=> $menu_item->menu_item_parent,
					'url'				=> wp_logout_url(home_url()),
					);
				$new_menu_items[] = (object) $link;
			}
		}
		return $new_menu_items;
	} else {
		return $sorted_menu_items;
	}
}

add_filter( 'wp_nav_menu_objects', 'da_add_logout_link');

class Stats_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'stats_widget', // Base ID
			__( 'Stats Widget', 'text_domain' ), // Name
			array( 'description' => __( 'Academy stats', 'text_domain' ),
				'title' => 'Academy stats',
			) // Args
		);
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$query_args = array(
			'post_type'				=> 'course',
			'posts_per_page'		=> -1,
			'post_status'			=> 'publish',
			'fields'				=> 'ids',
		);
		// The Query
		$courseIds = get_posts( $query_args );

		// The Loop
		$totalSeconds = 0;
		$totalMinutes = 0;
		foreach ($courseIds as $courseId) {
			$timeForCourse = (int)strtotime(Course::get_course_time_estimation($courseId));
			$totalMinutes += date("i", $timeForCourse);
		}

		global $wpdb;
		$payed_users = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT	COUNT(user_id)
				FROM	$wpdb->pmpro_memberships_users
				WHERE	status=%s",
				'active'
			)
		);
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		} ?>
		<ul>
			<li><?php echo count($courseIds); ?> Courses</li>
			<li><?php echo $totalMinutes ?> Minutes of video || CHANGE TO HOUR!</li>
			<li>25 Tutorials || STATIC!!</li>
			<li><?php echo $payed_users; ?> Members</li>
		</ul>
		<?php echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Stats_Widget

// register Stats_Widget widget
function register_stats_widget() {
	register_widget( 'Stats_Widget' );
}
add_action( 'widgets_init', 'register_stats_widget' );
