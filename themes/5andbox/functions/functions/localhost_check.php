<?php

// Check if running on localhost
function sandbox_is_local() {
  $localhost_whitelist = array( '127.0.0.1', '::1' );
  $remote_address = $_SERVER['REMOTE_ADDR'];
  $is_local = false;

  if ( substr( $remote_address, 0, 7 ) === '192.168' ) {
    $is_local = true;
  } elseif ( in_array( $_SERVER['REMOTE_ADDR'], $localhost_whitelist ) ) {
    $is_local = true;
  }

  return $is_local;
}
