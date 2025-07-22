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

// PRODUCTION ONCLICK FIX - Final solution
add_action('admin_enqueue_scripts', function() {
    $screen = get_current_screen();
    if ($screen && $screen->is_block_editor) {
        wp_add_inline_script('wp-blocks', '
        (function() {
            console.log("🔧 onClick fix starting...");
            
            function fixOnClick() {
                var elements = document.querySelectorAll("[onclick]");
                var fixed = 0;
                
                elements.forEach(function(el) {
                    // Skip WordPress core elements (Object Cache, Comments, etc.)
                    if (el.closest(".object-cache-pro") || 
                        el.closest(".comment-reply") ||
                        el.classList.contains("button-secondary")) {
                        return;
                    }
                    
                    var onclickValue = el.getAttribute("onclick");
                    if (onclickValue) {
                        el.removeAttribute("onclick");
                        el.setAttribute("onClick", onclickValue);
                        fixed++;
                        console.log("✅ Fixed onclick:", el.tagName);
                    }
                });
                
                if (fixed > 0) {
                    console.log("🎯 Fixed " + fixed + " onclick elements");
                }
                
                return fixed;
            }
            
            // Run at different intervals
            setTimeout(fixOnClick, 100);
            setTimeout(fixOnClick, 500);
            setTimeout(fixOnClick, 1000);
            
            // Watch for new elements
            var observer = new MutationObserver(function(mutations) {
                var needsFix = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
                        needsFix = true;
                    }
                });
                
                if (needsFix) {
                    setTimeout(fixOnClick, 50);
                }
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            
            console.log("🚀 onClick fix initialized");
        })();
        ', 'after');
        
        // Hide React error notices
        wp_add_inline_style('wp-edit-blocks', '
        .components-notice.is-error[data-id*="onclick"] {
            display: none !important;
        }
        ');
    }
});

// Disable ACF caching to prevent conflicts
add_filter('acf/settings/enable_cache', '__return_false');
add_filter('acf/settings/enable_shortcode', '__return_false');

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
 * Include User Management System
 * Custom registration, login, and approval system without plugins
 */
require_once get_template_directory() . '/inc/user-management/init.php';

/**
 * Format download links to include file type and size
 * Automatically adds [dokumenttype, größe in kB] to download links
 */
function format_download_links($content) {
    return preg_replace_callback(
        '#<a\s[^>]*href="([^"]+\.(pdf|zip|jpg|jpeg|png|svg|dotx|xls|xlsx))"[^>]*>(.*?)</a>#i',
        function ($matches) {
            $file_url = $matches[1];
            $file_extension = $matches[2];
            $link_text = $matches[3];

            // Convert relative URLs to absolute paths
            if (strpos($file_url, 'http') !== 0) {
                $file_url = site_url($file_url);
            }

            // Get local file path
            $file_path = ABSPATH . str_replace(site_url() . '/', '', $file_url);

            // Get file type and size
            $filetype = strtoupper($file_extension);
            $filesize = 'Unbekannt';
            
            if (file_exists($file_path)) {
                $filesize_bytes = filesize($file_path);
                $filesize = size_format($filesize_bytes);
            }

            // Return formatted link with meta information
            return '<a href="' . esc_url($file_url) . '" download>' . 
                   $link_text . 
                   '<span class="file-meta">[' . $filetype . ', ' . $filesize . ']</span>' .
                   '</a>';
        },
        $content
    );
}
add_filter('the_content', 'format_download_links');

/**
 * Get color value from choice field (centralized function)
 */
function abf_get_styleguide_color_value($color_choice) {
    if (!$color_choice || $color_choice === 'inherit') {
        return '#575756'; // Standard text color
    }
    
    // Try to get dynamic color from colors.json first
    if (function_exists('abf_get_color_value')) {
        $color_value = abf_get_color_value($color_choice);
        if ($color_value) {
            return $color_value;
        }
    }
    
    // Handle fixed brand colors
    if ($color_choice === 'primary') {
        return '#66a98c'; // Primary brand color
    } elseif ($color_choice === 'secondary') {
        return '#c50d14'; // Secondary brand color
    }
    
    // Fallback to CSS variable
    return "var(--color-" . sanitize_title($color_choice) . ")";
}

/**
 * Helper function for file meta information
 * Used in various blocks to display file extension and size
 */
function abf_get_file_meta($file) {
    if (!$file || !isset($file['url'])) {
        return '';
    }
    
    $file_extension = strtoupper(pathinfo($file['url'], PATHINFO_EXTENSION));
    $file_size = isset($file['filesize']) ? round($file['filesize'] / 1024, 0) : 0;
    
    return "<span class=\"file-meta\">[{$file_extension}, {$file_size} kB]</span>";
} 