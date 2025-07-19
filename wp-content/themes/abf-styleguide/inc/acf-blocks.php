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
        'white' => 'Weiß',
        'black' => 'Schwarz',
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
        'mode'              => 'auto',
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
        'mode'              => 'auto',
    ));

    // Register Parallax Grid Block
    acf_register_block_type(array(
        'name'              => 'parallax-grid',
        'title'             => __('Parallax Grid'),
        'description'       => __('Ein bildschirmfüllender Block mit 6 Elementen in parallax Grid-Layout'),
        'render_template'   => get_template_directory() . '/blocks/parallax-grid/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'grid-view',
        'keywords'          => array('parallax', 'grid', 'gallery', 'mosaic'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));

    // Register Parallax Content Block
    acf_register_block_type(array(
        'name'              => 'parallax-content',
        'title'             => __('Parallax Content'),
        'description'       => __('Ein bildschirmfüllender Block mit unbegrenzten Content-Elementen (Text links, weißes Layer rechts)'),
        'render_template'   => get_template_directory() . '/blocks/parallax-content/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'align-left',
        'keywords'          => array('parallax', 'content', 'text', 'media', 'repeater'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));

    // Register Parallax Element Block (NEW)
    acf_register_block_type(array(
        'name'              => 'parallax-element',
        'title'             => __('Parallax Element'),
        'description'       => __('Einzelnes Parallax Content Element mit konfigurierbarem Sticky-Verhalten'),
        'render_template'   => get_template_directory() . '/blocks/parallax-element/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'admin-page',
        'keywords'          => array('parallax', 'sticky', 'content', 'hero', 'element'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));

    // Register Text Block (NEW)
    acf_register_block_type(array(
        'name'              => 'text-block',
        'title'             => __('Text Block'),
        'description'       => __('Einfacher Text-Block mit Sticky-Verhalten und optionaler voller Höhe'),
        'render_template'   => get_template_directory() . '/blocks/text-block/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'editor-alignleft',
        'keywords'          => array('text', 'sticky', 'content', 'headline', 'fullwidth'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));

    // Register Styleguide Text Element Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-text-element',
        'title'             => __('Styleguide-Textelement'),
        'description'       => __('Ein konfigurierbares Textelement mit Headline, Text und Button (max. 840px Breite)'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-text-element/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'editor-textcolor',
        'keywords'          => array('text', 'headline', 'button', 'styleguide', 'content'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Bild-Text Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-bild-text',
        'title'             => __('Styleguide-Bild-Text'),
        'description'       => __('Ein zweispaltiges Element mit Bild und Text in konfigurierbaren Spaltenaufteilungen'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-bild-text/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'format-image',
        'keywords'          => array('bild', 'text', 'image', 'zweispaltig', 'layout', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Trennlinie Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-trennlinie',
        'title'             => __('Styleguide-Trennlinie'),
        'description'       => __('Eine konfigurierbare Trennlinie mit verschiedenen Stärken, Farben und Breiten'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-trennlinie/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'minus',
        'keywords'          => array('trennlinie', 'separator', 'divider', 'linie', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Akkordeon Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-akkordeon',
        'title'             => __('Styleguide-Akkordeon'),
        'description'       => __('Ein konfigurierbares Akkordeon-Element mit Headline, Text und aufklappbaren Elementen'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-akkordeon/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'list-view',
        'keywords'          => array('akkordeon', 'accordion', 'faq', 'aufklappbar', 'toggle', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Posts Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-posts',
        'title'             => __('Styleguide Posts'),
        'description'       => __('Zeigt Posts in einem Card-Grid an'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-posts/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'grid-view',
        'keywords'          => array('posts', 'cards', 'grid', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Similar Posts Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-similar',
        'title'             => __('Styleguide Similar Posts'),
        'description'       => __('Zeigt ähnliche/verwandte Posts in einem Card-Grid an'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-similar/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'slides',
        'keywords'          => array('similar', 'related', 'posts', 'cards', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Einzelbild Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-einzelbild',
        'title'             => __('Styleguide-Einzelbild'),
        'description'       => __('Ein einzelnes Bild im 16:9 Format mit Lightbox und Download-Funktionalität'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-einzelbild/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'format-image',
        'keywords'          => array('bild', 'image', 'einzelbild', 'lightbox', 'download', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Grid Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-grid',
        'title'             => __('Styleguide-Grid'),
        'description'       => __('150×150px Grid mit 80×80px SVG-Icons, Beschriftungen und Download-Links'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-grid/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'grid-view',
        'keywords'          => array('grid', 'svg', 'icons', 'download', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
    
    // Register Styleguide Masonry Block (NEW)
    acf_register_block_type(array(
        'name'              => 'styleguide-masonry',
        'title'             => __('Styleguide-Masonry'),
        'description'       => __('Masonry-Layout für Bilder mit Lightbox und Download-Funktionalität'),
        'render_template'   => get_template_directory() . '/blocks/styleguide-masonry/template.php',
        'category'          => 'abf-blocks',
        'icon'              => 'images-alt2',
        'keywords'          => array('masonry', 'bilder', 'gallery', 'lightbox', 'download', 'styleguide'),
        'supports'          => array(
            'jsx' => true,
        ),
        'mode'              => 'auto',
    ));
}

/**
 * Include modular field definitions
 */
function abf_include_modular_fields() {
    // Include Parallax Content Block fields
    $parallax_content_fields = get_template_directory() . '/blocks/parallax-content/fields.php';
    if (file_exists($parallax_content_fields)) {
        require_once $parallax_content_fields;
    }
    
    // Include Parallax Element Block fields (NEW)
    $parallax_element_fields = get_template_directory() . '/blocks/parallax-element/fields.php';
    if (file_exists($parallax_element_fields)) {
        require_once $parallax_element_fields;
    }
    
    // Include Styleguide Text Element Block fields
    $styleguide_text_fields = get_template_directory() . '/blocks/styleguide-text-element/fields.php';
    if (file_exists($styleguide_text_fields)) {
        $field_group = require $styleguide_text_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
    
    // Include Styleguide Bild-Text Block fields
    $styleguide_bild_text_fields = get_template_directory() . '/blocks/styleguide-bild-text/fields.php';
    if (file_exists($styleguide_bild_text_fields)) {
        $field_group = require $styleguide_bild_text_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
    
    // Include Styleguide Trennlinie Block fields
    $styleguide_trennlinie_fields = get_template_directory() . '/blocks/styleguide-trennlinie/fields.php';
    if (file_exists($styleguide_trennlinie_fields)) {
        $field_group = require $styleguide_trennlinie_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
    
    // Include Styleguide Akkordeon Block fields
    $styleguide_akkordeon_fields = get_template_directory() . '/blocks/styleguide-akkordeon/fields.php';
    if (file_exists($styleguide_akkordeon_fields)) {
        $field_group = require $styleguide_akkordeon_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
    
    // Include Styleguide Posts Block fields
    $styleguide_posts_fields = get_template_directory() . '/blocks/styleguide-posts/fields.php';
    if (file_exists($styleguide_posts_fields)) {
        $field_group = require $styleguide_posts_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
    
    // Include Styleguide Einzelbild Block fields
    $styleguide_einzelbild_fields = get_template_directory() . '/blocks/styleguide-einzelbild/fields.php';
    if (file_exists($styleguide_einzelbild_fields)) {
        $field_group = require $styleguide_einzelbild_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
    
    // Include Styleguide Grid Block fields
    $styleguide_grid_fields = get_template_directory() . '/blocks/styleguide-grid/fields.php';
    if (file_exists($styleguide_grid_fields)) {
        $field_group = require $styleguide_grid_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
    
    // Include Styleguide Masonry Block fields
    $styleguide_masonry_fields = get_template_directory() . '/blocks/styleguide-masonry/fields.php';
    if (file_exists($styleguide_masonry_fields)) {
        $field_group = require $styleguide_masonry_fields;
        if (is_array($field_group)) {
            acf_add_local_field_group($field_group);
        }
    }
}
add_action('acf/init', 'abf_include_modular_fields');

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
                    '48' => '48px (XL)',
                    '60' => '60px (XXL)',
                    '72' => '72px (3XL)',
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
                    '500' => 'Medium (500)',
                    '600' => 'Semi-Bold (600)',
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
                    '500' => 'Medium (500)',
                    '600' => 'Semi-Bold (600)',
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
                    '48' => '48px (XL)',
                    '60' => '60px (XXL)',
                    '72' => '72px (3XL)',
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
                    '500' => 'Medium (500)',
                    '600' => 'Semi-Bold (600)',
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
                    '36' => '36px (H1)',
                    '48' => '48px (XL)',
                    '60' => '60px (XXL)',
                    '72' => '72px (3XL)',
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

    // Parallax Grid Block Field Group mit Repeater (effizienter als 120 einzelne Felder)
    acf_add_local_field_group(array(
        'key' => 'group_parallax_grid_block',
        'title' => 'Parallax Grid Block Felder',
        'fields' => array(
            
            // =============================================================================
            // STICKY EINSTELLUNGEN
            // =============================================================================
            array(
                'key' => 'field_pg_sticky_tab',
                'label' => 'Sticky Einstellungen',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_pg_enable_sticky',
                'label' => 'Sticky aktivieren',
                'name' => 'grid_enable_sticky',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'instructions' => 'Block wird beim Scrollen an der angegebenen Position fixiert',
            ),
            array(
                'key' => 'field_pg_sticky_position',
                'label' => 'Sticky Position',
                'name' => 'grid_sticky_position',
                'type' => 'number',
                'default_value' => 0,
                'min' => 0,
                'max' => 500,
                'step' => 10,
                'append' => 'px',
                'instructions' => 'Abstand vom oberen Bildschirmrand (0px = ganz oben)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pg_enable_sticky',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_pg_z_index',
                'label' => 'Z-Index (Stapelreihenfolge)',
                'name' => 'grid_z_index',
                'type' => 'number',
                'default_value' => 1000,
                'min' => 1,
                'max' => 9999,
                'step' => 1,
                'instructions' => 'Höhere Werte überdecken niedrigere (1000, 1001, 1002...) - auch ohne Sticky aktiv',
            ),
            array(
                'key' => 'field_pg_sticky_mobile',
                'label' => 'Sticky auf Mobile deaktivieren',
                'name' => 'grid_sticky_mobile_disable',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
                'instructions' => 'Empfohlen: Sticky-Effekte auf kleinen Bildschirmen deaktivieren',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_pg_enable_sticky',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            
            // =============================================================================
            // DESIGN EINSTELLUNGEN
            // =============================================================================
            array(
                'key' => 'field_pg_design_tab',
                'label' => 'Design',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_pg_container_background_color',
                'label' => 'Container Hintergrundfarbe',
                'name' => 'grid_container_background_color',
                'type' => 'select',
                'choices' => array_merge(array('#ffffff' => 'Weiß (Standard)'), abf_get_color_choices()),
                'default_value' => '#ffffff',
                'allow_null' => 0,
                'return_format' => 'value',
                'instructions' => 'Hintergrundfarbe des gesamten Grid-Containers',
            ),
            
            // =============================================================================
            // GRID ELEMENTE
            // =============================================================================
            array(
                'key' => 'field_pg_elements_tab',
                'label' => 'Grid Elemente',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_parallax_elements',
                'label' => 'Grid Elemente',
                'name' => 'parallax_elements',
                'type' => 'repeater',
                'instructions' => 'Konfiguriere die 6 Grid-Elemente. Die Reihenfolge entspricht: El1, El2, El3, El4, El5, El6',
                'required' => 0,
                'conditional_logic' => 0,
                'min' => 6,
                'max' => 6,
                'layout' => 'block',
                'button_label' => 'Element hinzufügen',
                'sub_fields' => array(
                    // Background Settings
                    array(
                        'key' => 'field_element_background_type',
                        'label' => 'Hintergrund Typ',
                        'name' => 'background_type',
                        'type' => 'radio',
                        'instructions' => 'Wähle zwischen Farbe oder Bild',
                        'required' => 1,
                        'choices' => array(
                            'color' => 'Hintergrundfarbe',
                            'image' => 'Hintergrund-Bild',
                            'video' => 'Hintergrund-Video',
                        ),
                        'default_value' => 'color',
                        'layout' => 'horizontal',
                    ),
                    array(
                        'key' => 'field_element_background_color',
                        'label' => 'Hintergrundfarbe',
                        'name' => 'background_color',
                        'type' => 'select',
                        'instructions' => 'Wähle eine Hintergrundfarbe aus den Theme-Farben',
                        'required' => 0,
                        'choices' => abf_get_color_choices(),
                        'default_value' => 'primary',
                        'allow_null' => 0,
                        'return_format' => 'value',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_background_type',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_element_background_image',
                        'label' => 'Hintergrund-Bild',
                        'name' => 'background_image',
                        'type' => 'image',
                        'instructions' => 'Wähle ein Hintergrund-Bild',
                        'required' => 0,
                        'return_format' => 'id',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_background_type',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_element_background_video',
                        'label' => 'Hintergrund-Video',
                        'name' => 'background_video',
                        'type' => 'file',
                        'instructions' => 'Wähle ein Hintergrund-Video (MP4 empfohlen, läuft automatisch in Schleife)',
                        'required' => 0,
                        'return_format' => 'array',
                        'mime_types' => 'mp4,webm,ogg',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_background_type',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                            ),
                        ),
                    ),
                    
                    // Plus Overlay Settings
                    array(
                        'key' => 'field_element_show_plus',
                        'label' => 'Plus anzeigen',
                        'name' => 'show_plus',
                        'type' => 'true_false',
                        'instructions' => 'Soll ein Plus-Icon im rechten unteren Eck angezeigt werden?',
                        'default_value' => 0,
                    ),
                    array(
                        'key' => 'field_element_plus_type',
                        'label' => 'Plus Icon Typ',
                        'name' => 'plus_type',
                        'type' => 'radio',
                        'instructions' => 'Wähle zwischen Default SVG oder eigenem Icon',
                        'choices' => array(
                            'default' => 'Standard Plus SVG',
                            'custom' => 'Eigenes Icon (PNG/SVG)',
                        ),
                        'default_value' => 'default',
                        'layout' => 'horizontal',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_plus',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_element_plus_icon',
                        'label' => 'Plus Icon',
                        'name' => 'plus_icon',
                        'type' => 'image',
                        'instructions' => 'Wähle ein eigenes Plus-Icon (PNG oder SVG)',
                        'required' => 0,
                        'return_format' => 'array',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_plus',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                                array(
                                    'field' => 'field_element_plus_type',
                                    'operator' => '==',
                                    'value' => 'custom',
                                ),
                            ),
                        ),
                    ),
                    
                    // Headline Settings
                    array(
                        'key' => 'field_element_headline_text',
                        'label' => 'Headline',
                        'name' => 'headline_text',
                        'type' => 'textarea',
                        'instructions' => 'Gib hier die Hauptüberschrift ein (optional)',
                        'required' => 0,
                        'rows' => 2,
                    ),
                    array(
                        'key' => 'field_element_headline_tag',
                        'label' => 'Headline Tag',
                        'name' => 'headline_tag',
                        'type' => 'select',
                        'instructions' => 'HTML-Tag für die Headline',
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
                        'key' => 'field_element_headline_weight',
                        'label' => 'Headline Gewicht',
                        'name' => 'headline_weight',
                        'type' => 'select',
                        'instructions' => 'Schriftgewicht',
                        'choices' => array(
                            '300' => 'Light (300)',
                            '400' => 'Regular (400)',
                            '700' => 'Bold (700)',
                        ),
                        'default_value' => '400',
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_headline_size',
                        'label' => 'Headline Größe',
                        'name' => 'headline_size',
                        'type' => 'select',
                        'instructions' => 'Wähle die Schriftgröße',
                        'choices' => array(
                            '12' => '12px (Small)',
                            '18' => '18px (Body)', 
                            '24' => '24px (H2)',
                            '36' => '36px (H1)',
                            '48' => '48px (XL)',
                            '60' => '60px (XXL)',
                            '72' => '72px (3XL)',
                        ),
                        'default_value' => '24',
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_headline_color',
                        'label' => 'Headline Farbe',
                        'name' => 'headline_color',
                        'type' => 'select',
                        'instructions' => 'Textfarbe der Headline',
                        'choices' => abf_get_color_choices(),
                        'default_value' => 'inherit',
                        'wrapper' => array('width' => '25'),
                    ),
                    
                    // Subline Settings
                    array(
                        'key' => 'field_element_subline_text',
                        'label' => 'Subline',
                        'name' => 'subline_text',
                        'type' => 'textarea',
                        'instructions' => 'Gib hier die Unterüberschrift ein (optional)',
                        'required' => 0,
                        'rows' => 2,
                    ),
                    array(
                        'key' => 'field_element_subline_tag',
                        'label' => 'Subline Tag',
                        'name' => 'subline_tag',
                        'type' => 'select',
                        'instructions' => 'HTML-Tag für die Subline',
                        'choices' => array(
                            'h1' => 'H1',
                            'h2' => 'H2',
                            'h3' => 'H3',
                            'h4' => 'H4',
                            'h5' => 'H5',
                            'h6' => 'H6',
                            'p' => 'Paragraph',
                        ),
                        'default_value' => 'p',
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_subline_weight',
                        'label' => 'Subline Gewicht',
                        'name' => 'subline_weight',
                        'type' => 'select',
                        'instructions' => 'Schriftgewicht',
                        'choices' => array(
                            '300' => 'Light (300)',
                            '400' => 'Regular (400)',
                            '700' => 'Bold (700)',
                        ),
                        'default_value' => '400',
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_subline_size',
                        'label' => 'Subline Größe',
                        'name' => 'subline_size',
                        'type' => 'select',
                        'instructions' => 'Wähle die Schriftgröße',
                        'choices' => array(
                            '12' => '12px (Small)',
                            '18' => '18px (Body)', 
                            '24' => '24px (H2)',
                            '36' => '36px (H1)',
                            '48' => '48px (XL)',
                            '60' => '60px (XXL)',
                            '72' => '72px (3XL)',
                        ),
                        'default_value' => '18',
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_subline_color',
                        'label' => 'Subline Farbe',
                        'name' => 'subline_color',
                        'type' => 'select',
                        'instructions' => 'Textfarbe der Subline',
                        'choices' => abf_get_color_choices(),
                        'default_value' => 'inherit',
                        'wrapper' => array('width' => '25'),
                    ),
                    
                    // Button Settings
                    array(
                        'key' => 'field_element_show_button',
                        'label' => 'Button anzeigen',
                        'name' => 'show_button',
                        'type' => 'true_false',
                        'instructions' => 'Soll ein Button angezeigt werden?',
                        'default_value' => 0,
                    ),
                    array(
                        'key' => 'field_element_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'instructions' => 'Text für den Button',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_button',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                        'wrapper' => array('width' => '50'),
                    ),
                    array(
                        'key' => 'field_element_button_url',
                        'label' => 'Button Link',
                        'name' => 'button_url',
                        'type' => 'link',
                        'instructions' => 'Wähle ein Linkziel aus Seiten, Beiträgen oder externe URL',
                        'return_format' => 'array',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_button',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                        'wrapper' => array('width' => '50'),
                    ),
                    array(
                        'key' => 'field_element_button_bg_color',
                        'label' => 'Button Hintergrund',
                        'name' => 'button_bg_color',
                        'type' => 'select',
                        'instructions' => 'Hintergrundfarbe des Buttons',
                        'choices' => abf_get_color_choices(),
                        'default_value' => 'primary',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_button',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_button_text_color',
                        'label' => 'Button Text',
                        'name' => 'button_text_color',
                        'type' => 'select',
                        'instructions' => 'Textfarbe des Buttons',
                        'choices' => abf_get_color_choices(),
                        'default_value' => 'inherit',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_button',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_button_hover_bg_color',
                        'label' => 'Button Hover BG',
                        'name' => 'button_hover_bg_color',
                        'type' => 'select',
                        'instructions' => 'Hover-Hintergrundfarbe',
                        'choices' => abf_get_color_choices(),
                        'default_value' => 'secondary',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_button',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                        'wrapper' => array('width' => '25'),
                    ),
                    array(
                        'key' => 'field_element_button_hover_text_color',
                        'label' => 'Button Hover Text',
                        'name' => 'button_hover_text_color',
                        'type' => 'select',
                        'instructions' => 'Hover-Textfarbe',
                        'choices' => abf_get_color_choices(),
                        'default_value' => 'inherit',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_element_show_button',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                        'wrapper' => array('width' => '25'),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/parallax-grid',
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
        'description' => 'Felder für den Parallax Grid Block mit 6 konfigurierbaren Elementen',
    ));

    // Text Block Field Group (NEW)
    acf_add_local_field_group(array(
        'key' => 'group_text_block',
        'title' => 'Text Block Felder',
        'fields' => array(
            
            // =============================================================================
            // STICKY EINSTELLUNGEN
            // =============================================================================
            array(
                'key' => 'field_tb_sticky_tab',
                'label' => 'Sticky Einstellungen',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_tb_enable_sticky',
                'label' => 'Sticky aktivieren',
                'name' => 'tb_enable_sticky',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'instructions' => 'Block wird beim Scrollen an der angegebenen Position fixiert',
            ),
            array(
                'key' => 'field_tb_sticky_position',
                'label' => 'Sticky Position',
                'name' => 'tb_sticky_position',
                'type' => 'number',
                'default_value' => 0,
                'min' => 0,
                'max' => 500,
                'step' => 10,
                'append' => 'px',
                'instructions' => 'Abstand vom oberen Bildschirmrand (0px = ganz oben)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_enable_sticky',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_z_index',
                'label' => 'Z-Index (Stapelreihenfolge)',
                'name' => 'tb_z_index',
                'type' => 'number',
                'default_value' => 1000,
                'min' => 1,
                'max' => 9999,
                'step' => 1,
                'instructions' => 'Höhere Werte überdecken niedrigere (1000, 1001, 1002...)',
            ),
            array(
                'key' => 'field_tb_sticky_mobile',
                'label' => 'Sticky auf Mobile deaktivieren',
                'name' => 'tb_sticky_mobile_disable',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
                'instructions' => 'Empfohlen: Sticky-Effekte auf kleinen Bildschirmen deaktivieren',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_enable_sticky',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            
            // =============================================================================
            // DESIGN EINSTELLUNGEN
            // =============================================================================
            array(
                'key' => 'field_tb_design_tab',
                'label' => 'Design',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_tb_background_color',
                'label' => 'Hintergrundfarbe',
                'name' => 'tb_background_color',
                'type' => 'select',
                'choices' => $color_choices,
                'default_value' => 'primary',
                'allow_null' => 0,
                'return_format' => 'value',
                'instructions' => 'Hintergrundfarbe des Text-Blocks',
            ),
            array(
                'key' => 'field_tb_full_height',
                'label' => 'Volle Höhe',
                'name' => 'tb_full_height',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'instructions' => 'Block nimmt die volle Viewport-Höhe ein (100vh). Text bleibt linksbündig und top-aligned.',
            ),
            
            // =============================================================================
            // CONTENT EINSTELLUNGEN
            // =============================================================================
            array(
                'key' => 'field_tb_content_tab',
                'label' => 'Inhalt',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            
            // Headline
            array(
                'key' => 'field_tb_headline_text',
                'label' => 'Headline',
                'name' => 'tb_headline_text',
                'type' => 'text',
                'instructions' => 'Hauptüberschrift des Blocks',
            ),
            array(
                'key' => 'field_tb_headline_tag',
                'label' => 'Headline Tag',
                'name' => 'tb_headline_tag',
                'type' => 'select',
                'choices' => array(
                    'h1' => 'H1',
                    'h2' => 'H2 (Standard)',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ),
                'default_value' => 'h2',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_headline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_headline_weight',
                'label' => 'Headline Schriftdicke',
                'name' => 'tb_headline_weight',
                'type' => 'select',
                'choices' => array(
                    '300' => 'Light (300)',
                    '400' => 'Normal (400)',
                    '500' => 'Medium (500)',
                    '600' => 'Semi-Bold (600)',
                    '700' => 'Bold (700)',
                ),
                'default_value' => '400',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_headline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_headline_size',
                'label' => 'Headline Schriftgröße',
                'name' => 'tb_headline_size',
                'type' => 'select',
                'choices' => array(
                    '12' => '12px (Small)',
                    '18' => '18px (Body)', 
                    '24' => '24px (H2)',
                    '36' => '36px (H1)',
                    '48' => '48px (XL)',
                    '60' => '60px (XXL)',
                    '72' => '72px (3XL)',
                ),
                'default_value' => '36',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_headline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_headline_color',
                'label' => 'Headline Farbe',
                'name' => 'tb_headline_color',
                'type' => 'select',
                'choices' => $color_choices,
                'default_value' => 'white',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_headline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            
            // Subline (NEW)
            array(
                'key' => 'field_tb_subline_text',
                'label' => 'Subline',
                'name' => 'tb_subline_text',
                'type' => 'text',
                'instructions' => 'Unterüberschrift des Blocks (optional)',
            ),
            array(
                'key' => 'field_tb_subline_tag',
                'label' => 'Subline Tag',
                'name' => 'tb_subline_tag',
                'type' => 'select',
                'choices' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'Paragraph',
                ),
                'default_value' => 'h3',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_subline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_subline_weight',
                'label' => 'Subline Schriftdicke',
                'name' => 'tb_subline_weight',
                'type' => 'select',
                'choices' => array(
                    '300' => 'Light (300)',
                    '400' => 'Normal (400)',
                    '500' => 'Medium (500)',
                    '600' => 'Semi-Bold (600)',
                    '700' => 'Bold (700)',
                ),
                'default_value' => '400',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_subline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_subline_size',
                'label' => 'Subline Schriftgröße',
                'name' => 'tb_subline_size',
                'type' => 'select',
                'choices' => array(
                    '12' => '12px (Small)',
                    '18' => '18px (Body)', 
                    '24' => '24px (H2)',
                    '36' => '36px (H1)',
                    '48' => '48px (XL)',
                    '60' => '60px (XXL)',
                    '72' => '72px (3XL)',
                ),
                'default_value' => '24',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_subline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_subline_color',
                'label' => 'Subline Farbe',
                'name' => 'tb_subline_color',
                'type' => 'select',
                'choices' => $color_choices,
                'default_value' => 'white',
                'wrapper' => array('width' => '25'),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_subline_text',
                            'operator' => '!=',
                            'value' => '',
                        ),
                    ),
                ),
            ),
            
            // Fließtext
            array(
                'key' => 'field_tb_richtext_content',
                'label' => 'Fließtext',
                'name' => 'tb_richtext_content',
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'delay' => 0,
                'instructions' => 'Haupttext des Blocks. Unterstützt grundlegende Formatierung.',
            ),
            
            // Button
            array(
                'key' => 'field_tb_show_button',
                'label' => 'Button anzeigen',
                'name' => 'tb_show_button',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
            ),
            array(
                'key' => 'field_tb_button_text',
                'label' => 'Button Text',
                'name' => 'tb_button_text',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_show_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_button_url',
                'label' => 'Button URL',
                'name' => 'tb_button_url',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_show_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_button_bg_color',
                'label' => 'Button Hintergrundfarbe',
                'name' => 'tb_button_bg_color',
                'type' => 'select',
                'choices' => $color_choices,
                'default_value' => 'secondary',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_show_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_button_text_color',
                'label' => 'Button Textfarbe',
                'name' => 'tb_button_text_color',
                'type' => 'select',
                'choices' => $color_choices,
                'default_value' => 'white',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_show_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_button_hover_bg_color',
                'label' => 'Button Hover Hintergrundfarbe',
                'name' => 'tb_button_hover_bg_color',
                'type' => 'select',
                'choices' => $color_choices,
                'default_value' => 'primary',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_show_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_tb_button_hover_text_color',
                'label' => 'Button Hover Textfarbe',
                'name' => 'tb_button_hover_text_color',
                'type' => 'select',
                'choices' => $color_choices,
                'default_value' => 'white',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_tb_show_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/text-block',
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
        'description' => 'Felder für den Text-Block mit Sticky-Funktionalität',
    ));

    // Styleguide Text Element Block Field Group
    // Load modular field definitions
    abf_include_modular_fields();
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

/**
 * Add ACF field groups for new blocks
 */
add_action('acf/init', 'abf_register_card_blocks_fields');

function abf_register_card_blocks_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Styleguide Posts Block Field Group
    acf_add_local_field_group(array(
        'key' => 'group_styleguide_posts',
        'title' => 'Styleguide Posts Block Felder',
        'fields' => array(
            array(
                'key' => 'field_posts_to_show',
                'label' => 'Anzahl Posts',
                'name' => 'posts_to_show',
                'type' => 'number',
                'instructions' => 'Wie viele Posts sollen angezeigt werden?',
                'default_value' => 6,
                'min' => 1,
                'max' => 20,
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_posts_columns',
                'label' => 'Spalten',
                'name' => 'columns',
                'type' => 'select',
                'instructions' => 'Anzahl der Spalten im Grid',
                'choices' => array(
                    '2' => '2 Spalten',
                    '3' => '3 Spalten',
                    '4' => '4 Spalten',
                ),
                'default_value' => '3',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_posts_post_types',
                'label' => 'Post Types',
                'name' => 'post_types',
                'type' => 'select',
                'instructions' => 'Welche Post Types sollen angezeigt werden?',
                'choices' => array(
                    'post' => 'Beiträge',
                    'page' => 'Seiten',
                ),
                'default_value' => array('post'),
                'multiple' => 1,
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_posts_categories',
                'label' => 'Kategorien',
                'name' => 'categories',
                'type' => 'taxonomy',
                'instructions' => 'Nur Posts aus bestimmten Kategorien anzeigen (leer = alle)',
                'taxonomy' => 'category',
                'field_type' => 'multi_select',
                'multiple' => 1,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_posts_tags',
                'label' => 'Tags',
                'name' => 'tags',
                'type' => 'taxonomy',
                'instructions' => 'Nur Posts mit bestimmten Tags anzeigen (leer = alle)',
                'taxonomy' => 'post_tag',
                'field_type' => 'multi_select',
                'multiple' => 1,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_posts_orderby',
                'label' => 'Sortierung',
                'name' => 'orderby',
                'type' => 'select',
                'instructions' => 'Nach was soll sortiert werden?',
                'choices' => array(
                    'date' => 'Datum',
                    'title' => 'Titel',
                    'menu_order' => 'Reihenfolge',
                    'rand' => 'Zufällig',
                ),
                'default_value' => 'date',
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_posts_order',
                'label' => 'Reihenfolge',
                'name' => 'order',
                'type' => 'select',
                'instructions' => 'Aufsteigend oder absteigend?',
                'choices' => array(
                    'DESC' => 'Absteigend',
                    'ASC' => 'Aufsteigend',
                ),
                'default_value' => 'DESC',
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_posts_show_title',
                'label' => 'Block-Titel anzeigen',
                'name' => 'show_title',
                'type' => 'true_false',
                'instructions' => 'Soll eine Überschrift über dem Block angezeigt werden?',
                'default_value' => 0,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_posts_block_title',
                'label' => 'Block-Titel',
                'name' => 'block_title',
                'type' => 'text',
                'instructions' => 'Titel für den Block',
                'default_value' => 'Aktuelle Beiträge',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_posts_show_title',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array('width' => '50'),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/styleguide-posts',
                ),
            ),
        ),
    ));
    
    // Styleguide Similar Posts Block Field Group
    acf_add_local_field_group(array(
        'key' => 'group_styleguide_similar',
        'title' => 'Styleguide Similar Posts Block Felder',
        'fields' => array(
            array(
                'key' => 'field_similar_posts_to_show',
                'label' => 'Anzahl Posts',
                'name' => 'posts_to_show',
                'type' => 'number',
                'instructions' => 'Wie viele ähnliche Posts sollen angezeigt werden?',
                'default_value' => 3,
                'min' => 1,
                'max' => 12,
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_similar_columns',
                'label' => 'Spalten',
                'name' => 'columns',
                'type' => 'select',
                'instructions' => 'Anzahl der Spalten im Grid',
                'choices' => array(
                    '2' => '2 Spalten',
                    '3' => '3 Spalten',
                    '4' => '4 Spalten',
                ),
                'default_value' => '3',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_similar_base_post',
                'label' => 'Basis-Post',
                'name' => 'base_post',
                'type' => 'post_object',
                'instructions' => 'Zu welchem Post sollen ähnliche Posts gefunden werden? (leer = aktueller Post)',
                'post_type' => array('post'),
                'multiple' => 0,
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_similar_show_title',
                'label' => 'Block-Titel anzeigen',
                'name' => 'show_title',
                'type' => 'true_false',
                'instructions' => 'Soll eine Überschrift über dem Block angezeigt werden?',
                'default_value' => 1,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_similar_block_title',
                'label' => 'Block-Titel',
                'name' => 'block_title',
                'type' => 'text',
                'instructions' => 'Titel für den Block',
                'default_value' => 'Ähnliche Beiträge',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_similar_show_title',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array('width' => '50'),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/styleguide-similar',
                ),
            ),
        ),
    ));
} 