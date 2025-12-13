<?php

//
// Enqueue scripts
//

function sandbox_enqueue_scripts() {
  // jQuery
  wp_deregister_script('jquery');
  wp_register_script(
    'jquery',
    get_stylesheet_directory_uri() . '/assets/javascripts/lib/jquery.min.js',
    false,
    NULL,
    array(
      'strategy' => 'defer'
    )
  );

  // Application javascript
  $javascript_version = filemtime( get_stylesheet_directory() . '/assets/dist/javascripts/src/app.js');
  wp_enqueue_script(
    'application',
    get_stylesheet_directory_uri() . '/assets/dist/javascripts/src/app.js',
    array('jquery'),
    $javascript_version,
    array(
      'strategy' => 'defer'
    )
  );

  // CSS
  $css_version = filemtime( get_stylesheet_directory() . '/assets/dist/stylesheets/style.css');
  wp_enqueue_style(
    'sandbox-stylesheet',
    get_stylesheet_directory_uri() . '/assets/dist/stylesheets/style.css',
    array(),
    $css_version,
    'all'
  );
}

add_action( 'wp_enqueue_scripts', 'sandbox_enqueue_scripts' );
