<?php

// Check if running on localhost
function sandbox_is_local() {
  $localhost_whitelist = array( '127.0.0.1', '::1' );

  return in_array( $_SERVER['REMOTE_ADDR'], $localhost_whitelist );
}
