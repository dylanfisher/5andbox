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
    true // Load in footer
  );

  // Application javascript - unminified in development, minified in production
  $js_file = sandbox_is_local() ? 'application.js' : 'application.min.js';
  $javascript_version = filemtime( get_stylesheet_directory() . '/assets/javascripts/dist/' . $js_file);
  wp_enqueue_script(
    'application',
    get_stylesheet_directory_uri() . '/assets/javascripts/dist/' . $js_file,
    array('jquery'),
    $javascript_version,
    true // Load in footer
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
