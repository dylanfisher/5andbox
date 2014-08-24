<?php

//  ____                  _ _
// | ___|  __ _ _ __   __| | |__   _____  __
// |___ \ / _` | '_ \ / _` | '_ \ / _ \ \/ /
//  ___) | (_| | | | | (_| | |_) | (_) >  <
// |____/ \__,_|_| |_|\__,_|_.__/ \___/_/\_\


//
// Enables
//

// Custom menus
add_theme_support( 'menus' );

// Enable support for Advanced Custom Fields JSON data in JSON-API plugin
add_filter('json_api_encode', 'sandbox_json_api_encode_acf');
function sandbox_json_api_encode_acf($response) {
  if (isset($response['posts'])) {
    foreach ($response['posts'] as $post) {
      sandbox_json_api_add_acf($post); // Add specs to each post
    }
  }
  else if (isset($response['post'])) {
    sandbox_json_api_add_acf($response['post']); // Add a specs property
  }

  return $response;
}

function sandbox_json_api_add_acf(&$post) {
  $post->acf = get_fields($post->id);
}

// Open external links in new windows by default
function sandbox_autoblank($text) {
  $return = str_replace('href=', 'target="_blank" href=', $text);
  $return = str_replace('target="_blank" href="echo home_url()', 'echo home_url()', $return);
  $return = str_replace('target="_blank" href="#', 'href="#', $return);
  $return = str_replace(' target = "_blank">', '>', $return);
  return $return;
}
add_filter('the_content', 'sandbox_autoblank');
add_filter('comment_text', 'sandbox_autoblank');

// Custom Image Sizes
// add_image_size( 'custom-image-size-name', 300, 300, true ); // Custom Image - Name, Width, Height, Hard Crop boolean

// Check for custom Single Post templates by category ID. Format for new template names is single-category[ID#].php (ommiting the brackets)
// add_filter('single_template', create_function('$t', 'foreach( (array) get_the_category() as $cat ) { if ( file_exists(TEMPLATEPATH . "/single-{$cat->term_id}.php") ) return TEMPLATEPATH . "/single-{$cat->term_id}.php"; } return $t;' ));


//
// Disables
//

// Disable Admin Bar
add_filter('show_admin_bar', '__return_false');

// Disable Wordpress Generator meta tag
function sandbox_version_info() {
   return '';
}
add_filter('the_generator', 'sandbox_version_info');

// Remove unnecessary wp_head items
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

// Remove meta boxes from dashboard
function sandbox_remove_dashboard_widgets(){
  global $wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);}
add_action('wp_dashboard_setup', 'sandbox_remove_dashboard_widgets' );

// Remove unneccesary admin menu panels. Uncomment to disable
function sandbox_remove_menus(){
  // remove_menu_page( 'index.php' );                  //Dashboard
  // remove_menu_page( 'edit.php' );                   //Posts
  // remove_menu_page( 'upload.php' );                 //Media
  // remove_menu_page( 'edit.php?post_type=page' );    //Pages
  // remove_menu_page( 'edit-comments.php' );          //Comments
  // remove_menu_page( 'themes.php' );                 //Appearance
  // remove_menu_page( 'plugins.php' );                //Plugins
  // remove_menu_page( 'users.php' );                  //Users
  // remove_menu_page( 'tools.php' );                  //Tools
  // remove_menu_page( 'options-general.php' );        //Settings
}
add_action( 'admin_menu', 'sandbox_remove_menus' );


//
// Custom functions
//

// Function to create slug out of text
function sandbox_slugify( $text ) {
  $str = strtolower( trim( $text ) );
  $str = preg_replace( '/[^a-z0-9-]/', '-', $str );
  $str = preg_replace( '/-+/', "-", $str );
  return trim( $str, '-' );
}

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

?>
