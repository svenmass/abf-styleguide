<?php
/**
 * ACF Fields for Parallax Grid Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_parallax_grid_block',
    'title' => 'Parallax Grid Block Felder',
    'fields' => array(
        
        // =============================================================================
        // STICKY EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_pg_sticky_tab',
            'label' => 'Sticky Einstellungen',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_pg_enable_sticky',
            'label' => 'Sticky aktivieren',
            'name' => 'grid_enable_sticky',
            'type' => 'true_false',
            'default_value' => 0,
            'ui' => 1,
            'instructions' => 'Block wird beim Scrollen an der angegebenen Position fixiert',
        ),
        array(
            'key' => 'field_pg_sticky_position',
            'label' => 'Sticky Position',
            'name' => 'grid_sticky_position',
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
                        'field' => 'field_pg_enable_sticky',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_pg_z_index',
            'label' => 'Z-Index (Stapelreihenfolge)',
            'name' => 'grid_z_index',
            'type' => 'number',
            'default_value' => 1000,
            'min' => 1,
            'max' => 9999,
            'step' => 1,
            'instructions' => 'Höhere Werte überdecken niedrigere (1000, 1001, 1002...) - auch ohne Sticky aktiv',
        ),
        array(
            'key' => 'field_pg_sticky_mobile',
            'label' => 'Sticky auf Mobile deaktivieren',
            'name' => 'grid_sticky_mobile_disable',
            'type' => 'true_false',
            'default_value' => 1,
            'ui' => 1,
            'instructions' => 'Empfohlen: Sticky-Effekte auf kleinen Bildschirmen deaktivieren',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_pg_enable_sticky',
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
            'key' => 'field_pg_design_tab',
            'label' => 'Design',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_pg_container_background_color',
            'label' => 'Container Hintergrundfarbe',
            'name' => 'grid_container_background_color',
            'type' => 'select',
            'choices' => array_merge(array('#ffffff' => 'Weiß (Standard)'), $color_choices),
            'default_value' => '#ffffff',
            'allow_null' => 0,
            'return_format' => 'value',
            'instructions' => 'Hintergrundfarbe des gesamten Grid-Containers',
        ),
        
        // =============================================================================
        // GRID ELEMENTE
        // =============================================================================
        array(
            'key' => 'field_pg_elements_tab',
            'label' => 'Grid Elemente',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_parallax_elements',
            'label' => 'Grid Elemente',
            'name' => 'parallax_elements',
            'type' => 'repeater',
            'instructions' => 'Konfiguriere die 6 Grid-Elemente. Die Reihenfolge entspricht: El1, El2, El3, El4, El5, El6',
            'required' => 0,
            'conditional_logic' => 0,
            'min' => 6,
            'max' => 6,
            'layout' => 'block',
            'button_label' => 'Element hinzufügen',
            'sub_fields' => array(
                // Background Settings
                array(
                    'key' => 'field_element_background_type',
                    'label' => 'Hintergrund Typ',
                    'name' => 'background_type',
                    'type' => 'radio',
                    'instructions' => 'Wähle zwischen Farbe oder Bild',
                    'required' => 1,
                    'choices' => array(
                        'color' => 'Hintergrundfarbe',
                        'image' => 'Hintergrund-Bild',
                        'video' => 'Hintergrund-Video',
                    ),
                    'default_value' => 'color',
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_element_background_color',
                    'label' => 'Hintergrundfarbe',
                    'name' => 'background_color',
                    'type' => 'select',
                    'instructions' => 'Wähle eine Hintergrundfarbe aus den Theme-Farben',
                    'required' => 0,
                    'choices' => $color_choices,
                    'default_value' => 'primary',
                    'allow_null' => 0,
                    'return_format' => 'value',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_background_type',
                                'operator' => '==',
                                'value' => 'color',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_element_background_image',
                    'label' => 'Hintergrund-Bild',
                    'name' => 'background_image',
                    'type' => 'image',
                    'instructions' => 'Wähle ein Hintergrund-Bild',
                    'required' => 0,
                    'return_format' => 'id',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_background_type',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_element_background_video',
                    'label' => 'Hintergrund-Video',
                    'name' => 'background_video',
                    'type' => 'file',
                    'instructions' => 'Wähle ein Hintergrund-Video (MP4 empfohlen, läuft automatisch in Schleife)',
                    'required' => 0,
                    'return_format' => 'array',
                    'mime_types' => 'mp4,webm,ogg',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_background_type',
                                'operator' => '==',
                                'value' => 'video',
                            ),
                        ),
                    ),
                ),
                
                // Plus Overlay Settings
                array(
                    'key' => 'field_element_show_plus',
                    'label' => 'Plus anzeigen',
                    'name' => 'show_plus',
                    'type' => 'true_false',
                    'instructions' => 'Soll ein Plus-Icon im rechten unteren Eck angezeigt werden?',
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_element_plus_type',
                    'label' => 'Plus Icon Typ',
                    'name' => 'plus_type',
                    'type' => 'radio',
                    'instructions' => 'Wähle zwischen Default SVG oder eigenem Icon',
                    'choices' => array(
                        'default' => 'Standard Plus SVG',
                        'custom' => 'Eigenes Icon (PNG/SVG)',
                    ),
                    'default_value' => 'default',
                    'layout' => 'horizontal',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_plus',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_element_plus_icon',
                    'label' => 'Plus Icon',
                    'name' => 'plus_icon',
                    'type' => 'image',
                    'instructions' => 'Wähle ein eigenes Plus-Icon (PNG oder SVG)',
                    'required' => 0,
                    'return_format' => 'array',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_plus',
                                'operator' => '==',
                                'value' => '1',
                            ),
                            array(
                                'field' => 'field_element_plus_type',
                                'operator' => '==',
                                'value' => 'custom',
                            ),
                        ),
                    ),
                ),
                
                // Headline Settings
                array(
                    'key' => 'field_element_headline_text',
                    'label' => 'Headline',
                    'name' => 'headline_text',
                    'type' => 'textarea',
                    'instructions' => 'Gib hier die Hauptüberschrift ein (optional)',
                    'required' => 0,
                    'rows' => 2,
                ),
                array(
                    'key' => 'field_element_headline_tag',
                    'label' => 'Headline Tag',
                    'name' => 'headline_tag',
                    'type' => 'select',
                    'instructions' => 'HTML-Tag für die Headline',
                    'choices' => array(
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6',
                    ),
                    'default_value' => 'h2',
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_element_headline_weight',
                    'label' => 'Headline Gewicht',
                    'name' => 'headline_weight',
                    'type' => 'select',
                    'instructions' => 'Schriftgewicht aus den Theme-Einstellungen',
                    'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array(
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
                    'key' => 'field_element_headline_size',
                    'label' => 'Headline Größe',
                    'name' => 'headline_size',
                    'type' => 'select',
                    'instructions' => 'Wähle die Schriftgröße aus den Theme-Einstellungen',
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
                ),
                array(
                    'key' => 'field_element_headline_color',
                    'label' => 'Headline Farbe',
                    'name' => 'headline_color',
                    'type' => 'select',
                    'instructions' => 'Textfarbe der Headline',
                    'choices' => $color_choices,
                    'default_value' => 'inherit',
                    'wrapper' => array('width' => '25'),
                ),
                
                // Subline Settings
                array(
                    'key' => 'field_element_subline_text',
                    'label' => 'Subline',
                    'name' => 'subline_text',
                    'type' => 'textarea',
                    'instructions' => 'Gib hier die Unterüberschrift ein (optional)',
                    'required' => 0,
                    'rows' => 2,
                ),
                array(
                    'key' => 'field_element_subline_tag',
                    'label' => 'Subline Tag',
                    'name' => 'subline_tag',
                    'type' => 'select',
                    'instructions' => 'HTML-Tag für die Subline',
                    'choices' => array(
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6',
                        'p' => 'Paragraph',
                    ),
                    'default_value' => 'p',
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_element_subline_weight',
                    'label' => 'Subline Gewicht',
                    'name' => 'subline_weight',
                    'type' => 'select',
                    'instructions' => 'Schriftgewicht aus den Theme-Einstellungen',
                    'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array(
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
                    'key' => 'field_element_subline_size',
                    'label' => 'Subline Größe',
                    'name' => 'subline_size',
                    'type' => 'select',
                    'instructions' => 'Wähle die Schriftgröße aus den Theme-Einstellungen',
                    'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array(
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
                    'key' => 'field_element_subline_color',
                    'label' => 'Subline Farbe',
                    'name' => 'subline_color',
                    'type' => 'select',
                    'instructions' => 'Textfarbe der Subline',
                    'choices' => $color_choices,
                    'default_value' => 'inherit',
                    'wrapper' => array('width' => '25'),
                ),
                
                // Button Settings
                array(
                    'key' => 'field_element_show_button',
                    'label' => 'Button anzeigen',
                    'name' => 'show_button',
                    'type' => 'true_false',
                    'instructions' => 'Soll ein Button angezeigt werden?',
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_element_button_text',
                    'label' => 'Button Text',
                    'name' => 'button_text',
                    'type' => 'text',
                    'instructions' => 'Text für den Button',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '50'),
                ),
                array(
                    'key' => 'field_element_button_url',
                    'label' => 'Button Link',
                    'name' => 'button_url',
                    'type' => 'link',
                    'instructions' => 'Wähle ein Linkziel aus Seiten, Beiträgen oder externe URL',
                    'return_format' => 'array',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '50'),
                ),
                array(
                    'key' => 'field_element_button_bg_color',
                    'label' => 'Button Hintergrund',
                    'name' => 'button_bg_color',
                    'type' => 'select',
                    'instructions' => 'Hintergrundfarbe des Buttons',
                    'choices' => $color_choices,
                    'default_value' => 'primary',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_element_button_text_color',
                    'label' => 'Button Text',
                    'name' => 'button_text_color',
                    'type' => 'select',
                    'instructions' => 'Textfarbe des Buttons',
                    'choices' => $color_choices,
                    'default_value' => 'inherit',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_element_button_hover_bg_color',
                    'label' => 'Button Hover BG',
                    'name' => 'button_hover_bg_color',
                    'type' => 'select',
                    'instructions' => 'Hover-Hintergrundfarbe',
                    'choices' => $color_choices,
                    'default_value' => 'secondary',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_element_button_hover_text_color',
                    'label' => 'Button Hover Text',
                    'name' => 'button_hover_text_color',
                    'type' => 'select',
                    'instructions' => 'Hover-Textfarbe',
                    'choices' => $color_choices,
                    'default_value' => 'inherit',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_element_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/parallax-grid',
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
    'description' => 'Felder für den Parallax Grid Block mit 6 konfigurierbaren Elementen',
); 