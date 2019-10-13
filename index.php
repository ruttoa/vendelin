<?php get_header(); ?>
<main role="main" class="clearfix">
	<section>
		<?php get_template_part( 'template-parts/page', 'header' ); ?>
		<div class="container">
			<div class="primary">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile; ?>
				<div class="pagination">
					<?php 
					the_posts_pagination(
						array(
							'prev_text'          => __( 'Previous page' ),
							'next_text'          => __( 'Next page' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page' ) . ' </span>',
						)
					); 
					?>
				</div>
				<?php else :
					get_template_part( 'template-parts/content', 'none' );
				endif; ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
