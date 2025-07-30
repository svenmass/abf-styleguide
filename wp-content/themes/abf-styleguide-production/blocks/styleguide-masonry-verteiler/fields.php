<?php
/**
 * ACF Fields for Styleguide Masonry Verteiler Block
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_masonry_verteiler',
    'title' => 'Styleguide Masonry Verteiler Block',
    'fields' => array(
        
        // =============================================================================
        // HEADLINE SECTION
        // =============================================================================
        array(
            'key' => 'field_smv_headline_text',
            'label' => 'Überschrift',
            'name' => 'smv_headline_text',
            'type' => 'text',
            'instructions' => 'Optionale Überschrift für den Masonry-Verteiler',
            'required' => 0,
            'wrapper' => array(
                'width' => '40',
            ),
        ),
        array(
            'key' => 'field_smv_headline_tag',
            'label' => 'HTML-Tag',
            'name' => 'smv_headline_tag',
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
            'key' => 'field_smv_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'smv_headline_size',
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
            'key' => 'field_smv_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'smv_headline_weight',
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
            'key' => 'field_smv_headline_color',
            'label' => 'Farbe',
            'name' => 'smv_headline_color',
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
        // MASONRY ELEMENTS REPEATER
        // =============================================================================
        array(
            'key' => 'field_smv_masonry_elements',
            'label' => 'Masonry-Elemente',
            'name' => 'smv_masonry_elements',
            'type' => 'repeater',
            'instructions' => 'Elemente für das Masonry-Layout. 3-Spalten-System: Hochformat (4:6) nimmt Höhe von 2 Querformaten (4:3) ein.',
            'required' => 1,
            'min' => 1,
            'max' => 50,
            'layout' => 'block',
            'button_label' => 'Element hinzufügen',
            'collapsed' => 'field_smv_element_format',
            'sub_fields' => array(
                
                // Format Selection
                array(
                    'key' => 'field_smv_element_format',
                    'label' => 'Format',
                    'name' => 'element_format',
                    'type' => 'select',
                    'instructions' => 'Wählen Sie das Format für dieses Element',
                    'required' => 1,
                    'choices' => array(
                        'landscape' => 'Querformat (4:3)',
                        'portrait' => 'Hochformat (4:6 - nimmt 2 Querformat-Höhen)',
                    ),
                    'default_value' => 'landscape',
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                
                // Content Type Selection
                array(
                    'key' => 'field_smv_content_type',
                    'label' => 'Inhalts-Typ',
                    'name' => 'content_type',
                    'type' => 'select',
                    'instructions' => 'Wählen Sie den Typ des Inhalts',
                    'required' => 1,
                    'choices' => array(
                        'image' => 'Bild',
                        'color' => 'Farbfläche',
                    ),
                    'default_value' => 'image',
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                
                // Image Field (conditional)
                array(
                    'key' => 'field_smv_element_image',
                    'label' => 'Bild',
                    'name' => 'element_image',
                    'type' => 'image',
                    'instructions' => 'Hauptbild für das Element',
                    'required' => 1,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_smv_content_type',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                
                // Color Field (conditional)
                array(
                    'key' => 'field_smv_element_color',
                    'label' => 'Hintergrundfarbe',
                    'name' => 'element_color',
                    'type' => 'select',
                    'instructions' => 'Farbe der Farbfläche',
                    'required' => 1,
                    'choices' => $color_choices,
                    'default_value' => 'primary',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_smv_content_type',
                                'operator' => '==',
                                'value' => 'color',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                
                // Text Overlay
                array(
                    'key' => 'field_smv_element_text',
                    'label' => 'Text-Overlay',
                    'name' => 'element_text',
                    'type' => 'text',
                    'instructions' => 'Text der über dem Bild/der Farbfläche angezeigt wird (unten links)',
                    'required' => 0,
                    'placeholder' => 'z.B. "Jetzt entdecken"',
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                
                // Text Color
                array(
                    'key' => 'field_smv_text_color',
                    'label' => 'Textfarbe',
                    'name' => 'text_color',
                    'type' => 'select',
                    'instructions' => 'Farbe des Text-Overlays',
                    'required' => 0,
                    'choices' => array_merge(
                        array('white' => 'Weiß (Standard)'),
                        $color_choices
                    ),
                    'default_value' => 'white',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_smv_element_text',
                                'operator' => '!=',
                                'value' => '',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '25',
                    ),
                ),
                
                // Text Size
                array(
                    'key' => 'field_smv_text_size',
                    'label' => 'Textgröße',
                    'name' => 'text_size',
                    'type' => 'select',
                    'instructions' => 'Schriftgröße des Text-Overlays',
                    'required' => 0,
                    'choices' => function_exists('abf_get_typography_font_sizes') 
                        ? abf_get_typography_font_sizes() 
                        : array(
                            '14' => '14px (Klein)',
                            '16' => '16px (Normal)', 
                            '18' => '18px (Groß)',
                            '20' => '20px (Sehr groß)'
                        ),
                    'default_value' => '18',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_smv_element_text',
                                'operator' => '!=',
                                'value' => '',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '25',
                    ),
                ),
                
                // Link Selection (WordPress Pages and Posts)
                array(
                    'key' => 'field_smv_element_link',
                    'label' => 'Link zu Seite/Beitrag',
                    'name' => 'element_link',
                    'type' => 'post_object',
                    'instructions' => 'Verlinkung zu einer WordPress-Seite oder einem Beitrag',
                    'required' => 0,
                    'post_type' => array('page', 'post'),
                    'taxonomy' => '',
                    'allow_null' => 1,
                    'multiple' => 0,
                    'return_format' => 'object',
                    'ui' => 1,
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                
                // Alt Text Override
                array(
                    'key' => 'field_smv_alt_text',
                    'label' => 'Alt-Text Override',
                    'name' => 'alt_text',
                    'type' => 'text',
                    'instructions' => 'Überschreibt den Alt-Text für Barrierefreiheit',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_smv_content_type',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '50',
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
                'value' => 'acf/styleguide-masonry-verteiler',
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
    'description' => '',
); 