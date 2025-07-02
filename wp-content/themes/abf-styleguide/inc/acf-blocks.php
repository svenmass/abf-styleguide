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
        'description'       => __('Ein vollbreiter Hero-Block mit Video/Bild-Hintergrund, Logo, Headlines und Button'),
        'render_template'   => get_template_directory() . '/blocks/hero/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'format-image',
        'keywords'          => array('hero', 'header', 'video', 'background', 'headline'),
        'supports'          => array(
            'jsx' => true,
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
            // Background Settings
            array(
                'key' => 'field_hero_background_type',
                'label' => 'Hintergrund Typ',
                'name' => 'hero_background_type',
                'type' => 'select',
                'instructions' => 'Wähle zwischen Bild oder Video',
                'required' => 1,
                'choices' => array(
                    'image' => 'Bild',
                    'video' => 'Video',
                ),
                'default_value' => 'image',
                'wrapper' => array('width' => '100'),
            ),
            array(
                'key' => 'field_hero_background_image',
                'label' => 'Hintergrund Bild',
                'name' => 'hero_background_image',
                'type' => 'image',
                'instructions' => 'Wähle ein Hintergrund-Bild (wird als "cover" dargestellt)',
                'required' => 0,
                'return_format' => 'array',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_hero_background_type',
                            'operator' => '==',
                            'value' => 'image',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_hero_background_video',
                'label' => 'Hintergrund Video',
                'name' => 'hero_background_video',
                'type' => 'file',
                'instructions' => 'Wähle ein Hintergrund-Video (MP4 empfohlen, läuft automatisch in Schleife)',
                'required' => 0,
                'return_format' => 'array',
                'mime_types' => 'mp4,webm,ogg',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_hero_background_type',
                            'operator' => '==',
                            'value' => 'video',
                        ),
                    ),
                ),
            ),
            
            // Headline Settings
            array(
                'key' => 'field_hero_headline',
                'label' => 'Headline',
                'name' => 'hero_headline',
                'type' => 'textarea',
                'instructions' => 'Gib hier die Hauptüberschrift ein',
                'required' => 1,
                'rows' => 3,
            ),
            array(
                'key' => 'field_hero_headline_tag',
                'label' => 'Headline HTML Tag',
                'name' => 'hero_headline_tag',
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
                'default_value' => 'h1',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_headline_weight',
                'label' => 'Headline Schriftgewicht',
                'name' => 'hero_headline_weight',
                'type' => 'select',
                'instructions' => 'Wähle das Schriftgewicht',
                'required' => 1,
                'choices' => array(
                    '300' => 'Light (300)',
                    '400' => 'Regular (400)',
                    '700' => 'Bold (700)',
                ),
                'default_value' => '700',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_headline_size',
                'label' => 'Headline Schriftgröße',
                'name' => 'hero_headline_size',
                'type' => 'select',
                'instructions' => 'Wähle die Schriftgröße',
                'required' => 1,
                'choices' => array(
                    '24' => '24px (H2)',
                    '36' => '36px (H1)',
                    '48' => '48px (Large)',
                    '60' => '60px (XL)',
                    '72' => '72px (XXL)',
                ),
                'default_value' => '48',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_headline_color',
                'label' => 'Headline Farbe',
                'name' => 'hero_headline_color',
                'type' => 'select',
                'instructions' => 'Wähle eine Farbe für die Headline',
                'required' => 1,
                'choices' => $color_choices,
                'default_value' => 'primary',
                'wrapper' => array('width' => '25'),
            ),
            
            // Subline Settings
            array(
                'key' => 'field_hero_subline',
                'label' => 'Subline',
                'name' => 'hero_subline',
                'type' => 'textarea',
                'instructions' => 'Gib hier die Unterüberschrift ein (optional)',
                'required' => 0,
                'rows' => 2,
            ),
            array(
                'key' => 'field_hero_subline_tag',
                'label' => 'Subline HTML Tag',
                'name' => 'hero_subline_tag',
                'type' => 'select',
                'instructions' => 'Wähle das HTML-Tag für die Subline',
                'required' => 1,
                'choices' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'Paragraph',
                ),
                'default_value' => 'h2',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_subline_weight',
                'label' => 'Subline Schriftgewicht',
                'name' => 'hero_subline_weight',
                'type' => 'select',
                'instructions' => 'Wähle das Schriftgewicht',
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
                'key' => 'field_hero_subline_size',
                'label' => 'Subline Schriftgröße',
                'name' => 'hero_subline_size',
                'type' => 'select',
                'instructions' => 'Wähle die Schriftgröße',
                'required' => 1,
                'choices' => array(
                    '18' => '18px (Body)',
                    '24' => '24px (H2)',
                    '30' => '30px (Medium)',
                    '36' => '36px (H1)',
                    '60' => '60px (XL)',
                ),
                'default_value' => '24',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_subline_color',
                'label' => 'Subline Farbe',
                'name' => 'hero_subline_color',
                'type' => 'select',
                'instructions' => 'Wähle eine Farbe für die Subline',
                'required' => 1,
                'choices' => $color_choices,
                'default_value' => 'secondary',
                'wrapper' => array('width' => '25'),
            ),
            
            // Button Settings
            array(
                'key' => 'field_hero_button_text',
                'label' => 'Button Text',
                'name' => 'hero_button_text',
                'type' => 'text',
                'instructions' => 'Gib hier den Button-Text ein (optional)',
                'required' => 0,
            ),
            array(
                'key' => 'field_hero_button_url',
                'label' => 'Button URL',
                'name' => 'hero_button_url',
                'type' => 'url',
                'instructions' => 'Gib hier die Ziel-URL ein (optional, leer = kein Link)',
                'required' => 0,
            ),
            array(
                'key' => 'field_hero_button_bg_color',
                'label' => 'Button Hintergrundfarbe',
                'name' => 'hero_button_bg_color',
                'type' => 'select',
                'instructions' => 'Wähle die Hintergrundfarbe des Buttons',
                'required' => 1,
                'choices' => array_merge(array('white' => 'Weiß'), $color_choices),
                'default_value' => 'primary',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_button_text_color',
                'label' => 'Button Textfarbe',
                'name' => 'hero_button_text_color',
                'type' => 'select',
                'instructions' => 'Wähle die Textfarbe des Buttons',
                'required' => 1,
                'choices' => array_merge(array('white' => 'Weiß'), $color_choices),
                'default_value' => 'white',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_button_hover_bg',
                'label' => 'Button Hover Hintergrund',
                'name' => 'hero_button_hover_bg',
                'type' => 'select',
                'instructions' => 'Wähle die Hintergrundfarbe beim Hover',
                'required' => 1,
                'choices' => array_merge(array('white' => 'Weiß'), $color_choices),
                'default_value' => 'secondary',
                'wrapper' => array('width' => '25'),
            ),
            array(
                'key' => 'field_hero_button_hover_text',
                'label' => 'Button Hover Textfarbe',
                'name' => 'hero_button_hover_text',
                'type' => 'select',
                'instructions' => 'Wähle die Textfarbe beim Hover',
                'required' => 1,
                'choices' => array_merge(array('white' => 'Weiß'), $color_choices),
                'default_value' => 'white',
                'wrapper' => array('width' => '25'),
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