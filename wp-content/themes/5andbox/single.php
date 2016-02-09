<?php get_header() ?>
  <?php the_post() ?>
  <div class="content">
    <div id="post-<?php the_ID() ?>" <?php post_class() ?>>
      <h2 class="entry-title"><?php the_title() ?></h2>
      <div class="entry-content">
        <?php the_content() ?>
      </div>
      <?php sandbox_images('images', 'image', 'large', ''); ?>
    </div><!-- .post -->
  </div><!-- .content -->
  <?php get_footer() ?>
</body>
</html>
