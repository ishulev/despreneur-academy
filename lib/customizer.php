<?php

namespace Roots\Sage\Customizer;

use Roots\Sage\Assets;

/**
 * Add postMessage support
 */
function customize_register($wp_customize) {
	$wp_customize->get_setting('blogname')->transport = 'postMessage';

	//CUSTOM!
	$wp_customize->add_setting( 'da_logo_option', array(
		'default'        => 'title',
	) );
	$wp_customize->add_control( 'da_logo_option', array(
		'label'      => 'Choose type of logo',
		'section'    => 'title_tagline',
		'settings'   => 'da_logo_option',
		'type'       => 'radio',
		'choices'    => array(
			'title' => 'Title',
			'image' => 'Image',
			),
		) );
	function choice_title_callback( $control ) {
		if ( $control->manager->get_setting('da_logo_option')->value() == 'title' ) {
			return true;
		} else {
			return false;
		}
	}
	function choice_image_callback( $control ) {
		if ( $control->manager->get_setting('da_logo_option')->value() == 'image' ) {
			return true;
		} else {
			return false;
		}
	}
	$wp_customize->add_setting(
		'da_logo_image',
		array(
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
		)
	);
	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'da_logo_image',
			array(
				'label'      => __( 'Choose a logo', 'da' ),
				'section'    => 'title_tagline',
				'settings'   => 'da_logo_image',
				'context'    => '',
				'active_callback'   => __NAMESPACE__ . '\\choice_image_callback',
			)
		)
	);
	$wp_customize->add_setting(
		'da_logo_title',
		array(
			'default'        => get_bloginfo( 'name' ),
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'transport'      => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'da_logo_title',
		array(
			'label'      => __( 'Choose title for logo', 'da' ),
			'section'    => 'title_tagline',
			'settings'   => 'da_logo_title',
			'context'    => '',
			'active_callback' => __NAMESPACE__ . '\\choice_title_callback'
		)
	);

	function customizer_is_pmpro_page() {
		global $pmpro_pages;
		if(is_array($pmpro_pages)) {
			foreach ($pmpro_pages as $value) {
				if(is_page( $value )) {
					return true;
				}
			}
		}
		return false;
	}

	$wp_customize->add_section(
		'da_payment_pages',
		array(
			'title'    => __('Payment pages', 'themename'),
			'description' => '',
			'priority' => 160,
			'active_callback' => __NAMESPACE__ . '\\customizer_is_pmpro_page'
		)
	);
	$wp_customize->add_setting(
		'da_payment_pages_background',
		array(
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
		)
	);
	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'da_payment_pages_background',
			array(
				'label'      => __( 'Manage background', 'theme_name' ),
				'section'    => 'da_payment_pages',
				'settings'   => 'da_payment_pages_background',
				'context'    => '' 
			)
		)
	);

	function customizer_is_course_archive_page() {
		if(is_post_type_archive( 'course' )) {
			return true;
		}
		return false;
	}
	$wp_customize->add_section(
		'da_course_archive',
		array(
			'title'    => __('Courses', 'themename'),
			'description' => '',
			'priority' => 160,
			'active_callback' => __NAMESPACE__ . '\\customizer_is_course_archive_page'
		)
	);
	$wp_customize->add_setting(
		'da_course_archive_background',
		array(
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
		)
	);
	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'da_course_archive_background',
			array(
				'label'      => __( 'Manage background', 'theme_name' ),
				'section'    => 'da_course_archive',
				'settings'   => 'da_course_archive_background',
				'context'    => '' 
			)
		)
	);
}
add_action('customize_register', __NAMESPACE__ . '\\customize_register');

/**
 * Customizer JS
 */
function customize_preview_js() {
  wp_enqueue_script('sage/customizer', Assets\asset_path('scripts/customizer.js'), array('customize-preview'), null, true);
}
add_action('customize_preview_init', __NAMESPACE__ . '\\customize_preview_js');
