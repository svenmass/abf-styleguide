<?php
/**
 * SERVER FILE VERIFICATION SCRIPT
 * Upload this to wp-content/ and call via browser: /wp-content/debug-server-files.php
 */

// Security check
if (!isset($_GET['debug']) || $_GET['debug'] !== 'verify-files') {
    die('Access denied. Add ?debug=verify-files to URL');
}

echo "<h1>🔍 SERVER FILE VERIFICATION</h1>";
echo "<style>body{font-family:monospace;} .good{color:green;} .bad{color:red;} .warn{color:orange;}</style>";

// Check functions.php
echo "<h2>📁 FUNCTIONS.PHP</h2>";
$functions_file = __DIR__ . '/themes/abf-styleguide-production/functions.php';
if (file_exists($functions_file)) {
    $content = file_get_contents($functions_file);
    $last_modified = date('Y-m-d H:i:s', filemtime($functions_file));
    $onclick_count = substr_count($content, ' onclick=');
    $onClick_count = substr_count($content, ' onClick=');
    
    echo "<div class='good'>✅ File exists</div>";
    echo "<div>📅 Last modified: {$last_modified}</div>";
    echo "<div class='" . ($onclick_count > 0 ? 'bad' : 'good') . "'>onclick count: {$onclick_count}</div>";
    echo "<div class='" . ($onClick_count > 0 ? 'good' : 'warn') . "'>onClick count: {$onClick_count}</div>";
    
    // Check for onClick fixes
    $has_js_fix = strpos($content, 'onClick="ABF_UserManagement') !== false;
    $has_php_fix = strpos($content, 'admin_enqueue_scripts') !== false;
    
    echo "<div class='" . ($has_js_fix ? 'good' : 'bad') . "'>JS onClick Fix: " . ($has_js_fix ? 'FOUND' : 'MISSING') . "</div>";
    echo "<div class='" . ($has_php_fix ? 'good' : 'bad') . "'>PHP onClick Fix: " . ($has_php_fix ? 'FOUND' : 'MISSING') . "</div>";
} else {
    echo "<div class='bad'>❌ functions.php NOT FOUND</div>";
}

// Check critical block templates
echo "<h2>🧱 BLOCK TEMPLATES</h2>";
$critical_blocks = [
    'text-block/template.php',
    'hero/template.php',
    'parallax-element/template.php',
    'styleguide-text-element/template.php'
];

foreach ($critical_blocks as $block) {
    $file_path = __DIR__ . '/themes/abf-styleguide-production/blocks/' . $block;
    echo "<h3>📄 {$block}</h3>";
    
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        $last_modified = date('Y-m-d H:i:s', filemtime($file_path));
        $onclick_count = substr_count($content, ' onclick=');
        $onClick_count = substr_count($content, ' onClick=');
        
        echo "<div class='good'>✅ File exists</div>";
        echo "<div>📅 Last modified: {$last_modified}</div>";
        echo "<div class='" . ($onclick_count > 0 ? 'bad' : 'good') . "'>onclick count: {$onclick_count}</div>";
        echo "<div class='" . ($onClick_count > 0 ? 'good' : 'warn') . "'>onClick count: {$onClick_count}</div>";
        
        // Show sample of onclick usage
        if ($onclick_count > 0) {
            preg_match_all('/onclick="[^"]*"/', $content, $matches);
            echo "<div class='bad'>❌ FOUND onclick: " . implode(', ', array_slice($matches[0], 0, 3)) . "</div>";
        }
        
        if ($onClick_count > 0) {
            preg_match_all('/onClick="[^"]*"/', $content, $matches);
            echo "<div class='good'>✅ FOUND onClick: " . implode(', ', array_slice($matches[0], 0, 3)) . "</div>";
        }
    } else {
        echo "<div class='bad'>❌ File NOT FOUND</div>";
    }
    echo "<hr>";
}

// Check user management files
echo "<h2>👤 USER MANAGEMENT</h2>";
$user_files = [
    'admin-interface.php',
    'login-button.php'
];

foreach ($user_files as $file) {
    $file_path = __DIR__ . '/themes/abf-styleguide-production/inc/user-management/' . $file;
    echo "<h3>📄 {$file}</h3>";
    
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        $last_modified = date('Y-m-d H:i:s', filemtime($file_path));
        $onclick_count = substr_count($content, ' onclick=');
        $onClick_count = substr_count($content, ' onClick=');
        
        echo "<div class='good'>✅ File exists</div>";
        echo "<div>📅 Last modified: {$last_modified}</div>";
        echo "<div class='" . ($onclick_count > 0 ? 'bad' : 'good') . "'>onclick count: {$onclick_count}</div>";
        echo "<div class='" . ($onClick_count > 0 ? 'good' : 'warn') . "'>onClick count: {$onClick_count}</div>";
    } else {
        echo "<div class='bad'>❌ File NOT FOUND</div>";
    }
    echo "<hr>";
}

// Check wp-config.php
echo "<h2>⚙️ WP-CONFIG.PHP</h2>";
$wp_config = dirname(__DIR__) . '/wp-config.php';
if (file_exists($wp_config)) {
    $content = file_get_contents($wp_config);
    $last_modified = date('Y-m-d H:i:s', filemtime($wp_config));
    $has_add_action = strpos($content, 'add_action') !== false;
    
    echo "<div class='good'>✅ File exists</div>";
    echo "<div>📅 Last modified: {$last_modified}</div>";
    echo "<div class='" . ($has_add_action ? 'bad' : 'good') . "'>Contains add_action(): " . ($has_add_action ? 'YES (BAD!)' : 'NO (GOOD)') . "</div>";
    
    if ($has_add_action) {
        echo "<div class='bad'>❌ wp-config.php still contains WordPress functions!</div>";
    }
} else {
    echo "<div class='bad'>❌ wp-config.php NOT FOUND</div>";
}

// Check PHP OpCode Cache
echo "<h2>🔥 SERVER CACHE INFO</h2>";
echo "<div>OPcache enabled: " . (function_exists('opcache_get_status') && opcache_get_status() ? 'YES' : 'NO') . "</div>";
if (function_exists('opcache_get_status') && opcache_get_status()) {
    echo "<div class='warn'>⚠️ OPcache is active - files might be cached!</div>";
    echo "<div><a href='?debug=verify-files&flush_opcache=1' style='color:red;'>🔥 FLUSH OPCACHE</a></div>";
}

// Flush OpCache if requested
if (isset($_GET['flush_opcache']) && function_exists('opcache_reset')) {
    opcache_reset();
    echo "<div class='good'>✅ OpCache flushed!</div>";
}

echo "<hr><h2>🎯 NEXT STEPS</h2>";
echo "<p>1. If wp-config.php still has add_action() → Upload wp-config-final.php</p>";
echo "<p>2. If onclick count > 0 → Re-upload block templates</p>";
echo "<p>3. If OpCache active → Click flush link above</p>";

// Show current time for reference
echo "<hr><p><strong>Server time:</strong> " . date('Y-m-d H:i:s') . "</p>";
?> 