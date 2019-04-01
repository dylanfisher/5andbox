<?php
// From: https://github.com/humanmade/hm-core/blob/master/hm-core.functions.php#L1236

// Like get_template_part() but lets you pass args to the template file
// Args are available in the template as $template_args array
//
// Full example usage:
//
// sandbox_get_template_part( 'partials/calendar/datepicker_active_dates', array( 'relative_date' => $relative_date ), 'page-calendar/relative_dates');

function sandbox_get_template_part( $file, $template_args = array(), $options = array() ) {
  // Disable the cache in development
  // $disable_cache = sandbox_is_local();
  $disable_cache = true;

  $cache_key     = isset( $options['cache_key'] ) ? $options['cache_key'] : false;
  $force         = isset( $options['force'] ) ? $options['force'] : false;
  $cache_timeout = isset( $options['cache_timeout'] ) ? $options['cache_timeout'] : 3600;

  // If using a persistent memory store, set to false
  $use_transient_api = true;


  if ( $force ) {
    if ( $use_transient_api ) {
      delete_transient( $cache_key );
    }
  }

  $template_args = wp_parse_args( $template_args );

  if ( !$disable_cache && $cache_key ) {
    if ( $use_transient_api ) {
      $cache_key_value = get_transient( $cache_key );
    } else {
      $cache_key_value = wp_cache_get( $file, $cache_key );
    }

    if ( ( $cache = $cache_key_value ) !== false ) {
      if ( ! empty( $template_args['return'] ) )
        return $cache;
      echo $cache;
      return;
    }
  }

  $file_handle = $file;
  do_action( 'start_operation', 'hm_template_part::' . $file_handle );

  if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) )
    $file = get_stylesheet_directory() . '/' . $file . '.php';
  elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) )
    $file = get_template_directory() . '/' . $file . '.php';

  ob_start();
  $return = require( $file );
  $data = ob_get_clean();

  do_action( 'end_operation', 'hm_template_part::' . $file_handle );

  if ( !$disable_cache ) {
    if ( $cache_key ) {
      if ( $use_transient_api ) {
        set_transient( $cache_key, $data, $cache_timeout );
      } else {
        wp_cache_set( $file, $data, $cache_key, $cache_timeout );
      }
    }
  }

  if ( ! empty( $template_args['return'] ) )
    if ( $return === false )
      return false;
    else
      return $data;

  echo $data;
}
