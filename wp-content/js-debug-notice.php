// JAVASCRIPT ONCLICK DEBUG - Add to functions.php
add_action('admin_notices', function() {
    if (current_user_can('administrator')) {
        echo '<div class="notice notice-warning" style="padding:10px; max-height:400px; overflow:auto;">';
        echo '<h3>🔍 JAVASCRIPT ONCLICK DEBUG</h3>';
        
        // Check theme JS files
        echo '<h4>🎨 THEME JS FILES</h4>';
        $js_files = [
            '/assets/js/main.js',
            '/assets/js/custom.js',
            '/assets/js/app.js',
            '/assets/js/script.js'
        ];
        
        foreach ($js_files as $js_file) {
            $file_path = get_template_directory() . $js_file;
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                $onclick_count = substr_count($content, 'onclick');
                $onClick_count = substr_count($content, 'onClick');
                
                echo "<p><strong>{$js_file}:</strong> onclick:{$onclick_count} | onClick:{$onClick_count}</p>";
                
                if ($onclick_count > 0) {
                    // Show first few onclick occurrences
                    preg_match_all('/onclick[^;]{0,50}/', $content, $matches);
                    foreach (array_slice($matches[0], 0, 3) as $match) {
                        echo "<code style='color:red;'>❌ {$match}</code><br>";
                    }
                }
            } else {
                echo "<p>{$js_file}: ❌ Not found</p>";
            }
        }
        
        // Check all block templates (not just text-block)
        echo '<h4>🧱 ALL BLOCK TEMPLATES</h4>';
        $blocks_dir = get_template_directory() . '/blocks/';
        if (is_dir($blocks_dir)) {
            $block_dirs = scandir($blocks_dir);
            $onclick_blocks = [];
            
            foreach ($block_dirs as $block_dir) {
                if ($block_dir != '.' && $block_dir != '..' && is_dir($blocks_dir . $block_dir)) {
                    $template_file = $blocks_dir . $block_dir . '/template.php';
                    if (file_exists($template_file)) {
                        $content = file_get_contents($template_file);
                        $onclick_count = substr_count($content, ' onclick=');
                        $onClick_count = substr_count($content, ' onClick=');
                        
                        if ($onclick_count > 0) {
                            $onclick_blocks[] = $block_dir;
                            echo "<p style='color:red;'><strong>{$block_dir}:</strong> ❌ onclick:{$onclick_count} | onClick:{$onClick_count}</p>";
                        } else if ($onClick_count > 0) {
                            echo "<p style='color:green;'><strong>{$block_dir}:</strong> ✅ onclick:0 | onClick:{$onClick_count}</p>";
                        }
                    }
                }
            }
            
            if (count($onclick_blocks) > 0) {
                echo '<p style="color:red; font-weight:bold;">❌ BLOCKS WITH onclick: ' . implode(', ', $onclick_blocks) . '</p>';
            } else {
                echo '<p style="color:green; font-weight:bold;">✅ All blocks use onClick (correct!)</p>';
            }
        }
        
        // Check for potential JavaScript inline code
        echo '<h4>💻 INLINE JAVASCRIPT CHECK</h4>';
        echo '<script>
        // Check for onclick in DOM
        setTimeout(function() {
            var elements = document.querySelectorAll("[onclick]");
            if (elements.length > 0) {
                console.log("🚨 FOUND onclick elements:", elements);
                elements.forEach(function(el, index) {
                    console.log("Element " + index + ":", el.outerHTML.substring(0, 200));
                });
            } else {
                console.log("✅ No onclick elements found in DOM");
            }
        }, 2000);
        </script>';
        echo '<p><strong>Open Developer Console (F12)</strong> and look for onclick elements logged there!</p>';
        
        // Plugin check
        echo '<h4>🔌 ACTIVE PLUGINS</h4>';
        $active_plugins = get_option('active_plugins');
        foreach ($active_plugins as $plugin) {
            if (strpos($plugin, 'acf') !== false) {
                echo "<p style='color:orange;'>⚠️ <strong>ACF Plugin:</strong> {$plugin}</p>";
            } else {
                echo "<p>{$plugin}</p>";
            }
        }
        
        echo '<p><strong>💡 Tip:</strong> ACF Pro might be generating onclick in its own JavaScript!</p>';
        echo '</div>';
    }
});
// END JS DEBUG 