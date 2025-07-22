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
        
        // Check TinyMCE availability
        if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
            console.log('🔍 DEBUG: TinyMCE available: true');
        } else {
            console.log('🔍 DEBUG: TinyMCE available: false');
        }
        
        // Show all abf* globals
        var abfGlobals = Object.keys(window).filter(function(key) {
            return key.toLowerCase().indexOf('abf') === 0;
        });
        console.log('🔍 DEBUG: All abf* globals:', abfGlobals);
    }
    
    // Check on DOM ready
    jQuery(document).ready(function() {
        console.log('🔍 DEBUG: DOM Ready - checking data again');
        checkData();
        
        // Monitor TinyMCE initialization
        var checkCount = 0;
        var maxChecks = 20;
        
        function periodicCheck() {
            checkCount++;
            console.log('🔍 DEBUG: Periodic check #' + checkCount);
            checkData();
            
            if (checkCount < maxChecks) {
                setTimeout(periodicCheck, 1000);
            } else {
                console.log('🔍 DEBUG: Stopping periodic checks');
            }
        }
        
        setTimeout(periodicCheck, 1000);
    });
    
    // Immediate check
    setTimeout(checkData, 100);
    
    // Global debug function for manual testing
    window.debugABFToolbar = function() {
        console.log('🔧 MANUAL DEBUG: User triggered debug check');
        checkData();
        
        if (window.abfToolbarData && window.abfToolbarData.colors) {
            console.log('📋 DEBUG: Color details:');
            window.abfToolbarData.colors.forEach(function(color, i) {
                console.log('  ' + i + ':', color.name, '=', color.value);
            });
        }
        
        if (window.abfToolbarData && window.abfToolbarData.typography && window.abfToolbarData.typography.font_sizes) {
            console.log('📋 DEBUG: Font size details:');
            window.abfToolbarData.typography.font_sizes.forEach(function(size, i) {
                console.log('  ' + i + ':', size.name, '=', size.value + 'px');
            });
        }
    };
    
    console.log('🔍 DEBUG: Use window.debugABFToolbar() to manually test');
    
    // Create a global utility object
    window.ABFToolbarUtils = {
        checkData: checkData,
        debugAll: window.debugABFToolbar
    };
    
})(); 