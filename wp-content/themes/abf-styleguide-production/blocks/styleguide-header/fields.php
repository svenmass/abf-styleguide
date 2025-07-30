<?php
/**
 * ACF Fields for Styleguide Header Block
 */

// Get color choices from the main function
if (function_exists('abf_get_color_choices')) {
    $color_choices = abf_get_color_choices();
} else {
    $color_choices = array();
}

return array(
    'key' => 'group_styleguide_header',
    'title' => 'Styleguide Header',
    'fields' => array(
        
        // =============================================================================
        // LAYOUT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sh_layout_tab',
            'label' => 'Layout',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sh_image_position',
            'label' => 'Bildposition',
            'name' => 'sh_image_position',
            'type' => 'select',
            'choices' => array(
                'left' => 'Links (60% Bild, 40% Text)',
                'right' => 'Rechts (40% Text, 60% Bild)',
            ),
            'default_value' => 'right',
            'instructions' => 'Position des Bildes - hat immer 60% Breite, Text 40%',
        ),
        
        // =============================================================================
        // BILD EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sh_image_tab',
            'label' => 'Bild',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sh_image',
            'label' => 'Bild',
            'name' => 'sh_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'instructions' => 'Bild für den Header (60% Breite)',
        ),
        array(
            'key' => 'field_sh_image_fit',
            'label' => 'Bildanpassung',
            'name' => 'sh_image_fit',
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
            'key' => 'field_sh_headline_tab',
            'label' => 'Headline',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sh_headline_text',
            'label' => 'Headline Text',
            'name' => 'sh_headline_text',
            'type' => 'text',
            'instructions' => 'Hauptüberschrift (optional)',
        ),
        array(
            'key' => 'field_sh_headline_tag',
            'label' => 'HTML Tag',
            'name' => 'sh_headline_tag',
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
            'key' => 'field_sh_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'sh_headline_size',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('24' => '24px (H2)'),
            'default_value' => '24',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sh_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sh_headline_weight',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('400' => 'Regular (400)'),
            'default_value' => '400',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sh_headline_color',
            'label' => 'Farbe',
            'name' => 'sh_headline_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        
        // =============================================================================
        // TEXT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sh_text_tab',
            'label' => 'Text',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sh_text_content',
            'label' => 'Text Inhalt',
            'name' => 'sh_text_content',
            'type' => 'wysiwyg',
            'toolbar' => 'abf_enhanced',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Haupttext (optional). Verwende die Farb- und Schriftgrößen-Buttons in der Toolbar für individuelle Formatierungen.',
        ),
        array(
            'key' => 'field_sh_text_tag',
            'label' => 'HTML Tag',
            'name' => 'sh_text_tag',
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
        // HINTERGRUND EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sh_background_tab',
            'label' => 'Hintergrund',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sh_background_color',
            'label' => 'Hintergrundfarbe (nur Text-Bereich)',
            'name' => 'sh_background_color',
            'type' => 'select',
            'choices' => array_merge(
                array('' => 'Keine (Standard)'),
                $color_choices
            ),
            'default_value' => '',
            'instructions' => 'Optionale Hintergrundfarbe nur für den Text-Bereich (40% Spalte)',
            'allow_null' => 0,
        ),
        
        // =============================================================================
        // DOWNLOADS EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sh_downloads_tab',
            'label' => 'Downloads',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sh_downloads',
            'label' => 'Downloads',
            'name' => 'sh_downloads',
            'type' => 'repeater',
            'instructions' => 'Fügen Sie Downloads hinzu, die automatisch formatiert werden',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_sh_download_title',
            'min' => 0,
            'max' => 20,
            'layout' => 'table',
            'button_label' => 'Download hinzufügen',
            'sub_fields' => array(
                array(
                    'key' => 'field_sh_download_title',
                    'label' => 'Titel',
                    'name' => 'download_title',
                    'type' => 'text',
                    'instructions' => 'Anzeigename für den Download/Link',
                    'required' => 1,
                    'default_value' => '',
                    'placeholder' => 'z.B. "Broschüre herunterladen"',
                    'wrapper' => array(
                        'width' => '40',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_sh_download_link',
                    'label' => 'Datei',
                    'name' => 'download_link',
                    'type' => 'file',
                    'instructions' => 'Datei aus der Mediathek auswählen',
                    'required' => 1,
                    'return_format' => 'array',
                    'library' => 'all',
                    'wrapper' => array(
                        'width' => '60',
                        'class' => '',
                        'id' => '',
                    ),
                ),
            ),
        ),
        
        // =============================================================================
        // BUTTON EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sh_button_tab',
            'label' => 'Button',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sh_show_button',
            'label' => 'Button anzeigen',
            'name' => 'sh_show_button',
            'type' => 'true_false',
            'message' => 'Button am Ende des Elements anzeigen',
            'default_value' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_sh_button_text',
            'label' => 'Button Text',
            'name' => 'sh_button_text',
            'type' => 'text',
            'instructions' => 'Text der auf dem Button angezeigt wird',
            'placeholder' => 'z.B. "Mehr erfahren"',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sh_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sh_button_url',
            'label' => 'Button URL',
            'name' => 'sh_button_url',
            'type' => 'link',
            'instructions' => 'Ziel-URL des Buttons',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sh_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sh_button_bg_color',
            'label' => 'Hintergrundfarbe',
            'name' => 'sh_button_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'primary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sh_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sh_button_text_color',
            'label' => 'Textfarbe',
            'name' => 'sh_button_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sh_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sh_button_hover_bg_color',
            'label' => 'Hintergrundfarbe (Hover)',
            'name' => 'sh_button_hover_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'secondary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sh_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sh_button_hover_text_color',
            'label' => 'Textfarbe (Hover)',
            'name' => 'sh_button_hover_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sh_show_button',
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
                'value' => 'acf/styleguide-header',
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
    'description' => 'ACF Fields for Styleguide Header Block',
); 