<?php
/**
 * ACF Fields for Styleguide Grid Block
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_grid',
    'title' => 'Styleguide Grid Block',
    'fields' => array(
        
        // =============================================================================
        // HEADLINE SECTION
        // =============================================================================
        array(
            'key' => 'field_sg_headline_text',
            'label' => 'Überschrift',
            'name' => 'sg_headline_text',
            'type' => 'text',
            'instructions' => 'Optionale Überschrift für den Grid-Block',
            'required' => 0,
            'wrapper' => array(
                'width' => '40',
            ),
        ),
        array(
            'key' => 'field_sg_headline_tag',
            'label' => 'HTML-Tag',
            'name' => 'sg_headline_tag',
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
            'key' => 'field_sg_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'sg_headline_size',
            'type' => 'select',
            'instructions' => 'Schriftgröße der Überschrift',
            'required' => 0,
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('24' => '24px (H2)'),
            'default_value' => '24',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_sg_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sg_headline_weight',
            'type' => 'select',
            'instructions' => 'Schriftgewicht der Überschrift',
            'required' => 0,
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('400' => 'Regular (400)'),
            'default_value' => '400',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_sg_headline_color',
            'label' => 'Farbe',
            'name' => 'sg_headline_color',
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
        // GRID ITEMS REPEATER
        // =============================================================================
        array(
            'key' => 'field_sg_grid_items',
            'label' => 'Grid-Items',
            'name' => 'sg_grid_items',
            'type' => 'repeater',
            'instructions' => 'SVG-Icons für das Grid hinzufügen. Jedes Icon wird in einer 150×150px Zelle mit 80×80px angezeigt.',
            'required' => 1,
            'min' => 1,
            'max' => 20,
            'layout' => 'block',
            'button_label' => 'Icon hinzufügen',
            'sub_fields' => array(
                array(
                    'key' => 'field_sg_icon_svg',
                    'label' => 'SVG-Icon',
                    'name' => 'icon_svg',
                    'type' => 'image',
                    'instructions' => 'SVG-Datei für das Icon (wird mit 80×80px angezeigt)',
                    'required' => 1,
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'mime_types' => 'svg',
                    'wrapper' => array(
                        'width' => '30',
                    ),
                ),
                array(
                    'key' => 'field_sg_icon_name',
                    'label' => 'Icon-Name',
                    'name' => 'icon_name',
                    'type' => 'text',
                    'instructions' => 'Beschriftung unter dem Icon',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '35',
                    ),
                ),
                array(
                    'key' => 'field_sg_show_download',
                    'label' => 'Download anzeigen',
                    'name' => 'show_download',
                    'type' => 'true_false',
                    'instructions' => 'Download-Link unter dem Icon anzeigen',
                    'required' => 0,
                    'default_value' => 0,
                    'ui' => 1,
                    'wrapper' => array(
                        'width' => '35',
                    ),
                ),
                array(
                    'key' => 'field_sg_download_text',
                    'label' => 'Download-Text',
                    'name' => 'download_text',
                    'type' => 'text',
                    'instructions' => 'Text für den Download-Link (Standard: "Download")',
                    'required' => 0,
                    'default_value' => 'Download',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_sg_show_download',
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
                    'key' => 'field_sg_download_file',
                    'label' => 'Download-Datei',
                    'name' => 'download_file',
                    'type' => 'file',
                    'instructions' => 'Datei für den Download (falls nicht angegeben, wird das SVG verwendet)',
                    'required' => 0,
                    'return_format' => 'array',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_sg_show_download',
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
                'value' => 'acf/styleguide-grid',
            ),
        ),
    ),
);
