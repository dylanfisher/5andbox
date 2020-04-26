<?php /* Template Name: Custom Page Name */ ?>

<?php the_post(); ?>

<div class="site-container">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <h2 class="title"><?php the_title(); ?></h2>
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</div>
