<?php the_post(); ?>

<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="container-fluid">
    <div class="site-container">
      <div class="row">
        <div class="col-sm-12">
          <h2 class="title"><?php the_title(); ?></h2>
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
