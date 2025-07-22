// AGGRESSIVE ONCLICK FIX - Replace in functions.php
add_action('admin_enqueue_scripts', function() {
    // Only in Gutenberg editor
    $screen = get_current_screen();
    if ($screen && $screen->is_block_editor) {
        wp_add_inline_script('wp-blocks', '
        (function() {
            console.log("🔧 Starting aggressive onClick fix...");
            
            // Function to convert onclick to onClick
            function convertOnclickToOnClick() {
                var elements = document.querySelectorAll("[onclick]");
                var converted = 0;
                
                elements.forEach(function(el) {
                    var onclickValue = el.getAttribute("onclick");
                    if (onclickValue) {
                        // Remove onclick and add onClick
                        el.removeAttribute("onclick");
                        el.setAttribute("onClick", onclickValue);
                        converted++;
                        console.log("✅ Converted onclick to onClick:", el.tagName, onclickValue.substring(0, 50));
                    }
                });
                
                if (converted > 0) {
                    console.log("🎯 Converted " + converted + " onclick to onClick");
                }
                
                return converted;
            }
            
            // Run immediately
            setTimeout(convertOnclickToOnClick, 100);
            
            // Run after DOM changes
            var observer = new MutationObserver(function(mutations) {
                var needsConversion = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === "childList") {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) {
                                if (node.hasAttribute && node.hasAttribute("onclick")) {
                                    needsConversion = true;
                                }
                                var clickElements = node.querySelectorAll ? node.querySelectorAll("[onclick]") : [];
                                if (clickElements.length > 0) {
                                    needsConversion = true;
                                }
                            }
                        });
                    }
                    if (mutation.type === "attributes" && mutation.attributeName === "onclick") {
                        needsConversion = true;
                    }
                });
                
                if (needsConversion) {
                    setTimeout(convertOnclickToOnClick, 10);
                }
            });
            
            // Observe everything
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ["onclick"]
            });
            
            // Also run when ACF blocks load
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(convertOnclickToOnClick, 500);
                setTimeout(convertOnclickToOnClick, 1000);
                setTimeout(convertOnclickToOnClick, 2000);
            });
            
            // Run when Gutenberg loads
            if (window.wp && window.wp.data) {
                window.wp.data.subscribe(function() {
                    setTimeout(convertOnclickToOnClick, 50);
                });
            }
            
            console.log("🚀 Aggressive onClick fix initialized");
        })();
        ', 'before');
    }
});

// BACKUP: HTML Output Buffer Fix
add_action('init', function() {
    if (is_admin()) {
        ob_start(function($buffer) {
            // Only in admin area
            if (strpos($buffer, 'wp-admin') !== false || strpos($buffer, 'post.php') !== false) {
                // Replace onclick= with onClick= in HTML output
                $buffer = preg_replace('/\s+onclick=/', ' onClick=', $buffer);
                console.log("🔄 HTML buffer onClick conversion applied");
            }
            return $buffer;
        });
    }
});

// Clean buffer on shutdown
add_action('shutdown', function() {
    if (ob_get_level() > 0) {
        ob_end_flush();
    }
});
// END AGGRESSIVE FIX 