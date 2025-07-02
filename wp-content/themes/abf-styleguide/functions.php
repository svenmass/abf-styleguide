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
    @ini_set('memory_limit', '256M');
}
add_action('init', 'abf_increase_upload_limits');

// --- TEST: Minimalbeispiel für ACF Block + Field Group (mit JSX im Content-Bereich) ---
// (entfernt)
// --- ENDE TEST ---


