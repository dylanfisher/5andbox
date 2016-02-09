<?php

// CSS and JS script enqueues
require_once('includes/enqueue_scripts.php');

// Enable wordpress behaviors, like adding theme support for menus
require_once('includes/wordpress_enables.php');

// Disable unwanted default wordpress behaviors
require_once('includes/wordpress_disables.php');

// Register custom image sizes
require_once('includes/custom_image_sizes.php');

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
