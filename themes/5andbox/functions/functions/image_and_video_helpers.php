<?php

// Get an <img> at size from an ACF image field
function lazy_image($acf_image_field_name, $options = array()) {
  $media_item = get_field( $acf_image_field_name );
  if ( empty( $media_item ) ) $media_item = get_sub_field( $acf_image_field_name );

  if ( empty( $media_item ) ) {
    throw new Exception( 'lazy_image function requires a media item.' );
    return;
  }

  $size = array_key_exists( 'size', $options ) ? $options['size'] : 'large';
  $size_mobile = array_key_exists( 'size_mobile', $options ) ? $options['size_mobile'] : $size;
  $data = array_key_exists( 'data', $options ) ? $options['data'] : array();
  $alt = array_key_exists( 'alt', $options ) ? $options['alt'] : false;
  $skip_jump_fix = array_key_exists( 'skip_jump_fix', $options ) ? $options['skip_jump_fix'] : false;
  $class = array_key_exists( 'class', $options ) ? $options['class'] : false;

  if ( empty( $alt ) ) $alt = $media_item['title'];

  $data_sources = array(
    'src' => $media_item['sizes'][$size],
    'src-full' => $media_item['url']
  );

  if ( $size_mobile != $size ) {
    $data_sources = array_merge( $data_sources, array( 'src-mobile' => $media_item['sizes'][$size_mobile] ) );
  }

  $data = array_merge( $data, $data_sources );

  $data_attributes = array();
  foreach ( $data as $key => $value ) {
    array_push( $data_attributes, 'data-' . $key . '="' . $value . '"' );
  }

  $image = '<img src="' . sandbox_uri_image_placeholder() . '"
                 alt="' . $alt . '"
                 class="lazy-image lazyload ' . $class . '"
                 ' . join( $data_attributes, ' ' ) . '>';

  // Jump fix
  $width = $media_item['width'];
  $height = $media_item['height'];

  $ratio = $height / $width * 100;

  if ( $skip_jump_fix ) {
    echo $image;
  } else {
    echo '<div class="sandbox-image-jump-fix sandbox-image-jump-fix--' . $media_item['subtype'] . '"
               style="padding-bottom: ' . $ratio . '%;">' . $image . '</div>';
  }
}

function sandbox_uri_image_placeholder() {
  return 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
}
