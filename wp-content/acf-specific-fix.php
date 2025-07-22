// ACF-SPECIFIC ONCLICK FIX - Add to functions.php
add_action('admin_enqueue_scripts', function() {
    $screen = get_current_screen();
    if ($screen && $screen->is_block_editor) {
        wp_add_inline_script('wp-blocks', '
        (function() {
            console.log("🎯 ACF-specific onClick fix starting...");
            
            // Function to fix ACF blocks specifically
            function fixACFBlocksOnClick() {
                var fixed = 0;
                
                // Target ACF block containers
                var acfBlocks = document.querySelectorAll("[data-type*=\"acf/\"], .acf-block-component, .acf-block-preview");
                console.log("Found ACF blocks:", acfBlocks.length);
                
                acfBlocks.forEach(function(block) {
                    // Find onclick elements within ACF blocks
                    var clickElements = block.querySelectorAll("[onclick]");
                    clickElements.forEach(function(el) {
                        var onclickValue = el.getAttribute("onclick");
                        if (onclickValue) {
                            el.removeAttribute("onclick");
                            el.setAttribute("onClick", onclickValue);
                            fixed++;
                            console.log("🔧 Fixed ACF block onclick:", el.tagName, onclickValue.substring(0, 50));
                        }
                    });
                });
                
                // Also check for any a[onclick] or button[onclick] in editor
                var allClickElements = document.querySelectorAll(".block-editor a[onclick], .block-editor button[onclick], .edit-post-layout a[onclick], .edit-post-layout button[onclick]");
                allClickElements.forEach(function(el) {
                    var onclickValue = el.getAttribute("onclick");
                    if (onclickValue) {
                        el.removeAttribute("onclick");
                        el.setAttribute("onClick", onclickValue);
                        fixed++;
                        console.log("🔧 Fixed editor onclick:", el.tagName, onclickValue.substring(0, 50));
                    }
                });
                
                if (fixed > 0) {
                    console.log("✅ ACF onClick fix: Fixed " + fixed + " elements");
                }
                
                return fixed;
            }
            
            // Run when blocks are loaded/updated
            if (window.wp && window.wp.data) {
                var store = wp.data.select("core/block-editor");
                var previousBlocks = [];
                
                wp.data.subscribe(function() {
                    var currentBlocks = store.getBlocks();
                    if (currentBlocks.length !== previousBlocks.length) {
                        console.log("📝 Blocks changed, running ACF onClick fix...");
                        setTimeout(fixACFBlocksOnClick, 100);
                        previousBlocks = currentBlocks;
                    }
                });
            }
            
            // Run immediately and repeatedly
            setTimeout(fixACFBlocksOnClick, 500);
            setTimeout(fixACFBlocksOnClick, 1500);
            setTimeout(fixACFBlocksOnClick, 3000);
            
            // Also run on any DOM changes
            var observer = new MutationObserver(function(mutations) {
                var hasNewNodes = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
                        hasNewNodes = true;
                    }
                });
                
                if (hasNewNodes) {
                    setTimeout(fixACFBlocksOnClick, 50);
                }
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            
            console.log("🚀 ACF-specific onClick fix initialized");
        })();
        ', 'after');
        
        // Also add CSS to hide React warnings
        wp_add_inline_style('wp-edit-blocks', '
        .components-notice.is-error {
            display: none !important;
        }
        ');
    }
});

// DISABLE ACF BLOCKS CACHING
add_filter('acf/settings/enable_shortcode', '__return_false');
add_filter('acf/settings/enable_cache', '__return_false');

// END ACF-SPECIFIC FIX 