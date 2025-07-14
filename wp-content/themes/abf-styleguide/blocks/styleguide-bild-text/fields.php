<?php
/**
 * ACF Fields for Styleguide Bild-Text Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_bild_text',
    'title' => 'Styleguide Bild-Text',
    'fields' => array(
        
        // =============================================================================
        // LAYOUT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sbt_layout_tab',
            'label' => 'Layout',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sbt_column_split',
            'label' => 'Spaltenaufteilung',
            'name' => 'sbt_column_split',
            'type' => 'select',
            'choices' => array(
                '25/75' => '25% / 75%',
                '30/70' => '30% / 70%',
                '40/60' => '40% / 60%',
                '50/50' => '50% / 50%',
                '60/40' => '60% / 40%',
                '70/30' => '70% / 30%',
                '75/25' => '75% / 25%',
            ),
            'default_value' => '50/50',
            'instructions' => 'Verhältnis der beiden Spalten',
            'wrapper' => array('width' => '50'),
        ),
        array(
            'key' => 'field_sbt_image_position',
            'label' => 'Bildposition',
            'name' => 'sbt_image_position',
            'type' => 'select',
            'choices' => array(
                'left' => 'Links',
                'right' => 'Rechts',
            ),
            'default_value' => 'left',
            'instructions' => 'Position des Bildes (Text ist in der anderen Spalte)',
            'wrapper' => array('width' => '50'),
        ),
        
        // =============================================================================
        // BILD EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sbt_image_tab',
            'label' => 'Bild',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sbt_image',
            'label' => 'Bild',
            'name' => 'sbt_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'instructions' => 'Bild für die Bild-Spalte',
        ),
        array(
            'key' => 'field_sbt_image_fit',
            'label' => 'Bildanpassung',
            'name' => 'sbt_image_fit',
            'type' => 'select',
            'choices' => array(
                'cover' => 'Cover (füllt Bereich aus, kann beschnitten werden)',
                'contain' => 'Contain (vollständig sichtbar, kann Leerraum haben)',
            ),
            'default_value' => 'cover',
            'instructions' => 'Wie das Bild in den verfügbaren Bereich eingepasst wird',
        ),
        
        // =============================================================================
        // HEADLINE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sbt_headline_tab',
            'label' => 'Headline',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sbt_headline_text',
            'label' => 'Headline Text',
            'name' => 'sbt_headline_text',
            'type' => 'text',
            'instructions' => 'Hauptüberschrift (optional)',
        ),
        array(
            'key' => 'field_sbt_headline_tag',
            'label' => 'HTML Tag',
            'name' => 'sbt_headline_tag',
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
        ),
        array(
            'key' => 'field_sbt_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'sbt_headline_size',
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
        ),
        array(
            'key' => 'field_sbt_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sbt_headline_weight',
            'type' => 'select',
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
            'key' => 'field_sbt_headline_color',
            'label' => 'Farbe',
            'name' => 'sbt_headline_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        
        // =============================================================================
        // TEXT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sbt_text_tab',
            'label' => 'Text',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sbt_text_content',
            'label' => 'Text Inhalt',
            'name' => 'sbt_text_content',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Haupttext (optional)',
        ),
        array(
            'key' => 'field_sbt_text_size',
            'label' => 'Schriftgröße',
            'name' => 'sbt_text_size',
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
            'default_value' => '18',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sbt_text_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sbt_text_weight',
            'type' => 'select',
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
            'key' => 'field_sbt_text_color',
            'label' => 'Farbe',
            'name' => 'sbt_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sbt_text_tag',
            'label' => 'HTML Tag',
            'name' => 'sbt_text_tag',
            'type' => 'select',
            'choices' => array(
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'p' => 'Paragraph (Standard)',
            ),
            'default_value' => 'p',
            'wrapper' => array('width' => '25'),
        ),
        
        // =============================================================================
        // BUTTON EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sbt_button_tab',
            'label' => 'Button',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sbt_show_button',
            'label' => 'Button anzeigen',
            'name' => 'sbt_show_button',
            'type' => 'true_false',
            'default_value' => 0,
            'ui' => 1,
            'instructions' => 'Button aktivieren/deaktivieren',
        ),
        array(
            'key' => 'field_sbt_button_text',
            'label' => 'Button Text',
            'name' => 'sbt_button_text',
            'type' => 'text',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sbt_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sbt_button_url',
            'label' => 'Button URL',
            'name' => 'sbt_button_url',
            'type' => 'url',
            'instructions' => 'URL oder Modal-Trigger (#register-modal, #login-modal, #modal)',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sbt_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sbt_button_bg_color',
            'label' => 'Hintergrundfarbe (Standard)',
            'name' => 'sbt_button_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'primary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sbt_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sbt_button_text_color',
            'label' => 'Textfarbe (Standard)',
            'name' => 'sbt_button_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sbt_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sbt_button_hover_bg_color',
            'label' => 'Hintergrundfarbe (Hover)',
            'name' => 'sbt_button_hover_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'secondary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sbt_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sbt_button_hover_text_color',
            'label' => 'Textfarbe (Hover)',
            'name' => 'sbt_button_hover_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sbt_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        
        // =============================================================================
        // HINTERGRUND EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sbt_background_tab',
            'label' => 'Hintergrund',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sbt_background_color',
            'label' => 'Hintergrundfarbe',
            'name' => 'sbt_background_color',
            'type' => 'select',
            'choices' => array_merge(
                array('' => 'Keine (Standard)'),
                $color_choices
            ),
            'default_value' => '',
            'instructions' => 'Optionale Hintergrundfarbe für das gesamte Element (zeigt Container mit Padding, Schatten und Border-Radius)',
            'allow_null' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/styleguide-bild-text',
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
    'description' => 'ACF Fields for Styleguide Bild-Text Block',
); 