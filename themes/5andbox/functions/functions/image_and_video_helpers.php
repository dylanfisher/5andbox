<?php

// Get an <img> at size from an ACF image field
// Note: sandbox_image_tag requires that you install a lazy load plugin such as
//       https://github.com/aFarkas/lazysizes in order to work properly.
function sandbox_image_tag($media_item, $options = array()) {
  if ( empty($media_item) ) return;

  $lazy = array_key_exists( 'lazy', $options ) ? $options['lazy'] : false;
  $size = array_key_exists( 'size', $options ) ? $options['size'] : '2048x2048';
  $size_mobile = array_key_exists( 'size_mobile', $options ) ? $options['size_mobile'] : $size;
  $full_size = array_key_exists( 'full_size', $options ) ? $options['full_size'] : false;
  $data = array_key_exists( 'data', $options ) ? $options['data'] : array();
  $alt = array_key_exists( 'alt', $options ) ? htmlspecialchars($options['alt']) : htmlspecialchars(trim($media_item['alt']));
  $skip_jump_fix = array_key_exists( 'skip_jump_fix', $options ) ? $options['skip_jump_fix'] : false;
  $wrapper_class = array_key_exists( 'wrapper_class', $options ) ? $options['wrapper_class'] : false;
  $class = array_key_exists( 'class', $options ) ? $options['class'] : false;
  $style_props = array_key_exists( 'style', $options ) ? $options['style'] : '';
  $caption_override = array_key_exists( 'caption', $options ) ? $options['caption'] : '';

  $size_url = $media_item['sizes'][$size];
  if ( $full_size ) {
    $size_url = $media_item['url'];
  }

  $data_sources = array(
    'src' => str_replace('http://', https_replacement(), $size_url),
    'src-full' => str_replace('http://', https_replacement(), $media_item['url'])
  );

  if ( $size_mobile != $size ) {
    $data_sources = array_merge( $data_sources, array( 'src-mobile' => str_replace('http://', https_replacement(), $media_item['sizes'][$size_mobile]) ) );
  }

  $data = array_merge( $data, $data_sources );

  if ( !empty( trim($caption_override) ) ) {
    $caption = htmlspecialchars( trim($caption_override) );
  } elseif ( !empty( trim($media_item['caption']) ) ) {
    $caption = htmlspecialchars( trim( apply_filters('the_content', $media_item['caption']) ) );
  }

  if ( !empty($caption) ) {
    $data = array_merge( $data, array('caption' => $caption) );
  }

  $data_attributes = array();
  foreach ( $data as $key => $value ) {
    array_push( $data_attributes, 'data-' . $key . '="' . $value . '"' );
  }

  if ( $lazy ) {
    $image = '<img src="' . sandbox_uri_image_placeholder() . '"
                   alt="' . $alt . '"
                   style="' . $style_props . '"
                   class="lazy-image lazyload ' . $class . '"
                   ' . join( ' ', $data_attributes ) . '>';
  } else {
    $image = '<img src="' . $data_sources['src'] . '"
                   alt="' . $alt . '"
                   style="' . $style_props . '"
                   class="' . $class . '"
                   ' . join( ' ', $data_attributes ) . '>';
  }

  // Jump fix
  $width = $media_item['width'];
  $height = $media_item['height'];

  $ratio = $width / $height;

  if ( $skip_jump_fix ) {
    echo $image;
  } else {
    echo '<div class="' . $wrapper_class . ' sandbox-image-jump-fix sandbox-image-jump-fix--' . $media_item['subtype'] . '"
               style="aspect-ratio: ' . $ratio . ';">' . $image . '</div>';
  }
}

// Get an images orientation. Returns either "landscape" or "portrait"
function sandbox_get_image_orientation($image) {
  if ( empty($image) ) return;

  $width = $image['width'];
  $height = $image['height'];
  $ratio = $height / $width;

  $orientation = null;

  if ( $ratio <= 1 ) {
    $orientation = 'landscape';
  } else {
    $orientation = 'portrait';
  }

  return $orientation;
}

function sandbox_get_background_image_div($media_item, $options = array()) {
  $size = array_key_exists( 'size', $options ) ? $options['size'] : '2048x2048';
  $size_mobile = array_key_exists( 'size_mobile', $options ) ? $options['size_mobile'] : $size;
  $full_size = array_key_exists( 'full_size', $options ) ? $options['full_size'] : false;
  $data = array_key_exists( 'data', $options ) ? $options['data'] : array();
  $alt = array_key_exists( 'alt', $options ) ? htmlspecialchars($options['alt']) : htmlspecialchars(trim($media_item['alt']));
  $lazy = array_key_exists( 'lazy', $options ) ? $options['lazy'] : false;
  $class = array_key_exists( 'class', $options ) ? $options['class'] : '';
  $class .= ' background-image';
  if ( $lazy ) $class .= ' lazy-image lazyload';
  $caption_override = array_key_exists( 'caption', $options ) ? $options['caption'] : '';

  $size_url = $media_item['sizes'][$size];
  if ( $full_size ) {
    $size_url = $media_item['url'];
  }

  $data_sources = array(
    'bg' => str_replace('http://', https_replacement(), $size_url),
    'src-full' => str_replace('http://', https_replacement(), $media_item['url'])
  );

  if ( $size_mobile != $size ) {
    $data_sources = array_merge( $data_sources, array( 'bg-mobile' => str_replace('http://', https_replacement(), $media_item['sizes'][$size_mobile]) ) );
  }

  $data = array_merge( $data, $data_sources );

  if ( !empty( trim($caption_override) ) ) {
    $caption = htmlspecialchars( trim($caption_override) );
  } elseif ( !empty( trim($media_item['caption']) ) ) {
    $caption = htmlspecialchars( trim( apply_filters('the_content', $media_item['caption']) ) );
  }

  if ( !empty($caption) ) {
    $data = array_merge( $data, array('caption' => $caption) );
  }

  $data_attributes = array();
  foreach ( $data as $key => $value ) {
    array_push( $data_attributes, 'data-' . $key . '="' . $value . '"' );
  }

  return '<div class="' . $class . '" style="' . sandbox_background_image_style($media_item, $options) . '" ' . join(' ', $data_attributes) . '></div>';
}

function sandbox_background_image_style($image, $options = array()) {
  if ( empty($image) ) return;

  $lazy = array_key_exists( 'lazy', $options ) ? $options['lazy'] : false;
  $size = array_key_exists( 'size', $options ) ? $options['size'] : '2048x2048';
  $full_size = array_key_exists( 'full_size', $options ) ? $options['full_size'] : false;
  $image_url = $image['sizes'][$size];
  if ( $full_size ) {
    $image_url = $image['url'];
  }
  $image_url_or_placeholder = $lazy ? sandbox_uri_image_placeholder() : $image_url;
  $image_url_or_placeholder = str_replace('http://', https_replacement(), $image_url_or_placeholder);
  $class = array_key_exists( 'class', $options ) ? $options['class'] : '';

  $style_props = '';
  $style_props .= 'background-image: url(' . $image_url_or_placeholder . ');';
  $style_props .= sandbox_get_image_poi($image);

  return $style_props;
}

function sandbox_get_image_poi($image, $options = array()) {
  if ( empty($image) ) return;

  // Position type can also be set as 'object-position' if aligning images via the object-fit property.
  $position_type = array_key_exists( 'position_type', $options ) ? $options['position_type'] : 'background-position';

  $posX = get_field('point_of_interest_x', $image['ID']);
  $posY = get_field('point_of_interest_y', $image['ID']);

  if ( empty($posX) || empty($posY) ) return;

  return $position_type . ': ' . $posX . '% ' . $posY . '%;';
}

// https://www.advancedcustomfields.com/resources/oembed/
function sandbox_get_video_oembed($oembed_iframe_string) {
  $iframe = $oembed_iframe_string;

  // Use preg_match to find iframe src.
  preg_match('/src="(.+?)"/', $iframe, $matches);
  $src = $matches[1];

  // Add extra parameters to src and replace HTML.
  $params = array(
    'hd' => 1,
    'autohide' => 1,
    'autoplay' => 1
  );
  $new_src = add_query_arg($params, $src);
  $iframe = str_replace($src, $new_src, $iframe);

  // Add extra attributes to iframe HTML.
  $attributes = 'frameborder="0"';
  $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

  // Display customized HTML.
  return $iframe;
}

function sandbox_lazy_video($options = array()) {
  $src = array_key_exists( 'src', $options ) ? $options['src'] : false;
  $src_mobile = array_key_exists( 'src_mobile', $options ) ? $options['src_mobile'] : $src;
  $class = array_key_exists( 'class', $options ) ? $options['class'] : '';
  $autoplay = array_key_exists( 'autoplay', $options ) ? $options['autoplay'] : false;
  $loop = array_key_exists( 'loop', $options ) ? $options['loop'] : $autoplay;
  $muted = array_key_exists( 'muted', $options ) ? $options['muted'] : $autoplay;
  $playsinline = array_key_exists( 'playsinline', $options ) ? $options['playsinline'] : $autoplay;
  $ratio = array_key_exists( 'ratio', $options ) ? $options['ratio'] : (9 / 16);

  $video_attrs = array();
  if ( $autoplay ) array_push($video_attrs, 'autoplay');
  if ( $loop ) array_push($video_attrs, 'loop');
  if ( $muted ) array_push($video_attrs, 'muted');
  if ( $playsinline ) array_push($video_attrs, 'playsinline');
  if ( !$autoplay ) array_push($video_attrs, 'controls');
  if ( !$autoplay ) array_push($video_attrs, 'preload="metadata"');
  if ( !$autoplay ) array_push($video_attrs, 'controlsList="nodownload"');

  $video_tag_html = htmlspecialchars('<video class="lazy-video ' . $class . '" data-src="' . $src . '" data-src-mobile="' . $src_mobile . '" ' . implode(' ', $video_attrs) .'></video>');
  $video_placeholder_html = '<div class="lazy-video-placeholder" data-video-tag-html="' . $video_tag_html . '" style="height: 0; aspect-ratio: ' . $ratio . ';"></div>';

  echo $video_placeholder_html;
}

function sandbox_uri_image_placeholder() {
  return 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
}

// If the server is in SSL mode but tries to insert an insecure image, swap the protocol.
function https_replacement() {
  return is_ssl() ? 'https://' : 'http://';
}
