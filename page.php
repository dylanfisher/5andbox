<?php get_header() ?>
		<div class="content">
<?php the_post() ?>
			<section id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
					<!-- JUST TESTING SOME THINGS: -->
					<div id="test" data-category="1">TEST_BUTTON</div>
					<div id="ajax-here">ajax will go here</div>
					<div>
						<p><?php the_field( "test" ); ?></p>
						<p><?php the_field( "text2" ); ?></p>
						<p><?php the_field( "text3" ); ?></p>
						<div>
<?php
$image = get_field('image');
echo $image["id"] . $image["title"] . $image["url"] . $image["description"]
?>
							<div>
								<img src="<?php echo $image["url"] ?>">
								<p>that owl's image title is: <?php echo $image["title"] ?></p>
							</div>
						</div>
					</div>
					<!-- END TEST -->
<?php the_content() ?>
				</div>
			</section><!-- .post -->
<?php if ( get_post_custom_values('comments') ) comments_template() // Add a key+value of "comments" to enable comments on this page ?>
		</div><!-- .content -->
<?php get_footer() ?>
</body>
</html>