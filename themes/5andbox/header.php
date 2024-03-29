<!DOCTYPE html>
<html lang="en" data-home-url="<?php echo home_url('/'); ?>" data-ajax-url="<?php echo admin_url('admin-ajax.php'); ?>">
<head>
  <title><?php wp_title( '-', true, 'right' ); echo esc_html( get_bloginfo('name'), 1 ); ?></title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="description" content="<?php echo get_bloginfo('description'); ?>">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width">
  <?php get_template_part('partials/meta_tags'); ?>
  <?php if ( false ): ?>
    <?php // TODO: Add favicon, analytics ?>
    <link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_url'); ?>/images/favicon.png">
  <?php endif ?>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header id="header" class="header">
    <div class="site-container">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <a href="<?php bloginfo('url'); ?>/" rel="home"><?php bloginfo('name'); ?></a>
            <nav id="nav" class="nav">
              <?php wp_nav_menu(); ?>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </header>
  <main id="application-wrapper" class="application-wrapper">
