<?php
/**
 * ACF Blocks Registration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF Blocks
 */
function abf_register_acf_blocks() {
    // Check if ACF is active
    if (!function_exists('acf_register_block_type')) {
        return;
    }
    
    // Get all block directories
    $blocks_dir = get_template_directory() . '/blocks/';
    $blocks = glob($blocks_dir . '*', GLOB_ONLYDIR);
    
    foreach ($blocks as $block_dir) {
        $block_name = basename($block_dir);
        $block_json = $block_dir . '/block.json';
        
        if (file_exists($block_json)) {
            $block_data = json_decode(file_get_contents($block_json), true);
            
            // Load block fields if they exist
            $fields_file = $block_dir . '/fields.php';
            if (file_exists($fields_file)) {
                require_once $fields_file;
            }
            
            // Register block
            acf_register_block_type(array(
                'name' => $block_data['name'] ?? 'acf/' . $block_name,
                'title' => $block_data['title'] ?? ucfirst(str_replace('-', ' ', $block_name)),
                'description' => $block_data['description'] ?? '',
                'category' => $block_data['category'] ?? 'formatting',
                'icon' => $block_data['icon'] ?? 'admin-generic',
                'keywords' => $block_data['keywords'] ?? array(),
                'supports' => $block_data['supports'] ?? array(),
                'render_template' => $block_dir . '/template.php',
                'enqueue_style' => $block_dir . '/style.css',
                'enqueue_script' => $block_dir . '/script.js',
                'example' => $block_data['example'] ?? array(),
                'mode' => 'edit', // Force edit mode to show fields
            ));
        }
    }
}
add_action('acf/init', 'abf_register_acf_blocks');

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
    $colors_file = get_template_directory() . '/colors.json';
    
    if (file_exists($colors_file)) {
        $colors = json_decode(file_get_contents($colors_file), true);
        
        if ($colors && isset($colors['colors'])) {
            $color_palette = array();
            
            foreach ($colors['colors'] as $color) {
                $color_palette[] = array(
                    'name' => $color['name'],
                    'slug' => sanitize_title($color['name']),
                    'color' => $color['value'],
                );
            }
            
            add_theme_support('editor-color-palette', $color_palette);
        }
    }
}
add_action('after_setup_theme', 'abf_add_color_palette'); 