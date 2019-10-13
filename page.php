<?php get_header(); ?>
	<main role="main" class="clearfix">
		<?php while ( have_posts() ) : the_post(); ?>
			<section>
				<?php get_template_part( 'template-parts/page', 'header' ); ?>
				<div class="container entry-container clearfix">
					<?php 
					if ( !empty( get_the_content() ) ) :
						get_template_part( 'template-parts/content', 'page' );
					endif; 
					?>
				</div>
			</section>
		<?php endwhile; ?>
	</main>
<?php get_footer(); ?>
