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
 * Enable WordPress Debug for better error reporting
 */
function abf_enable_debug() {
    if (!defined('WP_DEBUG')) {
        define('WP_DEBUG', true);
    }
    if (!defined('WP_DEBUG_LOG')) {
        define('WP_DEBUG_LOG', true);
    }
    if (!defined('WP_DEBUG_DISPLAY')) {
        define('WP_DEBUG_DISPLAY', true);
    }
}

// --- TEST: Minimalbeispiel für ACF Block + Field Group (mit JSX im Content-Bereich) ---
// (entfernt)
// --- ENDE TEST ---


