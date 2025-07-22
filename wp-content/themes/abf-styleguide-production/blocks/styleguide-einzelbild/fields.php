<?php
/**
 * ACF Fields for Styleguide Einzelbild Block
 */

return array(
    'key' => 'group_styleguide_einzelbild',
    'title' => 'Styleguide Einzelbild',
    'fields' => array(
        
        // =============================================================================
        // BILD EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_se_image_tab',
            'label' => 'Bild',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_se_image',
            'label' => 'Bild',
            'name' => 'se_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'required' => 1,
            'instructions' => 'Wählen Sie das Bild aus, das im 16:9 Format angezeigt werden soll',
        ),
        array(
            'key' => 'field_se_alt_text',
            'label' => 'Alt-Text (Optional)',
            'name' => 'se_alt_text',
            'type' => 'text',
            'instructions' => 'Alternativer Text für Barrierefreiheit. Leer lassen, um den Alt-Text aus der Mediathek zu verwenden.',
            'placeholder' => 'Beschreibung des Bildes für Screenreader',
        ),
        array(
            'key' => 'field_se_caption',
            'label' => 'Bildunterschrift (Optional)',
            'name' => 'se_caption',
            'type' => 'textarea',
            'rows' => 3,
            'instructions' => 'Optionale Bildunterschrift, die unter dem Bild angezeigt wird',
            'placeholder' => 'Bildunterschrift...',
        ),
        
        // =============================================================================
        // DOWNLOAD EINSTELLUNGEN  
        // =============================================================================
        array(
            'key' => 'field_se_download_tab',
            'label' => 'Download',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_se_show_download',
            'label' => 'Download-Button anzeigen',
            'name' => 'se_show_download',
            'type' => 'true_false',
            'default_value' => 1,
            'ui' => 1,
            'instructions' => 'Download-Button in der Lightbox anzeigen',
        ),
        array(
            'key' => 'field_se_download_text',
            'label' => 'Download-Text',
            'name' => 'se_download_text',
            'type' => 'text',
            'default_value' => 'Bild herunterladen',
            'instructions' => 'Text für den Download-Button',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_se_show_download',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_se_custom_download',
            'label' => 'Anderes Download-Bild (Optional)',
            'name' => 'se_custom_download',
            'type' => 'image',
            'return_format' => 'array',
            'instructions' => 'Optional: Anderes Bild für den Download bereitstellen (z.B. höhere Auflösung)',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_se_show_download',
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
                'value' => 'acf/styleguide-einzelbild',
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