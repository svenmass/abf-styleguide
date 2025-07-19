<?php
/**
 * Manual Typography Initialization Script
 * Run this once to initialize typography settings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

echo "<h2>🎯 Manual Typography Initialization</h2>";

// Check if ACF is available
if (!function_exists('update_field')) {
    echo "<p style='color: red;'>❌ ACF not available!</p>";
    exit;
}

echo "<p>✅ ACF available</p>";

// Define default font sizes (exactly from typography.json)
$default_font_sizes = array(
    array('key' => '4xl', 'label' => '72px - 4XL', 'desktop' => '72', 'tablet' => '60', 'mobile' => '54'),
    array('key' => '3xl', 'label' => '60px - 3XL', 'desktop' => '60', 'tablet' => '48', 'mobile' => '40'),
    array('key' => '2xl', 'label' => '48px - 2XL', 'desktop' => '48', 'tablet' => '40', 'mobile' => '32'),
    array('key' => 'xl', 'label' => '36px - Standard H1', 'desktop' => '36', 'tablet' => '30', 'mobile' => '24'),
    array('key' => 'lg', 'label' => '24px - Standard H2', 'desktop' => '24', 'tablet' => '20', 'mobile' => '18'),
    array('key' => 'md', 'label' => '18px - Standard Body', 'desktop' => '18', 'tablet' => '16', 'mobile' => '14'),
    array('key' => 'sm', 'label' => '16px - Small Body', 'desktop' => '16', 'tablet' => '14', 'mobile' => '12'),
    array('key' => 'xs', 'label' => '12px - Small', 'desktop' => '12', 'tablet' => '10', 'mobile' => '9'),
);

// Define default font weights
$default_font_weights = array(
    array('key' => 'light', 'label' => 'Light (300)', 'value' => '300'),
    array('key' => 'regular', 'label' => 'Regular (400)', 'value' => '400'),
    array('key' => 'medium', 'label' => 'Medium (500)', 'value' => '500'),
    array('key' => 'semibold', 'label' => 'Semibold (600)', 'value' => '600'),
    array('key' => 'bold', 'label' => 'Bold (700)', 'value' => '700'),
);

echo "<h3>📝 Setting Font Sizes...</h3>";
$result1 = update_field('font_sizes', $default_font_sizes, 'option');
echo "<p>Font Sizes Result: " . ($result1 ? "✅ SUCCESS" : "❌ FAILED") . "</p>";

echo "<h3>⚖️ Setting Font Weights...</h3>";
$result2 = update_field('font_weights', $default_font_weights, 'option');
echo "<p>Font Weights Result: " . ($result2 ? "✅ SUCCESS" : "❌ FAILED") . "</p>";

// Verify what was saved
echo "<h3>🔍 Verification:</h3>";
$saved_sizes = get_field('font_sizes', 'option');
$saved_weights = get_field('font_weights', 'option');

echo "<p>Font Sizes Count: " . (is_array($saved_sizes) ? count($saved_sizes) : '0') . "</p>";
echo "<p>Font Weights Count: " . (is_array($saved_weights) ? count($saved_weights) : '0') . "</p>";

// Update typography.json
$typography_file = get_template_directory() . '/typography.json';
$typography_data = array(
    'font_sizes' => $saved_sizes ?: $default_font_sizes,
    'font_weights' => $saved_weights ?: $default_font_weights,
    'updated' => current_time('mysql'),
);

file_put_contents($typography_file, json_encode($typography_data, JSON_PRETTY_PRINT));
echo "<p>✅ typography.json updated</p>";

echo "<h3>🎉 FERTIG!</h3>";
echo "<p><a href='" . admin_url('admin.php?page=theme-settings') . "'>→ Zu Theme Settings</a></p>";
?> 