<?php // Facebook ?>
<meta property="og:url" content="<?php the_permalink(); ?>">
<!-- <meta property="og:image" content="{{imageUrl}}"> -->
<meta property="og:description" content="<?php echo get_bloginfo('description'); ?>">
<meta property="og:title" content="<?php the_title(); ?>">
<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>">
<meta property="og:see_also" content="<?php echo home_url('/'); ?>">
<?php // Google ?>
<meta itemprop="name" content="<?php the_title(); ?>">
<meta itemprop="description" content="<?php echo get_bloginfo('description'); ?>">
<!-- <meta itemprop="image" content="{{imageUrl}}"> -->
<?php // Twittter ?>
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="<?php the_permalink(); ?>">
<meta name="twitter:title" content="<?php the_title(); ?>">
<meta name="twitter:description" content="<?php echo get_bloginfo('description'); ?>">
<!-- <meta name="twitter:image" content="{{imageUrl}}"> -->
