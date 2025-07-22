<?php
/**
 * ACF Fields for Text Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
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
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array(
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
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array(
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
        
        // Subline
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
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array(
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
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array(
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
            'label' => 'Button Link',
            'name' => 'tb_button_url',
            'type' => 'link',
            'instructions' => 'Wähle ein Linkziel aus Seiten, Beiträgen oder externe URL. "#" für Anker-Links ist erlaubt.',
            'return_format' => 'array',
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
); 