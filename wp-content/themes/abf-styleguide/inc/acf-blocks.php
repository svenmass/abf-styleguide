<?php
/**
 * ACF Blocks Registration
 */

if (!defined('ABSPATH')) {
    exit;
}

// Global array to store field groups
global $abf_block_field_groups;
$abf_block_field_groups = array();

// Function to register a field group (called from fields.php files)
function abf_register_block_field_group($field_group) {
    global $abf_block_field_groups;
    $abf_block_field_groups[] = $field_group;
}

// Automatische Registrierung aller Blöcke und Felder im selben acf/init-Hook
add_action('acf/init', function() {
    global $abf_block_field_groups;
    
    $blocks_dir = get_template_directory() . '/blocks/';
    $blocks = glob($blocks_dir . '*', GLOB_ONLYDIR);

    // Erst alle Field Groups sammeln
    foreach ($blocks as $block_dir) {
        $fields_file = $block_dir . '/fields.php';
        if (file_exists($fields_file)) {
            require_once $fields_file;
        }
    }
    
    // Dann alle Field Groups registrieren
    foreach ($abf_block_field_groups as $field_group) {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group($field_group);
        }
    }

    // Dann alle Blöcke registrieren
    foreach ($blocks as $block_dir) {
        $block_name = basename($block_dir);
        $block_json = $block_dir . '/block.json';

        if (file_exists($block_json)) {
            $block_data = json_decode(file_get_contents($block_json), true);

            acf_register_block_type(array(
                'name' => $block_data['name'] ?? 'acf/' . $block_name,
                'title' => $block_data['title'] ?? ucfirst(str_replace('-', ' ', $block_name)),
                'category' => $block_data['category'] ?? 'formatting',
                'icon' => $block_data['icon'] ?? 'admin-generic',
                'supports' => $block_data['supports'] ?? array('jsx' => true),
                'mode' => $block_data['mode'] ?? 'edit',
                'render_template' => $block_dir . '/template.php',
            ));
        }
    }
});

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