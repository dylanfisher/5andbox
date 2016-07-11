<?php

function sandbox_add_slug_class_to_menu_item( $output ) {
  $ps = get_option('permalink_structure');
  if ( !empty($ps) ) {
    $idstr = preg_match_all('/<li id="menu-item-(\d+)/', $output, $matches);
    foreach ( $matches[1] as $mid ) {
      $id = get_post_meta($mid, '_menu_item_object_id', true);
      $slug = basename(get_permalink($id));
      $output = preg_replace('/menu-item-'.$mid.'">/', 'menu-item-'.$mid.' menu-item-slug-'.$slug.'" data-slug="'.$slug.'">', $output, 1);
    }
  }
  return $output;
}
add_filter('wp_nav_menu', 'sandbox_add_slug_class_to_menu_item');
