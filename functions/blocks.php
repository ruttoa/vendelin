<?php
function register_acf_block_types() {
    if( function_exists('acf_register_block_type') ) {

        // Accordion
        acf_register_block_type(array(
            'name'              => 'accordion',
            'title'             => __('Accordion'),
            'description'       => __('A accordion block for hiding content. Toggle the content by clicking the title.'),
            'render_template'   => get_theme_file_path('template-parts/blocks/accordion/accordion.php'), // Check first from child theme folder.
            'category'          => 'formatting',
            'icon'              => 'editor-justify',
            'keywords'          => array( 'accordion', 'haitari', 'collapse' ),
            'enqueue_assets' 	=> function(){
                wp_enqueue_style( 'block-accordion', get_theme_file_uri('/template-parts/blocks/accordion/accordion.css'), array(), '1.0.0' );
                //wp_enqueue_script( 'block-accordion-js', get_theme_file_uri('/template-parts/blocks/accordion/accordion.js'), array(), '1.0.0', true );
            },
        ));

        // Slider
        acf_register_block_type(array(
            'name'              => 'slider',
            'title'             => __('Slider'),
            'description'       => __('Image slider block.'),
            'render_template'   => get_theme_file_path('template-parts/blocks/slider/slider.php'), // Check first from child theme folder.
            'category'          => 'formatting',
            'icon'              => 'images-alt2',
            'keywords'          => array( 'image', 'slider', 'kuva' ),
            'enqueue_assets' 	=> function(){
                // load owl plugin files only on block editor as in front-end they are included in the combined script file
                require_once(ABSPATH . 'wp-admin/includes/screen.php');
                global $current_screen;
                $current_screen = get_current_screen();
                if( method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor() ) {
                    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/lib/owl.carousel.min.js', array(), '1.0.0', true );
                    wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/lib/owl.carousel.css', array(), '1.0.0' );
                }
                wp_enqueue_style( 'block-slider', get_theme_file_uri('/template-parts/blocks/slider/slider.css'), array(), '1.0.0' );
                wp_enqueue_script( 'block-slider', get_theme_file_uri('/template-parts/blocks/slider/slider.js'), array(), '1.0.0', true );
            },
        ));

    }
}
add_action('acf/init', 'register_acf_block_types');
