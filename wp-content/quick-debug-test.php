// QUICK DEBUG TEST - Add to functions.php temporarily
add_action('admin_notices', function() {
    if (current_user_can('administrator') && is_admin()) {
        echo '<div class="notice notice-info"><p>';
        echo '<strong>🔍 QUICK DEBUG TEST:</strong><br>';
        
        // Check text-block template
        $template_path = get_template_directory() . '/blocks/text-block/template.php';
        if (file_exists($template_path)) {
            $content = file_get_contents($template_path);
            $onclick_count = substr_count($content, ' onclick=');
            $onClick_count = substr_count($content, ' onClick=');
            $last_modified = date('Y-m-d H:i:s', filemtime($template_path));
            
            echo "text-block/template.php: FOUND | {$last_modified} | onclick:{$onclick_count} | onClick:{$onClick_count}<br>";
            
            if ($onclick_count > 0) {
                echo '<span style="color:red;">❌ STILL HAS onclick - Files not uploaded correctly!</span><br>';
            } else {
                echo '<span style="color:green;">✅ onclick fixed!</span><br>';
            }
        } else {
            echo '<span style="color:red;">❌ text-block/template.php NOT FOUND</span><br>';
        }
        
        // Check wp-config
        $wp_config = ABSPATH . 'wp-config.php';
        if (file_exists($wp_config)) {
            $content = file_get_contents($wp_config);
            $has_add_action = strpos($content, 'add_action') !== false;
            echo "wp-config.php: " . ($has_add_action ? '<span style="color:red;">❌ STILL HAS add_action!</span>' : '<span style="color:green;">✅ Clean</span>') . '<br>';
        }
        
        echo "Theme: " . get_stylesheet() . " | Server: " . date('Y-m-d H:i:s');
        echo '</p></div>';
    }
});
// END QUICK DEBUG 