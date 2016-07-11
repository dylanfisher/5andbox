<?php

// CSS and JS script enqueues
require_once('functions/enqueue_scripts.php');

// Register custom image sizes
require_once('functions/custom_image_sizes.php');

// Security
foreach (glob(get_stylesheet_directory() . '/functions/wordpress_security/*.php') as $filename) {
  require_once $filename;
}

// Enable wordpress behaviors, like adding theme support for menus
foreach (glob(get_stylesheet_directory() . '/functions/wordpress_enables/*.php') as $filename) {
  require_once $filename;
}

// Disable unwanted default wordpress behaviors
foreach (glob(get_stylesheet_directory() . '/functions/wordpress_disables/*.php') as $filename) {
  require_once $filename;
}

// Include all functions
foreach (glob(get_stylesheet_directory() . '/functions/functions/*.php') as $filename) {
  require_once $filename;
}

// Include all filters
foreach (glob(get_stylesheet_directory() . '/functions/filters/*.php') as $filename) {
  require_once $filename;
}

// Include all shortcodes
foreach (glob(get_stylesheet_directory() . '/functions/shortcodes/*.php') as $filename) {
  require_once $filename;
}
