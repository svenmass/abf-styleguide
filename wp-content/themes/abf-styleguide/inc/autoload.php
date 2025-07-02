<?php
/**
 * Autoload PHP Files
 * Automatically loads all PHP files in the inc directory
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Autoload all PHP files in inc directory
 */
function abf_autoload_inc_files() {
    $inc_dir = get_template_directory() . '/inc/';
    
    // Get all PHP files in inc directory
    $php_files = glob($inc_dir . '*.php');
    
    foreach ($php_files as $file) {
        // Skip this autoload file
        if (basename($file) === 'autoload.php') {
            continue;
        }
        
        // Include the file
        require_once $file;
    }
}

// Load all inc files
abf_autoload_inc_files(); 