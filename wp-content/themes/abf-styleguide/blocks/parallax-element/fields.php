<?php
/**
 * ACF Fields für Parallax Element Block
 */

return array(
    'key' => 'group_parallax_element',
    'title' => 'Parallax Element',
    'fields' => array(
        
        // =============================================================================
        // STICKY EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_pe_sticky_tab',
            'label' => 'Sticky Einstellungen',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_pe_enable_sticky',
            'label' => 'Sticky aktivieren',
            'name' => 'enable_sticky',
            'type' => 'true_false',
            'default_value' => 0,
            'ui' => 1,
            'instructions' => 'Element wird beim Scrollen an der angegebenen Position fixiert',
        ),
        array(
            'key' => 'field_pe_sticky_position',
            'label' => 'Sticky Position',
            'name' => 'sticky_position',
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
                        'field' => 'field_pe_enable_sticky',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_z_index',
            'label' => 'Z-Index (Stapelreihenfolge)',
            'name' => 'z_index',
            'type' => 'number',
            'default_value' => 1000,
            'min' => 1,
            'max' => 9999,
            'step' => 1,
            'instructions' => 'Höhere Werte überdecken niedrigere (1000, 1001, 1002...)',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_enable_sticky',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_sticky_mobile',
            'label' => 'Sticky auf Mobile deaktivieren',
            'name' => 'sticky_mobile_disable',
            'type' => 'true_false',
            'default_value' => 1,
            'ui' => 1,
            'instructions' => 'Empfohlen: Sticky-Effekte auf kleinen Bildschirmen deaktivieren',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_enable_sticky',
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
            'key' => 'field_pe_design_tab',
            'label' => 'Design',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_pe_background_color',
            'label' => 'Hintergrundfarbe',
            'name' => 'background_color',
            'type' => 'select',
            'choices' => array(
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'white' => 'Weiß',
                'black' => 'Schwarz',
                'inherit' => 'Transparent / Erben',
            ),
            'default_value' => 'primary',
            'allow_null' => 0,
            'return_format' => 'value',
        ),
        
        // =============================================================================
        // CONTENT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_pe_content_tab',
            'label' => 'Inhalt',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        
        // Headline
        array(
            'key' => 'field_pe_headline_text',
            'label' => 'Headline',
            'name' => 'headline_text',
            'type' => 'text',
            'instructions' => 'Hauptüberschrift des Elements',
        ),
        array(
            'key' => 'field_pe_headline_tag',
            'label' => 'Headline Tag',
            'name' => 'headline_tag',
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
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_headline_weight',
            'label' => 'Headline Schriftdicke',
            'name' => 'headline_weight',
            'type' => 'select',
            'choices' => array(
                '300' => 'Light (300)',
                '400' => 'Normal (400)',
                '500' => 'Medium (500)',
                '600' => 'Semi-Bold (600)',
                '700' => 'Bold (700)',
            ),
            'default_value' => '400',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_headline_size',
            'label' => 'Headline Schriftgröße',
            'name' => 'headline_size',
            'type' => 'number',
            'default_value' => 36,
            'min' => 16,
            'max' => 120,
            'step' => 1,
            'append' => 'px',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_headline_color',
            'label' => 'Headline Farbe',
            'name' => 'headline_color',
            'type' => 'select',
            'choices' => array(
                'white' => 'Weiß',
                'black' => 'Schwarz',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'inherit' => 'Erben',
            ),
            'default_value' => 'white',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        
        // Richtext Content
        array(
            'key' => 'field_pe_richtext_content',
            'label' => 'Text Inhalt',
            'name' => 'richtext_content',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Haupttext des Elements. Unterstützt grundlegende Formatierung.',
        ),
        
        // Button
        array(
            'key' => 'field_pe_show_button',
            'label' => 'Button anzeigen',
            'name' => 'show_button',
            'type' => 'true_false',
            'default_value' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_pe_button_text',
            'label' => 'Button Text',
            'name' => 'button_text',
            'type' => 'text',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_button_url',
            'label' => 'Button URL',
            'name' => 'button_url',
            'type' => 'url',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_button_bg_color',
            'label' => 'Button Hintergrundfarbe',
            'name' => 'button_bg_color',
            'type' => 'select',
            'choices' => array(
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'white' => 'Weiß',
                'black' => 'Schwarz',
            ),
            'default_value' => 'secondary',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_button_text_color',
            'label' => 'Button Textfarbe',
            'name' => 'button_text_color',
            'type' => 'select',
            'choices' => array(
                'white' => 'Weiß',
                'black' => 'Schwarz',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
            ),
            'default_value' => 'white',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_button_hover_bg_color',
            'label' => 'Button Hover Hintergrundfarbe',
            'name' => 'button_hover_bg_color',
            'type' => 'select',
            'choices' => array(
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'white' => 'Weiß',
                'black' => 'Schwarz',
            ),
            'default_value' => 'primary',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_button_hover_text_color',
            'label' => 'Button Hover Textfarbe',
            'name' => 'button_hover_text_color',
            'type' => 'select',
            'choices' => array(
                'white' => 'Weiß',
                'black' => 'Schwarz',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
            ),
            'default_value' => 'white',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_show_button',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        
        // =============================================================================
        // MEDIA EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_pe_media_tab',
            'label' => 'Media',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_pe_media_type',
            'label' => 'Media Type',
            'name' => 'media_type',
            'type' => 'select',
            'choices' => array(
                'none' => 'Kein Media',
                'image' => 'Bild',
                'video' => 'Video',
            ),
            'default_value' => 'image',
            'allow_null' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_pe_image',
            'label' => 'Bild',
            'name' => 'image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_media_type',
                        'operator' => '==',
                        'value' => 'image',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_image_fit',
            'label' => 'Bild Anpassung',
            'name' => 'image_fit',
            'type' => 'select',
            'choices' => array(
                'cover' => 'Cover (füllt den Bereich, beschneidet ggf.)',
                'contain' => 'Contain (zeigt komplettes Bild mit Padding)',
            ),
            'default_value' => 'cover',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_media_type',
                        'operator' => '==',
                        'value' => 'image',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pe_video',
            'label' => 'Video',
            'name' => 'video',
            'type' => 'file',
            'return_format' => 'array',
            'library' => 'all',
            'mime_types' => 'mp4,webm,ogg',
            'instructions' => 'Video wird als Hintergrund abgespielt (autoplay, muted, loop)',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pe_media_type',
                        'operator' => '==',
                        'value' => 'video',
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
                'value' => 'abf/parallax-element',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
); 