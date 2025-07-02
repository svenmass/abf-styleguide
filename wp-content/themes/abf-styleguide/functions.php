<?php
// Enqueue custom fonts
function abf_styleguide_enqueue_fonts() {
    wp_enqueue_style('abf-custom-fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), null);
}
add_action('wp_enqueue_scripts', 'abf_styleguide_enqueue_fonts');

// Dequeue default WordPress fonts
function abf_styleguide_dequeue_fonts() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // WooCommerce block CSS
}
add_action('wp_enqueue_scripts', 'abf_styleguide_dequeue_fonts', 100);

// Add ACF Options Page
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Theme Einstellungen',
        'menu_title'    => 'Theme Einstellungen',
        'menu_slug'     => 'theme-einstellungen',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

// Add repeater field for colors in ACF Options Page
if( function_exists('acf_add_local_field_group') ) {
    acf_add_local_field_group(array(
        'key' => 'group_theme_colors',
        'title' => 'Theme Farben',
        'fields' => array(
            array(
                'key' => 'field_colors',
                'label' => 'Farben',
                'name' => 'colors',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_color_name',
                        'label' => 'Farbname',
                        'name' => 'color_name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_color_value',
                        'label' => 'Farbwert',
                        'name' => 'color_value',
                        'type' => 'color_picker',
                    ),
                ),
                'button_label' => 'Farbe hinzufügen',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'theme-einstellungen',
                ),
            ),
        ),
    ));
}

// Save colors to JSON file
function abf_styleguide_save_colors_to_json() {
    $colors = get_field('colors', 'option');
    $colors_array = array();

    if ($colors) {
        foreach ($colors as $color) {
            $colors_array[] = array(
                'name' => $color['color_name'],
                'color' => $color['color_value'],
            );
        }
    }

    $json_data = json_encode($colors_array);
    file_put_contents(get_template_directory() . '/colors.json', $json_data);
}
add_action('acf/save_post', 'abf_styleguide_save_colors_to_json', 20);

// Register theme colors in Gutenberg from JSON
function abf_styleguide_gutenberg_colors() {
    $colors_json = file_get_contents(get_template_directory() . '/colors.json');
    $colors_array = json_decode($colors_json, true);
    $gutenberg_colors = array();

    if ($colors_array) {
        foreach ($colors_array as $color) {
            $gutenberg_colors[] = array(
                'name'  => __($color['name'], 'abf-styleguide'),
                'slug'  => sanitize_title($color['name']),
                'color' => $color['color'],
            );
        }
    }

    add_theme_support('editor-color-palette', $gutenberg_colors);
}
add_action('after_setup_theme', 'abf_styleguide_gutenberg_colors');
