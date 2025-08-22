<?php
// Defensive ACF local field group for block
if (!defined('ABSPATH')) { exit; }

return array(
    'key' => 'group_block_styleguide_farben_nav',
    'title' => 'Block: Styleguide-Farben',
    'fields' => array(
        array(
            'key' => 'field_palette_ref_nav',
            'label' => 'Palette',
            'name' => 'palette_ref',
            'type' => 'relationship',
            'post_type' => array('abf-farben','abf_palette'),
            'max' => 1,
            'return_format' => 'id',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/styleguide-farben',
            ),
        ),
    ),
    'active' => true,
);


