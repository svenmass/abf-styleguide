<?php
/**
 * Hero Block Fields
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Hero Block Fields
 */
function abf_hero_block_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_hero_block_fields',
        'title' => 'Hero Block Felder',
        'fields' => array(
            array(
                'key' => 'field_hero_title',
                'label' => 'Titel',
                'name' => 'title',
                'type' => 'text',
                'instructions' => 'Gib hier den Haupttitel für den Hero-Bereich ein',
                'required' => 1,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hero_text',
                'label' => 'Text',
                'name' => 'text',
                'type' => 'wysiwyg',
                'instructions' => 'Gib hier den Beschreibungstext ein',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
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
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
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
                    'class' => '',
                    'id' => '',
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
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hero_button_color',
                'label' => 'Button Farbe',
                'name' => 'button_color',
                'type' => 'select',
                'instructions' => 'Wähle eine Farbe für den Button',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => abf_get_color_choices(),
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
}

// Register fields when ACF is ready
add_action('acf/init', 'abf_hero_block_fields'); 