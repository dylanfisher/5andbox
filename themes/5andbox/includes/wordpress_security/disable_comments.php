<?php

//
// Remove comments
//

// Removes from admin menu
function sandbox_remove_admin_menus() {
  remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'sandbox_remove_admin_menus' );

// Removes from post and pages
add_action('init', 'sandbox_remove_comment_support', 100);

function sandbox_remove_comment_support() {
  remove_post_type_support( 'post', 'comments' );
  remove_post_type_support( 'page', 'comments' );
}

// Removes from admin bar
function sandbox_admin_bar_render() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'sandbox_admin_bar_render' );
