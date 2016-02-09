<?php

//
// Enqueue scripts
//

function sandbox_enqueue_scripts() {
  // Register jQuery from Google CDN (on production)
  if (!sandbox_is_local() && !is_admin() && $GLOBALS['pagenow'] != 'wp-login.php') {
    wp_deregister_script('jquery');
    wp_register_script('jquery',
      'https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js',
      false,
      '2.2.0',
      true);
    wp_enqueue_script('jquery');
  }

  // Application javascript
  $application_js = sandbox_is_local() ? 'application.js' : 'application.min.js';
  $javascript_version = filemtime( get_stylesheet_directory() . '/js/dist/' . $application_js);
  wp_enqueue_script(
    'application',
    get_stylesheet_directory_uri() . '/js/dist/' . $application_js,
    array('jquery'),
    $javascript_version,
    true
  );

  // CSS
  $css_version = filemtime( get_stylesheet_directory() . '/style.css');
  wp_enqueue_style(
    'sandbox-stylesheet',
    get_stylesheet_directory_uri() . '/style.css',
    array(),
    $css_version,
    'all'
  );
}

add_action( 'wp_enqueue_scripts', 'sandbox_enqueue_scripts' );
