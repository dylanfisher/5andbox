<?php the_post() ?>
<div class="content">
  <div id="post-<?php the_ID() ?>" <?php post_class() ?>>
    <h2 class="entry-title"><?php the_title() ?></h2>
    <div class="entry-content">
      <?php the_content() ?>
    </div>
  </div><!-- .post -->
</div><!-- .content -->
