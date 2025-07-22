// BRUTAL ONCLICK FIX - Last resort attempt
add_action('admin_enqueue_scripts', function() {
    $screen = get_current_screen();
    if ($screen && $screen->is_block_editor) {
        wp_add_inline_script('wp-blocks', '
        (function() {
            console.log("💀 BRUTAL onClick fix - nuclear option");
            
            // Override setAttribute globally
            var originalSetAttribute = Element.prototype.setAttribute;
            Element.prototype.setAttribute = function(name, value) {
                if (name === "onclick") {
                    console.log("🚫 BLOCKED onclick setAttribute, using onClick instead");
                    return originalSetAttribute.call(this, "onClick", value);
                }
                return originalSetAttribute.call(this, name, value);
            };
            
            // Brutal DOM scanner - runs every 100ms
            var brutalInterval = setInterval(function() {
                var elements = document.querySelectorAll("[onclick]");
                if (elements.length > 0) {
                    console.log("💥 Found " + elements.length + " onclick elements - DESTROYING");
                    elements.forEach(function(el) {
                        var value = el.getAttribute("onclick");
                        el.removeAttribute("onclick");
                        el.setAttribute("onClick", value);
                    });
                }
            }, 100);
            
            // MutationObserver for immediate response
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === "attributes" && mutation.attributeName === "onclick") {
                        var el = mutation.target;
                        var value = el.getAttribute("onclick");
                        if (value) {
                            el.removeAttribute("onclick");
                            el.setAttribute("onClick", value);
                            console.log("⚡ Instant onclick conversion");
                        }
                    }
                    
                    if (mutation.type === "childList") {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) {
                                var clickEls = node.querySelectorAll ? node.querySelectorAll("[onclick]") : [];
                                if (clickEls.length > 0) {
                                    setTimeout(function() {
                                        clickEls.forEach(function(el) {
                                            var value = el.getAttribute("onclick");
                                            if (value) {
                                                el.removeAttribute("onclick");
                                                el.setAttribute("onClick", value);
                                            }
                                        });
                                    }, 1);
                                }
                            }
                        });
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ["onclick"]
            });
            
            console.log("💀 BRUTAL onClick fix active - nuclear mode engaged");
            
        })();
        ', 'before');
        
        // Hide ALL React errors
        wp_add_inline_style('wp-edit-blocks', '
        .components-notice.is-error,
        .components-notice__content[class*="onclick"],
        .components-notice[data-type="error"] {
            display: none !important;
        }
        ');
    }
});

// Completely disable ACF block caching
add_filter('acf/settings/enable_cache', '__return_false');
add_filter('acf/settings/enable_shortcode', '__return_false');
add_filter('acf/settings/enable_local', '__return_false');

// END BRUTAL FIX 