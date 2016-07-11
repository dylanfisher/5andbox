<?php

// Alters wordpress template heirarchy by adding a parent layout which all other template files
// inherit from. This is similar to the concept of a layout in Ruby on Rails.
// http://scribu.net/wordpress/theme-wrappers.html

function sandbox_template_path() {
  return Sandbox_Layout::$main_template;
}

function sandbox_template_base() {
  return Sandbox_Layout::$base;
}

class Sandbox_Layout {
  // Stores the full path to the main template file
  static $main_template;

  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  static $base;

  static function layout( $template ) {
    self::$main_template = $template;
    self::$base = substr( basename( self::$main_template ), 0, -4 );
    if ( 'index' == self::$base )
      self::$base = false;
    $templates = array( 'layout.php' );
    if ( self::$base )
      array_unshift( $templates, sprintf( 'layout-%s.php', self::$base ) );
    return locate_template( $templates );
  }
}

add_filter( 'template_include', array( 'Sandbox_Layout', 'layout' ), 99 );
