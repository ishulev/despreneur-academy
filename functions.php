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

add_action( 'after_setup_theme', 'da_child_theme_setup' );

function da_child_theme_setup() {
	remove_shortcode( 'course_join_button' );
	add_shortcode( 'course_join_button', 'da_course_join_button' );
}

function da_course_join_button( $atts ) {
	global $coursepress;
	extract( shortcode_atts( array(
		'course_id'                => in_the_loop() ? get_the_ID() : '',
		'course'                   => false,
		'course_full_text'         => __( 'Course Full', 'cp' ),
		'course_expired_text'      => __( 'Not available', 'cp' ),
		'enrollment_finished_text' => __( 'Enrollments Finished', 'cp' ),
		'enrollment_closed_text'   => __( 'Enrollments Closed', 'cp' ),
		'enroll_text'              => __( 'Enroll now', 'cp' ),
		'signup_text'              => __( 'Signup!', 'cp' ),
		'details_text'             => __( 'Details', 'cp' ),
		'prerequisite_text'        => __( 'Pre-requisite Required', 'cp' ),
		'passcode_text'            => __( 'Passcode Required', 'cp' ),
		'not_started_text'         => __( 'Not Available', 'cp' ),
		'access_text'              => __( 'Start Learning', 'cp' ),
		'continue_learning_text'   => __( 'Continue Learning', 'cp' ),
		'list_page'                => false,
		'class'                    => '',
		), $atts, 'course_join_button' ) );

	$course_id = (int) $course_id;
	$list_page = sanitize_text_field( $list_page );
	$list_page = "true" == $list_page || 1 == (int) $list_page ? true : false;
	$class     = sanitize_html_class( $class );

	global $enrollment_process_url, $signup_url;

			// Saves some overhead by not loading the post again if we don't need to.
	$course  = empty( $course ) ? new Course( $course_id ) : object_decode( $course, 'Course' );
	$student = false;

	$course->enrollment_details();

	$button        = '';
	$button_option = '';
	$button_url    = $enrollment_process_url;
	$is_form       = false;

	$buttons = apply_filters( 'coursepress_course_enrollment_button_options', array(
		'full'                => array(
			'label' => sanitize_text_field( $course_full_text ),
			'attr'  => array(
				'class' => 'apply-button apply-button-full ' . $class,
				),
			'type'  => 'label',
			),
		'expired'             => array(
			'label' => sanitize_text_field( $course_expired_text ),
			'attr'  => array(
				'class' => 'apply-button apply-button-finished ' . $class,
				),
			'type'  => 'label',
			),
		'enrollment_finished' => array(
			'label' => sanitize_text_field( $enrollment_finished_text ),
			'attr'  => array(
				'class' => 'apply-button apply-button-enrollment-finished ' . $class,
				),
			'type'  => 'label',
			),
		'enrollment_closed'   => array(
			'label' => sanitize_text_field( $enrollment_closed_text ),
			'attr'  => array(
				'class' => 'apply-button apply-button-enrollment-closed ' . $class,
				),
			'type'  => 'label',
			),
		'enroll'              => array(
			'label' => sanitize_text_field( $enroll_text ),
			'attr'  => array(
				'class'          => 'apply-button enroll ' . $class,
				'data-link-old'  => esc_url( $signup_url . '?course_id=' . $course_id ),
				'data-course-id' => $course_id,
				),
			'type'  => 'form_button',
			),
		'signup'              => array(
			'label' => sanitize_text_field( $signup_text ),
			'attr'  => array(
				'class'          => 'apply-button signup ' . $class,
				'data-link-old'  => esc_url( $signup_url . '?course_id=' . $course_id ),
				'data-course-id' => $course_id,
				),
			'type'  => 'form_button',
			),
		'details'             => array(
			'label' => sanitize_text_field( $details_text ),
			'attr'  => array(
				'class'     => 'apply-button apply-button-details ' . $class,
				'data-link' => esc_url( get_permalink( $course_id ) ),
				),
			'type'  => 'button',
			),
		'prerequisite'        => array(
			'label' => sanitize_text_field( $prerequisite_text ),
			'attr'  => array(
				'class' => 'apply-button apply-button-prerequisite ' . $class,
				),
			'type'  => 'label',
			),
		'passcode'            => array(
			'label'      => sanitize_text_field( $passcode_text ),
			'button_pre' => '<div class="passcode-box"><label>' . esc_html( $passcode_text ) . ' <input type="password" name="passcode" /></label></div>',
			'attr'       => array(
				'class' => 'apply-button apply-button-passcode ' . $class,
				),
			'type'       => 'form_submit',
			),
		'not_started'         => array(
			'label' => sanitize_text_field( $not_started_text ),
			'attr'  => array(
				'class' => 'apply-button apply-button-not-started  ' . $class,
				),
			'type'  => 'label',
			),
		'access'              => array(
			'label' => sanitize_text_field( $access_text ),
			'attr'  => array(
				'class'     => 'apply-button apply-button-enrolled apply-button-first-time ' . $class,
				'data-link' => esc_url( trailingslashit( get_permalink( $course_id ) ) . trailingslashit( CoursePress::instance()->get_units_slug() ) ),
				),
			'type'  => 'button',
			),
		'continue'            => array(
			'label' => sanitize_text_field( $continue_learning_text ),
			'attr'  => array(
				'class'     => 'apply-button apply-button-enrolled ' . $class,
				'data-link' => esc_url( trailingslashit( get_permalink( $course_id ) ) . trailingslashit( CoursePress::instance()->get_units_slug() ) ),
				),
			'type'  => 'button',
			),
		) );

if ( is_user_logged_in() ) {
	$student           = new Student( get_current_user_id() );
	$student->enrolled = $student->user_enrolled_in_course( $course_id );
}

			// Determine the button option
if ( empty( $student ) || ! $student->enrolled ) {

				// For vistors and non-enrolled students
	if ( $course->full ) {
					// COURSE FULL
		$button_option = 'full';
	} elseif ( $course->course_expired && ! $course->open_ended_course ) {
					// COURSE EXPIRED
		$button_option = 'expired';
	} elseif ( ! $course->enrollment_started && ! $course->open_ended_enrollment && ! $course->enrollment_expired ) {
					// ENROLMENTS NOT STARTED (CLOSED)
		$button_option = 'enrollment_closed';
	} elseif ( $course->enrollment_expired && ! $course->open_ended_enrollment ) {
					// ENROLMENTS FINISHED
		$button_option = 'enrollment_finished';
	} elseif ( 'prerequisite' == $course->enroll_type ) {
					// PREREQUISITE REQUIRED
		if ( ! empty( $student ) ) {
			$pre_course   = ! empty( $course->prerequisite ) ? $course->prerequisite : false;
			$enrolled_pre = false;
			if ( $student->enroll_in_course( $course->prerequisite ) ) {
				$enrolled_pre = true;
			}

			if ( $enrolled_pre && ! empty( $pre_course ) && Student_Completion::is_course_complete( get_current_user_id(), (int) $pre_course ) ) {
				$button_option = 'enroll';
			} else {
				$button_option = 'prerequisite';
			}
		} else {
			$button_option = 'prerequisite';
		}
	}

	$user_can_register = cp_user_can_register();

	if ( empty( $student ) && $user_can_register && empty( $button_option ) ) {

					// If the user is allowed to signup, let them sign up
		$button_option = 'signup';
	} elseif ( ! empty( $student ) && empty( $button_option ) ) {

					// If the user is not enrolled, then see if they can enroll
		switch ( $course->enroll_type ) {
			case 'anyone':
			case 'registered':
			$button_option = 'enroll';
			break;
			case 'passcode':
			$button_option = 'passcode';
			break;
			case 'prerequisite':
			$pre_course   = ! empty( $course->prerequisite ) ? $course->prerequisite : false;
			$enrolled_pre = false;
			if ( $student->enroll_in_course( $course->prerequisite ) ) {
								//								$pre_course = new Course_Completion( $course->prerequisite );
								//								$pre_course->init_student_status();
				$enrolled_pre = true;
			}

			if ( $enrolled_pre && ! empty( $pre_course ) && Student_Completion::is_course_complete( get_current_user_id(), (int) $pre_course ) ) {
								//							if ( !empty( $pre_course ) && $pre_course->is_course_complete() ) {
				$button_option = 'enroll';
			} else {
				$button_option = 'prerequisite';
			}

			break;
		}
	}
} else {


				// For already enrolled students.

	$progress = Student_Completion::calculate_course_completion( get_current_user_id(), $course_id, false );

	if ( $course->course_expired && ! $course->open_ended_course ) {
					// COURSE EXPIRED
		$button_option = 'expired';
	} elseif ( ! $course->course_started && ! $course->open_ended_course ) {
					// COURSE HASN'T STARTED
		$button_option = 'not_started';
	} elseif ( ! is_single() && false === strpos( $_SERVER['REQUEST_URI'], CoursePress::instance()->get_student_dashboard_slug() ) ) {
					// SHOW DETAILS | Dashboard
		$button_option = 'details';
	} else {
		if ( 0 < $progress ) {
			$button_option = 'continue';
		} else {
			$button_option = 'access';
		}
	}
}

			// Make the option extendable
$button_option = apply_filters( 'coursepress_course_enrollment_button_option', $button_option );

			// Prepare the button
if ( ( ! is_single() && ! is_page() ) || 'yes' == $list_page ) {
	$button_url = get_permalink( $course_id );
	$button     = '<button data-link="' . esc_url( $button_url ) . '" class="apply-button apply-button-details ' . esc_attr( $class ) . '">' . esc_html( $details_text ) . '</button>';
} else {
				//$button = apply_filters( 'coursepress_enroll_button_content', '', $course );
	if ( empty( $button_option ) || ( 'manually' == $course->enroll_type && ! ( 'access' == $button_option || 'continue' == $button_option ) ) ) {
		return apply_filters( 'coursepress_enroll_button', $button, $course, $student );
	}

	$button_attributes = '';
	foreach ( $buttons[ $button_option ]['attr'] as $key => $value ) {
		$button_attributes .= $key . '="' . esc_attr( $value ) . '" ';
	}
	$button_pre  = isset( $buttons[ $button_option ]['button_pre'] ) ? $buttons[ $button_option ]['button_pre'] : '';
	$button_post = isset( $buttons[ $button_option ]['button_post'] ) ? $buttons[ $button_option ]['button_post'] : '';

	switch ( $buttons[ $button_option ]['type'] ) {
		case 'label':
		$button = '<span ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</span>';
		break;
		case 'form_button':
		$button  = '<button class="btn btn-primary" ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</button>';
		$is_form = true;
		break;
		case 'form_submit':
		$button  = '<input type="submit" class="btn btn-primary" ' . $button_attributes . ' value="' . esc_attr( $buttons[ $button_option ]['label'] ) . '" />';
		$is_form = true;
		break;
		case 'button':
		$button = '<button class="btn btn-primary" ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</button>';
		break;
	}

	$button = $button_pre . $button . $button_post;
}

			// Wrap button in form if needed
if ( $is_form ) {
	$button = '<form name="enrollment-process" method="post" action="' . $button_url . '">' . $button;
	$button .= wp_nonce_field( 'enrollment_process' );
	$button .= '<input type="hidden" name="course_id" value="' . $course_id . '" />';
	$button .= '</form>';
}

			// Return button for rendering
return apply_filters( 'coursepress_enroll_button', $button, $course, $student );
}
