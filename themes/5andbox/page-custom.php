<?php /* Template Name: Custom Page Name */ ?>

<?php the_post() ?>
<div class="content">
  <div id="page-<?php the_ID() ?>" <?php post_class() ?>>
    <h2 class="entry-title"><?php the_title() ?></h2>
    <h4>find me in page-custom.php</h4>
    <div class="entry-content">
      <?php the_content() ?>
    </div>
  </div><!-- .post -->
</div><!-- .content -->
