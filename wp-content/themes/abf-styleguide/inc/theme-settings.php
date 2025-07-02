<?php
/**
 * Theme Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Theme Settings Page
 */
function abf_add_theme_settings_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => __('Theme Settings', 'abf-styleguide'),
            'menu_title' => __('Theme Settings', 'abf-styleguide'),
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
            'position' => 59,
            'icon_url' => 'dashicons-admin-generic',
        ));
    }
}
add_action('acf/init', 'abf_add_theme_settings_page');

/**
 * Register ACF Fields for Theme Settings
 */
function abf_register_theme_settings_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_theme_settings',
            'title' => 'Theme Settings',
            'fields' => array(
                array(
                    'key' => 'field_colors',
                    'label' => 'Farben',
                    'name' => 'colors',
                    'type' => 'repeater',
                    'instructions' => 'Fügen Sie hier die Farben für das Theme hinzu.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => 'Farbe hinzufügen',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_color_name',
                            'label' => 'Name',
                            'name' => 'name',
                            'type' => 'text',
                            'instructions' => 'Name der Farbe (z.B. Primärfarbe)',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_color_value',
                            'label' => 'Farbwert',
                            'name' => 'value',
                            'type' => 'color_picker',
                            'instructions' => 'Wählen Sie die Farbe aus',
                            'required' => 1,
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-settings',
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
            'description' => '',
        ));
    }
}
add_action('acf/init', 'abf_register_theme_settings_fields');

/**
 * Save colors to JSON file when theme settings are updated
 */
function abf_save_colors_to_json($value, $post_id, $field) {
    if ($field['name'] === 'colors') {
        $colors_file = get_template_directory() . '/colors.json';
        $colors_data = array(
            'colors' => $value,
            'updated' => current_time('mysql'),
        );
        
        file_put_contents($colors_file, json_encode($colors_data, JSON_PRETTY_PRINT));
    }
    
    return $value;
}
add_filter('acf/update_value', 'abf_save_colors_to_json', 10, 3);

/**
 * Alternative save function for backward compatibility
 */
function abf_styleguide_save_colors_to_json() {
    $colors = get_field('colors', 'option');
    $colors_array = array();

    if ($colors) {
        foreach ($colors as $color) {
            $colors_array[] = array(
                'name' => $color['name'] ?? $color['color_name'] ?? '',
                'value' => $color['value'] ?? $color['color_value'] ?? '',
            );
        }
    }

    $colors_data = array(
        'colors' => $colors_array,
        'updated' => current_time('mysql'),
    );
    
    file_put_contents(get_template_directory() . '/colors.json', json_encode($colors_data, JSON_PRETTY_PRINT));
}
add_action('acf/save_post', 'abf_styleguide_save_colors_to_json', 20);

/**
 * Get colors from JSON file
 */
function abf_get_colors() {
    $colors_file = get_template_directory() . '/colors.json';
    
    if (file_exists($colors_file)) {
        $colors = json_decode(file_get_contents($colors_file), true);
        
        // Handle both old and new JSON structure
        if (isset($colors['colors'])) {
            return $colors['colors'];
        } elseif (is_array($colors)) {
            // Old structure: direct array
            return $colors;
        }
    }
    
    return array();
}

/**
 * Register theme colors in Gutenberg from JSON
 */
function abf_styleguide_gutenberg_colors() {
    $colors = abf_get_colors();
    $gutenberg_colors = array();

    if ($colors) {
        foreach ($colors as $color) {
            $color_name = $color['name'] ?? $color['color_name'] ?? '';
            $color_value = $color['value'] ?? $color['color_value'] ?? '';
            
            if ($color_name && $color_value) {
                $gutenberg_colors[] = array(
                    'name'  => __($color_name, 'abf-styleguide'),
                    'slug'  => sanitize_title($color_name),
                    'color' => $color_value,
                );
            }
        }
    }

    add_theme_support('editor-color-palette', $gutenberg_colors);
}
add_action('after_setup_theme', 'abf_styleguide_gutenberg_colors'); 