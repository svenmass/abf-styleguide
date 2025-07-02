<?php
/**
 * Dynamic Colors Management
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add dynamic colors as CSS variables
 */
function abf_add_dynamic_colors_css() {
    $colors = abf_get_colors();
    
    if (empty($colors)) {
        return;
    }
    
    $css_variables = array();
    
    foreach ($colors as $color) {
        $color_name = sanitize_title($color['name']);
        $color_value = $color['value'];
        
        // Add main color variable
        $css_variables[] = "--color-{$color_name}: {$color_value};";
        
        // Add darker variant for hover states
        $darker_color = abf_darken_color($color_value, 10);
        $css_variables[] = "--color-{$color_name}-dark: {$darker_color};";
        
        // Add lighter variant
        $lighter_color = abf_lighten_color($color_value, 10);
        $css_variables[] = "--color-{$color_name}-light: {$lighter_color};";
    }
    
    if (!empty($css_variables)) {
        echo '<style id="abf-dynamic-colors">';
        echo ':root {';
        echo implode(' ', $css_variables);
        echo '}';
        echo '</style>';
    }
}
add_action('wp_head', 'abf_add_dynamic_colors_css');

/**
 * Add dynamic colors to Gutenberg editor
 */
function abf_add_dynamic_colors_editor() {
    $colors = abf_get_colors();
    
    if (empty($colors)) {
        return;
    }
    
    $css_variables = array();
    
    foreach ($colors as $color) {
        $color_name = sanitize_title($color['name']);
        $color_value = $color['value'];
        
        $css_variables[] = "--color-{$color_name}: {$color_value};";
    }
    
    if (!empty($css_variables)) {
        echo '<style id="abf-dynamic-colors-editor">';
        echo '.editor-styles-wrapper {';
        echo implode(' ', $css_variables);
        echo '}';
        echo '</style>';
    }
}
add_action('admin_head', 'abf_add_dynamic_colors_editor');

/**
 * Darken a hex color by a percentage
 */
function abf_darken_color($hex, $percent) {
    $hex = str_replace('#', '', $hex);
    
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }
    
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r - ($r * $percent / 100)));
    $g = max(0, min(255, $g - ($g * $percent / 100)));
    $b = max(0, min(255, $b - ($b * $percent / 100)));
    
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * Lighten a hex color by a percentage
 */
function abf_lighten_color($hex, $percent) {
    $hex = str_replace('#', '', $hex);
    
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }
    
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r + ((255 - $r) * $percent / 100)));
    $g = max(0, min(255, $g + ((255 - $g) * $percent / 100)));
    $b = max(0, min(255, $b + ((255 - $b) * $percent / 100)));
    
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * Get color value by name
 */
function abf_get_color_value($color_name) {
    $colors = abf_get_colors();
    
    foreach ($colors as $color) {
        if (sanitize_title($color['name']) === sanitize_title($color_name)) {
            return $color['value'];
        }
    }
    
    return null;
}

/**
 * Get all color names as array
 */
function abf_get_color_names() {
    $colors = abf_get_colors();
    $names = array();
    
    foreach ($colors as $color) {
        $names[] = $color['name'];
    }
    
    return $names;
} 