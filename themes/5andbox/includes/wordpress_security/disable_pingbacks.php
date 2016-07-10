<?php

//
// Wordpress security
//

// Kill all WordPress pingback/trackback functionality
// https://gist.github.com/chrisguitarguy/1309433
add_filter( 'wp_headers', 'sandbox_filter_headers', 10, 1 );
function sandbox_filter_headers( $headers ) {
  if( isset( $headers['X-Pingback'] ) ) {
    unset( $headers['X-Pingback'] );
  }
  return $headers;
}

// Kill the rewrite rule
add_filter( 'rewrite_rules_array', 'sandbox_filter_rewrites' );
function sandbox_filter_rewrites( $rules ) {
  foreach( $rules as $rule => $rewrite ) {
    if( preg_match( '/trackback\/\?\$$/i', $rule ) ) {
      unset( $rules[$rule] );
    }
  }
  return $rules;
}

// Kill bloginfo( 'pingback_url' )
add_filter( 'bloginfo_url', 'sandbox_kill_pingback_url', 10, 2 );
function sandbox_kill_pingback_url( $output, $show ) {
  if( $show == 'pingback_url' ) {
    $output = '';
  }
  return $output;
}

// remove RSD link
remove_action( 'wp_head', 'rsd_link' );

// hijack options updating for XMLRPC
add_filter( 'pre_update_option_enable_xmlrpc', '__return_false' );
add_filter( 'pre_option_enable_xmlrpc', '__return_zero' );

// Disable XMLRPC call
add_action( 'xmlrpc_call', 'sandbox_kill_xmlrpc' );
function sandbox_kill_xmlrpc( $action ) {
  if( 'pingback.ping' === $action ) {
    wp_die(
      'Pingbacks are not supported',
      'Not Allowed!',
      array( 'response' => 403 )
    );
  }
}
