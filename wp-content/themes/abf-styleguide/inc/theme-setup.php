<?php
/**
 * Theme Setup Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function abf_theme_setup() {
    // Theme Support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    
    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'abf-styleguide'),
        'footer' => __('Footer Menu', 'abf-styleguide'),
    ));
}
add_action('after_setup_theme', 'abf_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function abf_enqueue_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('abf-style', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Enqueue fonts
    wp_enqueue_style('abf-fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), '1.0.0');
    
    // Enqueue PhotoSwipe library (CDN)
    wp_enqueue_style('photoswipe', 'https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe.css', array(), '5.4.4');
    wp_enqueue_script('photoswipe', 'https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe.umd.min.js', array(), '5.4.4', true);
    
    // Enqueue scripts
    wp_enqueue_script('abf-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'abf_enqueue_scripts');

/**
 * Dequeue default WordPress styles
 */
function abf_dequeue_default_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // WooCommerce block CSS
}
add_action('wp_enqueue_scripts', 'abf_dequeue_default_styles', 100);

/**
 * Add custom image sizes
 */
function abf_image_sizes() {
    add_image_size('abf-hero', 1920, 800, true);
    add_image_size('abf-thumbnail', 400, 300, true);
    add_image_size('abf-medium', 800, 600, true);
    
    // Set post-thumbnail size for cards (16:10 aspect ratio)
    set_post_thumbnail_size(400, 250, true);
}
add_action('after_setup_theme', 'abf_image_sizes'); 