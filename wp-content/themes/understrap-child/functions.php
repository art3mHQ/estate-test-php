<?php
/**
 * Understrap Child Theme functions and definitions.
 * 
 * Theme Name: Real Estate Custom Theme
 * Description: Custom theme for real estate website
 * Version: 1.0.0
 * Author: art3m
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package understrap-child
 */

add_action( 'wp_enqueue_scripts', 'understrap_child_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function understrap_child_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'understrap-style', get_template_directory_uri() . '/style.css', array(), '0.1.0' );
	wp_enqueue_style(
		'understrap-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'understrap-style' ),
		'0.1.0'
	);
}

function real_estate_theme_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'real-estate'),
        'footer' => __('Footer Menu', 'real-estate'),
    ));
}
add_action('after_setup_theme', 'real_estate_theme_setup');

// Enqueue styles and scripts
function real_estate_scripts() {
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('real-estate-style', get_stylesheet_uri());
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.1.3', true);
    wp_enqueue_script('real-estate-script', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'real_estate_scripts');



// add_action('admin_head', 'debug_admin_template');
// function debug_admin_template() {
//     $screen = get_current_screen();
//     if ($screen->post_type === 'real_estate') {
//         echo '<div style="padding: 10px; background: #f1f1f1; border: 1px solid #ddd; margin: 20px;">';
//         echo '<strong>Current Admin Template:</strong> ';
//         echo 'Editing a Real Estate post (uses WordPress core admin templates)';
//         echo '<br><strong>Screen ID:</strong> ' . $screen->id;
//         echo '</div>';
//     }
// }



// Widget areas
function real_estate_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'real-estate'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'real-estate'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer', 'real-estate'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here.', 'real-estate'),
        'before_widget' => '<div class="col-md-3"><div class="widget %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'real_estate_widgets_init');

// Custom excerpt length
function real_estate_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'real_estate_excerpt_length');

// Custom excerpt more
function real_estate_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'real_estate_excerpt_more');










