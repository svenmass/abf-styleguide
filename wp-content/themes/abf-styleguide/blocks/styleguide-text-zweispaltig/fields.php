<?php
/**
 * ACF Fields for Styleguide Text Zweispaltig Block
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get dynamic color choices with fallbacks
$color_choices = array(
    'inherit' => 'Standard Text',
    'primary' => 'Primary',
    'secondary' => 'Secondary'
);

// Only try to get colors if function exists
if (function_exists('abf_get_colors')) {
    $colors = abf_get_colors();
    if (!empty($colors) && is_array($colors)) {
        foreach ($colors as $color) {
            if (isset($color['name']) && !empty($color['name'])) {
                $color_choices[sanitize_title($color['name'])] = $color['name'];
            }
        }
    }
}

// Return ACF Field Group configuration
return array(
    'key' => 'group_styleguide_text_zweispaltig',
    'title' => 'Styleguide Text Zweispaltig Settings',
    'fields' => array(
        array(
            'key' => 'field_stz_headline_text',
            'label' => 'Headline',
            'name' => 'stz_headline_text',
            'type' => 'text',
            'wrapper' => array('width' => '50'),
        ),
        array(
            'key' => 'field_stz_headline_tag',
            'label' => 'Headline Tag',
            'name' => 'stz_headline_tag',
            'type' => 'select',
            'choices' => array('h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4'),
            'default_value' => 'h2',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_stz_headline_color',
            'label' => 'Headline Farbe',
            'name' => 'stz_headline_color',
            'type' => 'select',
            'choices' => $color_choices,
            'default_value' => 'inherit',
            'wrapper' => array('width' => '25'),
        ),
        array(
            'key' => 'field_stz_left_text',
            'label' => 'Linker Text',
            'name' => 'stz_left_text',
            'type' => 'wysiwyg',
            'required' => 1,
            'wrapper' => array('width' => '50'),
        ),
        array(
            'key' => 'field_stz_right_text',
            'label' => 'Rechter Text',
            'name' => 'stz_right_text',
            'type' => 'wysiwyg',
            'required' => 1,
            'wrapper' => array('width' => '50'),
        ),
        array(
            'key' => 'field_stz_section_spacing',
            'label' => 'Abstand',
            'name' => 'stz_section_spacing',
            'type' => 'select',
            'choices' => array('default' => 'Standard', 'small' => 'Klein', 'large' => 'Groß'),
            'default_value' => 'default',
            'wrapper' => array('width' => '50'),
        ),
        array(
            'key' => 'field_stz_container_width',
            'label' => 'Container Breite',
            'name' => 'stz_container_width',
            'type' => 'select',
            'choices' => array('content' => 'Content', 'wide' => 'Breit', 'full' => 'Vollbreite'),
            'default_value' => 'content',
            'wrapper' => array('width' => '50'),
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
); 