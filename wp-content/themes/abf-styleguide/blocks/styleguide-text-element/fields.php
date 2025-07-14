<?php
/**
 * ACF Fields for Styleguide Text Element Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_text_element',
    'title' => 'Styleguide Text Element',
    'fields' => array(
        
        // =============================================================================
        // HEADLINE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_ste_headline_tab',
            'label' => 'Headline',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_ste_headline_text',
            'label' => 'Headline Text',
            'name' => 'ste_headline_text',
            'type' => 'text',
            'instructions' => 'Hauptüberschrift (optional)',
        ),
        array(
            'key' => 'field_ste_headline_tag',
            'label' => 'HTML Tag',
            'name' => 'ste_headline_tag',
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
            'key' => 'field_ste_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'ste_headline_size',
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
            'key' => 'field_ste_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'ste_headline_weight',
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
            'key' => 'field_ste_headline_color',
            'label' => 'Farbe',
            'name' => 'ste_headline_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        
        // =============================================================================
        // TEXT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_ste_text_tab',
            'label' => 'Text',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_ste_text_content',
            'label' => 'Text Inhalt',
            'name' => 'ste_text_content',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Haupttext (optional)',
        ),
        array(
            'key' => 'field_ste_text_size',
            'label' => 'Schriftgröße',
            'name' => 'ste_text_size',
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
            'key' => 'field_ste_text_weight',
            'label' => 'Schriftgewicht',
            'name' => 'ste_text_weight',
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
            'key' => 'field_ste_text_color',
            'label' => 'Farbe',
            'name' => 'ste_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_ste_text_tag',
            'label' => 'HTML Tag',
            'name' => 'ste_text_tag',
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
            'key' => 'field_ste_background_tab',
            'label' => 'Hintergrund',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_ste_background_color',
            'label' => 'Hintergrundfarbe',
            'name' => 'ste_background_color',
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
        // BUTTON EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_ste_button_tab',
            'label' => 'Button',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_ste_show_button',
            'label' => 'Button anzeigen',
            'name' => 'ste_show_button',
            'type' => 'true_false',
            'default_value' => 0,
            'ui' => 1,
            'instructions' => 'Button aktivieren/deaktivieren',
        ),
        array(
            'key' => 'field_ste_button_text',
            'label' => 'Button Text',
            'name' => 'ste_button_text',
            'type' => 'text',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_ste_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_ste_button_url',
            'label' => 'Button URL',
            'name' => 'ste_button_url',
            'type' => 'url',
            'instructions' => 'URL oder Modal-Trigger (#register-modal, #login-modal, #modal)',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_ste_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_ste_button_bg_color',
            'label' => 'Hintergrundfarbe (Standard)',
            'name' => 'ste_button_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'primary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_ste_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_ste_button_text_color',
            'label' => 'Textfarbe (Standard)',
            'name' => 'ste_button_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_ste_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_ste_button_hover_bg_color',
            'label' => 'Hintergrundfarbe (Hover)',
            'name' => 'ste_button_hover_bg_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'secondary',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_ste_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_ste_button_hover_text_color',
            'label' => 'Textfarbe (Hover)',
            'name' => 'ste_button_hover_text_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'white',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_ste_show_button',
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
                'value' => 'acf/styleguide-text-element',
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
    'description' => 'ACF Fields for Styleguide Text Element Block',
); 