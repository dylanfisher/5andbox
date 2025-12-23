<?php

/**
 * Safely read an option value while preserving explicit null/false values.
 */
function sandbox__opt($options, $key, $default = null) {
  if (!is_array($options)) return $default;
  return array_key_exists($key, $options) ? $options[$key] : $default;
}

/**
 * Swap http -> https when site is in SSL mode.
 */
function sandbox__https_url($url) {
  if (!is_string($url) || $url === '') return $url;
  return str_replace('http://', https_replacement(), $url);
}

/**
 * Convert a data array into HTML data-* attributes.
 */
function sandbox__data_attributes($data) {
  if (!is_array($data) || empty($data)) return '';

  $attrs = [];
  foreach ($data as $key => $value) {
    $attr_key = preg_replace('/[^a-zA-Z0-9\\-_]/', '', (string) $key);
    if ($attr_key === '') continue;
    $attrs[] = 'data-' . $attr_key . '="' . esc_attr((string) $value) . '"';
  }

  return implode(' ', $attrs);
}

/**
 * Build a caption string (or null) from override/media caption.
 */
function sandbox__media_caption($media_item, $caption_override = '') {
  $caption_override = is_string($caption_override) ? trim($caption_override) : '';
  if ($caption_override !== '') return htmlspecialchars($caption_override);

  $caption = is_array($media_item) ? (string) ($media_item['caption'] ?? '') : '';
  $caption = trim($caption);
  if ($caption === '') return null;

  return htmlspecialchars(trim(apply_filters('the_content', $caption)));
}

/**
 * Normalize an ACF image array into a URL for a named size (or full URL).
 */
function sandbox__image_url_for_size($image, $size, $full_size = false) {
  if (empty($image) || !is_array($image)) return null;
  if ($full_size && !empty($image['url'])) return sandbox__https_url($image['url']);

  if (!empty($image['sizes']) && is_array($image['sizes']) && !empty($image['sizes'][$size])) {
    return sandbox__https_url($image['sizes'][$size]);
  }

  return !empty($image['url']) ? sandbox__https_url($image['url']) : null;
}

function sandbox_media_item_detect_type($media_item, $options = []) {
  if ( empty($media_item) ) return null;

  if ( ($options['is_image'] ?? false) === true ) return 'image';
  if ( ($options['is_video'] ?? false) === true ) return 'video';

  $type = $media_item['type'] ?? null;
  if ( $type === 'image' || $type === 'video' ) return $type;

  $mime_type = $media_item['mime_type'] ?? null;
  if ( is_string($mime_type) ) {
    if ( strpos($mime_type, 'image/') === 0 ) return 'image';
    if ( strpos($mime_type, 'video/') === 0 ) return 'video';
  }

  if ( !empty($media_item['image']) || !empty($media_item['portrait_image']) || !empty($media_item['image_portrait']) ) return 'image';
  if ( !empty($media_item['video']) || !empty($media_item['portrait_video']) || !empty($media_item['video_portrait']) ) return 'video';

  return null;
}

/**
 * Normalize a media item into the expected "wrapper" structure.
 *
 * Accepts either a "media item" wrapper array (with `image`/`video` keys) or a
 * plain ACF image/video array (with `url`, `mime_type`, etc) and returns a
 * consistent structure for rendering.
 *
 * @param array $media_item
 * @param array $options
 * @return array
 */
function sandbox_media_item_normalize($media_item, $options = []) {
  if ( empty($media_item) ) return [];

  $normalized = $media_item;
  $detected_type = sandbox_media_item_detect_type($media_item, $options);

  $has_wrapped_fields = !empty($media_item['image']) || !empty($media_item['video']) || !empty($media_item['portrait_image']) || !empty($media_item['portrait_video']) || !empty($media_item['image_portrait']) || !empty($media_item['video_portrait']);
  if ( !$has_wrapped_fields && !empty($media_item['url']) && ($detected_type === 'image' || $detected_type === 'video') ) {
    $inner = $media_item;
    unset($inner['type']);
    $normalized = [
      'type' => $detected_type,
      $detected_type => $inner,
      '__sandbox_wrapped' => true,
    ];
  }

  if ( !empty($normalized['image_portrait']) && empty($normalized['portrait_image']) ) $normalized['portrait_image'] = $normalized['image_portrait'];
  if ( !empty($normalized['video_portrait']) && empty($normalized['portrait_video']) ) $normalized['portrait_video'] = $normalized['video_portrait'];
  if ( !empty($normalized['poster_image_portrait']) && empty($normalized['portrait_poster_image']) ) $normalized['portrait_poster_image'] = $normalized['poster_image_portrait'];

  return $normalized;
}

/**
 * Remove internal-only options before passing to underlying tag helpers.
 *
 * @param array $options
 * @return array
 */
function sandbox_media_item_pass_through_options($options) {
  unset($options['image_options']);
  unset($options['video_options']);
  unset($options['skip_portrait']);
  unset($options['is_image']);
  unset($options['is_video']);
  return $options;
}

/**
 * True when the given media item should be rendered as an image.
 *
 * @param array $media_item
 * @param array $options
 * @return bool
 */
function sandbox_media_item_is_image($media_item, $options = []) {
  return sandbox_media_item_detect_type($media_item, $options) === 'image';
}

/**
 * Render a responsive media item (image or video), optionally with portrait variant.
 *
 * Expected shape is produced by `sandbox_media_item_normalize()`.
 *
 * @param array $media_item
 * @param array $options
 * @return void
 */
function sandbox_media_item($media_item, $options = []) {
  $media_item = sandbox_media_item_normalize($media_item, $options);
  if ( empty($media_item) ) return;

  $is_wrapped_single = !empty($media_item['__sandbox_wrapped']);
  $has_explicit_image_options = array_key_exists('image_options', $options);
  $has_explicit_video_options = array_key_exists('video_options', $options);

  $skip_portrait = !empty($options['skip_portrait']);

  $portrait_on = !$skip_portrait && (
    !empty($media_item['alternate_portrait_media_item'])
    || !empty($media_item['portrait_image'])
    || !empty($media_item['portrait_video'])
    || !empty($media_item['portrait_poster_image'])
  );
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
    $image_tag_options = (!$has_explicit_image_options && $is_wrapped_single)
      ? sandbox_media_item_pass_through_options($options)
      : array_merge($options, $image_options);

    if ($portrait_on && !empty($media_item['portrait_image'])) {
      echo '<div class="d-portrait sandbox-responsive-media-container">';
        sandbox_image_tag($media_item['portrait_image'], $image_tag_options);
      echo '</div>';
    }
    if (!empty($media_item['image'])) {
      if ($portrait_on && !empty($media_item['portrait_image'])) echo '<div class="d-landscape sandbox-responsive-media-container">';
      sandbox_image_tag($media_item['image'], $image_tag_options);
      if ($portrait_on && !empty($media_item['portrait_image'])) echo '</div>';
    }
  } else {
    $video_tag_options_landscape = (!$has_explicit_video_options && $is_wrapped_single)
      ? array_merge(sandbox_media_item_pass_through_options($options), $video_options_landscape)
      : array_merge($options, $video_options_landscape);
    $video_tag_options_portrait = (!$has_explicit_video_options && $is_wrapped_single)
      ? array_merge(sandbox_media_item_pass_through_options($options), $video_options_portrait)
      : array_merge($options, $video_options_portrait);

    if ($portrait_on && !empty($media_item['portrait_video'])) {
      echo '<div class="d-portrait sandbox-responsive-media-container">';
        sandbox_lazy_video($media_item['portrait_video'], $video_tag_options_portrait);
      echo '</div>';
    }
    if (!empty($media_item['video'])) {
      if ($portrait_on && !empty($media_item['portrait_video'])) echo '<div class="d-landscape sandbox-responsive-media-container">';
      sandbox_lazy_video($media_item['video'], $video_tag_options_landscape);
      if ($portrait_on && !empty($media_item['portrait_video'])) echo '</div>';
    }
  }
}

/**
 * Return the rendered HTML for a media item as an escaped attribute string.
 *
 * Useful when placing the HTML inside a data attribute.
 *
 * @param array $media_item
 * @param array $options
 * @return string
 */
function sandbox_media_item_output($media_item, $options = array()) {
  ob_start();
  sandbox_media_item($media_item, $options);
  $html = ob_get_clean();
  return esc_attr($html);
}

// Possible image sizes in a default WP install
// thumbnail: 150x150
// medium: 300x300
// medium_large: 768x0
// large: 1024x1024
// 1536x1536: 1536x1536
// 2048x2048: 2048x2048

// Get an <img> at size from an ACF image field
// Note: sandbox_image_tag requires that you install a lazy load plugin such as
//       https://github.com/aFarkas/lazysizes in order to work properly.
/**
 * Echo an <img> tag for an ACF image array, with optional lazy-loading and jump-fix wrapper.
 *
 * @param array $media_item ACF image array.
 * @param array $options
 * @return void
 */
function sandbox_image_tag($media_item, $options = array()) {
  if ( empty($media_item) ) return;

  $is_svg = ($media_item['mime_type'] ?? '') === 'image/svg+xml';

  $lazy = sandbox__opt($options, 'lazy', true);
  $skip_lazy = sandbox__opt($options, 'skip_lazy', false);
  if ($skip_lazy) $lazy = false;

  $size = sandbox__opt($options, 'size', '2048x2048');
  if ($size === '2048x2048' && !empty($media_item['width']) && $media_item['width'] < 3000) {
    $options['full_size'] = true;
  }

  $size_mobile = sandbox__opt($options, 'size_mobile', '1536x1536');
  $full_size = sandbox__opt($options, 'full_size', false);
  $data = sandbox__opt($options, 'data', []);
  if (!is_array($data)) $data = [];

  $alt_raw = array_key_exists('alt', $options) ? (string) $options['alt'] : (string) ($media_item['alt'] ?? '');
  $alt = htmlspecialchars(trim($alt_raw));

  $skip_jump_fix = $is_svg || sandbox__opt($options, 'skip_jump_fix', false);
  $wrapper_class = (string) sandbox__opt($options, 'wrapper_class', '');
  $class = (string) sandbox__opt($options, 'class', '');
  $style_props = (string) sandbox__opt($options, 'style', '');
  $caption_override = (string) sandbox__opt($options, 'caption', '');

  $width = sandbox__opt($options, 'width', $media_item['width'] ?? null);
  $height = sandbox__opt($options, 'height', $media_item['height'] ?? null);

  $src_url = sandbox__image_url_for_size($media_item, $size, $full_size);
  $full_url = !empty($media_item['url']) ? sandbox__https_url($media_item['url']) : $src_url;
  $src_mobile_url = ($size_mobile !== $size) ? sandbox__image_url_for_size($media_item, $size_mobile, $full_size) : null;

  $data_sources = [
    'src' => $src_url,
    'src-full' => $full_url,
  ];
  if ($src_mobile_url) $data_sources['src-mobile'] = $src_mobile_url;

  if ($skip_lazy) {
    unset($data_sources['src']);
    unset($data_sources['src-mobile']);
  }

  $data = array_merge($data, array_filter($data_sources));

  $caption = sandbox__media_caption($media_item, $caption_override);
  if (!empty($caption)) $data['caption'] = $caption;

  $src_for_tag = $src_url ?: sandbox_uri_image_placeholder();
  $img_src = $lazy ? sandbox_uri_image_placeholder() : $src_for_tag;
  $img_classes = trim('sandbox-image sandbox-media-item ' . ($lazy ? 'lazy-image lazyload ' : '') . $class);

  $image = '<img src="' . $img_src . '" ' .
    'alt="' . esc_attr($alt) . '" ' .
    'style="' . esc_attr($style_props) . '" ' .
    'class="' . esc_attr($img_classes) . '" ' .
    sandbox__data_attributes($data) .
  '>';

  // Jump fix
  $ratio = (!empty($width) && !empty($height) && $width > 0) ? (($height / $width) * 100) : 0;

  if ( $skip_jump_fix ) {
    echo $image;
  } else {
    $subtype = (string) ($media_item['subtype'] ?? '');
    echo '<div class="' . esc_attr(trim($wrapper_class . ' sandbox-image-jump-fix sandbox-image-jump-fix--' . $subtype)) . '"' .
      ' style="padding-bottom: ' . esc_attr((string) $ratio) . '%;"' .
      ' data-aspect-ratio="' . esc_attr((string) $ratio) . '"' .
      '>' . $image . '</div>';
  }
}

// Get an image's aspect ratio as a float
/**
 * Get an image's aspect ratio (height / width).
 *
 * @param array $image ACF image array.
 * @return float|null
 */
function sandbox_get_image_aspect_ratio($image) {
  if ( empty($image) ) return;

  $width = $image['width'] ?? null;
  $height = $image['height'] ?? null;
  if (empty($width) || empty($height) || $width <= 0) return null;

  return $height / $width;
}

// Get an image's orientation. Returns either "landscape" or "portrait"
/**
 * Get an image's orientation based on aspect ratio.
 *
 * @param array $image ACF image array.
 * @return string|null "landscape" or "portrait"
 */
function sandbox_get_image_orientation($image) {
  if ( empty($image) ) return;

  $ratio = sandbox_get_image_aspect_ratio($image);
  if ($ratio === null) return null;
  $orientation = null;

  if ( $ratio <= 1 ) {
    $orientation = 'landscape';
  } else {
    $orientation = 'portrait';
  }

  return $orientation;
}

/**
 * Return a background-image <div> string with data attributes for lazy-loading.
 *
 * @param array $media_item ACF image array.
 * @param array $options
 * @return string
 */
function sandbox_get_background_image_div($media_item, $options = array()) {
  $size = sandbox__opt($options, 'size', '2048x2048');
  $size_mobile = sandbox__opt($options, 'size_mobile', '1536x1536');
  $full_size = sandbox__opt($options, 'full_size', false);
  $data = sandbox__opt($options, 'data', []);
  if (!is_array($data)) $data = [];
  $lazy = sandbox__opt($options, 'lazy', true);
  $set_aspect_ratio = sandbox__opt($options, 'set_aspect_ratio', false);

  $class = (string) sandbox__opt($options, 'class', '');
  $class = trim($class . ' background-image' . ($lazy ? ' lazy-image lazyload' : ''));

  $caption_override = (string) sandbox__opt($options, 'caption', '');
  $caption = sandbox__media_caption($media_item, $caption_override);
  if (!empty($caption)) $data['caption'] = $caption;

  if (empty($media_item)) {
    $class .= ' missing-image';
    return '<div class="' . esc_attr($class) . '" ' . sandbox__data_attributes($data) . '></div>';
  }

  $bg_url = sandbox__image_url_for_size($media_item, $size, $full_size);
  $full_url = !empty($media_item['url']) ? sandbox__https_url($media_item['url']) : $bg_url;
  $bg_mobile_url = ($size_mobile !== $size) ? sandbox__image_url_for_size($media_item, $size_mobile, $full_size) : null;

  if ($bg_url) $data['bg'] = $bg_url;
  if ($full_url) $data['src-full'] = $full_url;
  if ($bg_mobile_url) $data['bg-mobile'] = $bg_mobile_url;

  $aspect_ratio_style = '';
  if ($set_aspect_ratio) {
    $height_ratio = sandbox_get_image_aspect_ratio($media_item);
    if ($height_ratio !== null) {
      $aspect_ratio_style = 'padding-bottom: ' . ($height_ratio * 100) . '%;';
    }
  }

  $style = trim((string) sandbox_background_image_style($media_item, array_merge($options, ['lazy' => $lazy])));
  $style = trim($style . '; ' . $aspect_ratio_style);

  return '<div class="' . esc_attr($class) . '" style="' . esc_attr($style) . '" ' . sandbox__data_attributes($data) . '></div>';
}

/**
 * Return inline CSS for setting a background-image (plus point-of-interest if set).
 *
 * @param array $image ACF image array.
 * @param array $options
 * @return string|null
 */
function sandbox_background_image_style($image, $options = array()) {
  if ( empty($image) ) return;

  $lazy = sandbox__opt($options, 'lazy', false);
  $skip_lazy = sandbox__opt($options, 'skip_lazy', false);
  if ($skip_lazy) $lazy = false;

  $size = sandbox__opt($options, 'size', '2048x2048');
  $full_size = sandbox__opt($options, 'full_size', false);
  $image_url = sandbox__image_url_for_size($image, $size, $full_size);
  if (empty($image_url)) return;

  $image_url_or_placeholder = $lazy ? sandbox_uri_image_placeholder() : $image_url;
  $image_url_or_placeholder = sandbox__https_url($image_url_or_placeholder);

  $style_props = 'background-image: url(' . $image_url_or_placeholder . ');';
  $style_props .= sandbox_get_image_poi($image);

  return $style_props;
}

function sandbox_get_image_poi($image, $options = array()) {
  if ( empty($image) ) return;
  if (empty($image['ID'])) return;

  // Position type can also be set as 'object-position' if aligning images via the object-fit property.
  $position_type = array_key_exists( 'position_type', $options ) ? $options['position_type'] : 'background-position';

  $posX = get_field('point_of_interest_x', $image['ID']);
  $posY = get_field('point_of_interest_y', $image['ID']);

  if ($posX === false || $posY === false) return;
  if ($posX === null || $posY === null) return;
  if ($posX === '' || $posY === '') return;

  return $position_type . ': ' . $posX . '% ' . $posY . '%;';
}

// https://www.advancedcustomfields.com/resources/oembed/
/**
 * Add common query params/attrs to an ACF oEmbed iframe string.
 *
 * @param string $oembed_iframe_string
 * @return string
 */
function sandbox_get_video_oembed($oembed_iframe_string) {
  $iframe = $oembed_iframe_string;

  // Use preg_match to find iframe src.
  preg_match('/src="(.+?)"/', $iframe, $matches);
  $src = $matches[1] ?? null;
  if (empty($src)) return $iframe;

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

/**
 * Echo a lazy-loadable <video> placeholder (or the video tag directly).
 *
 * @param array $video ACF file/attachment array (or similar).
 * @param array $options
 * @return void
 */
function sandbox_lazy_video($video, $options = array()) {
  $src = sandbox__opt($options, 'src', $video['url'] ?? false);
  $src_mobile = sandbox__opt($options, 'src_mobile', $src);
  $class = (string) sandbox__opt($options, 'class', '');
  $autoplay = sandbox__opt($options, 'autoplay', true);
  $loop = sandbox__opt($options, 'loop', $autoplay);
  $muted = sandbox__opt($options, 'muted', $autoplay);
  $playsinline = sandbox__opt($options, 'playsinline', $autoplay);
  $width = sandbox__opt($options, 'width', $video['width'] ?? null);
  $height = sandbox__opt($options, 'height', $video['height'] ?? null);
  $ratio = sandbox__opt($options, 'ratio', (!empty($width) && !empty($height)) ? "$width / $height" : null);

  $skip_placeholder = sandbox__opt($options, 'skip_placeholder', false);
  $skip_lazy = sandbox__opt($options, 'skip_lazy', false);

  if (empty($ratio)) $ratio = '9 / 16';

  $poster_image = sandbox__opt($options, 'poster_image', false);

  $video_attrs = array();
  if ( $autoplay ) array_push($video_attrs, 'autoplay');
  if ( $loop ) array_push($video_attrs, 'loop');
  if ( $muted ) array_push($video_attrs, 'muted');
  if ( $playsinline ) array_push($video_attrs, 'playsinline');
  if ( !$autoplay ) array_push($video_attrs, 'controls');
  if ( !$autoplay ) array_push($video_attrs, 'preload="metadata"');
  if ( !$autoplay ) array_push($video_attrs, 'controlsList="nodownload"');
  if ( !empty($poster_image) && !empty($poster_image['sizes']['2048x2048']) ) {
    array_push($video_attrs, 'poster="' . esc_url(sandbox__https_url($poster_image['sizes']['2048x2048'])) . '"');
  }

  $classes = trim('sandbox-video sandbox-media-item ' . $class . ($skip_lazy ? '' : ' lazy-video'));
  $src_attr = $skip_lazy ? 'src="' . esc_url(sandbox__https_url($src)) . '"' : 'data-src="' . esc_url(sandbox__https_url($src)) . '"';
  $src_mobile_attr = $skip_lazy ? '' : ' data-src-mobile="' . esc_url(sandbox__https_url($src_mobile)) . '"';

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

/**
 * 1x1 transparent GIF used as an image placeholder.
 *
 * @return string
 */
function sandbox_uri_image_placeholder() {
  return 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
}

// If the server is in SSL mode but tries to insert an insecure image, swap the protocol.
/**
 * Return the correct URL protocol prefix for the current request.
 *
 * @return string "https://" or "http://"
 */
function https_replacement() {
  return is_ssl() ? 'https://' : 'http://';
}
