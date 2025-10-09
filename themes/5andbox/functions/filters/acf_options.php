<?php

// Only show the ACF menu on localhost
add_filter('acf/settings/show_admin', 'sandbox_acf_show_admin');

function sandbox_acf_show_admin( $show ) {
  return sandbox_is_local();
}

// Enable the ACF options page
if ( function_exists('acf_add_options_page') ) {
  add_action('acf/init', function() {
    acf_add_options_page();
  });
}
