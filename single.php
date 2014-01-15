<?php get_header() ?>
		<div class="content">

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

<?php the_post() ?>
			<section id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
<?php the_content() ?>
				</div>
			</section><!-- .post -->
			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ) ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ) ?></div>
			</div>
		</div><!-- .content -->
<?php get_footer() ?>
</body>
</html>