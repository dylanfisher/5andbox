<?php

//
// Disable Gutenberg
//

add_action( 'wp_enqueue_scripts', 'sandbox_remove_block_css', 100 );
function sandbox_remove_block_css() {
  wp_dequeue_style( 'wp-block-library' );
}
