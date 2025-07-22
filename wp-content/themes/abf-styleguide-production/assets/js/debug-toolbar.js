/**
 * Debug Script for WYSIWYG Toolbar
 * This script helps diagnose what's happening with the toolbar data
 */

(function() {
    'use strict';
    
    
    // Check immediately
    function checkData() {
        
        if (window.abfToolbarData) {
        } else {
        }
        
        // Check if TinyMCE is available
        
        // Check all global objects
    }
    
    // Check immediately
    checkData();
    
    // Check after DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        checkData();
    });
    
    // Check periodically for 10 seconds
    let checkCount = 0;
    const intervalId = setInterval(function() {
        checkCount++;
        checkData();
        
        if (checkCount >= 20) { // Stop after 10 seconds (20 * 500ms)
            clearInterval(intervalId);
        }
    }, 500);
    
    // Global function for manual testing
    window.debugABFToolbar = function() {
        checkData();
        
        // Try to manually create a menu
        if (window.abfToolbarData && window.abfToolbarData.colors) {
            
            var testMenu = [];
            window.abfToolbarData.colors.forEach(function(color, index) {
                testMenu.push({
                    text: color.name + ' (' + color.value + ')',
                    value: color.value
                });
            });
            
            return testMenu;
        }
    };
    
})(); 