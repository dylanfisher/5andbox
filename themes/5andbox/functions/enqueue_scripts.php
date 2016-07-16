<?php

//
// Enqueue scripts
//

function sandbox_enqueue_scripts() {
  // PRODUCTION - Register jQuery from Google CDN
  if ( !sandbox_is_local() && !is_admin() && $GLOBALS['pagenow'] != 'wp-login.php' ) {
    wp_deregister_script('jquery');
    wp_register_script(
      'jquery',
      'https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js',
      false,
      NULL,
      true
    );
  } elseif ( sandbox_is_local() && !is_admin() && $GLOBALS['pagenow'] != 'wp-login.php' ) {
    // DEVELOPMENT - Register jQuery locally
    wp_deregister_script('jquery');
    wp_register_script(
      'jquery',
      get_stylesheet_directory_uri() . '/js/lib/jquery.min.js',
      false,
      NULL,
      true
    );
  }

  // Application javascript - unminified in development, minified in production
  $js_file = sandbox_is_local() ? 'application.js' : 'application.min.js';
  $javascript_version = filemtime( get_stylesheet_directory() . '/js/dist/' . $js_file);
  wp_enqueue_script(
    'application',
    get_stylesheet_directory_uri() . '/js/dist/' . $js_file,
    array('jquery'),
    $javascript_version,
    true
  );

  // CSS
  $css_file = sandbox_is_local() ? '/style.full.css' : '/style.css';
  $css_version = filemtime( get_stylesheet_directory() . '/style.css');
  wp_enqueue_style(
    'sandbox-stylesheet',
    get_stylesheet_directory_uri() . $css_file,
    array(),
    $css_version,
    'all'
  );
}

add_action( 'wp_enqueue_scripts', 'sandbox_enqueue_scripts' );
