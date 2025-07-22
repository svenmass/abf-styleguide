/**
 * Debug Script for WYSIWYG Toolbar
 * This script helps diagnose what's happening with the toolbar data
 */

(function() {
    'use strict';
    
    console.log('🔍 DEBUG: Toolbar Debug Script loaded');
    
    // Check immediately
    function checkData() {
        console.log('🔍 DEBUG: Checking window.abfToolbarData:', window.abfToolbarData);
        
        if (window.abfToolbarData) {
            console.log('✅ DEBUG: abfToolbarData exists');
            console.log('📊 DEBUG: Colors:', window.abfToolbarData.colors);
            console.log('📊 DEBUG: Typography:', window.abfToolbarData.typography);
            console.log('📊 DEBUG: Colors count:', window.abfToolbarData.colors ? window.abfToolbarData.colors.length : 'undefined');
        } else {
            console.log('❌ DEBUG: abfToolbarData is not available');
        }
        
        // Check if TinyMCE is available
        console.log('🔍 DEBUG: TinyMCE available:', typeof tinymce !== 'undefined');
        
        // Check all global objects
        console.log('🔍 DEBUG: All abf* globals:', Object.keys(window).filter(key => key.toLowerCase().includes('abf')));
    }
    
    // Check immediately
    checkData();
    
    // Check after DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔍 DEBUG: DOM Ready - checking data again');
        checkData();
    });
    
    // Check periodically for 10 seconds
    let checkCount = 0;
    const intervalId = setInterval(function() {
        checkCount++;
        console.log('🔍 DEBUG: Periodic check #' + checkCount);
        checkData();
        
        if (checkCount >= 20) { // Stop after 10 seconds (20 * 500ms)
            clearInterval(intervalId);
            console.log('🔍 DEBUG: Stopping periodic checks');
        }
    }, 500);
    
    // Global function for manual testing
    window.debugABFToolbar = function() {
        console.log('🔍 DEBUG: Manual check triggered');
        checkData();
        
        // Try to manually create a menu
        if (window.abfToolbarData && window.abfToolbarData.colors) {
            console.log('🔧 DEBUG: Attempting to create menu items manually...');
            
            var testMenu = [];
            window.abfToolbarData.colors.forEach(function(color, index) {
                testMenu.push({
                    text: color.name + ' (' + color.value + ')',
                    value: color.value
                });
            });
            
            console.log('✅ DEBUG: Test menu created:', testMenu);
            return testMenu;
        }
    };
    
    console.log('🔍 DEBUG: Use window.debugABFToolbar() to manually test');
})(); 