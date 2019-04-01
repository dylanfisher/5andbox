<?php

// https://css-tricks.com/wordpress-fragment-caching-revisited/
//
// Usage:
//
// fragment_cache('my_footer', DAY_IN_SECONDS, function() {
//   ..expensive content
// });

function fragment_cache($key, $ttl, $function) {
  if ( sandbox_is_local() ) {
    call_user_func($function);
    return;
  }

  $key = apply_filters('fragment_cache_prefix','fragment_cache_').$key;
  $output = is_user_logged_in() ? '' : get_transient($key);

  if ( empty($output) ) {
    ob_start();
    call_user_func($function);
    $output = ob_get_clean();
    set_transient($key, $output, $ttl);
  }

  echo $output;
}
