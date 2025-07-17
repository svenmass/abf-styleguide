<?php
/**
 * ACF Fields for Styleguide Masonry Block
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_masonry',
    'title' => 'Styleguide Masonry Block',
    'fields' => array(
        
        // =============================================================================
        // HEADLINE SECTION
        // =============================================================================
        array(
            'key' => 'field_sm_headline_text',
            'label' => 'Überschrift',
            'name' => 'sm_headline_text',
            'type' => 'text',
            'instructions' => 'Optionale Überschrift für den Masonry-Block',
            'required' => 0,
            'wrapper' => array(
                'width' => '40',
            ),
        ),
        array(
            'key' => 'field_sm_headline_tag',
            'label' => 'HTML-Tag',
            'name' => 'sm_headline_tag',
            'type' => 'select',
            'instructions' => 'HTML-Tag für die Überschrift',
            'required' => 0,
            'choices' => array(
                'h1' => 'H1',
                'h2' => 'H2 (Standard)',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
            ),
            'default_value' => 'h2',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_sm_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'sm_headline_size',
            'type' => 'select',
            'instructions' => 'Schriftgröße der Überschrift',
            'required' => 0,
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
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_sm_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sm_headline_weight',
            'type' => 'select',
            'instructions' => 'Schriftgewicht der Überschrift',
            'required' => 0,
            'choices' => array(
                '300' => 'Light (300)',
                '400' => 'Regular (400)',
                '500' => 'Medium (500)',
                '600' => 'Semi-Bold (600)',
                '700' => 'Bold (700)',
            ),
            'default_value' => '400',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_sm_headline_color',
            'label' => 'Farbe',
            'name' => 'sm_headline_color',
            'type' => 'select',
            'instructions' => 'Farbe der Überschrift',
            'required' => 0,
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        
        // =============================================================================
        // MASONRY IMAGES REPEATER
        // =============================================================================
        array(
            'key' => 'field_sm_masonry_images',
            'label' => 'Masonry-Bilder',
            'name' => 'sm_masonry_images',
            'type' => 'repeater',
            'instructions' => 'Bilder für das Masonry-Layout hinzufügen. Alle Bilder werden in einem responsiven Masonry-Layout angezeigt.',
            'required' => 1,
            'min' => 1,
            'max' => 50,
            'layout' => 'block',
            'button_label' => 'Bild hinzufügen',
            'sub_fields' => array(
                array(
                    'key' => 'field_sm_image',
                    'label' => 'Bild',
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => 'Hauptbild für das Masonry-Layout',
                    'required' => 1,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'wrapper' => array(
                        'width' => '30',
                    ),
                ),
                array(
                    'key' => 'field_sm_caption',
                    'label' => 'Bildunterschrift',
                    'name' => 'caption',
                    'type' => 'text',
                    'instructions' => 'Optionale Bildunterschrift (wird in der Lightbox angezeigt)',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '35',
                    ),
                ),
                array(
                    'key' => 'field_sm_alt_text',
                    'label' => 'Alt-Text Override',
                    'name' => 'alt_text',
                    'type' => 'text',
                    'instructions' => 'Überschreibt den Alt-Text des Bildes (für Barrierefreiheit)',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '35',
                    ),
                ),
                array(
                    'key' => 'field_sm_show_download',
                    'label' => 'Download anzeigen',
                    'name' => 'show_download',
                    'type' => 'true_false',
                    'instructions' => 'Download-Button in der Lightbox anzeigen',
                    'required' => 0,
                    'default_value' => 0,
                    'ui' => 1,
                    'wrapper' => array(
                        'width' => '35',
                    ),
                ),
                array(
                    'key' => 'field_sm_download_text',
                    'label' => 'Download-Text',
                    'name' => 'download_text',
                    'type' => 'text',
                    'instructions' => 'Text für den Download-Button (Standard: "Download")',
                    'required' => 0,
                    'default_value' => 'Download',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_sm_show_download',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '35',
                    ),
                ),
                array(
                    'key' => 'field_sm_download_file',
                    'label' => 'Download-Datei',
                    'name' => 'download_file',
                    'type' => 'file',
                    'instructions' => 'Alternative hochauflösende Datei für den Download (falls nicht angegeben, wird das Originalbild verwendet)',
                    'required' => 0,
                    'return_format' => 'array',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_sm_show_download',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '30',
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
                'value' => 'acf/styleguide-masonry',
            ),
        ),
    ),
); 