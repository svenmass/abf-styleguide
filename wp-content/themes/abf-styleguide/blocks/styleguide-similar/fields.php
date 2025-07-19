<?php
/**
 * ACF Fields for Styleguide Similar Posts Block
 */

return array(
    'key' => 'group_styleguide_similar',
    'title' => 'Styleguide Similar Posts Block Felder',
    'fields' => array(
        array(
            'key' => 'field_similar_posts_to_show',
            'label' => 'Anzahl Posts',
            'name' => 'posts_to_show',
            'type' => 'number',
            'instructions' => 'Wie viele ähnliche Posts sollen angezeigt werden?',
            'default_value' => 3,
            'min' => 1,
            'max' => 12,
            'wrapper' => array('width' => '33'),
        ),
        array(
            'key' => 'field_similar_columns',
            'label' => 'Spalten',
            'name' => 'columns',
            'type' => 'select',
            'instructions' => 'Anzahl der Spalten im Grid',
            'choices' => array(
                '2' => '2 Spalten',
                '3' => '3 Spalten',
                '4' => '4 Spalten',
            ),
            'default_value' => '3',
            'wrapper' => array('width' => '33'),
        ),
        array(
            'key' => 'field_similar_base_post',
            'label' => 'Basis-Post',
            'name' => 'base_post',
            'type' => 'post_object',
            'instructions' => 'Zu welchem Post sollen ähnliche Posts gefunden werden? (leer = aktueller Post)',
            'post_type' => array('post'),
            'multiple' => 0,
            'wrapper' => array('width' => '33'),
        ),
        array(
            'key' => 'field_similar_show_title',
            'label' => 'Block-Titel anzeigen',
            'name' => 'show_title',
            'type' => 'true_false',
            'instructions' => 'Soll eine Überschrift über dem Block angezeigt werden?',
            'default_value' => 1,
            'wrapper' => array('width' => '50'),
        ),
        array(
            'key' => 'field_similar_block_title',
            'label' => 'Block-Titel',
            'name' => 'block_title',
            'type' => 'text',
            'instructions' => 'Titel für den Block',
            'default_value' => 'Ähnliche Beiträge',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_similar_show_title',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array('width' => '50'),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/styleguide-similar',
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
    'description' => 'Felder für den Styleguide Similar Posts Block',
); 