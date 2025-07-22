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
    echo '<ul class="navigation__menu">';
    echo '<li class="navigation__menu-item"><a href="' . esc_url(home_url('/')) . '" class="navigation__menu-link">Startseite</a></li>';
    echo '<li class="navigation__menu-item"><a href="' . esc_url(home_url('/about')) . '" class="navigation__menu-link">Über uns</a></li>';
    echo '<li class="navigation__menu-item"><a href="' . esc_url(home_url('/contact')) . '" class="navigation__menu-link">Kontakt</a></li>';
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

class ABF_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"navigation__submenu\">\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'navigation__menu-item';
        $has_children = in_array('menu-item-has-children', $classes);
        $output .= '<li class="' . implode(' ', $classes) . '">';
        $output .= '<a href="' . esc_attr($item->url) . '" class="navigation__menu-link">' . apply_filters('the_title', $item->title, $item->ID) . '</a>';
        if ($has_children) {
            $output .= '<span class="navigation__submenu-toggle" tabindex="0" aria-label="Subnavigation öffnen">'
                . '<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">'
                . '<line x1="7.5" y1="0.106598" x2="7.5" y2="15.1066" stroke="#74A68E"/>'
                . '<line x1="15" y1="7.6066" x2="-4.37114e-08" y2="7.6066" stroke="#74A68E"/>'
                . '</svg>'
                . '</span>';
        }
    }

    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
} 