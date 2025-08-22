<?php
if (!defined('ABSPATH')) { exit; }

return array(
    'key' => 'group_block_styleguide_farben_details',
    'title' => 'Block: Styleguide-Farben-Details',
    'fields' => array(
        array(
            'key' => 'field_palette_ref_details',
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
                'value' => 'acf/styleguide-farben-details',
            ),
        ),
    ),
    'active' => true,
);


