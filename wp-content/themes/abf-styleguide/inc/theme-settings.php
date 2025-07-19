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
                
                // Typography Section
                array(
                    'key' => 'field_typography_section',
                    'label' => 'Typography',
                    'name' => 'typography_section',
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
                
                // Font Sizes
                array(
                    'key' => 'field_font_sizes',
                    'label' => 'Schriftgrößen',
                    'name' => 'font_sizes',
                    'type' => 'repeater',
                    'instructions' => 'Definieren Sie hier die verfügbaren Schriftgrößen für alle Blöcke.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 1,
                    'max' => 20,
                    'layout' => 'table',
                    'button_label' => 'Schriftgröße hinzufügen',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_font_size_key',
                            'label' => 'Schlüssel',
                            'name' => 'key',
                            'type' => 'text',
                            'instructions' => 'Eindeutiger Schlüssel (z.B. small, body, h1, xl)',
                            'required' => 1,
                            'wrapper' => array('width' => '20'),
                        ),
                        array(
                            'key' => 'field_font_size_label',
                            'label' => 'Label',
                            'name' => 'label',
                            'type' => 'text',
                            'instructions' => 'Anzeige-Name im Backend (z.B. "Small (12px)")',
                            'required' => 1,
                            'wrapper' => array('width' => '30'),
                        ),
                        array(
                            'key' => 'field_font_size_desktop',
                            'label' => 'Desktop',
                            'name' => 'desktop',
                            'type' => 'number',
                            'instructions' => 'Größe in px für Desktop',
                            'required' => 1,
                            'min' => 8,
                            'max' => 200,
                            'step' => 1,
                            'append' => 'px',
                            'wrapper' => array('width' => '20'),
                        ),
                        array(
                            'key' => 'field_font_size_tablet',
                            'label' => 'Tablet',
                            'name' => 'tablet',
                            'type' => 'number',
                            'instructions' => 'Größe in px für Tablet (optional - wird automatisch berechnet)',
                            'required' => 0,
                            'min' => 8,
                            'max' => 200,
                            'step' => 1,
                            'append' => 'px',
                            'wrapper' => array('width' => '15'),
                        ),
                        array(
                            'key' => 'field_font_size_mobile',
                            'label' => 'Mobile',
                            'name' => 'mobile',
                            'type' => 'number',
                            'instructions' => 'Größe in px für Mobile (optional - wird automatisch berechnet)',
                            'required' => 0,
                            'min' => 8,
                            'max' => 200,
                            'step' => 1,
                            'append' => 'px',
                            'wrapper' => array('width' => '15'),
                        ),
                    ),
                ),
                
                // Font Weights
                array(
                    'key' => 'field_font_weights',
                    'label' => 'Schriftgewichte',
                    'name' => 'font_weights',
                    'type' => 'repeater',
                    'instructions' => 'Definieren Sie hier die verfügbaren Schriftgewichte für alle Blöcke.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 1,
                    'max' => 10,
                    'layout' => 'table',
                    'button_label' => 'Schriftgewicht hinzufügen',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_font_weight_key',
                            'label' => 'Schlüssel',
                            'name' => 'key',
                            'type' => 'text',
                            'instructions' => 'Eindeutiger Schlüssel (z.B. light, regular, bold)',
                            'required' => 1,
                            'wrapper' => array('width' => '30'),
                        ),
                        array(
                            'key' => 'field_font_weight_label',
                            'label' => 'Label',
                            'name' => 'label',
                            'type' => 'text',
                            'instructions' => 'Anzeige-Name im Backend (z.B. "Light (300)")',
                            'required' => 1,
                            'wrapper' => array('width' => '40'),
                        ),
                        array(
                            'key' => 'field_font_weight_value',
                            'label' => 'Wert',
                            'name' => 'value',
                            'type' => 'select',
                            'instructions' => 'CSS font-weight Wert',
                            'required' => 1,
                            'choices' => array(
                                '100' => '100 (Thin)',
                                '200' => '200 (Extra Light)',
                                '300' => '300 (Light)',
                                '400' => '400 (Regular)',
                                '500' => '500 (Medium)',
                                '600' => '600 (Semi Bold)',
                                '700' => '700 (Bold)',
                                '800' => '800 (Extra Bold)',
                                '900' => '900 (Black)',
                            ),
                            'wrapper' => array('width' => '30'),
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
 * Save typography to JSON file when theme settings are updated
 */
function abf_save_typography_to_json($value, $post_id, $field) {
    if ($field['name'] === 'font_sizes' || $field['name'] === 'font_weights') {
        $typography_file = get_template_directory() . '/typography.json';
        
        // Get all typography data
        $font_sizes = get_field('font_sizes', 'option') ?: array();
        $font_weights = get_field('font_weights', 'option') ?: array();
        
        // If we're updating font_sizes, use the new value
        if ($field['name'] === 'font_sizes') {
            $font_sizes = $value;
        }
        // If we're updating font_weights, use the new value
        if ($field['name'] === 'font_weights') {
            $font_weights = $value;
        }
        
        $typography_data = array(
            'font_sizes' => $font_sizes,
            'font_weights' => $font_weights,
            'updated' => current_time('mysql'),
        );
        
        file_put_contents($typography_file, json_encode($typography_data, JSON_PRETTY_PRINT));
        
        // Generate CSS file for frontend
        abf_generate_typography_css();
    }
    
    return $value;
}
add_filter('acf/update_value', 'abf_save_typography_to_json', 10, 3);

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

// =============================================================================
// TYPOGRAPHY HELPER FUNCTIONS
// =============================================================================

/**
 * Generate CSS file from typography settings
 */
function abf_generate_typography_css() {
    $font_sizes = get_field('font_sizes', 'option') ?: array();
    $font_weights = get_field('font_weights', 'option') ?: array();
    
    // If no data, use defaults
    if (empty($font_sizes)) {
        $font_sizes = abf_get_default_font_sizes();
    }
    if (empty($font_weights)) {
        $font_weights = abf_get_default_font_weights();
    }
    
    $css = "/* Auto-generated Typography CSS from Theme Settings */\n";
    $css .= ":root {\n";
    
    // Generate CSS Custom Properties for font sizes
    foreach ($font_sizes as $size) {
        if (empty($size['key']) || empty($size['desktop'])) continue;
        $css .= "    --font-size-" . sanitize_title($size['key']) . ": " . intval($size['desktop']) . "px;\n";
    }
    
    // Generate CSS Custom Properties for font weights
    foreach ($font_weights as $weight) {
        if (empty($weight['key']) || empty($weight['value'])) continue;
        $css .= "    --font-weight-" . sanitize_title($weight['key']) . ": " . intval($weight['value']) . ";\n";
    }
    
    $css .= "}\n\n";
    
    // Generate responsive font sizes
    $css .= "/* Responsive Typography - Tablet */\n";
    $css .= "@media (max-width: 991px) {\n";
    $css .= "    :root {\n";
    foreach ($font_sizes as $size) {
        if (empty($size['key']) || empty($size['desktop'])) continue;
        $tablet_size = !empty($size['tablet']) ? intval($size['tablet']) : round(intval($size['desktop']) * 0.85);
        $css .= "        --font-size-" . sanitize_title($size['key']) . ": " . $tablet_size . "px;\n";
    }
    $css .= "    }\n}\n\n";
    
    $css .= "/* Responsive Typography - Mobile */\n";
    $css .= "@media (max-width: 575px) {\n";
    $css .= "    :root {\n";
    foreach ($font_sizes as $size) {
        if (empty($size['key']) || empty($size['desktop'])) continue;
        $mobile_size = !empty($size['mobile']) ? intval($size['mobile']) : round(intval($size['desktop']) * 0.75);
        $css .= "        --font-size-" . sanitize_title($size['key']) . ": " . $mobile_size . "px;\n";
    }
    $css .= "    }\n}\n\n";
    
    // Generate responsive overrides for inline styles
    $css .= "/* Auto-responsive inline font-size overrides */\n";
    foreach ($font_sizes as $size) {
        if (empty($size['key']) || empty($size['desktop'])) continue;
        $desktop = intval($size['desktop']);
        $tablet = !empty($size['tablet']) ? intval($size['tablet']) : round($desktop * 0.85);
        $mobile = !empty($size['mobile']) ? intval($size['mobile']) : round($desktop * 0.75);
        
        $css .= "[style*=\"font-size: {$desktop}px\"], [style*=\"font-size:{$desktop}px\"] {\n";
        $css .= "    @media (max-width: 991px) { font-size: {$tablet}px !important; }\n";
        $css .= "    @media (max-width: 575px) { font-size: {$mobile}px !important; }\n";
        $css .= "}\n";
    }
    
    // Save to CSS file
    $css_file = get_template_directory() . '/assets/css/typography-generated.css';
    file_put_contents($css_file, $css);
}

/**
 * Get font sizes for ACF field choices
 */
function abf_get_typography_font_sizes() {
    $font_sizes = get_field('font_sizes', 'option');
    
    if (empty($font_sizes)) {
        $font_sizes = abf_get_default_font_sizes();
    }
    
    $choices = array();
    foreach ($font_sizes as $size) {
        if (empty($size['key']) || empty($size['label']) || empty($size['desktop'])) continue;
        $choices[$size['desktop']] = $size['label'];
    }
    
    return $choices;
}

/**
 * Get font weights for ACF field choices
 */
function abf_get_typography_font_weights() {
    $font_weights = get_field('font_weights', 'option');
    
    if (empty($font_weights)) {
        $font_weights = abf_get_default_font_weights();
    }
    
    $choices = array();
    foreach ($font_weights as $weight) {
        if (empty($weight['key']) || empty($weight['label']) || empty($weight['value'])) continue;
        $choices[$weight['value']] = $weight['label'];
    }
    
    return $choices;
}

/**
 * Get default font sizes if none are set
 */
function abf_get_default_font_sizes() {
    return array(
        array('key' => 'small', 'label' => 'Small (12px)', 'desktop' => 12, 'tablet' => 11, 'mobile' => 10),
        array('key' => 'body', 'label' => 'Body (18px)', 'desktop' => 18, 'tablet' => 16, 'mobile' => 14),
        array('key' => 'h2', 'label' => 'H2 (24px)', 'desktop' => 24, 'tablet' => 20, 'mobile' => 18),
        array('key' => 'h1', 'label' => 'H1 (36px)', 'desktop' => 36, 'tablet' => 30, 'mobile' => 24),
        array('key' => 'xl', 'label' => 'XL (48px)', 'desktop' => 48, 'tablet' => 40, 'mobile' => 32),
        array('key' => 'xxl', 'label' => 'XXL (60px)', 'desktop' => 60, 'tablet' => 48, 'mobile' => 40),
        array('key' => '3xl', 'label' => '3XL (72px)', 'desktop' => 72, 'tablet' => 54, 'mobile' => 45),
    );
}

/**
 * Get default font weights if none are set
 */
function abf_get_default_font_weights() {
    return array(
        array('key' => 'light', 'label' => 'Light (300)', 'value' => 300),
        array('key' => 'regular', 'label' => 'Regular (400)', 'value' => 400),
        array('key' => 'bold', 'label' => 'Bold (700)', 'value' => 700),
    );
}

/**
 * Initialize typography on theme activation
 */
function abf_init_typography() {
    // Create default typography settings if they don't exist
    $font_sizes = get_field('font_sizes', 'option');
    $font_weights = get_field('font_weights', 'option');
    
    if (empty($font_sizes)) {
        update_field('font_sizes', abf_get_default_font_sizes(), 'option');
    }
    
    if (empty($font_weights)) {
        update_field('font_weights', abf_get_default_font_weights(), 'option');
    }
    
    // Generate initial CSS
    abf_generate_typography_css();
}
add_action('after_switch_theme', 'abf_init_typography');

/**
 * Enqueue generated typography CSS
 */
function abf_enqueue_typography_css() {
    $css_file = get_template_directory() . '/assets/css/typography-generated.css';
    $css_url = get_template_directory_uri() . '/assets/css/typography-generated.css';
    
    if (file_exists($css_file)) {
        wp_enqueue_style(
            'abf-typography-generated',
            $css_url,
            array('abf-main-styles'),
            filemtime($css_file)
        );
    }
}
add_action('wp_enqueue_scripts', 'abf_enqueue_typography_css'); 