<?php
/**
 * Custom WYSIWYG Toolbar with Colors and Typography from JSON files
 *
 * This adds custom buttons to ACF WYSIWYG fields that allow users to apply
 * colors and font sizes from the theme's JSON configuration files while
 * preserving native formatting like bold, italic, etc.
 */

if (!defined('ABSPATH')) {
    exit;
}

class ABF_WYSIWYG_Toolbar {
    
    private $colors = [];
    private $typography = [];
    
    public function __construct() {
        add_action('admin_init', [$this, 'init_toolbar']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_filter('acf/fields/wysiwyg/toolbars', [$this, 'add_custom_toolbar']);
        
        // Load JSON data
        $this->load_json_data();
    }
    
    /**
     * Load colors and typography from JSON files
     */
    private function load_json_data() {
        $colors_file = get_template_directory() . '/colors.json';
        $typography_file = get_template_directory() . '/typography.json';
        
        // Load colors
        if (file_exists($colors_file)) {
            $colors_json = file_get_contents($colors_file);
            if ($colors_json !== false) {
                $colors_data = json_decode($colors_json, true);
                if ($colors_data && isset($colors_data['colors'])) {
                    $this->colors = $colors_data['colors'];
                    error_log('ABF Toolbar: Loaded ' . count($this->colors) . ' colors from JSON');
                } else {
                    error_log('ABF Toolbar: Colors JSON decode failed or no colors array found');
                }
            } else {
                error_log('ABF Toolbar: Could not read colors file');
            }
        } else {
            error_log('ABF Toolbar: Colors file does not exist: ' . $colors_file);
        }
        
        // Load typography
        if (file_exists($typography_file)) {
            $typography_json = file_get_contents($typography_file);
            if ($typography_json !== false) {
                $typography_data = json_decode($typography_json, true);
                if ($typography_data) {
                    $this->typography = $typography_data;
                    error_log('ABF Toolbar: Loaded typography data with ' . (isset($typography_data['font_sizes']) ? count($typography_data['font_sizes']) : 0) . ' font sizes');
                } else {
                    error_log('ABF Toolbar: Typography JSON decode failed');
                }
            } else {
                error_log('ABF Toolbar: Could not read typography file');
            }
        } else {
            error_log('ABF Toolbar: Typography file does not exist: ' . $typography_file);
        }
    }
    
    /**
     * Initialize TinyMCE customizations
     */
    public function init_toolbar() {
        // Only in admin
        if (!is_admin()) {
            return;
        }
        
        // Check if we're on a page that might have ACF WYSIWYG fields
        global $pagenow;
        if (!in_array($pagenow, ['post.php', 'post-new.php', 'admin.php'])) {
            return;
        }
        
        // Only add filters if not already added
        if (!has_filter('mce_external_plugins', [$this, 'add_tinymce_plugins'])) {
            add_filter('mce_external_plugins', [$this, 'add_tinymce_plugins']);
        }
        
        if (!has_filter('mce_buttons', [$this, 'add_tinymce_buttons'])) {
            add_filter('mce_buttons', [$this, 'add_tinymce_buttons']);
        }
    }
    
    /**
     * Add custom TinyMCE plugins
     */
    public function add_tinymce_plugins($plugin_array) {
        // Add version parameter to force cache refresh
        $version = '1.0.6'; // Increment this to force refresh
        $plugin_array['abf_colors'] = get_template_directory_uri() . '/assets/js/tinymce-abf-colors.js?ver=' . $version;
        $plugin_array['abf_typography'] = get_template_directory_uri() . '/assets/js/tinymce-abf-typography.js?ver=' . $version;
        return $plugin_array;
    }
    
    /**
     * Add custom buttons to TinyMCE toolbar
     */
    public function add_tinymce_buttons($buttons) {
        // Prevent duplicate buttons by checking if they already exist
        if (in_array('abf_colors', $buttons) || in_array('abf_typography', $buttons)) {
            error_log('ABF Toolbar: Buttons already exist in toolbar, skipping duplicate');
            return $buttons;
        }
        
        // Add our custom buttons after the format select
        $pos = array_search('formatselect', $buttons);
        if ($pos !== false) {
            array_splice($buttons, $pos + 1, 0, ['abf_colors', 'abf_typography']);
            error_log('ABF Toolbar: Added buttons after formatselect');
        } else {
            // Fallback: add at the end
            $buttons[] = 'abf_colors';
            $buttons[] = 'abf_typography';
            error_log('ABF Toolbar: Added buttons at end');
        }
        
        return $buttons;
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_scripts($hook) {
        // Only load on admin pages with editor
        if (!is_admin()) {
            return;
        }
        
        // Check if we're on a page that might have WYSIWYG fields
        global $pagenow;
        $allowed_pages = ['post.php', 'post-new.php', 'admin.php'];
        if (!in_array($pagenow, $allowed_pages)) {
            return;
        }
        
        // Enqueue DEBUG script first
        wp_enqueue_script(
            'abf-toolbar-debug',
            get_template_directory_uri() . '/assets/js/debug-toolbar.js',
            ['jquery'],
            '1.0.0',
            true
        );
        
        // Enqueue the main JavaScript file that will create TinyMCE plugins
        wp_enqueue_script(
            'abf-wysiwyg-toolbar',
            get_template_directory_uri() . '/assets/js/wysiwyg-toolbar.js',
            ['jquery', 'abf-toolbar-debug'],
            '1.0.0',
            true
        );
        
        error_log('ABF Toolbar Colors count: ' . count($this->colors));
        error_log('ABF Toolbar Typography font_sizes count: ' . (isset($this->typography['font_sizes']) ? count($this->typography['font_sizes']) : 'not set'));
        
        // Localize the script with our JSON data - ATTACH TO DEBUG SCRIPT
        wp_localize_script('abf-toolbar-debug', 'abfToolbarData', [
            'colors' => $this->colors,
            'typography' => $this->typography,
            'nonce' => wp_create_nonce('abf_wysiwyg_nonce'),
            'templateUri' => get_template_directory_uri(),
            'debug' => true // Enable debug mode
        ]);
        
        // Generate frontend CSS for custom attributes
        $this->generate_frontend_css();
        
        // Custom CSS for the toolbar buttons
        wp_add_inline_style('wp-admin', $this->get_toolbar_css());
    }
    
    /**
     * Add custom toolbar to ACF WYSIWYG fields
     */
    public function add_custom_toolbar($toolbars) {
        // Create enhanced basic toolbar (case-sensitive key!)
        $toolbars['abf_enhanced'] = [
            1 => [
                'bold', 'italic', 'underline',
                'abf_colors', 'abf_typography',
                'alignleft', 'aligncenter', 'alignright',
                'bullist', 'numlist',
                'link', 'unlink',
                'undo', 'redo'
            ]
        ];
        
        return $toolbars;
    }
    
    /**
     * Get CSS for toolbar styling
     */
    private function get_toolbar_css() {
        return '
        .mce-btn.mce-abf-colors i:before,
        .mce-btn.mce-abf-typography i:before {
            font-family: "dashicons";
            font-size: 16px;
        }
        
        .mce-btn.mce-abf-colors i:before {
            content: "\f309"; /* color picker icon */
        }
        
        .mce-btn.mce-abf-typography i:before {
            content: "\f179"; /* typography icon */
        }
        
        .abf-color-dropdown,
        .abf-typography-dropdown {
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 999999;
        }
        
        .abf-dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .abf-dropdown-item:hover {
            background: #f0f0f1;
        }
        
        .abf-color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 2px;
            border: 1px solid #ddd;
            display: inline-block;
        }
        
        .abf-typography-preview {
            font-weight: bold;
            color: #333;
        }
        ';
    }
    
    /**
     * Generate dynamic CSS for frontend display
     */
    private function generate_frontend_css() {
        $css = '';
        
        // Generate CSS for colors
        if (!empty($this->colors)) {
            foreach ($this->colors as $color) {
                $css .= sprintf(
                    '[data-abf-color="%s"] { color: %s !important; }' . "\n",
                    esc_attr($color['name']),
                    esc_attr($color['value'])
                );
            }
        }
        
        // Generate CSS for font sizes
        if (!empty($this->typography['font_sizes'])) {
            foreach ($this->typography['font_sizes'] as $fontSize) {
                $css .= sprintf(
                    '[data-abf-font-size="%s"] { font-size: %spx !important; }' . "\n",
                    esc_attr($fontSize['key']),
                    esc_attr($fontSize['desktop'])
                );
                
                // Add mobile responsive styles
                if (isset($fontSize['mobile'])) {
                    $css .= sprintf(
                        '@media (max-width: 768px) { [data-abf-font-size="%s"] { font-size: %spx !important; } }' . "\n",
                        esc_attr($fontSize['key']),
                        esc_attr($fontSize['mobile'])
                    );
                }
            }
        }
        
        // Generate CSS for font weights
        if (!empty($this->typography['font_weights'])) {
            foreach ($this->typography['font_weights'] as $fontWeight) {
                $css .= sprintf(
                    '[data-abf-font-weight="%s"] { font-weight: %s !important; }' . "\n",
                    esc_attr($fontWeight['key']),
                    esc_attr($fontWeight['value'])
                );
            }
        }
        
        // Output CSS inline for both admin and frontend
        if (!empty($css)) {
            // For admin (editor)
            if (is_admin()) {
                wp_add_inline_style('wp-admin', $css);
            } else {
                // For frontend - enqueue with theme styles
                wp_add_inline_style('wp-block-library', $css);
            }
            
            error_log('ABF Toolbar: Generated frontend CSS (' . strlen($css) . ' chars)');
        }
    }
}

// Initialize the toolbar
new ABF_WYSIWYG_Toolbar(); 