<?php
/**
 * ACF Blocks Registration - Manual Registration
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
        'inherit' => 'Standard (inherit)',
        'primary' => 'Primärfarbe',
        'secondary' => 'Sekundärfarbe',
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
 * Register ACF Blocks manually
 */
add_action('acf/init', 'abf_register_acf_blocks');

function abf_register_acf_blocks() {
    // Check if ACF is available
    if (!function_exists('acf_register_block_type') || !function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Register Headline Block
    acf_register_block_type(array(
        'name'              => 'headline',
        'title'             => __('Headline Block'),
        'description'       => __('Ein konfigurierbarer Headline-Block'),
        'render_template'   => get_template_directory() . '/blocks/headline/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'heading',
        'keywords'          => array('headline', 'title', 'heading'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'edit',
    ));
    
    // Register Hero Block
    acf_register_block_type(array(
        'name'              => 'hero',
        'title'             => __('Hero Block'),
        'description'       => __('Ein Hero-Bereich mit Titel und Text'),
        'render_template'   => get_template_directory() . '/blocks/hero/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'align-wide',
        'keywords'          => array('hero', 'banner', 'header'),
        'supports'          => array(
            'jsx' => true,
            'align' => array('wide', 'full'),
        ),
        'mode'              => 'edit',
    ));
}

/**
 * Register ACF Field Groups manually
 */
add_action('acf/init', 'abf_register_acf_field_groups');

function abf_register_acf_field_groups() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Get dynamic color choices
    $color_choices = abf_get_color_choices();
    
    // Headline Block Field Group
    acf_add_local_field_group(array(
        'key' => 'group_headline_block',
        'title' => 'Headline Block Felder',
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
                'label' => 'HTML Tag',
                'name' => 'headline_tag',
                'type' => 'select',
                'instructions' => 'Wähle das HTML-Tag für die Headline',
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
                'label' => 'Schriftgröße',
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
                'label' => 'Textfarbe',
                'name' => 'headline_color',
                'type' => 'select',
                'instructions' => 'Wähle eine Farbe für die Headline',
                'required' => 1,
                'choices' => $color_choices,
                'default_value' => 'inherit',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_headline_padding',
                'label' => 'Abstand',
                'name' => 'headline_padding',
                'type' => 'select',
                'instructions' => 'Wähle den Abstand um die Headline',
                'required' => 0,
                'choices' => array(
                    'none' => 'Kein Abstand',
                    'xs' => 'XS (12px)',
                    'sm' => 'SM (16px)',
                    'md' => 'MD (24px)',
                    'lg' => 'LG (32px)',
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
    
    // Hero Block Field Group
    acf_add_local_field_group(array(
        'key' => 'group_hero_block',
        'title' => 'Hero Block Felder',
        'fields' => array(
            array(
                'key' => 'field_hero_title',
                'label' => 'Haupttitel',
                'name' => 'title',
                'type' => 'text',
                'instructions' => 'Gib hier den Haupttitel für den Hero-Bereich ein',
                'required' => 1,
            ),
            array(
                'key' => 'field_hero_text',
                'label' => 'Beschreibungstext',
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
            array(
                'key' => 'field_hero_background_image',
                'label' => 'Hintergrundbild',
                'name' => 'background_image',
                'type' => 'image',
                'instructions' => 'Wähle ein Hintergrundbild (optional)',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_hero_button_text',
                'label' => 'Button Text',
                'name' => 'button_text',
                'type' => 'text',
                'instructions' => 'Text für den Call-to-Action Button',
                'required' => 0,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_hero_button_link',
                'label' => 'Button Link',
                'name' => 'button_link',
                'type' => 'url',
                'instructions' => 'Link für den Button',
                'required' => 0,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_hero_button_color',
                'label' => 'Button Farbe',
                'name' => 'button_color',
                'type' => 'select',
                'instructions' => 'Wähle eine Farbe für den Button',
                'required' => 0,
                'choices' => $color_choices,
                'default_value' => 'primary',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
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