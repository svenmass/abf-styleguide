<?php
/**
 * Debug Functions (temporary)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Debug ACF Field Groups
 */
function abf_debug_field_groups() {
    if (!function_exists('acf_get_field_groups')) {
        return;
    }
    
    $field_groups = acf_get_field_groups();
    
    echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 1px solid #ccc;">';
    echo '<h3>Debug: ACF Field Groups</h3>';
    echo '<p>Anzahl gefundener Field Groups: ' . count($field_groups) . '</p>';
    
    foreach ($field_groups as $field_group) {
        echo '<div style="margin: 10px 0; padding: 10px; background: white; border: 1px solid #ddd;">';
        echo '<strong>Title:</strong> ' . $field_group['title'] . '<br>';
        echo '<strong>Key:</strong> ' . $field_group['key'] . '<br>';
        echo '<strong>Location:</strong> ';
        if (isset($field_group['location'])) {
            foreach ($field_group['location'] as $location_group) {
                foreach ($location_group as $location) {
                    echo $location['param'] . ' = ' . $location['value'] . ' ';
                }
            }
        }
        echo '</div>';
    }
    
    echo '</div>';
}

// Debug disabled for production
// add_action('admin_footer', 'abf_debug_field_groups'); 