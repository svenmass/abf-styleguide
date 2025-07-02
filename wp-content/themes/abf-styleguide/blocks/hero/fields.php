<?php
/**
 * Hero Block Fields
 */

if (!defined('ABSPATH')) {
    exit;
}

// Hero Block Felder - wird über abf_register_block_field_group registriert
abf_register_block_field_group(array(
    'key' => 'group_hero_block',
    'title' => 'Hero Block',
    'fields' => array(
        array(
            'key' => 'field_hero_title',
            'label' => 'Titel',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Gib hier den Haupttitel für den Hero-Bereich ein',
            'required' => 1,
        ),
        array(
            'key' => 'field_hero_text',
            'label' => 'Text',
            'name' => 'text',
            'type' => 'wysiwyg',
            'instructions' => 'Gib hier den Beschreibungstext ein',
            'required' => 0,
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 0,
            'delay' => 0,
        ),
        array(
            'key' => 'field_hero_background_image',
            'label' => 'Hintergrundbild',
            'name' => 'background_image',
            'type' => 'image',
            'instructions' => 'Wähle ein Hintergrundbild aus (optional)',
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_hero_button_text',
            'label' => 'Button Text',
            'name' => 'button_text',
            'type' => 'text',
            'instructions' => 'Text für den Call-to-Action Button',
            'required' => 0,
            'wrapper' => array(
                'width' => '50',
            ),
        ),
        array(
            'key' => 'field_hero_button_link',
            'label' => 'Button Link',
            'name' => 'button_link',
            'type' => 'url',
            'instructions' => 'Link für den Button',
            'required' => 0,
            'wrapper' => array(
                'width' => '50',
            ),
        ),
        array(
            'key' => 'field_hero_button_color',
            'label' => 'Button Farbe',
            'name' => 'button_color',
            'type' => 'select',
            'instructions' => 'Wähle eine Farbe für den Button',
            'required' => 0,
            'choices' => array(
                'primary' => 'Primärfarbe',
                'secondary' => 'Sekundärfarbe',
            ),
            'default_value' => 'primary',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/hero',
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
    'description' => 'Felder für den Hero-Block',
)); 