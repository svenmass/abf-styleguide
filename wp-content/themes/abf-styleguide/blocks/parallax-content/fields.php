<?php
/**
 * Parallax Content Block - ACF Fields
 */

if (!defined('ABSPATH')) {
    exit;
}

// Parallax Content Block Field Group
return array(
    'key' => 'group_parallax_content_block',
    'title' => 'Parallax Content Block Felder',
    'fields' => array(
        array(
            'key' => 'field_parallax_content_elements',
            'label' => 'Content Elemente',
            'name' => 'parallax_content_elements',
            'type' => 'repeater',
            'instructions' => 'Füge beliebig viele Content-Elemente hinzu. Jedes Element hat Text links (50%) und ein weißes Layer rechts (50%).',
            'required' => 0,
            'conditional_logic' => 0,
            'min' => 1,
            'max' => 0, // Unlimited
            'layout' => 'block',
            'button_label' => 'Element hinzufügen',
            'sub_fields' => array(
                // Background Settings
                array(
                    'key' => 'field_content_background_color',
                    'label' => 'Hintergrundfarbe',
                    'name' => 'background_color',
                    'type' => 'select',
                    'instructions' => 'Wähle eine Hintergrundfarbe für das gesamte Element',
                    'required' => 1,
                    'choices' => abf_get_color_choices(),
                    'default_value' => 'primary',
                ),
                
                // Left Side - Text Content
                array(
                    'key' => 'field_content_headline_text',
                    'label' => 'Headline',
                    'name' => 'headline_text',
                    'type' => 'textarea',
                    'instructions' => 'Gib hier die Hauptüberschrift ein',
                    'required' => 0,
                    'rows' => 2,
                ),
                array(
                    'key' => 'field_content_headline_tag',
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
                    'key' => 'field_content_headline_weight',
                    'label' => 'Headline Gewicht',
                    'name' => 'headline_weight',
                    'type' => 'select',
                    'instructions' => 'Schriftgewicht',
                    'choices' => function_exists('abf_get_typography_font_weights') ? abf_get_typography_font_weights() : array('400' => 'Regular (400)'),
                    'default_value' => '400',
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_content_headline_size',
                    'label' => 'Headline Größe',
                    'name' => 'headline_size',
                    'type' => 'select',
                    'instructions' => 'Wähle die Schriftgröße',
                    'choices' => function_exists('abf_get_typography_font_sizes') ? abf_get_typography_font_sizes() : array('36' => '36px (H1)'),
                    'default_value' => '36',
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_content_headline_color',
                    'label' => 'Headline Farbe',
                    'name' => 'headline_color',
                    'type' => 'select',
                    'instructions' => 'Textfarbe der Headline',
                    'choices' => abf_get_color_choices(),
                    'default_value' => 'white',
                    'wrapper' => array('width' => '25'),
                ),
                
                // Richtext Content
                array(
                    'key' => 'field_content_richtext',
                    'label' => 'Richtext Inhalt',
                    'name' => 'richtext_content',
                    'type' => 'wysiwyg',
                    'instructions' => 'Füge hier den Haupttext ein',
                    'required' => 0,
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                    'delay' => 0,
                ),
                
                // Button Settings
                array(
                    'key' => 'field_content_show_button',
                    'label' => 'Button anzeigen',
                    'name' => 'show_button',
                    'type' => 'true_false',
                    'instructions' => 'Soll ein Button angezeigt werden?',
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_content_button_text',
                    'label' => 'Button Text',
                    'name' => 'button_text',
                    'type' => 'text',
                    'instructions' => 'Text für den Button',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '50'),
                ),
                array(
                    'key' => 'field_content_button_url',
                    'label' => 'Button URL',
                    'name' => 'button_url',
                    'type' => 'url',
                    'instructions' => 'Ziel-URL für den Button',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '50'),
                ),
                array(
                    'key' => 'field_content_button_bg_color',
                    'label' => 'Button Hintergrund',
                    'name' => 'button_bg_color',
                    'type' => 'select',
                    'instructions' => 'Hintergrundfarbe des Buttons',
                    'choices' => abf_get_color_choices(),
                    'default_value' => 'secondary',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_content_button_text_color',
                    'label' => 'Button Text',
                    'name' => 'button_text_color',
                    'type' => 'select',
                    'instructions' => 'Textfarbe des Buttons',
                    'choices' => abf_get_color_choices(),
                    'default_value' => 'white',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_content_button_hover_bg_color',
                    'label' => 'Button Hover BG',
                    'name' => 'button_hover_bg_color',
                    'type' => 'select',
                    'instructions' => 'Hover-Hintergrundfarbe',
                    'choices' => abf_get_color_choices(),
                    'default_value' => 'primary',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
                array(
                    'key' => 'field_content_button_hover_text_color',
                    'label' => 'Button Hover Text',
                    'name' => 'button_hover_text_color',
                    'type' => 'select',
                    'instructions' => 'Hover-Textfarbe',
                    'choices' => abf_get_color_choices(),
                    'default_value' => 'white',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array('width' => '25'),
                ),
                
                // Right Side - Media Content
                array(
                    'key' => 'field_content_media_type',
                    'label' => 'Media Typ',
                    'name' => 'media_type',
                    'type' => 'radio',
                    'instructions' => 'Wähle zwischen Bild oder Video für das weiße Layer',
                    'required' => 1,
                    'choices' => array(
                        'image' => 'Bild',
                        'video' => 'Video',
                    ),
                    'default_value' => 'image',
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_content_image',
                    'label' => 'Bild',
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => 'Wähle ein Bild für das weiße Layer',
                    'required' => 0,
                    'return_format' => 'array',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_media_type',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_content_image_fit',
                    'label' => 'Bild Darstellung',
                    'name' => 'image_fit',
                    'type' => 'radio',
                    'instructions' => 'Wie soll das Bild dargestellt werden?',
                    'choices' => array(
                        'contain' => 'Contain (komplettes Bild sichtbar, mit Padding)',
                        'cover' => 'Cover (Bild füllt Layer aus, wird beschnitten)',
                    ),
                    'default_value' => 'cover',
                    'layout' => 'vertical',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_media_type',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_content_video',
                    'label' => 'Video',
                    'name' => 'video',
                    'type' => 'file',
                    'instructions' => 'Wähle ein Video für das weiße Layer (wird automatisch als Cover dargestellt)',
                    'required' => 0,
                    'return_format' => 'array',
                    'mime_types' => 'mp4,webm,ogg',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_content_media_type',
                                'operator' => '==',
                                'value' => 'video',
                            ),
                        ),
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
                'value' => 'acf/parallax-content',
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
    'description' => 'Felder für den Parallax Content Block mit unbegrenzten Repeater-Elementen',
); 