<?php

// CSS and JS script enqueues
require_once('includes/enqueue_scripts.php');

// Register custom image sizes
require_once('includes/custom_image_sizes.php');

// Security
foreach (glob(get_stylesheet_directory() . "/includes/wordpress_security/*.php") as $filename) {
  require_once $filename;
}

// Enable wordpress behaviors, like adding theme support for menus
foreach (glob(get_stylesheet_directory() . "/includes/wordpress_enables/*.php") as $filename) {
  require_once $filename;
}

// Disable unwanted default wordpress behaviors
foreach (glob(get_stylesheet_directory() . "/includes/wordpress_disables/*.php") as $filename) {
  require_once $filename;
}

// Include all functions
foreach (glob(get_stylesheet_directory() . "/includes/functions/*.php") as $filename) {
  require_once $filename;
}

// Include all filters
foreach (glob(get_stylesheet_directory() . "/includes/filters/*.php") as $filename) {
  require_once $filename;
}

// Include all shortcodes
foreach (glob(get_stylesheet_directory() . "/includes/shortcodes/*.php") as $filename) {
  require_once $filename;
}
