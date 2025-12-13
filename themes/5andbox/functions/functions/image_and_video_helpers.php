<?php

function sandbox_media_item_is_image($media_item, $options = []) {
  if ( empty($media_item) ) return false;

  $is_image = (($media_item['type'] ?? null) === 'image')
           || (($options['is_image'] ?? false) === true);

  return $is_image;
}

function sandbox_media_item($media_item, $options = []) {
  $skip_portrait = $options['skip_portrait'] ?? [];

  $portrait_on = !empty($media_item['alternate_portrait_media_item']) && !$skip_portrait;
  $image_options = $options['image_options'] ?? [];

  // Base video options
  $video_options_base = $options['video_options'] ?? [];

  // Clone per-orientation so we can attach different posters
  $video_options_landscape = $video_options_base;
  $video_options_portrait = $video_options_base;

  // If present, attach posters to the corresponding video options
  if (!empty($media_item['poster_image'])) {
    $video_options_landscape['poster_image'] = $media_item['poster_image'];
  }
  if (!empty($media_item['portrait_poster_image'])) {
    $video_options_portrait['poster_image'] = $media_item['portrait_poster_image'];
  }

  if (sandbox_media_item_is_image($media_item, $options)) {
    if ($portrait_on && !empty($media_item['portrait_image'])) {
      echo '<div class="d-portrait">';
        sandbox_image_tag($media_item['portrait_image'], array_merge($options, $image_options));
      echo '</div>';
    }
    if (!empty($media_item['image'])) {
      if ($portrait_on && !empty($media_item['portrait_image'])) echo '<div class="d-landscape">';
      sandbox_image_tag($media_item['image'], array_merge($options, $image_options));
      if ($portrait_on && !empty($media_item['portrait_image'])) echo '</div>';
    }
  } else {
    if ($portrait_on && !empty($media_item['portrait_video'])) {
      echo '<div class="d-portrait">';
        sandbox_lazy_video($media_item['portrait_video'], array_merge($options, $video_options_portrait));
      echo '</div>';
    }
    if (!empty($media_item['video'])) {
      if ($portrait_on && !empty($media_item['portrait_video'])) echo '<div class="d-landscape">';
      sandbox_lazy_video($media_item['video'], array_merge($options, $video_options_landscape));
      if ($portrait_on && !empty($media_item['portrait_video'])) echo '</div>';
    }
  }
}

function sandbox_media_item_output($media_item, $options) {
  ob_start();
  sandbox_media_item($media_item, $options);
  $html = ob_get_clean();
  return esc_attr($html);
}

// Possible image sizes
// thumbnail: 150x150
// medium: 300x300
// medium_large: 768x0
// large: 1024x1024
// 1536x1536: 1536x1536
// 2048x2048: 2048x2048

// Get an <img> at size from an ACF image field
// Note: sandbox_image_tag requires that you install a lazy load plugin such as
//       https://github.com/aFarkas/lazysizes in order to work properly.
function sandbox_image_tag($media_item, $options = array()) {
  if ( empty($media_item) ) return;

  $is_svg = $media_item['mime_type'] == 'image/svg+xml';
  $lazy = array_key_exists( 'lazy', $options ) ? $options['lazy'] : true;
  $size = array_key_exists( 'size', $options ) ? $options['size'] : '2048x2048';
  if ( $size == '2048x2048' && $media_item['width'] < 3000 ) $options['full_size'] = true;
  $size_mobile = array_key_exists( 'size_mobile', $options ) ? $options['size_mobile'] : '1536x1536';
  $full_size = array_key_exists( 'full_size', $options ) ? $options['full_size'] : false;
  $data = array_key_exists( 'data', $options ) ? $options['data'] : array();
  $alt = array_key_exists( 'alt', $options ) ? htmlspecialchars($options['alt']) : htmlspecialchars(trim($media_item['alt']));
  $skip_jump_fix = $is_svg || (array_key_exists( 'skip_jump_fix', $options ) ? $options['skip_jump_fix'] : false);
  $wrapper_class = array_key_exists( 'wrapper_class', $options ) ? $options['wrapper_class'] : false;
  $class = array_key_exists( 'class', $options ) ? $options['class'] : false;
  $style_props = array_key_exists( 'style', $options ) ? $options['style'] : '';
  $caption_override = array_key_exists( 'caption', $options ) ? $options['caption'] : '';
  $width = array_key_exists( 'width', $options ) ? $options['width'] : $media_item['width'];
  $height = array_key_exists( 'height', $options ) ? $options['height'] : $media_item['height'];

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
                   class="sandbox-image sandbox-media-item lazy-image lazyload ' . $class . '"
                   ' . join( ' ', $data_attributes ) . '>';
  } else {
    $image = '<img src="' . $data_sources['src'] . '"
                   alt="' . $alt . '"
                   style="' . $style_props . '"
                   class="sandbox-image sandbox-media-item ' . $class . '"
                   ' . join( ' ', $data_attributes ) . '>';
  }

  // Jump fix
  $ratio = $height / $width * 100;

  if ( $skip_jump_fix ) {
    echo $image;
  } else {
    echo '<div class="' . $wrapper_class . ' sandbox-image-jump-fix sandbox-image-jump-fix--' . $media_item['subtype'] . '"
               style="padding-bottom: ' . $ratio . '%;" data-aspect-ratio="' . $ratio . '">' . $image . '</div>';
  }
}

// Get an image's aspect ratio as a float
function sandbox_get_image_aspect_ratio($image) {
  if ( empty($image) ) return;

  $width = $image['width'];
  $height = $image['height'];
  $ratio = $height / $width;

  return $ratio;
}

// Get an image's orientation. Returns either "landscape" or "portrait"
function sandbox_get_image_orientation($image) {
  if ( empty($image) ) return;

  $ratio = sandbox_get_image_aspect_ratio($image);
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
  $size_mobile = array_key_exists( 'size_mobile', $options ) ? $options['size_mobile'] : '1536x1536';
  $full_size = array_key_exists( 'full_size', $options ) ? $options['full_size'] : false;
  $data = array_key_exists( 'data', $options ) ? $options['data'] : array();
  $alt = array_key_exists( 'alt', $options ) ? htmlspecialchars($options['alt']) : htmlspecialchars(trim($media_item['alt']));
  $lazy = array_key_exists( 'lazy', $options ) ? $options['lazy'] : true;
  $set_aspect_ratio = array_key_exists( 'set_aspect_ratio', $options ) ? $options['set_aspect_ratio'] : false;
  $options['lazy'] = $lazy;
  $class = array_key_exists( 'class', $options ) ? $options['class'] : '';
  $class .= ' background-image';
  if ( $lazy ) $class .= ' lazy-image lazyload';
  if ( empty($media_item) ) $class .= ' missing-image';
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

  $aspect_ratio_style = '';
  if ( $set_aspect_ratio ) {
    $height_ratio = sandbox_get_image_aspect_ratio($media_item) * 100;
    $aspect_ratio_style = 'padding-bottom: ' . $height_ratio . '%;';
  }

  return '<div class="' . $class . '" style="' . sandbox_background_image_style($media_item, $options) . '; ' . $aspect_ratio_style . '" ' . join(' ', $data_attributes) . '></div>';
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

function sandbox_lazy_video($video, $options = array()) {
  $src = array_key_exists( 'src', $options ) ? $options['src'] : (!empty($video) ? $video['url'] : false);
  $src_mobile = array_key_exists( 'src_mobile', $options ) ? $options['src_mobile'] : $src;
  $class = array_key_exists( 'class', $options ) ? $options['class'] : '';
  $wrapper_class = array_key_exists( 'wrapper_class', $options ) ? $options['wrapper_class'] : '';
  $autoplay = array_key_exists( 'autoplay', $options ) ? $options['autoplay'] : false;
  $loop = array_key_exists( 'loop', $options ) ? $options['loop'] : $autoplay;
  $muted = array_key_exists( 'muted', $options ) ? $options['muted'] : $autoplay;
  $playsinline = array_key_exists( 'playsinline', $options ) ? $options['playsinline'] : $autoplay;
  $width = array_key_exists( 'width', $options ) ? $options['width'] : $video['width'];
  $height = array_key_exists( 'height', $options ) ? $options['height'] : $video['height'];
  $ratio = array_key_exists( 'ratio', $options ) ? $options['ratio'] : "$width / $height";

  $skip_jump_fix = array_key_exists( 'skip_jump_fix', $options ) ? $options['skip_jump_fix'] : false;
  $skip_placeholder = array_key_exists('skip_placeholder', $options) ? $options['skip_placeholder'] : false;
  $skip_lazy = array_key_exists('skip_lazy', $options) ? $options['skip_lazy'] : false;

  if ( empty($ratio) ) $ratio = '9 / 16';
  $padding_pct = 0;
  if (preg_match('/^\s*(\d+(?:\.\d+)?)\s*[\/:]\s*(\d+(?:\.\d+)?)\s*$/', $ratio, $m)) {
    $rw = (float) $m[1];
    $rh = (float) $m[2];
    if ($rw > 0) $padding_pct = ($rh / $rw) * 100;
  } elseif (!empty($width) && !empty($height) && $width > 0) {
    $padding_pct = ($height / $width) * 100;
  } else {
    $padding_pct = 56.25; // sensible fallback (16:9)
  }

  $poster_image = array_key_exists( 'poster_image', $options ) ? $options['poster_image'] : false;

  $video_attrs = array();
  if ( $autoplay ) array_push($video_attrs, 'autoplay');
  if ( $loop ) array_push($video_attrs, 'loop');
  if ( $muted ) array_push($video_attrs, 'muted');
  if ( $playsinline ) array_push($video_attrs, 'playsinline');
  if ( !$autoplay ) array_push($video_attrs, 'controls');
  if ( !$autoplay ) array_push($video_attrs, 'preload="metadata"');
  if ( !$autoplay ) array_push($video_attrs, 'controlsList="nodownload"');
  if ( !empty($poster_image) ) array_push($video_attrs, 'poster="' . $poster_image['sizes']['2048x2048'] . '"');

  $classes = trim('sandbox-video sandbox-media-item ' . $class . ($skip_lazy ? '' : ' lazy-video'));
  $src_attr = $skip_lazy ? 'src="' . esc_url($src) . '"' : 'data-src="' . esc_url($src) . '"';
  $src_mobile_attr = $skip_lazy ? '' : ' data-src-mobile="' . esc_url($src_mobile) . '"';

  $video_html =
    '<video class="' . esc_attr($classes) . '" ' .
      $src_attr .
      $src_mobile_attr . ' ' .
      implode(' ', array_map('trim', $video_attrs)) . ' ' .
      'style="--aspect-ratio:' . esc_attr($ratio) . ';"' .
    '></video>';

  $escaped_video_html = htmlspecialchars($video_html, ENT_QUOTES, 'UTF-8');

  if ( $skip_placeholder ) {
    echo $video_html;
    return;
  }

  $video_placeholder_html = '<div class="lazy-video-placeholder" data-video-tag-html="' . $escaped_video_html . '" style="--aspect-ratio: ' . $ratio . ';" data-aspect-ratio="' . $ratio . '"></div>';

  echo $video_placeholder_html;
}

function sandbox_uri_image_placeholder() {
  return 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
}

// If the server is in SSL mode but tries to insert an insecure image, swap the protocol.
function https_replacement() {
  return is_ssl() ? 'https://' : 'http://';
}
