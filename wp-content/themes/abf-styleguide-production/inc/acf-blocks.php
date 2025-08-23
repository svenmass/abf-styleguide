<?php
/**
 * ACF Blocks Registration - Automatic System
 * 
 * This file provides automatic block registration and field loading.
 * Individual blocks are defined in their respective directories with:
 * - block.json (for registration)
 * - fields.php (for ACF fields)
 * - template.php (for rendering)
 * - style.scss (for styles)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get dynamic color choices for ACF fields
 */
function abf_get_color_choices() {
    $colors = abf_get_colors();
    $choices = array(
        'inherit' => 'Standard (inherit) - #575756',
        'primary' => 'Primärfarbe - #66a98c',
        'secondary' => 'Sekundärfarbe - #c50d14',
    );
    
    if (!empty($colors)) {
        foreach ($colors as $color) {
            $color_name = $color['name'] ?? '';
            if ($color_name) {
                $color_slug = sanitize_title($color_name);
                $choices[$color_slug] = $color_name;
            }
        }
    }
    
    return $choices;
}

/**
 * Automatic Block Registration System
 * Scans all block directories and automatically registers blocks via block.json
 */
add_action('init', 'abf_register_blocks_automatically');

function abf_register_blocks_automatically() {
    // Check if block registration function exists
    if (!function_exists('register_block_type')) {
        return;
    }
    
    $blocks_dir = get_template_directory() . '/blocks';
    
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Scan all block directories
    $block_directories = glob($blocks_dir . '/*', GLOB_ONLYDIR);
    
    foreach ($block_directories as $block_dir) {
        $block_name = basename($block_dir);
        $block_json_path = $block_dir . '/block.json';
        
        // Register block if block.json exists
        if (file_exists($block_json_path)) {
            register_block_type($block_dir);
        }
    }
}

/**
 * Automatically load all fields.php files from block directories
 */
add_action('acf/init', 'abf_load_block_fields_automatically');

function abf_load_block_fields_automatically() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    $blocks_dir = get_template_directory() . '/blocks';
    
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Scan all block directories
    $block_directories = glob($blocks_dir . '/*', GLOB_ONLYDIR);
    
    foreach ($block_directories as $block_dir) {
        $block_name = basename($block_dir);
        $fields_path = $block_dir . '/fields.php';
        
        // Load fields if fields.php exists
        if (file_exists($fields_path)) {
            $field_group = require $fields_path;
            
            if (is_array($field_group)) {
                acf_add_local_field_group($field_group);
            }
        }
    }
}

/**
 * Add custom block categories
 */
function abf_block_categories($categories, $post) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'abf-blocks',
                'title' => __('ABF Blocks', 'abf-styleguide'),
                'icon' => 'admin-generic',
            ),
        )
    );
}
add_filter('block_categories_all', 'abf_block_categories', 10, 2);

/**
 * Add ACF color palette to editor
 */
function abf_add_color_palette() {
    // Nutze zentrale Logik (uploads -> stylesheet -> template)
    if (!function_exists('abf_get_colors')) { return; }
    $palette = abf_get_colors();
    if (is_array($palette) && !empty($palette)) {
        $color_palette = array();
        foreach ($palette as $color) {
            $name = isset($color['name']) ? $color['name'] : '';
            $value = isset($color['value']) ? $color['value'] : '';
            if ($name && $value) {
                $color_palette[] = array(
                    'name' => $name,
                    'slug' => sanitize_title($name),
                    'color' => $value,
                );
            }
        }
        if (!empty($color_palette)) {
            add_theme_support('editor-color-palette', $color_palette);
        }
    }
}
add_action('after_setup_theme', 'abf_add_color_palette', 99);

/**
 * Enqueue block editor styles
 */
function abf_block_editor_styles() {
    wp_enqueue_style(
        'abf-blocks-editor-style',
        get_template_directory_uri() . '/assets/css/main.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/main.css')
    );
}
add_action('enqueue_block_editor_assets', 'abf_block_editor_styles');