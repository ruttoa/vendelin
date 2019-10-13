<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

	<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail(array(120,120)); // Declare pixel size you need inside the array ?>
		</a>
	<?php endif; ?>

	<header class="entry-header">

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

		get_template_part( 'template-parts/entry-meta'); ?>

	</header>

	<?php the_excerpt(); ?>

</article>