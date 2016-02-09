<?php

// Function to create slug out of text
function sandbox_slugify( $text ) {
  $str = strtolower( trim( $text ) );
  $str = preg_replace( '/[^a-z0-9-]/', '-', $str );
  $str = preg_replace( '/-+/', "-", $str );
  return trim( $str, '-' );
}
