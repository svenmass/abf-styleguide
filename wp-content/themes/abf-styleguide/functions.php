<?php
/**
 * ABF Styleguide Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load ACF
if (class_exists('ACF')) {
    // ACF is already loaded
} else {
    // Load ACF from plugins directory
    require_once get_template_directory() . '/../plugins/advanced-custom-fields-pro/acf.php';
}

// Load modular PHP files
require_once get_template_directory() . '/inc/autoload.php';

// Increase upload limits for large files (videos, images)
function abf_increase_upload_limits() {
    @ini_set('upload_max_filesize', '128M');
    @ini_set('post_max_size', '128M');
    @ini_set('max_file_uploads', '20');
    @ini_set('max_execution_time', '300');
    @ini_set('max_input_time', '300');
    @ini_set('memory_limit', '512M');
    @ini_set('max_input_vars', '3000');
    @ini_set('max_input_nesting_level', '64');
}
add_action('init', 'abf_increase_upload_limits');

/**
 * Debug settings (disabled in production)
 */
function abf_enable_debug() {
    // Debug settings disabled for production
    // Enable in development by uncommenting:
    /*
    if (!defined('WP_DEBUG')) {
        define('WP_DEBUG', true);
    }
    if (!defined('WP_DEBUG_LOG')) {
        define('WP_DEBUG_LOG', true);
    }
    if (!defined('WP_DEBUG_DISPLAY')) {
        define('WP_DEBUG_DISPLAY', false); // Never show errors in frontend
    }
    */
}

/**
 * Include User Management System
 * Custom registration, login, and approval system without plugins
 */
require_once get_template_directory() . '/inc/user-management/init.php';

/**
 * Format download links to include file type and size
 * Automatically adds [dokumenttype, größe in kB] to download links
 */
function format_download_links($content) {
    return preg_replace_callback(
        '#<a\s[^>]*href="([^"]+\.(pdf|zip|jpg|jpeg|png|svg|dotx|xls|xlsx))"[^>]*>(.*?)</a>#i',
        function ($matches) {
            $file_url = $matches[1];
            $file_extension = $matches[2];
            $link_text = $matches[3];

            // Convert relative URLs to absolute paths
            if (strpos($file_url, 'http') !== 0) {
                $file_url = site_url($file_url);
            }

            // Get local file path
            $file_path = ABSPATH . str_replace(site_url() . '/', '', $file_url);

            // Get file type and size
            $filetype = strtoupper($file_extension);
            $filesize = 'Unbekannt';
            
            if (file_exists($file_path)) {
                $filesize_bytes = filesize($file_path);
                $filesize = size_format($filesize_bytes);
            }

            // Return formatted link with meta information
            return '<a href="' . esc_url($file_url) . '" download>' . 
                   $link_text . 
                   '<span class="file-meta">[' . $filetype . ', ' . $filesize . ']</span>' .
                   '</a>';
        },
        $content
    );
}
add_filter('the_content', 'format_download_links');

/**
 * Get color value from choice field (centralized function)
 */
function abf_get_styleguide_color_value($color_choice) {
    if (!$color_choice || $color_choice === 'inherit') {
        return 'inherit';
    }
    
    // Handle basic colors
    if ($color_choice === 'white') {
        return '#ffffff';
    } elseif ($color_choice === 'black') {
        return '#000000';
    }
    
    // Try to get dynamic color from colors.json
    if (function_exists('abf_get_color_value')) {
        $color_value = abf_get_color_value($color_choice);
        if ($color_value) {
            return $color_value;
        }
    }
    
    // Handle primary and secondary colors with fallbacks
    if ($color_choice === 'primary') {
        return '#66a98c'; // ABF Grün from colors.json
    } elseif ($color_choice === 'secondary') {
        return '#c50d14'; // ABF Rot from colors.json
    }
    
    // Fallback to CSS variable
    return "var(--color-" . sanitize_title($color_choice) . ")";
}


