// TEMPORARY DEBUG CODE - Add to end of functions.php
// Remove after debugging!

add_action('wp_dashboard_setup', function() {
    if (current_user_can('administrator')) {
        wp_add_dashboard_widget('debug_files_widget', '🔍 Server File Debug', 'debug_files_dashboard_widget');
    }
});

function debug_files_dashboard_widget() {
    echo "<style>.debug-good{color:green;} .debug-bad{color:red;} .debug-warn{color:orange;} .debug-info{font-family:monospace; font-size:12px;}</style>";
    echo "<div class='debug-info'>";
    
    // Check functions.php
    echo "<h3>📁 FUNCTIONS.PHP</h3>";
    $functions_file = get_template_directory() . '/functions.php';
    if (file_exists($functions_file)) {
        $content = file_get_contents($functions_file);
        $last_modified = date('Y-m-d H:i:s', filemtime($functions_file));
        $onclick_count = substr_count($content, ' onclick=');
        $onClick_count = substr_count($content, ' onClick=');
        
        echo "<div class='debug-good'>✅ File exists</div>";
        echo "<div>📅 Last modified: {$last_modified}</div>";
        echo "<div class='" . ($onclick_count > 0 ? 'debug-bad' : 'debug-good') . "'>onclick count: {$onclick_count}</div>";
        echo "<div class='" . ($onClick_count > 0 ? 'debug-good' : 'debug-warn') . "'>onClick count: {$onClick_count}</div>";
        
        // Check for onClick fixes
        $has_js_fix = strpos($content, 'onClick="ABF_UserManagement') !== false;
        $has_php_fix = strpos($content, 'admin_enqueue_scripts') !== false;
        
        echo "<div class='" . ($has_js_fix ? 'debug-good' : 'debug-bad') . "'>JS onClick Fix: " . ($has_js_fix ? 'FOUND' : 'MISSING') . "</div>";
        echo "<div class='" . ($has_php_fix ? 'debug-good' : 'debug-bad') . "'>PHP onClick Fix: " . ($has_php_fix ? 'FOUND' : 'MISSING') . "</div>";
    } else {
        echo "<div class='debug-bad'>❌ functions.php NOT FOUND</div>";
    }
    
    // Check critical block templates
    echo "<h3>🧱 CRITICAL BLOCK TEMPLATES</h3>";
    $critical_blocks = [
        'text-block/template.php',
        'hero/template.php',
        'parallax-element/template.php',
        'styleguide-text-element/template.php'
    ];
    
    foreach ($critical_blocks as $block) {
        $file_path = get_template_directory() . '/blocks/' . $block;
        echo "<strong>{$block}:</strong> ";
        
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            $last_modified = date('Y-m-d H:i:s', filemtime($file_path));
            $onclick_count = substr_count($content, ' onclick=');
            $onClick_count = substr_count($content, ' onClick=');
            
            echo "✅ EXISTS | {$last_modified} | ";
            echo "<span class='" . ($onclick_count > 0 ? 'debug-bad' : 'debug-good') . "'>onclick:{$onclick_count}</span> | ";
            echo "<span class='" . ($onClick_count > 0 ? 'debug-good' : 'debug-warn') . "'>onClick:{$onClick_count}</span>";
            
            if ($onclick_count > 0) {
                preg_match('/onclick="[^"]*"/', $content, $matches);
                if (!empty($matches[0])) {
                    echo "<br><span class='debug-bad'>❌ Found: {$matches[0]}</span>";
                }
            }
        } else {
            echo "<span class='debug-bad'>❌ FILE NOT FOUND</span>";
        }
        echo "<br>";
    }
    
    // Check user management files
    echo "<h3>👤 USER MANAGEMENT</h3>";
    $user_files = ['admin-interface.php', 'login-button.php'];
    
    foreach ($user_files as $file) {
        $file_path = get_template_directory() . '/inc/user-management/' . $file;
        echo "<strong>{$file}:</strong> ";
        
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            $last_modified = date('Y-m-d H:i:s', filemtime($file_path));
            $onclick_count = substr_count($content, ' onclick=');
            $onClick_count = substr_count($content, ' onClick=');
            
            echo "✅ EXISTS | {$last_modified} | ";
            echo "<span class='" . ($onclick_count > 0 ? 'debug-bad' : 'debug-good') . "'>onclick:{$onclick_count}</span> | ";
            echo "<span class='" . ($onClick_count > 0 ? 'debug-good' : 'debug-warn') . "'>onClick:{$onClick_count}</span>";
        } else {
            echo "<span class='debug-bad'>❌ NOT FOUND</span>";
        }
        echo "<br>";
    }
    
    // Check wp-config.php
    echo "<h3>⚙️ WP-CONFIG.PHP</h3>";
    $wp_config = ABSPATH . 'wp-config.php';
    if (file_exists($wp_config)) {
        $content = file_get_contents($wp_config);
        $last_modified = date('Y-m-d H:i:s', filemtime($wp_config));
        $has_add_action = strpos($content, 'add_action') !== false;
        
        echo "✅ EXISTS | {$last_modified} | ";
        echo "<span class='" . ($has_add_action ? 'debug-bad' : 'debug-good') . "'>add_action(): " . ($has_add_action ? 'YES (BAD!)' : 'NO (GOOD)') . "</span>";
    } else {
        echo "<span class='debug-bad'>❌ NOT FOUND</span>";
    }
    
    // Cache info
    echo "<h3>🔥 CACHE INFO</h3>";
    echo "OPcache: " . (function_exists('opcache_get_status') && opcache_get_status() ? 'ACTIVE' : 'INACTIVE') . "<br>";
    if (function_exists('opcache_get_status') && opcache_get_status()) {
        echo "<span class='debug-warn'>⚠️ OPcache might cache old files!</span><br>";
    }
    
    // Current theme info
    echo "<h3>🎨 THEME INFO</h3>";
    echo "Active Theme: " . get_stylesheet() . "<br>";
    echo "Theme Directory: " . get_template_directory() . "<br>";
    echo "Server Time: " . date('Y-m-d H:i:s') . "<br>";
    
    echo "</div>";
    
    // Add flush opcache button if needed
    if (function_exists('opcache_get_status') && opcache_get_status() && isset($_GET['flush_opcache'])) {
        opcache_reset();
        echo "<div class='debug-good'><strong>✅ OpCache flushed!</strong></div>";
    }
    
    if (function_exists('opcache_get_status') && opcache_get_status()) {
        $current_url = admin_url('index.php?flush_opcache=1');
        echo "<p><a href='{$current_url}' class='button button-primary'>🔥 Flush OpCache</a></p>";
    }
}

// END TEMPORARY DEBUG CODE 