<?php
/**
 * ACF Fields for Styleguide Text Zweispaltig Block
 */

// Get color choices from the main function
if (function_exists('abf_get_color_choices')) {
    $color_choices = abf_get_color_choices();
} else {
    $color_choices = array();
}

return array(
    'key' => 'group_styleguide_text_zweispaltig',
    'title' => 'Styleguide Text Zweispaltig',
    'fields' => array(
        
        // =============================================================================
        // HEADLINE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_stz_headline_tab',
            'label' => 'Headline',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stz_headline_text',
            'label' => 'Headline Text',
            'name' => 'stz_headline_text',
            'type' => 'text',
            'instructions' => 'Hauptüberschrift (optional) - gilt für beide Spalten',
        ),
        array(
            'key' => 'field_stz_headline_tag',
            'label' => 'HTML Tag',
            'name' => 'stz_headline_tag',
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
            'key' => 'field_stz_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'stz_headline_size',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('24' => '24px (H2)'),
            'default_value' => '24',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_stz_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'stz_headline_weight',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('400' => 'Regular (400)'),
            'default_value' => '400',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_stz_headline_color',
            'label' => 'Farbe',
            'name' => 'stz_headline_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        
        // =============================================================================
        // TEXT EINSTELLUNGEN - LINKS
        // =============================================================================
        array(
            'key' => 'field_stz_left_text_tab',
            'label' => 'Linker Text',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stz_left_text',
            'label' => 'Linker Text Inhalt',
            'name' => 'stz_left_text',
            'type' => 'wysiwyg',
            'toolbar' => 'abf_enhanced',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Text für die linke Spalte (optional). Verwende die Farb- und Schriftgrößen-Buttons in der Toolbar für individuelle Formatierungen.',
        ),
        array(
            'key' => 'field_stz_left_text_tag',
            'label' => 'HTML Tag',
            'name' => 'stz_left_text_tag',
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
        // TEXT EINSTELLUNGEN - RECHTS
        // =============================================================================
        array(
            'key' => 'field_stz_right_text_tab',
            'label' => 'Rechter Text',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stz_right_text',
            'label' => 'Rechter Text Inhalt',
            'name' => 'stz_right_text',
            'type' => 'wysiwyg',
            'toolbar' => 'abf_enhanced',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Text für die rechte Spalte (optional). Verwende die Farb- und Schriftgrößen-Buttons in der Toolbar für individuelle Formatierungen.',
        ),
        array(
            'key' => 'field_stz_right_text_tag',
            'label' => 'HTML Tag',
            'name' => 'stz_right_text_tag',
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
            'key' => 'field_stz_background_tab',
            'label' => 'Hintergrund',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stz_background_color',
            'label' => 'Hintergrundfarbe',
            'name' => 'stz_background_color',
            'type' => 'select',
            'choices' => array_merge(
                array('' => 'Keine (Standard)'),
                $color_choices
            ),
            'default_value' => '',
            'instructions' => 'Optionale Hintergrundfarbe für das Element (zeigt Container mit Padding, Schatten und Border-Radius)',
            'allow_null' => 0,
        ),
        
        // =============================================================================
        // DOWNLOADS EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_stz_downloads_tab',
            'label' => 'Downloads',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stz_downloads',
            'label' => 'Downloads',
            'name' => 'stz_downloads',
            'type' => 'repeater',
            'instructions' => 'Fügen Sie Downloads hinzu, die automatisch formatiert werden',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_stz_download_title',
            'min' => 0,
            'max' => 20,
            'layout' => 'table',
            'button_label' => 'Download hinzufügen',
            'sub_fields' => array(
                array(
                    'key' => 'field_stz_download_title',
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
                    'key' => 'field_stz_download_link',
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
            'key' => 'field_stz_button_tab',
            'label' => 'Button',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stz_show_button',
            'label' => 'Button anzeigen',
            'name' => 'stz_show_button',
            'type' => 'true_false',
            'message' => 'Button am Ende des Elements anzeigen',
            'default_value' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_stz_button_text',
            'label' => 'Button Text',
            'name' => 'stz_button_text',
            'type' => 'text',
            'instructions' => 'Text der auf dem Button angezeigt wird',
            'placeholder' => 'z.B. "Mehr erfahren"',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_stz_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_stz_button_url',
            'label' => 'Button URL',
            'name' => 'stz_button_url',
            'type' => 'link',
            'instructions' => 'Ziel-URL des Buttons',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_stz_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_stz_button_bg_color',
            'label' => 'Hintergrundfarbe',
            'name' => 'stz_button_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'primary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_stz_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_stz_button_text_color',
            'label' => 'Textfarbe',
            'name' => 'stz_button_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_stz_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_stz_button_hover_bg_color',
            'label' => 'Hintergrundfarbe (Hover)',
            'name' => 'stz_button_hover_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'secondary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_stz_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_stz_button_hover_text_color',
            'label' => 'Textfarbe (Hover)',
            'name' => 'stz_button_hover_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_stz_show_button',
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
                'value' => 'acf/styleguide-text-zweispaltig',
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
    'description' => 'ACF Fields for Styleguide Text Zweispaltig Block',
); 