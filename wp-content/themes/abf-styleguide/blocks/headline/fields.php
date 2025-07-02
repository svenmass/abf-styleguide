<?php
/**
 * Headline Block Fields
 */

if (!defined('ABSPATH')) {
    exit;
}

// Headline Block Felder - wird über abf_register_block_field_group registriert
abf_register_block_field_group(array(
    'key' => 'group_headline_block',
    'title' => 'Headline Block',
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
            'label' => 'H-Klasse',
            'name' => 'headline_tag',
            'type' => 'select',
            'instructions' => 'Wähle die HTML-Tag für die Headline',
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
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_headline_size',
            'label' => 'Schriftgröße (px)',
            'name' => 'headline_size',
            'type' => 'select',
            'instructions' => 'Wähle die Schriftgröße',
            'required' => 1,
            'choices' => array(
                '12' => '12px (Small)',
                '18' => '18px (Body)',
                '24' => '24px (H2)',
                '36' => '36px (H1)',
                '48' => '48px (Large)',
            ),
            'default_value' => '24',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_headline_weight',
            'label' => 'Schriftschnitt',
            'name' => 'headline_weight',
            'type' => 'select',
            'instructions' => 'Wähle den Schriftschnitt',
            'required' => 1,
            'choices' => array(
                '300' => 'Light (300)',
                '400' => 'Regular (400)',
                '700' => 'Bold (700)',
            ),
            'default_value' => '400',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_headline_color',
            'label' => 'Farbe',
            'name' => 'headline_color',
            'type' => 'select',
            'instructions' => 'Wähle eine Farbe für die Headline',
            'required' => 1,
            'choices' => array(
                'inherit' => 'Standard (inherit)',
                'primary' => 'Primärfarbe',
                'secondary' => 'Sekundärfarbe',
            ),
            'default_value' => 'inherit',
            'wrapper' => array(
                'width' => '25',
            ),
        ),
        array(
            'key' => 'field_headline_padding',
            'label' => 'Padding',
            'name' => 'headline_padding',
            'type' => 'select',
            'instructions' => 'Wähle das Padding basierend auf den Spacing-Größen',
            'required' => 0,
            'choices' => array(
                'none' => 'Kein Padding',
                'xs' => 'XS (12px mobile)',
                'sm' => 'SM (16px tablet)',
                'md' => 'MD (24px desktop)',
                'lg' => 'LG (32px large desktop)',
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
)); 