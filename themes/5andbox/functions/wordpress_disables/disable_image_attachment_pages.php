<?php

add_action('template_redirect', function () {
  if (is_attachment()) {
    global $post;

    if ($post && $post->post_parent) {
      wp_redirect(get_permalink($post->post_parent), 301);
      exit;
    }

    // Fallback: redirect to homepage if no parent
    wp_redirect(home_url('/'), 301);
    exit;
  }
});
