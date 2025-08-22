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
 * Check if current page should use styleguide layout (no footer)
 * 
 * @return bool True if page should use styleguide layout
 */
function abf_is_styleguide_page() {
    // If using Styleguide Page template
    if (is_page_template('page-styleguide.php')) {
        return true;
    }
    
    // Check for custom field that indicates styleguide page
    if (function_exists('get_field') && get_field('is_styleguide_page')) {
        return true;
    }
    
    // Check page slug patterns that indicate styleguide content
    global $post;
    if ($post) {
        $slug = $post->post_name;
        $styleguide_patterns = ['styleguide', 'style-guide', 'basiselemente', 'anwendungen', 'einfuehrung'];
        
        foreach ($styleguide_patterns as $pattern) {
            if (strpos($slug, $pattern) !== false) {
                return true;
            }
        }
    }
    
    return false;
}

/**
 * Enqueue Scripts and Styles
 */
function abf_enqueue_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('abf-style', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Enqueue fonts
    wp_enqueue_style('abf-fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), '1.0.0');
    
    // Enqueue PhotoSwipe library (CDN) - v5 uses ESM modules, so we only need the CSS
    wp_enqueue_style('photoswipe', 'https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe.css', array(), '5.4.4');
    // PhotoSwipe v5 core will be loaded dynamically via import() in the template
    
    // Enqueue scripts
    wp_enqueue_script('abf-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
    // Styleguide Palette JS
    $palette_js = get_template_directory() . '/assets/js/styleguide-palette.js';
    $palette_ver = file_exists($palette_js) ? filemtime($palette_js) : '1.0.0';
    wp_enqueue_script('abf-styleguide-palette', get_template_directory_uri() . '/assets/js/styleguide-palette.js', array(), $palette_ver, true);
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