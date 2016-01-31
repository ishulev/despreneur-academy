<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header'); ?>
      <?php if(is_front_page()): ?>
        <div class="front-page full-width-background">
      <?php endif; ?>
    <?php
      get_template_part('templates/header');
    ?>
      <?php if(!is_front_page()): ?>
        <div class="container">
      <?php endif; ?>
        <?php include Wrapper\template_path(); ?>
      <?php if(!is_front_page()): ?>
        </div>
      <?php endif; ?>
    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>
  </body>
</html>
