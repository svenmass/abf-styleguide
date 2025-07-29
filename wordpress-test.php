<?php
/**
 * WordPress Functionality Test
 * Upload this to WordPress root and call via: /wordpress-test.php?test=debug
 */

if (!isset($_GET['test'])) {
    die('Add ?test=debug to URL');
}

// Load WordPress
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo "<h1>🔍 WordPress Functionality Test</h1>";
echo "<style>body{font-family:monospace;} .good{color:green;} .bad{color:red;}</style>";

// Test 1: Basic WordPress
echo "<h2>📝 WordPress Core</h2>";
echo "<div class='" . (function_exists('wp_head') ? 'good' : 'bad') . "'>WordPress loaded: " . (function_exists('wp_head') ? 'YES' : 'NO') . "</div>";
echo "<div>WordPress Version: " . get_bloginfo('version') . "</div>";

// Test 2: File Permissions
echo "<h2>📁 File Permissions</h2>";
$debug_log = WP_CONTENT_DIR . '/debug.log';
$can_write = is_writable(WP_CONTENT_DIR);
echo "<div class='" . ($can_write ? 'good' : 'bad') . "'>wp-content writable: " . ($can_write ? 'YES' : 'NO') . "</div>";

if (file_exists($debug_log)) {
    $can_write_log = is_writable($debug_log);
    echo "<div class='" . ($can_write_log ? 'good' : 'bad') . "'>debug.log writable: " . ($can_write_log ? 'YES' : 'NO') . "</div>";
}

// Test 3: WordPress Constants
echo "<h2>⚙️ WordPress Configuration</h2>";
echo "<div class='" . (WP_DEBUG ? 'good' : 'bad') . "'>WP_DEBUG: " . (WP_DEBUG ? 'ON' : 'OFF') . "</div>";
echo "<div class='" . (WP_DEBUG_LOG ? 'good' : 'bad') . "'>WP_DEBUG_LOG: " . (WP_DEBUG_LOG ? 'ON' : 'OFF') . "</div>";
echo "<div>Memory Limit: " . ini_get('memory_limit') . "</div>";
echo "<div>Max Execution Time: " . ini_get('max_execution_time') . "</div>";

// Test 4: AJAX/REST API
echo "<h2>🌐 AJAX & REST API</h2>";
$ajax_url = admin_url('admin-ajax.php');
echo "<div>AJAX URL: {$ajax_url}</div>";

$rest_url = rest_url();
echo "<div>REST URL: {$rest_url}</div>";

// Test 5: Current User & Capabilities
echo "<h2>👤 User & Permissions</h2>";
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    echo "<div class='good'>User logged in: " . $current_user->user_login . "</div>";
    echo "<div class='" . (current_user_can('edit_posts') ? 'good' : 'bad') . "'>Can edit posts: " . (current_user_can('edit_posts') ? 'YES' : 'NO') . "</div>";
    echo "<div class='" . (current_user_can('publish_posts') ? 'good' : 'bad') . "'>Can publish posts: " . (current_user_can('publish_posts') ? 'YES' : 'NO') . "</div>";
} else {
    echo "<div class='bad'>No user logged in</div>";
}

// Test 6: Nonces
echo "<h2>🔒 Security (Nonces)</h2>";
$nonce = wp_create_nonce('test_nonce');
echo "<div class='good'>Can create nonce: " . ($nonce ? 'YES' : 'NO') . "</div>";
echo "<div>Test Nonce: {$nonce}</div>";

// Test 7: Database
echo "<h2>🗄️ Database</h2>";
global $wpdb;
$db_test = $wpdb->get_var("SELECT 1");
echo "<div class='" . ($db_test == 1 ? 'good' : 'bad') . "'>Database connection: " . ($db_test == 1 ? 'OK' : 'FAILED') . "</div>";

// Test 8: Theme
echo "<h2>🎨 Active Theme</h2>";
echo "<div>Theme: " . get_stylesheet() . "</div>";
echo "<div>Theme Path: " . get_template_directory() . "</div>";
$functions_exists = file_exists(get_template_directory() . '/functions.php');
echo "<div class='" . ($functions_exists ? 'good' : 'bad') . "'>functions.php: " . ($functions_exists ? 'EXISTS' : 'MISSING') . "</div>";

// Test 9: Write Test
echo "<h2>✍️ Write Test</h2>";
try {
    error_log("Test from wordpress-test.php - " . date('Y-m-d H:i:s'));
    echo "<div class='good'>Error log write: SUCCESSFUL</div>";
} catch (Exception $e) {
    echo "<div class='bad'>Error log write: FAILED - " . $e->getMessage() . "</div>";
}

echo "<hr><p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>";
?> 