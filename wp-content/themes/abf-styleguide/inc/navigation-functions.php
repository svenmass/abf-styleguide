<?php
/**
 * Navigation Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fallback menu function
 */
function abf_fallback_menu() {
    echo '<ul class="navigation-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Startseite</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about')) . '">Über uns</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact')) . '">Kontakt</a></li>';
    echo '</ul>';
}

/**
 * Register navigation menu location
 */
function abf_register_navigation_menus() {
    register_nav_menus(array(
        'primary' => __('Hauptnavigation', 'abf-styleguide'),
    ));
}
add_action('init', 'abf_register_navigation_menus');

/**
 * Enqueue navigation scripts
 */
function abf_enqueue_navigation_scripts() {
    wp_enqueue_script(
        'abf-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'abf_enqueue_navigation_scripts'); 