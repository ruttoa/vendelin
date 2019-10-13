<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="theme-color" content="#FFFFFF">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<?php 
		// Wordpress wp_body_open action for script snippets etc.
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		} 
		?>

		<!--[if lte IE 9]>
            <div class="browserupgrade alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/?locale=en">upgrade your browser</a> to improve your experience.</div>
        <![endif]-->

		<div class="wrapper">
			<header class="site-header" role="banner">
				<nav class="navbar" role="navigation" aria-label="Primary navigation">
					<?php theme_logo(); ?>
					<?php if ( is_front_page() ) { ?>
						<a class="btn" role="button" href="#contact" title="Yhteystieto-osioon">Ota yhteyttä</a>
					<?php } ?>
				</nav>
			</header>
