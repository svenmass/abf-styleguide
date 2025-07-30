<?php
/**
 * ACF Fields for Styleguide Posts Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_posts',
    'title' => 'Styleguide Posts',
    'fields' => array(
        
        // =============================================================================
        // HEADLINE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sp_headline_tab',
            'label' => 'Headline',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sp_headline_text',
            'label' => 'Headline Text',
            'name' => 'sp_headline_text',
            'type' => 'text',
            'instructions' => 'Hauptüberschrift (optional)',
            'placeholder' => 'z.B. "Aktuelle Beiträge"',
        ),
        array(
            'key' => 'field_sp_headline_tag',
            'label' => 'HTML Tag',
            'name' => 'sp_headline_tag',
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
                        'field' => 'field_sp_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sp_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'sp_headline_size',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('36' => '36px (H1)'),
            'default_value' => '36',
            'wrapper' => array('width' => '25'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sp_headline_weight',
            'label' => 'Schriftgewicht',
            'name' => 'sp_headline_weight',
            'type' => 'select',
            'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('600' => 'Semi-Bold (600)'),
            'default_value' => '600',
            'wrapper' => array('width' => '25'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sp_headline_color',
            'label' => 'Farbe',
            'name' => 'sp_headline_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_headline_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        
        // =============================================================================
        // INHALT EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sp_content_tab',
            'label' => 'Inhalt',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sp_selection_mode',
            'label' => 'Auswahl-Modus',
            'name' => 'sp_selection_mode',
            'type' => 'radio',
            'choices' => array(
                'automatic' => 'Automatisch (nach Kategorien)',
                'manual' => 'Manuell (spezifische Posts)',
            ),
            'default_value' => 'automatic',
            'layout' => 'horizontal',
            'instructions' => 'Wählen Sie, wie die Posts ausgewählt werden sollen',
        ),
        
        // AUTOMATISCHE AUSWAHL
        array(
            'key' => 'field_sp_post_categories',
            'label' => 'Kategorien',
            'name' => 'sp_post_categories',
            'type' => 'taxonomy',
            'taxonomy' => 'category',
            'field_type' => 'checkbox',
            'multiple' => 1,
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'id',
            'instructions' => 'Wählen Sie die Kategorien aus, deren Posts angezeigt werden sollen (mehrere möglich)',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_selection_mode',
                        'operator' => '==',
                        'value' => 'automatic',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sp_orderby',
            'label' => 'Sortierung',
            'name' => 'sp_orderby',
            'type' => 'select',
            'choices' => array(
                'date' => 'Datum',
                'title' => 'Titel',
                'menu_order' => 'Menü-Reihenfolge',
                'rand' => 'Zufällig',
            ),
            'default_value' => 'date',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_selection_mode',
                        'operator' => '==',
                        'value' => 'automatic',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sp_order',
            'label' => 'Reihenfolge',
            'name' => 'sp_order',
            'type' => 'select',
            'choices' => array(
                'DESC' => 'Absteigend (neueste zuerst)',
                'ASC' => 'Aufsteigend (älteste zuerst)',
            ),
            'default_value' => 'DESC',
            'wrapper' => array('width' => '50'),
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_selection_mode',
                        'operator' => '==',
                        'value' => 'automatic',
                    ),
                ),
            ),
        ),
        
        // MANUELLE AUSWAHL
        array(
            'key' => 'field_sp_manual_posts',
            'label' => 'Posts auswählen',
            'name' => 'sp_manual_posts',
            'type' => 'post_object',
            'post_type' => array('post'),
            'multiple' => 1,
            'allow_null' => 0,
            'ui' => 1,
            'return_format' => 'id',
            'instructions' => 'Wählen Sie die Posts aus, die angezeigt werden sollen. Die Reihenfolge hier bestimmt die Anzeige-Reihenfolge.',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_selection_mode',
                        'operator' => '==',
                        'value' => 'manual',
                    ),
                ),
            ),
        ),
        
        // =============================================================================
        // ANZEIGE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sp_display_tab',
            'label' => 'Anzeige',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sp_posts_per_page',
            'label' => 'Anzahl Posts',
            'name' => 'sp_posts_per_page',
            'type' => 'number',
            'default_value' => 6,
            'min' => 1,
            'max' => 20,
            'step' => 1,
            'wrapper' => array('width' => '50'),
            'instructions' => 'Maximale Anzahl der Posts, die angezeigt werden sollen',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_sp_selection_mode',
                        'operator' => '==',
                        'value' => 'automatic',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_sp_columns',
            'label' => 'Spalten',
            'name' => 'sp_columns',
            'type' => 'select',
            'choices' => array(
                '1' => '1 Spalte',
                '2' => '2 Spalten',
                '3' => '3 Spalten',
                '4' => '4 Spalten',
            ),
            'default_value' => '3',
            'wrapper' => array('width' => '50'),
            'instructions' => 'Anzahl der Spalten im Grid',
        ),
        array(
            'key' => 'field_sp_show_excerpt',
            'label' => 'Excerpt anzeigen',
            'name' => 'sp_show_excerpt',
            'type' => 'true_false',
            'default_value' => 1,
            'ui' => 1,
            'wrapper' => array('width' => '33.33'),
            'instructions' => 'Kurze Beschreibung unter dem Titel anzeigen',
        ),
        array(
            'key' => 'field_sp_show_date',
            'label' => 'Datum anzeigen',
            'name' => 'sp_show_date',
            'type' => 'true_false',
            'default_value' => 1,
            'ui' => 1,
            'wrapper' => array('width' => '33.33'),
            'instructions' => 'Veröffentlichungsdatum anzeigen',
        ),
        array(
            'key' => 'field_sp_show_category',
            'label' => 'Kategorie anzeigen',
            'name' => 'sp_show_category',
            'type' => 'true_false',
            'default_value' => 1,
            'ui' => 1,
            'wrapper' => array('width' => '33.33'),
            'instructions' => 'Haupt-Kategorie anzeigen',
        ),
        
        // =============================================================================
        // ZUSATZELEMENTE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_sp_extras_tab',
            'label' => 'Zusatzelemente',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_sp_extra_elements',
            'label' => 'Zusatzelemente',
            'name' => 'sp_extra_elements',
            'type' => 'repeater',
            'instructions' => 'Zusätzliche Elemente die zwischen den Posts angezeigt werden. Diese haben die gleichen Maße wie die Post-Cards.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_sp_extra_title',
            'min' => 0,
            'max' => 10,
            'layout' => 'block',
            'button_label' => 'Zusatzelement hinzufügen',
            'sub_fields' => array(
                array(
                    'key' => 'field_sp_extra_title',
                    'label' => 'Titel/Text',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Text der in der Farbfläche angezeigt wird',
                    'required' => 1,
                    'default_value' => '',
                    'placeholder' => 'z.B. "Jetzt entdecken"',
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_sp_extra_link',
                    'label' => 'Link',
                    'name' => 'link',
                    'type' => 'link',
                    'instructions' => 'Verlinkung zu Seite oder Beitrag',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_sp_extra_background_color',
                    'label' => 'Hintergrundfarbe',
                    'name' => 'background_color',
                    'type' => 'select',
                    'choices' => $color_choices,
                    'default_value' => 'primary',
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'instructions' => 'Hintergrundfarbe der Farbfläche (identische Maße wie Post-Bilder)',
                ),
                array(
                    'key' => 'field_sp_extra_icon',
                    'label' => 'Icon',
                    'name' => 'icon',
                    'type' => 'image',
                    'instructions' => 'Icon das rechts oben mit Padding angezeigt wird (SVG empfohlen)',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_sp_extra_text_position',
                    'label' => 'Text-Position',
                    'name' => 'text_position',
                    'type' => 'select',
                    'choices' => array(
                        'bottom' => 'Unten (align bottom)',
                        'space_between' => 'Space Between (Icon oben, Text unten)',
                    ),
                    'default_value' => 'space_between',
                    'wrapper' => array(
                        'width' => '33.33',
                        'class' => '',
                        'id' => '',
                    ),
                    'instructions' => 'Position des Textes in der Farbfläche',
                ),
                array(
                    'key' => 'field_sp_extra_text_color',
                    'label' => 'Textfarbe',
                    'name' => 'text_color',
                    'type' => 'select',
                    'choices' => array_merge(
                        array('white' => 'Weiß (Standard)'),
                        $color_choices
                    ),
                    'default_value' => 'white',
                    'wrapper' => array(
                        'width' => '33.33',
                        'class' => '',
                        'id' => '',
                    ),
                    'instructions' => 'Farbe des Textes',
                ),
                array(
                    'key' => 'field_sp_extra_text_size',
                    'label' => 'Textgröße',
                    'name' => 'text_size',
                    'type' => 'select',
                    'choices' => function_exists('abf_get_typography_font_sizes') 
                        ? abf_get_typography_font_sizes() 
                        : array(
                            '14' => '14px (Klein)',
                            '16' => '16px (Normal)', 
                            '18' => '18px (Groß)',
                            '20' => '20px (Sehr groß)'
                        ),
                    'default_value' => '18',
                    'wrapper' => array(
                        'width' => '33.33',
                        'class' => '',
                        'id' => '',
                    ),
                    'instructions' => 'Schriftgröße des Textes',
                ),
            ),
        ),
        
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/styleguide-posts',
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