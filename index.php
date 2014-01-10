	<?php get_header() ?>
			<div class="content">
	<?php while ( have_posts() ) : the_post() ?>
				<section id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
					<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h2>
					<div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php unset($previousday); printf( __( '%1$s &#8211; %2$s', 'sandbox' ), the_date( '', '', '', false ), get_the_time() ) ?></abbr></div>
					<div class="entry-content">
	<?php the_content( __( 'Read More <span class="meta-nav">&raquo;</span>', 'sandbox' ) ) ?>
					</div>
				</section><!-- .post -->
	<?php endwhile; ?>
			</div><!-- .content -->
	<?php get_footer() ?>
	</body>
</html>