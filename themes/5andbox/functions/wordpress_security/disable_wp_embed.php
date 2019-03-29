<?php

//
// Remove WP Embed javascript
//

add_action( 'wp_footer', 'sandbox_deregister_scripts' );
function sandbox_deregister_scripts(){
  wp_deregister_script( 'wp-embed' );
}
