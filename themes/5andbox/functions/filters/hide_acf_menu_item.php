<?php

// Only show the ACF menu on localhost
add_filter('acf/settings/show_admin', 'sandbox_acf_show_admin');

function sandbox_acf_show_admin( $show ) {
  return sandbox_is_local();
}
