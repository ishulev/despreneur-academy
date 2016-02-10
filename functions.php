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
		$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
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

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'occupation_designer', $_POST['occupation_designer'] );
	update_usermeta( $user_id, 'occupation_engineer', $_POST['occupation_engineer'] );
	update_usermeta( $user_id, 'occupation_entrepreneur', $_POST['occupation_entrepreneur'] );
}
