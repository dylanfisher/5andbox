<?php
  $meta_title = get_the_title();
  $meta_description = get_bloginfo('description');
  $meta_image_url = null;

  if ( class_exists('acf') ):
    if ( !empty( get_field('sandbox_seo_description') ) ) $meta_description = get_field('sandbox_seo_description');
    if ( !empty( get_field('sandbox_seo_image') ) ):
      $sandbox_seo_image = get_field('sandbox_seo_image');
    elseif ( !empty( get_field('sandbox_seo_site_featured_image', 'option') ) ):
      $sandbox_seo_image = get_field('sandbox_seo_site_featured_image', 'option');
    endif;

    if ( !empty( $sandbox_seo_image ) ):
      $meta_image_url = $sandbox_seo_image['sizes']['large'];
      $meta_image_width = $sandbox_seo_image['sizes']['large-width'];
      $meta_image_height = $sandbox_seo_image['sizes']['large-height'];
      $meta_image_mime_type = $sandbox_seo_image['mime_type'];
      $meta_image_alt_text = $sandbox_seo_image['alt'];
    endif;
  endif;
?>

<meta property="og:url" content="<?php the_permalink(); ?>">
<meta itemprop="name" content="<?php $meta_title; ?>">

<?php if ( !empty( $meta_image_url ) ): ?>
  <meta property="og:image" content="<?php echo $meta_image_url; ?>">
  <meta itemprop="image" content="<?php echo $meta_image_url; ?>">
  <meta name="twitter:image" content="<?php echo $meta_image_url; ?>">

  <?php if ( !empty( $meta_image_width ) ): ?>
    <meta property="og:image:width" content="<?php echo $meta_image_width; ?>">
  <?php endif; ?>

  <?php if ( !empty( $meta_image_height ) ): ?>
    <meta property="og:image:height" content="<?php echo $meta_image_height; ?>">
  <?php endif; ?>

  <?php  if ( !empty( $meta_image_mime_type ) ): ?>
    <meta property="og:image:type" content="<?php echo $meta_image_mime_type; ?>">
  <?php endif; ?>

  <?php  if ( !empty( $meta_image_alt_text ) ): ?>
    <meta property="og:image:alt" content="<?php echo $meta_image_alt_text; ?>">
  <?php endif; ?>
<?php endif; ?>

<meta property="og:description" content="<?php echo $meta_description; ?>">
<meta itemprop="description" content="<?php echo $meta_description; ?>">

<meta property="og:title" content="<?php echo $meta_title; ?>">
<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>">
<meta property="og:see_also" content="<?php echo home_url('/'); ?>">

<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="<?php the_permalink(); ?>">
<meta name="twitter:title" content="<?php $meta_title; ?>">
<meta name="twitter:description" content="<?php echo $meta_description; ?>">
