<?php
/**
 * ACF Fields for Styleguide Trennlinie Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_styleguide_trennlinie',
    'title' => 'Styleguide Trennlinie',
    'fields' => array(
        
        // =============================================================================
        // TRENNLINIE EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_stl_line_tab',
            'label' => 'Trennlinie',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stl_thickness',
            'label' => 'Stärke der Trennlinie',
            'name' => 'stl_thickness',
            'type' => 'select',
            'choices' => array(
                '1px' => '1px (Dünn)',
                '2px' => '2px (Standard)',
                '3px' => '3px (Stark)',
            ),
            'default_value' => '2px',
            'wrapper' => array('width' => '33'),
            'instructions' => 'Wählen Sie die Stärke der Trennlinie',
        ),
        array(
            'key' => 'field_stl_color',
            'label' => 'Farbe der Trennlinie',
            'name' => 'stl_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'primary',
            'wrapper' => array('width' => '33'),
            'instructions' => 'Wählen Sie die Farbe der Trennlinie',
        ),
        array(
            'key' => 'field_stl_width',
            'label' => 'Breite der Trennlinie',
            'name' => 'stl_width',
            'type' => 'select',
            'choices' => array(
                '100%' => '100% (Vollbreite)',
                '60%' => '60% (Mittel)',
                '30%' => '30% (Schmal)',
            ),
            'default_value' => '100%',
            'wrapper' => array('width' => '34'),
            'instructions' => 'Wählen Sie die Breite der Trennlinie',
        ),
        
        // =============================================================================
        // SPACING EINSTELLUNGEN
        // =============================================================================
        array(
            'key' => 'field_stl_spacing_tab',
            'label' => 'Abstände',
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        ),
        array(
            'key' => 'field_stl_spacing_top',
            'label' => 'Abstand oben',
            'name' => 'stl_spacing_top',
            'type' => 'select',
            'choices' => array(
                'none' => 'Kein Abstand',
                'xs' => 'XS - 12px (Mobile)',
                'sm' => 'SM - 16px (Tablet)',
                'md' => 'MD - 24px (Desktop <1200)',
                'lg' => 'LG - 32px (Desktop >1200)',
            ),
            'default_value' => 'md',
            'wrapper' => array('width' => '50'),
            'instructions' => 'Abstand oberhalb der Trennlinie',
        ),
        array(
            'key' => 'field_stl_spacing_bottom',
            'label' => 'Abstand unten',
            'name' => 'stl_spacing_bottom',
            'type' => 'select',
            'choices' => array(
                'none' => 'Kein Abstand',
                'xs' => 'XS - 12px (Mobile)',
                'sm' => 'SM - 16px (Tablet)',
                'md' => 'MD - 24px (Desktop <1200)',
                'lg' => 'LG - 32px (Desktop >1200)',
            ),
            'default_value' => 'md',
            'wrapper' => array('width' => '50'),
            'instructions' => 'Abstand unterhalb der Trennlinie',
        ),
        
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/styleguide-trennlinie',
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