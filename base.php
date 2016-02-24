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
      <?php elseif(is_page( 'profile' )) : ?>
        <div class="profile-page full-width-background">
      <?php endif; ?>
    <?php
      get_template_part('templates/header');
    ?>
      <?php if(!is_front_page() && !is_page( 'profile' )): ?>
        <div class="container">
      <?php endif; ?>
        <?php include Wrapper\template_path(); ?>
      <?php if(!is_front_page() && !is_page( 'profile' )): ?>
        </div>
      <?php endif; ?>
      <?php if(!is_user_logged_in()) : ?>
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>
  </body>
</html>
