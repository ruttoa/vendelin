<?php

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here
require_once( 'functions/remove-commenting.php' );
require_once( 'functions/clean-media-filenames.php' );
require_once( 'functions/blocks.php' );


/*------------------------------------*\
	Theme Setup
\*------------------------------------*/

if ( ! function_exists( 'theme_setup' ) ) :
function theme_setup() {

    register_nav_menus(array(
        'header-menu' => __('Header Menu', 'blank'), 
    ));

    add_theme_support( 'custom-logo', array(
        //'height'      => 200,
        //'width'       => 300,
        'flex-height' => true,
        'flex-width' => true,
    ));

    /*
     * This theme styles the visual editor to resemble the theme style,
     * specifically font, colors, icons, and column width.
     */
    add_theme_support( 'editor-styles' );
    add_editor_style( array( 'dist/editor-style.css', 'google-fonts' ) );

    // Add support for Block Styles.
    add_theme_support( 'wp-block-styles' );
        
    // Add support for wide images in Gutenberg editor
    add_theme_support( 'align-wide' );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );
    
    // Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

    // Disable Gutenberg Custom Colors
    add_theme_support( 'disable-custom-colors' );

    // Disable User Notification of Password Change Confirmation
    add_filter( 'send_email_change_email', '__return_false' );

    add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'White' ),
			'slug'  => 'white',
			'color' => '#fff',
		),
        array(
			'name'  => __( 'Green' ),
			'slug'  => 'green',
			'color' => '#00D796',
		),
        array(
			'name'  => __( 'Blue' ),
			'slug'  => 'blue',
			'color' => '#023D58',
		),
        array(
			'name'  => __( 'Pink' ),
			'slug'  => 'pink',
			'color' => '#FFD9DA',
		),
	));

    add_theme_support( 'title-tag' );
}
endif; // if theme_setup
add_action( 'after_setup_theme', 'theme_setup' );


if (!isset($content_width))
{
    $content_width = 900;
}


/*------------------------------------*\
	Functions
\*------------------------------------*/



/**
 * Register scripts and styles.
 */
function theme_scripts_styles() {
    //wp_deregister_script('jquery');
    //wp_register_script('jquery', false);
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Heebo:300,400,500,700,900&display=swap', array(), '0.1');
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '0.1');
    wp_enqueue_style('styles-min', get_template_directory_uri() . '/dist/style.min.css', array(), '0.1.3');
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '', true);
    wp_enqueue_script('scripts-min', get_template_directory_uri() . '/dist/scripts.min.js', array('swiper-js'), '0.1.3', true);
    
}
add_action( 'wp_enqueue_scripts', 'theme_scripts_styles' );

/**
 * Enqueue scripts and styles for admin
 */
function theme_admin_scripts() {
    // Use get_theme_file_uri function so you can just copy the file to child theme to override it
    wp_enqueue_style('theme-admin-css', get_theme_file_uri( '/css/admin.css' ), false, '0.1');
}
add_action('admin_enqueue_scripts', 'theme_admin_scripts');

/**
 * Gutenberg scripts and styles
 */
function theme_gutenberg_assets() {
    // Use get_theme_file_uri function so you can just copy the file to child theme to override it
    wp_enqueue_style( 'theme-gutenberg', get_theme_file_uri( 'dist/editor-style.css' ), false );
	wp_enqueue_script( 'theme-editor-js', get_theme_file_uri( '/js/editor.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get('Version'), true );
}
add_action( 'enqueue_block_editor_assets', 'theme_gutenberg_assets' );


/**
* Dequeue jQuery Migrate script in WordPress and move jQuery to footer.
*/
function theme_remove_jquery_migrate($scripts) {
    if(!is_admin()) {
        // Remove jQuery Migrate
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
        // Move jQuery to footer
        $scripts->add_data('jquery', 'group', 1);
        $scripts->add_data('jquery-core', 'group', 1);
    }
}
add_filter( 'wp_default_scripts', 'theme_remove_jquery_migrate' );


/*
// Site branding function. Show site title and description if no logo has been uploaded.
*/
function theme_logo() { ?>

    <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
        <?php 
        //$image = get_stylesheet_directory_uri() .'/img/logo.svg';
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) :
            
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
            $image = $image[0];
            //the_custom_logo(); ?>

            <div class='site-logo'>
                <img src="<?php echo $image ?>" class="logo-img" alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
            </div>

        <?php endif; ?>

    </a>

<?php };


/*
// Add SVG image upload support
*/
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        /*$key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }*/
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }
    if ( is_active_sidebar( 'widget-area-1' ) ) {
        $classes[] = 'has-sidebar';
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{   

    // Define Footer Widget Area 1
    register_sidebar(array(
        'name' => __('Footer 1', 'blank'),
        'description' => __('Widget area in footer', 'blank'),
        'id' => 'sidebar-footer',
        'before_widget' => '<div id="%1$s" class="%2$s widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()


// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}



/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/


// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
//add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
//add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether


// Remove Query Strings for faster page loading with cache plugins (better Pingdom score)
// function _remove_script_version( $src ){
//     $parts = explode( '?ver', $src );
//     return $parts[0];
// }
// add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
// add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );


// Add Featured Image column to admin panel post list
function add_img_column($columns) {
    $columns['img'] = __('Image', 'blank');
    return $columns;
}
function manage_img_column($column_name, $post_id) {
    if( $column_name == 'img' ) {
        echo get_the_post_thumbnail($post_id, array(40, 40));
    }
    return $column_name;
}
add_filter('manage_posts_columns', 'add_img_column');
add_filter('manage_posts_custom_column', 'manage_img_column', 10, 2);


/**
 * Disable Wordpress emoji's
 */
function theme_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'theme_disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'theme_disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'theme_disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 */
function theme_disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 */
function theme_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
        $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }
    return $urls;
}



// Add editor role users capability to edit widgets and add users
function theme_modify_editor_permissions(){
    $role = get_role( 'editor' );
    $role->add_cap( 'create_users' );
    $role->add_cap( 'delete_users' );
    $role->add_cap( 'edit_users' );
    $role->add_cap( 'list_users' );
    $role->add_cap( 'promote_users' );
    $role->add_cap( 'remove_users' );
    $role->add_cap( 'edit_theme_options' );
}
add_action('init', 'theme_modify_editor_permissions'); 

/** 
 * Disallow editors to create or remove admin users 
 */
class theme_User_Caps {

  // Add our filters
  function __construct(){
    add_filter( 'editable_roles', array($this, 'editable_roles'));
    add_filter( 'map_meta_cap', array($this, 'map_meta_cap'), 10, 4);
  }

  // Remove 'Administrator' from the list of roles if the current user is not an admin
  function editable_roles( $roles ){
    if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
      unset( $roles['administrator']);
    }
    return $roles;
  }

  // If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
  function map_meta_cap( $caps, $cap, $user_id, $args ){

    switch( $cap ){
        case 'edit_user':
        case 'remove_user':
        case 'promote_user':
            if( isset($args[0]) && $args[0] == $user_id )
                break;
            elseif( !isset($args[0]) )
                $caps[] = 'do_not_allow';
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        case 'delete_user':
        case 'delete_users':
            if( !isset($args[0]) )
                break;
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        default:
            break;
    }
    return $caps;
  }
}
$jpb_user_caps = new theme_User_Caps();

/*
 * Allow editors and multisite admins edit privacy policy.
 */ 
function theme_manage_privacy_options($caps, $cap, $user_id, $args) {
    if ('manage_privacy_options' === $cap) {
        $manage_name = is_multisite() ? 'manage_network' : 'manage_options';
        $caps = array_diff($caps, [ $manage_name ]);
    }
    return $caps;
}
add_action('map_meta_cap', 'theme_manage_privacy_options', 1, 4);

?>
