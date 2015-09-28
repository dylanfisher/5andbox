<?php

//  ____                  _ _
// | ___|  __ _ _ __   __| | |__   _____  __
// |___ \ / _` | '_ \ / _` | '_ \ / _ \ \/ /
//  ___) | (_| | | | | (_| | |_) | (_) >  <
// |____/ \__,_|_| |_|\__,_|_.__/ \___/_/\_\


//
// Enqueue scripts
//

function sandbox_enqueue_scripts() {
  $application = sandbox_is_local() ? 'application.js' : 'application.min.js';
  wp_enqueue_script('jquery');
  wp_enqueue_script(
    'application',
    get_stylesheet_directory_uri() . '/js/build/' . $application,
    array('jquery'),
    '1.0',
    true
  );
}
add_action( 'wp_enqueue_scripts', 'sandbox_enqueue_scripts' );


//
// Enables
//

// Custom menus
add_theme_support('menus');

// Custom Image Sizes (Name, Width, Height, Hard Crop boolean)
add_image_size( 'small', 400, 300, false );


//
// Disables
//

// Disable the rich text visual editor
// add_filter('user_can_richedit' , create_function('' , 'return false;') , 50);

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
  remove_menu_page( 'edit-comments.php' );          //Comments
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

// Check if running on localhost
function sandbox_is_local() {
  $localhost_whitelist = array( '127.0.0.1', '::1' );
  if( in_array($_SERVER['REMOTE_ADDR'], $localhost_whitelist) ) {
    return true;
  } else {
    return false;
  }
}

// Get an <img> at size from an ACF image field
function sandbox_image($acf_image_field_name='image', $image_size='large', $classes='') {
  $image = get_field($acf_image_field_name); // check for a top level field
  if(empty($image)) $image = get_sub_field($acf_image_field_name); // check for a sub-field
  $alt = $image['alt'];
  if(empty($alt)) $alt = $image['title'];
  $size = $image_size;
  $url = $image['sizes'][$size];
  $url_small = $image['sizes']['small'];
  $width = $image['sizes'][$size.'-width'];
  $height = $image['sizes'][$size.'-height'];
  echo '<img src="'.$url_small.'" data-src="'.$url.'" width="'.$width.'" height="'.$height.'" alt="'.$alt.'" class="lazyload '.$classes.'">';
}

// Get <img> tags from a ACF repeater
function sandbox_images($acf_repeater='images', $acf_image_field_name='image', $image_size='large', $classes='') {
  if(have_rows($acf_repeater)):
    while (have_rows($acf_repeater)): the_row();
      sandbox_image($acf_image_field_name, $image_size, $classes);
    endwhile;
  endif;
}

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


//
// Filters
//

// Add page slug to body class
function sandbox_add_slug_body_class( $classes ) {
  global $post;
  if ( isset( $post ) ) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'sandbox_add_slug_body_class' );

function sandbox_add_slug_class_to_menu_item($output){
  $ps = get_option('permalink_structure');
  if(!empty($ps)){
    $idstr = preg_match_all('/<li id="menu-item-(\d+)/', $output, $matches);
    foreach($matches[1] as $mid){
      $id = get_post_meta($mid, '_menu_item_object_id', true);
      $slug = basename(get_permalink($id));
      $output = preg_replace('/menu-item-'.$mid.'">/', 'menu-item-'.$mid.'" data-slug="'.$slug.'">', $output, 1);
    }
  }
  return $output;
}
add_filter('wp_nav_menu', 'sandbox_add_slug_class_to_menu_item');

//
// Shortcode functions
//

// function sandbox_example_shortcode( $atts, $content = null ) {
//   $a = shortcode_atts( array(
//       'name' => 'Name of staff',
//       'title' => 'Title of staff'
//   ), $atts );

//   return '<div class="staff-member"><div class="staff-member-name">'.$a['name'].'</div><div class="staff-member-title">'.$a['title'].'</div><div class="staff-member-bio">'.$content.'</div><div class="staff-member-bio-link">View Bio</div></div>';
// }
// add_shortcode( 'example', 'sandbox_example_shortcode' );

?>
