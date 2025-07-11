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
                // Logo Section
                array(
                    'key' => 'field_logo_section',
                    'label' => 'Logo & Branding',
                    'name' => 'logo_section',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_desktop_logo',
                    'label' => 'Desktop Logo',
                    'name' => 'desktop_logo',
                    'type' => 'image',
                    'instructions' => 'Logo für Desktop-Ansicht (empfohlen: 200x60px, PNG/SVG)',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => 'png,jpg,jpeg,svg,webp',
                ),
                array(
                    'key' => 'field_mobile_logo',
                    'label' => 'Mobile Logo',
                    'name' => 'mobile_logo',
                    'type' => 'image',
                    'instructions' => 'Logo für Mobile-Ansicht (empfohlen: 150x45px, PNG/SVG)',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => 'png,jpg,jpeg,svg,webp',
                ),
                array(
                    'key' => 'field_logo_alt_text',
                    'label' => 'Logo Alt-Text',
                    'name' => 'logo_alt_text',
                    'type' => 'text',
                    'instructions' => 'Alternativer Text für das Logo (für Barrierefreiheit)',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'z.B. Firmenname Logo',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                
                // Favicon Section
                array(
                    'key' => 'field_favicon_section',
                    'label' => 'Favicon & Icons',
                    'name' => 'favicon_section',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_favicon',
                    'label' => 'Favicon',
                    'name' => 'favicon',
                    'type' => 'image',
                    'instructions' => 'Favicon für Browser-Tabs (empfohlen: 32x32px, ICO/PNG/SVG)',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => 'ico,png,svg',
                ),
                array(
                    'key' => 'field_apple_touch_icon',
                    'label' => 'Apple Touch Icon',
                    'name' => 'apple_touch_icon',
                    'type' => 'image',
                    'instructions' => 'Icon für iOS-Geräte (empfohlen: 180x180px, PNG/SVG)',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => 'png,svg',
                ),
                array(
                    'key' => 'field_android_touch_icon',
                    'label' => 'Android Touch Icon',
                    'name' => 'android_touch_icon',
                    'type' => 'image',
                    'instructions' => 'Icon für Android-Geräte (empfohlen: 192x192px, PNG/SVG)',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => 'png,svg',
                ),
                
                // Colors Section
                array(
                    'key' => 'field_colors_section',
                    'label' => 'Farben',
                    'name' => 'colors_section',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
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

/**
 * Allow SVG uploads in WordPress
 */
function abf_allow_svg_uploads($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'abf_allow_svg_uploads');

/**
 * Sanitize SVG uploads for security
 */
function abf_sanitize_svg($file) {
    if ($file['type'] === 'image/svg+xml') {
        if (!function_exists('simplexml_load_file')) {
            return $file;
        }
        
        // Check if file is actually an SVG
        $file_content = file_get_contents($file['tmp_name']);
        if (strpos($file_content, '<svg') === false) {
            return new WP_Error('invalid_svg', 'Die Datei ist kein gültiges SVG.');
        }
        
        // Basic security check - remove potentially dangerous elements
        $dangerous_elements = array(
            'script', 'object', 'embed', 'iframe', 'foreignobject', 'use'
        );
        
        foreach ($dangerous_elements as $element) {
            if (strpos($file_content, '<' . $element) !== false) {
                return new WP_Error('dangerous_svg', 'Das SVG enthält gefährliche Elemente und kann nicht hochgeladen werden.');
            }
        }
        
        // Check for external references
        if (strpos($file_content, 'href="http') !== false || strpos($file_content, 'xlink:href="http') !== false) {
            return new WP_Error('external_svg', 'Das SVG enthält externe Referenzen und kann nicht hochgeladen werden.');
        }
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'abf_sanitize_svg');

/**
 * Add SVG support to media library
 */
function abf_fix_svg_thumb_display() {
    echo '
    <style>
        td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
            width: 100% !important; 
            height: auto !important; 
        }
    </style>
    ';
}
add_action('admin_head', 'abf_fix_svg_thumb_display'); 