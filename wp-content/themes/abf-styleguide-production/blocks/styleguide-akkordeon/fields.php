<?php
/**
 * ACF Fields for Styleguide Akkordeon Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_akkordeon',
    'title' => 'Styleguide Akkordeon',
    'fields' => array(
        
        // =============================================================================
        // HEADLINE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sa_headline_tab',
            'label' => 'Headline',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sa_headline_text',
            'label' => 'Headline Text',
            'name' => 'sa_headline_text',
            'type' => 'text',
            'instructions' => 'Hauptüberschrift (optional)',
        ),
        array(
            'key' => 'field_sa_headline_tag',
            'label' => 'HTML Tag',
            'name' => 'sa_headline_tag',
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
            'key' => 'field_sa_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'sa_headline_size',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('24' => '24px (H2)'),
            'default_value' => '24',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sa_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sa_headline_weight',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('400' => 'Regular (400)'),
            'default_value' => '400',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sa_headline_color',
            'label' => 'Farbe',
            'name' => 'sa_headline_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        
        // =============================================================================
        // TEXT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sa_text_tab',
            'label' => 'Text',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sa_text_content',
            'label' => 'Text Inhalt',
            'name' => 'sa_text_content',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Haupttext (optional)',
        ),
        array(
            'key' => 'field_sa_text_size',
            'label' => 'Schriftgröße',
            'name' => 'sa_text_size',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('18' => '18px (Body)'),
            'default_value' => '18',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sa_text_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sa_text_weight',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('400' => 'Regular (400)'),
            'default_value' => '400',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sa_text_color',
            'label' => 'Farbe',
            'name' => 'sa_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_sa_text_tag',
            'label' => 'HTML Tag',
            'name' => 'sa_text_tag',
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
        // AKKORDEON ELEMENTE
        // =============================================================================
        array(
            'key' => 'field_sa_accordion_tab',
            'label' => 'Akkordeon Elemente',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sa_accordion_items',
            'label' => 'Akkordeon Elemente',
            'name' => 'sa_accordion_items',
            'type' => 'repeater',
            'instructions' => 'Füge beliebig viele Akkordeon-Elemente hinzu',
            'required' => 0,
            'conditional_logic' => 0,
            'min' => 1,
            'max' => 0, // Unlimited
            'layout' => 'block',
            'button_label' => 'Element hinzufügen',
            'sub_fields' => array(
                // Akkordeon Titel
                array(
                    'key' => 'field_sa_accordion_title',
                    'label' => 'Titel',
                    'name' => 'accordion_title',
                    'type' => 'text',
                    'instructions' => 'Titel des Akkordeon-Elements',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_sa_accordion_title_size',
                    'label' => 'Titel Schriftgröße',
                    'name' => 'accordion_title_size',
                    'type' => 'select',
                    'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('18' => '18px (Body)'),
                    'default_value' => '18',
                    'wrapper' => array('width' => '33'),
                ),
                array(
                    'key' => 'field_sa_accordion_title_weight',
                    'label' => 'Titel Schriftgewicht',
                    'name' => 'accordion_title_weight',
                    'type' => 'select',
                    'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('600' => 'Semi-Bold (600)'),
                    'default_value' => '600',
                    'wrapper' => array('width' => '33'),
                ),
                array(
                    'key' => 'field_sa_accordion_title_color',
                    'label' => 'Titel Farbe',
                    'name' => 'accordion_title_color',
                    'type' => 'select',
                    'choices' => $color_choices,
                    'default_value' => 'inherit',
                    'wrapper' => array('width' => '34'),
                ),
                
                // Akkordeon Content
                array(
                    'key' => 'field_sa_accordion_content',
                    'label' => 'Inhalt',
                    'name' => 'accordion_content',
                    'type' => 'wysiwyg',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 0,
                    'instructions' => 'Inhalt des Akkordeon-Elements',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_sa_accordion_content_size',
                    'label' => 'Inhalt Schriftgröße',
                    'name' => 'accordion_content_size',
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
                    'wrapper' => array('width' => '33'),
                ),
                array(
                    'key' => 'field_sa_accordion_content_weight',
                    'label' => 'Inhalt Schriftgewicht',
                    'name' => 'accordion_content_weight',
                    'type' => 'select',
                    'choices' => array(
                        '300' => 'Light (300)',
                        '400' => 'Regular (400)',
                        '500' => 'Medium (500)',
                        '600' => 'Semi-Bold (600)',
                        '700' => 'Bold (700)',
                    ),
                    'default_value' => '400',
                    'wrapper' => array('width' => '33'),
                ),
                array(
                    'key' => 'field_sa_accordion_content_color',
                    'label' => 'Inhalt Farbe',
                    'name' => 'accordion_content_color',
                    'type' => 'select',
                    'choices' => $color_choices,
                    'default_value' => 'inherit',
                    'wrapper' => array('width' => '34'),
                ),
            ),
        ),
        
        // =============================================================================
        // DOWNLOADS TAB
        // =============================================================================
        array(
            'key' => 'field_sa_downloads_tab',
            'label' => 'Downloads',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sa_downloads',
            'label' => 'Downloads',
            'name' => 'sa_downloads',
            'type' => 'repeater',
            'instructions' => 'Fügen Sie Downloads hinzu, die nach den Akkordeon-Elementen angezeigt werden',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_sa_download_title',
            'min' => 0,
            'max' => 20,
            'layout' => 'table',
            'button_label' => 'Download hinzufügen',
            'sub_fields' => array(
                array(
                    'key' => 'field_sa_download_title',
                    'label' => 'Titel',
                    'name' => 'download_title',
                    'type' => 'text',
                    'instructions' => 'Anzeigename für den Download',
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
                    'key' => 'field_sa_download_link',
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
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/styleguide-akkordeon',
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
    'description' => 'ACF Fields for Styleguide Akkordeon Block',
); 