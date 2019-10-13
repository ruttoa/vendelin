<header class="page-header">
	<div class="container">
		<?php if ( is_front_page() && is_home() ) { // Default homepage ?>
			<h1 class="page-title">
				<?php echo get_bloginfo( 'name' ); ?>
			</h1>
		<?php 
		} elseif ( is_front_page() ) { // Static homepage 
			the_title( '<h1 class="page-title">', '</h1>' );
		} elseif ( is_home() ) { // Blog page 
		?>
			<h1 class="page-title">
				<?php single_post_title(); ?>
			</h1>
		<?php 
		} elseif ( is_archive() ) { // Archive page
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
		} elseif ( is_search() ) { // Search results page
			echo '<h1>'. sprintf( __( '%s Search Results for ', 'lankku' ), $wp_query->found_posts ).' '.get_search_query().'</h1>';
		} else { // Everything else
			the_title( '<h1 class="page-title">', '</h1>' );
		} 
		?>
	</div>
</header>