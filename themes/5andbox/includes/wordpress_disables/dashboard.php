<?php

// Remove meta boxes from dashboard
function sandbox_remove_dashboard_widgets(){
  global $wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);}
add_action('wp_dashboard_setup', 'sandbox_remove_dashboard_widgets' );
