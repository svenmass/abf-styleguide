<?php
/**
 * ACF Fields for Headline Block
 */

// Get color choices from the main function
$color_choices = function_exists('abf_get_color_choices') ? abf_get_color_choices() : array();

return array(
    'key' => 'group_headline_block',
    'title' => 'Headline Block Felder',
    'fields' => array(
        array(
            'key' => 'field_headline_text',
            'label' => 'Headline Text',
            'name' => 'headline_text',
            'type' => 'text',
            'instructions' => 'Gib hier den Text für die Headline ein',
            'required' => 1,
        ),
        array(
            'key' => 'field_headline_tag',
            'label' => 'HTML Tag',
            'name' => 'headline_tag',
            'type' => 'select',
            'instructions' => 'Wähle das HTML-Tag für die Headline',
            'required' => 1,
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
            'key' => 'field_headline_size',
            'label' => 'Schriftgröße',
            'name' => 'headline_size',
            'type' => 'select',
            'instructions' => 'Wähle die Schriftgröße',
            'required' => 1,
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
            'key' => 'field_headline_weight',
            'label' => 'Schriftschnitt',
            'name' => 'headline_weight',
            'type' => 'select',
            'instructions' => 'Wähle den Schriftschnitt',
            'required' => 1,
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
            'key' => 'field_headline_color',
            'label' => 'Textfarbe',
            'name' => 'headline_color',
            'type' => 'select',
            'instructions' => 'Wähle eine Farbe für die Headline',
            'required' => 1,
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_headline_padding',
            'label' => 'Abstand',
            'name' => 'headline_padding',
            'type' => 'select',
            'instructions' => 'Wähle den Abstand um die Headline',
            'required' => 0,
            'choices' => array(
                'none' => 'Kein Abstand',
                'xs' => 'XS (12px)',
                'sm' => 'SM (16px)',
                'md' => 'MD (24px)',
                'lg' => 'LG (32px)',
            ),
            'default_value' => 'md',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/headline',
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
    'description' => 'Felder für den Headline-Block',
); 