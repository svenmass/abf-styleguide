<?php
/**
 * ACF Blocks Registration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register all ACF blocks and their field groups
 */
add_action('acf/init', function() {
    // Check if ACF is available
    if (!function_exists('acf_register_block_type') || !function_exists('acf_add_local_field_group')) {
        return;
    }
    
    $blocks_dir = get_template_directory() . '/blocks/';
    $blocks = glob($blocks_dir . '*', GLOB_ONLYDIR);

    foreach ($blocks as $block_dir) {
        $block_name = basename($block_dir);
        $block_json = $block_dir . '/block.json';
        
        if (file_exists($block_json)) {
            $block_data = json_decode(file_get_contents($block_json), true);
            
            // Register field groups first - inline
            if ($block_name === 'headline') {
                acf_add_local_field_group(array(
                    'key' => 'group_headline_block',
                    'title' => 'Headline Block',
                    'fields' => array(
                        array(
                            'key' => 'field_headline_text',
                            'label' => 'Headline Text',
                            'name' => 'headline_text',
                            'type' => 'text',
                            'instructions' => 'Gib hier den Text für die Headline ein',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_headline_tag',
                            'label' => 'H-Klasse',
                            'name' => 'headline_tag',
                            'type' => 'select',
                            'instructions' => 'Wähle die HTML-Tag für die Headline',
                            'required' => 1,
                            'choices' => array(
                                'h1' => 'H1',
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'h6' => 'H6',
                            ),
                            'default_value' => 'h2',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_headline_size',
                            'label' => 'Schriftgröße (px)',
                            'name' => 'headline_size',
                            'type' => 'select',
                            'instructions' => 'Wähle die Schriftgröße',
                            'required' => 1,
                            'choices' => array(
                                '12' => '12px (Small)',
                                '18' => '18px (Body)',
                                '24' => '24px (H2)',
                                '36' => '36px (H1)',
                                '48' => '48px (Large)',
                            ),
                            'default_value' => '24',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_headline_weight',
                            'label' => 'Schriftschnitt',
                            'name' => 'headline_weight',
                            'type' => 'select',
                            'instructions' => 'Wähle den Schriftschnitt',
                            'required' => 1,
                            'choices' => array(
                                '300' => 'Light (300)',
                                '400' => 'Regular (400)',
                                '700' => 'Bold (700)',
                            ),
                            'default_value' => '400',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_headline_color',
                            'label' => 'Farbe',
                            'name' => 'headline_color',
                            'type' => 'select',
                            'instructions' => 'Wähle eine Farbe für die Headline',
                            'required' => 1,
                            'choices' => array(
                                'inherit' => 'Standard (inherit)',
                                'primary' => 'Primärfarbe',
                                'secondary' => 'Sekundärfarbe',
                            ),
                            'default_value' => 'inherit',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_headline_padding',
                            'label' => 'Padding',
                            'name' => 'headline_padding',
                            'type' => 'select',
                            'instructions' => 'Wähle das Padding basierend auf den Spacing-Größen',
                            'required' => 0,
                            'choices' => array(
                                'none' => 'Kein Padding',
                                'xs' => 'XS (12px mobile)',
                                'sm' => 'SM (16px tablet)',
                                'md' => 'MD (24px desktop)',
                                'lg' => 'LG (32px large desktop)',
                            ),
                            'default_value' => 'md',
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'block',
                                'operator' => '==',
                                'value' => 'acf/headline',
                            ),
                        ),
                    ),
                    'menu_order' => 0,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => true,
                    'description' => 'Felder für den Headline-Block',
                ));
            }
            
            if ($block_name === 'hero') {
                acf_add_local_field_group(array(
                    'key' => 'group_hero_block',
                    'title' => 'Hero Block',
                    'fields' => array(
                        array(
                            'key' => 'field_hero_title',
                            'label' => 'Titel',
                            'name' => 'title',
                            'type' => 'text',
                            'instructions' => 'Gib hier den Haupttitel für den Hero-Bereich ein',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_hero_text',
                            'label' => 'Text',
                            'name' => 'text',
                            'type' => 'wysiwyg',
                            'instructions' => 'Gib hier den Beschreibungstext ein',
                            'required' => 0,
                            'default_value' => '',
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 0,
                            'delay' => 0,
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'block',
                                'operator' => '==',
                                'value' => 'acf/hero',
                            ),
                        ),
                    ),
                    'menu_order' => 0,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => true,
                    'description' => 'Felder für den Hero-Block',
                ));
            }

            // Register block
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