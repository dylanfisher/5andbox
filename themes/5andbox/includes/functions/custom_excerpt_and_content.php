<?php

// Custom excerpt size
function sandbox_custom_excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}

// Limit content
function sandbox_content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}
